<?php

add_action( 'admin_menu', 'dsbl_profile_add_settings' );
add_action( 'admin_head', 'Simplify_the_user_profile' );

function dsbl_profile_add_settings() {
    add_submenu_page(
            'users.php', // Parent Menu
            'Profile Settings', // Page Title
            'Settings', // Menu Title
            'manage_options', // Capability
            'dsbl-profile-settings', // Slug
            'dsbl_profiler' // Callback Function
        );
}

function dsbl_profiler() {
    if (isset( $_POST['setopts'] ) ) {
        update_option( 'dsbl_remove_fields', $_POST['to_remove'] );
        update_option( 'dsbl_hide_fields', $_POST['to_hide'] );

        // Tried adding isset() to the $_POST, but that doesn't save the options
        // update_option( 'dsbl_remove_fields', isset( $_POST['to_remove'] ) );
        // update_option( 'dsbl_hide_fields', isset( $_POST['to_hide'] ) );
    }
    $rem = get_option( 'dsbl_remove_fields' );
    $toHide = get_option( 'dsbl_hide_fields' );
?>

<div class="wrap">
    <h2>Profile Settings</h2>
    <p>Select Profile fields to disable. Leave title fields blank to not show them.</p>
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" >
        <table class="form-table">
            <tbody>
                <tr><th scope="row">Personal Options</th><br>
                    <td>
                        <input type="checkbox" name="to_remove[]" value="rich_editing" <?php if ( is_array( $rem ) && in_array( 'rich_editing', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="rich_editing">Visual Editor</label><br>
                        <input type="checkbox" name="to_remove[]" value="admin_color" <?php if ( is_array( $rem ) && in_array( 'admin_color', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="admin_color">Admin Color Scheme</label><br>
                        <input type="checkbox" name="to_remove[]" value="comment_shortcuts" <?php if ( is_array( $rem ) && in_array( 'comment_shortcuts', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="comment_shortcuts">Keyboard Shortcuts</label><br>
                        <input type="checkbox" name="to_remove[]" value="admin_bar_front" <?php if ( is_array( $rem ) && in_array( 'admin_bar_front', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="admin_bar_front">Toolbar</label><br>
                    </td>
                </tr>
                <tr><th scope="row">Name</th><br>
                    <td>
                        <input type="checkbox" name="to_remove[]" value="first_name" <?php if ( is_array( $rem ) && in_array( 'first_name', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="first_name">First Name</label><br>
                        <input type="checkbox" name="to_remove[]" value="last_name" <?php if ( is_array( $rem ) && in_array( 'last_name', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="last_name">Last Name</label><br>
                        <input type="checkbox" name="to_remove[]" value="nickname" <?php if ( is_array( $rem ) && in_array( 'nickname', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="nickname">Nickname</label><br>
                        <input type="checkbox" name="to_remove[]" value="display_name" <?php if ( is_array( $rem ) && in_array( 'display_name', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="display_name">Display Name</label><br>
                    </td>
                </tr>
                <tr><th scope="row">Contact Info</th><br>
                    <td>
                        <input type="checkbox" name="to_remove[]" value="url" <?php if ( is_array( $rem ) && in_array( 'url', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="url">Website</label><br>
                        <?php foreach ( wp_get_user_contact_methods() as $name => $desc ) {
                        ?>
                            <input type="checkbox"  name ="to_remove[]" value="<?php echo $name; ?>" <?php if ( is_array( $rem ) && in_array( $name, $rem ) ) { echo 'checked="checked" '; } ?> ><label for="<?php echo $name; ?>"><?php echo apply_filters( 'user_' . $name . '_label', $desc); ?></label><br>
                        <?php } ?>
                    </td>
                </tr>
                <tr><th scope="row">About Yourself</th><br>
                    <td>
                        <input type="checkbox" name="to_remove[]" value="description" <?php if ( is_array( $rem ) && in_array( 'description', $rem ) ) { echo 'checked="checked" '; } ?> ><label for="description">Biographical Info</label><br>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button( 'Save Changes', 'primary', 'setopts' ); ?>
    </form>
</div>

<?php
}

function Simplify_the_user_profile() {
    global $pagenow;
    if ( ( $pagenow == 'profile.php' ) ) {
        UserProfileSetPageDisplay();
    }
}

function UserProfileSetPageDisplay() { // Hide user profile fields
    $rem = get_option( 'dsbl_remove_fields' );
    $hd = get_option( 'dsbl_hide_fields' );

    ?>
    <script type="text/javascript">
    jQuery(document).ready(function(jQuery) { 
    
    <?php
    if (is_array( $rem ) ) {
        foreach ( $rem as $t) {
            echo "jQuery( 'label[for=\"" . $t . "\"]' ).closest( 'tr' ).remove();";
        }
    }
    if (is_array( $hd) ) {
        foreach ( $hd as $n) {
            echo "jQuery( 'label[for=\"" . $n . "\"]' ).closest( 'tr' ).hide();";
        }
    }
    ?>
    
    var replaced = jQuery("body").html().replace( 'About Yourself','<?php
echo $aboutr;
?>' ).replace( 'Contact Info','<?php
echo $contacts;
?>' ).replace( '<h3>Name</h3>','<h3><?php
echo $namer;
?></h3>' ).replace( 'Personal Options','<?php
echo $persn;
?>' ) ;

    jQuery("body").html(replaced);
    
     });
    </script>

    <?php

    if (is_array( $rem ) && in_array( 'admin_color', $rem ) ) {
        global $_wp_admin_css_colors;
        $_wp_admin_css_colors = 0;
    }
}