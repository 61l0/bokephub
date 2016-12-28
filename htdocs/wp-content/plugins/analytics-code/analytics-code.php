<?php
/*
Plugin Name: Analytics Code Integration
Description: Easy integrate the Google Analytics Code on any WordPress website.
Version: 1.0
License: GPLv2 or later
Author: tms_gac
*/

define("GA_TC_TITLE", 'Google Analytics Code');
define("GA_TC_PLUGIN_NAME", basename(dirname(__FILE__)));
define("GA_TC_MENU_PREFIX", 'ga_tc_');

add_action('admin_menu', 'ga_tc_action_add_menu');

add_action('wp_footer','ga_tc_action_wp_footer');

if(!function_exists('ga_tc_action_add_menu')) {
    function ga_tc_action_add_menu() {
        $pages = array();
        $pages[] = add_options_page(
            GA_TC_TITLE,
            GA_TC_TITLE,
            'administrator',
            GA_TC_MENU_PREFIX . 'settings',
            'ga_tc_pageOptions'
        );
    }
}

if(!function_exists('ga_tc_pageOptions')) {
    function ga_tc_pageOptions() {
        require 'page_options.php';
    }
}

if(!function_exists('ga_tc_action_wp_footer')) {
    function ga_tc_action_wp_footer() {
	    $ga_tc_type = get_option( 'ga_tc_type', 'id' );
	    $ga_tc_code = get_option('ga_tc_code', '');
	    $ga_tc_id = get_option( 'ga_tc_id', '' );

	    if ($ga_tc_type == 'id' && !empty($ga_tc_id)) {
		    echo '<!-- '.GA_TC_PLUGIN_NAME.' google analytics tracking code -->';
		    require_once 'code_universal.php';
		    echo '<!--  -->';
	    } elseif ($ga_tc_type == 'code' && !empty($ga_tc_code)) {
		    echo '<!-- '.GA_TC_PLUGIN_NAME.' google analytics manual tracking code -->';
		    echo stripslashes($ga_tc_code);
		    echo '<!--  -->';
	    }
    }
}

