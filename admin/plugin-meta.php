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
        # Handle localization
        add_action( 'plugins_loaded', array( $this, 'i18n' ), 0, 1 );
        # Add meta links to plugin page
        add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
    }

    public function i18n() {
        # Load the translations
        load_plugin_textdomain( 'dsbl', false, basename( dirname( __FILE__ ) ) . '/languages/' );
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
}

# Instantiate the class
new Fact_Maven_Disable_Blogging();
