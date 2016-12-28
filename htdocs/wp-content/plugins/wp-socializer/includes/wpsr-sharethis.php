<?php
/*
 * Sharethis buttons Processor code for WP Socializer Plugin
 * Version : 2.6
 * Author : Aakash Chakravarthy
*/

## Generators
function wpsr_sharethis_pubkey(){
	$key = "wp.".sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),mt_rand( 0, 0x0fff ) | 0x4000,mt_rand( 0, 0x3fff ) | 0x8000,mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
	
	## Sharethis options
	$wpsr_sharethis = get_option('wpsr_sharethis_data');
	
	$wpsr_sharethis['pubkey'] = $key;
	update_option("wpsr_sharethis_data", $wpsr_sharethis);
	
	return $key;
}

function wpsr_sharethis_config(){
	## Sharethis options
	$wpsr_sharethis = get_option('wpsr_sharethis_data');
	
	$wpsr_sharethis_pubkey = $wpsr_sharethis['pubkey'];
	$wpsr_sharethis_pubkey = ($wpsr_sharethis_pubkey == '') ? wpsr_sharethis_pubkey() : $wpsr_sharethis_pubkey;
	$wpsr_sharethis_copynshare = $wpsr_sharethis['copynshare'] ? 'true' : 'false';
	
	// Return the script
	return '<!-- WP Socializer - ShareThis Config -->
<script type="text/javascript">
var switchTo5x=false;
var sharethisLoad = function(){
	' . "stLight.options({publisher: '{$wpsr_sharethis_pubkey}', doNotHash: {$wpsr_sharethis_copynshare}, doNotCopy: {$wpsr_sharethis_copynshare}, hashAddressBar: false});" . '
};
if(window.addEventListener) window.addEventListener("load", sharethisLoad, false); 
else if (window.attachEvent) window.attachEvent("onload", sharethisLoad);
</script>
<!-- WP Socializer - End ShareThis Config -->';
}

function wpsr_sharethis_script(){
	return '<script charset="utf-8" type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>';
}

function wpsr_sharethis($args = ''){
	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];
	
	## Sharethis option
	$wpsr_sharethis = get_option('wpsr_sharethis_data');
	
	# Get Retweet Button Options
	$wpsr_retweet = get_option('wpsr_retweet_data');
	
	$defaults = array (
		'output' => 'vcount',
 		'url' => $def_url,
 		'title' => $def_title,
		'services' => 'facebook,twitter,email,sharethis',
		'pubkey' => $wpsr_sharethis['pubkey'],
		'addp' => 1,
		'text' => 'ShareThis',
		'image' => WPSR_PUBLIC_URL . 'buttons/sharethis-bt.png',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	// Preliminary Adjustments
	if($services == ''){
		$output = 'classic';
	}else{
		$services_split = explode(',', $services);
	}
	
	// $title = urlencode($title); (fix v2.1)
	// $url = urlencode($url); (fix v2.4.6)
	$username = $wpsr_retweet['username'];
	
	if($addp == 1){
		$before_st = '<p>';
		$after_st = '</p>';
	}else{
		$before_st = $after_st = '';
	}
	
	$sharethis_processed = "\n<!-- Start WP Socializer Plugin - Sharethis Button -->\n" . $before_st;
	switch($output){
		case 'vcount':
			foreach($services_split as $srvc){
				$srvc = trim($srvc);
				$sharethis_processed .= "<span class='st_{$srvc}_vcount' st_title='{$title}' st_via='{$username}' st_url='{$url}' displayText='share'></span>";
			}
		break;
		
		case 'hcount':
			foreach($services_split as $srvc){
				$srvc = trim($srvc);
				$sharethis_processed .= "<span class='st_{$srvc}_hcount' st_title='{$title}' st_via='{$username}' st_url='{$url}' displayText='share'></span>";
			}
		break;
		
		case 'buttons':
			foreach($services_split as $srvc){
				$srvc = trim($srvc);
				$sharethis_processed .= "<span class='st_{$srvc}_buttons' st_title='{$title}' st_via='{$username}' st_url='{$url}' displayText='share'></span>";
			}
		break;
		
		case 'large':
			foreach($services_split as $srvc){
				$srvc = trim($srvc);
				$sharethis_processed .= "<span class='st_{$srvc}_large' st_title='{$title}' st_via='{$username}' st_url='{$url}' displayText='share'></span>";
			}
		break;
		
		case 'regular':
			foreach($services_split as $srvc){
				$srvc = trim($srvc);
				$sharethis_processed .= "<span class='st_{$srvc}' st_title='{$title}' st_via='{$username}' st_url='{$url}' displayText='share'></span>";
			}
		break;
		
		case 'regular2':
			foreach($services_split as $srvc){
				$srvc = trim($srvc);
				$sharethis_processed .= "<span class='st_{$srvc}' st_title='{$title}' st_via='{$username}' st_url='{$url}'></span>";
			}
		break;
		
		case 'classic':
			$sharethis_processed .= "<span class='st_sharethis' st_title='{$title}' st_via='{$username}' st_url='{$url}' displayText='ShareThis'></span>";
		break;
		
		case 'image':
			$sharethis_processed .= '<a href="http://sharethis.com/item?publisher='
						. $pubkey . '&amp;title='
						. $title . '&amp;url='
						. $url . '" ' . $params . '><img src="' . $image . '" /></a>';
		break;
		
		case 'text':
			$sharethis_processed .= '<a href="http://sharethis.com/item?publisher='
						. $pubkey . '&amp;title='
						. $title . '&amp;url='
						. $url . '" ' . $params . '>' . $text . '</a>';
		break;
	}
	$sharethis_processed .= $after_st . "\n<!-- End WP Socializer Plugin - Sharethis Button -->\n";
	
	return $sharethis_processed;
}

function wpsr_sharethis_bt($type){

	## Sharethis options
	$wpsr_sharethis = get_option('wpsr_sharethis_data');

	## Start Output
	$wpsr_sharethis_processed = wpsr_sharethis(array(
		'output' => $type,
		'services' => isset($wpsr_sharethis[$type . '_order']) ? $wpsr_sharethis[$type . '_order'] : '',
		'addp' => $wpsr_sharethis['addp'],
	));
	## End Output
	
	return $wpsr_sharethis_processed;
}

function wpsr_sharethis_rss_bt(){

	## Start Output
	$wpsr_sharethis_processed = wpsr_sharethis(array(
		'output' => 'text',
	));
	## End Output
	
	return $wpsr_sharethis_processed;
}
?>