<?php
/*
Plugin Name: Disable Blogging
Plugin URI: https://wordpress.org/plugins/disable-blogging/
Description: Simply disables blogging functionality & removes the links from menu, 'nuff said.
Version: 1.0.0
Author: Fact Maven Corp.
Author URI: http://www.factmaven.com/
License: GPL2 or later
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ADD ACTIONS
add_action( 'admin_menu', 'dsbl_sidebar_menu' );
add_action( 'wp_before_admin_bar_render', 'dsbl_toolbar_menu' );
add_action( 'init', 'dsbl_page_comments' );
add_action( 'load-index.php', 'dsbl_redirect_dashboard' );
add_action( 'wp_loaded', 'dsbl_feeds' );
add_action( 'personal_options','dsbl_user_profile' );

// ADD FILTERS
add_filter( 'script_loader_src', 'dsbl_remove_script_version' );
add_filter( 'style_loader_src', 'dsbl_remove_script_version' );

/* ACTIONS
-------------------------------------------------------------- */

function dsbl_sidebar_menu() { // Remove menu items & redirect to page menu    
    define( 'DISALLOW_FILE_EDIT', true ); // themes/plugins > editor
    remove_menu_page( 'index.php' ); // dashboard
    remove_menu_page( 'edit.php' ); // posts
    remove_menu_page( 'edit-comments.php' ); // comments
    remove_submenu_page( 'options-general.php', 'options-discussion.php' ); // settings > discussion

    global $pagenow;
    if ( in_array( $pagenow, array(
        /* comments */ 'comment.php', 'edit-comments.php',
        /* discussions */ 'options-discussion.php'
        ), true ) ) {
        wp_redirect( admin_url( 'edit.php?post_type=page' ) );
        // wp_die( __( 'This feature is disabled.' ), '', array( 'response' => 403 ) );
    }
}

function dsbl_toolbar_menu() { // Remove menu items from the toolbar
    global $wp_admin_bar;
    $wp_admin_bar -> remove_menu( 'wp-logo' ); // wordpress
    $wp_admin_bar -> remove_menu( 'new-post' ); // posts
    $wp_admin_bar -> remove_menu( 'comments' ); // comments
    $wp_admin_bar -> remove_menu( 'search' ); // search
}

function dsbl_page_comments() { // Remove comments columns from pages
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}

function dsbl_redirect_dashboard() { // Redirect dashboard to page menu
    wp_redirect( admin_url( 'edit.php?post_type=page' ) );
}

function dsbl_feeds() { // Remove feed links
    remove_action( 'wp_head', 'feed_links', 2 ); // general feeds (post and comment)
    remove_action( 'wp_head', 'feed_links_extra', 3 ); // extra feeds (category, etc.)
    remove_action( 'wp_head', 'rsd_link' ); // really simple discovery and edituri
    remove_action( 'wp_head', 'wlwmanifest_link' ); // windows live writer manifest
    remove_action( 'wp_head', 'index_rel_link' ); // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // relational links
    remove_action( 'wp_head', 'wp_generator' ); // wordpress version
}

function dsbl_user_profile() {
    remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    
    echo "\n" . '
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(\'form#your-profile > h3:first\').hide(); $(\'form#your-profile > table:first\').hide();
        $(\'form#your-profile\').show();
    });
    </script>' . "\n";
}

function add_twitter_contactmethod( $contactmethods ) {
  unset($contactmethods['description']);
  return $contactmethods;
}
add_filter('user_contactmethods','add_twitter_contactmethod',10,1);

/* FILTERS
-------------------------------------------------------------- */

function dsbl_remove_script_version( $src ) { // Remove query strings from static resources
    if( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

?>