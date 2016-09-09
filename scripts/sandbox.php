<?php

/*
?>
<script type="text/javascript">
jQuery(function($){
    $(".accordion").accordion({ header: "h3" });
    $(".accordion").last().accordion("option", "icons", false);
});
</script>

<div class="accordion">
    <div>
        <h3><a href="#">First</a></h3>
        <div>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</div>
    </div>
    <div>
        <h3><a href="#">Second</a></h3>
        <div>Phasellus mattis tincidunt nibh.</div>
    </div>
    <div>
        <h3><a href="#">Third</a></h3>
        <div>Nam dui erat, auctor a, dignissim quis.</div>
    </div>
</div>
<?php
*/

// Example to get settings
// $my_settings = get_option( 'dsbl_menu_settings' );

// echo '<pre>'; print_r( $my_settings ); echo '</pre>';

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