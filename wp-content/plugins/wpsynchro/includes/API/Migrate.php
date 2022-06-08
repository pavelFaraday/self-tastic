<?php
namespace WPSynchro\API;

/**
 * Class for handling service "migrate"
 * Call should already be verified by permissions callback
 *
 * @since 1.0.0
 */
class Migrate extends WPSynchroService
{

    public function service()
    {
        // Extract parameters
        $body = $this->getRequestBody();
        $parameters = json_decode($body);

        if (isset($parameters->migration_id)) {
            $migration_id = $parameters->migration_id;
        } else {
            $migration_id = '';
        }
        if (isset($parameters->job_id)) {
            $job_id = $parameters->job_id;
        } else {
            $job_id = '';
        }

        global $wpsynchro_container;
        $migrate = $wpsynchro_container->get('class.MigrationController');
        $migrate->setup($migration_id, $job_id);
        $sync_response = $migrate->runmigration();

        if (isset($sync_response->errors) && count($sync_response->errors) > 0) {
            // Set that we should not continue migration from frontend JS
            $sync_response->should_continue = false;
        } else {
            // Set to frontend to continue migration
            $sync_response->should_continue = true;
        }

        if (isset($sync_response->is_completed) && $sync_response->is_completed === true) {
            $sync_response->should_continue = false;
        }

        echo json_encode($sync_response);
    }
}
