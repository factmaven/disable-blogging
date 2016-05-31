<?php
/*
    Plugin Name: Disable Blogging
    Plugin URI: https://wordpress.org/plugins/disable-blogging/
    Description: Disables posts, comments, and other related the blogging features in WordPress, 'nuff said.
    Version: 1.1.0
    Author: Fact Maven Corp.
    Author URI: https://www.factmaven.com/
    GitHub Plugin URI: https://github.com/factmaven/disable-blogging/
    License: GPLv2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// HOOKS
do_action( 'dsbl_hook' );

// GLOBAL DEFINES
define( 'DISALLOW_FILE_EDIT', true ); // Disable themes & plugins editor
define( 'WP_POST_REVISIONS', false ); // Disable post revisions

// ADD ACTIONS
add_action( 'admin_menu', 'dsbl_sidebar_menu', 10, 1 );
add_action( 'wp_before_admin_bar_render', 'dsbl_toolbar_menu', 10, 1 );
add_action( 'init', 'dsbl_page_comments', 10, 1 );
add_action( 'personal_options', 'dsbl_user_profile', 10, 1 );
add_action( 'pre_ping', 'dsbl_pingback', 10, 1 );
add_action( 'wp_loaded', 'dsbl_feeds', 1, 1 );
add_action( 'widgets_init', 'dsbl_widgets', 11, 1 );
add_action( 'admin_head', 'dsbl_help_tabs', 999, 1 );
add_action( 'load-press-this.php', 'dsbl_press_this', 10, 1 );

// ADD FILTERS
add_filter( 'xmlrpc_enabled', '__return_false', 10, 1 ); // Doesn't need a function
add_filter( 'plugin_row_meta', 'dsbl_plugin_links', 10, 2 );
add_filter( 'admin_bar_menu', 'dsbl_howdy', 25, 1 );
add_filter( 'script_loader_src', 'dsbl_script_version', 10, 1 );
add_filter( 'style_loader_src', 'dsbl_script_version', 10, 1 );
add_filter( 'editable_roles', 'dsbl_exclude_role', 10, 1 );

/* ACTIONS
-------------------------------------------------------------- */

function dsbl_sidebar_menu() { // Remove menu items & redirect to page menu
    remove_menu_page( 'index.php' ); // dashboard
    remove_menu_page( 'edit.php' ); // posts
    remove_menu_page( 'edit-comments.php' ); // comments
    remove_submenu_page( 'tools.php', 'tools.php' ); // tools > available tools
    remove_submenu_page( 'options-general.php', 'options-writing.php' ); // settings > writing
    remove_submenu_page( 'options-general.php', 'options-discussion.php' ); // settings > discussion

    global $pagenow;
    if ( in_array( $pagenow, array( 'index.php', 'edit.php', 'post-new.php', 'edit-tags.php', 'edit-comments.php' ), true ) && ( ! isset( $_GET['post_type'] ) || isset( $_GET['post_type'] ) && $_GET['post_type'] == 'post' ) ) { // dashboard, posts, comments
        wp_redirect( admin_url( 'edit.php?post_type=page' ), 301 );
        exit;
    }
    if ( in_array( $pagenow, array( 'tools.php' ), true ) ) { // tools
        wp_redirect( admin_url( 'import.php' ), 301 );
        exit;
    }
    if ( in_array( $pagenow, array( 'options-writing.php', 'options-discussion.php' ), true ) ) { // settings
        wp_redirect( admin_url( 'options-general.php' ), 301 );
        exit;
    }
}

function dsbl_toolbar_menu() { // Remove menu items from the toolbar
    global $wp_admin_bar;
    $wp_admin_bar -> remove_menu( 'wp-logo' ); // wordpress
    $wp_admin_bar -> remove_menu( 'new-post' ); // posts
    $wp_admin_bar -> remove_menu( 'comments' ); // comments
    $wp_admin_bar -> remove_menu( 'search' ); // search
}

function dsbl_page_comments() { // Remove comments from pages
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}

function dsbl_user_profile() { // Hide certain fields from user profile
    echo "\n" . '
    <script type="text/javascript">
    jQuery( document ).ready( function($) {
        $(\'form#your-profile > h2\').hide();
        $(\'form#your-profile > h3\').hide();
        $(\'form#your-profile > table:first\').hide();
        $(\'form#your-profile\').show();
        $(\'#nickname, #display_name, #url, #aim, #yim, #jabber, #facebook, #twitter, #googleplus, #description, #wpseo_author_title, #wpseo_author_metadesc\').parent().parent().hide();
    });
    </script>' . "\n";
}

function dsbl_pingback( &$links ) { // Disable pings and trackbacks
    $home = get_option( 'home' );
    foreach ( $links as $l => $link ) {
        if ( 0 === strpos( $link, $home ) ) {
            unset($links[$l]);
        }
    }
}

function dsbl_feeds() { // Remove feed links & redirect to homepage
    remove_action( 'wp_head', 'feed_links', 2 ); // general feeds (post and comment)
    remove_action( 'wp_head', 'feed_links_extra', 3 ); // extra feeds (category, etc.)
    remove_action( 'wp_head', 'rsd_link' ); // really simple discovery and edituri
    remove_action( 'wp_head', 'wlwmanifest_link' ); // windows live writer manifest
    remove_action( 'wp_head', 'index_rel_link' ); // index link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
    remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // relational links
    remove_action( 'wp_head', 'wp_generator' ); // wordpress version

    global $wp_rewrite;
    if( get_query_var( 'feed' ) !== 'old' ) {
        set_query_var( 'feed', '' );
    }
    redirect_canonical();   // Automatically determine appropriate redirect URL

    // If 'redirect_canonical' failed, try another way
    $url_struct = ( !is_singular() && is_comment_feed() ) ? $wp_rewrite -> get_comment_feed_permastruct() : $wp_rewrite -> get_feed_permastruct();
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
        'WP_Widget_Archives',
        'WP_Widget_Calendar',
        'WP_Widget_Categories',
        'WP_Widget_Links',
        'WP_Widget_Meta',
        'WP_Widget_Pages',
        'WP_Widget_Recent_Comments',
        'WP_Widget_Recent_Posts',
        'WP_Widget_RSS',
        'WP_Widget_Tag_Cloud'
    );
    foreach( $widgets as $widget ) {
        unregister_widget( $widget );
    }
}

function dsbl_help_tabs() { // Remove help tabs
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}

function dsbl_press_this() { // Disables "Press This"
    wp_die('"Press This" functionality has been disabled.');
}

/* FILTERS
-------------------------------------------------------------- */

function dsbl_plugin_links( $links, $file ) {
    if ( strpos( $file, 'disable-blogging.php' ) !== false ) { // Adds support and GitHub link to plugin page
        $meta = array(
        'support' => '<a href="https://wordpress.org/support/plugin/disable-blogging" target="_blank">Support</a>',
        'github' => '<a href="https://github.com/factmaven/disable-blogging" target="_blank">GitHub</a>'
        );
        $links = array_merge( $links, $meta );
    }
    return $links;
}

function dsbl_howdy( $wp_admin_bar ) { // Removed "Howdy," from the admin bar, we ain't from Texas!
    $account = $wp_admin_bar -> get_node( 'my-account' );
    $no_howdy = str_replace( 'Howdy,', '', $account -> title );
    $wp_admin_bar -> add_node( array(
        'id' => 'my-account',
        'title' => $no_howdy,
    ) );
}

function dsbl_script_version( $src ) { // Remove query strings from static resources
    if( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

function dsbl_exclude_role( $roles ) { // Hide default user roles (except admin)
    if ( isset( $roles['editor'] ) ) {
        unset( $roles['editor'] );
    }
    if ( isset( $roles['author'] ) ) {
        unset( $roles['author'] );
    }
    if ( isset( $roles['contributor'] ) ) {
        unset( $roles['contributor'] );
    }
    if ( isset( $roles['subscriber'] ) ) {
        unset( $roles['subscriber'] );
    }
    return $roles;
}