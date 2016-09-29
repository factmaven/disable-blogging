<?php

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_Extra {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    public function __construct() {
        # Get the plugin options
        $settings = get_option( 'factmaven_dsbl_extra_settings' );

        if ( is_array( $settings ) || is_object( $settings ) ) {
            if ( $settings['help_tabs'] == 'on' ) {
                # Remove all help tabs from admin header
                add_action( 'admin_head', array( $this, 'help_tabs' ), PHP_INT_MAX, 1 );
            }
            if ( $settings['howdy'] == 'on' ) {
                # Replace "Howdy," from the admin bar
                add_filter( 'admin_bar_menu', array( $this, 'howdy' ), 25, 1 );
            }
            if ( $settings['query_strings'] == 'on' ) {
                # Remove query strings from static resources
                add_filter( 'script_loader_src', array( $this, 'query_strings' ), 10, 1 );
                add_filter( 'style_loader_src', array( $this, 'query_strings' ), 10, 1 );
            }
            if ( $settings['emojis'] == 'on' ) {
                # Disable emojis
                add_action( 'init', array( $this, 'emojis' ), 10, 1 );
                # Remove the emoji's DNS prefetch
                add_filter( 'emoji_svg_url', '__return_false' );
                remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
                remove_action( 'admin_print_scripts', 'print_emoji_detection_script', 10 );
                remove_action( 'wp_print_styles', 'print_emoji_styles', 10 );
                remove_action( 'admin_print_styles', 'print_emoji_styles', 10 );
                remove_filter( 'the_content_feed', 'wp_staticize_emoji', 10 );
                remove_filter( 'comment_text_rss', 'wp_staticize_emoji', 10 );
                remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email', 10 );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function help_tabs() {
        # Remove help tabs
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
            /** This filter is documented in wp-includes/formatting.php */
            $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
            $urls = array_diff( $urls, array( $emoji_svg_url ) );
        }
        return $urls;
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_Extra();