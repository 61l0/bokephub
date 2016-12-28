<?php
class PopupInstaller
{
	public static $maintablename = "sg_popup";

	public static function createTables($blogsId)
	{
		global $wpdb;
		update_option('SG_POPUP_VERSION', SG_POPUP_VERSION);
		$sgPopupBase = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."sg_popup (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`type` varchar(255) NOT NULL,
			`title` varchar(255) NOT NULL,
			`options` LONGTEXT NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
		$sgPopupSettingsBase = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."sg_popup_settings (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`options` LONGTEXT NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
		$optionsDefault = SgPopupGetData::getDefaultValues();
		$sgPopupInsertSettingsSql = $wpdb->prepare("INSERT IGNORE ". $wpdb->prefix.$blogsId."sg_popup_settings (id, options) VALUES(%d,%s) ", 1, json_encode($optionsDefault['settingsParamas']));

		$sgPopupImageBase = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."sg_image_popup (
				`id` int(11) NOT NULL,
				`url` varchar(255) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
		$sgPopupHtmlBase = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."sg_html_popup (
				`id` int(11) NOT NULL,
				`content` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sgPopupFblikeBase = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."sg_fblike_popup (
				`id` int(11) NOT NULL,
				`content` text NOT NULL,
				`options` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$sgPopupShortcodeBase =  "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."sg_shortCode_popup (
				`id` int(12) NOT NULL,
				`url` text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		$sgPopupAddon = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogsId."sg_popup_addons (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL UNIQUE,
			`paths` TEXT NOT NULL,
			`type` varchar(255) NOT NULL,
			`options` TEXT NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";

		$wpdb->query($sgPopupBase);
		$wpdb->query($sgPopupSettingsBase);
		$wpdb->query($sgPopupInsertSettingsSql);
		$wpdb->query($sgPopupImageBase);
		$wpdb->query($sgPopupHtmlBase);
		$wpdb->query($sgPopupFblikeBase);
		$wpdb->query($sgPopupShortcodeBase);
		$wpdb->query($sgPopupAddon);
	}

	public static function install()
	{
		$obj = new self();
		$obj->createTables("");

		if(is_multisite()) {
			$sites = wp_get_sites();
			foreach($sites as $site) {
				$blogsId = $site['blog_id']."_";
				global $wpdb;
				$obj->createTables($blogsId);
			}
		}
	}

	public static function uninstallTables($blogsId)
	{
		global $wpdb;
		$delete = "DELETE FROM ".$wpdb->prefix.$blogsId."postmeta WHERE meta_key = 'sg_promotional_popup' ";
		$wpdb->query($delete);

		$popupTable = $wpdb->prefix.$blogsId."sg_popup";
		$popupSql = "DROP TABLE ". $popupTable;

		$popupImageTable = $wpdb->prefix.$blogsId."sg_image_popup";
		$popupImageSql = "DROP TABLE ". $popupImageTable;

		$popupHtmlTable = $wpdb->prefix.$blogsId."sg_html_popup";
		$popupHtmlSql = "DROP TABLE ". $popupHtmlTable;

		$popupFblikeTable = $wpdb->prefix.$blogsId."sg_fblike_popup";
		$popupFblikeSql = "DROP TABLE ". $popupFblikeTable;

		$popupShortcodeTable = $wpdb->prefix.$blogsId."sg_shortCode_popup";
		$popupShortcodeSql = "DROP TABLE ". $popupShortcodeTable;

		$popupAddonDrop = $wpdb->prefix.$blogsId."sg_popup_addons";
		$popupAddonSql = "DROP TABLE ". $popupAddonDrop;

		$popupSettingsDrop = $wpdb->prefix.$blogsId."sg_popup_settings";
		$popupSettingsSql = "DROP TABLE ". $popupSettingsDrop;

		$wpdb->query($popupSql);
		$wpdb->query($popupImageSql);
		$wpdb->query($popupHtmlSql);
		$wpdb->query($popupFblikeSql);
		$wpdb->query($popupShortcodeSql);
		$wpdb->query($popupAddonSql);
		$wpdb->query($popupSettingsSql);
	}

	public static function deleteSgPopupOptions($blogsId)
	{
		global $wpdb;
		$deleteSG = "DELETE FROM ".$wpdb->prefix.$blogsId."options WHERE option_name LIKE '%SG_POPUP%'";
		$wpdb->query($deleteSG);
	}

	public static function uninstall() {
		global $wpdb;
		$obj = new self();
		$obj->uninstallTables("");
		$obj->deleteSgPopupOptions("");

		if(is_multisite()) {
			$stites = wp_get_sites();
			foreach($stites as $site) {
				$blogsId = $site['blog_id']."_";
				global $wpdb;
				$obj->uninstallTables($blogsId);
				$obj->deleteSgPopupOptions($blogsId);
			}
		}
	}
}