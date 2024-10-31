<?php

// Add Shortcode
function display_rocket_text_reviews( $atts ) {
    
    wp_register_style( 'bootstrap.min.css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', false, '4.1.3' );
    wp_enqueue_style ( 'bootstrap.min.css' );
    wp_register_style( 'googlefont_css', 'https://fonts.googleapis.com/css?family=Lato:300,400,700', false, '1.0.0' );
    wp_enqueue_style ( 'googlefont_css' );
    wp_register_style( 'rocket_text_css', plugin_dir_url( __FILE__) . 'css/rocket-text-styles.css', false, '1.0.0' );
    wp_enqueue_style ( 'rocket_text_css' );
    
    wp_register_script( 'fontawesome_pro.min.js', plugin_dir_url( __FILE__ ) . 'js/fontawesome.js', array('jquery-core'), false, true );
    wp_enqueue_script( 'fontawesome_pro.min.js' );
    wp_register_script( 'popper.min.js', plugin_dir_url( __FILE__ ) . 'js/popper.min.js', array('jquery-core'), false, true );
    wp_enqueue_script( 'popper.min.js' );
    wp_register_script( 'bootstrap.min.js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array('jquery-core'), false, true );
    wp_enqueue_script( 'bootstrap.min.js' );

    $value = shortcode_atts( array(
        'reviewStart' => 1,
        'reviewCount' => 10,
    ), $atts );
	
    $rocket_text_options = get_option('rocket_text_options');
    $rocket_text_key = $rocket_text_options['rocket_text_api_field_keys'];
    
    $url ="https://go.rocket-text.com/api/reviews?token=".$rocket_text_key."&reviewStart=".$value['reviewstart']."&reviewCount=".$value['reviewCount']."";
    
    $response = wp_remote_post( $url, array(
    	'method' => 'GET',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'cookies' => array()
        )
    );
    
    if ( is_wp_error( $response ) ) {
       $error_message = $response->get_error_message();
       echo "Something went wrong: $error_message";
    } 
    
    $body = wp_remote_retrieve_body( $response );
    $response = json_decode( $body, true );
   
    $all_reviews = $response['data']['reviews'];
    $rating_chart = $response['data']['aggregateRating']['chartData']; 
    
    ?>
<style>
/* Individual bars */
.bar-5 {width:<?php echo intval($rating_chart['percentage'][5]) ?>%;height:25px;background-color:#4CAF50;}
.bar-4 {width:<?php echo intval($rating_chart['percentage'][4]) ?>%;height:25px;background-color:#2196F3;}
.bar-3 {width:<?php echo intval($rating_chart['percentage'][3]) ?>%;height:25px;background-color:#00bcd4;}
.bar-2 {width:<?php echo intval($rating_chart['percentage'][2]) ?>%;height:25px;background-color:#ff9800;}
.bar-1 {width:<?php echo intval($rating_chart['percentage'][1]) ?>%;height:25px;background-color:#f44336;}    
</style>    
<div class="container dashboard-container">
    <div class="row py-4 rating_result">
        <div class="col-4 rating_result_left">
            <div class="result_left_box">
                <div class="w-75 m-auto text-center">
                    <h3><?php
                        $total_rating_value = round($response['data']['aggregateRating']['ratingValue']);
                        echo $total_rating_value;
                        ?></h3>
                    <div>
                        <fieldset class="rate feedback_rating m-0">
                            <input type="radio" id="rating_total_5" name="total_Rating_5"
                                   value="5" <?php if ($total_rating_value == "5") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?>
                                   disabled="true"/><label
                                    for="rating_total_5"
                                    title="5 stars"></label>
                            <input type="radio" id="rating_total_4" name="total_Rating_4"
                                   value="4" <?php if ($total_rating_value == "4") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?>
                                   disabled="true"/><label
                                    for="rating_total_4"
                                    title="4 stars"></label>
                            <input type="radio" id="rating_total_3" name="total_Rating_3"
                                   value="3" <?php if ($total_rating_value == "3") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?>
                                   disabled="true"/><label
                                    for="rating_total_3"
                                    title="3 stars"></label>
                            <input type="radio" id="rating_total_2" name="total_Rating_2"
                                   value="2" <?php if ($total_rating_value == "2") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?>
                                   disabled="true"/><label
                                    for="rating_total_2"
                                    title="2 star"></label>
                            <input type="radio" id="rating_total_1" name="total_Rating_1"
                                   value="1" <?php if ($total_rating_value == "1") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?>
                                   disabled="true"/><label
                                    for="rating_total_1"
                                    title="1 star"></label>
                        </fieldset>
                    </div>
                    <p><?php echo $response['data']['aggregateRating']['reviewCount'] ?> Total</p>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="star_rating_result">

                <div class="bar_row">
                    <div class="side left">
                        <div class="text-right mr-2"><i class="fa fa-star"></i> 5</div>
                    </div>
                    <div class="middle">
                        <div class="bar-container">
                            <div class="bar-5"></div>
                            <p class="rate_bar_num"><?php echo $rating_chart['count'][5] ?></p>
                        </div>
                    </div>
                    <div class="side right">
                        <div class="d-none"><?php echo $rating_chart['count'][5] ?></div>
                    </div>
                </div>

                <div class="bar_row">
                    <div class="side left">
                        <div class="text-right mr-2"><i class="fa fa-star"></i> 4</div>
                    </div>
                    <div class="middle">
                        <div class="bar-container">
                            <div class="bar-4"></div>
                            <p class="rate_bar_num"><?php echo $rating_chart['count'][4] ?></p>
                        </div>
                    </div>
                    <div class="side right">
                        <div class="d-none"><?php echo $rating_chart['count'][4] ?></div>
                    </div>
                </div>

                <div class="bar_row">
                    <div class="side left">
                        <div class="text-right mr-2"><i class="fa fa-star"></i> 3</div>
                    </div>
                    <div class="middle">
                        <div class="bar-container">
                            <div class="bar-3"></div>
                            <p class="rate_bar_num"><?php echo $rating_chart['count'][3] ?></p>
                        </div>
                    </div>
                    <div class="side right">
                        <div class="d-none"><?php echo $rating_chart['count'][3] ?></div>
                    </div>
                </div>

                <div class="bar_row">
                    <div class="side left">
                        <div class="text-right mr-2"><i class="fa fa-star"></i> 2</div>
                    </div>
                    <div class="middle left">
                        <div class="bar-container">
                            <div class="bar-2"></div>
                            <p class="rate_bar_num"><?php echo $rating_chart['count'][2] ?></p>
                        </div>
                    </div>
                    <div class="side right">
                        <div class="d-none"><?php echo $rating_chart['count'][2] ?></div>
                    </div>
                </div>

                <div class="bar_row">
                    <div class="side left">
                        <div class="text-right mr-2"><i class="fa fa-star"></i> 1</div>
                    </div>
                    <div class="middle">
                        <div class="bar-container">
                            <div class="bar-1"></div>
                            <p class="rate_bar_num"><?php echo $rating_chart['count'][1] ?></p>
                        </div>
                    </div>
                    <div class="side right">
                        <div class="d-none"><?php echo $rating_chart['count'][1] ?></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="row">

        <?php foreach ($all_reviews as $key => $review) { ?>
            <div class="col-sm-4 d-flex my-2">
                <div class="card h-100 w-100">
                    <div class="card-header text-center">
                        <p class="mb-0">Overall Experienece <?php echo $review['reviewRating']['overallExperience']; ?>
                            /5</p>
                        <fieldset class="rate feedback_rating mt-2 mb-2">
                            <input type="radio" id="q1_rating_<?php echo $key ?>_5" name="q1_Rating<?php echo $key ?>"
                                   value="5" <?php if ($review['reviewRating']['overallExperience'] == "5") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?> disabled="true"/><label
                                    for="q1_rating_<?php echo $key; ?>_5"
                                    title="5 stars"></label>
                            <input type="radio" id="q1_rating_<?php echo $key; ?>_4" name="q1_Rating<?php echo $key; ?>"
                                   value="4" <?php if ($review['reviewRating']['overallExperience'] == "4") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?> disabled="true"/> <label
                                    for="q1_rating_<?php echo $key; ?>_4"
                                    title="4 stars"></label>
                            <input type="radio" id="q1_rating_<?php echo $key; ?>_3" name="q1_Rating<?php echo $key; ?>"
                                   value="3" <?php if ($review['reviewRating']['overallExperience'] == "3") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?> disabled="true"/><label
                                    for="q1_rating_<?php echo $key; ?>_3"
                                    title="3 stars"></label>
                            <input type="radio" id="q1_rating_<?php echo $key ?>_2" name="q1_Rating<?php echo $key ?>"
                                   value="2" <?php if ($review['reviewRating']['overallExperience'] == "2") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?> disabled="true"/><label
                                    for="q1_rating_<?php echo $key ?>_2"
                                    title="2 star"></label>
                            <input type="radio" id="q1_rating_<?php echo $key ?>_1" name="q1_Rating<?php echo $key ?>"
                                   value="1" <?php if ($review['reviewRating']['overallExperience'] == "1") {
                                echo "checked";
                            } else {
                                echo "";
                            } ?> disabled="true"/><label
                                    for="q1_rating_<?php echo $key ?>_1"
                                    title="1 star"></label>
                        </fieldset>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $review['author']; ?></h4>
                        <p class="card-text"> <?php echo $review['name']; ?></p>
                        <p><?php echo $review['description']; ?></p>
                        <p><i class="fa fa-calendar"></i> <?php echo date('m/d/y',
                                strtotime($review['datePublished'])); ?></p>
                        <div class="row rate_feedback_rating">
                            <div class="col-12">
                                <div class="d-inline-flex rate_text">
                                    <fieldset class="rate feedback_rating m-0">
                                        <input type="radio" id="rating_<?php echo $key ?>_5"
                                               name="Rating<?php echo $key ?>"
                                               value="5"
                                            <?php if ($review['reviewRating']['qualityOfService'] == "5") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="rating_<?php echo $key ?>_5"
                                                title="5 stars"></label>
                                        <input type="radio" id="rating_<?php $key ?>_4" name="Rating<?php echo $key ?>"
                                               value="4"
                                            <?php if ($review['reviewRating']['qualityOfService'] == "4") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="rating_<?php echo $key ?>_4"
                                                title="4 stars"></label>
                                        <input type="radio" id="rating_<?php echo $key ?>_3"
                                               name="Rating<?php echo $key ?>"
                                               value="3"
                                            <?php if ($review['reviewRating']['qualityOfService'] == "3") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="rating_<?php echo $key ?>_3"
                                                title="3 stars"></label>
                                        <input type="radio" id="rating_<?php echo $key ?>_2"
                                               name="Rating<?php echo $key ?>"
                                               value="2"
                                            <?php if ($review['reviewRating']['qualityOfService'] == "2") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="rating_<?php echo $key ?>_2"
                                                title="2 star"></label>
                                        <input type="radio" id="rating_<?php echo $key ?>_1"
                                               name="Rating<?php echo $key ?>"
                                               value="1"
                                            <?php if ($review['reviewRating']['qualityOfService'] == "1") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="rating_<?php echo $key ?>_1"
                                                title="1 star"></label>
                                    </fieldset>
                                    <span class="m-0 ml-2">Service</span>
                                </div>

                            </div>
                        </div>
                        <div class="row rate_feedback_rating">
                            <div class="col-12">
                                <div class="d-inline-flex rate_text">
                                    <fieldset class="rate feedback_rating m-0">
                                        <input type="radio" id="q3_rating_<?php echo $key ?>_5"
                                               name="q3_Rating<?php echo $key ?>"
                                               value="5"
                                            <?php if ($review['reviewRating']['serviceProvider'] == "5") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q3_rating_<?php echo $key ?>_5"
                                                title="5 stars"></label>
                                        <input type="radio" id="q3_rating_<?php echo $key ?>_4"
                                               name="q3_Rating<?php echo $key ?>"
                                               value="4"
                                            <?php if ($review['reviewRating']['serviceProvider'] == "4") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q3_rating_<?php echo $key ?>_4"
                                                title="4 stars"></label>
                                        <input type="radio" id="q3_rating_<?php echo $key ?>_3"
                                               name="q3_Rating<?php echo $key ?>"
                                               value="3"
                                            <?php if ($review['reviewRating']['serviceProvider'] == "3") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q3_rating_<?php echo $key ?>_3"
                                                title="3 stars"></label>
                                        <input type="radio" id="q3_rating_<?php echo $key ?>_2"
                                               name="q3_Rating<?php echo $key ?>"
                                               value="2"
                                            <?php if ($review['reviewRating']['serviceProvider'] == "2") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q3_rating_<?php echo $key ?>_2"
                                                title="2 star"></label>
                                        <input type="radio" id="q3_rating_<?php echo $key ?>_1"
                                               name="q3_Rating<?php echo $key ?>"
                                               value="1"
                                            <?php if ($review['reviewRating']['serviceProvider'] == "1") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q3_rating_<?php echo $key ?>_1"
                                                title="1 star"></label>
                                    </fieldset>
                                    <span class="m-0 ml-2">Experience</span>
                                </div>
                            </div>
                        </div>
                        <div class="row rate_feedback_rating">
                            <div class="col-12">
                                <div class="d-inline-flex rate_text">
                                    <fieldset class="rate feedback_rating m-0">
                                        <input type="radio" id="q4_rating_<?php echo $key ?>_5"
                                               name="q4_Rating<?php echo $key ?>"
                                               value="5"
                                            <?php if ($review['reviewRating']['comfortNeeds'] == "5") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q4_rating_<?php echo $key ?>_5"
                                                title="5 stars"></label>
                                        <input type="radio" id="q4_rating_<?php echo $key ?>_4"
                                               name="q4_Rating<?php echo $key ?>"
                                               value="4"
                                            <?php if ($review['reviewRating']['comfortNeeds'] == "4") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q4_rating_<?php echo $key ?>_4"
                                                title="4 stars"></label>
                                        <input type="radio" id="q4_rating_<?php echo $key ?>_3"
                                               name="q4_Rating<?php echo $key ?>"
                                               value="3"
                                            <?php if ($review['reviewRating']['comfortNeeds'] == "3") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q4_rating_<?php echo $key ?>_3"
                                                title="3 stars"></label>
                                        <input type="radio" id="q4_rating_<?php echo $key ?>_2"
                                               name="q4_Rating<?php echo $key ?>"
                                               value="2"
                                            <?php if ($review['reviewRating']['comfortNeeds'] == "2") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q4_rating_<?php echo $key ?>_2"
                                                title="2 star"></label>
                                        <input type="radio" id="q4_rating_<?php echo $key ?>_1"
                                               name="q4_Rating<?php echo $key ?>"
                                               value="1"
                                            <?php if ($review['reviewRating']['comfortNeeds'] == "1") {
                                                echo "checked";
                                            } else {
                                                echo "";
                                            } ?>
                                               disabled="true"/><label
                                                for="q4_rating_<?php echo $key ?>_1"
                                                title="1 star"></label>
                                    </fieldset>
                                    <span class="m-0 ml-2">Quality</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php }

add_shortcode( 'rocket-text-reviews', 'display_rocket_text_reviews' );
    