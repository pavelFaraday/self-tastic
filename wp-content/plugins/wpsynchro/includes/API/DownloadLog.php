<?php

namespace WPSynchro\API;

use WPSynchro\CommonFunctions;
use WPSynchro\MigrationFactory;

/**
 * Class for handling service to download logs
 * Call should already be verified by permissions callback
 *
 * @since 1.0.0
 */
class DownloadLog extends WPSynchroService
{

    public function service()
    {

        if (!isset($_REQUEST['job_id']) || strlen($_REQUEST['job_id']) == 0) {
            $result = new \StdClass();
            echo json_encode($result);
            http_response_code(400);
            return;
        }
        $job_id = $_REQUEST['job_id'];

        if (!isset($_REQUEST['migration_id']) || strlen($_REQUEST['migration_id']) == 0) {
            $result = new \StdClass();
            echo json_encode($result);
            http_response_code(400);
            return;
        }
        $migration_id = $_REQUEST['migration_id'];

        $common = new CommonFunctions();
        $migration_factory = new MigrationFactory();

        $logpath = $common->getLogLocation();
        $filename = $common->getLogFilename($job_id);

        if (file_exists($logpath . $filename)) {

            $logcontents = "";

            // Intro
            $logcontents .= "Beware: Do not share this file with other people than WP Synchro support - It contains data that can compromise your site." . PHP_EOL . PHP_EOL;

            // Log data
            $logcontents .= file_get_contents($logpath . $filename);
            $job_obj = get_option("wpsynchro_" . $migration_id . "_" . $job_id, "");
            $migration_obj = $migration_factory->retrieveMigration($migration_id);

            // migration object
            $logcontents .= PHP_EOL . "Migration object:" . PHP_EOL;
            $logcontents .= print_r($migration_obj, true);

            // Job object
            $logcontents .= PHP_EOL . "Job object:" . PHP_EOL;
            $logcontents .= print_r($job_obj, true);

            $zipfilename = "wpsynchro_log_" . $job_id . ".zip";

            http_response_code(200);    // IIS fails if this is not here
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Type: application/zip");
            header("Content-Disposition: attachment; filename=" . $zipfilename);

            $zipfile = tempnam($common->getLogLocation(), "zip");
            $zip = new \ZipArchive();
            $zip->open($zipfile, \ZipArchive::OVERWRITE);
            $zip->addFromString($filename, $logcontents);
            $zip->close();

            readfile($zipfile);
            unlink($zipfile);

            exit();
        } else {
            http_response_code(400);
            return;
        }
    }
}
