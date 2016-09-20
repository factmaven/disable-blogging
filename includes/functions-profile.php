<?php

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Fact_Maven_Disable_Blogging_Profile' ) ):
class Fact_Maven_Disable_Blogging_Profile {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    public function __construct() {
        # Get the plugin options
        $profile_fields = get_option( 'factmaven_dsbl_profile_settings' );

        # Hide the User Profile fields
        add_action( 'admin_head', array( $this, 'user_profile_fields' ), 10, 1 );
        # If the 'Avatar Display' is selected, update the option in the Discussion page
        if ( isset( $profile_fields['about_yourself'] ) ) {
            if ( is_array( $profile_fields['about_yourself'] ) && in_array( 'show_avatars', $profile_fields['about_yourself'] ) ) {
                update_option( 'show_avatars', 0 );
            }
        }
        # If the 'Admin Color Scheme' is selected, remove action
        if ( isset( $profile_fields['personal_options'] ) ) {
            if ( is_array( $profile_fields['personal_options'] ) && in_array( 'admin_color', $profile_fields['personal_options'] ) ) {
                remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function user_profile_fields() {
        # Get the plugin options
        $profile_fields = get_option( 'factmaven_dsbl_profile_settings' );
        # Define the list of page to apply JavaScript
        global $pagenow;
        $page = array(
            'profile.php',
            'user-edit.php',
            'user-new.php',
            );
        # Apply jQuery script in the header
        if ( in_array( $pagenow, $page, true ) ) {
            if ( is_array( $profile_fields ) || is_object( $profile_fields ) ) {
                ?>
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('form#your-profile>h2').hide();
                <?php
                    if ( is_array( $profile_fields ) || is_object( $profile_fields ) ) {
                        # Hide each field that is define in the plugin's options
                        foreach ( $profile_fields as $group => $item ) {
                            if( is_array( $item ) ) {
                                foreach ( $item as $value ) {
                                    echo( "$('#" . $value . "').closest('tr').hide();" );
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

# Instantiate the class
new Fact_Maven_Disable_Blogging_Profile();