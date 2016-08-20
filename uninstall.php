<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'dsbl_remove_profile_fields' );