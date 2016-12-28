<?php
class SgPopupGetData {

	public static function getDefaultValues() {

		$settingsParamas = array(
			'tables-delete-status' => 'on',
			'plugin_users_role' => array(),
			'sg-popup-time-zone' => 'Pacific/Midway'
		);

		$usersRoleList = self::getAllUserRoles();

		$deafultParams = array(
			'settingsParamas' =>  $settingsParamas,
			'usersRoleList' => $usersRoleList
		);

		return $deafultParams;
	}

	public static function getValue($optionName,$optionType) {

		$optionType = strtolower($optionType);
		$optionFunctionName = 'get'.ucfirst($optionType).'Options';
		$options = self::$optionFunctionName();
	

		if(isset($options[$optionName])) {
			return $options[$optionName];
		}
		
		$deafaultValues = self::getDefaultValues();
		$deafultSettings = $deafaultValues[$optionType.'Paramas'];

		return $deafultSettings[$optionName];
	}

	public static function getSettingsOptions() {

		global $wpdb;

		$st = $wpdb->prepare("SELECT options FROM ". $wpdb->prefix ."sg_popup_settings WHERE id = %d",1);
		$options = $wpdb->get_row($st, ARRAY_A);

		/*Option can be null when ex settings table does now exists for old users*/
		if(is_null($options)) {
			return array();
		}
		$options = json_decode($options['options'], true);

		return $options;
	}

	public static function getPopupTimeZone() {

		$options = self::getSettingsOptions();

		$popupImeZone = @$options['sg-popup-time-zone'];

		if(!isset($popupImeZone) || empty($popupImeZone)) {
			$popupImeZone = 'Asia/Yerevan';
		}
		
		return $popupImeZone;
	}

	public static function getPostsAllCategories() {

		 $cats =  get_categories(
			array(
				"hide_empty" => 0,
				"type"      => "post",      
				"orderby"   => "name",
				"order"     => "ASC"
			)
		);
		$catsParams = array();
		foreach ($cats as $cat) {

			$id = $cat->term_id;
			$name = $cat->name;
			$catsParams[$id] = $name;
		}

		return $catsParams;
	}

	public static function sgSetChecked($value) {

		if($value == '') {
			return '';
		}
		return 'checked';
	}

	public static function getAllUserRoles() {

		$rulesArray = array();
		if(!function_exists('get_editable_roles')){
			return $rulesArray;
		}

		$roles = get_editable_roles();
		foreach ($roles as $role_name => $role_info) {
			if($role_name == 'administrator') {
				continue;
			}
			$rulesArray["sgpb_".$role_name] = $role_name;

		}
		return $rulesArray;
	}

	public static function getCurrentUserRole() {
		global $current_user, $wpdb;
		$role = $wpdb->prefix . 'capabilities';
		$current_user->role = array_keys($current_user->$role);
		$role = $current_user->role[0];
		return "sgpb_".$role;
	}

	public static function getAllSubscriptionForms() {
		global $wpdb;
		$st = "SELECT title FROM ". $wpdb->prefix ."sg_popup WHERE type='subscription'";
		$subsriptionForms = $wpdb->get_results($st, ARRAY_A);
		$subsFormList = array();

		foreach ($subsriptionForms as $subsriptionForm) {
			$value = $subsriptionForm['title'];
			$subsFormList[$value] = $value;
		}
		return $subsFormList;
	}

	public static function isActivePopup($id) {
		
		$obj = SGPopup::findById($id);
		if(empty($obj)) {
			return '';
		}
		$options = $obj->getOptions();
		$options = json_decode($options, true);

		if(!isset($options['isActiveStatus']) || $options['isActiveStatus'] == 'on') {
			return "checked";
		}
		return "";
	}

	public static function getAllCustomPosts() {

		$args = array(
			'public' => true,
			'_builtin' => false
		);

		$allCustomPosts = get_post_types($args);

		return $allCustomPosts;
	}
}