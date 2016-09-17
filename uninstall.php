<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { // Exit if accessed directly
    exit;
}

// Remove all plugin options with the prefix "factmaven_dsbl_"
global $wpdb;
$plugin_options = $wpdb -> get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'factmaven_dsbl_%'" );

foreach( $plugin_options as $option ) {
    delete_option( $option -> option_name );
}