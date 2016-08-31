<?php

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'FMC_DisableBloggingPlugin' ) ) {
    
    class FMC_DisableBloggingPlugin {

        public function __construct() {
            // PLUGIN META
            add_filter( 'plugin_row_meta', array( $this, 'dsbl_plugin_meta' ), 10, 2 );
        }

        public function dsbl_plugin_meta( $links, $file ) { // Add meta links to plugin page
            if ( strpos( $file, 'disable-blogging.php' ) !== false ) {
                $meta = array(
                    'support' => '<a href="https://wordpress.org/support/plugin/disable-blogging" target="_blank"><span class="dashicons dashicons-sos"></span> ' . __( 'Support' ) . '</a>',
                    'review' => '<a href="https://wordpress.org/support/view/plugin-reviews/disable-blogging" target="_blank"><span class="dashicons dashicons-nametag"></span> ' . __( 'Review' ) . '</a>',
                    'github' => '<a href="https://github.com/factmaven/disable-blogging" target="_blank"><span class="dashicons dashicons-randomize"></span> ' . __( 'GitHub' ) . '</a>'
                );
                $links = array_merge( $links, $meta );
            }
            return $links;
        }
    }
}

if ( class_exists( 'FMC_DisableBloggingPlugin' ) ) { // Instantiate the plugin class
    global $dsbl;
    $dsbl = new FMC_DisableBloggingPlugin();
}