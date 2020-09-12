<?php
/**
 * General Plugin Functions
 * Enable or disable all blogging related functions.
 *
 * @author Fact Maven Corp.
 * @link https://wordpress.org/plugins/disable-blogging/
 */

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_General {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    private $settings;

    public function __construct() {
        # Get the plugin options
        $this->settings = get_option( 'factmaven_dsbl_general' );

        add_action( 'wp_before_admin_bar_render', array( $this, 'toolbar_menu' ), 10, 1 );
        # Remove blogging related menu items & redirect to 'Pages' menu
        add_action( 'admin_menu', array( $this, 'sidebar_menu' ), 10, 1 );
        # Remove blog related widgets
        add_action( 'widgets_init', array( $this, 'widgets' ), 11, 1 );
        # Remove blogging related meta boxes on the 'Dashboard'
        add_action( 'wp_dashboard_setup', array( $this, 'meta_boxes' ), 10, 1 );

        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            # Disable all posting related functions
            if ( $this->settings['posts'] == 'disable' ) {
                # Remove 'Posts' column from 'Users' page
                add_action( 'manage_users_columns', array( $this, 'post_column' ), 10, 1 );
                # Display custom post types in the 'Activity' meta box instead of posts
                add_filter( 'dashboard_recent_posts_query_args', array( $this, 'activity_meta_box' ), 10, 1 );
                # Disable 'Press This' function and redirect to homepage
                add_action( 'load-press-this.php', array( $this, 'press_this' ), 10, 1 );
                # Update options in 'Reading' and 'Discussion' settings
                add_action( 'admin_init', array( $this, 'posting_options' ), 10, 1 );
                # Hide post related options in the settings
                add_action( 'admin_enqueue_scripts', array( $this, 'post_options' ), 10, 1 );
                # Remove 'post' type from the REST API
                add_action( 'init', array( $this, 'rest_api_posts' ), 25, 1 );
                # Disable post-by-email functionality
                add_filter( 'enable_post_by_email_configuration', '__return_false', 10, 1 );
            }
            # Disable all comment relating functions
            if ( $this->settings['comments'] == 'disable' ) {
                # Disable support for comments & trackbacks in all post types
                add_action( 'init', array( $this, 'comment_support' ), 10, 1 );
                # Hide 'Recent Comments' section in the 'Activity' meta box
                add_action( 'admin_enqueue_scripts', array( $this, 'activity_comments' ), 10, 1 );
                # Update options in 'Discussion' settings
                add_action( 'admin_init', array( $this, 'comment_options' ), 10, 1 );
                # Hide existing comments from all post types
                add_filter( 'comments_array', array( $this, 'existing_comments' ), 10, 2 );
                # Close all posts from receiving comments
                add_filter( 'comments_open', '__return_false', 20, 2 );
            }
            # Disable author page
            if ( $this->settings['author_page'] == 'disable' ) {
                # Redirect author page to homepage
                add_action( 'template_redirect', array( $this, 'author_page' ), 10, 1 );
                # Replace author URL with the homepage
                add_filter( 'author_link', array( $this, 'author_link' ), 10, 1 );
            }
            # Disable all feeds, pingbacks, trackbacks, & XML-RPC function
            if ( $this->settings['feeds'] == 'disable' ) {
                # Remove feed links from the header
                add_action( 'wp_loaded', array( $this, 'header_feeds' ), 1, 1 );
                # Redirect all feeds to homepage
                add_action( 'template_redirect', array( $this, 'filter_feeds' ), 1, 1 );
                # Disable internal pingbacks
                add_action( 'pre_ping', array( $this, 'internal_pingbacks' ), 10, 1 );
                # Disable x-pingback
                add_filter( 'wp_headers', array( $this, 'x_pingback' ), 10, 1 );
                # Set pingback URI to blank for blog info
                add_filter( 'bloginfo_url', array( $this, 'pingback_url' ), 1, 2 );
                add_filter( 'bloginfo', array( $this, 'pingback_url' ), 1, 2 );
                # Disable XML-RPC methods
                add_filter( 'xmlrpc_methods', array( $this, 'xmlrpc_methods' ), 10, 1 );
                # Return other XML-RPC features to false
                add_filter( 'xmlrpc_enabled', '__return_false', 10, 1 );
                add_filter( 'pre_option_enable_xmlrpc', '__return_zero', 10, 1 );
                # Close all posts from pings
                add_filter( 'pings_open', '__return_false', 20, 2 );
                # Remove the generator name from the RSS feeds
                add_filter( 'the_generator', '__return_false', 10, 1 ); 
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function toolbar_menu() {
        # Define the list of toolbar items to hide
        $toolbar = array(
            'wp-logo', // WordPress Logo
            'search', // Search
        );
        # Remove additional toolbar items depending on the options
        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            if ( $this->settings['posts'] == 'disable' ) {
                $toolbar[] = 'new-post'; // New > Post
            }
            if ( $this->settings['comments'] == 'disable' ) {
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
        # Remove menu items based on the options
        $menu_slug = array();
        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            if ( $this->settings['posts'] == 'disable' ) {
                $menu_slug[] = 'edit.php'; // Posts
            }
            if ( $this->settings['comments'] == 'disable' ) {
                $menu_slug[] = 'edit-comments.php'; // Comments
            }
        }
        # Remove each menu item
        foreach ( $menu_slug as $main ) {
            remove_menu_page( $main );
        }
        # Remove each submenu item
        remove_submenu_page( 'tools.php', 'tools.php' ); // Tools > Available Tools
        # Remove additional submenu items bases on options
        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            if ( $this->settings['posts'] == 'disable' ) {
                remove_submenu_page( 'options-general.php', 'options-writing.php' ); // Settings > Writing
            }
            if ( $this->settings['comments'] == 'disable' ) {
                remove_submenu_page( 'options-general.php', 'options-discussion.php' ); // Settings > Discussion
            }
        }
        # Redirect menu items to 'Pages' depending on the options
        global $pagenow;
        $page_slug = array();
        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            if ( $this->settings['posts'] == 'disable' ) {
                $page_slug[] = 'edit.php'; // Posts
                $page_slug[] = 'post-new.php'; // New Post
                $page_slug[] = 'edit-tags.php'; // Tags
                $page_slug[] = 'options-writing.php'; // Settings > Writing
            }
            if ( $this->settings['comments'] == 'disable' ) {
                $page_slug[] = 'edit-comments.php'; // Comments
                $page_slug[] = 'options-discussion.php'; // Settings > Discussion
            }
        }
        # If the menu items are being accessed, redirect to 'Pages'
        if ( in_array( $pagenow, $page_slug, TRUE ) && $_SERVER['REQUEST_METHOD'] == 'GET' && ( ! isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) {
            wp_safe_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
            exit;
        }
    }

    public function widgets() {
        # Define the list of widget to remove
        $widgets = array(
            'WP_Widget_Calendar', // Calendar
            'WP_Widget_Links', // Links
            'WP_Widget_Meta', // Meta
            'WP_Widget_RSS', // RSS
        );
        if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
            if ( $this->settings['posts'] == 'disable' ) {
                $widgets[] = 'WP_Widget_Archives'; // Archives
                $widgets[] = 'WP_Widget_Categories'; // Categories
                $widgets[] = 'WP_Widget_Recent_Posts'; // Recent Posts
                $widgets[] = 'WP_Widget_Tag_Cloud'; // Tag Cloud
            }
            if ( $this->settings['comments'] == 'disable' ) {
                $widgets[] = 'WP_Widget_Recent_Comments'; // Recent Comments
            }
        }
        # Remove each widget
        foreach( $widgets as $item ) {
            unregister_widget( $item );
        }
    }

    public function meta_boxes() {
        # Remove the 'Welcome' panel
        remove_action( 'welcome_panel', 'wp_welcome_panel' ); // Welcome
        # Define the list of meta boxes to remove
        $meta_box = array(
            'dashboard_primary' => 'side', // WordPress Blog
            'dashboard_quick_press' => 'side', // Quick Draft
            'dashboard_right_now' => 'normal', // At a Glance
            'dashboard_incoming_links' => 'normal', // Incoming Links
            'wpe_dify_news_feed' => 'normal', // WP Engine
        );
        # Remove each meta box
        foreach ( $meta_box as $id => $context ) {
            remove_meta_box( $id, 'dashboard', $context ); 
        }
    }

    /* Disable Posts */
    public function post_column( $column ) {
        # Unset the 'Posts' column
        unset( $column['posts'] );
        return $column;
    }

    public function activity_comments() {
        global $pagenow;
        # If pagenow is 'Dashboard', hide comments section in 'Activity' meta box
        if ( $pagenow == 'index.php' ) {
            wp_enqueue_style( 'factmaven-dsbl-dashboard', plugin_dir_url( __FILE__ ) . 'css/dashboard.css' );
        }
    }

    public function activity_meta_box( $query_args ) {
        # Return custom posts types in 'Activity' meta box
        $query_args['post_type'] = get_post_types(
            array(
                'public' => TRUE,
                '_builtin' => FALSE,
            ), 'names', 'and' );
        return $query_args;
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
    }

    public function rest_api_posts() {
        global $wp_post_types;
        # If the API calls 'post', return false
        if ( isset( $wp_post_types['post'] ) ) {
            $wp_post_types['post']->show_in_rest = FALSE;
            return TRUE;
        }
        return FALSE;
    }

    /* Disable Comments */
    public function comment_support() {
        # If post type supports comments, remove comment & trackback support
        foreach ( get_post_types() as $type ) {
            if ( post_type_supports( $type, 'comments' ) ) {
                remove_post_type_support( $type, 'comments' );
                remove_post_type_support( $type, 'trackbacks' );
            }
        }
    }

    public function comment_options() {
        # 'Allow people to post comments on new articles' (unchecked)
        update_option( 'default_comment_status', 0 );
        # 'Comment must be manually approved' (checked)
        update_option( 'comment_moderation', 1 );
        # 'Comment author must have a previously approved comment' (checked)
        @update_option( 'comment_whitelist', 1 ); # deprecated since WP 5.5.0
        update_option( 'comment_previously_approved', 1 );
    }

    public function existing_comments( $comments ) {
        # Return empty array of comments
        $comments = array();
        return $comments;
    }

    /* Disable Author Page */
    public function author_page() {
        # If the author page is being accessed, redirect to homepage
        if ( is_author() ) {
            wp_safe_redirect( home_url(), 301 );
            exit;
        }
    }

    public function author_link() {
        # Return homepage URL
        return home_url();
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
        $this->redirect_feeds();
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