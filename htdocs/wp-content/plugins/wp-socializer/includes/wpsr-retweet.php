<?php
/*
 * Retweet buttons Processor code for WP Socializer Plugin
 * Version : 2.5
 * Author : Aakash Chakravarthy
*/

function wpsr_retweet_topsy_script(){
	// Return the script
	return "\n<!-- WP Socializer - Topsy Script -->\n" .
	'<script type="text/javascript" src="http://cdn.topsy.com/topsy.js?init=topsyWidgetCreator"></script>' .
	"\n<!-- WP Socializer - End Topsy Script -->\n";
}

function wpsr_retweet_twitter_script(){
	// Return the script
	return "\n<!-- WP Socializer - Twitter Script -->\n" .
	'<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>' .
	"\n<!-- WP Socializer - End Twitter Script -->\n";
}

function wpsr_retweet($args = ''){
	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];
	
	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'username' => '',
		'service' => 'twitter',
		'type' => 'compact',
		'topsytheme' => 'blue',
		'twitter_recacc' => '',
		'twitter_lang' => 'en',
		'text' => __('Retweet this' ,'wpsr'),
		'image' => WPSR_PUBLIC_URL . 'buttons/retweet-bt.png',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	$pid = $post->ID;
	$purl = home_url() . '/?p=' . $pid;
	//$title = trim(str_replace(array( '&', '#', '&#8211;' ), array('', '', '-'), $title)); // revised since v2.4.9.5
	
	## Start Output
	$retweet_processed = "\n<!-- Start WP Socializer Plugin - Retweet Button -->\n";
	
	switch($output){
		// Display the buttons
		case 'button':
			switch($service){
				// Twitter button processing code
				case 'twitter':
				
					switch($type){
						case 'normal': $type = 'vertical'; break;
						case 'compact': $type = 'horizontal'; break;
						case 'nocount': $type = 'none'; break;
					}
					
					$user = ($username == '') ? '' : "data-via='$username'";
					$recacc = ($twitter_recacc == '') ? '' : "data-related='$twitter_recacc'";
					
					$retweet_processed .= 
					'<a href="http://twitter.com/share" class="twitter-share-button" data-count="' . $type . '" ' . $user . ' data-lang="' . $twitter_lang . '" ' . $recacc . ' data-url="' . $url . '" data-text="' . $title . ' - "></a>';

				break;
				
				// Tweetmeme processing code
				case "tweetmeme" :

					$retweet_processed .= 
					'<script type="text/javascript">' . "\n" . "<!--\n" . 
						'tweetmeme_url = "' . $url . '"; ' . 
						'tweetmeme_style = "' . $type . '"; ' . 
						'tweetmeme_source = "' . $username . '"; ' . "\n" . "\n-->" . 
					'</script>' . "\n" . 
					'<script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>';

				break;
				
				// Topsy processing code
				case "topsy" :
		
					if($type == 'normal'){
						$type = "big";
					}else{
						$type = '';
					}
					
					$retweet_processed .= 
					'<div class="topsy_widget_data"><!--{' .  
						'"url": "' . $url . '", ' . 
						'"title": "'. $title . '", ' . 
						'"style": "'. $type . '", ' . 
						'"nick": "'. $username . '", ' . 
						'"theme": "'. $topsytheme . '", ' . 
					'}--></div>';

				break;
			}
			
		break;
			
		// Display the image format
		case 'image':
			$retweet_processed .= '<a href="http://twitter.com/?status=RT @' . $username . ' ' . $title .' ' . $purl .'" ' . $params . '><img src="' . $image . '" alt="Retweet" /></a>';
		break;
		
		// Display the text format
		case 'text':
			$retweet_processed .= '<a href="http://twitter.com/?status=RT @' . $username . ' ' . $title .' ' . $purl .'" ' . $params . '>' . $text . '</a>';
		break;
	}
	
	$retweet_processed .= "\n<!-- End WP Socializer Plugin - Retweet Button -->\n";
	
	return $retweet_processed;
}

function wpsr_retweet_bt(){
	
	global $post;
	
	# Get Retweet Button Options
	$wpsr_retweet = get_option('wpsr_retweet_data');
	
	## Start Output
	$wpsr_retweet_processed = wpsr_retweet(array(
		'output' => 'button',
		'type' => $wpsr_retweet['type'],
		'service' => $wpsr_retweet['service'],
		'username' => $wpsr_retweet['username'],
		'topsytheme' => $wpsr_retweet['topsytheme'],
		'twitter_recacc' => $wpsr_retweet['twitter_recacc'],
		'twitter_lang' => $wpsr_retweet['twitter_lang'],
	));
	## End Output
	
	return $wpsr_retweet_processed;
}

function wpsr_retweet_rss_bt(){

	## Start Output
	$wpsr_retweet_processed = wpsr_retweet(array(
		'output' => 'text',
		'params' => 'target="_blank"',
	));
	## End Output
	
	return $wpsr_retweet_processed;
}
?>