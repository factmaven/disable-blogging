<?php
/**
 * Plugin Name: Disable Blogging
 * Plugin URI: https://wordpress.org/plugins/disable-blogging/
 * Description: Turn WordPress into a non-blogging, CMS platform by disabling posts, comments, feeds, and other related the blogging features.
 * Version: 2.0.0
 * Author: Fact Maven
 * Author URI: https://www.factmaven.com/
 * License: GPLv3
 * Text Domain: disable-blogging
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

# Defines used throughout the plugin
define( 'DSBL_BASE', plugin_basename( __FILE__ ) );
define( 'DSBL_PATH', plugin_dir_path( __FILE__ ) );
define( 'DSBL_VER', '2.0.0' );

$version = get_option( 'factmaven_dsbl_version' );
if ( $version < DSBL_VER ) {
    # Remove options with the prefix "factmaven_dsbl_"
    global $wpdb;
    $plugin_options = $wpdb -> get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'factmaven_dsbl_%'" );
    foreach( $plugin_options as $option ) {
    delete_option( $option -> option_name );
    }
    # Delete previous option from v1.3.0
    delete_option( 'dsbl_remove_profile_fields' );
    # Add options for plugin version
    add_option( 'factmaven_dsbl_version', '2.0.0' );
}

# Call the required files
require_once( DSBL_PATH . 'admin/plugin-meta.php' );
require_once( DSBL_PATH . 'admin/settings-api.php' );
require_once( DSBL_PATH . 'admin/settings-page.php' );
require_once( DSBL_PATH . 'includes/functions-general.php' );
require_once( DSBL_PATH . 'includes/functions-extra.php' );
require_once( DSBL_PATH . 'includes/functions-profile.php' );
require_once( DSBL_PATH . 'includes/functions-menu.php' );

/*add_action( 'upgrader_process_complete', 'fact_maven_dsbl_upgrade', 10, 2 );

function fact_maven_dsbl_upgrade( $upgrader_object, $options ) {
    if ( $options['action'] == 'update' && $options['type'] == 'plugin' ){
       foreach ( $options['packages'] as $each_plugin ) {
          if ( $each_plugin== DSBL_BASE ) {
             // .......................... YOUR CODES .............
          }
       }
    }
}*/