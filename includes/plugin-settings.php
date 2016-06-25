<?php

add_action( 'admin_menu', 'dsbl_add_admin_menu', 10, 1 );
add_action( 'admin_init', 'dsbl_settings_init', 10, 1 );

function dsbl_add_admin_menu() { 
    add_options_page(
        'Blogging Settings', // page_title
        'Blogging', // menu_title
        'manage_options', // capability
        'dsbl-blogging', // menu_slug
        'dsbl_options_page' // function
    );
}

function dsbl_settings_init() { 
    register_setting( 'pluginPage', 'dsbl_settings' );

    add_settings_section(
        'dsbl_pluginPage_section', 
        __( 'Your section description', 'wordpress' ), 
        'dsbl_settings_section_callback', 
        'pluginPage'
    );

    add_settings_field( 
        'dsbl_textarea_field_0', 
        __( 'Settings field description', 'wordpress' ), 
        'dsbl_textarea_field_0_render', 
        'pluginPage', 
        'dsbl_pluginPage_section' 
    );

    add_settings_field( 
        'dsbl_textarea_field_1', 
        __( 'Settings field description', 'wordpress' ), 
        'dsbl_textarea_field_1_render', 
        'pluginPage', 
        'dsbl_pluginPage_section' 
    );
}

function dsbl_textarea_field_0_render() { 
    $options = get_option( 'dsbl_settings' );
    ?>
    <textarea cols='40' rows='5' name='dsbl_settings[dsbl_textarea_field_0]'> 
        <?php echo $options['dsbl_textarea_field_0']; ?>
    </textarea>
    <?php
}

function dsbl_textarea_field_1_render() { 
    $options = get_option( 'dsbl_settings' );
    ?>
    <textarea cols='40' rows='5' name='dsbl_settings[dsbl_textarea_field_1]'> 
        <?php echo $options['dsbl_textarea_field_1']; ?>
    </textarea>
    <?php
}

function dsbl_settings_section_callback() { 
    echo __( 'This section description', 'wordpress' );
}

function dsbl_options_page() { 
    ?>
    <form action='options.php' method='post'>
        <h2>Disable Blogging</h2>
        <?php
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>
    </form>
    <?php
}