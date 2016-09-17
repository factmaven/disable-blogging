<?php

if ( ! defined( 'ABSPATH' ) ) { // Exit if accessed directly
    exit;
}

if ( ! class_exists( 'Fact_Maven_Disable_Blogging_General' ) ):
class Fact_Maven_Disable_Blogging_General {

    public function __construct() {
        $general_settings = get_option( 'factmaven_dsbl_general_settings' );

        if ( $general_settings['disable_posts'] == 'disable' ) {
            add_action( 'admin_menu', array( $this, 'posts_menu' ), 10, 1 );
            add_action( 'manage_users_columns', array( $this, 'post_column' ), 10, 1 );
            add_action( 'wp_dashboard_setup', array( $this, 'meta_boxes' ), 10, 1 );
            add_action( 'widgets_init', array( $this, 'widgets' ), 11, 1 );
            add_action( 'load-press-this.php', array( $this, 'press_this' ), 10, 1 );
            add_action( 'admin_init', array( $this, 'posting_options' ), 10, 1 );
            add_action( 'admin_enqueue_scripts', array( $this, 'hide_settings' ), 10, 1 );
            add_filter( 'enable_post_by_email_configuration', '__return_false', 10, 1 );
        }

        if ( $general_settings['disable_comments'] == 'disable' ) {
            add_action( 'admin_menu', array( $this, 'comments_menu' ), 10, 1 );
            add_action( 'init', array( $this, 'comments_column' ), 10, 1 );
            add_action( 'admin_enqueue_scripts', array( $this, 'comment_settings' ), 10, 1 );
        }
    }

    /**
     * Disable Posting related functions
     */
    public function posts_menu() { // Remove menu/submenu items & redirect to page menu
        $menu_slug = array(
            'edit.php', // Posts
            'separator1',  'separator2', 'separator3' // Separators
            );
        foreach ( $menu_slug as $main ) {
            remove_menu_page( $main );
        }
        $menu_slug = array(
            'tools.php' => 'tools.php', // Tools > Available Tools
            'options-general.php' => 'options-writing.php', // Settings > Writing
        );
        foreach( $menu_slug as $main => $sub ) {
            remove_submenu_page( $main, $sub );
        }
        global $pagenow;
        $page = array(
            'edit.php', // Posts
            'post-new.php', // New Post
            'edit-tags.php', // Tags
            'options-writing.php', // Settings > Writing
            );
        if ( in_array( $pagenow, $page, true ) && ( ! isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) {
            wp_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
            exit;
        }
    }

    public function post_column( $column ) { // Remove posts column
        unset( $column['posts'] );
        return $column;
    }

    public function meta_boxes() { // Disable blogging related meta boxes on the Dashboard
        remove_action( 'welcome_panel', 'wp_welcome_panel' ); // Welcome
        $meta_box = array(
            'dashboard_primary' => 'side', // WordPress Blog
            'dashboard_quick_press' => 'side', // Quick Draft
            'dashboard_right_now' => 'normal', // At a Glance
            'dashboard_incoming_links' => 'normal', // Incoming Links
            'dashboard_activity' => 'normal', // Activity
            'wpe_dify_news_feed' => 'normal' // WP Engine
            );
        foreach ( $meta_box as $id => $context ) {
            remove_meta_box( $id, 'dashboard', $context ); 
        }
    }

    public function widgets() { // Remove blog related widgets
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

    public function press_this() { // Disables "Press This" and redirect to homepage
        wp_safe_redirect( home_url(), 301 );
    }

    public function posting_options() { // Default the reading settings to a static page
        if ( 'posts' == get_option( 'show_on_front' ) ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_for_posts', 0 );
            update_option( 'page_on_front', 1 );
        }
        update_option( 'default_pingback_flag ', 0 );
        update_option( 'default_ping_status ', 0 );
        // update_option( 'default_comment_status', 0 );
    }

    public function hide_settings() {
        global $pagenow;
        // wp_enqueue_style( 'dsbl-wp-admin', plugin_dir_url( __FILE__ ) . 'css/wp-admin.css' );
        if ( $pagenow == 'tools.php' ) {
            wp_enqueue_style( 'dsbl-tools', plugin_dir_url( __FILE__ ) . 'css/tools.css' );
        }
        if ( $pagenow == 'options-reading.php' ) {
            wp_enqueue_style( 'dsbl-options-reading', plugin_dir_url( __FILE__ ) . 'css/options-reading.css' );
        }
    }

    /**
     * Disable Comment related functions
     */
    public function comments_menu() { // Remove menu/submenu items & redirect to page menu
        global $pagenow;
        $menu_slug = array(
            'edit-comments.php', // Comments
            'separator1',  'separator2', 'separator3' // Separators
            );
        foreach ( $menu_slug as $main ) {
            remove_menu_page( $main );
        }
        if ( in_array( $pagenow, $menu_slug, true ) && ( ! isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) {
            wp_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
            exit;
        }
    }

    public function comments_column() { // Remove comments column from posts & pages
        $menu_slug = array(
            'post' => 'comments', // Posts
            'page' => 'comments', // Pages
            'attachment' => 'comments' // Media
            );
        foreach ( $menu_slug as $item => $column ) {
            remove_post_type_support( $item, $column );
        }
    }

    public function comment_settings() {
        global $pagenow;
        wp_enqueue_style( 'dsbl-wp-admin', plugin_dir_url( __FILE__ ) . 'css/wp-admin.css' );
        if ( $pagenow == 'tools.php' ) {
            wp_enqueue_style( 'dsbl-tools', plugin_dir_url( __FILE__ ) . 'css/tools.css' );
        }
        if ( $pagenow == 'options-reading.php' ) {
            wp_enqueue_style( 'dsbl-options-reading', plugin_dir_url( __FILE__ ) . 'css/options-reading.css' );
        }
    }
}
endif;

new Fact_Maven_Disable_Blogging_General();