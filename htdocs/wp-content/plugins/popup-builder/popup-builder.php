<?php
/**
* Plugin Name: Popup Builder
* Plugin URI: http://sygnoos.com
* Description: The most complete popup plugin. Html, image, iframe, shortcode, video and many other popup types. Manage popup dimensions, effects, themes and more.
* Version: 2.4.6
* Author: Sygnoos
* Author URI: http://www.sygnoos.com
* License: GPLv2
*/

require_once(dirname(__FILE__)."/config.php");
require_once(SG_APP_POPUP_CLASSES .'/SGPopupBuilderMain.php');

$mainPopupObj = new SGPopupBuilderMain();
$mainPopupObj->init();

require_once(SG_APP_POPUP_CLASSES .'/SGPopup.php');
require_once(SG_APP_POPUP_FILES .'/sg_functions.php');
require_once(SG_APP_POPUP_HELPERS .'/Integrate_external_settings.php');
require_once(SG_APP_POPUP_HELPERS .'/SgPopupGetData.php');

require_once(SG_APP_POPUP_CLASSES .'/PopupInstaller.php'); //cretae tables

if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
	require_once( SG_APP_POPUP_CLASSES .'/PopupProInstaller.php'); //uninstall tables
	require_once(SG_APP_POPUP_FILES ."/sg_popup_pro.php"); // Pro functions
}
require_once(SG_APP_POPUP_PATH .'/style/sg_popup_style.php' ); //include our css file
require_once(SG_APP_POPUP_JS .'/sg_popup_javascript.php' ); //include our js file
require_once(SG_APP_POPUP_FILES .'/sg_popup_page_selection.php' );  // include here in page  button for select popup every page

register_activation_hook(__FILE__, 'sgPopupActivate');
register_uninstall_hook(__FILE__, 'sgPopupDeactivate');

add_action('wpmu_new_blog', 'sgNewBlogPopup', 10, 6);

function sgNewBlogPopup()
{
	PopupInstaller::install();
	if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
		PopupProInstaller::install();
	}
}

function sgPopupActivate()
{
	update_option('SG_POPUP_VERSION', SG_POPUP_VERSION);
	PopupInstaller::install();
	if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
		PopupProInstaller::install();
	}
}

function sgPopupDeactivate()
{
	$deleteStatus = SGFunctions::popupTablesDeleteSatus();

	if($deleteStatus) {
		PopupInstaller::uninstall();
		if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
			PopupProInstaller::uninstall();
		}
	}
}


function sgRegisterScripts()
{
	SGPopup::$registeredScripts = true;
	wp_register_style('sg_animate', SG_APP_POPUP_URL . '/style/animate.css');
	wp_enqueue_style('sg_animate');
	wp_register_script('sg_popup_frontend', SG_APP_POPUP_URL . '/javascript/sg_popup_frontend.js', array('jquery'), SG_POPUP_VERSION);
	wp_enqueue_script('sg_popup_frontend');
	wp_register_script('sg_popup_init', SG_APP_POPUP_URL . '/javascript/sg_popup_init.js', array('jquery'), SG_POPUP_VERSION);
	wp_enqueue_script('sg_popup_init');
	wp_enqueue_script('jquery');
	wp_register_script('sg_colorbox', SG_APP_POPUP_URL . '/javascript/jquery.sgcolorbox-min.js', array('jquery'), SG_POPUP_VERSION);
	wp_enqueue_script('sg_colorbox');
	if (POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
		wp_register_script('sgPopupPro', SG_APP_POPUP_URL . '/javascript/sg_popup_pro.js', array(), SG_POPUP_VERSION);
		wp_enqueue_script('sgPopupPro');
		wp_register_script('sg_cookie', SG_APP_POPUP_URL . '/javascript/jquery_cookie.js', array('jquery'), SG_POPUP_VERSION);
		wp_enqueue_script('sg_cookie');
		wp_register_script('sg_popup_queue', SG_APP_POPUP_URL . '/javascript/sg_popup_queue.js', array(), SG_POPUP_VERSION);
		wp_enqueue_script('sg_popup_queue');
	}
	/* For ajax case */
	if (defined( 'DOING_AJAX' ) && DOING_AJAX  && !is_admin()) {
		wp_print_scripts('sg_popup_frontend');
		wp_print_scripts('sg_colorbox');
		wp_print_scripts('sg_popup_support_plugins');
		wp_print_scripts('sgPopupPro');
		wp_print_scripts('sg_cookie');
		wp_print_scripts('sg_popup_queue');
		wp_print_scripts('sg_animate');
		wp_print_scripts('sg_popup_init');
	}
}

function sgRenderPopupScript($id)
{
	if (SGPopup::$registeredScripts==false) {
		sgRegisterScripts();
	}
	wp_register_style('sg_colorbox_theme', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox1.css", array(), SG_POPUP_VERSION);
	wp_register_style('sg_colorbox_theme2', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox2.css", array(), SG_POPUP_VERSION);
	wp_register_style('sg_colorbox_theme3', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox3.css", array(), SG_POPUP_VERSION);
	wp_register_style('sg_colorbox_theme4', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox4.css", array(), SG_POPUP_VERSION);
	wp_register_style('sg_colorbox_theme5', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox5.css", array(), SG_POPUP_VERSION);
	wp_register_style('sg_colorbox_theme6', SG_APP_POPUP_URL . "/style/sgcolorbox/colorbox6.css", array(), SG_POPUP_VERSION);
	wp_enqueue_style('sg_colorbox_theme');
	wp_enqueue_style('sg_colorbox_theme2');
	wp_enqueue_style('sg_colorbox_theme3');
	wp_enqueue_style('sg_colorbox_theme4');
	wp_enqueue_style('sg_colorbox_theme5');
	wp_enqueue_style('sg_colorbox_theme6');
	sgFindPopupData($id);
}

function sgFindPopupData($id)
{
	$obj = SGPopup::findById($id);
	if (!empty($obj)) {
		$content = $obj->render();
	}

	if (POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_PLATINUM) {
		$userCountryIso = SGFunctions::getUserLocationData($id);
		if(!is_bool($userCountryIso)) {
			echo "<script type='text/javascript'>SgUserData = {
				'countryIsoName': '$userCountryIso'
			}</script>";
		}
	}

	echo "<script type='text/javascript'>";
	echo @$content;
	echo "</script>";
}

function sgShowShortCode($args, $content)
{
	ob_start();
	$obj = SGPopup::findById($args['id']);
	if (!$obj) {
		return $content;
	}
	if(!empty($content)) {
		sgRenderPopupScript($args['id']);
		$attr = '';
		$eventName = @$args['event'];
		
		if(isset($args['insidepopup'])) {
			$attr .= 'insidePopup="on"';
 		}
 		if(@$args['event'] == 'onload') {
 			$content = '';
 		}
 		if(!isset($args['event'])) {
 			$eventName = 'click';
 		}
 		if(isset($args["wrap"])) {
 			echo "<".$args["wrap"]." class='sg-show-popup' data-sgpopupid=".@$args['id']." $attr data-popup-event=".$eventName.">".$content."</".$args["wrap"]." >";
 		} else {
			echo "<a href='javascript:void(0)' class='sg-show-popup' data-sgpopupid=".@$args['id']." $attr data-popup-event=".$eventName.">".$content."</a>";
		}
	}
	else {
		/* Free user does not have QUEUE possibility */
		if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {
			$page = get_queried_object_id();
			$popupsId = SgPopupPro::allowPopupInAllPages($page,'page');
			
			/* When have many popups in current page */
			if(count($popupsId) > 0) {
				/* Add shordcode popup id in the QUEUE for php side */
				array_push($popupsId,$args['id']);
				/* Add shordcode popup id at the first in the QUEUE for javascript side */
				echo "<script type=\"text/javascript\">SG_POPUPS_QUEUE.splice(0, 0, ".$args['id'].");</script>";
				update_option("SG_MULTIPLE_POPUP",$popupsId);
				sgRenderPopupScript($args['id']);
			}
			else {
				echo showPopupInPage($args['id']);
			}
		}
		else {
			echo showPopupInPage($args['id']);
		}
		
	}
	$shortcodeContent = ob_get_contents();
	ob_end_clean();
	return do_shortcode($shortcodeContent);
}
add_shortCode('sg_popup', 'sgShowShortCode');

function sgRenderPopupOpen($popupId)
{
	sgRenderPopupScript($popupId);
	
	echo "<script type=\"text/javascript\">

			sgAddEvent(window, 'load',function() {
				var sgPoupFrontendObj = new SGPopup();
				sgPoupFrontendObj.popupOpenById($popupId)
			});
		</script>";
}

function showPopupInPage($popupId) {

	$isActivePopup = SgPopupGetData::isActivePopup($popupId);

	if(!$isActivePopup) {
		return false;
	}

	if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE) {

		$popupInTimeRange = SgPopupPro::popupInTimeRange($popupId);
	
		if(!$popupInTimeRange) {
			return false;
		}

		$isInSchedule = SgPopupPro::popupInSchedule($popupId);

		if(!$isInSchedule) {
			return;
		}

		$showUser = SgPopupPro::showUserResolution($popupId);
		if(!$showUser) return false;
		
		if(!SGPopup::showPopupForCounrty($popupId)) { /* Sended popupId and function return true or false */
			return;
		}
	}
	redenderScriptMode($popupId);
}

function redenderScriptMode($popupId)
{
	/* If user delete popup */
	$obj = SGPopup::findById($popupId);
	if(empty($obj)) {
		return;
	}
	$multiplePopup = get_option('SG_MULTIPLE_POPUP');
	$exitIntentPopupId = get_option('SG_POPUP_EXITINTENT_'.$popupId);
	
	if(isset($exitIntentPopupId) && $exitIntentPopupId == $popupId) {
		sgRenderPopupScript($popupId);
		require_once(SG_APP_POPUP_CLASSES.'/SGExitintentPopup.php');
		$exitObj = new SGExitintentPopup();
		echo $exitObj->getExitIntentInitScript($popupId);
		return;
	}
	if($multiplePopup && @in_array($popupId, $multiplePopup)) {
		sgRenderPopupScript($popupId);
		return;	
	}
	

	sgRenderPopupOpen($popupId);
}

function getPopupIdFromContentByClass($content) {

 	$popupsID = array();
 	$popupClasses = array(
 		'sg-popup-id-',
 		'sg-iframe-popup-',
 		'sg-confirm-popup-'
 	);

 	foreach ($popupClasses as $popupClassName) {

 		preg_match_all("/".$popupClassName."+[0-9]+/i", $content, $matchers);

	 	foreach ($matchers['0'] as $value) {
	 		$ids = explode($popupClassName, $value);
	 		$id = @$ids[1];

	 		if(!empty($id)) {
	 			array_push($popupsID, $id);
	 		}
	 	}
 	}
 	
 	return $popupsID;
}

function getPopupIdInPageByClass($pageId) {

	$postContentObj = get_post($pageId);

	if(isset($postContentObj)) {
		$content = $postContentObj->post_content;
		return getPopupIdFromContentByClass($content);
	}
	
	return 0;
}

/**
 * Get popup id from url
 *
 * @since 3.1.5
 *
 * @return  int if popup not found->0 otherwise->popupId
 *
 */

function getPopupIdFromUrl() {

	$popupId = 0;
	if(!isset($_SERVER['REQUEST_URI'])) {
		return $popupId;
	}

	$pageUrl = @$_SERVER['REQUEST_URI'];

	preg_match("/sg_popup_id=+[0-9]+/i", $pageUrl, $match);

	if(!empty($match)) {
		$matchingNumber = explode("=", $match[0]);
		if(!empty($matchingNumber[1])) {
			$popupId = (int)$matchingNumber[1];
			return $popupId;
		}
		return 0;
	}

	return 0;
}

function sgOnloadPopup()
{
	$page = get_queried_object_id();
	$postType = get_post_type();
	$popup = "sg_promotional_popup";
	/* If popup is set on page load */
	$popupId = SGPopup::getPagePopupId($page, $popup);
	/* get all popups id which set in current page by class */
	$popupsIdByClass = getPopupIdInPageByClass($page);
	/* get popup id in page url */
	$popupIdInPageUrl = getPopupIdFromUrl();

	if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_FREE){
		delete_option("SG_MULTIPLE_POPUP");

		/* Retrun all popups id width selected On All Pages */
		$popupsId = SgPopupPro::allowPopupInAllPages($page,'page');
		$categories = SgPopupPro::allowPopupInAllCategories($page);
		
		$popupsId = array_merge($popupsId,$categories);
	
		$sgpbAllPosts = get_option("SG_ALL_POSTS");
		
		$popupsInAllPosts = SgPopupPro::popupsIdInAllCategories($postType);
		$popupsId = array_merge($popupsInAllPosts, $popupsId);

		/* $popupsId[0] its last selected popup id */
		if(isset($popupsId[0])) {
			delete_option("SG_MULTIPLE_POPUP");
			if(count($popupsId) > 0) {
				update_option("SG_MULTIPLE_POPUP",$popupsId);
			}
			foreach ($popupsId as $queuePupupId) {

				showPopupInPage($queuePupupId);
			}
			
			$popupsId = json_encode($popupsId);
		}
		else {
			$popupsId = json_encode(array());
		}
		echo '<script type="text/javascript">
			SG_POPUPS_QUEUE = '.$popupsId.'</script>';
	}

	//If popup is set for all pages
	if($popupId != 0) {
		showPopupInPage($popupId);
	}

	if(!empty($popupsIdByClass)) {
		foreach ($popupsIdByClass as $popupId) {
			sgRenderPopupScript($popupId);
		}
	}
	if($popupIdInPageUrl) {
		showPopupInPage($popupIdInPageUrl);
	}
	return false;
}

add_filter('wp_nav_menu_items', 'getPopupIdByClassFromMenu');
function getPopupIdByClassFromMenu ($items) {
	$popupsID =  getPopupIdFromContentByClass($items);
	if(!empty($popupsID)) {
		foreach ($popupsID as $popupId) {
			sgRenderPopupScript($popupId);
		}
	}
	return $items;
}

add_action('wp_head','sgOnloadPopup');
require_once( SG_APP_POPUP_FILES . '/sg_popup_media_button.php');
require_once( SG_APP_POPUP_FILES . '/sg_popup_save.php'); // saving form data
require_once( SG_APP_POPUP_FILES . '/sg_popup_ajax.php');
require_once( SG_APP_POPUP_FILES . '/sg_admin_post.php');
require_once( SG_APP_POPUP_FILES . '/sg_popup_filetrs.php');
require_once( SG_APP_POPUP_FILES . '/sg_popup_actions.php');
