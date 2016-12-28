<?php
/*
 * Custom buttons Processor code for WP Socializer Plugin
 * Version : 1.0
 * Author : Aakash Chakravarthy
*/

function wpsr_custom_bt($custom){
	
	global $post;
	
	$permalink 	= urlencode(get_permalink($post->ID));
	$title 	= str_replace('+','%20',urlencode(get_the_title($post->ID)));
	
	$to_be_replaced = array(
		"{url}", "{title}"
	);
	
	$replace_with = array(
		$permalink, $title
	);
	
	## Get Custom options
	$wpsr_custom = get_option('wpsr_custom_data');
	
	$wpsr_custom1 = $wpsr_custom['custom1'];
	$wpsr_custom2 = $wpsr_custom['custom2'];
	
	switch ($custom){
		case 'custom1' :
			return str_replace($to_be_replaced, $replace_with, $wpsr_custom1);		
		break;
		
		case 'custom2':
			return str_replace($to_be_replaced, $replace_with, $wpsr_custom2);	
		break;
	}
	
}
?>