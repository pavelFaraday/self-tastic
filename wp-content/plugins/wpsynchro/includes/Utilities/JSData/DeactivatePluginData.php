<?php

/**
 * Class for providing data for deactivate modal
 * @since 1.8.0
 */

namespace WPSynchro\Utilities\JSData;

use WPSynchro\CommonFunctions;

class DeactivatePluginData
{

    /**
     *  Load the JS data for page headers
     */
    public function load()
    {
        global $wp_version;
        $jsdata = [
            'header_text' => __('Help us improve', 'wpsynchro'),
            'introduction_text' => __('Why you want to deactivate WP Synchro?', 'wpsynchro'),
            'comment_text' => __('How can we improve WP Synchro?', 'wpsynchro'),
            'must_select_option_error_text' => __('You need to select an option or write a comment, before you can send feedback.', 'wpsynchro'),
            'send_feedback_help' => __('Privacy information: Feedback will be sent to webservice at wpsynchro.com from your browser - Will contain the selected reason for deactivation, the comment, site language, WordPress version, WP Synchro version and if it is FREE or PRO version. Nothing else will be sent or saved.', 'wpsynchro'),
            'wp_version' => $wp_version,
            'wp_synchro_version' => WPSYNCHRO_VERSION,
            'wp_synchro_version_type' => CommonFunctions::isPremiumVersion() ? 'PRO' : 'FREE',
            'wp_language' => get_bloginfo("language"),
            'get_questions_url' => 'https://wpsynchro.com/api/v1/deactivate-feedback-questions',
            'post_feedback_url' => 'https://wpsynchro.com/api/v1/deactivate-feedback',
        ];

        wp_localize_script('wpsynchro_deactivate_js', 'wpsynchro_deactivation', $jsdata);
    }
}
