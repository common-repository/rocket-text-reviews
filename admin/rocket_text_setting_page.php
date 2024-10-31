<?php

defined('ABSPATH') or die('The Autumn Wind Is A Pirate');

/**
 * custom option and settings
 */
function rocket_text_settings_init() {
    // register a new setting for "rocket_text" page
    register_setting('rocket_text', 'rocket_text_options');
    register_setting('rocket_text', 'rocket_text_enable_chat');

    // register a new section in the "rocket_text" page
    add_settings_section(
        'rocket_text_section_developers',
        __('Rocket Text API Settings', 'rocket_text'),
        'rocket_text_section_developers_cb',
        'rocket_text'
    );

    add_settings_field(
        'rocket_text_api_field_keys',
        __('Rocket text API Keys: ', 'rocket_text'),
        'rocket_text_api_field_keys_cb',
        'rocket_text',
        'rocket_text_section_developers',
        [
            'label_for' => 'rocket_text_api_field_keys',
            'class' => 'rocket_text_row',
            'rocket_text_api_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'rocket_text_enable_chat',
        __('Enable Rocket Text Chat: ', 'rocket_text'),
        'rocket_text_enable_chat_cb',
        'rocket_text',
        'rocket_text_section_developers',
        [
            'label_for' => 'rocket_text_enable_chat',
            'class' => 'rocket_text_row',
            'rocket_text_api_custom_data' => 'custom',
        ]
    );    

}


add_action('admin_init', 'rocket_text_settings_init');


function rocket_text_section_developers_cb($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('', 'rocket_text'); ?></p>
    <?php
}


function rocket_text_api_field_keys_cb($args) {
// get the value of the setting we've registered with register_setting()
    $rocket_text_options = get_option('rocket_text_options');
    // output the field
    ?>
    <div class="form-group">
        <input type="text" class="form-control" id="<?= esc_attr($args['label_for']); ?>"
               data-custom="<?= esc_attr($args['rocket_text_api_custom_data']); ?>"
               placeholder="Enter Rocket Text API key"
               name="rocket_text_options[<?= esc_attr($args['label_for']); ?>]"
               value="<?= isset( $rocket_text_options[$args['label_for']] ) ? esc_attr( $rocket_text_options[$args['label_for']]) : '' ?>" required>
    </div>
    <?php
}

    function rocket_text_enable_chat_cb($args) {
 
        $rocket_text_options = get_option('rocket_text_options');

        if( !isset( $options[$args['label_for']] ) ) $options[$args['label_for']] = 0;

        $html .= '<div class="form-group">';      
            $html .= '<input name="rocket_text_enable_chat" id="rocket_text_enable_chat" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'rocket_text_enable_chat' ), false ) . ' />';
        $html .= '</div>';

        echo $html;
     
    }    

function rocket_text_options_page() {
    // add top level menu page
    add_menu_page(
        'rocket_text',
        'Rocket Text',
        'manage_options',
        'rocket_text',
        'rocket_text_options_page_html',
        'dashicons-star-filled'
    );
}

add_action('admin_menu', 'rocket_text_options_page');

function rocket_text_options_page_html() {
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('rocket_text_messages', 'rocket_text_message', __('Settings Saved', 'rocket_text'), 'updated');
    }

    // show error/update messages
    settings_errors('rocket_text_messages');
    ?>
    <div class='wrap'>
        <h1><?php //echo esc_html(get_admin_page_title()); ?></h1>
        <div class="row">
            <div class="col-sm-6">
                <form action="options.php" method="post">
                    <?php
                    // output security fields for the registered setting "rocket_text"
                    settings_fields('rocket_text');
                    // output setting sections and their fields
                    // (sections are registered for "rocket_text", each field is registered to a specific section)
                    do_settings_sections('rocket_text');
                    // output save settings button
                    submit_button('Save Settings');
                    ?>
                </form>
            </div>
        </div>
    </div>
    <?php
}