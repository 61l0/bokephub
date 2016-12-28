<?php
/*
 * Facebook buttons Processor code for WP Socializer Plugin
 * Version : 1.9
 * Author : Aakash Chakravarthy
*/

function wpsr_facebook($args = ''){
	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];

	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'type' => 'like',
		'style' => 'button_count',
		'showfaces' => 1,
		'verb' => 'like',
		'color' => 'light',
		'appid' => '',
		'text' => __('Share on Facebook', 'wpsr'),
		'image' => WPSR_PUBLIC_URL . 'buttons/facebook-bt.png',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	$facebook_processed = "\n<!-- Start WP Socializer Plugin - Facebook Button -->\n";
	
	switch($output){
		// Output ordinary button
		case 'button':
				
			$facebook_processed .=
			'<div class="fb-like" data-href="' . $url . '" data-share="' . ( $type == 'send' ? 'true' : 'false') . '" data-layout="' . $style . '" data-show-faces="' . $showfaces . '" data-action="' . $verb . '" data-colorscheme="' . $color . '"></div>';
			
		break;
		
		// Output Image format
		case 'image':
			$facebook_processed .= '<a href="https://www.facebook.com/sharer.php?u=' . urlencode($url) . '" ' . $params . '><img src="' . $image . '" /></a>';
		break;
		
		// Output Text format
		case 'text':
			$facebook_processed .= '<a href="https://www.facebook.com/sharer.php?u=' . urlencode($url) . '" ' . $params . '>' . $text . '</a>';
		break;
	}
	
	$facebook_processed .= "\n<!-- End WP Socializer Plugin - Facebook Button -->\n";

	return $facebook_processed;
}

function wpsr_facebook_bt($type){

	## Get Facebook Options
	$wpsr_facebook = get_option('wpsr_facebook_data');
	
	## Start Output
	$wpsr_facebook_bt_processed = wpsr_facebook(array(
		'output' => 'button',
		'type' => $type,
		'style' => $wpsr_facebook['btstyle'],
		'showfaces' => $wpsr_facebook['showfaces'],
		'verb' => $wpsr_facebook['verb'],
		'color' => $wpsr_facebook['color'],
		'appid' => $wpsr_facebook['appid']
	));
	## End Output

	return $wpsr_facebook_bt_processed;
}

function wpsr_facebook_rss_bt(){

	## Start Output
	$wpsr_facebook_processed = wpsr_facebook(array(
		'output' => 'text',
		'params' => 'target="_blank"',
	));
	## End Output

	return $wpsr_facebook_processed;
}
?>