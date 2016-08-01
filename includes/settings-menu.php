<?php

class DisableBloggingMenu {
    private $menu_options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'dsbl_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'menu_page_init' ) );
    }

    public function dsbl_add_plugin_page() {
        add_options_page(
            'Menu Settings', // page_title
            'Menu', // menu_title
            'manage_options', // capability
            'dsbl-menu', // menu_slug
            array( $this, 'dsbl_create_admin_page' ) // function
        );
    }

    public function dsbl_create_admin_page() {
        $this->menu_options = get_option( 'menu_option_name' ); ?>

        <div class="wrap">
            <h2>Menu Settings</h2>
            <p>Additional menu items can be hidden from the sidebar and toolbar</p>
            <?php settings_errors(); ?>

            <form method="post" action="options.php">
                <?php
                    settings_fields( 'menu_option_group' );
                    do_settings_sections( 'menu-admin' );
                    submit_button();
                ?>
            </form>
        </div>
    <?php }

    public function menu_page_init() {
        register_setting(
            'menu_option_group', // option_group
            'menu_option_name', // option_name
            array( $this, 'menu_sanitize' ) // sanitize_callback
        );

        add_settings_section(
            'menu_setting_section', // id
            'Settings', // title
            array( $this, 'menu_section_info' ), // callback
            'menu-admin' // page
        );

        add_settings_field(
            'menu_0', // id
            'Menu', // title
            array( $this, 'menu_0_callback' ), // callback
            'menu-admin', // page
            'menu_setting_section' // section
        );
    }

    public function menu_sanitize($input) {
        $sanitary_values = array();
        if ( isset( $input['menu_0'] ) ) {
            $sanitary_values['menu_0'] = $input['menu_0'];
        }
        return $sanitary_values;
    }

    public function menu_section_info() {

    }

    public function menu_0_callback() {
        // printf(
        //     '<input type="checkbox" name="menu_option_name[menu_0]" id="menu_0" value="menu_0" %s> <label for="menu_0">Main menu items</label>',
        //     ( isset( $this->menu_options['menu_0'] ) && $this->menu_options['menu_0'] === 'menu_0' ) ? 'checked' : ''
        // );

        $options = get_option( 'menu-admin' );

        global $menu;
        foreach ( $menu as $group => $items ) {
            foreach ( $items as $position => $item ) {
                if ( $position != 2 ) continue; // Only show the second index of each item
                printf(
                    '<input type="checkbox" name="menu_option_name[menu_' . $item . ']" id="menu_' . $item . '" value="menu_' . $item . '" %s> <label for="menu_0">' . $item . '</label>',
                    ( isset( $this->menu_options['menu_0'] ) && $this->menu_options['menu_0'] === 'menu_0' ) ? 'checked' : ''
                );
                echo '<br>';
                // echo '<pre>'; print_r( $position ); echo '</pre>';
            }
        }
    }
}
if ( is_admin() ) {
    $menu = new DisableBloggingMenu();
}

/* 
 * Retrieve this value with:
 * $menu_options = get_option( 'menu_option_name' ); // Array of All Options
 * $menu_0 = $menu_options['menu_0']; // Menu
 */
