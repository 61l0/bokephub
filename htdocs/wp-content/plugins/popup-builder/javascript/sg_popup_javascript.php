<?php

function sg_set_admin_url($hook) {
	if ('popup-builder_page_create-popup' == $hook) {
		echo '<script type="text/javascript">SG_ADMIN_URL = "'.admin_url()."admin.php?page=create-popup".'";</script>';
	}
}

function sg_popup_admin_scripts($hook) {
	
    if ( 'popup-builder_page_edit-popup' == $hook  || 'popup-builder_page_create-popup' == $hook || 'popup-builder_page_subscribers' == $hook) {

		wp_enqueue_media();
		wp_register_script('javascript', SG_APP_POPUP_URL . '/javascript/sg_popup_backend.js', array('jquery', 'wp-color-picker'));
		wp_enqueue_script('jquery');
		wp_enqueue_script('javascript');
		
		if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
			wp_register_script('sg_popup_pro', SG_APP_POPUP_URL . '/javascript/sg_popup_backend_pro.js');
			wp_enqueue_script('sg_popup_pro');
		}
    }
	else if('toplevel_page_PopupBuilder' == $hook  || $hook == 'toplevel_page_popup-settings'){
		wp_register_script('javascript', SG_APP_POPUP_URL . '/javascript/sg_popup_backend.js', array('jquery', 'wp-color-picker'));
		wp_enqueue_script('jquery');
		wp_enqueue_script('javascript');
		if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
			wp_register_script('sg_popup_pro', SG_APP_POPUP_URL . '/javascript/sg_popup_backend_pro.js');
			wp_enqueue_script('sg_popup_pro');
			wp_enqueue_media();
		}
		wp_enqueue_script('jquery');
	}
	if('popup-builder_page_edit-popup' == $hook) {
		wp_register_script('sg_popup_rangeslider', SG_APP_POPUP_URL . '/javascript/sg_popup_rangeslider.js', array('jquery'));
		wp_enqueue_script('sg_popup_rangeslider');
		wp_enqueue_script('jquery');
		if (POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_PLATINUM) {
			wp_register_script('sg_popup_tagsinput', SG_APP_POPUP_URL . '/javascript/bootstrap-tagsinput.js', array('jquery'));
			wp_enqueue_script('sg_popup_tagsinput');
		}
		if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_SILVER) {
			wp_register_script('jssocials.min', SG_APP_POPUP_URL . '/javascript/jssocials.min.js');
			wp_enqueue_script('jssocials.min');
			wp_register_script('sg_social_backend', SG_APP_POPUP_URL . '/javascript/sg_social_backend.js',array('jquery'));
			wp_enqueue_script('sg_social_backend');
		}
		if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
			wp_register_script('datetimepicker', SG_APP_POPUP_URL . '/javascript/jquery.datetimepicker.full.min.js');
			wp_enqueue_script('datetimepicker');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script( 'sg_libs_handle', plugins_url('javascript/sg_datapickers.js',dirname(__FILE__)), array('wp-color-picker'));
			wp_register_script('sg_popup_pro', SG_APP_POPUP_URL . '/javascript/sg_popup_backend_pro.js');
			wp_enqueue_script('sg_popup_pro');
		}
		wp_enqueue_style( 'wp-color-picker' );
		
	}
}

function SgFrontendScripts() {
	wp_enqueue_script('sg_popup_core', plugins_url('/sg_popup_core.js', __FILE__), '1.0.0', true);
	echo "<script type='text/javascript'>SG_POPUPS_QUEUE = [];SG_POPUP_DATA = [];SG_APP_POPUP_URL = '".SG_APP_POPUP_URL."';SG_POPUP_VERSION='".SG_POPUP_VERSION."_".POPUP_BUILDER_PKG."'</script>";
}

add_action('admin_enqueue_scripts', 'sg_set_admin_url');
add_action('admin_enqueue_scripts', 'sg_popup_admin_scripts');
add_action('wp_enqueue_scripts', 'SgFrontendScripts');


