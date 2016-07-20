<?php
/**
    Plugin Name: Disable Blogging
    Plugin URI: https://wordpress.org/plugins/disable-blogging/
    Description: Disables posts, comments, feeds, and other related the blogging features in WordPress.
    Version: 1.2.5
    Author: <a href="https://www.factmaven.com/">Fact Maven Corp.</a>
    License: GPLv3
*/

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'FMC_Disable_Blogging' ) ) {
    
    class FMC_Disable_Blogging {

        public function __construct() {
            // DEFINE CONSTANTS
            define( 'DSBL_FACTMAVEN', 'https://www.factmaven.com/' );         
            define( 'DSBL_WORDPRESS', 'https://wordpress.org/' );
            define( 'DSBL_GITHUB', 'https://github.com/factmaven/disable-blogging' );
            define( 'DSBL_PLUGIN', plugin_dir_path( __FILE__ ) );

            // PLUGIN INFO
            include( DSBL_PLUGIN . 'includes/plugin-meta.php' );

            // ADMIN DASHBOARD
            add_action( 'admin_menu', array( $this, 'dsbl_sidebar_menu' ), 10, 1 );
            add_action( 'wp_before_admin_bar_render', array( $this, 'dsbl_toolbar_menu' ), 10, 1 );
            add_action( 'init', array( $this, 'dsbl_page_comments' ), 10, 1 );
            add_action( 'widgets_init', array( $this, 'dsbl_widgets' ), 11, 1 );
            add_action( 'load-press-this.php', array( $this, 'dsbl_press_this' ), 10, 1 );
            add_action( 'admin_head', array( $this, 'dsbl_help_tabs' ), 999, 1 );
            add_action( 'personal_options', array( $this, 'dsbl_user_profile' ), 10, 1 );
            add_filter( 'enable_post_by_email_configuration', '__return_false', 10, 1 );
            add_filter( 'admin_bar_menu', array( $this, 'dsbl_howdy' ), 25, 1 );

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
            add_filter( 'comments_template', array( $this, 'dsbl_comments_template' ), 20, 1 );
            add_filter( 'script_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
            add_filter( 'style_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
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
                wp_redirect( esc_url_raw( remove_query_arg( 'feed' ) ), 301 );
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
                wp_redirect( $url_new, 301 );
                exit;
            }
        }

        /* FUNCTIONS
        -------------------------------------------------------------- */

        // ADMIN DASHBOARD
        public function dsbl_sidebar_menu() { // Remove menu/submenu items & redirect to page menu
            $menu = array(
                'index.php', // Dashboard
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
            if ( in_array( $pagenow, $page, true ) && ( !isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) {
                wp_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
                exit;
            }

            if ( $pagenow == 'index.php' ) { // Redirect Dashboard to Profile menu
                wp_redirect( admin_url( 'profile.php' ), 301 );
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

        public function dsbl_page_comments() { // Remove comments column from posts & pages
            $menu = array(
                'post' => 'comments', // Posts
                'page' => 'comments', // Pages
                'attachment' => 'comments' // Media
                );
            foreach ( $menu as $item => $column ) {
                remove_post_type_support( $item, $column );
            }
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
            wp_redirect( home_url(), 301 );
        }

        public function dsbl_help_tabs() { // Remove help tabs
            get_current_screen() -> remove_help_tabs();
        }

        public function dsbl_user_profile() { // Hide unused fields from user profile
            ?>
            <script type="text/javascript">
            jQuery( document ).ready( function( $ ) {
                $( 'form#your-profile > h2' ).hide(); // Section titles
                $( 'form#your-profile > table:first' ).hide(); // Personal Options
                $( '#url' ).closest( 'tr' ).remove(); // Website
                $( '#description' ).closest( 'table' ).remove(); // About Yourself
                // Yoast SEO
                $( '#googleplus' ).closest( 'tr' ).remove(); // Google+
                $( '#twitter' ).closest( 'tr' ).remove(); // Twitter
                $( '#facebook' ).closest( 'tr' ).remove(); // Facebook
                $( '#wpseo_author_title' ).closest( 'table' ).remove(); // Author
            });
            </script>
            <?php
        }

        public function dsbl_howdy( $wp_admin_bar ) { // Removed "Howdy," from the admin bar, we ain't from Texas!
            $wp_admin_bar -> add_node( array(
                'id' => 'my-account',
                'title' => str_replace( 'Howdy, ', '', $wp_admin_bar -> get_node( 'my-account' ) -> title ),
            ) );
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

if ( class_exists( 'FMC_Disable_Blogging' ) ) { // Instantiate the plugin class
    global $dsbl;
    $dsbl = new FMC_Disable_Blogging();
}