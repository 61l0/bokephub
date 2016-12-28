<?php
function sg_popup_admin_style($hook) {
	if ('toplevel_page_PopupBuilder' != $hook && 
		'popup-builder_page_create-popup' != $hook &&
		'popup-builder_page_edit-popup' != $hook && 
		'popup-builder_page_sgPopupMenu' != $hook && 
		'popup-builder_page_more-plugins' != $hook && 
		'popup-builder_page_popup-settings' != $hook && 
		'popup-builder_page_subscribers' != $hook &&
		'popup-builder_page_newsletter' != $hook) {
        return;
    }
	wp_register_style('sg_popup_style', SG_APP_POPUP_URL . '/style/sg_popup_style.css', false, '1.0.0');
	wp_enqueue_style('sg_popup_style');
	wp_register_style('sg_popup_review_panel_style', SG_APP_POPUP_URL . '/style/sg_review_panel.css', false, '1.0.0');
	wp_enqueue_style('sg_popup_review_panel_style');
	wp_register_style('sg_popup_animate', SG_APP_POPUP_URL . '/style/animate.css');
	wp_enqueue_style('sg_popup_animate');
	if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_SILVER) {
		wp_register_style('font_awesome', SG_APP_POPUP_URL . "/style/jssocial/font-awesome.min.css");
		wp_enqueue_style('font_awesome');
		wp_register_style('jssocials_main_css', SG_APP_POPUP_URL . "/style/jssocial/jssocials.css");
		wp_enqueue_style('jssocials_main_css');
		wp_register_style('jssocials_theme_tm', SG_APP_POPUP_URL . "/style/jssocial/jssocials-theme-classic.css");
		wp_enqueue_style('jssocials_theme_tm');
		wp_register_style('sg_flipclock_css', SG_APP_POPUP_URL . "/style/sg_flipclock.css");
		wp_enqueue_style('sg_flipclock_css');
		wp_register_style('sg_jqueryUi_css', SG_APP_POPUP_URL . "/style/jquery-ui.min.css");
		wp_enqueue_style('sg_jqueryUi_css');
	}
	if(POPUP_BUILDER_PKG != POPUP_BUILDER_PKG_FREE) {
		wp_register_style('sg_datetimepicker_css', SG_APP_POPUP_URL . "/style/jquery.datetimepicker.min.css");
		wp_enqueue_style('sg_datetimepicker_css');
	}
	if(POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_PLATINUM) {
		wp_register_style('sg_bootstrap_input', SG_APP_POPUP_URL . "/style/bootstrap-tagsinput.css");
		wp_enqueue_style('sg_bootstrap_input');
	}
}
add_action('admin_enqueue_scripts', 'sg_popup_admin_style');

function sg_popup_style($hook) {
	if ('admin.php' != $hook) {
		return;
	}
	wp_register_style('sg_popup_animate', SG_APP_POPUP_URL . '/style/animate.css');
	wp_enqueue_style('sg_popup_animate');

	wp_register_style('sg_popup_style', SG_APP_POPUP_URL . '/style/sg_popup_style.css', false);
	wp_enqueue_style('sg_popup_style');
}

add_action('admin_enqueue_scripts', 'sg_popup_style');
