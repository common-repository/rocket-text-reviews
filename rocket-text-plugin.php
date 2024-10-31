<?php
/*
Plugin Name: Rocket Text Reviews
Plugin URI: http://rocket-text.com/integrations/wordpress-integrations/
description: Rocket Text Reviews
Version: 1.0
Author: Rocket Text
Author URI: http://rocket-text.com
License: GPL2
*/

defined('ABSPATH') or die('Hey,What are you doing here?');

if ( is_admin() ) {
    // we are in admin mode
    require_once( dirname( __FILE__ ) . '/admin/rocket_text_setting_page.php' );
}else {
    require_once( dirname( __FILE__ ) . '/inc/shortcode_functions.php' );
    require_once( dirname( __FILE__ ) . '/inc/chat_function.php' );
}

global $rocket_text_db_version;
$rocket_text_db_version = '1.0';
define( 'ROCKET_TEXT_PATH', dirname( __FILE__ ) );

add_action('admin_init', 'rocket_text_redirect');

function rocket_text_plugin_activate() {
    if ( ! current_user_can( 'activate_plugins' ) )
        return;

    add_action('wp_enqueue_scripts', 'my_load_scripts');
    update_option('rocket_text_redirect', '');

    flush_rewrite_rules();
}

function rocket_text_redirect() {
    if (get_option('rocket_text_redirect', false)) {
        delete_option('rocket_text_redirect');
        wp_redirect('admin.php?page=rocket_text');
    }
}

function rocket_text_plugin_dependancy_check_activation() {
    global $wp_version;

    $php = '5.3';
    $wp  = '4.7';

    if ( version_compare( PHP_VERSION, $php, '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die(
            '<p>' .
            sprintf(
                __( 'This plugin can not be activated because it requires a PHP version greater than %1$s. Your PHP version can be updated by your hosting company.', 'my_plugin' ),
                $php
            )
            . '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'go back', 'my_plugin' ) . '</a>'
        );
    }

    if ( version_compare( $wp_version, $wp, '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die(
            '<p>' .
            sprintf(
                __( 'This plugin can not be activated because it requires a WordPress version greater than %1$s. Please go to Dashboard &#9656; Updates to gran the latest version of WordPress .', 'my_plugin' ),
                $php
            )
            . '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'go back', 'my_plugin' ) . '</a>'
        );
    }
}

function rocket_text_add_script_to_admin($hook)
{
    if ( 'toplevel_page_rocket_text' != $hook ) {
        return;
    }
     
    // loading css
    wp_register_style( 'bootstrap.min.css', plugin_dir_url( __FILE__ ) . 'admin/css/bootstrap.min.css', false, '4.1.3' );
    wp_enqueue_style( 'bootstrap.min.css' );
     
    // loading js
    wp_register_script( 'bootstrap.min.js', plugin_dir_url( __FILE__ ) . 'admin/js/bootstrap.min.js', array('jquery-core'), false, true );
    wp_enqueue_script( 'bootstrap.min.js' );
}
add_action( 'admin_enqueue_scripts', 'rocket_text_add_script_to_admin' );

function rocket_text_plugin_deactivation() {
    if ( ! current_user_can( 'deactivate_plugins' ) )
        return;

    delete_option('rewrite_rules');
}

register_activation_hook( __FILE__, 'rocket_text_plugin_dependancy_check_activation' );
register_activation_hook(__FILE__, 'rocket_text_plugin_activate');
register_deactivation_hook( __FILE__, 'rocket_text_plugin_deactivation' );