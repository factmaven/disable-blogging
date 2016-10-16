<?php
/**
    Plugin Name: Disable Blogging
    Plugin URI: https://wordpress.org/plugins/disable-blogging/
    Description: Disables posts, comments, feeds, and other related the blogging features. A must have plugin to turn WordPress into a non-blogging CMS platform.
    Version: 1.3.1
    Author: <a href="https://www.factmaven.com/">Fact Maven Corp.</a>
    License: GPLv3
    Text Domain: disable-blogging
*/

if ( !defined( 'ABSPATH' ) ) { // Exit if accessed directly
    exit;
}


if ( !class_exists( 'FMC_DisableBlogging' ) ) {

    class FMC_DisableBlogging {

        public function __construct() {
            // PLUGIN META & SETTINGS
            define( 'DSBL_PLUGIN', plugin_dir_path( __FILE__ ) );
            include( DSBL_PLUGIN . 'includes/settings-profile.php' );
            include( DSBL_PLUGIN . 'includes/plugin-meta.php' );
            add_option( 'factmaven_dsbl_version', '1.3.0' );

            // ADMIN DASHBOARD
            add_action( 'wp_dashboard_setup', array( $this, 'dsbl_meta_boxes' ), 10, 1 );
            add_action( 'admin_menu', array( $this, 'dsbl_sidebar_menu' ), 10, 1 );
            add_action( 'wp_before_admin_bar_render', array( $this, 'dsbl_toolbar_menu' ), 10, 1 );
            add_action( 'manage_users_columns', array( $this, 'dsbl_post_comment_column' ), 10, 1 );
            add_action( 'widgets_init', array( $this, 'dsbl_widgets' ), 11, 1 );
            add_action( 'load-press-this.php', array( $this, 'dsbl_press_this' ), 10, 1 );
            add_action( 'admin_head', array( $this, 'dsbl_help_tabs' ), 999, 1 );
            add_filter( 'enable_post_by_email_configuration', '__return_false', 10, 1 );
            add_filter( 'admin_bar_menu', array( $this, 'dsbl_howdy' ), 25, 1 );
            add_filter( 'custom_menu_order', '__return_true', 10, 1  );
            add_filter( 'menu_order', array( $this, 'dsbl_custom_menu_order' ), 10, 1 );
            add_filter( 'dashboard_recent_posts_query_args', array( $this, 'dsbl_dashboard_recent_posts_query_args' ), 10, 1 );

            // FEEDS & RELATED
            add_action( 'wp_loaded', array( $this, 'dsbl_header_feeds' ), 1, 1 );
            add_action( 'template_redirect', array( $this, 'dsbl_filter_feeds' ), 1, 1 );
            add_action( 'pre_ping', array( $this, 'dsbl_internal_pingbacks' ), 10, 1 );
            add_filter( 'wp_headers', array( $this, 'dsbl_x_pingback' ), 10, 1 );
            add_filter( 'bloginfo_url', array( $this, 'dsbl_pingback_url' ), 1, 2 );
            add_filter( 'bloginfo', array( $this, 'dsbl_pingback_url' ), 1, 2 );
            add_filter( 'xmlrpc_enabled', '__return_false', 10, 1 );
            add_filter( 'xmlrpc_methods', array( $this, 'dsbl_xmlrpc_methods' ), 10, 1 );

            // OTHER
            add_action( 'admin_init', array( $this, 'dsbl_reading_settings' ), 10, 1 );
            add_action( 'template_redirect', array( $this, 'dsbl_author_page' ), 10, 1 );
            add_filter( 'author_link', array( $this, 'dsbl_author_link' ), 10, 1 );
            add_filter( 'comments_template', array( $this, 'dsbl_comments_template' ), 20, 1 );
            add_filter( 'script_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
            add_filter( 'style_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
        }

        /* FUNCTIONS
        -------------------------------------------------------------- */

        // ADMIN DASHBOARD
        public function dsbl_meta_boxes() { // Disable blogging related meta boxes on the Dashboard
            remove_action( 'welcome_panel', 'wp_welcome_panel' ); // Welcome
            $metabox = array(
                'dashboard_primary' => 'side', // WordPress Blog
                'dashboard_secondary' => 'side', // Other WordPress News
                'dashboard_quick_press' => 'side', // Quick Press
                'dashboard_recent_drafts' => 'side', // Recent Drafts
                'dashboard_right_now' => 'normal', // Right Now
                'dashboard_recent_comments' => 'normal', // Recent Comments
                'dashboard_incoming_links' => 'normal', // Incoming Links
                'wpe_dify_news_feed' => 'normal' // WP Engine
                );
            foreach ( $metabox as $id => $context ) {
                remove_meta_box( $id, 'dashboard', $context );
            }
        }

        public function dsbl_get_all_custom_post_types() {
            $args = array(
                'public'   => true,
                '_builtin' => false
            );
            $output = 'names';
            $operator = 'and';
            $posttypes = get_post_types($args, $output, $operator);

            return $posttypes;
        }

        public function dsbl_dashboard_recent_posts_query_args($query_args) {
            $posttypes = $this->dsbl_get_all_custom_post_types();
            $query_args['post_type'] = $posttypes;

            return $query_args;
        }

        public function dsbl_sidebar_menu() { // Remove menu/submenu items & redirect to page menu
            $menu = array(
                'edit.php', // Posts
                'edit-comments.php', // Comments
                'separator1',  'separator2', 'separator3' // Separators
                );
            foreach ( $menu as $main ) {
                remove_menu_page( $main );
            }
            remove_submenu_page( 'tools.php', 'tools.php' ); // Tools > Available Tools
            remove_submenu_page( 'options-general.php', 'options-writing.php' ); // Settings > Writing
            remove_submenu_page( 'options-general.php', 'options-discussion.php' ); // Settings > Discussion

            global $pagenow;
            $page = array(
                'edit.php', // Posts
                'post-new.php', // New Post
                'edit-tags.php', // Tags
                'edit-comments.php', // Comments
                'options-writing.php', // Settings > Writing
                'options-discussion.php' // Settings > Discussion
                );
            if ( in_array( $pagenow, $page, true ) && $_SERVER["REQUEST_METHOD"] == "GET" && ( !isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) {
                wp_safe_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
                exit;
            }
        }

        public function dsbl_toolbar_menu() { // Remove menu items from the toolbar
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

        public function dsbl_post_comment_column( $column ) { // Remove posts & comments column
            unset( $column['posts'] );
            unset( $column['comments'] );
            return $column;
        }

        public function dsbl_widgets() { // Remove blog related widgets
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

        public function dsbl_press_this() { // Disables "Press This" and redirect to homepage
            wp_safe_redirect( home_url(), 301 );
        }

        public function dsbl_help_tabs() { // Remove help tabs
            get_current_screen() -> remove_help_tabs();
        }

        public function dsbl_howdy( $wp_admin_bar ) { // Removed "Howdy," from the admin bar, we ain't from Texas!
            $wp_admin_bar -> add_node( array(
                'id' => 'my-account',
                'title' => str_replace( 'Howdy, ', '', $wp_admin_bar -> get_node( 'my-account' ) -> title ),
            ) );
        }

        public function dsbl_custom_menu_order() { // move Pages up the top in the sidebar menu
            return array( 'index.php', 'edit.php?post_type=page' );
        }


        // FEEDS & RELATED
        public function dsbl_header_feeds() { // Remove feed links from the header
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
        }

        public function dsbl_filter_feeds() { // Prevent redirect loop
            if ( !is_feed() || is_404() ) {
                return;
            }
            $this -> dsbl_redirect_feeds();
        }

        private function dsbl_redirect_feeds() { // Redirect all feeds to homepage
            global $wp_rewrite, $wp_query;

            if ( isset( $_GET['feed'] ) ) {
                wp_safe_redirect( esc_url_raw( remove_query_arg( 'feed' ) ), 301 );
                exit;
            }

            if ( get_query_var( 'feed' ) !== 'old' ) {
                set_query_var( 'feed', '' );
            }
            redirect_canonical();

            $url_struct = ( !is_singular() && is_comment_feed() ) ? $wp_rewrite -> get_comment_feed_permastruct() : $wp_rewrite -> get_feed_permastruct();
            $url_struct = preg_quote( $url_struct, '#' );
            $url_struct = str_replace( '%feed%', '(\w+)?', $url_struct );
            $url_struct = preg_replace( '#/+#', '/', $url_struct );
            $url_current = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $url_new = preg_replace( '#' . $url_struct . '/?$#', '', $url_current );

            if ( $url_new != $url_current ) {
                wp_safe_redirect( $url_new, 301 );
                exit;
            }
        }

        public function dsbl_internal_pingbacks( &$links ) { // Disable internal pingbacks
            foreach ( $links as $l => $link ) {
                if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
                    unset( $links[$l] );
                }
            }
        }

        public function dsbl_x_pingback( $headers ) { // Disable x-pingback
            unset( $headers['X-Pingback'] );
            return $headers;
        }

        public function dsbl_pingback_url( $output, $show ) { // Remove pingback URLs
            if ( $show == 'pingback_url' ) $output = '';
            return $output;
        }

        public function dsbl_xmlrpc_methods( $methods ) { // Disable XML-RPC methods
            unset( $methods['pingback.ping'] );
            return $methods;
        }

        // OTHER
        public function dsbl_reading_settings() { // Default the reading settings to a static page
            if ( 'posts' == get_option( 'show_on_front' ) ) {
                update_option( 'show_on_front', 'page' );
                update_option( 'page_for_posts', 0 );
                update_option( 'page_on_front', 1 );
            }
        }

        public function dsbl_author_page() { // Redirect author page to homepage
            if ( is_author() ) {
                wp_safe_redirect( get_home_url(), 301 );
                exit;
            }
        }

        public function dsbl_author_link( $content ) { // Replace author URL with the homepage
            return get_home_url();
        }

        public function dsbl_comments_template() { // Replaces theme's comments template with empty page
            return DSBL_PLUGIN . '/includes/blank-template.php';
        }

        public function dsbl_script_version( $src ) { // Remove query strings from static resources
            if ( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
                $src = remove_query_arg( 'ver', $src );
            }
            return $src;
        }
    }
}

if ( class_exists( 'FMC_DisableBlogging' ) ) { // Instantiate the plugin class
    global $dsbl;
    $dsbl = new FMC_DisableBlogging();
}
