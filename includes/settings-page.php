<?php

class Fact_Maven_Disable_Blogging {

    private $settings_api;

    function __construct() {
        # Call the settings API
        $this -> settings_api = new Fact_Maven_Disable_Blogging_Settings_API;

        # Set and instantiate the class
        add_action( 'admin_init', array( $this, 'admin_init' ), 10, 1 );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 10, 1 );
        # Reorder 'Blogging' under 'General' submenu
        add_filter( 'custom_menu_order', array( $this, 'submenu_order' ), 10, 1 );
    }

    function admin_init() {
        $this -> settings_api -> set_sections( $this -> get_settings_sections() );
        $this -> settings_api -> set_fields( $this -> get_settings_fields() );
        $this -> settings_api -> admin_init();
    }

    function admin_menu() {
        add_options_page(
            'Blogging Settings', // Page title
            'Blogging', // Menu title
            'manage_options', // Capability
            'blogging', // URL slug
            array( $this, 'plugin_page' ) // Callback function
            );
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
        $sections = array(
            array(
                'id' => 'factmaven_dsbl_general_settings',
                'title' => __( 'General', 'dsbl' ),
            ),
            array(
                'id' => 'factmaven_dsbl_extra_settings',
                'title' => __( 'Extra', 'dsbl' ),
            ),
            array(
                'id' => 'factmaven_dsbl_profile_settings',
                'title' => __( 'Profile', 'dsbl' ),
            ),
            array(
                'id' => 'factmaven_dsbl_menu_settings',
                'title' => __( 'Menu', 'dsbl' ),
            ),
            /*array(
                'id' => 'dsbl_remove_profile_fields',
                'title' => __( 'Test', 'dsbl' ),
            ),*/
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     */
    function get_settings_fields() {
        
        $options_contact = [];
        $options_contact['url'] = 'Website';
        foreach ( wp_get_user_contact_methods() as $value => $label ) {
            $options_contact[$value] = $label;
        }

        global $menu, $submenu;
        $options_menu = [];
        foreach ( $menu as $group => $item ) {
            if ( !empty( $item[0] ) ) {
                $options_menu[$item[2]] = $item[0];
            }
            else {
                $item[0] = '<span class="description">- Separator -</span>';
                $options_menu[$item[2]] = $item[0];
            }
        }
        $options_submenu = [];
        foreach ( $submenu as $group => $item ) {
            foreach ( $item as $key ) {
                $options_submenu[$key[2]] = $key[0];
            }
        }

        $settings_fields = array(
            'factmaven_dsbl_general_settings' => array( // General Settings
                array(
                    'name' => 'disable_posts',
                    'label' => __( 'Posting', 'dsbl' ),
                    'desc' => __( 'Previous posts will still be accessible.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
                array(
                    'name' => 'disable_comments',
                    'label' => __( 'Comments', 'dsbl' ),
                    'desc' => __( 'Previous comments will be hidden.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
                array(
                    'name' => 'disable_author_page',
                    'label' => __( 'Author Page', 'dsbl' ),
                    'desc' => __( 'Redirects the author links to the homepage.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
                array(
                    'name' => 'disable_feeds',
                    'label' => __( 'Feeds & Related', 'dsbl' ),
                    'desc' => __( 'Includes <a href="https://codex.wordpress.org/Glossary#Pingback" target="_blank">pingbacks</a>, <a href="https://codex.wordpress.org/Glossary#Trackback" target="_blank">trackbacks</a>, & <a href="https://codex.wordpress.org/XML-RPC_Support" target="_blank">XML-RPC</a>.', 'dsbl' ),
                    'type' => 'radio',
                    'default' => 'disable',
                    'options' => array(
                        'enable' => 'Enable',
                        'disable' => 'Disable',
                    ),
                ),
                /*array(
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
                    'label' => __( 'Query Strings', 'dsbl' ),
                    'desc' => __( 'Remove query strings from static resources (<code>ver=</code>)', 'dsbl' ),
                    'type' => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name' => 'emojis',
                    'label' => __( 'Emojis', 'dsbl' ),
                    'desc' => __( 'Disable WordPress emojis', 'dsbl' ),
                    'type' => 'checkbox',
                    'default' => 'on',
                ),*/
            ),
            'factmaven_dsbl_extra_settings' => array( // Extra Settings
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
                    'label' => __( 'Query Strings', 'dsbl' ),
                    'desc' => __( 'Remove query strings from static resources (<code>ver=</code>)', 'dsbl' ),
                    'type' => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name' => 'emojis',
                    'label' => __( 'Emojis', 'dsbl' ),
                    'desc' => __( 'Disable WordPress emojis', 'dsbl' ),
                    'type' => 'checkbox',
                    'default' => 'on',
                ),
            ),
            'factmaven_dsbl_profile_settings' => array( // User Profile
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
                    )
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
                        'display_name' => 'Display Name'
                    )
                ),
                array(
                    'name' => 'contact_info',
                    'label' => __( 'Contact Info', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'url' => 'url',
                    ),
                    'options' => $options_contact
                ),
                array(
                    'name' => 'about_yourself',
                    'label' => __( 'About Yourself', 'dsbl' ),
                    'desc' => __( 'Additional avatar settings can be managed in <a href="' . get_site_url() . '/wp-admin/options-discussion.php#show_avatars">Discussion</a> page.', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'description' => 'description',
                    ),
                    'options' => array(
                        'description' => 'Biographical Info',
                        'show_avatars' => 'Avatar Display'
                    )
                )
            ),
            'factmaven_dsbl_menu_settings' => array( // Admin Menu
                array(
                    'name' => 'redirect_menu',
                    'label' => __( 'Redirect hidden menu items to', 'dsbl' ),
                    'desc' => __( 'If none is selected, a denied message will be displayed instead.', 'dsbl' ),
                    'type' => 'select',
                    'default' => 'none',
                    'options' => array(
                        'index.php' => 'Dashboard',
                        'edit.php?post_type=page' => 'Pages',
                        'none' => '- None -'
                    )
                ),
                array(
                    'name' => 'hide_dashicons',
                    'label' => __( 'Hide all menu <a target="_blank" href="https://developer.wordpress.org/resource/dashicons">dashicons</a>', 'dsbl' ),
                    'desc' => __( 'The icons will only be shown when the menu is collapsed.', 'dsbl' ),
                    'type' => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),
                array(
                    'name' => 'main_menu',
                    'label' => __( 'Main Menu', 'dsbl' ),
                    'desc' => __( 'Hiding each <strong>seperator</strong> will remove the spacing in between the menu items.', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'edit.php' => 'edit.php', // Posts
                        'edit-comments.php' => 'edit-comments.php', // Comments
                        'separator1' => 'separator1', // Separator
                        'separator2' => 'separator2' // Separator
                    ),
                    'options' => $options_menu
                ),
                array(
                    'name' => 'submenu',
                    'label' => __( 'Submenu', 'dsbl' ),
                    'type' => 'multicheck',
                    'default' => array(
                        'edit.php' => 'edit.php', // Posts > All Posts
                        'post-new.php' => 'post-new.php', // Posts > Add New
                        'edit-tags.php?taxonomy=category' => 'edit-tags.php?taxonomy=category', // Posts > Categories
                        'edit-tags.php?taxonomy=post_tag' => 'edit-tags.php?taxonomy=post_tag', // Posts > Tags
                        'tools.php' => 'tools.php', // Tools > Available Tools
                        'import.php' => 'import.php', // Tools > Import
                        'export.php' => 'export.php', // Tools > Export
                        'options-discussion.php' => 'options-discussion.php', // Settings > Discussion                   
                    ),
                    'options' => $options_submenu
                )
            ),
        );

        $options_yoast = array( // Yoast SEO plugin
            'name' => 'yoast_seo',
            'label' => __( 'Yoast SEO', 'dsbl' ),
            'type' => 'multicheck',
            'options' => array(
                'wpseo_author_title' => 'Title to use for Author page',
                'wpseo_author_metadesc' => 'Meta description to use for Author page',
                'wpseo_author_exclude' => 'Exclude user from Author-sitemap',
                'wpseo_keyword_analysis_disable' => 'Disable SEO analysis',
                'wpseo_content_analysis_disable' => 'Disable readability analysis'
            )
        );
        if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
            $settings_fields['factmaven_dsbl_profile_settings'][] = $options_yoast;
        }

        $options_um = array( // Ultimate Member plugin
            'name' => 'ultimate_member',
            'label' => __( 'Ultimate Member', 'dsbl' ),
            'type' => 'multicheck',
            'options' => array(
                'um_set_api_key' => 'Ultimate Member REST API',
                'um_role' => 'Community Role'
            )
        );
        if ( is_plugin_active( 'ultimate-member/index.php' ) ) {
            $settings_fields['factmaven_dsbl_profile_settings'][] = $options_um;
        }

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">
        <h1>Blogging Settings</h1>';
        // SANDBOX
        require_once dirname( __FILE__ ) . '/sandbox.php';
        // SANDBOX
        $this -> settings_api -> show_navigation();
        $this -> settings_api -> show_forms();
        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ( $pages as $page) {
                $pages_options[$page -> ID] = $page -> post_title;
            }
        }
        return $pages_options;
    }
}