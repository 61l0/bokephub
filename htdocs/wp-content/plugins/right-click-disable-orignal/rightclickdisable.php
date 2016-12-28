<?php
/*
Plugin Name: Right Click Disable Orignal
Plugin URI: http://yashfale.in/wp-content/uploads/2014/09/rightclickdisable.zip
Description: This  plugin prevent right click.
Author: Yash Fale
Version: 1.0
Author URI: http://www.yashfale.in
*/
add_action('wp_enqueue_scripts', 'pops');

function pops() 
{
	 wp_enqueue_script('jquery');
	 wp_register_script('popsjs',plugins_url( 'rightclickdisable.js' , __FILE__ ),array( 'jquery' ));
         wp_enqueue_script('popsjs');
}
?>