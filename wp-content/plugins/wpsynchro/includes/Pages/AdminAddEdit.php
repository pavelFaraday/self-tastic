<?php

namespace WPSynchro\Pages;

use WPSynchro\CommonFunctions;
use WPSynchro\Files\Location;
use WPSynchro\Migration;
use WPSynchro\MigrationFactory;

/**
 * Class for handling what to show when adding or editing a migration in wp-admin
 * @since 1.0.0
 */
class AdminAddEdit
{
    public static function render()
    {
        $instance = new self;
        // Handle post
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $instance->handlePOST();
        }
        $instance->handleGET();
    }

    private function handleGET()
    {
        // Check php/wp/mysql versions
        $commonfunctions = new CommonFunctions();
        $compat_errors = $commonfunctions->checkEnvCompatability();

        // Set the id
        if (isset($_REQUEST['migration_id'])) {
            $id = sanitize_text_field($_REQUEST['migration_id']);
        } else {
            $id = '';
        }

        // Get the data
        $migration_factory = new MigrationFactory();
        $migration = $migration_factory->retrieveMigration($id);

        if ($migration == false) {
            $migration = new Migration();
        }

        // Is PRO version
        $is_pro = $commonfunctions::isPremiumVersion();

        // Localize the script with data
        $adminjsdata = [
            'instance' => $migration,
            'home_url' => esc_url_raw(get_home_url()),
            'is_pro' => $is_pro,
            'text_error_gettoken' => __('Could not get token from remote server - Is WP Synchro installed and activated?', 'wpsynchro'),
            'text_error_response' => __('Got a response from remote site, but did not get correct response - Check access key and website url', 'wpsynchro'),
            'text_error_request' => __('No proper response from remote server - Check that website and access key is correct and WP Synchro is activated', 'wpsynchro'),
            'text_valid_endpoint_error_no_transfer_token' => __('No proper transfer token to use for safe communication - Try with another browser. Eg. newest Chrome.', 'wpsynchro'),
            'text_valid_endpoint_could_not_connect' => __('Could not connect to remote service - Check access key, website url and WP Synchro is activated', 'wpsynchro'),
            'text_valid_endpoint_http_error_debug' => __('Debug information - HTTP code: {0} - Response: {1}', 'wpsynchro'),
            'text_get_dbtables_error' => __('Could not grab the database tables names from remote', 'wpsynchro'),
            'text_get_filedetails_error' => __('Could not grab the file data from remote - It may be caused by different versions of WP Synchro', 'wpsynchro'),
            'text_validate_name_error' => __('Please choose a name for this migration', 'wpsynchro'),
            'text_validate_endpoint_error' => __('Website or access key is not valid', 'wpsynchro'),
            'text_validate_endpoint_compat_wp_in_own_dir_diff' => __('One of the sites seem to be using a non-standard location for WordPress core compared with the web root. This needs to be the same on both ends if migration also includes files. If you are just synchronizing database, you can ignore this warning. Source web root was: {0} and source WP dir: {1}. Target web root was {2} and target WP dir: {3}.', 'wpsynchro'),
            'text_validate_endpoint_different_plugin_versions' => __('Sites are using different versions of WP Synchro. One uses {0} and the other uses {1}. Upgrade to newest version.', 'wpsynchro'),
            'text_warning_shared_paths' => __('The web root for the {0} site is overlapping with the {1} site web root. This is not a problem if it is on a different server, but if they have overlapping paths on the same server, it will create problems if you try to migrate all files. To prevent problems, make sure each site has its own location with no other sites inside. Database migration will work without problems. For more information, see the documentation on sub directory sites on wpsynchro.com', 'wpsynchro'),
            'text_save_validate_email_success_notification_failed' => __("Email list from 'notify success' in General Settings is not valid. Emails must be valid and separated by semicolon.", 'wpsynchro'),
            'text_save_validate_email_errors_notification_failed' => __("Email list from 'notify errors' in General Settings is not valid. Emails must be valid and separated by semicolon.", 'wpsynchro'),
            'limited_in_pro_title' => __('Get PRO version now to start doing file migration and more! Free 14 day trial - Creditcard required', 'wpsynchro'),
            'limited_in_pro_anchor_content' => __('PRO version only', 'wpsynchro'),
        ];
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_addedit', $adminjsdata);

        // Location entry data and translation
        $location_entry_data = [
            'text_entry_locked' => __('This should not be synced and will be excluded from migrations', 'wpsynchro'),
            'text_entry_blocked_text' => __('Choose the entire dir or use the other add buttons', 'wpsynchro'),
        ];
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_addedit_location_entry', $location_entry_data);

        // Location picker data and translation
        $location_picker_data = [
            'text_header' => __('Add files or directories to migrate', 'wpsynchro'),
            'text_keep' => __('Keep', 'wpsynchro'),
            'text_clean' => __('Clean', 'wpsynchro'),
            'text_keep_description' => __('Keep files on target not present on source. Faster, but will potentially leave unused files on target', 'wpsynchro'),
            'text_clean_description' => __('Delete files on target not present on source. Slower, but more clean, because unused files will be removed', 'wpsynchro'),
            'text_exclusions' => __('Exclusions', 'wpsynchro'),
            'text_exclusions_description' => __('Exclusions to be applied to this location. Will be matched as substring on the path, so be careful. Separate with comma. Like: ignoredir,otherignoredir', 'wpsynchro'),
            'text_cancel' => __('Cancel', 'wpsynchro'),
            'text_save' => __('Save', 'wpsynchro'),
            'text_fetchfiledata_could_not_fetch_data' => __('Could not fetch filedata - Normally due to a timed out security token. Refresh page and continue.', 'wpsynchro'),
        ];
        wp_localize_script('wpsynchro_admin_js', 'wpsynchro_addedit_location_picker', $location_picker_data);

        require WPSYNCHRO_PLUGIN_DIR . 'includes/Templates/page-add-edit.php';
    }

    /**
     *  Handle saving of migration
     */
    private function handlePOST()
    {
        $migration_factory = new MigrationFactory();
        $migration = new Migration();
        $newly_created = false;

        if (strlen($_POST['id']) > 0) {
            // Existing migration
            $migration->id = $_POST['id'];
        } else {
            // New migration
            $migration->id = uniqid();
            $newly_created = true;
        }
        if (isset($_POST['name'])) {
            $migration->name = sanitize_text_field(trim($_POST['name']));
        } else {
            $migration->name = '';
        }
        if (isset($_POST['type'])) {
            $migration->type = sanitize_text_field($_POST['type']);
        } else {
            $migration->type = '';
        }
        if (isset($_POST['website'])) {
            $migration->site_url = sanitize_text_field(trim($_POST['website'], ',/\\ '));
        } else {
            $migration->site_url = '';
        }
        if (isset($_POST['accesskey'])) {
            $migration->access_key = sanitize_text_field(trim($_POST['accesskey']));
        } else {
            $migration->access_key = '';
        }
        // Connection type
        if (isset($_POST['connection_type'])) {
            $migration->connection_type = sanitize_text_field(trim($_POST['connection_type']));
        } else {
            $migration->connection_type = 'direct';
        }
        if (isset($_POST['basic_auth_username'])) {
            $migration->basic_auth_username = sanitize_text_field(trim($_POST['basic_auth_username']));
        } else {
            $migration->basic_auth_username = '';
        }
        if (isset($_POST['basic_auth_password'])) {
            $migration->basic_auth_password = sanitize_text_field(trim($_POST['basic_auth_password']));
        } else {
            $migration->basic_auth_password = '';
        }

        /**
         *  General settings
         */
        $migration->verify_ssl = (isset($_POST['verify_ssl']) ? true : false);

        if (isset($_POST['success_notification_email_list'])) {
            $migration->success_notification_email_list = sanitize_text_field($_POST['success_notification_email_list']);
        } else {
            $migration->success_notification_email_list = '';
        }
        if (isset($_POST['error_notification_email_list'])) {
            $migration->error_notification_email_list = sanitize_text_field($_POST['error_notification_email_list']);
        } else {
            $migration->error_notification_email_list = '';
        }

        /**
         *  migration sync
         */
        $migration->sync_preset = (isset($_POST['sync_preset']) ? $_POST['sync_preset'] : 'none');
        $migration->sync_database = (isset($_POST['sync_database']) ? true : false);
        $migration->sync_files = (isset($_POST['sync_files']) ? true : false);

        /**
         * Database save
         */
        $migration->db_make_backup = (isset($_POST['db_make_backup']) ? true : false);
        $migration->db_table_prefix_change = (isset($_POST['db_table_prefix_change']) ? true : false);
        $migration->include_all_database_tables = (isset($_POST['include_all_database_tables']) ? true : false);
        $migration->only_include_database_table_names = (isset($_POST['only_include_database_table_names']) ? $_POST['only_include_database_table_names'] : []);
        $migration->db_preserve_options_table_keys = [];
        if (isset($_POST['db_preserve_active_plugins'])) {
            $migration->db_preserve_options_table_keys[] = 'active_plugins';
        }
        if (isset($_POST['db_preserve_blog_public'])) {
            $migration->db_preserve_options_table_keys[] = 'blog_public';
        }
        $migration->db_preserve_options_custom = sanitize_text_field($_POST['db_preserve_options_custom']);

        if (isset($_POST['searchreplaces_from'])) {
            $searchreplaces_from = $_POST['searchreplaces_from'];
        } else {
            $searchreplaces_from = [];
        }
        if (isset($_POST['searchreplaces_to'])) {
            $searchreplaces_to = $_POST['searchreplaces_to'];
        } else {
            $searchreplaces_to = [];
        }

        $searchreplaces = [];
        for ($i = 0; $i < count($searchreplaces_from); $i++) {
            if (strlen($searchreplaces_from[$i]) > 0 && strlen($searchreplaces_to[$i]) > 0) {
                $tmp_obj = new \stdClass();
                $tmp_obj->to = stripslashes($searchreplaces_to[$i]);
                $tmp_obj->from = stripslashes($searchreplaces_from[$i]);
                $searchreplaces[] = $tmp_obj;
            }
        }
        $migration->searchreplaces = $searchreplaces;

        /**
         * Files save
         */
        $file_locations = [];

        if (\WPSynchro\CommonFunctions::isPremiumVersion()) {
            if (isset($_POST['file_locations_base'])) {
                $file_locations_base = $_POST['file_locations_base'];
            }
            if (isset($_POST['file_locations_strategy'])) {
                $file_locations_strategy = $_POST['file_locations_strategy'];
            }
            if (isset($_POST['file_locations_isfile'])) {
                $file_locations_isfile = $_POST['file_locations_isfile'];
            }
            if (isset($_POST['file_locations_exclusions'])) {
                $file_locations_exclusions = $_POST['file_locations_exclusions'];
            }
            if (isset($_POST['file_locations_path'])) {
                $file_locations_path = $_POST['file_locations_path'];
            }

            if (isset($file_locations_path)) {
                for ($i = 0; $i < count($file_locations_path); $i++) {
                    $location = new Location();
                    $location->path = $file_locations_path[$i];
                    if (isset($file_locations_base[$i])) {
                        $location->base = $file_locations_base[$i];
                    }
                    if (isset($file_locations_strategy[$i])) {
                        $location->strategy = $file_locations_strategy[$i];
                    }
                    if (isset($file_locations_isfile[$i])) {
                        $location->is_file = ($file_locations_isfile[$i] == 'true' ? true : false);
                    }
                    if (isset($file_locations_exclusions[$i])) {
                        $location->exclusions = $file_locations_exclusions[$i];
                    }
                    $file_locations[] = $location;
                }
            }
        }

        $migration->file_locations = $file_locations;

        if (isset($_POST['files_exclude_files_match'])) {
            $migration->files_exclude_files_match = sanitize_text_field($_POST['files_exclude_files_match']);
        } else {
            $migration->files_exclude_files_match = '';
        }

        $migration->files_ask_user_for_confirm = (isset($_POST['files_ask_user_for_confirm']) ? true : false);

        $migration_factory->addMigration($migration);

        if ($newly_created) {
            $redirurl = add_query_arg('migration_id', $migration->id, menu_page_url('wpsynchro_addedit', false));
            $redirurl = add_query_arg('created', '1', $redirurl);
            echo "<script>window.location.replace('" . $redirurl . "');</script>";
        }
    }
}
