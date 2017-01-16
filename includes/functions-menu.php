<?php
/**
 * Menu Plugin Functions
 * Hide additional menu items including ones
 * created by plugins and themes.
 *
 * @author Fact Maven Corp.
 * @link https://wordpress.org/plugins/disable-blogging/
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_Menu {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    private $settings;

    public function __construct() {
        # Get the plugin options
        $this->settings = get_option( 'factmaven_dsbl_menu' );

        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            # Hide all menu dashicons
            if ( $this->settings['dashicons'] == 'hidden' ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'admin_icons' ), 10, 1 );
            }
            # Remove menu separators
            if ( $this->settings['separator'] == 'removed' ) {
                add_action( 'admin_init', array( $this, 'separator' ), 10, 1 );
            }
            # Reorder `Pages` menu below the `Dashboard`
            if ( $this->settings['reorder_menu'] == 'on' ) {
                add_filter( 'custom_menu_order', '__return_true', 10, 1 );
                add_filter( 'menu_order', array( $this, 'reorder_menu' ), 10, 1 );
            }
            # Remove and redirect `Dashboard` menu
            if ( $this->settings['redirect_dashboard'] != 'none' ) {
                add_action( 'admin_menu', array( $this, 'redirect_dashboard' ), 10, 1 );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function redirect_dashboard() {
        # Remove the `Dashboard` menu item
        remove_menu_page( 'index.php' );
        # Redirect the `Dashboard` to the selected page
        global $pagenow;
        if ( $pagenow == 'index.php' ) {
            wp_safe_redirect( admin_url( $this->settings['redirect_dashboard'] ), 301 );
            exit;
        }
    }

    public function admin_icons() {
        # Apply CSS script to hide admin dashicons
        wp_enqueue_style( 'factmaven-dsbl-admin-icons', plugin_dir_url( __FILE__ ) . 'css/admin-icons.css' );
    }

    public function separator() {
        # Remove all menu separators
        global $menu;
        if ( is_array( $menu ) || is_object( $menu ) ) {
            foreach ( $menu as $group => $item ) {
                # If the menu title is blank, it's a separator
                if ( empty( $item[0] ) ) {
                    remove_menu_page( $item[2] );
                }
            }
        }
    }

    public function reorder_menu() {
        # Reorder admin menu
        $menu_slug = array(
            'index.php', // Dashboard
            'edit.php?post_type=page', // Pages
            );
        # Return new page order
        return $menu_slug;
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_Menu();