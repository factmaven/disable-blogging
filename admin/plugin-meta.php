<?php

class Fact_Maven_Disable_Blogging {

    public function __construct() {
        # Add meta links to plugin page
        add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
        # Add link to plugin settings
        add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
    }

    public function plugin_row_meta( $links, $file ) {
        # Display meta links
        if ( strpos( $file, DSBL_BASE ) !== FALSE ) {
            $meta = array(
                'support' => '<a href="https://wordpress.org/support/plugin/disable-blogging" target="_blank"><span class="dashicons dashicons-sos"></span> ' . __( 'Support' ) . '</a>',
                'review' => '<a href="https://wordpress.org/support/plugin/disable-blogging/reviews/" target="_blank"><span class="dashicons dashicons-thumbs-up"></span> ' . __( 'Review' ) . '</a>',
                'github' => '<a href="https://github.com/factmaven/disable-blogging" target="_blank"><span class="dashicons dashicons-randomize"></span> ' . __( 'GitHub' ) . '</a>',
            );
            $links = array_merge( $links, $meta );
        }
        # Return plugin meta links
        return $links;
    }

    public function plugin_action_links( $links, $file ) {
        # Display settings link
        if ( $file == DSBL_BASE && current_user_can( 'manage_options' ) ) {
            array_unshift(
                $links,
                '<a href="options-general.php?page=blogging"><span class="dashicons dashicons-admin-settings"></span> ' . __( 'Settings' ) . '</a>'
            );
        }
        # Return the settings link
        return $links;
    }
}

new Fact_Maven_Disable_Blogging();