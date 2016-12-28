<?php
/**
 * Fired when the plugin is uninstalled.
 *
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Custom_Register_Login
 */
// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

//Delete options from db
$options = array(
    'wpcrl_redirect_settings',
    'wpcrl_display_settings',
    'wpcrl_form_settings',
    'wpcrl_email_settings');

foreach ($options as $key => $option) {
    delete_option($option);
}


