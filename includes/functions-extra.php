<?php
/**
 * Extra Plugin Functions
 * Miscellaneous features to enable or disable.
 *
 * @author Fact Maven Corp.
 * @link https://wordpress.org/plugins/disable-blogging/
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_Extra {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    private $settings;

    public function __construct() {
        # Get the plugin options
        $this->settings = get_option( 'factmaven_dsbl_extra' );

        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            if ( $this->settings['screen_options'] == 'on' ) {
                # Remove all screen options tab from admin header
                add_filter( 'screen_options_show_screen', '__return_false' );
            }
            if ( $this->settings['help_tabs'] == 'on' ) {
                # Remove all help tabs from admin header
                add_action( 'admin_head', array( $this, 'help_tabs' ), PHP_INT_MAX, 1 );
            }
            if ( $this->settings['admin_greeting'] == 'on' ) {
                # Remove greeting in the admin bar
                add_filter( 'admin_bar_menu', array( $this, 'admin_greeting' ), 25, 1 );
            }
            if ( $this->settings['query_strings'] == 'removed' ) {
                # Remove query strings from static resources
                add_filter( 'script_loader_src', array( $this, 'query_strings' ), 10, 1 );
                add_filter( 'style_loader_src', array( $this, 'query_strings' ), 10, 1 );
            }
            if ( $this->settings['emojis'] == 'on' ) {
                # Disable emojis
                add_action( 'init', array( $this, 'emojis' ), 10, 1 );
                # Remove all other emoji support
                add_filter( 'emoji_svg_url', '__return_false' );
                remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
                remove_action( 'admin_print_scripts', 'print_emoji_detection_script', 10 );
                remove_action( 'wp_print_styles', 'print_emoji_styles', 10 );
                remove_action( 'admin_print_styles', 'print_emoji_styles', 10 );
                remove_filter( 'the_content_feed', 'wp_staticize_emoji', 10 );
                remove_filter( 'comment_text_rss', 'wp_staticize_emoji', 10 );
                remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email', 10 );
            }
            if ( $this->settings['admin_footer'] != 'default' ) {
                # Change the admin footer
                add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 10, 1 );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function help_tabs() {
        # Remove help tabs
        if( is_admin() ) {
        echo '<style type="text/css">
                #contextual-help-link-wrap { display: none !important; }
              </style>';
        }
    }

    public function admin_greeting( $wp_admin_bar ) {
        # Remove admin greeting in all languages
        if ( 0 != get_current_user_id() ) {
            $wp_admin_bar->add_menu( array(
                'id' => 'my-account',
                'parent' => 'top-secondary',
                'title' => wp_get_current_user()->display_name . get_avatar( get_current_user_id(), 28 ),
                'href' => get_edit_profile_url( get_current_user_id() ),
                'meta' => array( 'class' => ( get_avatar( get_current_user_id(), 28 ) ) ? 'with-avatar' : '', ),
            ) );
            return $wp_admin_bar;
        }
    }

    public function query_strings( $query ) {
        # Remove all query strings with 'ver=' or 'v='
        if ( strpos( $query, '?ver=' ) || strpos( $query, '&ver=' ) || strpos( $query, '&v=' ) ) {
            $query = remove_query_arg( 'ver', $query );
        }
        # Return all scripts without version query strings
        return $query;
    }

    public function emojis() {
        # Remove the TinyMCE emoji plugin
        add_filter( 'tiny_mce_plugins', array( $this, 'tinymce_emojis' ), 10, 1 );
        # Remove emoji CDN hostname from DNS prefetching hints
        add_filter( 'wp_resource_hints', array( $this, 'emojis_dns_prefetch' ), 10, 2 );
    }

    public function tinymce_emojis( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, array( 'wpemoji' ) );
        }
        else {
            return array();
        }
    }

    public function emojis_dns_prefetch( $urls, $relation_type ) {
        if ( 'dns-prefetch' == $relation_type ) {
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        return $urls;
    }

    public function admin_footer() {
        # Change footer based on option
        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            if ( $this->settings['admin_footer'] == 'site_info' ) {
                # Return copyright, current year, and site name
                return __( 'Copyright &copy; ' . date("Y") . ' <a href="' . get_bloginfo( 'url' ) . '">' . get_bloginfo( 'name' ) . '</a>', 'dsbl' );
            }
            if ( $this->settings['admin_footer'] == 'remove' ) {
                # Return nothing
                return;
            }
        }
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_Extra();
