<?php
/*
 * Addthis button Processor code for WP Socializer Plugin
 * Version : 2.2
 * Author : Aakash Chakravarthy
*/

function wpsr_addthis_uniqueid(){
    $rand_no  = dechex(mt_rand(0,min(0xffffffff,mt_getrandmax())));
    $current_time = dechex(time());
    $unique_id = 'wp-' . $current_time . str_pad($rand_no, 8, '0', STR_PAD_LEFT);
	
	$wpsr_addthis = get_option('wpsr_addthis_data');
	
	$wpsr_addthis['username'] = $unique_id;
	update_option("wpsr_addthis_data", $wpsr_addthis);
	
    return $unique_id;
}

function wpsr_addthis_config(){
	## Get Addthis Options
	$wpsr_addthis = get_option('wpsr_addthis_data');
	
	$wpsr_addthis_lang = $wpsr_addthis['language'];
	$wpsr_addthis_button = $wpsr_addthis['button'];
	$wpsr_addthis_btbrand = $wpsr_addthis['btbrand'];
	$wpsr_addthis_clickback = $wpsr_addthis['clickback'];
	$wpsr_addthis_btheadclr = $wpsr_addthis['btheadclr'];
	$wpsr_addthis_btheadbgclr = $wpsr_addthis['btheadbgclr'];
	
	# Config settings
	if($wpsr_addthis['username'] == ''){
		$wpsr_addthis_username = 'username: "' . wpsr_addthis_uniqueid() . '"' . ",\n";
	}else{
		$wpsr_addthis_username = 'username: "' . $wpsr_addthis['username'] . '"' . ",\n";
	}
	
	if($wpsr_addthis['language'] != 'lg-share-'){
		$wpsr_addthis_lang = 'en';
	}
	
	if($wpsr_addthis_btbrand != ''){
		$wpsr_addthis_btbrand = 'ui_cobrand: "' . $wpsr_addthis_btbrand .'"' . ",\n";
	}else{
		$wpsr_addthis_btbrand = '';
	}
	
	if($wpsr_addthis_btheadclr != ''){
		$wpsr_addthis_btheadclr = 'ui_header_color: "' . $wpsr_addthis_btheadclr . '"' . ",\n";
	}else{
		$wpsr_addthis_btheadclr = '';
	}
	
	if($wpsr_addthis_btheadbgclr != ''){
		$wpsr_addthis_btheadbgclr = 'ui_header_background: "' . $wpsr_addthis_btheadbgclr . '"' . ",\n";
	}else{
		$wpsr_addthis_btheadbgclr = '';
	}
	
	if($wpsr_addthis_clickback == 1){
		$wpsr_addthis_clickback = 'data_track_clickback: true' . "\n";
	}else{
		$wpsr_addthis_clickback = 'data_track_clickback: false' . "\n";
	}
	
	$wpsr_addthis_config = $wpsr_addthis_username . $wpsr_addthis_btbrand . $wpsr_addthis_btheadclr . $wpsr_addthis_btheadbgclr . $wpsr_addthis_clickback;
	
	$wpsr_addthis_settings  = 
	"\n<script type=\"text/javascript\">\n" .
	"<!--\n" .
	"var addthis_config = { \n" . 
	$wpsr_addthis_config .
	"}\n//-->" .
	"</script>\n";
	
	// Return the script
	return "\n<!-- WP Socializer - AddThis Config -->" .
	$wpsr_addthis_settings . 
	"<!-- WP Socializer - End AddThis Config -->\n";
}

function wpsr_addthis_script(){
	return '<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>';
}

function wpsr_addthis($args = ''){
	global $post;
	
	$details = wpsr_get_post_details();
	$def_url = $details['permalink'];
	$def_title = $details['title'];
	
	## Get Addthis Options
	$wpsr_addthis = get_option('wpsr_addthis_data');

	$defaults = array (
		'output' => 'button',
 		'url' => $def_url,
 		'title' => $def_title,
		'type' => 'button',
		'btstyle' => 'lg-share-',
		'tbstyle' => '16px',
		'tbservices' => 'facebook,twitter,digg,delicious,email,compact',
		'scstyle' => 'normal',
		'username' => $wpsr_addthis['username'],
		'lang' => 'en',
		'text' => __('Share with Addthis' ,'wpsr'),
		'params' => '',
	);
	
	$args = wp_parse_args($args, $defaults);
	extract($args, EXTR_SKIP);
	
	## Adjustments
	$url = ' addthis:url="' . $url .'"' ;
	$title = ' addthis:title="' . $title .'"' ;
	$btwidth = (strpos($btstyle, 'lg') === false) ? '83' : '125';

	$addthis_processed = "\n<!-- Start WP Socializer Plugin - Addthis Button -->\n";
	$inner = '';
	
	switch($output){
		// Output the ordinary button
		case 'button':
			switch($type){
				// Display the toolbar
				case 'toolbar':
				
					$style = ($tbstyle == '16px') ? 'addthis_default_style' : 'addthis_default_style addthis_32x32_style';
					$outer_start = '<div class="addthis_toolbox ' . $style . '"' . $url . $title . '>' . "\n";
					$outer_end = '</div>';
				
					$default_code = '<a class="addthis_button_preferred_1"></a><a class="addthis_button_preferred_2"></a><a class="addthis_button_preferred_3"></a><a class="addthis_button_preferred_4"></a><a class="addthis_button_compact"></a>';
					
					switch($tbstyle){
						case '16px':
							if($tbservices == ''){
								$inner = $default_code;
							}else{
								$tb_16px = explode(',', $tbservices);
								foreach($tb_16px as $tb_16px_srvc){
									$tb_16px_srvc = trim($tb_16px_srvc);
									$inner .= "<a class=\"addthis_button_{$tb_16px_srvc}\"></a>\n";
								}
							}
						break;
							
						case '32px':
							if($tbservices == ''){
								$inner = $default_code;
							}else{
								$tb_32px = explode(',', $tbservices);
								foreach($tb_32px as $tb_32px_srvc){
									$tb_32px_srvc = trim($tb_32px_srvc);
									$inner .= "<a class=\"addthis_button_{$tb_32px_srvc}\"></a>\n";
								}
							}
						break;
					}
					
					$addthis_processed .= $outer_start . $inner . $outer_end;
					
				break;
				
				// Display the sharecount
				case 'sharecount':
				
					$outer_start = "\n" . '<div class="addthis_toolbox addthis_default_style"' . $url . $title . '>' . "\n";
					$outer_end = '</div>' . "\n";
					
					switch($scstyle){
						case 'normal':
							$inner = '<a class="addthis_counter"></a>';
						break;
						
						case 'pill':
							$inner = '<a class="addthis_counter addthis_pill_style"></a>';
						break;
						
						case 'grouped':
							$inner = '<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a><a class="addthis_button_tweet"></a><a class="addthis_counter addthis_pill_style"></a>';
						break;
					}
					
					$addthis_processed .= $outer_start . $inner . $outer_end;
					
				break;
				
				// Display the ordinary button
				case 'button':
				
					$addthis_processed .= 
					'<a class="addthis_button" ' . $params . ' href="http://addthis.com/bookmark.php?v=250&amp;pubid=' . $username .
					'"' . $url . $title . '><img src="http://s7.addthis.com/static/btn/v2/' . $btstyle . $lang . 
					'.gif" width="' . $btwidth . '" height="16" alt="Bookmark and Share" style="border:0"/></a>';
					
				break;
			}
		break;
		
		case 'text':
		
			$addthis_processed .= 
			'<a class="addthis_button" ' . $params . ' href="http://addthis.com/bookmark.php?v=250&amp;pubid=' . $username .
			'"' . $url . $title . '>' . $text . '</a>';
		
		break;
	}
	
	$addthis_processed .= "\n<!-- End WP Socializer Plugin - Addthis Button -->\n";
	
	return $addthis_processed;
}

## Addthis function
function wpsr_addthis_bt($type, $tbstyle = '16px'){

	## Addthis Options
	$wpsr_addthis = get_option('wpsr_addthis_data');

	## Start Output
	$wpsr_addthis_bt_processed = wpsr_addthis(array(
		'output' => 'button',
		'type' => $type,
		'tbstyle' => $tbstyle,
		'scstyle' => $wpsr_addthis['sharecount'],
		'btstyle' => $wpsr_addthis['button'],
		'tbservices' => ($tbstyle == '16px') ? $wpsr_addthis['tb_16pxservices'] : $wpsr_addthis['tb_32pxservices'],
		'lang' => $wpsr_addthis['language'],
	));
	## End Output
	
	return $wpsr_addthis_bt_processed;
}

function wpsr_addthis_rss_bt(){

	## Get the Options
	$wpsr_addthis = get_option('wpsr_addthis_data');
	
	## Start Output
	$wpsr_addthis_processed = wpsr_addthis(array(
		'output' => 'text',
		'type' => 'button',
		'btstyle' => $wpsr_addthis['button'],
		'lang' => $wpsr_addthis['language'],
		'params' => 'target="_blank"',
	));
	## End Output
	
	return $wpsr_addthis_processed;
}
?>