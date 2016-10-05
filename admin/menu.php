<?php

add_action( 'init', 'uas_init' );

//future: implement show only user's available menus, eg. less than admins as per suggestion

function uas_init() {
    wp_enqueue_script( 'jquery' );
    add_action( 'admin_menu', 'uas_add_admin_menu', 99 );
    add_action( 'admin_head', 'uas_edit_admin_menus', 100 );
    add_action( 'admin_head', 'uas_admin_js' );
    add_action( 'admin_head', 'uas_admin_css' );
}

function uas_add_admin_menu() {
    add_management_page( esc_html__( 'Menu', 'menu' ),
    esc_html__( 'Menu', 'menu' ),
    'manage_options',
    'menu',
    'useradminsimplifier_options_page' );
}

function uas_edit_admin_menus() {
    global $menu;
    global $current_user;
    global $storedmenu;
    global $storedsubmenu;
    global $submenu;
    $storedmenu = $menu; //store the original menu
    $storedsubmenu = $submenu; //store the original menu
    $uas_options = uas_get_admin_options();
    $newmenu = array();
    if ( ! isset( $menu ) )
        return false;
    //rebuild menu based on saved options
    foreach ( $menu as $menuitem ) {
        if ( isset( $menuitem[5] ) && isset( $uas_options[ $current_user->user_nicename ][ sanitize_key( $menuitem[5] )  ] ) &&
                1 == $uas_options[ $current_user->user_nicename ][ sanitize_key( $menuitem[5] )  ] ) {
            remove_menu_page( $menuitem[2] );
        } else {
            // lets check the submenus
            if ( isset ( $storedsubmenu[ $menuitem[2] ] ) ) {
                foreach ( $storedsubmenu[ $menuitem[2] ] as $subsub ) {
                    $combinedname = sanitize_key( $menuitem[5] . $subsub[2] );
                    if  ( isset ( $subsub[2] ) && isset( $uas_options[ $current_user->user_nicename ][ $combinedname ] ) &&
                        1 == $uas_options[ $current_user->user_nicename ][ $combinedname ] ) {
                        remove_submenu_page( $menuitem[2], $subsub[2] );
                    }
                }
            }
        }
    }
}

function uas_get_admin_options() {
    $saved_options = get_option( 'useradminsimplifier_options' );
    return is_array( $saved_options ) ? $saved_options : array();
}

function uas_save_admin_options( $uas_options ) {
    update_option( 'useradminsimplifier_options', $uas_options );
}

function uas_clean_menu_name( $menuname ) { //clean up menu names provided by WordPress
    $menuname = preg_replace( '/<span(.*?)span>/', '', $menuname ); //strip the count appended to menus like the post count
    return ( $menuname );
}

function useradminsimplifier_options_page() {
    $uas_options = uas_get_admin_options();
    $uas_selecteduser = isset( $_POST['uas_user_select'] ) ? $_POST['uas_user_select']: '';
    global $menu;
    global $submenu;
    global $current_user;
    global $storedmenu;
    if ( !isset( $storedmenu ) ) {
        $storedmenu = $menu;
    }
    global $storedsubmenu;
    if ( !isset( $storedsubmenu ) ) {
        $storedsubmenu = $submenu;
    }

    $nowselected = array (); //store selections to apply later in display loop where every menu option is iterated
    $menusectionsubmitted = false;
    if ( isset( $uas_options['selecteduser'] ) && $uas_options['selecteduser'] != $uas_selecteduser ) {
        //user was changed
        $uas_options['selecteduser'] = $uas_selecteduser;
    } else {
        $uas_options['selecteduser'] = $uas_selecteduser;
        // process submitted menu selections
        if ( isset ( $_POST['uas_reset'] ) ) {
            //reset options for this user by clearing all their options
            unset ( $uas_options[ $uas_selecteduser ] );
        } else {
            if ( isset ( $_POST['menuselection'] ) && is_array( $_POST['menuselection'] ) ) {
                $menusectionsubmitted = true;
                foreach ( $_POST['menuselection'] as $key => $value ) {
                    $nowselected[ $uas_selecteduser ][ $value ] = 1; //disable this menu for this user
                }
            }
        }
    }

?>
<div class="wrap">
<h2>
    <?php esc_html_e( 'User Admin Simplifier', 'user_admin_simplifier' ); ?>
</h2>
<form action="" method="post" id="uas_options_form" class="uas_options_form">
<div class="uas_container" id="choosemenus">
    <h3>
        <?php esc_html_e( 'Select menus/submenus to disable for this user', 'user_admin_simplifier' ); ?>:
    </h3>
    <input class="uas_dummy" style="display:none;" type="checkbox" checked="checked" value="uas_dummy" id="menuselection[]" name="menuselection[]">
<?php
            //lets start with top level menus stored in global $menu
            //will add submenu support if needed later
            $rowcount = 0;
            foreach( $storedmenu as $menuitem ) {
                $menuuseroption = 0;
                if ( !( 'wp-menu-separator' == $menuitem[4] ) ) {
                    //reset                         $uas_options[ $uas_selecteduser ][ $menuitem[5] ] = 0;
                    if ( $menusectionsubmitted ) {
                        if ( isset( $nowselected[ $uas_selecteduser ][ sanitize_key( $menuitem[5] )  ] ) ) { //any selected options for this user/menu
                            $menuuseroption = $uas_options[ $uas_selecteduser ][ sanitize_key( $menuitem[5] )  ] = $nowselected[ $uas_selecteduser ][ sanitize_key( $menuitem[5] )  ] ;
                        }
                        else {
                            $menuuseroption = $uas_options[ $uas_selecteduser ][ sanitize_key( $menuitem[5] )  ] = 0;
                        }
                    }
                    if ( isset( $uas_options[ $uas_selecteduser ][ sanitize_key( $menuitem[5] )  ] ) ) { //any saved options for this user/menu
                        $menuuseroption = $uas_options[ $uas_selecteduser ][ sanitize_key( $menuitem[5] )  ];
                    } else {
                        $menuuseroption = 0;
                        $uas_options[ $uas_selecteduser ][ sanitize_key( $menuitem[5] )  ] = 0;
                    }
                    echo    '<p class='. ( ( 0 == $rowcount++ %2 ) ? '"menumain"' : '"menualternate"' ) . '>' .
                            '<input type="checkbox" name="menuselection[]" id="menuselection[]" ' . 'value="'. sanitize_key( $menuitem[5] )  .'" ' . ( 1 == $menuuseroption ? 'checked="checked"' : '' ) . ' /> ' .
                            uas_clean_menu_name( $menuitem[0] ) . "</p>";
                    if ( !( strpos( $menuitem[0], 'pending-count' ) ) ) { //top level menu items with pending count span don't have submenus
                        $topmenu = $menuitem[2];
                        if ( isset( $storedsubmenu[ $topmenu] ) ) { //display submenus
                            echo ( '<div class="submenu uas-unselected"><a href="javascript:;">'. esc_html__( 'Show submenus', 'user_admin_simplifier' ) . '</a></div><div class="submenuinner">' );
                            $subrowcount = 0;
                            foreach ( $storedsubmenu[ $topmenu] as $subsub ) {
                                $combinedname = sanitize_key( $menuitem[5] . $subsub[2] );
                                $submenuuseroption = 0;
                                if ( $menusectionsubmitted ) { //deal with submitted checkboxes
                                    if ( isset( $nowselected[ $uas_selecteduser ][ $combinedname ] ) ) { // selected option for this user/submenu
                                        $submenuuseroption = $uas_options[ $uas_selecteduser ][ $combinedname ] = $nowselected[ $uas_selecteduser ][ $combinedname ] ;
                                    }
                                    else {
                                        $uas_options[ $uas_selecteduser ][ $combinedname ] = 0;
                                    }
                                }
                                if ( isset( $uas_options[ $uas_selecteduser ][ $combinedname ] ) ) { // now show saved options for this user/submenu
                                    $submenuuseroption = $uas_options[ $uas_selecteduser ][ $combinedname ];
                                } else {
                                    $uas_options[ $uas_selecteduser ][ $combinedname ] = 0;
                                }
                                echo( '<p class='. ( ( 0 == $subrowcount++ %2 ) ? '"submain"' : '"subalternate"' ) . '>' .
                                    '<input type="checkbox" name="menuselection[]" id="menuselection[]" ' .
                                    'value="'. $combinedname . '" ' . ( 1 == $submenuuseroption ? 'checked="checked"' : '' ) .
                                    ' /> ' . uas_clean_menu_name( $subsub[0] ) . '</p>' );
                            }
                            echo ( '</div>' );
                        }
                    }
                }
            }
?>
<input name="uas_save" type="submit" id="uas_save" value="<?php esc_attr_e( 'Save Changes', 'user_admin_simplifier' ); ?>" /> <br />
<?php esc_html_e( 'or', 'user_admin_simplifier' ); ?>:
<input name="uas_reset" type="submit" id="uas_reset" value="<?php esc_attr_e( 'Clear User Settings', 'user_admin_simplifier' ); ?>" />
</div>
</form>
</div>
 <?php
uas_save_admin_options( $uas_options );
}
function uas_admin_js() {
?>
<script type="text/javascript">
jQuery( function() {
    jQuery( 'form#uas_options_form #uas_user_select' ).change( function() {
            jQuery( 'form#uas_options_form' ).submit();
        } )
} );
jQuery( document ).ready( function () {
    jQuery( 'div.submenuinner' ).slideUp( 'fast' ).hide();
    //TO-DO: makes these submenu openings persist, save state in cookies?
    jQuery( '.submenu' ).click( function() {
        inner=jQuery( this ).next( '.submenuinner' );
        if ( jQuery( inner ).is( ":hidden" ) ) {
            jQuery( inner ).show().slideDown( 'fast' );
            jQuery( this ).removeClass( 'uas-unselected' ).addClass( 'uas_selected' );
            jQuery( this ).children( 'a' ).text( '<?php esc_html_e( 'Hide submenus', 'user_admin_simplifier' )?>' );
        } else {
            jQuery( inner ).slideUp( 'fast' ).hide();
            jQuery( this ).removeClass( 'uas_selected' ).addClass( 'uas-unselected' );
            jQuery( this ).children( 'a' ).text( '<?php esc_html_e( 'Show submenus', 'user_admin_simplifier' )?>' );

        }
} );
} );
</script>
<?php
}
function uas_admin_css() {
?>
<style type="text/css">

.uas-unselected {
    background-image:url( <?php echo ( plugins_url( 'images/plus15.png', __FILE__ ) ); ?> );
}

.uas_selected {
    background-image:url( <?php echo ( plugins_url( 'images/minus15.png', __FILE__ ) ); ?> );
}

.submenu {
    margin-left:200px;
    padding-left:20px;
    font-size:12px;
    height:22px;
    width:200px;
    margin-top:-25px;
    position:absolute;
    background-repeat:no-repeat;
    background-position:left top;
}

.submenuinner {
    margin-left:50px;
}

.uas_options_form {
    font-size:14px;
}

.uas_options_form p {
    margin:0 0;
    padding:.5em .5em;
}

.uas_options_form input[type="submit"] {
    font-size:18px;
    margin-top:10px;
    margin-bottom:10px;
}

.uas_options_form select {
    min-width:200px;
    padding:5px;
    font-size:16px;
    height: 34px;
}

#choosemenus {
    border-width:1px;
    border-color:#ccc;
    padding:10px;
    border-style:solid;
}

.submain {
    background-color: #F3F3F3;
}

.subalternate {
    background-color: #ECECEC;
}

.menumain {
    background-color: #FCFCFC;
}

.menualternate {
    background-color: #DDDDDD;
}
</style>
<?php
}