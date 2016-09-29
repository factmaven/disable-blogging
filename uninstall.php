<?php

# If uninstall is not called by WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )  exit;

# Remove options with the prefix "factmaven_dsbl_"
global $wpdb;
$plugin_options = $wpdb -> get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'factmaven_dsbl_%'" );
foreach( $plugin_options as $option ) {
    delete_option( $option -> option_name );
}