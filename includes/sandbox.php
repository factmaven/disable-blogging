<?php

// Example to get settings
$my_settings = get_option( 'dsbl_general_settings' );

echo '<pre>'; print_r( $my_settings ); echo '</pre>';


add_action( 'admin_bar_menu', function( \WP_Admin_Bar $wp_admin_bar ) {

    $items = $wp_admin_bar->get_nodes();

    if ( ! $items )
        return;

    print '<pre>';

    foreach ( $items as $id => $item )
    {
        print "$id: " . print_r( $item, TRUE ) . "\n";
    }

    print '</pre>';
}, PHP_INT_MAX );

// if ( $my_settings['disable_posts'] == 'disable' ) {
//     echo 'yesy';
// }

// if ( is_array( $my_settings ) || is_object( $my_settings ) ) {
//     foreach ( $my_settings as $group => $item ) {
//         // echo '<pre>'; print_r( $item ); echo '</pre>';
//         if( is_array($item) ) {
//             foreach ( $item as $value ) {
//                 echo '<pre>'; print_r( $value ); echo '</pre>';
//             }
//         }
//     }
// }

// if ( is_array( $my_settings ) || is_object( $my_settings ) ) {
//     foreach ( $my_settings as $group => $item ) {
//         if( is_array($item) ) {
//             foreach ( $item as $value ) {
//                 echo '<pre>'; print_r( $value ); echo '</pre>';
//             }
//         }
//     }
// }

/*
global $menu, $submenu;
echo "<h3>Main Menu</h3>";
foreach ( $menu as $group => $item ) {
    if ( !empty( $item[0] ) ) {
        echo '<label for="' . $item[2] . '">
        <input name="some_array[]" type="checkbox" value="' . $item[2] . '" />
        ' . $item[0] . '</label>
        <br>';
    }
    else {
        $item[0] = '<span class="description">- Separator -</span>';
        $options_menu[$item[2]] = $item[0];
    }
}
echo "<h3>Submenu</h3>";
foreach ( $submenu as $group => $item ) {
    foreach ( $item as $key ) {
        echo '<label for="' . $key[2] . '">
        <input name="some_array[]" type="checkbox" value="' . $key[2] . '" />
        ' . $key[0] . '</label>
        <br>';
    }
}
*/