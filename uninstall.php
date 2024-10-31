<?php 

	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit;
	}	
	global $wpdb;
	
    $optiontable=$wpdb->prefix . 'options';
    $optionvalue='rocket_text_options';
    $optionDeleteQuery = "DELETE FROM $optiontable WHERE option_name='$optionvalue'";
	$wpdb->query($optionDeleteQuery);

	delete_option("rocket_text_db_version");

	function rocket_text_remove_options_page()
	{
	    remove_menu_page('rocket_text');
	}
	add_action('admin_menu', 'rocket_text_remove_options_page', 99);
?>