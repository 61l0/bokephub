<?php
/*
 * Social buttons Processor code for WP Socializer Plugin
 * Version : 4.9
 * Author : Aakash Chakravarthy
*/

function wpsr_socialbts_script(){
	echo "\n<!-- Start WP Socializer | Social Buttons CSS File -->\n";
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . WPSR_PUBLIC_URL . 'css/wp-socializer-buttons-css.css?v=' . WPSR_VERSION . '" />';
	echo "\n<!-- End WP Socializer | Social Buttons CSS File -->\n";
}

function wpsr_addtofavorites_script(){
	echo "\n<!-- Start WP Socializer | Bookmark File -->\n";
	echo '<script type="text/javascript" src="' . WPSR_PUBLIC_URL . 'js/wp-socializer-bookmark-js.js?v=' . WPSR_VERSION . '"></script>';
	echo "\n<!-- End WP Socializer | Bookmark CSS File -->\n";
}

function wpsr_socialbts_used($type){
	## Get template data
	$wpsr_template1 = get_option('wpsr_template1_data');
	$wpsr_template2 = get_option('wpsr_template2_data');
	
	$wpsr_template_content = $wpsr_template1['content'] . $wpsr_template2['content'];
	
	$is_socialbts_used_16px = strpos($wpsr_template_content, '{social-bts-16px}');
	$is_socialbts_used_32px = strpos($wpsr_template_content, '{social-bts-32px}');
	
	switch($type){
		case '16px' :
			if ($is_socialbts_used_16px === false) {
				return 0;
			} else {
				return 1;
			}
		break;
			
		case '32px' :
			if ($is_socialbts_used_32px === false) {
				return 0;
			} else {
				return 1;
			}
		break;
	}
}

function wpsr_addtofavorites_bt_used(){
	## Get Social Button options
	$wpsr_socialbt = get_option('wpsr_socialbt_data');

	$in_selected_16px = in_array("addtofavorites", explode(',', $wpsr_socialbt['selected16px']));
	$in_selected_32px = in_array("addtofavorites", explode(',', $wpsr_socialbt['selected32px']));
	
	if (wpsr_socialbts_used('16px') && $in_selected_16px) {
		return 1;
	} elseif (wpsr_socialbts_used('32px') && $in_selected_32px){
		return 1;
	}
	
}

function get_sprite_coord($to_find_Key = '', $in_Array = '', $px = '16px'){
	$pixel = ($px == '16px') ? 16 : 32;

	$index = 0;
	$pixel = $pixel + 1;
	
	switch ($pixel){
		case '17' :
		// Start the counter
		foreach ($in_Array as $key => $value) {
			if($key == $to_find_Key){
				return $index * $pixel ;
			}
			$index++;
		}
		
		break;
		
		case '33':
		// Start the counter
		foreach ($in_Array as $key => $value) {
			if(isset($value['support32px'])){
				if($key == $to_find_Key){
					return $index * $pixel ;
				}
				$index++;
			}
		}
		break;
	} // End switch
	
}

function wpsr_get_shorturl($url){
	global $post;
	## Get the settings
	$wpsr_settings = get_option('wpsr_settings_data');
	
	$username = $wpsr_settings['bitlyusername'];
	$apikey = $wpsr_settings['bitlyapi'];
	
	if(empty($username) || empty($apikey)){
		if(in_the_loop()){
			//return urlencode(get_bloginfo('url') . '?p=' . $post->ID);
			return urlencode($url); // changed since v2.4.6 as per user requests http://bit.ly/V6zsH7
		}else{
			return urlencode($url);
		}
	}else{
		$url = 'http://api.bit.ly/v3/shorten?login=' . $username . '&apiKey=' . $apikey . '&longUrl=' . urlencode($url) . '&format=txt';
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

function wpsr_socialbts_processlist($args = ''){

	global $post, $wpsr_socialsites_list;
	
	$defaults = array(
		'pUrl' => '',
		'pTitle' => '',
		'pExcerpt' => '',
		'services' => '',
		'btType' => '',
		'pixel' => '16px',
		'before' => "\n <li>",
		'after' => "</li> \n",
		'textBefore' => '&bull; ',
		'textAfter' => '',
		'sprites' => 1,
		'label' => 1,
		'target' => 1,
		'nofollow' => 1,
		'imgpath' => '',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	## Get retweet options and settings for Twitter Username and RSS URL
	$wpsr_retweet = get_option('wpsr_retweet_data');
	$wpsr_settings = get_option('wpsr_settings_data');
	
	## Get Page details
	$details = wpsr_get_post_details();
	$url = urlencode($pUrl);
	$deUrl = $pUrl;
	
	$title = urlencode( html_entity_decode( trim( $pTitle ) ) ); // REVISED IN 2.4.9.8
	$deTitle = trim($pTitle);

	$excerpt = trim(urlencode($pExcerpt));
	$deExcerpt = htmlspecialchars($pExcerpt);
	
	$rss = (empty($wpsr_settings['rssurl'])) ? get_bloginfo('rss_url') : $wpsr_settings['rssurl'];
	$blogname = urlencode(get_bloginfo('name') . ' - ' . get_bloginfo('description'));
	$email = get_bloginfo('admin_email');
	$trUsername = (empty($wpsr_retweet['username'])) ? '' : '@' . $wpsr_retweet['username'];
	$sUrl = wpsr_get_shorturl($deUrl);
	$image = urlencode($details['image']);
	
	$replace_with = array(
		$url, $title, $rss, 
		$blogname, $excerpt, $deUrl,
		$deTitle, $deExcerpt, $email, 
		$trUsername, $sUrl, $image
	);
	
	$to_be_replaced = array(
		'{url}', '{title}', '{rss-url}', 
		'{blogname}', '{excerpt}', '{de-url}',
		'{de-title}', '{de-excerpt}', '{email}', 
		'{twitter-username}', '{s-url}', '{image}'
	);

	$srvcsSplit = explode(',', $services);
	$spriteImage = WPSR_SOCIALBT_IMGPATH . "wp-socializer-sprite-$pixel.png?v1";
	$spriteMaskImage = WPSR_SOCIALBT_IMGPATH . "wp-socializer-sprite-mask-$pixel.gif";
	$srvcsCount = count($srvcsSplit);
	$socialbts_list = '';
	
	for($i = 0; $i < $srvcsCount; $i++){
		$sitename = $srvcsSplit[$i];
		$finalTitle = $sitename;
		$finalName = $wpsr_socialsites_list[$sitename]['name'];
		$finalTitle = trim(str_replace($to_be_replaced, $replace_with, $wpsr_socialsites_list[$sitename]['titleText']));
		$finalUrl = str_replace($to_be_replaced, $replace_with, $wpsr_socialsites_list[$sitename]['url']);
		$finalIcon = $imgpath . $wpsr_socialsites_list[$sitename]['icon'];
		$spritesYCoord = get_sprite_coord($sitename, $wpsr_socialsites_list, $pixel);
		$finalSprites = '0px -' . $spritesYCoord . 'px';
		$finalTarget = ($target == 1) ? ' target="_blank"' : '';
		$finalNofollow = ($nofollow == 1) ? ' rel="nofollow"' : '';
		
		$socialbts_list .= $before;
		
		// Checking the output type
		if($btType != 'text'){
		
			// Check whether label is enabled
			if($label == 1){
				$finalLabel = '<span class="wp-socializer-label"><a href="' . $finalUrl . '" title="' . $finalTitle . '"' . $finalTarget . $finalNofollow .  '>' . $finalName . '</a></span>';
			}else{
				$finalLabel = '';
			}
			
			// Check whether sprites is enabled
			if($sprites == 1){
				$socialbts_list .= 
					'<a href="' . $finalUrl . '" title="' . $finalTitle . '"' . $finalTarget . $finalNofollow .  $params . '>' .
					'<img src="' . $spriteMaskImage . '" alt="' . $finalName . '" style="width:' . $pixel . '; height:' . $pixel . '; background: transparent url(' . $spriteImage . ') no-repeat; background-position:' . $finalSprites . '; border:0;"/>' .
					"</a>" . $finalLabel ;		
			}else{
				$socialbts_list .= 
					'<a href="' . $finalUrl . '" title="' . $finalTitle . '"' . $finalTarget . $finalNofollow .  $params . '>' .
					'<img src="' . $finalIcon . '" alt="' . $finalName . '" border="0"/>' .
					"</a>" . $finalLabel ;
			}
		
		}else{
			$socialbts_list .= 
				$textBefore . '<a href="' . $finalUrl . '" title="' . $finalTitle . '"' . $finalTarget . $finalNofollow .  $params . '>' .
				$finalName . "</a>" . $textAfter ;
		}
		
		$socialbts_list .= $after;
	}
	
	return $socialbts_list;
}

function wpsr_socialbts($args = ''){
	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];
	$def_excerpt = $details['excerpt'];

	$defaults = array(
		'output' => 'image',
 		'url' => $def_url,
 		'title' => $def_title,
		'excerpt' => $def_excerpt,
		'type' => '16px',
		'target' => 1,
		'nofollow' => 1, // Since 2.3
		'effect' => 'opacity',
		'label' => 0,
		'columns' => 'no',
		'services' => 'facebook,twitter,delicious,digg,googlebuzz,stumbleupon,addtofavorites,email',
		'sprites' => 1,
		'imgpath16px' => WPSR_SOCIALBT_IMGPATH . '16/',
		'imgpath32px' => WPSR_SOCIALBT_IMGPATH . '32/',
		'before' => '',
		'after' => '',
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	$socialbt_processed = ''; // Define empty var v2.4.9.6
	
	if($output != 'singles'){
		$socialbt_processed = "\n<!-- Start WP Socializer - Social Buttons - Output -->\n";
	}
	
	switch($output){
		// Output the ordinary buttons
		case 'image':
		
			$socialbt_processed .= '<div class="wp-socializer ' . $type . '">' . "\n" ;
			$socialbt_processed .= '<ul class="wp-socializer-' . $effect . ' columns-' . $columns . '">';
			
			switch($type){
				case '16px':
					$socialbt_processed .= wpsr_socialbts_processlist(array(
						'pUrl' => $url,
						'pTitle' => $title,
						'pExcerpt' => $excerpt,
						'btType' => 'image',
						'pixel' => '16px',
						'services' => $services,
						'sprites' => $sprites,
						'label' => $label,
						'target' => $target,
						'nofollow' => $nofollow,
						'imgpath' => $imgpath16px,
					));
				break;
				
				case '32px':
					$socialbt_processed .= wpsr_socialbts_processlist(array(
						'pUrl' => $url,
						'pTitle' => $title,
						'pExcerpt' => $excerpt,
						'btType' => 'image',
						'pixel' => '32px',
						'services' => $services,
						'sprites' => $sprites,
						'label' => $label,
						'target' => $target,
						'nofollow' => $nofollow,
						'imgpath' => $imgpath32px,
					));
				break;
			}
			
			$socialbt_processed .= "</ul> \n";
			$socialbt_processed .= '<div class="wp-socializer-clearer"></div>';
			$socialbt_processed .= '</div>';
			
		break;
		
		case 'singles':
			switch($type){
				case '16px':
					$socialbt_processed .= wpsr_socialbts_processlist(array(
						'pUrl' => $url,
						'pTitle' => $title,
						'pExcerpt' => $excerpt,
						'btType' => 'image',
						'pixel' => '16px',
						'services' => $services,
						'sprites' => $sprites,
						'label' => $label,
						'target' => $target,
						'nofollow' => $nofollow,
						'imgpath' => $imgpath16px,
						'after' => '',
						'before' => '',
						'params' => 'class="wp-socializer-single"',
					));
				break;
				
				case '32px':
					$socialbt_processed .= wpsr_socialbts_processlist(array(
						'pUrl' => $url,
						'pTitle' => $title,
						'pExcerpt' => $excerpt,
						'btType' => 'image',
						'pixel' => '32px',
						'services' => $services,
						'sprites' => $sprites,
						'label' => $label,
						'target' => $target,
						'nofollow' => $nofollow,
						'imgpath' => $imgpath32px,
						'after' => '',
						'before' => '',
						'params' => 'class="wp-socializer-single"',
					));
				break;
				
				case 'text':
					$socialbt_processed .= wpsr_socialbts_processlist(array(
						'pUrl' => $url,
						'pTitle' => $title,
						'pExcerpt' => $excerpt,
						'btType' => 'text',
						'services' => $services,
						'sprites' => $sprites,
						'label' => $label,
						'target' => $target,
						'nofollow' => $nofollow,
						'after' => $after,
						'before' => $before,
						'textBefore' => '',
						'textAfter' => ' ',
						'params' => 'class="wp-socializer-single-text"',
					));
				break;
			}
		break;
		
		// Output the text links
		case 'text':
		
			$socialbt_processed .= '<div class="wp-socializer text">' . "\n" ;
			$socialbt_processed .= '<ul class="wp-socializer-' . $effect . ' columns-' . $columns . '">';
		
			$socialbt_processed .= wpsr_socialbts_processlist(array(
				'pUrl' => $url,
				'pTitle' => $title,
				'pExcerpt' => $excerpt,
				'btType' => 'text',
				'services' => $services,
				'textBefore' => '&bull; ',
				'textAfter' => ' ',
			));
			
			$socialbt_processed .= "</ul> \n";
			$socialbt_processed .= '<div class="wp-socializer-clearer"></div>';
			$socialbt_processed .= '</div>';
			
		break;
		
		case 'rss':
			$socialbt_processed .= wpsr_socialbts_processlist(array(
				'pUrl' => $url,
				'pTitle' => $title,
				'pExcerpt' => $excerpt,
				'btType' => 'text',
				'services' => $services,
				'textBefore' => ' &bull; ',
				'textAfter' => '',
				'before' => $before,
				'after' => $after,
			));
		break;
	}
	
	if($output != 'singles'){
		$socialbt_processed .= "\n<!-- End WP Socializer - Social Buttons - Output -->\n";
	}
	
	return $socialbt_processed;
}

function wpsr_socialbts_template($type){

	## Get Social Button options
	$wpsr_socialbt = get_option('wpsr_socialbt_data');

	$wpsr_socialbt_imgpath16px = ($wpsr_socialbt['imgpath16px'] != '') ? $wpsr_socialbt['imgpath16px'] : WPSR_SOCIALBT_IMGPATH . '16/';
	$wpsr_socialbt_imgpath32px = ($wpsr_socialbt['imgpath32px'] != '') ? $wpsr_socialbt['imgpath32px'] : WPSR_SOCIALBT_IMGPATH . '32/';
	
	$wpsr_socialbt_processed = wpsr_socialbts(array(
		'output' => 'image',
		'type' => $type,
		'target' => $wpsr_socialbt['target'],
		'nofollow' => $wpsr_socialbt['nofollow'],
		'effect' => $wpsr_socialbt['effect'],
		'label' => $wpsr_socialbt['label'],
		'columns' => $wpsr_socialbt['columns'],
		'services' => $wpsr_socialbt['selected' . $type],
		'sprites' => $wpsr_socialbt['usesprites'],
		'imgpath16px' => $wpsr_socialbt_imgpath16px,
		'imgpath32px' => $wpsr_socialbt_imgpath32px,
	));
	
	return $wpsr_socialbt_processed;
}

function wpsr_socialbts_rss($type){

	## Get Social Button options
	$wpsr_socialbt = get_option('wpsr_socialbt_data');
	
	$wpsr_socialbt_processed = wpsr_socialbts(array(
		'output' => 'rss',
		'target' => $wpsr_socialbt['target'],
		'services' => $wpsr_socialbt['selected' . $type],
	));
	
	return $wpsr_socialbt_processed;
}
?>