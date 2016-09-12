<?php

if ( ! defined( 'ABSPATH' ) ) { // Exit if accessed directly
    exit;
}

if ( ! class_exists( 'Fact_Maven_Disable_Blogging_General' ) ):
class Fact_Maven_Disable_Blogging_General {

    public function __construct() {
        $general_settings = get_option( 'dsbl_general_settings' );

        if ( $general_settings['disable_posts'] == 'disable' ) {
            add_action( 'admin_menu', array( $this, 'post_menu' ), 10, 1 );
            add_action( 'wp_dashboard_setup', array( $this, 'meta_boxes' ), 10, 1 );
            add_action( 'widgets_init', array( $this, 'widgets' ), 11, 1 );
            add_action( 'admin_head', array( $this, 'reading_options' ), 10, 1 );
            add_action( 'load-press-this.php', array( $this, 'press_this' ), 10, 1 );
            // add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ), 10, 1 );
            add_filter( 'enable_post_by_email_configuration', '__return_false', 10, 1 );
        }

        if ( $general_settings['disable_comments'] == 'disable' ) {
           add_action( 'init', array( $this, 'page_comments' ), 10, 1 );
            add_action( 'manage_users_columns', array( $this, 'post_comment_column' ), 10, 1 );
        }
    }

    /**
     * Disable Posting related functions
     */
    public function post_menu() { // Remove menu/submenu items & redirect to page menu
        $menu = array(
            'edit.php', // Posts
            'separator1',  'separator2', 'separator3', // Separators
            );
        foreach ( $menu as $main ) {
            remove_menu_page( $main );
        }

        $submenu = array(
            'tools.php' => 'tools.php', // Tools > Available Tools
            'options-general.php' => 'options-writing.php', // Settings > Writing
        );
        foreach( $submenu as $main => $sub ) {
            remove_submenu_page( $main, $sub );
        }

        global $pagenow;
        $page = array(
            'edit.php', // Posts
            'post-new.php', // New Post
            'edit-tags.php', // Tags
            'options-writing.php', // Settings > Writing
            );
        if ( in_array( $pagenow, $page, true ) ) {
            wp_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
            exit;
        }
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

    public function post_comment_column( $column ) { // Remove posts column
        unset( $column['posts'] );
        unset( $column['comments'] );
        return $column;
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

    public function reading_options() {
        global $pagenow;
        ?>
        <style type="text/css">
        .nav-menus-php label[for="add-post-hide"],
        .control-section.add-post,
        .welcome-icon.welcome-write-blog,
        .users-php .column-posts,
        .control-section .accordion-section .add-post-type-post {
            display: none;
        }

        <?php if ( $pagenow == 'tools.php' ) { ?>
        .tools-php .card {
            display: none;
        }
        <?php } ?>

        <?php if ( $pagenow == 'options-reading.php' ) { ?>
        .options-reading-php #front-static-pages p:first-of-type,
        .options-reading-php .form-table tr:nth-child(2),
        .options-reading-php .form-table tr:nth-child(3),
        .options-reading-php .form-table tr:nth-child(4),
        .options-reading-php #front-static-pages ul li:last-child,
        .options-reading-php #front-static-pages ul li:nth-child(2),
        .options-reading-php #front-static-pages input[name="show_on_front"] {
            display: none;
        }
        #front-static-pages ul {
            margin: 0
        }
        <?php } ?>
        </style>
        <?php
    }

    public function press_this() { // Disables "Press This" and redirect to homepage
        wp_safe_redirect( home_url(), 301 );
    }

    public function enqueue_styles() {
        wp_enqueue_style( 'dsbl-wp-admin', plugin_dir_url( __FILE__ ) . 'css/wp-admin.css' );
    }

    /**
     * Disable Comment related functions
     */
    public function page_comments() { // Remove comments column from posts & pages
        $menu = array(
            'post' => 'comments', // Posts
            'page' => 'comments', // Pages
            'attachment' => 'comments' // Media
            );
        foreach ( $menu as $item => $column ) {
            remove_post_type_support( $item, $column );
        }
    }

}
endif;

new Fact_Maven_Disable_Blogging_General();