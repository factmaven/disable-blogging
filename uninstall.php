<?php
/**
 * 
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 */
// If plugin is not being uninstalled, exit (do nothing)
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'dsbl_remove_profile_fields' );
delete_option( 'dsbl_remove_menu_items' );