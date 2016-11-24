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
        }
        # Remove additional menu items
        add_action( 'admin_menu', array( $this, 'main_menu' ), 10, 1 );
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
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

    public function main_menu() {
        if ( isset( $this->settings['main_menu'] ) ) {
            # Convert each new line in the textarea as an array item
            $menu_slug = explode( "\n", str_replace( "\r", "", $this->settings['main_menu'] ) );
            if ( is_array( $menu_slug ) || is_object( $menu_slug ) ) {
                foreach ( $menu_slug as $key => $value ) {
                    # Remove each menu item
                    remove_menu_page( $value );
                }
            }
        }
        global $pagenow;
        # If the menu items accessed and option is not set to 'none', redirect to selected page
        if ( isset( $menu_slug ) && in_array( $pagenow, $menu_slug, TRUE ) && $this->settings['redirect_menu'] != 'none' ) {
            wp_safe_redirect( admin_url( $this->settings['redirect_menu'] ), 301 );
            exit;
        }
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_Menu();