<?php

if ( ! defined( 'ABSPATH' ) ) { // Exit if accessed directly
    exit;
}

if ( ! class_exists( 'Fact_Maven_Disable_Blogging_Profile' ) ):
class Fact_Maven_Disable_Blogging_Profile {

    function __construct() {
        add_action( 'admin_head', array( $this, 'user_profile_fields' ), 10, 1 );
    }

    function user_profile_fields() { // Hide unused fields from user profile
        global $pagenow;
        $page = array(
            'profile.php',
            'user-edit.php',
            'user-new.php'
            );
        $profile_fields = get_option( 'factmaven_dsbl_profile_settings' );

        if ( in_array( $pagenow, $page, true ) ) {
            if ( is_array( $profile_fields ) || is_object( $profile_fields ) ) {
                ?>
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('form#your-profile > h2').hide();
                <?php
                    if ( is_array( $profile_fields ) || is_object( $profile_fields ) ) {
                        foreach ( $profile_fields as $group => $item ) {
                            if( is_array($item) ) {
                                foreach ( $item as $value ) {
                                    echo( "$('#" . $value . "').closest('tr').hide();" );
                                    if ( in_array( 'admin_color', $item ) ) {
                                        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
                                    }
                                }
                            }
                        }
                    }
                ?>
                } );
                </script>
                <?php
            }
        }
    }
}
endif;

new Fact_Maven_Disable_Blogging_Profile();