<?php
/*
 * Shortcodes for WP Socializer plugin
 * Version : 2.5
 * Author : Aakash Chakravarthy
 * Since : 2.0
 */
 
## Social buttons Shortcode
function wpsr_socialbts_shortcode($atts){
	return wpsr_socialbts($atts);
}
add_shortcode('wpsr_socialbts', 'wpsr_socialbts_shortcode');

## Addthis button Shortcode
function wpsr_addthis_shortcode($atts){
	return wpsr_addthis($atts) . wpsr_addthis_config() . wpsr_addthis_script();
}
add_shortcode('wpsr_addthis', 'wpsr_addthis_shortcode');

## Sharethis button Shortcode
function wpsr_sharethis_shortcode($atts){
	return wpsr_sharethis($atts) . wpsr_sharethis_config() . wpsr_sharethis_script();
}
add_shortcode('wpsr_sharethis', 'wpsr_sharethis_shortcode');

## Retweet button Shortcode
function wpsr_retweet_shortcode($atts){
	$wpsr_retweet = get_option('wpsr_retweet_data');
	
	if($atts['service'] == 'twitter'  || $atts['service'] == ''){
		$script = ($atts['script'] == "0") ? '' : wpsr_retweet_twitter_script();
	}elseif($atts['service'] == 'topsy'){
		$script = ($atts['script'] == "0") ? '' : wpsr_retweet_topsy_script();
	}elseif($atts['service'] == 'tweetmeme' || $atts['service'] == 'retweet'){
		$script = '';
	}
	
	$atts['username'] = $wpsr_retweet['username']; // Included @username for retweet buttons v2.4.6
	return wpsr_retweet($atts) . $script;
}
add_shortcode('wpsr_retweet', 'wpsr_retweet_shortcode');

## Google Buzz button Shortcode - Removed since v2.3
function wpsr_buzz_shortcode($atts){
	return '';
}
add_shortcode('wpsr_buzz', 'wpsr_buzz_shortcode');

## Google Plusone button Shortcode
function wpsr_plusone_shortcode($atts){
	$script = ($atts['script'] == "0") ? '' : wpsr_plusone_script();
	return wpsr_plusone($atts) . $script;
}
add_shortcode('wpsr_plusone', 'wpsr_plusone_shortcode');

## Digg button Shortcode
function wpsr_digg_shortcode($atts){
	$script = ($atts['script'] == "0") ? '' : wpsr_digg_script();
	return wpsr_digg($atts) . $script;
}
add_shortcode('wpsr_digg', 'wpsr_digg_shortcode');

## Facebook Shortcode
function wpsr_facebook_shortcode($atts){
	return wpsr_facebook($atts);
}
add_shortcode('wpsr_facebook', 'wpsr_facebook_shortcode');

## StumbleUpon Shortcode
function wpsr_stumbleupon_shortcode($atts){
	$script = ($atts['script'] == "0") ? '' : wpsr_stumbleupon_script();
	return wpsr_stumbleupon($atts) . $script;
}
add_shortcode('wpsr_stumbleupon', 'wpsr_stumbleupon_shortcode');

## Reddit Shortcode
function wpsr_reddit_shortcode($atts){
	return wpsr_reddit($atts);
}
add_shortcode('wpsr_reddit', 'wpsr_reddit_shortcode');

## LinkedIn Shortcode - since v2.3
function wpsr_linkedin_shortcode($atts){
	$script = ($atts['script'] == "0") ? '' : wpsr_linkedin_script();
	return wpsr_linkedin($atts) . $script;
}
add_shortcode('wpsr_linkedin', 'wpsr_linkedin_shortcode');

## Pinterest Shortcode - since v2.4
function wpsr_pinterest_shortcode($atts){
	$script = ($atts['script'] == "0") ? '' : wpsr_pinterest_script();
	return wpsr_pinterest($atts) . $script;
}
add_shortcode('wpsr_pinterest', 'wpsr_pinterest_shortcode');

## Comments Shortcode - Floating sharebar - since v2.4.4 (Beta)
function wpsr_commentsbt_shortcode($atts){
	return wpsr_floatingbts_commentbt($atts);
}
add_shortcode('wpsr_commentsbt', 'wpsr_commentsbt_shortcode');
?>