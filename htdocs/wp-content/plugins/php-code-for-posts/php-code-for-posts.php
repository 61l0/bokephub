<?php
/**
 * Plugin Name: PHP Code For Posts
 * Description: Insert and Execute PHP Code in WordPress Content.  This plugin also enables shortcodes for the text widget.
 * Version: 2.1.3.1
 * Author: Jamie Fraser
 * Author uri: http://www.jamiefraser.co.uk/?utm-campaign=PHPCodeForPosts
 * Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SFQZ3KDJ4LQBA
 * Text Domain: phpcodeforposts
**/

if (function_exists('session_status')) {
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
} else {
	if(session_id() == '') {
    	session_start();
	}
}
global $PHPPC;
include('Classes/PhpCodeForPosts.php');

$plugin_directory = trailingslashit( dirname( __FILE__ ) );
$plugin_web_path = trailingslashit( plugins_url( '', __FILE__ ) );

$PHPPC = new PhpCodeForPosts( $plugin_directory, $plugin_web_path );
register_activation_hook( __FILE__ , array( "PhpCodeForPosts_Install" , "activation_hook" ) );
register_uninstall_hook( __FILE__, array( "PhpCodeForPosts_Install", "uninstall_hook" ) );
add_action( 'plugins_loaded', 'PHPPC_text_domain' );

function PHPPC_text_domain()
{
    load_plugin_textdomain("phpcodeforposts", false, basename( dirname( __FILE__ ) ) );
}
