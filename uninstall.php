<?php
/**
 * Runs automatically when the plugin is deleted.
 *
 * @author Fact Maven Corp.
 * @link https://wordpress.org/plugins/disable-blogging/
 */

# If uninstall is not called by WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )  exit;

# Remove options with the prefix "factmaven_dsbl_"
foreach ( wp_load_alloptions() as $option => $value ) {
    if ( strpos( $option, 'factmaven_dsbl' ) === 0 ) {
        delete_option( $option );
    }
}

# Delete previous option from v1.3.0
delete_option( 'dsbl_remove_profile_fields' );