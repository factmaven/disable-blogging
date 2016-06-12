<?php
/*
    Plugin Name: Disable Blogging
    Plugin URI: https://wordpress.org/plugins/disable-blogging/
    Description: Disables posts, comments, and other related the blogging features in WordPress, 'nuff said.
    Version: 1.1.1
    Author: <a href="https://www.factmaven.com/">Fact Maven Corp.</a>
    License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'Disable_Blogging' ) )
{
    class Disable_Blogging {

        public function __construct() {
            // HOOKS
            do_action( 'dsbl_hook' );

            // GLOBAL DEFINES
            define( 'DISALLOW_FILE_EDIT', true ); // Disable themes & plugins editor
            define( 'WP_POST_REVISIONS', false ); // Disable post revisions

            // ADD ACTIONS
            add_action( 'admin_notices', array( $this, 'dsbl_admin_notice' ), 10, 1 );
            add_action( 'admin_menu', array( $this, 'dsbl_sidebar_menu' ), 10, 1 );
            add_action( 'wp_before_admin_bar_render', array( $this, 'dsbl_toolbar_menu' ), 10, 1 );
            add_action( 'init', array( $this, 'dsbl_page_comments' ), 10, 1 );
            add_action( 'personal_options', array( $this, 'dsbl_user_profile' ), 10, 1 );
            add_action( 'pre_ping', array( $this, 'dsbl_pings_trackbacks' ), 10, 1 );
            add_action( 'wp_loaded', array( $this, 'dsbl_feeds' ), 1, 1 );
            add_action( 'widgets_init', array( $this, 'dsbl_widgets' ), 11, 1 );
            add_action( 'admin_head', array( $this, 'dsbl_help_tabs' ), 999, 1 );
            add_action( 'load-press-this.php', array( $this, 'dsbl_press_this' ), 10, 1 );

            // ADD FILTERS
            add_filter( 'plugin_row_meta', array( $this, 'dsbl_plugin_links' ), 10, 2 );
            add_filter( 'admin_bar_menu', array( $this, 'dsbl_howdy' ), 25, 1 );
            add_filter( 'comments_template', array( $this, 'dsbl_comments_template' ), 20, 1 );
            add_filter( 'script_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
            add_filter( 'style_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
        }

        /* ACTIONS
        -------------------------------------------------------------- */

        function dsbl_admin_notice() { // Disable conflicting plugins and display admin notice
            if( is_plugin_active( 'disable-blogging/disable-blogging.php' ) ) {
                global $pagenow;
                if( $pagenow == 'plugins.php' ) {
                    $plugins = array(
                        'disable-blog/disable-blog.php', // Disable Blog
                        'disable-comments/disable-comments.php', // Disable Comments
                        'disable-comments-wpz/dc-wpzest.php', // Disable Comments | WPZest
                        'wp-disable-comments/bootstrap.php', // WP Disable Comments
                        'comments-disable-accesspress/comments-disable-accesspress.php', // Comments Disable - AccessPress
                        'crudlab-disable-comments/crudlab-disable-comments.php', // CRUDLab Disable Comments
                        'disable-feeds/disable-feeds.php', // Disable Feeds
                        'disabler/disabler.php', // Disabler
                        'postless/postless.php' // Postless
                        );
                    foreach ( $plugins as $item ) {
                        deactivate_plugins( $item );
                    }
                    if ( current_user_can( 'install_plugins' ) ) {
                        echo '<div id="message" class="updated notice is-dismissible"><p>Please make sure to <strong>deactivate</strong> other blog disabling plugins to prevent conflicts.</p></div>';
                    }
                }
            }
        }

        function dsbl_sidebar_menu() { // Remove menu/submenu items & redirect to page menu
            $menu = array(
                'index.php', // Dashboard
                'edit.php', // Posts
                'edit-comments.php' // Comments
                );
            foreach ( $menu as $main ) {
                remove_menu_page( $main );
            }
            // $submenu = array(
            //     'tools.php' => 'tools.php', // Tools > Available Tools
            //     'options-general.php' => 'options-writing.php', // Settings > Writing
            //     'options-general.php' => 'options-discussion.php' // Settings > Discussion
            //     );
            // foreach ( $submenu as $main => $sub ) {
            //     remove_submenu_page( $main, $sub );
            // }

            $submenu = array(
                'tools.php' => 'tools.php', // Tools > Available Tools
                array( // Settings > Writing
                    'parent' => 'options-general.php',
                    'remove' => 'options-writing.php'
                    ),
                array( // Settings > Discussion
                    'parent' => 'options-general.php',
                    'remove' => 'options-discussion.php'
                    )
                );
            foreach ( $submenu as $main => $sub ) {
                if ( is_array( $sub ) ) {
                    foreach ($sub as $main1 => $sub1 ) {
                        remove_submenu_page( $main1, $sub1 );
                    }
                }
                else {
                    remove_submenu_page( $main, $sub );
                }
            }


            global $pagenow;
            $page = array(
                'index.php', // Dashboard
                'edit.php', // Posts
                'post-new.php', // New Post
                'edit-tags.php', // Tags
                'edit-comments.php', // Comments
                'tools.php', // Tools
                'options-writing.php', // Settings > Writing
                'options-discussion.php' // Settings > Discussion
                );
            if ( in_array( $pagenow, $page, true ) && ( ! isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) {
                wp_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
                exit;
            }
        }

        function dsbl_toolbar_menu() { // Remove menu items from the toolbar
            global $wp_admin_bar;
            $toolbar = array(
                'wp-logo', // WordPress logo
                'comments', // Comments
                'new-post', // New > Post
                'search' // Search
                );
            foreach ( $toolbar as $item ) {
                $wp_admin_bar -> remove_menu( $item );
            }
        }

        function dsbl_page_comments() { // Remove comments column from posts & pages
            $menu = array(
                'post' => 'comments', // Posts
                'page' => 'comments' // Pages
                );
            foreach ( $menu as $item => $column ) {
                remove_post_type_support( $item, $column );
            }
        }

        function dsbl_user_profile() { // Hide certain fields from user profile
            echo "\n" . '
            <script type="text/javascript">
            jQuery( document ).ready( function($) {
                $(\'form#your-profile > h2\').hide();
                $(\'form#your-profile > h3\').hide();
                $(\'form#your-profile > table:first\').hide();
                $(\'form#your-profile\').show();
                $(\'#url, #aim, #yim, #jabber, #googleplus, #twitter, #facebook, #description, #wpseo_author_title, #wpseo_author_metadesc\').parent().parent().hide();
            });
            </script>
            ' . "\n";
        }

        function dsbl_pings_trackbacks( &$links ) { // Disable pings and trackbacks
            foreach ( $links as $l => $link ) {
                if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
                    unset( $links[$l] );
                }
            }
        }

        function dsbl_feeds() { // Remove feed links & redirect to homepage
            $feed = array(
                'feed_links' => 2, // General feeds
                'feed_links_extra' => 3, // Extra feeds
                'rsd_link' => 10, // Really Simply Discovery & EditURI
                'wlwmanifest_link' => 10, // Windows Live Writer manifest
                'index_rel_link' => 10, // Index link
                'parent_post_rel_link' => 10, // Prev link
                'start_post_rel_link' => 10, // Start link
                'adjacent_posts_rel_link' => 10, // Relational links
                'wp_generator' => 10 // WordPress version
                );
            foreach ( $feed as $function => $priority ) {
                remove_action( 'wp_head', $function, $priority );
            }

            global $wp_rewrite;
            if( get_query_var( 'feed' ) !== 'old' ) {
                set_query_var( 'feed', '' );
            }
            redirect_canonical();   // Automatically determine appropriate redirect URL

            // If 'redirect_canonical' failed, try another way
            $url_struct = ( ! is_singular() && is_comment_feed() ) ? $wp_rewrite -> get_comment_feed_permastruct() : $wp_rewrite -> get_feed_permastruct();
            $url_struct = preg_quote( $url_struct, '#' );
            $url_struct = str_replace( '%feed%', '(\w+)?', $url_struct );
            $url_struct = preg_replace( '#/+#', '/', $url_struct );
            $requested_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $new_url = preg_replace( '#' . $url_struct . '/?$#', '', $requested_url );

            if( $new_url != $requested_url ) {
                wp_redirect( $new_url, 301 );
                exit;
            }
        }

        function dsbl_widgets() { // Remove blog related widgets
            $widgets = array(
                'WP_Widget_Archives', // Archives
                'WP_Widget_Calendar', // Calendar
                'WP_Widget_Categories', // Categories
                'WP_Widget_Links', // Links
                'WP_Widget_Meta', // Meta
                'WP_Widget_Recent_Comments', // Recent Comments
                'WP_Widget_Recent_Posts', // Recent Posts
                'WP_Widget_RSS', // RSS
                'WP_Widget_Tag_Cloud' // Tag Cloud
            );
            foreach( $widgets as $item ) {
                unregister_widget( $item );
            }
        }

        function dsbl_help_tabs() { // Remove help tabs
            get_current_screen() -> remove_help_tabs();
        }

        function dsbl_press_this() { // Disables "Press This" and redirect to homepage
            wp_redirect( home_url(), 301 );
        }

        /* FILTERS
        -------------------------------------------------------------- */

        function dsbl_plugin_links( $links, $file ) {
            if ( strpos( $file, 'disable-blogging.php' ) !== false ) { // Adds support and GitHub link to plugin page
                $meta = array(
                    'support' => '<a href="https://wordpress.org/support/plugin/disable-blogging" target="_blank"><span class="dashicons dashicons-sos"></span> ' . __( 'Support' ) . '</a>',
                    'review' => '<a href="https://wordpress.org/support/view/plugin-reviews/disable-blogging" target="_blank"><span class="dashicons dashicons-nametag"></span> ' . __( 'Review' ) . '</a>',
                    'github' => '<a href="https://github.com/factmaven/disable-blogging" target="_blank"><span class="dashicons dashicons-randomize"></span> ' . __( 'GitHub' ) . '</a>'
                );
                $links = array_merge( $links, $meta );
            }
            return $links;
        }

        function dsbl_howdy( $wp_admin_bar ) { // Removed "Howdy," from the admin bar, we ain't from Texas!
            $wp_admin_bar -> add_node( array(
                'id' => 'my-account',
                'title' => str_replace( 'Howdy, ', '', $wp_admin_bar -> get_node( 'my-account' ) -> title ),
            ) );
        }

        function dsbl_comments_template() { // Replaces theme's comments template with empty page
                return dirname( __FILE__ ) . '/includes/comments-template.php';
        }

        function dsbl_script_version( $src ) { // Remove query strings from static resources
            if( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
                $src = remove_query_arg( 'ver', $src );
            }
            return $src;
        }
    }
}

if ( class_exists( 'Disable_Blogging' ) ) { // Instantiate the plugin class
    $disable_blogging = new Disable_Blogging();
}