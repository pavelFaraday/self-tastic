<?php

namespace WPSynchro;

use WPSynchro\Database\DatabaseBackup;
use WPSynchro\Initiate\InitiateSync;
use WPSynchro\Masterdata\MasterdataSync;
use WPSynchro\Utilities\Actions;

/**
 * Class for controlling the migration flow (main controller)
 * Called from API service, for both the worker thread and the status thread
 *
 * @since 1.0.0
 */
class MigrationController
{

    // General data
    public $migration_id = 0;
    public $job_id = 0;
    // Objects
    public $job = null;
    public $migration = null;
    // Timer
    public $timer = null;
    // Helpers
    public $common = null;
    // Errors and warnings
    public $errors = [];
    public $warnings = [];
    public $logger = null;

    /**
     * Setup the data needed for migration, needed for both worker and status thread
     * @since 1.0.0
     */
    public function setup($migration_id, $job_id)
    {
        global $wpsynchro_container;

        // Get sync timer
        $this->timer = $wpsynchro_container->get("class.SyncTimerList");
        $this->timer->init();

        // Set migration and job id
        $this->migration_id = $migration_id;
        $this->job_id = $job_id;

        // Common
        $this->common = new CommonFunctions();

        // Init logging
        $this->logger = $wpsynchro_container->get("class.Logger");
        $this->logger->setFileName($this->common->getLogFilename($this->job_id));

        // Get job data
        $this->job = $wpsynchro_container->get('class.Job');
        $this->job->load($this->migration_id, $this->job_id);

        // Get migration
        $MigrationFactory = new MigrationFactory();;
        $this->migration = $MigrationFactory->retrieveMigration($this->migration_id);

        // Load actions
        $actions = new Actions();
        $actions->loadActions();
    }

    /**
     * Run migration
     * @since 1.0.0
     */
    public function runmigration()
    {
        $result = $this->getResult();

        if ($this->job == null) {
            return null;
        }

        // Handle job locking
        if (isset($this->job->run_lock) && $this->job->run_lock === true) {
            // Ohhh noes, already running
            $errormsg = __('Job is already running or error has happened - Check PHP error logs', 'wpsynchro');
            $result->errors[] = $errormsg;
            $this->logger->log("CRITICAL", $errormsg);
            return $result;
        }

        // If we are completed, just return
        if ($this->job->is_completed) {
            return $result;
        }

        // Set lock in job
        $this->job->run_lock = true;
        $this->job->run_lock_timer = time();
        $this->job->run_lock_problem_time = time() + ceil($this->common->getPHPMaxExecutionTime() * 1.5); // Status thread will check if this time has passed (aka the migration thread has stopped
        $this->job->save();

        // Reset full time frame request
        $this->job->request_full_timeframe = false;

        // Start jobs
        $lastrun_time = 0;
        while ($this->timer->shouldContinueWithLastrunTime($lastrun_time)) {
            $timer_start_identifier = $this->timer->startTimer("migrate-controller", "while", "lastrun");
            $allotted_time_for_subjob = $this->timer->getRemainingSyncTime();

            $this->logger->log("INFO", "Starting migration loop - With allotted time: " . $allotted_time_for_subjob . " seconds");

            // If run requires full time frame
            if ($this->job->request_full_timeframe) {
                break;
            }

            // Handle the steps
            if (!$this->job->initiation_completed) {
                // Initiation
                $this->handleInitiationStep();
                break;
            } elseif (!$this->job->masterdata_completed) {
                // Metadata
                $this->handleStepMasterdata();
                break;
            } elseif (!$this->job->database_backup_completed) {
                // Database backup
                if ($this->migration->sync_database && $this->migration->db_make_backup) {
                    $this->handleStepDatabaseBackup();
                } else {
                    $this->job->database_backup_progress = 100;
                    $this->job->database_backup_completed = true;
                }
                break;
            } elseif (!$this->job->database_completed) {
                // Database
                if ($this->migration->sync_database) {
                    $this->handleStepDatabase();
                } else {
                    $this->job->database_progress = 100;
                    $this->job->database_completed = true;
                }
                break;
            } elseif (!$this->job->files_all_completed) {
                // Files sync
                if ($this->migration->sync_files) {
                    $this->handleStepFiles();
                } else {
                    $this->job->files_progress = 100;
                    $this->job->files_all_completed = true;
                }
                break;
            } elseif (!$this->job->finalize_completed) {
                // Finalize
                $this->handleStepFinalize();
                break;
            } else {
                break;
            }

            $lastrun_time = $this->timer->getElapsedTimeToNow($timer_start_identifier);
        }

        // Add errors and warnings to job
        $this->job->errors = array_merge($this->job->errors, $this->errors);
        $this->job->warnings = array_merge($this->job->warnings, $this->warnings);

        // Set post run data
        $this->updateCompletedState();
        $this->job->run_lock = false;

        // Get result to return
        $result = $this->getResult();

        // save job status before returning
        $this->job->save();

        if (count($this->job->errors) > 0) {
            // Do error action
            do_action("wpsynchro_migration_failure", $this->migration_id, $this->job_id, $this->job->errors, $this->job->warnings);

            // Set this migration to failed
            global $wpsynchro_container;
            $metadatalog = $wpsynchro_container->get('class.SyncMetadataLog');
            $metadatalog->setMigrationToFailed($this->job_id, $this->migration_id);
        }

        // If we are completed
        if ($this->job->is_completed) {
            // Do migration completed action
            do_action("wpsynchro_migration_completed", $this->migration_id, $this->job_id);
        }

        // Stop all timers and debug log them
        $this->timer->endSync();
        $this->logger->log("INFO", "Ending migration loop - with remaining time: " . $this->timer->getRemainingSyncTime());

        return $result;
    }

    /**
     *  Get migration result
     *  @since 1.6.1
     */
    public function getResult()
    {
        $result = new \stdClass();
        $result->is_completed = $this->job->is_completed;
        $result->transfertoken = $this->job->local_transfer_token;
        $result->errors = $this->job->errors;
        $result->warnings = $this->job->warnings;
        $result->migration_complete_messages = $this->job->finalize_success_messages_frontend;
        return $result;
    }

    /**
     * Handle initiation step
     * @since 1.0.0
     */
    private function handleInitiationStep()
    {
        $initiate = new InitiateSync();
        $initiate->initiatemigration($this->migration, $this->job);
    }

    /**
     * Handle masterdata step
     * @since 1.0.0
     */
    private function handleStepMasterdata()
    {
        $masterdata = new MasterdataSync();
        $masterdata->runMasterdataStep($this->migration, $this->job);
    }

    /**
     * Handle database backup step
     * @since 1.2.0
     */
    private function handleStepDatabaseBackup()
    {
        $databasebackup = new DatabaseBackup();
        $databasebackup->backupDatabase($this->migration, $this->job);
    }

    /**
     * Handle database step
     * @since 1.0.0
     */
    private function handleStepDatabase()
    {
        global $wpsynchro_container;
        $databasesync = $wpsynchro_container->get('class.DatabaseSync');
        $databasesync->runDatabaseSync($this->migration, $this->job);
    }

    /**
     * Handle files step
     * @since 1.0.0
     */
    private function handleStepFiles()
    {
        global $wpsynchro_container;
        $filessync = $wpsynchro_container->get('class.FilesSync');
        if ($filessync != null) {
            $filessync->runFilesSync($this->migration, $this->job);
        }
    }

    /**
     * Handle finalize step
     * @since 1.0.0
     */
    private function handleStepFinalize()
    {
        global $wpsynchro_container;
        $finalizesync = $wpsynchro_container->get('class.FinalizeSync');
        $finalizesync->runFinalize($this->migration, $this->job);
    }

    /**
     * Updated completed status
     * @since 1.0.0
     */
    private function updateCompletedState()
    {
        if ($this->job->masterdata_completed) {
            $this->job->masterdata_progress = 100;
        }
        if ($this->job->database_backup_completed) {
            $this->job->database_backup_progress = 100;
        }
        if ($this->job->database_completed) {
            $this->job->database_progress = 100;
        }
        if ($this->job->files_all_completed) {
            $this->job->files_progress = 100;
        }
        if ($this->job->finalize_completed) {
            $this->job->finalize_progress = 100;
        }
        if ($this->job->masterdata_completed && $this->job->database_backup_completed && $this->job->database_completed && $this->job->files_all_completed && $this->job->finalize_completed) {
            $this->job->is_completed = true;

            global $wpsynchro_container;

            // Stop migration and mark as completed in metadatalog
            $metadatalog = $wpsynchro_container->get('class.SyncMetadataLog');
            $metadatalog->stopMigration($this->job_id, $this->migration_id);
        }
    }
}
