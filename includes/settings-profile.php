<?php

add_action( 'admin_menu', 'Simplr_usr_profiler_menu' );
add_action( 'admin_head', 'Simplify_the_user_profile' );
add_action( 'wp_head', 'Simplify_the_user_profile_frontend' );

function Simplr_usr_profiler_menu() {
    // add_options_page ( 'Simplify the User Profile Page', 'User Profile', 'manage_options', 'adj-usr-profilr', 'Simplr_usr_profiler' );
    add_submenu_page( 'users.php', // Parent Menu
        'Profile Settings', // Page Title
        'Settings', // Menu Title
        'manage_options', // Capability
        'profile-settings', // Slug
        'Simplr_usr_profiler' // Callback Function
        );
}

// register_activation_hook( __FILE__, 'userPrfoInitOpts' );

// function userPrfoInitOpts( ) {
// add_option( 'usrprof_personal', 'Personal Options' );
// add_option( 'usrprof_name', 'Name' );
// add_option( 'usrprof_contact', 'Contact Info' );
// add_option( 'usrprof_about', ' About Yourself ' );
// }

function Simplr_usr_profiler() {
    if ( isset( $_POST['setopts'] ) ) {
        //rich_editing, comment_shorcut, admin_bar_front, admin_bar_admin, admin_color
        update_option( 'usrprof_personal', $_POST['personal'] );
        update_option( 'usrprof_name', $_POST['name'] );
        update_option( 'usrprof_contact', $_POST['contact'] );
        update_option( 'usrprof_about', $_POST['about'] );

        update_option( 'usrprof_toRemove', $_POST['to_remove'] );
        update_option( 'usrprof_toHigh', $_POST['to_hide'] );

        update_option( 'simple_profile_default_uncheck_bar', $_POST['def_bar'] );
        // update_option( 'simple_profile_show_front', $_POST['page_id'] );

        // update_option( 'simple_profile_on_edit', $_POST['onAdmin'] );
    }
    
    $rem = get_option( 'usrprof_toRemove' );
    $toHide = get_option( 'usrprof_toHigh' );
    // $disBar = get_option( 'simple_profile_default_uncheck_bar' );

?>

<div class="wrap">
    <h1>Profile Settings</h1>
    <p>Select Profile fields to disable.</p>

    <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Personal Options</th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span>Personal Options</span></legend>
                            <label for="rich_editing">
                            <input name="to_remove[]" type="checkbox" value="rich_editing" <?php if ( is_array( $rem ) && in_array( 'rich_editing', $rem ) ) { echo 'checked="checked" '; } ?> >
                            Visual Editor</label>
                            <br>
                            <label for="admin_color">
                            <input name="to_remove[]" type="checkbox" value="admin_color" <?php if ( is_array( $rem ) && in_array( 'admin_color', $rem ) ) { echo 'checked="checked" '; } ?> >
                            Admin Color Scheme</label>
                            <br>
                            <label for="comment_shortcuts">
                            <input name="to_remove[]" type="checkbox" value="comment_shortcuts" <?php if ( is_array( $rem ) && in_array( 'comment_shortcuts', $rem ) ) { echo 'checked="checked" '; } ?> >
                            Keyboard Shortcuts</label>
                            <br>
                            <label for="admin_bar_front">
                            <input name="to_remove[]" type="checkbox" value="admin_bar_front" <?php if ( is_array( $rem ) && in_array( 'admin_bar_front', $rem ) ) { echo 'checked="checked" '; } ?> >
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
                            <input name="to_remove[]" type="checkbox" value="first_name" <?php if ( is_array( $rem ) && in_array( 'first_name', $rem ) ) { echo 'checked="checked" '; } ?> >
                            First Name</label>
                            <br>
                            <label for="last_name">
                            <input name="to_remove[]" type="checkbox" value="last_name" <?php if ( is_array( $rem ) && in_array( 'last_name', $rem ) ) { echo 'checked="checked" '; } ?> >
                            Last Name</label>
                            <br>
                            <label for="nickname">
                            <input name="to_remove[]" type="checkbox" value="nickname" <?php if ( is_array( $rem ) && in_array( 'nickname', $rem ) ) { echo 'checked="checked" '; } ?> >
                            Nickname</label>
                            <br>
                            <label for="display_name">
                            <input name="to_remove[]" type="checkbox" value="display_name" <?php if ( is_array( $rem ) && in_array( 'display_name', $rem ) ) { echo 'checked="checked" '; } ?> >
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
                            <input name="to_remove[]" type="checkbox" value="url" <?php if ( is_array( $rem ) && in_array( 'url', $rem ) ) { echo 'checked="checked" '; } ?> >
                            Website</label>
                            <br>
                            <?php foreach ( wp_get_user_contact_methods() as $name => $desc ) { ?>
                            <input name ="to_remove[]" type="checkbox"  value="<?php echo $name; ?>" <?php if ( is_array( $rem ) && in_array( $name, $rem ) ) { echo 'checked="checked" '; } ?> ><label for="<?php echo $name; ?>"><?php echo apply_filters( 'user_' . $name . '_label', $desc ); ?></label><br>
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
                            <input name="to_remove[]" type="checkbox" value="description" <?php if ( is_array( $rem ) && in_array( 'description', $rem ) ) { echo 'checked="checked" '; } ?> >
                            Biographical Info</label>
                            <br>
                            <?php $show_avatars = get_option( 'show_avatars' ); ?>
                            <label for="show_avatars">
                            <input type="checkbox" disabled="disabled" name="show_avatars" value="1" <?php checked( $show_avatars, 1 ); ?> />
                            Avatar Display</label>
                            <br>
                            <p class="description">(Avatar display &amp; settings can be managed in <a href="./options-discussion.php">Discussion</a> page.)</p>
                            </label>
                        </fieldset>
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
        UserProfileSetPageDisplay( );
    }
    
    $ifOadmin = get_option( 'simple_profile_on_edit' );
    if ( ( $pagenow == 'user-edit.php' && $ifOadmin == 'yes' ) ) {
        UserProfileSetPageDisplay( );
    }
}

function Simplify_the_user_profile_frontend() {
    global $post;
    $t = get_option( 'simple_profile_show_front' );
    if ( $t == $post->ID ) {
        UserProfileSetPageDisplay( );
    }
}

// add_action( 'user_register', 'simple_profile_default_uncheck_bar' );
// function simple_profile_default_uncheck_bar( $user_ID ) {
// if ( get_option( 'simple_profile_default_uncheck_bar' )=='yes' ) {
//         update_user_meta( $user_ID, 'show_admin_bar_front', 'false' );
//     }
// }

function UserProfileSetPageDisplay() {
    $rem = get_option( 'usrprof_toRemove' );
    $hd  = get_option( 'usrprof_toHigh' );
    
    $persn    = get_option( 'usrprof_personal' );
    $namer    = get_option( 'usrprof_name' );
    $contacts = get_option( 'usrprof_contact' );
    $aboutr   = get_option( 'usrprof_about' );
    
?>
        <script type="text/javascript">
        jQuery( document ).ready( function( jQuery ) { 
        
        <?php
    if ( is_array( $rem ) ) {
        foreach ( $rem as $t ) {
            echo "jQuery( 'label[for=\"" . $t . "\"]' ).closest( 'tr' ).remove( );";
        }
    }
    
    if ( is_array( $hd ) ) {
        foreach ( $hd as $n ) {
            echo "jQuery( 'label[for=\"" . $n . "\"]' ).closest( 'tr' ).hide( );";
        }
    }
?>
        var replaced = jQuery( "body" ).html( ).replace( 'About Yourself', '<?php
    echo $aboutr;
?>' ).replace( 'Contact Info', '<?php
    echo $contacts;
?>' ).replace( '<h3>Name</h3>', '<h3><?php
    echo $namer;
?></h3>' ).replace( 'Personal Options', '<?php
    echo $persn;
?>' ) ;
        jQuery( "body" ).html( replaced );
         } );</script>

<?php
    
    if ( is_array( $rem ) && in_array( 'admin_color', $rem ) ) {
        global $_wp_admin_css_colors;
        $_wp_admin_css_colors = 0;
    }
}