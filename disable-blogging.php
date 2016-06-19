<?php
/**
    Plugin Name: Disable Blogging
    Plugin URI: https://wordpress.org/plugins/disable-blogging/
    Description: Disables posts, comments, and other related the blogging features in WordPress, 'nuff said.
    Version: 1.2.0
    Author: <a href="https://www.factmaven.com/">Fact Maven Corp.</a>
    License: GPLv3
*/

/**
     Disable Blogging Plugin
     Copyright (C) 2011-2014, Fact Maven Corp. - contact@factmaven.com
     
     This program is free software: you can redistribute it and/or modify
     it under the terms of the GNU General Public License as published by
     the Free Software Foundation, either version 3 of the License, or
     (at your option) any later version.
     
     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.
     
     You should have received a copy of the GNU General Public License
     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Disable_Blogging' ) ) {
    
    class Disable_Blogging {

        public function __construct() {
            // HOOK
            do_action( 'plugin_disable_blogging' );

            // DEFINE CONSTANTS
            define( 'DSBL_FACTMAVEN', 'https://www.factmaven.com/' );         
            define( 'DSBL_WORDPRESS', 'https://wordpress.org/' );
            define( 'DSBL_GITHUB', 'https://github.com/factmaven/disable-blogging' );

            // PLUGIN INFO
            add_filter( 'plugin_row_meta', array( $this, 'dsbl_plugin_links' ), 10, 2 );
            add_action( 'all_admin_notices', array( $this, 'dsbl_admin_notice' ), 10, 1 );

            // ADMIN DASHBOARD
            add_action( 'admin_menu', array( $this, 'dsbl_sidebar_menu' ), 10, 1 );
            add_action( 'wp_before_admin_bar_render', array( $this, 'dsbl_toolbar_menu' ), 10, 1 );
            add_action( 'init', array( $this, 'dsbl_page_comments' ), 10, 1 );
            add_action( 'widgets_init', array( $this, 'dsbl_widgets' ), 11, 1 );
            add_action( 'load-press-this.php', array( $this, 'dsbl_press_this' ), 10, 1 );
            add_action( 'admin_head', array( $this, 'dsbl_help_tabs' ), 999, 1 );
            add_action( 'personal_options', array( $this, 'dsbl_user_profile' ), 10, 1 );
            add_filter( 'admin_bar_menu', array( $this, 'dsbl_howdy' ), 25, 1 );

            // FEEDS & RELATED
            add_action( 'init', array( $this, 'dsbl_htaccess' ), 10, 1 );
            add_action( 'wp_loaded', array( $this, 'dsbl_feeds' ), 1, 1 );
            add_action( 'pre_ping', array( $this, 'dsbl_internal_pingbacks' ), 10, 1 );
            add_filter( 'wp_headers', array( $this, 'dsbl_x_pingback' ), 10, 1 );
            add_filter( 'bloginfo_url', array( $this, 'dsbl_pingback_url' ), 1, 2 );
            add_filter( 'bloginfo', array( $this, 'dsbl_pingback_url' ), 1, 2 );
            add_filter( 'xmlrpc_enabled', array( $this, 'dsbl_xmlrpc_false' ), 10, 1 );
            add_filter( 'xmlrpc_methods', array( $this, 'dsbl_xmlrpc_methods' ), 10, 1 );

            // OTHER
            add_filter( 'comments_template', array( $this, 'dsbl_comments_template' ), 20, 1 );
            add_filter( 'script_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
            add_filter( 'style_loader_src', array( $this, 'dsbl_script_version' ), 10, 1 );
        }

        /* FUNCTIONS
        -------------------------------------------------------------- */

        // PLUGIN INFO
        public function dsbl_plugin_links( $links, $file ) { // Add meta links to plugin page
            if ( strpos( $file, 'disable-blogging.php' ) !== false ) {
                $meta = array(
                    'support' => '<a href="' . DSBL_WORDPRESS . 'support/plugin/disable-blogging" target="_blank"><span class="dashicons dashicons-sos"></span> ' . __( 'Support' ) . '</a>',
                    'review' => '<a href="' . DSBL_WORDPRESS . 'support/view/plugin-reviews/disable-blogging" target="_blank"><span class="dashicons dashicons-nametag"></span> ' . __( 'Review' ) . '</a>',
                    'github' => '<a href="' . DSBL_GITHUB . '" target="_blank"><span class="dashicons dashicons-randomize"></span> ' . __( 'GitHub' ) . '</a>'
                );
                $links = array_merge( $links, $meta );
            }
            return $links;
        }

        public function dsbl_admin_notice() { // Disable conflicting plugins and display admin notice
            if ( is_plugin_active( 'disable-blogging/disable-blogging.php' ) ) {
                global $pagenow;
                if ( $pagenow == 'plugins.php' ) {
                    $plugins = array( file( DSBL_FACTMAVEN . 'wp-content/uploads/disable-blogging.txt', FILE_IGNORE_NEW_LINES ) );
                    foreach ( $plugins as $item ) {
                        deactivate_plugins( $item );
                    }
                    if ( current_user_can( 'install_plugins' ) ) {
                        echo '<div id="message" class="updated notice is-dismissible"><p>Please make sure to <strong>deactivate</strong> other blog disabling plugins to prevent conflicts.</p></div>';
                    }
                }
            }
        }

        // ADMIN DASHBOARD
        public function dsbl_sidebar_menu() { // Remove menu/submenu items & redirect to page menu
            $menu = array(
                'index.php', // Dashboard
                'edit.php', // Posts
                'edit-comments.php' // Comments
                );
            foreach ( $menu as $main ) {
                remove_menu_page( $main );
            }
            remove_submenu_page( 'tools.php', 'tools.php' ); // Tools > Available Tools
            remove_submenu_page( 'options-general.php', 'options-writing.php' ); // Settings > Writing
            remove_submenu_page( 'options-general.php', 'options-discussion.php' ); // Settings > Discussion

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

        public function dsbl_user_profile() { // Hide certain fields from user profile
            echo "\n" . '
            <script type="text/javascript">
            jQuery( document ).ready( public function($) {
                $(\'form#your-profile > h2\').hide();
                $(\'form#your-profile > h3\').hide();
                $(\'form#your-profile > table:first\').hide();
                $(\'form#your-profile\').show();
                $(\'#url, #aim, #yim, #jabber, #googleplus, #twitter, #facebook, #description, #wpseo_author_title, #wpseo_author_metadesc\').parent().parent().hide();
            });
            </script>
            ' . "\n";
        }

        public function dsbl_howdy( $wp_admin_bar ) { // Removed "Howdy," from the admin bar, we ain't from Texas!
            $wp_admin_bar -> add_node( array(
                'id' => 'my-account',
                'title' => str_replace( 'Howdy, ', '', $wp_admin_bar -> get_node( 'my-account' ) -> title ),
            ) );
        }

        // FEEDS & RELATED
        public function dsbl_htaccess() { // Add rules to the .htaccess
            require_once( ABSPATH . '/wp-admin/includes/misc.php' );
            $rules = array();
            $rules[] = '<Files xmlrpc.php> # Disable XML-RPC';
            $rules[] = 'Order allow,deny';
            $rules[] = 'Deny from all';
            $rules[] = '</Files>';
            $rules[] = '';
            $rules[] = '<Files wlwmanifest.xml> # Disable Windows Live Writer';
            $rules[] = 'Order allow,deny';
            $rules[] = 'Deny from all';
            $rules[] = '</Files>';
            $htaccess_file = ABSPATH . '.htaccess';
            insert_with_markers( $htaccess_file, 'Disable Blogging', ( array ) $rules );
        }

        public function dsbl_feeds() { // Remove feed links & redirect to homepage
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
            if ( get_query_var( 'feed' ) !== 'old' ) {
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

            if ( $new_url != $requested_url ) {
                wp_redirect( $new_url, 301 );
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

        public function dsbl_xmlrpc_false() { // Disable XML-RPC
            return false;
        }

        public function dsbl_xmlrpc_methods( $methods ) { // Disable XML-RPC methods
            unset( $methods['pingback.ping'] );
            return $methods;
        }

        // OTHER
        public function dsbl_comments_template() { // Replaces theme's comments template with empty page
                return dirname( __FILE__ ) . '/includes/blank-template.php';
        }

        public function dsbl_script_version( $src ) { // Remove query strings from static resources
            if ( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
                $src = remove_query_arg( 'ver', $src );
            }
            return $src;
        }
    }
}

if ( class_exists( 'Disable_Blogging' ) ) { // Instantiate the plugin class
    $disable_blogging = new Disable_Blogging();
}