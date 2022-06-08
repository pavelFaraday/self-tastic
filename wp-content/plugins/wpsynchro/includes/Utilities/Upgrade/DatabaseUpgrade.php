<?php

namespace WPSynchro\Utilities\Upgrade;

use WPSynchro\MigrationFactory;
use WPSynchro\Utilities\Configuration\PluginConfiguration;

/**
 * Handle database upgrades
 * @since 1.6.0
 */
class DatabaseUpgrade
{

    /**
     *  Check WP Synchro database version and compare with current
     *  @since 1.0.3
     */
    public static function checkDBVersion()
    {
        $dbversion = get_option('wpsynchro_dbversion');

        // If not set yet, just set it and continue with life
        if (!$dbversion || $dbversion == "") {
            $dbversion = 0;
        }

        // Check if it is same as current
        if ($dbversion == WPSYNCHRO_DB_VERSION) {
            // Puuurfect, all good, so return
            return;
        } else {
            // Database is different than current version
            if ($dbversion > WPSYNCHRO_DB_VERSION) {
                // Its newer? :|
                return;
            } else {
                // Its older, so lets upgrade
                self::handleDBUpgrade($dbversion);
            }
        }
    }

    /**
     *  Handle upgrading of DB versions
     *  @since 1.0.3
     */
    public static function handleDBUpgrade($current_version)
    {
        if ($current_version > WPSYNCHRO_DB_VERSION) {
            return false;
        }

        // Version 1 - First DB version, no upgrades needed
        if ($current_version < 1) {
            // nothing to do for first version
        }

        // Version 1 > 2
        if ($current_version < 2) {
            // Enable MU Plugin by default
            $plugin_configuration = new PluginConfiguration();
            $plugin_configuration->setMUPluginEnabledState(true);
        }

        // Version 2 > 3
        if ($current_version < 3) {
            // Update migrations with the new preset setting
            global $wpsynchro_container;
            $migration_factory = $wpsynchro_container->get("class.MigrationFactory");
            $migration_factory->getAllMigrations();
            foreach ($migration_factory->migrations as &$migration) {
                $migration->sync_preset = 'none';
                $migration->db_make_backup = false;
                $migration->searchreplaces = [];
            }
            $migration_factory->save();
        }

        // Version 3 > 4
        if ($current_version < 4) {
            // Update migrations with the new table prefix setting
            global $wpsynchro_container;
            $migration_factory = $wpsynchro_container->get("class.MigrationFactory");
            $migration_factory->getAllMigrations();
            foreach ($migration_factory->migrations as &$migration) {
                $migration->db_table_prefix_change = true;
            }
            $migration_factory->save();
        }

        // Version 4 > 5
        if ($current_version < 5) {
            // Clear file population object from db, as it has been changed
            delete_option("wpsynchro_filepopulation_current");
            // Remove IP security option, as it is removed in 1.6.0
            delete_option("wpsynchro_ip_security_enabled");
            // Set all migrations as "direct" connections
            global $wpsynchro_container;
            $migration_factory = $wpsynchro_container->get("class.MigrationFactory");
            $migration_factory->getAllMigrations();
            foreach ($migration_factory->migrations as &$migration) {
                $migration->connection_type = "direct";
            }
            $migration_factory->save();
        }

        // Version 5 > 6
        if ($current_version < 6) {
            delete_option("wpsynchro_debuglogging_enabled");
        }

        // Version 6 > 7 (1.6.4 > 1.7.0)
        if ($current_version < 7) {
            $migration_factory = new MigrationFactory();
            $migration_factory->getAllMigrations();
            foreach ($migration_factory->migrations as &$migration) {
                $migration->files_ask_user_for_confirm = false;
            }
            $migration_factory->save();
        }

        // Version 7 > 8 (1.7.3 > 1.8.0)
        if ($current_version < 8) {
            global $wpdb;
            $wpdb->query("UPDATE {$wpdb->options} SET option_name='wpsynchro_migrations' WHERE option_name='wpsynchro_installations'");

            $migration_factory = new MigrationFactory();
            $migration_factory->getAllMigrations();
            foreach ($migration_factory->migrations as &$migration) {
                // Add new field
                $migration->db_preserve_options_table_keys = [];
                // Add preserve plugins, if previously set
                if (isset($migration->db_preserve_activeplugins) && $migration->db_preserve_activeplugins == true) {
                    $migration->db_preserve_options_table_keys[] = 'active_plugins';
                }
                unset($migration->db_preserve_activeplugins);
            }
            $migration_factory->save();
        }

        // Set to the db version for this release
        update_option('wpsynchro_dbversion', WPSYNCHRO_DB_VERSION, true);
        return true;
    }
}
