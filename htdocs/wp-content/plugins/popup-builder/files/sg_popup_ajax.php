<?php

function sgPopupDelete()
{
	$id = (int)@$_POST['popup_id'];
	if (!$id) {
		return;
	}
	require_once(SG_APP_POPUP_CLASSES.'/SGPopup.php');
	SGPopup::delete($id);
	SGPopup::removePopupFromPages($id);

	$args = array('popupId'=> $id);
	do_action('sgPopupDelete', $args);
}

add_action('wp_ajax_delete_popup', 'sgPopupDelete');

function sgFrontend()
{
	global $wpdb;
	parse_str($_POST['subsribers'], $subsribers);
	$email = $subsribers['subs-email-name'];
	$firstName = $subsribers['subs-first-name'];
	$lastName = $subsribers['subs-last-name'];
	$title = $subsribers['subs-popup-title'];

	$query = $wpdb->prepare("SELECT id FROM ". $wpdb->prefix ."sg_subscribers WHERE email = %s AND subscriptionType = %s", $email, $title);
	$list = $wpdb->get_row($query, ARRAY_A);
	if(!isset($list['id'])) {
		$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."sg_subscribers (firstName, lastName, email, subscriptionType, status) VALUES (%s, %s, %s, %s,%d)", $firstName, $lastName, $email, $title, 0);
		$res = $wpdb->query($sql);
	}
	die();
}

add_action('wp_ajax_nopriv_subs_send_mail', 'sgFrontend');
add_action('wp_ajax_subs_send_mail', 'sgFrontend');

function sgContactForm()
{
	global $wpdb;
	parse_str($_POST['contactParams'], $params);
	$adminMail = sanitize_text_field($_POST['receiveMail']);
	$popupTitle = sanitize_text_field($_POST['popupTitle']);
	$name = $params['contact-name'];
	$subject = $params['contact-subject'];
	$userMessage = $params['content-message'];
	$mail = $params['contact-email'];


	$message = '';
	if(isset($name)) {
		if($name == '') {
			$name = 'Not provided';
		}
		$message .= '<b>Name</b>: '.$name."<br>";
	}
	
	$message .= '<b>E-mail</b>: '.$mail."<br>";
	if(isset($subject)) {
		if($subject == '') {
			$subject = 'Not provided';
		}
		$message .= '<b>Subject</b>: '.$subject."<br>";
	}
	
	$message .= '<b>Message</b>: '.$userMessage."<br>";
	$headers  = 'MIME-Version: 1.0'."\r\n";
	$headers  = 'From: '.$adminMail.''."\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n"; //set UTF-8

	$sendStatus = wp_mail($adminMail, $popupTitle.'- Popup contact form', $message, $headers); //return true or false
	echo $sendStatus;
	die();
}

add_action('wp_ajax_nopriv_contact_send_mail', 'sgContactForm');
add_action('wp_ajax_contact_send_mail', 'sgContactForm');

function sgImportPopups()
{
	global $wpdb;
	$url = $_POST['attachmentUrl'];

	$contents = unserialize(base64_decode(file_get_contents($url)));

	/* For tables wich they are not popup tables child ex. subscribers */
	foreach ($contents['customData'] as $tableName => $datas) {
		$columns = '';

		$columsArray = array();
		foreach ($contents['customTablesColumsName'][$tableName] as $key => $value) {
			$columsArray[$key] = $value['Field'];
		}
		$columns .= implode(array_values($columsArray), ', ');
		foreach ($datas as $key => $data) {
			$values = "'".implode(array_values($data), "','")."'";
			$customInsertSql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix.$tableName."($columns) VALUES ($values)");
			$wpdb->query($customInsertSql);
		}
	}

	foreach ($contents['wpOptions'] as $key => $option) {
		update_option($key,$option);
	}
	
	foreach ($contents['exportArray'] as $content) {
		//Main popup table data
		$popupData = $content['mainPopupData'];
		$popupType = $popupData['type'];
		$popupTitle = $popupData['title'];
		$popupOptions = $popupData['options'];

		//Insert popup
		$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix.PopupInstaller::$maintablename."(type, title, options) VALUES (%s, %s, %s)", $popupType, $popupTitle, $popupOptions);
		$res = $wpdb->query($sql);
		//Get last insert popup id
		$lastInsertId = $wpdb->insert_id;

		//Child popup data
		$childPopupTableName = $content['childTableName']; // change it Tbale to Table
		$childPopupData = $content['childData']; //change it child

		//Foreach throw child popups
		foreach ($childPopupData as $childPopup) {
			//Child popup table columns
			$values = '';
			$columns = implode(array_keys($childPopup), ', ');
			//$values = "'".implode(array_values($childPopup), "','")."'";
			foreach (array_values($childPopup) as $value) {
				$values .= "'".addslashes($value)."', ";
			}
			$values = rtrim($values, ', ');
			
			$queryValues = str_repeat("%s, ", count(array_keys($childPopup)));
			$queryValues = "%d, ".rtrim($queryValues, ', ');
			
			$queryStr = 'INSERT INTO '.$wpdb->prefix.$childPopupTableName.'(id, '.$columns.') VALUES ('.$lastInsertId.','. $values.')';
			//$sql = $wpdb->prepare($queryStr,$lastInsertId, $valuess);
			
			$resa = $wpdb->query($queryStr);
			
			echo 'ChildRes: '.$resa;
		}
		echo 'MainRes: '.$res;
	}
}

add_action('wp_ajax_import_popups', 'sgImportPopups');

function sgCloseReviewPanel() {
    update_option('SG_COLOSE_REVIEW_BLOCK', true);
}
add_action('wp_ajax_close_review_panel', 'sgCloseReviewPanel');

function sgLazyLoading() {

	$popupId = (int)$_POST['popupId'];
	$params = "";
	$postType = $_POST['postType'];
	$loadingNumber = (int)$_POST['loadingNumber'];
	$customParams = $_POST['customParams'];

	/* Whem load first time need add Home page in Wp pages */
	if($loadingNumber == 0) {
		$defArray = array(-1=>"Home page");
	}
	else {
		$defArray = array();
	}

	if($postType == SG_POST_TYPE_PAGE) {
		$pageData = SgPopupPro::getAllData($defArray, 'page', $loadingNumber);
	}
	if($postType == SG_POST_TYPE_POST) {
		$pageData = SgPopupPro::getAllData($defArray, 'post', $loadingNumber);
	}
	if($postType == 'allCustomPosts') {
		$pageData = array();
		foreach ($customParams['customPosts'] as $value) {
			$currentData = SgPopupPro::getAllData(array(), $value, $loadingNumber);
			$pageData += $currentData;
		}
	}

	if($loadingNumber > 0 && sizeof($pageData) == 0) {
		die();
	}

	/* When popup is insert */
	if($popupId != -1) {
		$popup = SGPopup::findById($popupId);
		$options = $popup->getOptions();
		$options = json_decode($options, true);

		if($postType == SG_POST_TYPE_PAGE) {
			$allSelectedPages = $options['allSelectedPages'];
		}
		else if($postType == SG_POST_TYPE_POST) {
			$allSelectedPages = $options['allSelectedPosts'];
		}
		else if($postType == 'allCustomPosts') {
			$allSelectedPages = $options['allSelectedCustomPosts'];
		}
	}
	
	foreach ($pageData as $key => $value) {
		/* Add Home page in Wp pages array */
		if($popupId != -1) {
			$selected = "";
			if(isset($allSelectedPages) && @in_array($key, $allSelectedPages)) {
				$selected = "selected";
			}
		}
		else {
			$selected = "";
		}
		
		$params .= "<option value='".$key."' $selected>$value</option>";
	}
	echo $params;
	die();
}
add_action('wp_ajax_lazy_loading', 'sgLazyLoading');

function addToSubscribers() {

	global $wpdb;
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];
	$subsType = $_POST['subsType'];

	foreach ($subsType as $subType) {
		$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."sg_subscribers (firstName, lastName, email, subscriptionType) VALUES (%s, %s, %s, %s)", $firstName, $lastName, $email, $subType);
		$res = $wpdb->query($sql);
	}
	
	die();
}
add_action('wp_ajax_add_to_subsribers', 'addToSubscribers');

function sgDeleteSubscribers() {

	global $wpdb;

	$subsribersId = $_POST['subsribersId'];
	foreach ($subsribersId as $subsriberId) {
		$prepareSql = $wpdb->prepare("DELETE FROM ". $wpdb->prefix ."sg_subscribers WHERE id = %d",$subsriberId);
		$wpdb->query($prepareSql);
	}
	die();
}

add_action('wp_ajax_subsribers_delete', 'sgDeleteSubscribers');

function sgSendNewsletter() {

	global $wpdb;
	$newslatterData = $_POST['NewsLatterData'];

	/*Change to default status*/
	$updateStatusQuery = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_subscribers SET status=0 where subscriptionType = %s",$newslatterData['subsFormType']);
	$wpdb->query($updateStatusQuery);
	$deleteFromErrorLog = $wpdb->prepare("DELETE FROM ". $wpdb->prefix ."sg_subscription_error_log WHERE popupType=%s",$newslatterData['subsFormType']);
	$wpdb->query($deleteFromErrorLog);

	wp_schedule_event( time(), 'newsLetterSendEveryMinute', 'sgnewsletter_send_messages', array(json_encode($newslatterData)));

	die();
}
add_action('wp_ajax_send_newsletter', 'sgSendNewsletter');

function sgIsHaveErrorLog() {

	global $wpdb;
	$countRows = '';
	$popupType = $_POST['subsType'];
	
	$getErrorCounteSql = $wpdb->prepare("SELECT count(*) FROM ". $wpdb->prefix ."sg_subscription_error_log WHERE popupType=%s",$popupType);
	$countRows = $wpdb->get_var($getErrorCounteSql);
	echo $countRows;
	die();
}

add_action('wp_ajax_subs_error_log_count', 'sgIsHaveErrorLog');

function sgChangePopupStatus() {
	$popupId = (int)$_POST['popupId'];
	$obj = SGPopup::findById($popupId);
	$options = json_decode($obj->getOptions(), true);
	$options['isActiveStatus'] = $_POST['popupStatus'];
	$obj->setOptions(json_encode($options));
	$obj->save();
}	
add_action('wp_ajax_change_popup_status', 'sgChangePopupStatus');

