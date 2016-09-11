<?php

if ( ! defined( 'ABSPATH' ) ) { // Exit if accessed directly
    exit;
}

if ( ! class_exists( 'Fact_Maven_Disable_Blogging_General' ) ):
class Fact_Maven_Disable_Blogging_General {

    function __construct() {
        $general_settings = get_option( 'dsbl_general_settings' );

        if ( $general_settings['disable_posts'] == 'disable' ) { // Disable Posts
            add_action( 'wp_dashboard_setup', array( $this, 'meta_boxes' ), 10, 1 );
            add_action( 'widgets_init', array( $this, 'widgets' ), 11, 1 );
            add_action( 'load-press-this.php', array( $this, 'press_this' ), 10, 1 );
            add_filter( 'enable_post_by_email_configuration', '__return_false', 10, 1 );
        }

        if ( $general_settings['disable_comment'] == 'disable' ) { // Disable Comments
           add_action( 'init', array( $this, 'page_comments' ), 10, 1 );
            add_action( 'manage_users_columns', array( $this, 'post_comment_column' ), 10, 1 );
        }
    }

    /**
     * Disable Posting related functions
     */
    function meta_boxes() { // Disable blogging related meta boxes on the Dashboard
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

    function post_comment_column( $column ) { // Remove posts column
        unset( $column['posts'] );
        unset( $column['comments'] );
        return $column;
    }

    function widgets() { // Remove blog related widgets
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

    function press_this() { // Disables "Press This" and redirect to homepage
        wp_safe_redirect( home_url(), 301 );
    }

    /**
     * Disable Comment related functions
     */
    function page_comments() { // Remove comments column from posts & pages
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