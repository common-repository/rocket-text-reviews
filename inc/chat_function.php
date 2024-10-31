<?php

$rocket_text_chat_options = get_option('rocket_text_enable_chat');

if( $rocket_text_chat_options['rocket_text_enable_chat'] == '1' ) {

//Add Chat Widget
    add_action( 'wp_footer', 'display_rocket_text_chat_widget' );
    function display_rocket_text_chat_widget() {
    	
        $rocket_text_options = get_option('rocket_text_options');
        $rocket_text_key = $rocket_text_options['rocket_text_api_field_keys']; ?>
    
        <script id="chatBT" chatKey="<?php echo $rocket_text_key; ?>" src="https://go.rocket-text.com/js/communication.js" type="text/javascript"></script>
    <?php }
    
}    
    