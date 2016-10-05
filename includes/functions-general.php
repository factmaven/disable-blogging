<?php
/**
 * General Functions
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_General {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    public function __construct() {
        # Get the plugin options
        $settings = get_option( 'factmaven_dsbl_general_settings' );

        # Reorder `Pages` menu below the `Dashboard`
        add_filter( 'custom_menu_order', '__return_true', 10, 1 );
        add_filter( 'menu_order', array( $this, 'reorder_menu' ), 10, 1 );
        # Remove blogging related toolbar menu items
        add_action( 'wp_before_admin_bar_render', array( $this, 'toolbar_menu' ), 10, 1 );
        # Remove blogging related menu items & redirect to 'Pages' menu
        add_action( 'admin_menu', array( $this, 'sidebar_menu' ), 10, 1 );

        if ( is_array( $settings ) || is_object( $settings ) ) {
            # Disable all posting relate functions
            if ( $settings['disable_posts'] == 'disable' ) {
                # Remove 'Posts' column from 'Users' page
                add_action( 'manage_users_columns', array( $this, 'post_column' ), 10, 1 );
                # Remove blogging related meta boxes on the 'Dashboard'
                add_action( 'wp_dashboard_setup', array( $this, 'meta_boxes' ), 10, 1 );
                # Remove blog related widgets
                add_action( 'widgets_init', array( $this, 'widgets' ), 11, 1 );
                # Disable 'Press This' function and redirect to homepage
                add_action( 'load-press-this.php', array( $this, 'press_this' ), 10, 1 );
                # Update options in 'Reading' and 'Discussion' settings
                add_action( 'admin_init', array( $this, 'posting_options' ), 10, 1 );
                # Hide post related options in the settings
                add_action( 'admin_enqueue_scripts', array( $this, 'post_options' ), 10, 1 );
                # Disable post-by-email functionality
                add_filter( 'enable_post_by_email_configuration', '__return_false', 10, 1 );
            }
            # Disable all comment relating functions
            if ( $settings['disable_comments'] == 'disable' ) {
                # Remove 'Comments' column
                add_action( 'init', array( $this, 'comments_column' ), 10, 1 );
                # Remove blogging related meta boxes on the 'Dashboard'
                add_action( 'wp_dashboard_setup', array( $this, 'meta_boxes' ), 10, 1 );
                # Remove blog related widgets
                add_action( 'widgets_init', array( $this, 'widgets' ), 11, 1 );
                # Update options in 'Discussion' settings
                add_action( 'admin_init', array( $this, 'comment_options' ), 10, 1 );
                # Replace comments template with empty page
                add_filter( 'comments_template', array( $this, 'comments_template' ), 20, 1 );
                # Close all posts from comments
                add_filter( 'comments_open', '__return_false', 10, 2 );
            }
            # Disable Author page
            if ( $settings['disable_author_page'] == 'disable' ) {
                # Redirect author page to homepage
                add_action( 'template_redirect', array( $this, 'author_page' ), 10, 1 );
                # Replace author URL with the homepage
                add_filter( 'author_link', array( $this, 'author_link' ), 10, 1 );
            }
            # Disable all feeds, pingbacks, trackbacks, & XML-RPC function
            if ( $settings['disable_feeds'] == 'disable' ) {
                # Remove feed links from the header
                add_action( 'wp_loaded', array( $this, 'header_feeds' ), 1, 1 );
                # Redirect all feeds to homepage
                add_action( 'template_redirect', array( $this, 'filter_feeds' ), 1, 1 );
                # Disable internal pingbacks
                add_action( 'pre_ping', array( $this, 'internal_pingbacks' ), 10, 1 );
                # Disable x-pingback
                add_filter( 'wp_headers', array( $this, 'x_pingback' ), 10, 1 );
                # Filter feed related content
                add_action( 'plugins_loaded', array( $this, 'output_buffering' ), 10, 1 );
                # Set pingback URI to blank for blog info
                add_filter( 'bloginfo_url', array( $this, 'pingback_url' ), 1, 2 );
                add_filter( 'bloginfo', array( $this, 'pingback_url' ), 1, 2 );
                # Disable XML-RPC methods
                add_filter( 'xmlrpc_methods', array( $this, 'xmlrpc_methods' ), 10, 1 );
                # Return other XML-RPC features to false
                add_filter( 'xmlrpc_enabled', '__return_false', 10, 1 );
                add_filter( 'pre_option_enable_xmlrpc', '__return_zero', 10, 1 );
                # Close all posts from pings
                add_filter( 'pings_open', '__return_false', 10, 2 );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function reorder_menu() {
        # Return new page order
        $menu_slug = array(
            'index.php', // Dashboard
            'edit.php?post_type=page', // Pages
            );
        return $menu_slug;
    }

    public function toolbar_menu() {
        # Get the plugin options
        $settings = get_option( 'factmaven_dsbl_general_settings' );
        # Define the list of toolbar items to hide
        $toolbar = array(
            'wp-logo', // WordPress Logo
            'search', // Search
        );
        if ( is_array( $settings ) || is_object( $settings ) ) {
            if ( $settings['disable_posts'] == 'disable' ) {
                $toolbar[] = 'new-post'; // New > Post
            }
            if ( $settings['disable_comments'] == 'disable' ) {
                $toolbar[] = 'comments'; // Comments
            }
        }
        # Remove each toolbar menu item
        global $wp_admin_bar;
        foreach ( $toolbar as $item ) {
            $wp_admin_bar -> remove_menu( $item );
        }
    }

    public function sidebar_menu() {
        # Get the plugin options
        $settings = get_option( 'factmaven_dsbl_general_settings' );
        # Remove all menu separators
        global $menu;
        if ( ( is_array( $settings ) || is_object( $settings ) ) && $settings['separator'] == 'on' ) {
            foreach ( $menu as $group => $item ) {
                # If the menu title is blank, it's a separator
                if ( empty( $item[0] ) ) {
                    remove_menu_page( $item[2] );
                }
            }
        }
        # Check which menu item to hide based on the options
        if ( is_array( $settings ) || is_object( $settings ) ) {
            if ( $settings['disable_posts'] == 'disable' ) {
                $menu_slug[] = 'edit.php'; // Posts
            }
            if ( $settings['disable_comments'] == 'disable' ) {
                $menu_slug[] = 'edit-comments.php'; // Comments
            }
        }
        # Remove each menu item
        foreach ( $menu_slug as $main ) {
            remove_menu_page( $main );
        }
        # Remove each submenu item
        remove_submenu_page( 'tools.php', 'tools.php' ); // Tools > Available Tools
        if ( is_array( $settings ) || is_object( $settings ) ) {
            if ( $settings['disable_posts'] == 'disable' ) {
                remove_submenu_page( 'options-general.php', 'options-writing.php' ); // Settings > Writing
            }
            if ( $settings['disable_posts'] == 'disable' ) {
                remove_submenu_page( 'options-general.php', 'options-discussion.php' ); // Settings > Discussion
            }
        }
        # Define the list of menu items to redirect
        global $pagenow;
        $page_slug = array();
        if ( is_array( $settings ) || is_object( $settings ) ) {
            if ( $settings['disable_posts'] == 'disable' ) {
                $page_slug[] = 'edit.php'; // Posts
                $page_slug[] = 'post-new.php'; // New Post
                $page_slug[] = 'edit-tags.php'; // Tags
                $page_slug[] = 'options-writing.php'; // Settings > Writing
            }
            if ( $settings['disable_comments'] == 'disable' ) {
                $page_slug[] = 'edit-comments.php'; // Comments
                $page_slug[] = 'options-discussion.php'; // Settings > Discussion
            }
        }
        # If the menu items are being accessed, redirect to 'Pages'
        if ( in_array( $pagenow, $page_slug, true ) && $_SERVER['REQUEST_METHOD'] == 'GET' && ( ! isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) {
            wp_safe_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
            exit;
        }
    }

    public function post_column( $column ) {
        # Unset the 'Posts' column
        unset( $column['posts'] );
        return $column;
    }

    public function meta_boxes() {
        # Remove the `Welcome` panel
        remove_action( 'welcome_panel', 'wp_welcome_panel' ); // Welcome
        # Define the list of meta boxes to remove
        $meta_box = array(
            'dashboard_primary' => 'side', // WordPress Blog
            'dashboard_quick_press' => 'side', // Quick Draft
            'dashboard_right_now' => 'normal', // At a Glance
            'dashboard_incoming_links' => 'normal', // Incoming Links
            'dashboard_activity' => 'normal', // Activity
            'wpe_dify_news_feed' => 'normal', // WP Engine
            );
        # Remove each meta box
        foreach ( $meta_box as $id => $context ) {
            remove_meta_box( $id, 'dashboard', $context ); 
        }
    }

    public function widgets() {
        # Define the list of widget to remove
        $widgets = array(
            'WP_Widget_Archives', // Archives
            'WP_Widget_Calendar', // Calendar
            'WP_Widget_Categories', // Categories
            'WP_Widget_Links', // Links
            'WP_Widget_Meta', // Meta
            'WP_Widget_Recent_Comments', // Recent Comments
            'WP_Widget_Recent_Posts', // Recent Posts
            'WP_Widget_RSS', // RSS
            'WP_Widget_Tag_Cloud', // Tag Cloud
        );
        # Remove each widget
        foreach( $widgets as $item ) {
            unregister_widget( $item );
        }
    }

    public function press_this() {
        # Redirect to homepage
        wp_safe_redirect( home_url(), 301 );
    }

    public function posting_options() {
        /* Reading Settings */
        # Default the reading settings to a static page
        if ( 'posts' == get_option( 'show_on_front' ) ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_for_posts', 0 );
            update_option( 'page_on_front', 1 );
        }

        /* Discussion Settings */
        # 'Attempt to notify any blogs linked to from the article' (unchecked)
        update_option( 'default_pingback_flag ', 0 );
        # 'Allow link notifications from other blogs (pingbacks and trackbacks) on new articles' (unchecked)
        update_option( 'default_ping_status ', 0 );

        /* Permalink Settings */
        # 'Post name - http://example.com/sample-post/' (selected)
        update_option( 'permalink_structure ', '/%postname%/' );
    }

    public function post_options() {
        global $pagenow;
        # If pagenow is 'Menus', hide 'Posts' section
        if ( $pagenow == 'nav-menus.php' ) {
            wp_enqueue_style( 'factmaven-dsbl-nav-menus', plugin_dir_url( __FILE__ ) . 'css/nav-menus.css' );
        }
        # If pagenow is `Tools`, hide options
        if ( $pagenow == 'tools.php' ) {
            wp_enqueue_style( 'factmaven-dsbl-tools', plugin_dir_url( __FILE__ ) . 'css/tools.css' );
        }
        # If pagenow is 'Reading', hide options
        if ( $pagenow == 'options-reading.php' ) {
            wp_enqueue_style( 'factmaven-dsbl-options-reading', plugin_dir_url( __FILE__ ) . 'css/options-reading.css' );
        }
        # If pagenow is 'Permalinks', hide options
        if ( $pagenow == 'options-permalink.php' ) {
            wp_enqueue_style( 'factmaven-dsbl-options-reading', plugin_dir_url( __FILE__ ) . 'css/options-permalink.css' );
        }
    }

    /* Disable Comments */
    public function comments_column() {
        $menu_slug = array(
            'post' => 'comments', // Posts
            'page' => 'comments', // Pages
            'attachment' => 'comments', // Media
            );
        foreach ( $menu_slug as $item => $column ) {
            remove_post_type_support( $item, $column );
        }
    }

    public function comment_options() {
        # 'Allow people to post comments on new articles' (unchecked)
        update_option( 'default_comment_status', 0 );
        # 'Comment must be manually approved' (checked)
        update_option( 'comment_moderation', 1 );
        # 'Comment author must have a previously approved comment' (checked)
        update_option( 'comment_whitelist', 1 );
    }

    public function comments_template() {
        # Return blank file for comment template
        return plugin_dir_path( __FILE__ ) . 'index.php';
    }

    /* Disable Author Page */
    public function author_page() {
        # If the author archive page is being accessed, redirect to homepage
        if ( is_author() ) {
            wp_safe_redirect( get_home_url(), 301 );
            exit;
        }
    }

    public function author_link() {
        # Return homepage URL
        return get_home_url();
    }

    /* Disable Feeds & Related */
    public function header_feeds() {
        # Get a list of header items
        $feed = array(
            'feed_links' => 2, // General feeds
            'feed_links_extra' => 3, // Extra feeds
            'rsd_link' => 10, // Really Simply Discovery & EditURI
            'wlwmanifest_link' => 10, // Windows Live Writer manifest
            'index_rel_link' => 10, // Index link
            'parent_post_rel_link' => 10, // Prev link
            'start_post_rel_link' => 10, // Start link
            'adjacent_posts_rel_link' => 10, // Relational links
            'wp_generator' => 10, // WordPress version
            'wp_resource_hints' => 2, // Resource Hints
            );
        # Remove each feed-related item from the header
        foreach ( $feed as $function => $priority ) {
            remove_action( 'wp_head', $function, $priority );
        }
    }

    public function filter_feeds() {
        # If the query is not a feed or 404 page, return
        if ( ! is_feed() || is_404() ) {
            return;
        }
        # Call function to redirect feeds
        $this -> redirect_feeds();
    }

    private function redirect_feeds() {
        global $wp_rewrite, $wp_query;
        # If the query contains `feed` remove from URL
        if ( isset( $_GET['feed'] ) ) {
            wp_safe_redirect( esc_url_raw( remove_query_arg( 'feed' ) ), 301 );
            exit;
        }
        # If the query contains `feed` remove from URL
        if ( get_query_var( 'feed' ) !== 'old' ) {
            set_query_var( 'feed', '' );
        }
        # Automatically redirect feed links to the proper URL
        redirect_canonical();
        # Alternative to redirect `feed` queries if canonical doesn't work
        $url_struct = ( ! is_singular() && is_comment_feed() ) ? $wp_rewrite -> get_comment_feed_permastruct() : $wp_rewrite -> get_feed_permastruct();
        $url_struct = preg_quote( $url_struct, '#' );
        $url_struct = str_replace( '%feed%', '(\w+)?', $url_struct );
        $url_struct = preg_replace( '#/+#', '/', $url_struct );
        $url_current = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url_new = preg_replace( '#' . $url_struct . '/?$#', '', $url_current );
        # If the new URL doesn't match the current URL, redirect
        if ( $url_new != $url_current ) {
            wp_safe_redirect( $url_new, 301 );
            exit;
        }
    }

    public function internal_pingbacks( &$links ) {
        # Unset each internal ping
        foreach ( $links as $l => $link ) {
            if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
                unset( $links[$l] );
            }
        }
    }

    public function x_pingback( $headers ) {
        # Unset x-pingback
        unset( $headers['X-Pingback'] );
        return $headers;
    }

    public function output_buffering() {
        # Remove 'pingback' from header
        ob_start( array( $this, 'pingback_header' ) );
        # Remove 'GMPG' from header
        ob_start( array( $this, 'gmpg_header' ) );
    }

    public function pingback_header( $buffer ) {
        # If in the admin panel, don't run
        if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
            return $buffer;
        }
        # Find and remove 'pingback' meta tags
        $buffer = preg_replace( '/(<link.*?rel=("|\')pingback("|\').*?href=("|\')(.*?)("|\')(.*?)?\/?>|<link.*?href=("|\')(.*?)("|\').*?rel=("|\')pingback("|\')(.*?)?\/?>)/i', '', $buffer );
        return $buffer;
    }

    public function gmpg_header( $buffer ) {
        # If in the admin panel, don't run
        if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
            return $buffer;
        }
        # Find and remove 'profile' meta tags
        $buffer = preg_replace( '/(<link.*?rel=("|\')profile("|\').*?href=("|\')(.*?)("|\')(.*?)?\/?>|<link.*?href=("|\')(.*?)("|\').*?rel=("|\')profile("|\')(.*?)?\/?>)/i', '', $buffer );
        return $buffer;
    }

    public function pingback_url( $output, $show ) {
        # If pingback URL is called, set it to blank
        if ( $show == 'pingback_url' ) {
            $output = '';
        }
        return $output;
    }

    public function xmlrpc_methods( $methods ) {
        # Unset Pingback Ping
        unset( $methods['pingback.ping'] );
        unset( $methods['pingback.extensions.getPingbacks'] );
        # Unset discovery of existing users
        unset( $methods['wp.getUsersBlogs'] );
        # Unset list of available methods
        unset( $methods['system.multicall'] );
        unset( $methods['system.listMethods'] );
        # Unset list of capabilities
        unset( $methods['system.getCapabilities'] );
        return $methods;
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_General();