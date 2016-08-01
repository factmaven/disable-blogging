<?php

add_action( 'admin_menu', 'dsbl_profiler_menu' );
function dsbl_profiler_menu() {
    add_users_page(
            'Profile Settings', // Page Title
            'Settings', // Menu Title
            'manage_options', // Capability
            'dsbl-profile', // Slug
            'dsbl_profiler' // Function
        );
}

function dsbl_profiler() {
    if (isset( $_POST['setopts'] ) ) {
        // rich_editing, comment_shorcut, admin_bar_front, admin_bar_admin, admin_color
        update_option( 'dsbl_personal', isset( $_POST['personal'] ) );
        update_option( 'dsbl_name', isset( $_POST['name'] ) );
        update_option( 'dsbl_contact', isset( $_POST['contact'] ) );
        update_option( 'dsbl_about', isset( $_POST['about'] ) );
        update_option( 'dsbl_toRemove', $_POST['to_remove'] );
        update_option( 'dsbl_toHigh', $_POST['to_hide'] );
    }
    $rem = get_option( 'dsbl_toRemove' );
    $toHide = get_option( 'dsbl_toHigh' );
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
add_action( 'admin_head', 'Simplify_the_user_profile' );
function Simplify_the_user_profile() {
    global $pagenow;
    if ( ( $pagenow == 'profile.php' ) ) {
        UserProfileSetPageDisplay();
    }
}

/* Consider using my version of JS to hide the fields
function dsbl_user_profile() { // Hide unused fields from user profile
    ?>
    <script type="text/javascript">
    jQuery( document ).ready( function( $ ) {
        $( 'form#your-profile > h2' ).hide(); // Section titles
        $( 'form#your-profile > table:first' ).hide(); // Personal Options
        $( '#url' ).closest( 'tr' ).remove(); // Website
        $( '#description' ).closest( 'table' ).remove(); // About Yourself
    });
    </script>
    <?php
*/

function UserProfileSetPageDisplay() { // Hide user profile fields
    $rem = get_option( 'dsbl_toRemove' );
    $hd = get_option( 'dsbl_toHigh' );
    $persn = get_option( 'dsbl_personal' );
    $namer = get_option( 'dsbl_name' );
    $contacts = get_option( 'dsbl_contact' );
    $aboutr = get_option( 'dsbl_about' );
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