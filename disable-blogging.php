<?php
/**
 * Plugin Name: Disable Blogging
 * Plugin URI: https://wordpress.org/plugins/disable-blogging/
 * Description: Turn WordPress into a non-blogging, CMS platform by disabling posts, comments, feeds, and other related the blogging features.
 * Version: 2.0.0
 * Author: Fact Maven
 * Author URI: https://www.factmaven.com/
 * License: GPLv3
 * Text Domain: dsbl
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

# Call the core Plugin API
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
# Get plugin's metadata 
$disable_blogging = get_plugin_data( __FILE__ );

# If the plugin version is lower or not defined, remove plugin options
if ( ( get_option( 'factmaven_dsbl_version' ) < $disable_blogging['Version'] ) || ! get_option( 'factmaven_dsbl_version' ) ) {
    # Remove options with the prefix "factmaven_dsbl_"
    foreach ( wp_load_alloptions() as $option => $value ) {
        if ( strpos( $option, 'factmaven_dsbl' ) === 0 ) {
            delete_option( $option );
        }
    }
    # Delete previous option from v1.3.0
    delete_option( 'dsbl_remove_profile_fields' );
    # Add options for new plugin version
    update_option( 'factmaven_dsbl_version', $disable_blogging['Version'] );
}

function my_plugin_load_plugin_textdomain() {
    load_plugin_textdomain( 'my-plugin', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'my_plugin_load_plugin_textdomain' );

# Call the required files
require_once( plugin_dir_path( __FILE__ ) . 'admin/plugin-meta.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/settings-api.php' );
require_once( plugin_dir_path( __FILE__ ) . 'admin/settings-page.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-general.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-extra.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-profile.php' );
require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-menu.php' );