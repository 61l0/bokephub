<?php
function sgGetCsvFile() {
	global $wpdb;
	$content = '';
	$sql = "SHOW COLUMNS FROM ". $wpdb->prefix ."sg_subscribers";
	$rows = $wpdb->get_results($sql, ARRAY_A);
	foreach ($rows as $value) {
		$content .= $value['Field'].",";
	}
	$content .= "\n";

	$sql = "Select * from ". $wpdb->prefix ."sg_subscribers";
	$subscribers = $wpdb->get_results($sql, ARRAY_A);

	foreach($subscribers as $values) {
		foreach ($values as  $value) {
			$content .= $value.',';
		}
		$content .= "\n";
	}

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"subscribersList.csv\";" );
	header("Content-Transfer-Encoding: binary");
	echo $content;
}

add_action('admin_post_csv_file', 'sgGetCsvFile');

function sgPopupClone() {
	$id = $_GET['id'];
	$obj = SGPopup::findById($id);
	$title = $obj->getTitle();
	$title .= "(clone)";
	$obj->setId("");
	$obj->setTitle($title);

	$options = $obj->getOptions();
	$options = json_decode($options, true);
	$obj->save();

	$cloneId = $obj->getId();
	/* For save popupIn pages table */
	if($options['allPagesStatus']) {
		if(!empty($options['showAllPages']) && $options['showAllPages'] != 'all') {
			update_option("SG_ALL_PAGES", false);
			SGPopup::addPopupForAllPages($cloneId, $options['allSelectedPages'], 'page');
		}
		else {
			update_option("SG_ALL_PAGES", $cloneId);
		}
	}
	if($options['allPostsStatus']) {
		if(!empty($options['showAllPosts']) && $options['showAllPosts'] != "all") {
			update_option("SG_ALL_POSTS", false);
			SGPopup::addPopupForAllPages($cloneId, $options['allSelectedPosts'], 'page');
		}
		else {
			update_option("SG_ALL_POSTS", $cloneId);
		}
	}

	wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=PopupBuilder");
}

add_action('admin_post_popup_clone', 'sgPopupClone');

function sgPopupDataExport() {
	global $wpdb;
	
	$allData = array();
	$exportArray = array();
	$wpOptions = array();
	$optionsName = array(
		"SG_ALL_PAGES",
		"SG_ALL_POSTS",
		"SG_MULTIPLE_POPUP"
	);

	$mainTable = PopupInstaller::$maintablename;

	$popupDataSql = "SELECT * FROM ".$wpdb->prefix.$mainTable;
	$getAllPopupData = $wpdb->get_results($popupDataSql, ARRAY_A);
	foreach ($getAllPopupData as $popupData) {
		$type = $popupData['type'];
		$id = $popupData['id'];
		if ($type == 'ageRestriction') {
			$type = "age_restriction";
		}
		else if($type == 'exitIntent') {
			$type = "exit_intent";
		}
		else if($type == 'contactForm') {
			$type = "contact_form";
		}
		else if($type == 'shortcode') {
			$type = "shortCode";
		}
		$table = "sg_".$type."_popup";
		$tableName = $wpdb->prefix.$table;

		$chieldPopupDataSql = "SELECT * FROM ".$tableName;
		$chieldPopupData = $wpdb->get_results($chieldPopupDataSql, ARRAY_A);

		$getRowsSql = "SHOW COLUMNS FROM ".$tableName;
		$chiledRows = $wpdb->get_results($getRowsSql, ARRAY_A);

		unset($chieldPopupData[0]['id']);
		//unset($chiledRows[0]);

		$exportArray[] = array(
			'mainPopupData' => $popupData,
			'childData' => $chieldPopupData,
			'chiledColums' => $chiledRows,
			'childTableName' => $table
		);
	}
	$customTables['sg_popup_in_pages'] = $wpdb->prefix."sg_popup_in_pages";
	$customTables['sg_subscribers'] = $wpdb->prefix."sg_subscribers";
	$customTablesColumsName = array();
	$customTablesData = array();

	foreach ($customTables as $key => $tableName) {

		$showColumnsSql = "SHOW COLUMNS FROM ".$tableName;
		$colums = $wpdb->get_results($showColumnsSql, ARRAY_A);
		$customTablesColumsName[$key] = $colums;

		$getCustomDataSql = "SELECT * FROM ".$tableName;
		$getCustomData = $wpdb->get_results($getCustomDataSql, ARRAY_A);
		$customTablesData[$key] = $getCustomData;
	}

	foreach ($optionsName as $optionName) {
		if(get_option($optionName)) {
			$wpOptions[$optionName] = get_option($optionName);
		}
	}

	$allData['exportArray'] = $exportArray;
	$allData['customData'] = $customTablesData;
	$allData['customTablesColumsName'] = $customTablesColumsName;
	$allData['wpOptions'] = $wpOptions;
	
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"sgexportdata.txt\";" );
	header("Content-Transfer-Encoding: binary");
	echo base64_encode(serialize($allData));
}

add_action('admin_post_popup_export', 'sgPopupDataExport');
function sgSanitizeField($key, $isTextField = false) {

	if (isset($_POST[$key])) {
		if($isTextField) {
			return wp_kses_post($_POST[$key]);
		}
		return sanitize_text_field($_POST[$key]);
	}
	return "";
}

function sgPopupSaveSettings() {

	global $wpdb;

	$st = $wpdb->prepare("SELECT options FROM ". $wpdb->prefix ."sg_popup_settings WHERE id = %d",1);
	$options = $wpdb->get_row($st, ARRAY_A);
	
	$settingsOptions = array(
		'plugin_users_role' => sgSanitizeField('plugin_users_role', true),
		'tables-delete-status' => sgSanitizeField('tables-delete-status'),
		'sg-popup-time-zone' => sgSanitizeField('sg-popup-time-zone')
	);
	
	$settingsOptions = json_encode($settingsOptions);
	if(is_null($options) || empty($options)) {

		$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_popup_settings (id, options) VALUES (%d,%s)",'1',$settingsOptions);
		$res = $wpdb->query($sql);
	}
	else {
		$sql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_popup_settings SET options=%s WHERE id=%d",$settingsOptions,1);
		$res = $wpdb->query($sql);
	}
	wp_redirect(SG_APP_POPUP_ADMIN_URL."admin.php?page=popup-settings&saved=1");
}

add_action('admin_post_save_settings', 'sgPopupSaveSettings');

function sgSubsErrorList() {
	global $wpdb;
	$content = '';
	$sql = "SHOW COLUMNS FROM ". $wpdb->prefix ."sg_subscription_error_log";
	$rows = $wpdb->get_results($sql, ARRAY_A);
	foreach ($rows as $value) {
		$content .= $value['Field'].",";
	}
	$content .= "\n";

	$sql = "Select * from ". $wpdb->prefix ."sg_subscription_error_log";
	$subscribers = $wpdb->get_results($sql, ARRAY_A);

	foreach($subscribers as $values) {
		foreach ($values as  $value) {
			$content .= $value.',';
		}
		$content .= "\n";
	}

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private", false);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"MailErrorLog.csv\";" );
	header("Content-Transfer-Encoding: binary");
	echo $content;
}

add_action('admin_post_subs_error_csv', 'sgSubsErrorList');

