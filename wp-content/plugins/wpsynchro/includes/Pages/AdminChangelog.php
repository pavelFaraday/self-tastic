<?php

/**
 * Show changelog
 * @since 1.8.0
 */

namespace WPSynchro\Pages;

class AdminChangelog
{
    /**
     *  Called from WP menu to show changelog
     *  @since 1.8.0
     */
    public static function render()
    {
        // Load changelog
        $changelog = \file_get_contents(WPSYNCHRO_PLUGIN_DIR . "changelog.txt");

        // Data for JS
        $data_for_js = [
            "changelog" => $changelog,
        ];
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_changelog_data', $data_for_js);

        $translation_for_js = [
            "pageTitle" => __('Changelog', 'wpsynchro'),
            "introText" => __('See the changes in each version throughout the history of WP Synchro.', 'wpsynchro'),

        ];
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_changelog_translations', $translation_for_js);

        // Print content
        echo '<div id="wpsynchro-changelog" class="wpsynchro"><page-changelog></page-changelog></div>';
    }
}
