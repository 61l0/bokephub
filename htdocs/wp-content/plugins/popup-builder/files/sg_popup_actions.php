<?php
function sgPopupPluginLoaded() {

	$versionPopup = get_option('SG_POPUP_VERSION');
	if (!$versionPopup || $versionPopup < SG_POPUP_VERSION ) {
		update_option('SG_POPUP_VERSION', SG_POPUP_VERSION);
		PopupInstaller::install();
	}
}

add_action('plugins_loaded', 'sgPopupPluginLoaded');

function sgnewslatter_repeat_function($args) {

	global $wpdb;
	/*Args is json from newsletter form parameters*/
	$params= json_decode($args, true);

	$subscriptionType = $params['subsFormType'];
	$sendingLimit = $params['emailsOneTime'];
	$emailMessage = $params['messageBody'];
	$mailSubject = $params['newsletterSubject'];
	$successMails = 0;
	$allData = array();
	$adminEmail = get_option('admin_email');
	
	$sql = $wpdb->prepare("select id from ".$wpdb->prefix."sg_subscribers  where status=0 and subscriptionType = %s limit 1",$subscriptionType);
	$result = $wpdb->get_row($sql, ARRAY_A);
	$id = (int)$result['id'];
	$getTotalSql = $wpdb->prepare("select count(*) from ".$wpdb->prefix."sg_subscribers  where  subscriptionType = %s ", $subscriptionType);
	$totalSubscribers = $wpdb->get_var($getTotalSql);

	/*Id = 0 when all emails status = 1*/
	if($id == 0) {
		/*Clear schedule hook*/
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'From: '.$adminEmail.''."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$successTotal = get_option("SG_NEWSLETTER_".$subscriptionType);
		$faildTotal = $totalSubscribers - $successTotal;

		$emailMessageCustom = 'Your mail list '.$subscriptionType.' delivered successfully!
						'.$successTotal.' of the '.$totalSubscribers.' emails succeeded, '.$faildTotal.' failed
						For more details, please download log files inside the plugin.

						This email was generated via Popup Builder plugin.';

		$mailStatus = wp_mail($adminEmail, $subscriptionType.' list has been successfully delivered!', $emailMessageCustom, $headers);
		delete_option("SG_NEWSLETTER_".$subscriptionType);
		wp_clear_scheduled_hook("sgnewsletter_send_messages", array(json_encode($params)));
		return;
	}
	else {
		$getAllDataSql = $wpdb->prepare("select firstName,lastName,email from ".$wpdb->prefix."sg_subscribers where id>=$id and subscriptionType = %s limit $sendingLimit",$subscriptionType);
		$allData = $wpdb->get_results($getAllDataSql, ARRAY_A);
	}

	foreach ($allData as $data) {
		
		$patternFirstName = '/\[First name]/';
		$patternLastName = '/\[Last name]/';
		$patternBlogName = '/\[Blog name]/';
		$patternUserName = '/\[User name]/';
		$replacementFirstName = $data['firstName'];
		$replacementLastName = $data['lastName'];
		$replacementBlogName = get_bloginfo("name");
		$replacementUserName = wp_get_current_user()->user_login;
		/*Replace First name and Last name form email message*/
		$emailMessageCustom = preg_replace($patternFirstName, $replacementFirstName, $emailMessage);
		$emailMessageCustom = preg_replace($patternLastName, $replacementLastName, $emailMessageCustom);
		$emailMessageCustom = preg_replace($patternBlogName, $replacementBlogName, $emailMessageCustom);
		$emailMessageCustom = preg_replace($patternUserName, $replacementUserName, $emailMessageCustom);
	
		/*Mail Headers*/
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'From: '.$adminEmail.''."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";

		$mailStatus = wp_mail($data['email'], $mailSubject, $emailMessageCustom, $headers);
		if(!$mailStatus) {
			$errorLogSql = $wpdb->prepare("INSERT INTO ". $wpdb->prefix ."sg_subscription_error_log(popupType,email,date) VALUES (%s,%s,%s)",$subscriptionType,$data['email'],Date('Y-m-d H:i'));
			$wpdb->query($errorLogSql);
		}
		/*Sending status*/
		$successCount = get_option("SG_NEWSLETTER_".$subscriptionType);
		if(!$successCount) {
			update_option("SG_NEWSLETTER_".$subscriptionType, 1);
		}
		else {
			update_option("SG_NEWSLETTER_".$subscriptionType, ++$successCount);
		}
		
	}
	/*Update all mails status which has been sent*/
	$updateStatusQuery = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_subscribers SET status=1 where id>=$id and subscriptionType = %s limit $sendingLimit",$subscriptionType);
	$wpdb->query($updateStatusQuery);
}
add_action ('sgnewsletter_send_messages', 'sgnewslatter_repeat_function', 10, 1);