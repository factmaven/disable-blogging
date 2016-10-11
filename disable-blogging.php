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

# Call the required files
require_once( DSBL_PATH . 'admin/plugin-meta.php' );
require_once( DSBL_PATH . 'admin/settings-api.php' );
require_once( DSBL_PATH . 'admin/settings-page.php' );
require_once( DSBL_PATH . 'includes/functions-general.php' );
require_once( DSBL_PATH . 'includes/functions-extra.php' );
require_once( DSBL_PATH . 'includes/functions-profile.php' );
require_once( DSBL_PATH . 'includes/functions-menu.php' );