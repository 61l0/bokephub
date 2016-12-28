<?php
/**
 * Plugin Name: Members Restricted Access
 * Plugin URI: http://www.jumptoweb.com
 * Description: This plugin creates shortcodes to restrict the access to Visitors, Members or by role. You can use nested shortcodes.
 * Version: 3.0
 * Author: Manuel Costales
 * Author URI: http://www.manuelcostales.com
 */
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
//function to create a shortcode to check if the user is a visitor
//the shorcode to add in a page is [visitor]...[/visitor]
add_shortcode( 'visitor', 'visitor_check_shortcode_mc' );
function visitor_check_shortcode_mc( $atts, $content = null ) {

	if (is_array($atts) && $atts['msg']) {
		$msg = $atts['msg'];}

	if ( ( !is_user_logged_in() && !is_null( $content ) ) || is_feed() )
		return do_shortcode($content);
	echo $msg;
}

//create a shorcode to members (logged users)[member]...[/member]
add_shortcode( 'member', 'member_check_shortcode_mc' );
function member_check_shortcode_mc( $atts, $content = null ) {
	
	if (is_array($atts) && $atts['msg']) {
		$msg = $atts['msg'];}

	if ( is_user_logged_in() && !is_null( $content ) && !is_feed() )
		return do_shortcode($content);
	echo $msg;
}

//shorcode to check which member is[showifrole]...[/showifrole]
add_shortcode( 'showifrole', 'showifrole_is_shortcode_mc' );
function showifrole_is_shortcode_mc( $atts, $content = null) {
	
	if (is_array($atts) && $atts['is']) {
		$is = $atts['is'];}
		else {$is = administrator;}

	if (is_array($atts) && $atts['msg']) {
		$msg = $atts['msg'];}

	$user = wp_get_current_user();
	if (in_array ($is, (array) $user->roles ))
		return do_shortcode($content);
	echo $msg;
}
