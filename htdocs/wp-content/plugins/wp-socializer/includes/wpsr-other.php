<?php
/*
 * Pinterest, Reddit, StumbleUpon and LinkedIn buttons Processor code for WP Socializer Plugin
 * Version : 2.5
 * Since v2.0
 * Author : Aakash Chakravarthy
*/

// StumbleUpon button
function wpsr_stumbleupon_script(){
	return "
<!-- WP Socializer - StumbleUpon Script -->
<script type=\"text/javascript\"> 
(function() { 
   var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true; 
   li.src = window.location.protocol + '//platform.stumbleupon.com/1/widgets.js'; 
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s); 
})(); 
</script>
<!-- WP Socializer - StumbleUpon Script -->\n";
}

function wpsr_stumbleupon($args = ''){
	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];

	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'type' => '1',
		'text' => __('Stumble this' ,'wpsr'),
		'image' => WPSR_PUBLIC_URL . 'buttons/stumbleupon-bt.gif',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	$stumbleupon_processed = "\n<!-- Start WP Socializer Plugin - StumbleUpon Button -->\n";
	
	switch($output){
		// Display the ordinary button
		case 'button':
			$stumbleupon_processed .= '<su:badge layout="' . $type . '"></su:badge>';
		break;
		
		// Display the Image format
		case 'image':
			$stumbleupon_processed .= '<a href="http://www.stumbleupon.com/submit?url=' . urlencode($url) . '&title=' . urlencode($title) . '" ' . $params . '><img src="' . $image . '" alt="Submit to Stumbleupon"  /></a>';
		break;
		
		// Display the Text format
		case 'text':
			$stumbleupon_processed .= '<a href="http://www.stumbleupon.com/submit?url=' . urlencode($url) . '&title=' . urlencode($title) . '" ' . $params . '>' . $text . '</a>';
		break;
	}
	
	$stumbleupon_processed .= "\n<!-- End WP Socializer Plugin - StumbleUpon Button -->\n";
	
	return $stumbleupon_processed;
}

function wpsr_stumbleupon_bt($type){

	## Start Output
	$wpsr_stumbleupon_bt_processed = wpsr_stumbleupon(array(
		'output' => 'button',
		'type' => $type,
	));
	## End Output
	
	return $wpsr_stumbleupon_bt_processed;
}

function wpsr_stumbleupon_rss_bt(){

	## Start Output
	$wpsr_stumbleupon_processed = wpsr_stumbleupon(array(
		'output' => 'text',
		'params' => 'target="_blank"',
	));
	## End Output
	
	return $wpsr_stumbleupon_processed;
}

// Pinterest button
function wpsr_pinterest_script(){
	return "
<!-- WP Socializer - Pinterest Script -->\n" . 
'<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>'.
"\n<!-- WP Socializer - Pinterest Script -->\n";
}

function wpsr_pinterest($args = ''){

	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];
	$def_media = $details['image'];

	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'type' => 'horizontal',
		'media' => $def_media,
		'text' => __('Submit this to', 'wpsr'),
		'image' => '//assets.pinterest.com/images/PinExt.png',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	// fix padding problem since 2.4.9.8
	$padd = "";
	if( $type == 'above' ) $padd = 'style="padding:35px 0 5px"';
	elseif( $type == 'beside' ) $padd = 'style="padding-right:50px"';
	
	$pinterest_processed = "\n<!-- Start WP Socializer Plugin - Pinterest Button -->\n";
	
	switch($output){
		// Display the ordinary button
		case 'button':
			$pinterest_processed .= '<div ' . $padd . '><a href="http://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&amp;media=' . urlencode($media) . '"  data-pin-do="buttonPin" data-pin-config="' . $type . '" ><img border="0" src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" alt="Pinterest" title="Pin It" /></a></div>';
		break;
		
		// Display the Image format
		case 'image':
			$pinterest_processed .= '<a href="http://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&amp;media=' . urlencode($media) . '" ' . $params . '><img src="' . $image . '" alt="Submit to Reddit"  /></a>';
		break;
		
		// Display the Text format
		case 'text':
			$pinterest_processed .= '<a href="http://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&amp;media=' . urlencode($media) . '" ' . $params . '>' . $text . '</a>';
		break;
	}
	
	$pinterest_processed .= "\n<!-- End WP Socializer Plugin - Pinterest Button -->\n";
	
	return $pinterest_processed;
}

function wpsr_pinterest_bt($type){

	## Start Output
	$wpsr_pinterest_processed = wpsr_pinterest(array(
		'output' => 'button',
		'type' => $type,
	));
	## End Output
	
	return $wpsr_pinterest_processed;
}

function wpsr_pinterest_rss_bt(){

	## Start Output
	$wpsr_pinterest_processed = wpsr_pinterest(array(
		'output' => 'text',
		'params' => 'target="_blank"',
	));
	## End Output
	
	return $wpsr_pinterest_processed;
}

// Reddit button
function wpsr_reddit($args = ''){

	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];

	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'type' => '2',
		'text' => __('Reddit this', 'wpsr'),
		'image' => WPSR_PUBLIC_URL . 'buttons/reddit-bt.gif',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	$reddit_processed = "\n<!-- Start WP Socializer Plugin - Reddit Button -->\n";
	
	switch($output){
		// Display the ordinary button
		case 'button':
			$reddit_processed .= '<script type="text/javascript">reddit_url = "' . $url . '";reddit_title = "' . $title . '";reddit_newwindow="1";</script><script type="text/javascript" src="http://www.reddit.com/static/button/button' . $type . '.js"></script>';
		break;
		
		// Display the Image format
		case 'image':
			$reddit_processed .= '<a href="http://www.reddit.com/submit?url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '" ' . $params . '><img src="' . $image . '" alt="Submit to Reddit"  /></a>';
		break;
		
		// Display the Text format
		case 'text':
			$reddit_processed .= '<a href="http://www.reddit.com/submit?url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '" ' . $params . '>' . $text . '</a>';
		break;
	}
	
	$reddit_processed .= "\n<!-- End WP Socializer Plugin - Reddit Button -->\n";
	
	return $reddit_processed;
}

function wpsr_reddit_bt($type){

	## Start Output
	$wpsr_reddit_bt_processed = wpsr_reddit(array(
		'output' => 'button',
		'type' => $type,
	));
	## End Output
	
	return $wpsr_reddit_bt_processed;
}

function wpsr_reddit_rss_bt(){

	## Start Output
	$wpsr_reddit_processed = wpsr_reddit(array(
		'output' => 'text',
		'params' => 'target="_blank"',
	));
	## End Output
	
	return $wpsr_reddit_processed;
}


// LinkedIn button
function wpsr_linkedin_script(){
	// Return the script
	return "
<!-- WP Socializer - LinkedIn Script -->\n".
"<script type=\"text/javascript\">
	(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'http://platform.linkedin.com/in.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>".
"\n<!-- WP Socializer - End LinkedIn Script -->\n";
}

function wpsr_linkedin($args = ''){

	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];

	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'type' => 'right',
		'text' => __('Submit this to ' ,'wpsr'),
		'image' => WPSR_PUBLIC_URL . 'buttons/linkedin-bt.png',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	$linkedin_processed = "\n<!-- Start WP Socializer Plugin - LinkedIn Button -->\n";
	
	switch($output){
		// Display the ordinary button
		case 'button':
			$linkedin_processed .= '<script type="IN/Share" data-url="' . $url . '" data-counter="' . $type . '"></script>';
		break;
		
		// Display the Image format
		case 'image':
			$linkedin_processed .= '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '" ' . $params . '><img src="' . $image . '" alt="Submit to linkedin"  /></a>';
		break;
		
		// Display the Text format
		case 'text':
			$linkedin_processed .= '<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($url) . '&amp;title=' . urlencode($title) . '" ' . $params . '>' . $text . '</a>';
		break;
	}
	
	$linkedin_processed .= "\n<!-- End WP Socializer Plugin - LinkedIn Button -->\n";
	
	return $linkedin_processed;
}

function wpsr_linkedin_bt($type){

	## Start Output
	$wpsr_linkedin_bt_processed = wpsr_linkedin(array(
		'output' => 'button',
		'type' => $type,
	));
	## End Output
	
	return $wpsr_linkedin_bt_processed;
}

function wpsr_linkedin_rss_bt(){

	## Start Output
	$wpsr_linkedin_processed = wpsr_linkedin(array(
		'output' => 'text',
		'params' => 'target="_blank"',
	));
	## End Output
	
	return $wpsr_linkedin_processed;
}
?>