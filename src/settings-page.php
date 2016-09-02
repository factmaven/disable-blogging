<?php
/**
 * WordPress settings API demo class
 *
 * @author Fact Maven Corp.
 */

if ( !class_exists( 'Fact_Maven_Disable_Blogging' ) ):
class Fact_Maven_Disable_Blogging {

    private $settings_api;

    function __construct() {
        $this->settings_api = new Fact_Maven_Disable_Blogging_Settings;

        add_action( 'admin_init', array($this, 'admin_init' ) );
        add_action( 'admin_menu', array($this, 'admin_menu' ) );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page(
            'Blogging Settings', // Page title
            'Blogging', // Menu title
            'manage_options', // Capability
            'blogging', // URL slug
            array($this, 'plugin_page' ) // Callback function
            );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'dsbl_basics',
                'title' => __( 'General Settings', 'dsbl' )
            ),
            array(
                'id' => 'dsbl_profile',
                'title' => __( 'Profile Page', 'dsbl' )
            ),
            array(
                'id' => 'dsbl_others',
                'title' => __( 'Other Settings', 'dsbl' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $options_contact = [];
        $options_contact['url'] = 'Website';
        foreach ( wp_get_user_contact_methods() as $value => $label ) { 
            $options_contact[$value] = $label;
        }

        $options_name = [];
        $show_avatars = get_option( 'show_avatars' );
        $options_name['description'] = 'Biographical Info';
        $options_name[$show_avatars] = 'Avatar Display';

        $settings_fields = array(
            'dsbl_basics' => array( // General Settings
                array(
                    'name' => 'text_val',
                    'label' => __( 'Text Input', 'dsbl' ),
                    'desc' => __( 'Text input description', 'dsbl' ),
                    'type' => 'text',
                    'default' => 'Title',
                    'sanitize_callback' => 'intval'
                )
            ),
            'dsbl_profile' => array( // Profile Settings
                array(
                    'name' => 'personal_options',
                    'label' => __( 'Personal Options', 'dsbl' ),
                    'type' => 'multicheck',
                    'options' => array(
                        'rich_editing' => 'Visual Editor',
                        'admin_color' => 'Admin Color Scheme',
                        'comment_shortcuts' => 'Keyboard Shortcuts',
                        'admin_bar_front' => 'Toolbar'
                    )
                ),
                array(
                    'name' => 'name',
                    'label' => __( 'Name', 'dsbl' ),
                    'type' => 'multicheck',
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
                    'options' => $options_contact
                ),
                array(
                    'name' => 'about_yourself',
                    'label' => __( 'About Yourself', 'dsbl' ),
                    'desc' => __( '(Avatar settings can be managed in <a href="./options-discussion.php#show_avatars">Discussion</a> page.)', 'dsbl' ),
                    'type' => 'multicheck',
                    'options' => $options_name
                    /*'options' => array(
                        'description' => 'Biographical Info',
                        'show_avatars' => 'Avatar Display'
                    )*/
                ),
                array(
                    'name' => 'additional_fields',
                    'label' => __( 'Additional Fields', 'dsbl' ),
                    'desc' => __( 'List additional fields to hide by ID, one per line.', 'dsbl' ),
                    'type' => 'textarea'
                )
            ),
            'dsbl_others' => array( // Other Settings
                array(
                    'name' => 'text',
                    'label' => __( 'Text Input', 'dsbl' ),
                    'desc' => __( 'Text input description', 'dsbl' ),
                    'type' => 'text',
                    'default' => 'Title'
                ),
                array(
                    'name' => 'textarea',
                    'label' => __( 'Textarea Input', 'dsbl' ),
                    'desc' => __( 'Textarea description', 'dsbl' ),
                    'type' => 'textarea'
                ),
                array(
                    'name' => 'checkbox',
                    'label' => __( 'Checkbox', 'dsbl' ),
                    'desc' => __( 'Checkbox Label', 'dsbl' ),
                    'type' => 'checkbox'
                ),
                array(
                    'name' => 'radio',
                    'label' => __( 'Radio Button', 'dsbl' ),
                    'desc' => __( 'A radio button', 'dsbl' ),
                    'type' => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),
                array(
                    'name' => 'multicheck',
                    'label' => __( 'Multile checkbox', 'dsbl' ),
                    'desc' => __( 'Multi checkbox description', 'dsbl' ),
                    'type' => 'multicheck',
                    'options' => array(
                        'one' => 'One',
                        'two' => 'Two',
                        'three' => 'Three',
                        'four' => 'Four'
                    )
                ),
                array(
                    'name' => 'selectbox',
                    'label' => __( 'A Dropdown', 'dsbl' ),
                    'desc' => __( 'Dropdown description', 'dsbl' ),
                    'type' => 'select',
                    'options' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),
                array(
                    'name' => 'password',
                    'label' => __( 'Password', 'dsbl' ),
                    'desc' => __( 'Password description', 'dsbl' ),
                    'type' => 'password',
                    'default' => ''
                ),
                array(
                    'name' => 'file',
                    'label' => __( 'File', 'dsbl' ),
                    'desc' => __( 'File description', 'dsbl' ),
                    'type' => 'file',
                    'default' => ''
                )
            )
        );

        $options_yoast = array( // Yoast SEO plugin
            'name' => 'yoast_seo',
            'label' => 'Yoast SEO', 
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
            $settings_fields['dsbl_profile'][] = $options_yoast;
        }

        $options_um = array( // Ultimate Member plugin
            'name' => 'ultimate_member',
            'label' => 'Ultimate Member', 
            'type' => 'multicheck',
            'options' => array(
                'um_set_api_key' => 'Ultimate Member REST API',
                'um_role' => 'Community Role'
            )
        );
        if ( is_plugin_active( 'ultimate-member/index.php' ) ) {
            $settings_fields['dsbl_profile'][] = $options_um;
        }

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
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
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }
        return $pages_options;
    }
}
endif;