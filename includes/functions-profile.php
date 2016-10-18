<?php

# If accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) exit;

class Fact_Maven_Disable_Blogging_Profile {

    //==============================
    // CALL THE FUNCTIONS
    //==============================
    private $settings;

    public function __construct() {
        # Get the plugin options
        $this->settings = get_option( 'factmaven_dsbl_profile' );

        # Hide the User Profile fields
        add_action( 'admin_head', array( $this, 'user_profile_fields' ), 10, 1 );
        # If the 'Avatar Display' is selected, uncheck the option in the Discussion page
        if ( isset( $this->settings['about_yourself'] ) ) {
            if ( is_array( $this->settings['about_yourself'] ) && in_array( 'show_avatars', $this->settings['about_yourself'] ) ) {
                update_option( 'show_avatars', 0 );
            }
            # Else, enable the avatar option in the Discussion page
            else {
                update_option( 'show_avatars', 1 );
            }
        }
        # If the 'Admin Color Scheme' is selected, remove action
        if ( isset( $this->settings['personal_options'] ) ) {
            if ( is_array( $this->settings['personal_options'] ) && in_array( 'admin_color', $this->settings['personal_options'] ) ) {
                remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
            }
        }
    }

    //==============================
    // BEGIN THE FUNCTIONS
    //==============================
    public function user_profile_fields() {
        # Define the list of page to apply JavaScript
        global $pagenow;
        $page = array(
            'profile.php',
            'user-edit.php',
            'user-new.php',
            );
        # Apply jQuery script in the header
        if ( in_array( $pagenow, $page, TRUE ) ) {
            if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
                ?>
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('form#your-profile>h2').hide();
                <?php
                if ( is_array( $this->settings ) || is_object( $this->settings ) ) {
                    # Hide each field that is define in the plugin's options
                    foreach ( $this->settings as $group => $item ) {
                        if ( is_array( $item ) ) {
                            foreach ( $item as $value ) {
                                echo( "$('#" . $value . "').closest('tr').hide();" );
                            }
                        }
                    }
                    # Convert each new line in the textarea as an array item
                    $new_field = explode( "\n", str_replace( "\r", "", $this->settings['additional_fields'] ) );
                    foreach ( $new_field as $key => $value ) {
                        if ( $value != NULL ) {
                            echo( "$('#" . $value . "').closest('tr').hide();" );
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

# Instantiate the class
new Fact_Maven_Disable_Blogging_Profile();