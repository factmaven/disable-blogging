<?php

add_filter( 'plugin_row_meta', 'dsbl_plugin_links', 10, 2 );

function dsbl_plugin_links( $links, $file ) { // Add meta links to plugin page
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