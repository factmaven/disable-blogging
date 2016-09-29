<?php

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_Menu {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    public function __construct() {
        # Get the plugin options
        $menu_settings = get_option( 'factmaven_dsbl_menu_settings' );

        if ( is_array( $menu_settings ) || is_object( $menu_settings ) ) {
            # Hide 
            if ( $menu_settings['hide_dashicons'] == 'yes' ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'admin_icons' ), 10, 1 );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    function admin_icons() {
        wp_enqueue_style( 'factmaven-dsbl-admin-icons', plugin_dir_url( __FILE__ ) . 'css/admin-icons.css' );
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_Menu();