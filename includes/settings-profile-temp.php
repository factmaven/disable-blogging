<?php

class ProfileSettings {
    private $profile_settings_options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'profile_settings_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'profile_settings_page_init' ) );
    }

    public function profile_settings_add_plugin_page() {
        add_users_page(
            'Profile Settings', // page_title
            'Settings', // menu_title
            'manage_options', // capability
            'profile-settings', // menu_slug
            array( $this, 'profile_settings_create_admin_page' ) // function
        );
    }

    public function profile_settings_create_admin_page() {
        $this->profile_settings_options = get_option( 'profile_settings_option_name' ); ?>

        <div class="wrap">
            <h2>Profile Settings</h2>
            <p>Simply the user profile by removing fields that aren't needed.</p>
            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                    settings_fields( 'profile_settings_option_group' );
                    do_settings_sections( 'profile-settings-admin' );
                    // submit_button( 'Save Changes', 'primary', 'setopts' );
                    submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function profile_settings_page_init() {
        register_setting(
            'profile_settings_option_group', // option_group
            'profile_settings_option_name', // option_name
            array( $this, 'profile_settings_sanitize' ) // sanitize_callback
        );

        add_settings_section(
            'profile_settings_setting_section', // id
            '', // title
            '', // callback
            'profile-settings-admin' // page
        );

        add_settings_field(
            'personal_options_0', // id
            'Personal Options', // title
            array( $this, 'personal_options_0_callback' ), // callback
            'profile-settings-admin', // page
            'profile_settings_setting_section' // section
        );

        add_settings_field(
            'name_1', // id
            'Name', // title
            array( $this, 'name_1_callback' ), // callback
            'profile-settings-admin', // page
            'profile_settings_setting_section' // section
        );

        add_settings_field(
            'contact_info_2', // id
            'Contact Info', // title
            array( $this, 'contact_info_2_callback' ), // callback
            'profile-settings-admin', // page
            'profile_settings_setting_section' // section
        );

        add_settings_field(
            'about_yourself_3', // id
            'About Yourself', // title
            array( $this, 'about_yourself_3_callback' ), // callback
            'profile-settings-admin', // page
            'profile_settings_setting_section' // section
        );
    }

    public function profile_settings_sanitize($input) {
        $sanitary_values = array();
        if ( isset( $input['personal_options_0'] ) ) {
            $sanitary_values['personal_options_0'] = $input['personal_options_0'];
        }

        if ( isset( $input['name_1'] ) ) {
            $sanitary_values['name_1'] = $input['name_1'];
        }

        if ( isset( $input['contact_info_2'] ) ) {
            $sanitary_values['contact_info_2'] = $input['contact_info_2'];
        }

        if ( isset( $input['about_yourself_3'] ) ) {
            $sanitary_values['about_yourself_3'] = $input['about_yourself_3'];
        }

        return $sanitary_values;
    }

    public function personal_options_0_callback() {
        printf(
            '<input type="checkbox" name="profile_settings_option_name[personal_options_0]" id="personal_options_0" value="personal_options_0" %s> <label for="personal_options_0"> Visual Editor</label>',
            ( isset( $this->profile_settings_options['personal_options_0'] ) && $this->profile_settings_options['personal_options_0'] === 'personal_options_0' ) ? 'checked' : ''
        );
    }

    public function name_1_callback() {
        printf(
            '<input type="checkbox" name="profile_settings_option_name[name_1]" id="name_1" value="name_1" %s> <label for="name_1"> First Name</label>',
            ( isset( $this->profile_settings_options['name_1'] ) && $this->profile_settings_options['name_1'] === 'name_1' ) ? 'checked' : ''
        );
    }

    public function contact_info_2_callback() {
        printf(
            '<input type="checkbox" name="profile_settings_option_name[contact_info_2]" id="contact_info_2" value="contact_info_2" %s> <label for="contact_info_2"> Website</label>',
            ( isset( $this->profile_settings_options['contact_info_2'] ) && $this->profile_settings_options['contact_info_2'] === 'contact_info_2' ) ? 'checked' : ''
        );
    }

    public function about_yourself_3_callback() {
        printf(
            '<input type="checkbox" name="profile_settings_option_name[about_yourself_3]" id="about_yourself_3" value="about_yourself_3" %s> <label for="about_yourself_3"> Biographical Info</label>',
            ( isset( $this->profile_settings_options['about_yourself_3'] ) && $this->profile_settings_options['about_yourself_3'] === 'about_yourself_3' ) ? 'checked' : ''
        );
    }

}

if ( is_admin() ) {
    $profile_settings = new ProfileSettings();
}
/* 
 * Retrieve this value with:
 * $profile_settings_options = get_option( 'profile_settings_option_name' ); // Array of All Options
 * $personal_options_0 = $profile_settings_options['personal_options_0']; // Personal Options
 * $name_1 = $profile_settings_options['name_1']; // Name
 * $contact_info_2 = $profile_settings_options['contact_info_2']; // Contact Info
 * $about_yourself_3 = $profile_settings_options['about_yourself_3']; // About Yourself
 */