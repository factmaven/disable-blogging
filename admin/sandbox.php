<?php

// DELETE FROM wp_options WHERE option_name LIKE 'factmaven_dsbl_%'

// Example to get settings
$settings = get_option( 'factmaven_dsbl_profile' );
// echo '<pre>'; print_r( $settings ); echo '</pre>';
// echo '<pre>'; print_r( $settings['additional_fields'] ); echo '</pre>';

/*if ( isset( $settings['additional_fields'] ) ) {
    $additional_fields = explode( "\n", $settings['additional_fields'] );
    // echo '<pre>'; print_r( $additional_fields ); echo '</pre>';
    foreach ( $additional_fields as $key => $value ) {
        echo( "$('#" . $value . "').closest('tr').hide();" );
    }
}*/

if ( isset( $settings['additional_fields'] ) ) {
    $new_field = explode( "\n", $settings['additional_fields'] );
    // echo '<pre>'; print_r( $new_field ); echo '</pre>';
    foreach ( $new_field as $key => $value ) {
        echo '<pre>'; print_r( $value ); echo '</pre>';
    }
}

/*if ( is_array( $settings ) || is_object( $settings ) ) {
    foreach ( $settings as $group => $item ) {
        if( is_array($item) ) {
            foreach ( $item as $value ) {
                // echo( $value . '<br>' );
                if ( in_array( 'show_avatars', $item ) ) {
                    echo 'test';
                }
            }
        }
    }
}*/

// global $wp_admin_bar;
// // echo '<pre>'; print_r( $wp_admin_bar ); echo '</pre>';
// echo '<pre>'; print_r( $wp_admin_bar['user-actions']['parent'] ); echo '</pre>';

// if ( is_array( $settings ) || is_object( $settings ) ) {
//     foreach ( $settings as $group => $item ) {
//         // echo '<pre>'; print_r( $item ); echo '</pre>';
//         if( is_array($item) ) {
//             foreach ( $item as $value ) {
//                 echo '<pre>'; print_r( $value ); echo '</pre>';
//             }
//         }
//     }
// }

// if ( is_array( $settings ) || is_object( $settings ) ) {
//     foreach ( $settings as $group => $item ) {
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