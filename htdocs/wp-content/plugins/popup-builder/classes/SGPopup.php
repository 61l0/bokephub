<?php

abstract class SGPopup {
	protected $id;
	protected $type;
	protected $title;
	protected $width;
	protected $height;
	protected $delay;
	protected $effectDuration;
	protected $effect;
	protected $initialWidth;
	protected $initialHeight;
	protected $options;
	public static $registeredScripts = false;
	public static $currentPopups = array();

	public function setType($type){
		$this->type = $type;
	}
	public function getType() {
		return $this->type;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	public function setWidth($width){
		$this->width = $width;
	}
	public function getWidth() {
		return $this->width;
	}
	public function setHeight($height){
		$this->height = $height;
	}
	public function getHeight() {
		return $this->height;
	}
	public function setDelay($delay){
		$this->delay = $delay;
	}
	public function getDelay() {
		return $this->delay;
	}
	public function setEffectDuration($effectDuration){
		$this->effectDuration = $effectDuration;
	}
	public function getEffectDuration() {
		return $this->effectDuration;
	}
	public function setEffect($effect){
		$this->effect = $effect;
	}
	public function getEffect() {
		return $this->effect;
	}
	public function setInitialWidth($initialWidth){
		$this->initialWidth = $initialWidth;
	}
	public function getInitialWidth() {
		return $this->initialWidth;
	}
	public function setInitialHeight($initialHeight){
		$this->initialHeight = $initialHeight;
	}
	public function getInitialHeight() {
		return $this->initialHeight;
	}
	public function setOptions($options) {
		$this->options = $options;
	}
	public function getOptions() {
		return $this->options;
	}
	public static function findById($id) {

		global $wpdb;
		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_popup WHERE id = %d",$id);
		$arr = $wpdb->get_row($st,ARRAY_A);
		if(!$arr) return false;
		return self::popupObjectFromArray($arr);

	}

	abstract protected function setCustomOptions($id);

	abstract protected function getExtraRenderOptions();

	private static function popupObjectFromArray($arr, $obj = null) {

		$jsonData = json_decode($arr['options'], true);

		$type = sgSafeStr($arr['type']);

		if ($obj===null) {
			$className = "SG".ucfirst(strtolower($type)).'Popup';
			/* get current popup app path */
			$paths = IntegrateExternalSettings::getCurrentPopupAppPaths($type);

			$popupAppPath = $paths['app-path'];
			require_once($popupAppPath.'/classes/'.$className.'.php');
			$obj = new $className();
		}

		$obj->setType(sgSafeStr($type));
		$obj->setTitle(sgSafeStr($arr['title']));
		if (@$arr['id']) $obj->setId($arr['id']);
		$obj->setWidth(sgSafeStr(@$jsonData['width']));
		$obj->setHeight(sgSafeStr(@$jsonData['height']));
		$obj->setDelay(sgSafeStr(@$jsonData['delay']));
		$obj->setEffectDuration(sgSafeStr(@$jsonData['duration']));
		$obj->setEffect(sgSafeStr($jsonData['effect']));
		$obj->setInitialWidth(sgSafeStr(@$jsonData['initialWidth']));
		$obj->setInitialHeight(sgSafeStr(@$jsonData['initialHeight']));
		$obj->setOptions(sgSafeStr($arr['options']));

		if (@$arr['id']) $obj->setCustomOptions($arr['id']);

		return $obj;
	}

	public static function create($data, $obj)
	{
		self::popupObjectFromArray($data, $obj);
		return $obj->save();
	}
	public function save($data = array()) {

		$id = $this->getId();
		$type = $this->getType();
		$title = $this->getTitle();
		$options = $this->getOptions();

		global $wpdb;

		if($id  == '') {
				$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_popup(type,title,options) VALUES (%s,%s,%s)",$type,$title,$options);
				$res = $wpdb->query($sql);


			if ($res) {
				$id = $wpdb->insert_id;
				$this->setId($id);
			}
			return $res;

		}
		else {
			$sql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_popup SET type=%s,title=%s,options=%s WHERE id=%d",$type,$title,$options,$id);
			$res = $wpdb->query($sql);
			if(!$wpdb->show_errors()) {
				$res = 1;
			}

			return $res;
		}
	}
	public static function findAll($orderBy = null, $limit = null, $offset = null) {

		global $wpdb;

		$query = "SELECT * FROM ". $wpdb->prefix ."sg_popup";

		if ($orderBy) {
			$query .= " ORDER BY ".$orderBy;
		}

		if ($limit) {
			$query .= " LIMIT ".intval($offset).','.intval($limit);
		}

		//$st = $wpdb->prepare($query, array());
		$popups = $wpdb->get_results($query, ARRAY_A);

		$arr = array();
		foreach ($popups as $popup) {
			$arr[] = self::popupObjectFromArray($popup);
		}

		return $arr;
	}
	public static function delete($id) {
			$pop = self::findById($id);
			$type =  $pop->getType();
			$table = 'sg_'.$type.'_Popup';

			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."$table WHERE id = %d"
					,$id
				)
			);
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."sg_popup WHERE id = %d"
					,$id
				)
			);

			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."postmeta WHERE meta_value = %d and meta_key = 'wp_sg_popup'"
					,$id
				)
			);
	}

	public static function setPopupForPost($post_id, $popupId) {
		update_post_meta($post_id, 'wp_sg_popup' , $popupId);
	}

	public function getRemoveOptions() {
		return array();
	}

	public function improveContent($content) {
		$hasSameShortcode = strpos($content,'id="'.$this->getId().'"');
		
		if(POPUP_BUILDER_PKG !== POPUP_BUILDER_PKG_FREE && !$hasSameShortcode) {
			require_once(SG_APP_POPUP_FILES ."/sg_popup_pro.php");
			return SgPopupPro::sgPopupExtraSanitize($content);
		}
		return $content;
	}

	public function hasPopupContentShortcode($content) {

		global $shortcode_tags;

		if(POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_FREE) {
			return false;
		}

		preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
		$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

		/* If tagnames is empty it's mean content does not have shortcode */
		if (empty($tagnames)) {
			return false;
		}
		return true;

	}

	public function render() {
		/* When have popup with same id in the same page */
		if(!in_array($this->getId(), SGPopup::$currentPopups)) {
			$parentOption = array('id'=>$this->getId(),'title'=>$this->getTitle(),'type'=>$this->getType(),'effect'=>$this->getEffect(),'width',$this->getWidth(),'height'=>$this->getHeight(),'delay'=>$this->getDelay(),'duration'=>$this->getEffectDuration(),'initialWidth',$this->getInitialWidth(),'initialHeight'=>$this->getInitialHeight());
			$getexrArray = $this->getExtraRenderOptions();
			array_push(SGPopup::$currentPopups,$this->getId());
			$options = json_decode($this->getOptions(),true);
			if(empty($options)) $options = array();
			$sgPopupVars = 'SG_POPUP_DATA['.$this->getId().'] ='.json_encode(array_merge($parentOption, $options, $getexrArray)).';';

			return $sgPopupVars;
		}
		return '';
		
	}
	public static function getTotalRowCount() {
		global $wpdb;
		$res =  $wpdb->get_var( "SELECT COUNT(id) FROM ". $wpdb->prefix ."sg_popup" );
		return $res;
	}

	public static function getPagePopupId($page,$popup) {
		global $wpdb;
		$sql = $wpdb->prepare("SELECT meta_value FROM ". $wpdb->prefix ."postmeta WHERE post_id = %d AND meta_key = %s",$page,$popup);
		$row = $wpdb->get_row($sql);
		$id = 0;
		if($row) {
			$id =  (int)@$row->meta_value;
		}
		return $id;
	}

	public static function showPopupForCounrty($popupId) {

 		$obj = SGPopup::findById($popupId);

 		if(!$obj) {
 			return true;
 		}

 		$isInArray = true;
 		$options = json_decode($obj->getOptions(), true);
 
 		$countryStatus = $options['countryStatus'];
 		$countryIso = $options['countryIso'];
 		$allowCountries = $options['allowCountries'];
 		$countryIsoArray = explode(',', $countryIso);

 		if($countryStatus) {

			$ip = SGFunctions::getUserIpAdress();

			$counrty = SGFunctions::getCountryName($ip);
 			
 			if($allowCountries == 'allow') {
				$isInArray = in_array($counrty, $countryIsoArray);
 			}

 			if($allowCountries == 'disallow') {
				$isInArray = !in_array($counrty, $countryIsoArray);
 			}
 		}
 		return $isInArray;
 	}

	public static function addPopupForAllPages($id = '', $selectedData = '', $type) {
		global $wpdb;

		$values = array();
		$insertPreapre = array();
	
		//Remove page if it is in use for another popup
		//self::deleteAllPagesPopup($selectedData);
		
		$insertQuery = "INSERT INTO ". $wpdb->prefix ."sg_popup_in_pages(popupId, pageId, type) VALUES ";

		foreach ($selectedData as $value) {
			$insertPreapre[] .= $wpdb->prepare( "(%d,%d,%s)", $id, $value, $type);
		}
		$insertQuery .= implode( ",\n", $insertPreapre );
		$res = $wpdb->query($insertQuery);
	}

	public static function removePopupFromPages($popupId, $type)
	{
		global $wpdb;
		/*Remove all pages and posts from the array*/
		self::removeFromAllPages($popupId);
		$query = $wpdb->prepare('DELETE FROM '.$wpdb->prefix.'sg_popup_in_pages WHERE popupId = %d and type=%s', $popupId, $type);
		$wpdb->query($query);
	}

	public static function removeFromAllPages($id) {
		$allPages = get_option("SG_ALL_PAGES");
		$allPosts = get_option("SG_ALL_POSTS");

		if(is_array($allPages)) {
			$key = array_search($id, $allPages);
		
			if ($key !== false) {
				unset($allPages[$key]);
			}
			update_option("SG_ALL_PAGES", $allPages);
		}
		if(is_array($allPosts)) {
			$key = array_search($id, $allPosts);
		
			if ($key !== false) {
				unset($allPosts[$key]);
			}
			update_option("SG_ALL_POSTS", $allPosts);
		}

	}

	public static function deleteAllPagesPopup($selectedPages) {
		global $wpdb;

		$deletePrepare = array();
		$deleteQuery = "DELETE FROM ". $wpdb->prefix ."sg_popup_in_pages WHERE pageId IN (";

		foreach ($selectedPages as $value) {
			$deletePrepare[] .= $wpdb->prepare("%d", $value );
		}

		$deleteQuery .= implode( ",\n", $deletePrepare ).")";

		$deleteRes = $wpdb->query($deleteQuery);
	}

	public static function findInAllSelectedPages($pageId, $type) {
		global $wpdb;

		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_popup_in_pages WHERE pageId = %d and type=%s", $pageId, $type);
		$arr = $wpdb->get_results($st, ARRAY_A);
		if(!$arr) return false;
		return $arr;
	}

}

function sgSafeStr ($param) {
	return ($param===null?'':$param);
}
