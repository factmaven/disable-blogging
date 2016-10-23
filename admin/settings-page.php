<?php
/**
 * Settings page with all of the options to
 * choose which functions to run. This depends
 * on the Settings API Wrapper to generate the fields.
 *
 * @author Fact Maven Corp.
 * @link https://wordpress.org/plugins/disable-blogging/
 */

class Fact_Maven_Disable_Blogging_Settings {
    # Define the Setting API variable
    private $settings_api;

    function __construct() {
        # Call the settings API
        $this->settings_api = new Fact_Maven_Disable_Blogging_Settings_API;
        # Set and instantiate the class
        add_action( 'admin_init', array( $this, 'admin_init' ), 10, 1 );
        # Create the plugin's settings page
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 10, 1 );
    }

    function admin_init() {
        # Setting sections
        $this->settings_api->set_sections( $this->get_settings_sections() );
        # Setting fields in each section
        $this->settings_api->set_fields( $this->get_settings_fields() );
        # Instantiate settings page
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        # Add the plugin settings page
        add_options_page(
            __( 'Blogging Settings', 'dsbl' ), // Page title
            __( 'Blogging', 'dsbl' ), // Menu title
            'manage_options', // Capability
            'blogging', // URL slug
            array( $this, 'plugin_page' ) // Callback function
            );
        # If current user can 'manage_options' reorder plugin settings link
        if ( current_user_can( 'manage_options' ) ) {
            # Reorder 'Blogging' under 'General' submenu
            add_filter( 'custom_menu_order', array( $this, 'submenu_order' ), 10, 1 );
        }
    }

    function submenu_order( $menu_order ) {
        # Get submenu key location based on slug
        global $submenu;
        $settings = $submenu['options-general.php'];
        foreach ( $settings as $key => $details ) {
            if ( $details[2] == 'blogging' ) {
                $index = $key;
            }
        }
        # Set the 'Blogging' menu below 'General'
        $submenu['options-general.php'][11] = $submenu['options-general.php'][$index];
        unset( $submenu['options-general.php'][$index] );
        # Reorder the menu based on the keys in ascending order
        ksort( $submenu['options-general.php'] );
        # Return the new submenu order
        return $menu_order;
    }

    function get_settings_sections() {
        # Create setting tabs for each section
        $sections = array(
            array(
                'id' => 'factmaven_dsbl_general',
                'title' => __( 'General', 'dsbl' ),
            ),
            array(
                'id' => 'factmaven_dsbl_extra',
                'title' => __( 'Extra', 'dsbl' ),
            ),
            array(
                'id' => 'factmaven_dsbl_profile',
                'title' => __( 'Profile', 'dsbl' ),
            ),
            array(
                'id' => 'factmaven_dsbl_menu',
                'title' => __( 'Menu', 'dsbl' ),
            ),
        );
        return $sections;
    }

    function get_settings_fields() {
        # List all contact fields
        $options_contact['url'] = 'Website';
        # List additional contact fields if they exist
        foreach ( wp_get_user_contact_methods() as $value => $label ) {
            $options_contact[$value] = $label;
        }

        global $menu;
        # Admin menu
        $options_redirect['none'] = __( '- None -', 'dsbl' );
        foreach ( $menu as $group => $item ) {
            # If the menu title isn't blank and a custom setting, continue
            if ( ! empty( $item[0] ) && strstr( $item[2], '.php' ) ) {
                # Set each page slug as the value and display the label, also remove the number count
                $options_redirect[$item[2]] = preg_replace( '/<span(.*?)span>/', '', $item[0] );
            }
            # If the menu title isn't blank and is a custom menu, continue
            if ( ! empty( $item[0] ) && ! strstr( $item[2], '.php' ) ) {
                # Set each page slug as the value and display the label, also remove the number count
                $options_redirect['admin.php?page=' . $item[2]] = preg_replace( '/<span(.*?)span>/', '', $item[0] );
            }
        }

        # Create the settings fields
        $settings_fields = array(
            /* General Setting Fields */
            'factmaven_dsbl_general' => array(
                array(
                    'name' => 'posts',
                    'label' => __( 'Posting', 'dsbl' ),
                    'desc' => __( 'Links to previous posts will still be accessible.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
                array(
                    'name' => 'comments',
                    'label' => __( 'Comments', 'dsbl' ),
                    'desc' => __( 'Previous comments will be hidden from view.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
                array(
                    'name' => 'author_page',
                    'label' => __( 'Author Page', 'dsbl' ),
                    'desc' => __( 'Helps prevent <a title="A common technique hackers use to reveal the usernames.">user enumeration</a>, redirects author links to homepage.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
                array(
                    'name' => 'feeds',
                    'label' => __( 'Feeds & Related', 'dsbl' ),
                    'desc' => __( 'Includes <a href="https://codex.wordpress.org/Glossary#Pingback" target="_blank">pingbacks</a>, <a href="https://codex.wordpress.org/Glossary#Trackback" target="_blank">trackbacks</a>, & <a href="https://codex.wordpress.org/XML-RPC_Support" target="_blank">XML-RPC</a>.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
            ),
            /* Extra Setting Fields */
            'factmaven_dsbl_extra' => array(
                array(
                    'name' => 'help_tabs',
                    'label' => __( 'Help Tabs', 'dsbl' ),
                    'desc' => __( 'Remove <span class="description">Help</span> tabs from the admin header', 'dsbl' ),
                    'type' => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name' => 'howdy',
                    'label' => __( '"Howdy," greeting', 'dsbl' ),
                    'desc' => __( 'Remove the greeting in the admin bar next to the username', 'dsbl' ),
                    'type' => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name' => 'query_strings',
                    'label' => __( 'Have query string version', 'dsbl' ),
                    'desc' => __( 'It will improve cache performance and overall <a href="https://developers.google.com/speed/pagespeed" target="_blank">page speed</a> score.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'removed',
                    'options' => array(
                        'shown' => '<strong>Shown</strong>: <code>../' . str_replace( ' ', '', strtolower( wp_get_theme() ) ) . '/style.css?ver=' . get_bloginfo( 'version' ) . '</code>',
                        'removed' => '<strong>Removed</strong>: <code>../' . str_replace( ' ', '', strtolower( wp_get_theme() ) ) . '/style.css</code>',
                    ),
                ),
                array(
                    'name' => 'emojis',
                    'label' => __( '<a href="https://codex.wordpress.org/Emoji" target="_blank">Emoji</a> Support', 'dsbl' ),
                    'desc' => __( 'Remove code in header used to add support for emojis<p class="description">Emojis will still work in browsers which have built in support for them.</p>', 'dsbl' ),
                    'type' => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name' => 'admin_footer',
                    'label' => __( 'Change admin footer to', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'site_info',
                    'options' => array(
                        'default' => '<strong>Default</strong>: <code>Thank you for creating with <a href="https://wordpress.org/" target="_blank">WordPress</a>.</code>',
                        'site_info' => '<strong>Site Info</strong>: <code>Copyright &copy; ' . date("Y") . ' <a href="' . site_url() . '">' . get_bloginfo( 'name' ) . '</a></code>',
                        'remove' => '<strong>None</strong>: Remove the WordPress credits',
                    ),
                ),
            ),
            /* User Profile Setting Fields */
            'factmaven_dsbl_profile' => array(
                array(
                    'name' => 'personal_options',
                    'label' => __( 'Personal Options', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'rich_editing' => 'rich_editing', // Visual Editor
                        'admin_color' => 'admin_color', // Admin Color Scheme
                        'comment_shortcuts' => 'comment_shortcuts', // Keyboard Shortcuts
                        'admin_bar_front' => 'admin_bar_front', // Toolbar
                    ),
                    'options' => array(
                        'rich_editing' => 'Visual Editor',
                        'admin_color' => 'Admin Color Scheme',
                        'comment_shortcuts' => 'Keyboard Shortcuts',
                        'admin_bar_front' => 'Toolbar',
                    ),
                ),
                array(
                    'name' => 'name',
                    'label' => __( 'Name', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'nickname' => 'nickname',
                        'display_name' => 'display_name',
                    ),
                    'options' => array(
                        'first_name' => 'First Name',
                        'last_name' => 'Last Name',
                        'nickname' => 'Nickname',
                        'display_name' => 'Display Name',
                    ),
                ),
                array(
                    'name' => 'contact_info',
                    'label' => __( 'Contact Info', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'url' => 'url',
                    ),
                    'options' => $options_contact,
                ),
                array(
                    'name' => 'about_yourself',
                    'label' => __( 'About Yourself', 'dsbl' ),
                    'desc' => __( 'If Comments are enabled, additional avatar settings can be managed in <a href="' . admin_url( 'options-discussion.php#show_avatars' ) . '">Discussion</a> page.', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'description' => 'description',
                    ),
                    'options' => array(
                        'description' => 'Biographical Info',
                        'show_avatars' => 'Avatar Display',
                    ),
                ),
                array(
                    'name' => 'additional_fields',
                    'label' => __( 'Additional Fields', 'dsbl' ),
                    'desc' => __( 'Hide additional profile fields created by plugins/theme by their label ID.<br>Read the <a href="https://wordpress.org/plugins/disable-blogging/faq" target="_blank">FAQ</a> on how to find the label IDs.', 'dsbl' ),
                    'placeholder' => __( "some_label\nanother_label\nyet_another_label", 'dsbl' ),
                    'type' => 'textarea',
                ),
            ),
            /* Admin Menu Setting Fields */
            'factmaven_dsbl_menu' => array(
                array(
                    'name' => 'dashicons',
                    'label' => __( 'Have menu <a target="_blank" href="https://developer.wordpress.org/resource/dashicons">dashicons</a>', 'dsbl' ),
                    'desc' => __( 'Will only be shown when the menu is collapsed.', 'dsbl' ),
                    'type' => 'select',
                    'default' => 'shown',
                    'options' => array(
                        'shown' => 'Shown',
                        'hidden' => 'Hidden',
                    ),
                ),
                array(
                    'name' => 'separator',
                    'label' => __( 'Menu separators will be', 'dsbl' ),
                    'desc' => __( 'This is the spacing between some of the menu items.', 'dsbl' ),
                    'type' => 'select',
                    'default' => 'removed',
                    'options' => array(
                        'shown' => 'Shown',
                        'removed' => 'Removed',
                    ),
                ),
                array(
                    'name' => 'redirect_menu',
                    'label' => __( 'Redirect hidden menu items to', 'dsbl' ),
                    'desc' => __( 'If <strong>none</strong> is selected, hidden menu items will still be manually accessible.', 'dsbl' ),
                    'type' => 'select',
                    'default' => 'none',
                    'options' => $options_redirect,
                ),
                array(
                    'name' => 'main_menu',
                    'label' => __( 'Admin Menu<br><sup>(parent menu only)</sup>', 'dsbl' ),
                    'desc' => __( 'Hide unwanted menu items by their slug - one per line.<br>Read the <a href="https://wordpress.org/plugins/disable-blogging/faq" target="_blank">FAQ</a> on how to find the slug name.', 'dsbl' ),
                    'placeholder' => __( "index.php\ntools.php\nplugin-slug", 'dsbl' ),
                    'type' => 'textarea',
                ),
            ),
        );
        # Return the list of the list of setting fields
        return $settings_fields;
    }

    function plugin_page() {
        # Display the setting section and fields
        echo '<div class="wrap">
        <h1>Blogging Settings</h1>';
        $this->settings_api->show_navigation();
        # Show each section form
        $this->settings_api->show_forms();
        echo '</div>';
    }
}

# Instantiate the class
new Fact_Maven_Disable_Blogging_Settings();