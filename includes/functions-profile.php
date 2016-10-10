<?php

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_Profile {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    public function __construct() {
        # Get the plugin options
        $settings = get_option( 'factmaven_dsbl_profile' );

        # Hide the User Profile fields
        add_action( 'admin_head', array( $this, 'user_profile_fields' ), 10, 1 );
        # If the 'Avatar Display' is selected, update the option in the Discussion page
        if ( isset( $settings['about_yourself'] ) ) {
            if ( is_array( $settings['about_yourself'] ) && in_array( 'show_avatars', $settings['about_yourself'] ) ) {
                update_option( 'show_avatars', 0 );
            }
        }
        # If the 'Admin Color Scheme' is selected, remove action
        if ( isset( $settings['personal_options'] ) ) {
            if ( is_array( $settings['personal_options'] ) && in_array( 'admin_color', $settings['personal_options'] ) ) {
                remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function user_profile_fields() {
        # Get the plugin options
        $settings = get_option( 'factmaven_dsbl_profile' );
        # Define the list of page to apply JavaScript
        global $pagenow;
        $page = array(
            'profile.php',
            'user-edit.php',
            'user-new.php',
            );
        # Apply jQuery script in the header
        if ( in_array( $pagenow, $page, TRUE ) ) {
            if ( is_array( $settings ) || is_object( $settings ) ) {
                ?>
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('form#your-profile>h2').hide();
                <?php
                    if ( is_array( $settings ) || is_object( $settings ) ) {
                        # Hide each field that is define in the plugin's options
                        foreach ( $settings as $group => $item ) {
                            if ( is_array( $item ) ) {
                                foreach ( $item as $value ) {
                                    echo( "$('#" . $value . "').closest('tr').hide();" );
                                }
                            }
                        }
                        /*if ( isset( $settings['additional_fields'] ) ) {
                            $additional_fields = explode( "\n", $settings['additional_fields'] );
                            // echo '<pre>'; print_r( $additional_fields ); echo '</pre>';
                            foreach ($additional_fields as $key => $value) {
                                echo( "$('#" . $value . "').closest('tr').hide();" );
                            }
                        }*/
                    }
                ?>
                } );
                </script>
                <?php
            }
        }
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_Profile();