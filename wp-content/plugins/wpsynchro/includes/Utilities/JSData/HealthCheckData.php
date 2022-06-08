<?php

/**
 * Class for providing data for Health check JS
 * @since 1.6.0
 */

namespace WPSynchro\Utilities\JSData;

use WPSynchro\CommonFunctions;

class HealthCheckData
{

    /**
     *  Load the JS data for Health Check Vue component
     */
    public function load()
    {
        $healthcheck_localize = [
            'healthcheck_url' => get_home_url() . '?action=wpsynchro_frontend_healthcheck',
            'introtext' => __('Health check for WP Synchro', 'wpsynchro'),
            'helptitle' => __('Check if this site will work with WP Synchro. It checks service access, php extensions, hosting setup and more.', 'wpsynchro'),
            'basic_check_desc' => __('Performing basic health check', 'wpsynchro'),
            'errorsfound' => __('Errors found', 'wpsynchro'),
            'warningsfound' => __('Warnings found', 'wpsynchro'),
            'rerunhelp' => __("Tip: These tests can be rerun in 'Support' menu.", 'wpsynchro'),
            'errorunknown' => __('Critical - Request to local WP Synchro health check service could not be sent or did not get no response.', 'wpsynchro'),
            'errornoresponse' => __('Critical - Request to local WP Synchro health check service did not get a response at all.', 'wpsynchro'),
            'errorwithstatuscode' => __('Critical - Request to service did not respond properly - HTTP {0} - Maybe service is blocked or returns invalid content. Response JSON:', 'wpsynchro'),
            'errorwithoutstatuscode' => __('Critical - Request to service did not respond properly - Maybe service is blocked or returns invalid content. Response JSON:', 'wpsynchro'),
        ];
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_healthcheck', $healthcheck_localize);
    }
}
