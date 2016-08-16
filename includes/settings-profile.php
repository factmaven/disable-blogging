<?php

if ( !class_exists( 'FMC_DisableBloggingProfile' ) ) {
    
    class FMC_DisableBloggingProfile {

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'dsbl_settings_profile_add' ), 10, 1 );
            add_action( 'admin_head', array( $this, 'dsbl_user_profile' ), 10, 1 );
        }

        public function dsbl_settings_profile_add() { // Set up plugin settings page
            add_submenu_page(
                'users.php', // Parent Menu
                'Profile Settings', // Page Title
                'Settings', // Menu Title
                'manage_options', // Capability
                'profile-settings', // Slug
                array( $this, 'dsbl_settings_profile_init' ) // Callback Function
                );
        }

        public function dsbl_settings_profile_init() { // Save checkbox values in an array and display a success message
            $profile_fields = get_option( 'dsbl_remove' );

            if ( isset( $_POST['dsbl_options'] ) && !empty( $_POST['dsbl_options'] ) ) {
                if ( array_key_exists('remove_field', $_POST )) {
                    update_option( 'dsbl_remove', $_POST['remove_field'] );
                }
                else { // When all options are unchecked, set array to null
                    update_option( 'dsbl_remove', NULL );
                }
                ?>
                <div class="notice notice-success is-dismissible"> 
                    <p><strong>Settings saved.</strong></p>
                </div>
                <?php
            }

            $profile_fields = get_option( 'dsbl_remove' );

            ?>
            <div class="wrap">
                <h1>Profile Settings</h1>
                <p>Simply the <a href="./profile.php">User Profile</a> by selecting the fields that aren't needed.</p>

                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Personal Options</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>Personal Options</span></legend>
                                        <label for="rich_editing">
                                        <input name="remove_field[]" type="checkbox" value="rich_editing" <?php if ( is_array( $profile_fields ) && in_array( 'rich_editing', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Visual Editor</label>
                                        <br>
                                        <label for="admin_color">
                                        <input name="remove_field[]" type="checkbox" value="admin_color" <?php if ( is_array( $profile_fields ) && in_array( 'admin_color', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Admin Color Scheme</label>
                                        <br>
                                        <label for="comment_shortcuts">
                                        <input name="remove_field[]" type="checkbox" value="comment_shortcuts" <?php if ( is_array( $profile_fields ) && in_array( 'comment_shortcuts', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Keyboard Shortcuts</label>
                                        <br>
                                        <label for="admin_bar_front">
                                        <input name="remove_field[]" type="checkbox" value="admin_bar_front" <?php if ( is_array( $profile_fields ) && in_array( 'admin_bar_front', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Toolbar</label>
                                        <br>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Name</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>Name</span></legend>
                                        <label for="first_name">
                                        <input name="remove_field[]" type="checkbox" value="first_name" <?php if ( is_array( $profile_fields ) && in_array( 'first_name', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        First Name</label>
                                        <br>
                                        <label for="last_name">
                                        <input name="remove_field[]" type="checkbox" value="last_name" <?php if ( is_array( $profile_fields ) && in_array( 'last_name', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Last Name</label>
                                        <br>
                                        <label for="nickname">
                                        <input name="remove_field[]" type="checkbox" value="nickname" <?php if ( is_array( $profile_fields ) && in_array( 'nickname', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Nickname <span class="description">(required)</span></label>
                                        <br>
                                        <label for="display_name">
                                        <input name="remove_field[]" type="checkbox" value="display_name" <?php if ( is_array( $profile_fields ) && in_array( 'display_name', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Display Name</label>
                                        <br>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Contact Info</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>Contact Info</span></legend>
                                        <label for="url">
                                        <input name="remove_field[]" type="checkbox" value="url" <?php if ( is_array( $profile_fields ) && in_array( 'url', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Website</label>
                                        <br>
                                        <?php foreach ( wp_get_user_contact_methods() as $value => $label ) { ?>
                                        <input name ="remove_field[]" type="checkbox"  value="<?php echo $value; ?>" <?php if ( is_array( $profile_fields ) && in_array( $value, $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        <label for="<?php echo $value; ?>">
                                        <?php echo apply_filters( 'user_' . $value . '_label', $label ); ?></label>
                                        <br>
                                        <?php } ?>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">About Yourself</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>About Yourself</span></legend>
                                        <label for="description">
                                        <input name="remove_field[]" type="checkbox" value="description" <?php if ( is_array( $profile_fields ) && in_array( 'description', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                        Biographical Info</label>
                                        <br>
                                        <?php $show_avatars = get_option( 'show_avatars' ); ?>
                                        <label for="show_avatars">
                                        <input type="checkbox" disabled="disabled" name="show_avatars" value="1" <?php checked( $show_avatars, 1 ); ?> />
                                        Avatar Display</label>
                                        <br>
                                        <p class="description">(Avatar settings can be managed in <a href="./options-discussion.php#show_avatars">Discussion</a> page.)</p>
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                            <?php if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) { // Yoast SEO plugin ?>
                                <tr>
                                    <th scope="row">Yoast SEO settings</th>
                                    <td>
                                        <fieldset>
                                            <legend class="screen-reader-text"><span>Yoast SEO settings</span></legend>
                                            <label for="wpseo_author_title">
                                            <input name="remove_field[]" type="checkbox" value="wpseo_author_title" <?php if ( is_array( $profile_fields ) && in_array( 'wpseo_author_title', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                            Title to use for Author page</label>
                                            <br>
                                            <label for="wpseo_author_metadesc">
                                            <input name="remove_field[]" type="checkbox" value="wpseo_author_metadesc" <?php if ( is_array( $profile_fields ) && in_array( 'wpseo_author_metadesc', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                            Meta description to use for Author page</label>
                                            <br>
                                            <label for="wpseo_author_exclude">
                                            <input name="remove_field[]" type="checkbox" value="wpseo_author_exclude" <?php if ( is_array( $profile_fields ) && in_array( 'wpseo_author_exclude', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                            Exclude user from Author-sitemap</label>
                                            <br>
                                            <label for="wpseo_keyword_analysis_disable">
                                            <input name="remove_field[]" type="checkbox" value="wpseo_keyword_analysis_disable" <?php if ( is_array( $profile_fields ) && in_array( 'wpseo_keyword_analysis_disable', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                            Disable SEO analysis</label>
                                            <br>
                                            <label for="wpseo_content_analysis_disable">
                                            <input name="remove_field[]" type="checkbox" value="wpseo_content_analysis_disable" <?php if ( is_array( $profile_fields ) && in_array( 'wpseo_content_analysis_disable', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                            Disable readability analysis</label>
                                            <br>
                                        </fieldset>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ( is_plugin_active( 'ultimate-member/index.php' ) ) { // Ultimate Member plugin ?>
                                <tr>
                                    <th scope="row">Ultimate Member</th>
                                    <td>
                                        <fieldset>
                                            <legend class="screen-reader-text"><span>Ultimate Member</span></legend>
                                            <label for="um_set_api_key">
                                            <input name="remove_field[]" type="checkbox" value="um_set_api_key" <?php if ( is_array( $profile_fields ) && in_array( 'um_set_api_key', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                            Ultimate Member REST API</label>
                                            <br>
                                            <label for="um_role">
                                            <input name="remove_field[]" type="checkbox" value="um_role" <?php if ( is_array( $profile_fields ) && in_array( 'um_role', $profile_fields ) ) { echo 'checked="checked" '; } ?> />
                                            Community Role</label>
                                            <br>
                                        </fieldset>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php submit_button( 'Save Changes', 'primary', 'dsbl_options' ); ?>
                </form>
            </div>
            <?php
        }

        public function dsbl_user_profile() { // Hide unused fields from user profile
            global $pagenow;
            $page = array(
                'profile.php',
                'user-edit.php',
                'user-new.php'
                );
            if ( in_array( $pagenow, $page, true ) ) {
                $profile_fields = get_option( 'dsbl_remove' );
                if ( is_array( $profile_fields ) || is_object( $profile_fields ) ) {
                    ?>
                    <script type="text/javascript">
                        jQuery( document ).ready( function( $ ) {
                        $( 'form#your-profile > h2' ).hide();
                        <?php
                            foreach ( $profile_fields as $label ) {
                                echo( "$( '#" . $label . "' ).closest( 'tr' ).hide(); " );
                            }
                        ?>
                        } );
                    </script>
                    <?php
                }
                
                if ( is_array( $profile_fields ) && in_array( 'admin_color', $profile_fields ) ) { // Hide the Admin Color Scheme 
                    global $_wp_admin_css_colors;
                    $_wp_admin_css_colors = 0;
                }
            }
        }

    }
}

if ( class_exists( 'FMC_DisableBloggingProfile' ) ) { // Instantiate the plugin class
    global $dsbl_profile;
    $dsbl_profile = new FMC_DisableBloggingProfile();
}