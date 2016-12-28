<?php
class SGPopupBuilderMain {

	public function init() {

		$this->filters();
		$this->sgpbActions();
	}

	public function sgpbActions() {
		
		add_action("admin_menu",array($this, "sgAddMenu"));
	}

	public function sgAddMenu($args) {
		$showCurrentUser = SGFunctions::isShowMenuForCurrentUser();
		if(!$showCurrentUser) {
			return false;
		}
		add_menu_page("Popup Builder", "Popup Builder", "read","PopupBuilder",  array($this, "sgPopupMenu"),"dashicons-welcome-widgets-menus");
		add_submenu_page("PopupBuilder", "All Popups", "All Popups", 'read', "PopupBuilder", array($this, "sgPopupMenu"));
		add_submenu_page("PopupBuilder", "Add New", "Add New", 'read', "create-popup", array($this,"sgCreatePopup"));
		add_submenu_page("PopupBuilder", "Edit Popup", "Edit Popup", 'read', "edit-popup", array($this,"sgEditPopup"));
		add_submenu_page("PopupBuilder", "Settings", "Settings", 'read', "popup-settings", array($this,"sgPopupSettings"));
		if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_SILVER) {
			add_submenu_page("PopupBuilder", "Subscribers", "Subscribers", 'read', "subscribers", array($this,"sgSubscribers"));
			add_submenu_page("PopupBuilder", "Newsletter", "Newsletter", 'read', "newsletter", array($this,"sgNewsletter"));
		}
		add_submenu_page("PopupBuilder", "More plugins", "More plugins", 'read', "more-plugins", array($this,"showMorePlugins"));
	}

	public function sgPopupMenu() {

		require_once( SG_APP_POPUP_FILES . '/sg_popup_main.php');
	}

	public function sgCreatePopup() {

		require_once( SG_APP_POPUP_FILES . '/sg_popup_create.php'); // here is inculde file in the first sub menu
	}

	public function sgPopupSettings() {

		require_once( SG_APP_POPUP_FILES . '/sg_popup_settings.php');
	}

	public function sgEditPopup() {

		require_once( SG_APP_POPUP_FILES . '/sg_popup_create_new.php');
	}

	public function sgSubscribers() {

		require_once( SG_APP_POPUP_FILES . '/sg_subscribers.php');
	}

	public function sgNewsletter() {
		
		require_once( SG_APP_POPUP_FILES . '/sg_newsletter.php');
	}

	public function showMorePlugins() {

		require_once( SG_APP_POPUP_FILES . '/sg_more_plugins.php');
	}

	public function filters() {

		add_filter('plugin_action_links_'. POPUP_BUILDER_BASENAME, array($this, 'popupPluginActionLinks'));
	}

	public function popupPluginActionLinks($links) {

		$popupActionLinks = array(
			'<a href="' . SG_POPUP_EXTENSION_URL . '" target="_blank">Extensions</a>',
		);

		if(POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_FREE) {
			array_push($popupActionLinks, '<a href="' . SG_POPUP_PRO_URL . '" target="_blank">Pro</a>');
		}
		
		return array_merge( $links, $popupActionLinks );
	}
}