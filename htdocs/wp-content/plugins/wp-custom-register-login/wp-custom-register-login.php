<?php
/**
 * Plugin Name:       WP Custom Register Login
 * Plugin URI:        http://www.daffodilsw.com
 * Description:       This plugin will help you to add ajax enabled custom login/register form on your website in just few minutes.
 * Version:           2.0.0
 * Author:            Jenis Patel
 * Author URI:        http://www.daffodilsw.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-custom-register-login
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Function that runs during plugin activation.
 */
function activate_wp_custom_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-register-login-activator.php';
	Wp_Custom_Register_Login_Activator::activate();
}

/**
 * Function that runs during plugin deactivation.
 */
function deactivate_wp_custom_register_login() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-register-login-deactivator.php';
	Wp_Custom_Register_Login_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_custom_register_login' );
register_deactivation_hook( __FILE__, 'deactivate_wp_custom_register_login' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-custom-register-login.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_wp_custom_register_login() {

	$plugin = new Wp_Custom_Register_Login();
	$plugin->run();

}
run_wp_custom_register_login();
