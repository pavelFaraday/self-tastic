<div id="wpsynchro-addedit" class="wrap wpsynchro" v-cloak>
    <h2 class="pagetitle"><img src="<?= $commonfunctions->getAssetUrl("icon.png") ?>" width="35" height="35" />WP Synchro <?= WPSYNCHRO_VERSION ?> <?php echo ($is_pro ? 'PRO' : 'FREE'); ?> - <?php ($id > 0 ? _e('Edit migration', 'wpsynchro') : _e('Add migration', 'wpsynchro')); ?></h2>

    <?php
    if (count($compat_errors) > 0) {
        foreach ($compat_errors as $error) {
            echo '<b>' . $error . '</b><br>';
        }
        echo '</div>';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<div class='notice notice-success wpsynchro-notice'><p>" . __('Migration is now saved', 'wpsynchro') . " - <a href='" . menu_page_url('wpsynchro_overview', false) . "'>" . __('Go back to overview', 'wpsynchro') . '</a></p></div>';
    } elseif (isset($_REQUEST['created'])) {
        echo "<div class='notice notice-success wpsynchro-notice'><p>" . __('Migration is now created', 'wpsynchro') . " - <a href='" . menu_page_url('wpsynchro_overview', false) . "'>" . __('Go back to overview', 'wpsynchro') . '</a></p></div>';
    }

    echo '<p>' . __('Configure your migration and which data to include.', 'wpsynchro') . '</p>'; ?>

    <form id="wpsynchro-addedit-form" ref="addeditForm" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

        <div class="generalsetup">
            <div class="sectionheader"><span class="dashicons dashicons-admin-home"></span> <?php _e('Migration', 'wpsynchro'); ?></div>

            <h3><?php _e('Choose a name', 'wpsynchro'); ?></h3>
            <div class="option">
                <div class="optionname">
                    <label for="name"><?php _e('Name', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <input v-model.trim="migration.name" type="text" name="name" id="name" value="" autocomplete="off" data-lpignore="true" required>
                    <span title="<?php _e('Choose a name for the migration, which will be used to identify it in the list of migrations. Use something like: Pull DB from production', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                </div>
            </div>

            <h3><?php _e('Type of migration', 'wpsynchro'); ?></h3>
            <div class="option">
                <div class="optionname">
                    <label><?php _e('Type', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <div><label><input v-model="migration.type" type="radio" name="type" value="pull" v-on:click="valid_endpoint = false"></input> <?php _e('Pull from remote site to this site ', 'wpsynchro'); ?></label></div>
                    <div><label><input v-model="migration.type" type="radio" name="type" value="push" v-on:click="valid_endpoint = false"></input> <?php _e('Push this site to remote site', 'wpsynchro'); ?></label></div>
                </div>
            </div>

            <div v-if="migration.type.length > 0">
                <h3 v-if="migration.type == 'pull'"><?php _e('Where to pull from', 'wpsynchro'); ?></h3>
                <h3 v-if="migration.type == 'push'"><?php _e('Where to push to', 'wpsynchro'); ?></h3>

                <div class="option">
                    <div class="optionname">
                        <label for="website"><?php _e('Website (full url)', 'wpsynchro'); ?></label>
                    </div>
                    <div class="optionvalue">
                        <input v-model.trim="migration.site_url" v-on:change="valid_endpoint = false" type="text" name="website" id="website" value="" placeholder="https://example.com" autocomplete="off" data-lpignore="true" required>
                        <span title="<?php _e('The URL of the site you want to pull from or push to. Format: https://example.com', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                        <span v-if="valid_endpoint" class="validstate dashicons dashicons-yes" title="<?php _e('Validated', 'wpsynchro'); ?>"></span>
                    </div>
                </div>
                <div class="option">
                    <div class="optionname">
                        <label for="accesskey"><?php _e('Access key', 'wpsynchro'); ?></label>
                    </div>
                    <div class="optionvalue">
                        <input v-model.trim="migration.access_key" v-on:change="valid_endpoint = false" type="password" name="accesskey" id="accesskey" value="" autocomplete="off" data-lpignore="true" required></input>
                        <span title="<?php _e("The access key from the remote site. It can be found in 'WP Synchro' > 'Setup' menu on the remote site.", 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                        <span v-if="valid_endpoint" class="validstate dashicons dashicons-yes" title="<?php _e('Validated', 'wpsynchro'); ?>"></span>
                    </div>
                </div>
            </div>

            <div v-show="migration.type.length > 0">
                <h3><?php _e('Connection options', 'wpsynchro'); ?></h3>

                <div class="option">
                    <div class="optionname">
                        <label><?php _e('Connection', 'wpsynchro'); ?></label>
                    </div>
                    <div class="optionvalue">
                        <div><label><input v-model="migration.connection_type" type="radio" name="connection_type" value="direct" v-on:click="valid_endpoint = false; migration.connection_options = {};"></input> <?php _e('Direct connection (default)', 'wpsynchro'); ?></label></div>
                        <div><label><input v-model="migration.connection_type" type="radio" name="connection_type" value="basicauth" v-on:click="valid_endpoint = false" v-bind:disabled="!is_pro"></input> <?php _e('Basic authentication (username+password)', 'wpsynchro'); ?> <pro-badge v-if="!is_pro"></pro-badge></label></div>
                    </div>
                </div>

                <div v-if="is_pro && migration.connection_type == 'basicauth'" class="option">
                    <div class="optionname">
                        <label><?php _e('Basic authentication', 'wpsynchro'); ?></label>
                    </div>
                    <div class="optionvalue">
                        <input v-model.trim="migration.basic_auth_username" v-on:input="valid_endpoint = false" type="text" name="basic_auth_username" id="basic_auth_username" value="" placeholder="Username" autocomplete="off" data-lpignore="true" required>
                        <input v-model.trim="migration.basic_auth_password" v-on:change="valid_endpoint = false" type="password" name="basic_auth_password" id="basic_auth_password" value="" placeholder="Password" autocomplete="off" data-lpignore="true" required>
                    </div>
                </div>

                <div class="option">
                    <div class="optionname">
                        <label><?php _e('Verify SSL certificate', 'wpsynchro'); ?></label>
                    </div>
                    <div class="optionvalue">
                        <label><input v-model="migration.verify_ssl" v-on:change="valid_endpoint = false" type="checkbox" name="verify_ssl" id="verify_ssl"></input> <?php _e('Verify SSL certificates - Uncheck this if you want to allow self-signed certificates', 'wpsynchro'); ?></label><br>
                    </div>
                </div>
            </div>

            <button id="verifyconnectionbtn" v-if="!valid_endpoint" v-bind:disabled="valid_endpoint_spinner" v-on:click.prevent="doVerification"><?php _e('Verify connection to remote site', 'wpsynchro'); ?></button>
            <div v-show="valid_endpoint_spinner" class="spinner"></div>

        </div>

        <div class="endpoint-errors" v-if="compatibility_errors.length > 0 || valid_endpoint_errors.length > 0">
            <div class="sectionheader sectionheadererror"><span class="dashicons dashicons-warning"></span> <?php _e('Errors was found', 'wpsynchro'); ?></div>

            <ul>
                <li v-for="(errormessage, index) in valid_endpoint_errors">{{errormessage}}</li>
                <li v-for="errortext in compatibility_errors">{{errortext}}</li>
            </ul>
        </div>

        <div class="endpoint-warnings" v-if="compatibility_warnings.length > 0 && valid_endpoint">
            <div class="sectionheader sectionheaderwarning"><span class="dashicons dashicons-warning"></span> <?php _e('Warnings was found', 'wpsynchro'); ?></div>

            <ul>
                <li v-for="errortext in compatibility_warnings">{{errortext}}</li>
            </ul>
        </div>

        <div class="multisitesetting" v-if="valid_endpoint && (this.multisite.source_is_multisite || this.multisite.target_is_multisite)">
            <div class="sectionheader"><span class="dashicons dashicons-admin-multisite"></span> <?php _e('Multisite migration', 'wpsynchro'); ?> [NOT SUPPORTED]</div>

            <p><?= __("Multisite migration is not supported, so if you want to try to use it anyway, make sure to test it in a safe manner.", "wpsynchro") ?></p>
        </div>

        <div class="generalsettings" v-if="valid_endpoint">
            <div class="sectionheader"><span class="dashicons dashicons-admin-tools"></span> <?php _e('General settings', 'wpsynchro'); ?></div>

            <div class="option">
                <div class="optionname">
                    <label><?php _e('Clear cache on success', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <label><input v-model="migration.clear_cache_on_success" type="checkbox" name="clear_cache_on_success" id="clear_cache_on_success"></input> <?php _e('Clear the cache on the target on successful migration', 'wpsynchro'); ?></label>
                    <span title="<?php _e('Attempt to clear cache on target on successful migration - support most popular caching plugins where programmatic clearing is supported.', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                </div>
            </div>

            <div class="option <?= $is_pro ? "" : 'limited_in_free' ?>">
                <div class="optionname">
                    <label for="success_notification_email_list"><?php _e('Notify emails on success', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <input type="text" v-model.trim="migration.success_notification_email_list" name="success_notification_email_list" id="success_notification_email_list" placeholder="<?= __('test@example.com;test2@example.com', 'wpsynchro') ?>" <?php echo ($is_pro ? '' : 'disabled'); ?> autocomplete="off" data-lpignore="true">
                    <span title="<?php _e('Send emails to email list when migration is successful.', 'wpsynchro'); ?> <?php _e('Emails are separated by semicolon. If empty, no emails will be sent.', 'wpsynchro'); ?> <?php _e('Uses WordPress standard function wp_mail() to send emails.', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                    <pro-badge v-if="!is_pro"></pro-badge>
                </div>
            </div>

            <div class="option <?= $is_pro ? "" : 'limited_in_free' ?>">
                <div class="optionname">
                    <label for="error_notification_email_list"><?php _e('Notify emails on error', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <input type="text" v-model.trim="migration.error_notification_email_list" name="error_notification_email_list" id="error_notification_email_list" placeholder="<?= __('test@example.com;test2@example.com', 'wpsynchro') ?>" <?php echo ($is_pro ? '' : 'disabled'); ?> autocomplete="off" data-lpignore="true">
                    <span title="<?php _e('Send emails to email list when migration fails.', 'wpsynchro'); ?> <?php _e('Emails are separated by semicolon. If empty, no emails will be sent.', 'wpsynchro'); ?> <?php _e('Uses WordPress standard function wp_mail() to send emails.', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                    <pro-badge v-if="!is_pro"></pro-badge>
                </div>
            </div>

        </div>

        <div class="datatosync" v-if="valid_endpoint">
            <div class="sectionheader"><span class="dashicons dashicons-screenoptions"></span> <?php _e('Data to migrate', 'wpsynchro'); ?></div>

            <div class="option">
                <div class="optionname">
                    <label><?php _e('Preconfigured migrations', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <div class="optionvaluepart <?= $is_pro ? "" : 'limited_in_free' ?>">
                        <label><input v-model="migration.sync_preset" type="radio" value="all" name="sync_preset" id="sync_preset_everything" <?php echo ($is_pro ? '' : 'disabled'); ?>></input> <?php _e('Migrate entire site', 'wpsynchro'); ?></label>
                        <span title="<?php _e('Backup database, migrate database, migrate all files from web root level (except WordPress core files)', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                        <pro-badge v-if="!is_pro"></pro-badge>
                    </div>
                    <div class="optionvaluepart <?= $is_pro ? "" : 'limited_in_free' ?>">
                        <label><input v-model="migration.sync_preset" type="radio" value="file_all" name="sync_preset" id="sync_preset_file_all" <?php echo ($is_pro ? '' : 'disabled'); ?>></input> <?php _e('Migrate all files', 'wpsynchro'); ?></label>
                        <span title="<?php _e('Migrate all files from web root level (except WordPress core files)', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span><br>
                        <pro-badge v-if="!is_pro"></pro-badge>
                    </div>
                    <div class="optionvaluepart">
                        <label><input v-model="migration.sync_preset" type="radio" value="db_all" name="sync_preset" id="sync_preset_db_all"></input> <?php _e('Migrate entire database', 'wpsynchro'); ?></label>
                        <span title="<?php echo ($is_pro ? __('Backup database and migrate all database tables', 'wpsynchro') : __('Backup database (Only PRO version) and migrate all database tables', 'wpsynchro')); ?>" class="dashicons dashicons-editor-help"></span>
                    </div>

                    <div class="optionvaluepart">
                        <label><input v-model="migration.sync_preset" type="radio" value="none" name="sync_preset" id="sync_preset_none"></input> <?php _e('Custom migration', 'wpsynchro'); ?></label>
                        <span title="<?php _e('Configure exactly what you want to migrate', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span>
                    </div>

                </div>
            </div>

            <div class="option" v-if="migration.sync_preset == 'none'">
                <div class="optionname">
                    <label><?php _e('Choose data to migrate', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <div class="optionvaluepart <?= $is_pro ? "" : 'limited_in_free' ?>">
                        <label><input v-model="migration.sync_files" type="checkbox" name="sync_files" id="sync_files" <?php echo ($is_pro ? '' : 'disabled'); ?>></input> <?php _e('Migrate files', 'wpsynchro'); ?> </label>
                        <pro-badge v-if="!is_pro"></pro-badge>
                    </div>
                    <div class="optionvaluepart">
                        <label><input v-model="migration.sync_database" type="checkbox" name="sync_database" id="sync_database"></input> <?php _e('Migrate database', 'wpsynchro'); ?></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="filessyncsetup" v-show="valid_endpoint && migration.sync_files && migration.sync_preset == 'none'">
            <div class="sectionheader"><span class="dashicons dashicons-admin-page"></span> <?php _e('Files migration', 'wpsynchro'); ?></div>

            <h3><?php _e('Files and directories to migrate', 'wpsynchro'); ?></h3>
            <p><?php _e('Choose the files or directories you want to migrate and how it should be handled.', 'wpsynchro'); ?></p>

            <?php
            $abovewebroot_readwrite_error = __('Disabled because read or write access to this location is disabled on the source or target server - Normally by PHPs open_basedir setting', 'wpsynchro');
            $std_readwrite_error = __('Disabled because read or write access to this location is disabled on the source or target server - Normally by incorrect file permissions', 'wpsynchro'); ?>

            <div class="addlocations">
                <button v-on:click.prevent="showLocationPicker('outsidewebroot',source_files_dirs.abovewebroot)" v-bind:disabled="isReadWriteRetrictedSourceTarget('abovewebroot')" v-bind:title="(isReadWriteRetrictedSourceTarget('abovewebroot') ? '<?php echo $abovewebroot_readwrite_error; ?>' : '')"><?php _e('Add from outside web root', 'wpsynchro'); ?></button>
                <button v-on:click.prevent="showLocationPicker('webroot',source_files_dirs.webroot)" v-bind:disabled="isReadWriteRetrictedSourceTarget('webroot')" v-bind:title="(isReadWriteRetrictedSourceTarget('webroot') ? '<?php echo $std_readwrite_error; ?>' : '')"><?php _e('Add from web root', 'wpsynchro'); ?></button>
                <button v-on:click.prevent="showLocationPicker('wpcontent',source_files_dirs.wpcontent)" v-bind:disabled="isReadWriteRetrictedSourceTarget('wpcontent')" v-bind:title="(isReadWriteRetrictedSourceTarget('wpcontent') ? '<?php echo $std_readwrite_error; ?>' : '')"><?php _e('Add from wp-content', 'wpsynchro'); ?></button>
            </div>

            <fieldset>
                <legend>Quick add</legend>
                <button type="button" v-on:click="quickAddFileLocation('webroot')" v-bind:disabled="isReadWriteRetrictedSourceTarget('webroot')" v-bind:title="(isReadWriteRetrictedSourceTarget('webroot') ? '<?php echo $std_readwrite_error; ?>' : '')"><?php _e('Web root', 'wpsynchro'); ?></button>
                <button type="button" v-on:click="quickAddFileLocation('themes')" v-bind:disabled="isReadWriteRetrictedSourceTarget('themes')" v-bind:title="(isReadWriteRetrictedSourceTarget('themes') ? '<?php echo $std_readwrite_error; ?>' : '')"><?php _e('Themes', 'wpsynchro'); ?></button>
                <button type="button" v-on:click="quickAddFileLocation('plugins')" v-bind:disabled="isReadWriteRetrictedSourceTarget('plugins')" v-bind:title="(isReadWriteRetrictedSourceTarget('plugins') ? '<?php echo $std_readwrite_error; ?>' : '')"><?php _e('Plugins', 'wpsynchro'); ?></button>
                <button type="button" v-on:click="quickAddFileLocation('uploads')" v-bind:disabled="isReadWriteRetrictedSourceTarget('uploads')" v-bind:title="(isReadWriteRetrictedSourceTarget('uploads') ? '<?php echo $std_readwrite_error; ?>' : '')"><?php _e('Uploads', 'wpsynchro'); ?></button>
            </fieldset>

            <h3><?php _e('Locations', 'wpsynchro'); ?></h3>
            <p v-if="migration.file_locations.length == 0"><?php _e('No files or directories selected yet. Add them with the buttons above.', 'wpsynchro'); ?></p>

            <div class="locationstable" v-if="migration.file_locations.length > 0">

                <div v-if="overlapping_file_sections.length > 0" class="syncerrors">
                    <div class="iconpart">&#9940;</div>
                    <div>
                        <p><b><?php _e('Please correct these locations:', 'wpsynchro') ?></b></p>
                        <ul>
                            <li v-for="(paths, index) in overlapping_file_sections"><?php _e('<u>{{paths[0]}}</u> overlaps with <u>{{paths[1]}}</u>', 'wpsynchro'); ?></li>
                        </ul>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th><?php _e('Type', 'wpsynchro'); ?></th>
                            <th><?php _e('Full path', 'wpsynchro'); ?></th>
                            <th><?php _e('Strategy', 'wpsynchro'); ?></th>
                            <th><?php _e('Exclusions', 'wpsynchro'); ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $file_locations_sections = ['outsidewebroot', 'webroot', 'wpcontent'];
                        foreach ($file_locations_sections as $file_location_section) {
                        ?>
                            <tr v-for="(location, key) in migration.file_locations" v-if="location.base == '<?= $file_location_section ?>'">
                                <input type="hidden" name="file_locations_base[]" v-bind:value="location.base"></input>
                                <input type="hidden" name="file_locations_path[]" v-bind:value="location.path"></input>
                                <input type="hidden" name="file_locations_strategy[]" v-bind:value="location.strategy"></input>
                                <input type="hidden" name="file_locations_isfile[]" v-bind:value="location.is_file"></input>
                                <input type="hidden" name="file_locations_exclusions[]" v-bind:value="location.exclusions"></input>


                                <td class="type">{{ (location.is_file ? "<?php _e('File', 'wpsynchro'); ?>" : "<?php _e('Dir', 'wpsynchro'); ?>") }}</td>
                                <td class="path"><code>{{ (showFullPath(location.base, location.path)) }}</code></td>

                                <td class="migratestrategy">
                                    <div v-if="location.strategy == 'keep' && !location.is_file"><?php _e('Keep', 'wpsynchro'); ?> <span title="<?php _e('Files on target not existing on source will be kept', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span></div>
                                    <div v-if="location.strategy == 'clean' && !location.is_file"><?php _e('Clean', 'wpsynchro'); ?> <span title="<?php _e('Files on target not present on source will be deleted', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span></div>
                                    <div v-if="location.is_file"><?php _e('Overwrite', 'wpsynchro'); ?> <span title="<?php _e('File will be overwritten', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span></div>
                                </td>
                                <td class="exclu">{{ (location.exclusions ? location.exclusions : "<?php _e('N/A', 'wpsynchro'); ?>") }}</td>
                                <td><span v-on:click="$delete(migration.file_locations, key)" title="<?php _e('Delete this location', 'wpsynchro'); ?>" class="deletelocation dashicons dashicons-trash"></span></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <h3><?php _e('Ask for user confirmation', 'wpsynchro'); ?></h3>
            <p><?php _e('Should the user be asked for confirmation before any file changes are done?', 'wpsynchro'); ?><br><?php _e('Beware: This will pause the migration, until the changes gets accepted or declined.', 'wpsynchro'); ?><br><?php _e('Beware: When running in WP-CLI, this user confirmation is always skipped, to prevent blocking.', 'wpsynchro'); ?></p>
            <div class="option">
                <div class="optionname">
                    <label><?php _e('User confirmation', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <label><input v-model="migration.files_ask_user_for_confirm" type="checkbox" name="files_ask_user_for_confirm" id="files_ask_user_for_confirm"></input> <?php _e('Ask user for confirmation of file changes', 'wpsynchro'); ?> <span title="<?php _e('The user will be presented with a modal popup, that contains lists of the files that will be added/changed or deleted. The user can then choose to accept or decline the changes.', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span> (<?php _e('Recommended', 'wpsynchro'); ?>)</label>
                </div>
            </div>

            <h3><?php _e('General exclusions', 'wpsynchro'); ?></h3>
            <p><?php _e('Exclude files or directories, separated by comma. Ex: .htaccess,favicon.ico,my-secret-dir', 'wpsynchro'); ?><br><?php _e('WP folders wp-admin, wp-includes and WP files in web root, as well as WP Synchro plugin and data are excluded.', 'wpsynchro'); ?><br><?php _e('These are applied to all file locations chosen in file/dir location list.', 'wpsynchro'); ?></p>
            <div class="option">
                <div class="optionname">
                    <label><?php _e('Exclusions', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <label><input v-model="migration.files_exclude_files_match" type="text" name="files_exclude_files_match" id="files_exclude_files_match" autocomplete="off" data-lpignore="true"></input></label>
                </div>
            </div>

        </div>

        <div class="dbsyncsetup" v-show="valid_endpoint && migration.sync_database && migration.sync_preset == 'none'">
            <div class="sectionheader"><span class="dashicons dashicons-update"></span> <?php _e('Database migration', 'wpsynchro'); ?></div>
            <h3><?php _e('Database migration settings', 'wpsynchro'); ?></h3>
            <div class="option <?= $is_pro ? "" : 'limited_in_free' ?>">
                <div class="optionname">
                    <label><?php _e('Backup database tables', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <label><input v-model="migration.db_make_backup" type="checkbox" name="db_make_backup" id="db_make_backup" <?php echo ($is_pro ? '' : 'disabled'); ?>></input> <?php _e('Backup chosen database tables to file', 'wpsynchro'); ?> <span title="<?php _e('Backup database tables before overwriting them. Will be written to a .sql file that can be imported again by phpmyadmin or equal tools.', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span> (<?php _e('Recommended', 'wpsynchro'); ?>)</label>
                    <pro-badge v-if="!is_pro"></pro-badge>
                </div>
            </div>

            <div class="option">
                <div class="optionname">
                    <label><?php _e('Table prefix migration', 'wpsynchro'); ?></label>
                </div>
                <div class="optionvalue">
                    <label><input v-model="migration.db_table_prefix_change" type="checkbox" name="db_table_prefix_change" id="db_table_prefix_change"></input> <?php _e('Migrate table prefix and data if needed', 'wpsynchro'); ?> <span title="<?php _e('Will rename database tables, so they match the correct prefix on target - Will also rename keys in rows in options and usermeta tables. This may cause problems, if the renames accidentally renames something it shouldnt, that is custom or used by another plugin', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span> (<?php _e('Recommended', 'wpsynchro'); ?>)</label><br>
                </div>
            </div>


            <h3><?php _e('Search/replace', 'wpsynchro'); ?></h3>
            <p><?php _e('Add your project specific search/replaces.', 'wpsynchro'); ?><br><?php _e('Search/replace is done in a case sensitive manner and in the order listed below.', 'wpsynchro'); ?></p>


            <div class="searchreplaces">
                <div class="searchreplaceheadlines">
                    <div><?php _e('Search', 'wpsynchro'); ?></div>
                    <div><?php _e('Replace', 'wpsynchro'); ?></div>
                </div>

                <draggable v-model="migration.searchreplaces" handle=".handle">
                    <div class="searchreplace" v-for="(replace, key) in migration.searchreplaces">
                        <div class="handle dashicons dashicons-move"></div>
                        <div><input v-model="replace.from" type="text" name="searchreplaces_from[]" autocomplete="off" data-lpignore="true"></input></div>
                        <div><input v-model="replace.to" type="text" name="searchreplaces_to[]" autocomplete="off" data-lpignore="true"></input></div>
                        <div v-on:click="$delete(migration.searchreplaces, key)" class="deletereplace dashicons dashicons-trash"></div>
                    </div>
                </draggable>
            </div>

            <div>
                <button class="addsearchreplace" v-on:click="addSearchReplace()" type="button"><?php _e('Add replace', 'wpsynchro'); ?></button>
                <button class="resetsearchreplace" v-on:click="createDefaultSearchReplaces()" type="button"><?php _e('Reset to recommended', 'wpsynchro'); ?></button>
            </div>

            <h3><?php _e('Tables to migrate', 'wpsynchro'); ?></h3>
            <div class="option">
                <div class="optionname">
                    <label><?php _e('Database tables', 'wpsynchro'); ?></label>
                    <p v-if="!migration.include_all_database_tables"><?php _e('<u>Win</u>: CTRL-A to mark all - Select/deselect tables by holding CTRL while clicking table', 'wpsynchro'); ?></p>
                    <p v-if="!migration.include_all_database_tables"><?php _e('<u>Mac</u>: &#8984;-A to mark all - Select/deselect tables by holding &#8984; while clicking table', 'wpsynchro'); ?></p>
                </div>
                <div class="optionvalue">
                    <p><label><input v-model="migration.include_all_database_tables" type="checkbox" name="include_all_database_tables" id="include_all_database_tables" checked="checked"></input> <?php _e('Migrate all database tables', 'wpsynchro'); ?></label></p>
                    <div v-if="! migration.include_all_database_tables" id="exclude_db_expanded_part">
                        <div>
                            <select v-model="migration.only_include_database_table_names" id="exclude_db_tables_select" name="only_include_database_table_names[]" multiple>
                                <option v-for="option in database_info.db_client_tables" v-bind:value="option">
                                    {{ option }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="database-preserve-data">
                <h3><?php _e('Preserve data in options table', 'wpsynchro'); ?></h3>
                <div class="wp-options-preserve" v-show="migration.include_all_database_tables || migration.only_include_database_table_names.includes(database_info.from_options_table)">
                    <p>
                        <?php _e('If you want to keep some of the settings from the options table on the target, select or add them here.', 'wpsynchro'); ?>
                    </p>

                    <div class="option">
                        <div class="optionname">
                            <label><?php _e('Active plugins', 'wpsynchro'); ?></label>
                        </div>
                        <div class="optionvalue">
                            <label><input v-model="migration.db_preserve_options_table_keys" type="checkbox" name="db_preserve_active_plugins" value="active_plugins" /> <?php _e('Preserve active plugins settings', 'wpsynchro'); ?> <span title=" <?php _e('Preserve which plugins are activated and which ones are not. When enabled, you will not risk having other plugins activated, that you dont already have activated', 'wpsynchro'); ?>" class="dashicons dashicons-editor-help"></span> (<?php _e('Recommended', 'wpsynchro'); ?>)</label>
                        </div>
                    </div>
                    <div class="option">
                        <div class="optionname">
                            <label><?php _e('Search engine visibility', 'wpsynchro'); ?></label>
                        </div>
                        <div class="optionvalue">
                            <label><input v-model="migration.db_preserve_options_table_keys" type="checkbox" name="db_preserve_blog_public" value="blog_public"> <?php _e('Preserve whether search engines are discouraged to index the site', 'wpsynchro'); ?> (<?php _e('Recommended', 'wpsynchro'); ?>)</label>
                        </div>
                    </div>

                    <h4><?php _e('Custom options keys', 'wpsynchro'); ?></h4>
                    <p>
                        <?php _e('Found in options table from "option_name" column. Separated by comma.', 'wpsynchro'); ?>
                    </p>
                    <div class="option">
                        <div class="optionname">
                            <label><?php _e('Custom options preserve', 'wpsynchro'); ?></label>
                        </div>
                        <div class="optionvalue">
                            <label><input v-model="migration.db_preserve_options_custom" type="text" placeholder="my_option_key,my_other_key" name="db_preserve_options_custom" autocomplete="off" data-lpignore="true"></label>
                        </div>
                    </div>
                </div>
                <p v-if="!migration.include_all_database_tables && !migration.only_include_database_table_names.includes(database_info.from_options_table)">
                    <?php _e('The options table is currently not selected for migration - Table name:', 'wpsynchro'); ?> {{ database_info.from_options_table }}
                </p>

            </div>
        </div>

        <div class="validate-errors" v-if="validate_errors.length > 0 && valid_endpoint">
            <div class="sectionheader sectionheadererror"><span class="dashicons dashicons-warning"></span> <?php _e('Could not save due to validation issues', 'wpsynchro'); ?></div>

            <ul>
                <li v-for="errortext in validate_errors">{{errortext}}</li>
            </ul>
        </div>

        <div class="savesetup" v-if="valid_endpoint">
            <div class="sectionheader"><span class="dashicons dashicons-edit"></span> <?php _e('Save migration', 'wpsynchro'); ?></div>
            <p>
                <input type="submit" v-on:click.prevent="actionsBeforeSubmit" v-if="valid_endpoint" value="<?php _e('Save', 'wpsynchro'); ?>"></input>
            </p>
        </div>

    </form>


    <b-modal ref="locationpickermodal" id="locationpickermodal" centered hide-footer hide-header lazy>
        <locationpicker v-bind:migration="migration" v-bind:is_local="files_locationpicker.islocal" v-bind:localserviceurl="files_locationpicker.localserviceurl" v-bind:fetchserviceurl="files_locationpicker.fetchserviceurl" v-bind:relativepath="files_locationpicker.relativepath" v-bind:relativebasename="files_locationpicker.relativebasename" v-bind:blockedpaths="files_locationpicker.blockedpaths" v-bind:location_template_obj="location_template_obj" files_locationpicker @add-location="addFileLocation"></locationpicker>
    </b-modal>



</div>