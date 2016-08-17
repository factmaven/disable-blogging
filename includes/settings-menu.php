<?php

if ( !class_exists( 'FMC_DisableBloggingMenu' ) ) {
    
    class FMC_DisableBloggingMenu {

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'dsbl_settings_menu_add' ), 10, 1 );
            add_action( 'admin_menu', array( $this, 'dsbl_menu_items' ), 10, 1 );
        }

        public function dsbl_settings_menu_add() { // Set up plugin settings page
            add_submenu_page(
                'options-general.php', // Parent Menu
                'Menu Settings', // Page Title
                'Menu', // Menu Title
                'manage_options', // Capability
                'menu-settings', // Slug
                array( $this, 'dsbl_settings_menu_init' ) // Callback Function
                );
        }

        public function dsbl_settings_menu_init() { // Save checkbox values in an array and display a success message
            $sidebar_menu = get_option( 'dsbl_remove_menu_items' );

            if ( isset( $_POST['dsbl_options'] ) && !empty( $_POST['dsbl_options'] ) ) {
                if ( array_key_exists('remove_menu', $_POST )) {
                    update_option( 'dsbl_remove_menu_items', $_POST['remove_menu'] );
                }
                else { // When all options are unchecked, set array to null
                    update_option( 'dsbl_remove_menu_items', NULL );
                }
                ?>
                <div class="notice notice-success is-dismissible"> 
                    <p><strong>Settings saved.</strong></p>
                </div>
                <?php
            }

            $sidebar_menu = get_option( 'dsbl_remove_menu_items' );

            // global $menu;
            // foreach ( $menu as $group => $item ) { // DEBUG
            //     if ( !empty( $item[0] ) ) {
            //         echo $item[0] . " > " . $item[2] . "<br>";
            //     }
            //     else {
            //         $item[0] = 'Seperator';
            //         echo $item[0] . " > " . $item[2] . "<br>";
            //     }
            // }

            ?>
            <div class="wrap">
                <h1>Menu Settings</h1>
                <p>Additional menu items can be hidden from the sidebar and toolbar.</p>

                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">Sidebar Menu</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>Sidebar Menu</span></legend>
                                        <?php
                                        global $menu;
                                        foreach ( $menu as $group => $item ) {
                                            if ( !empty( $item[0] ) ) {
                                        ?>
                                                <label for="<?php echo ( $item[2] ); ?>">
                                                <input name="remove_menu[]" type="checkbox" value="<?php echo( $item[2] ); ?>" <?php if ( is_array( $sidebar_menu ) && in_array( $item[2], $sidebar_menu ) ) { echo 'checked="checked" '; } ?> />
                                                <?php echo ( $item[0] ); ?></label>
                                                <br>
                                        <?php
                                            }
                                         } ?>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Toolbar Menu</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text"><span>Toolbar Menu</span></legend>
                                        <?php
                                        global $menu;
                                        foreach ( $menu as $group => $item ) {
                                            if ( !empty( $item[0] ) ) {
                                        ?>
                                                <label for="<?php echo ( $item[2] ); ?>">
                                                <input name="remove_menu[]" type="checkbox" value="<?php echo( $item[2] ); ?>" <?php if ( is_array( $sidebar_menu ) && in_array( $item[2], $sidebar_menu ) ) { echo 'checked="checked" '; } ?> />
                                                <?php echo ( $item[0] ); ?></label>
                                                <br>
                                        <?php
                                            }
                                         } ?>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php submit_button( 'Save Changes', 'primary', 'dsbl_options' ); ?>
                </form>
            </div>
            <?php
        }

        public function dsbl_menu_items() {
            $sidebar_menu = get_option( 'dsbl_remove_menu_items' );

            if ( is_array( $sidebar_menu ) || is_object( $sidebar_menu ) ) {
                foreach ( $sidebar_menu as $item ) {
                    remove_menu_page( $item );
                }
            }
        }

    }
}

if ( class_exists( 'FMC_DisableBloggingMenu' ) ) { // Instantiate the plugin class
    global $dsbl_profile;
    $dsbl_profile = new FMC_DisableBloggingMenu();
}