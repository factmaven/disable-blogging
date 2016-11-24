<?php
/**
 * Display the plugin meta links as well as a
 * link to the settings page.
 *
 * @author Fact Maven Corp.
 * @link https://wordpress.org/plugins/disable-blogging/
 */

class Fact_Maven_Disable_Blogging {

    public function __construct() {
        # Add meta links to plugin page
        add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
        # Add link to plugin settings
        add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
        # Handle localisation
        add_action( 'plugins_loaded', array( $this, 'i18n' ), 0 );
    }

    public function plugin_row_meta( $links, $file ) {
        # Display meta links
        if ( strpos( $file, 'disable-blogging/disable-blogging.php' ) !== FALSE ) {
            $meta = array(
                'support' => '<a href="https://wordpress.org/support/plugin/disable-blogging" target="_blank"><span class="dashicons dashicons-sos"></span> ' . __( 'Support', 'dsbl' ) . '</a>',
                'review' => '<a href="https://wordpress.org/support/plugin/disable-blogging/reviews/" target="_blank"><span class="dashicons dashicons-thumbs-up"></span> ' . __( 'Review', 'dsbl' ) . '</a>',
                'github' => '<a href="https://github.com/factmaven/disable-blogging" target="_blank"><span class="dashicons dashicons-randomize"></span> ' . __( 'GitHub', 'dsbl' ) . '</a>',
            );
            $links = array_merge( $links, $meta );
        }
        # Return plugin meta links
        return $links;
    }

    public function plugin_action_links( $links, $file ) {
        # Display settings link
        if ( $file == 'disable-blogging/disable-blogging.php' && current_user_can( 'manage_options' ) ) {
            array_unshift(
                $links,
                '<a href="options-general.php?page=blogging"><span class="dashicons dashicons-admin-settings"></span> ' . __( 'Settings', 'dsbl' ) . '</a>'
            );
        }
        # Return the settings link
        return $links;
    }

    public function i18n() {
        # Load the translation of the plugin
        load_plugin_textdomain( 'dsbl', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging();