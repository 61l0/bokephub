<?php
/*
 * Digg buttons Processor code for WP Socializer Plugin
 * Version : 1.1
 * Author : Aakash Chakravarthy
*/

function wpsr_digg_script(){
	// Return the script
	return "
<!-- WP Socializer - Digg Script -->\n" .
"<script type=\"text/javascript\">
(function() {
	var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
	s.type = 'text/javascript';
	s.async = true;
	s.src = 'http://widgets.digg.com/buttons.js';
	s1.parentNode.insertBefore(s, s1);
})();
</script>" .
"\n<!-- WP Socializer - End Digg Script -->\n";
}

function wpsr_digg($args = ''){

	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];

	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'type' => 'DiggMedium',
		'text' => __('Digg this' ,'wpsr'),
		'image' => WPSR_PUBLIC_URL . 'buttons/digg-bt.png',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	$digg_processed = "\n<!-- Start WP Socializer Plugin - Digg Button -->\n";
	
	switch($output){
		// Display the ordinary button
		case 'button':
			$digg_processed .= '<a class="DiggThisButton ' . $type . '" href="http://digg.com/submit?url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '" ' . $params . '></a>';
		break;
		
		// Display the Image format
		case 'image':
			$digg_processed .= '<a href="http://digg.com/submit?url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '" ' . $params . '><img src="' . $image . '" alt="Digg!"  /></a>';
		break;
		
		// Display the Text format
		case 'text':
			$digg_processed .= '<a href="http://digg.com/submit?url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '" ' . $params . '>' . $text . '</a>';
		break;
	}
	
	$digg_processed .= "\n<!-- End WP Socializer Plugin - Digg Button -->\n";
	
	return $digg_processed;
}

function wpsr_digg_bt(){
	## Digg Options
	$wpsr_digg = get_option('wpsr_digg_data');

	## Start Output
	$wpsr_digg_bt_processed = wpsr_digg(array(
		'output' => 'button',
		'type' => $wpsr_digg['type'],
	));
	## End Output
	
	return $wpsr_digg_bt_processed;
}

function wpsr_digg_rss_bt(){

	## Start Output
	$wpsr_digg_processed = wpsr_digg(array(
		'output' => 'text',
		'params' => 'target="_blank"',
	));
	## End Output
	
	return $wpsr_digg_processed;
}
?>