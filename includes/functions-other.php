<?php

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Fact_Maven_Disable_Blogging_Other' ) ):
class Fact_Maven_Disable_Blogging_Other {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    public function __construct() {
        # Get the plugin options
        $other_settings = get_option( 'factmaven_dsbl_other_settings' );

        if ( $other_settings[''] == '' ) {
            # Remove help tabs form admin header
            add_action( 'admin_head', array( $this, 'help_tabs' ), 9001, 1 );
            # Remove "Howdy," from the admin bar
            add_filter( 'admin_bar_menu', array( $this, 'howdy' ), 25, 1 );
            # Remove query strings from static resources
            add_filter( 'script_loader_src', array( $this, 'query_strings' ), 10, 1 );
            add_filter( 'style_loader_src', array( $this, 'query_strings' ), 10, 1 );
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function help_tabs() {
        get_current_screen() -> remove_help_tabs();
    }

    public function howdy( $wp_admin_bar ) {
        $wp_admin_bar -> add_node( array(
            'id' => 'my-account',
            'title' => str_replace( 'Howdy, ', '', $wp_admin_bar -> get_node( 'my-account' ) -> title ),
        ) );
    }

    public function query_strings( $src ) {
        if ( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }
}
endif;

# Instantiate the class
new Fact_Maven_Disable_Blogging_Other();