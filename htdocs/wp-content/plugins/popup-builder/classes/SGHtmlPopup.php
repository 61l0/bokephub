<?php
require_once(dirname(__FILE__).'/SGPopup.php');

class SGHtmlPopup extends SGPopup {
	public $content;

	public function setContent($content) {
		$this->content = $content;
	}
	public function getContent() {
		return $this->content;
	}
	public static function create($data, $obj = null) {
		$obj = new self();

		$obj->setContent($data['html']);

		return parent::create($data, $obj);
	}
	public function save($data = array()) {

		$editMode = $this->getId()?true:false;

		$res = parent::save($data);
		if ($res===false) return false;

		$sgHtmlPopup = $this->getContent();

		global $wpdb;
		if ($editMode) {
			$sgHtmlPopup = stripslashes($sgHtmlPopup);
			$sql = $wpdb->prepare("UPDATE ". $wpdb->prefix ."sg_html_popup SET content=%s WHERE id=%d",$sgHtmlPopup,$this->getId());
			$res = $wpdb->query($sql);
		}
		else {

			$sql = $wpdb->prepare( "INSERT INTO ". $wpdb->prefix ."sg_html_popup (id, content) VALUES (%d,%s)",$this->getId(),$sgHtmlPopup);
			$res = $wpdb->query($sql);
		}
		return $res;
	}

	protected function setCustomOptions($id) {
		global $wpdb;
		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_html_popup WHERE id = %d",$id);
		$arr = $wpdb->get_row($st,ARRAY_A);
		$this->setContent($arr['content']);
	}

	/**
	 * Add popup data to footer 
	 *
	 * @since 2.4.3
	 * 
	 * @param string $currentPopupContent popup html content
	 *
	 * @return void
	 *
	 */

	public function addPopupContentToFooter($currentPopupContent) {
	
		echo $currentPopupContent;
	}

	protected function getExtraRenderOptions() {
		$content = trim($this->getContent());
		$hasShortcode = $this->hasPopupContentShortcode($content);

		if($hasShortcode) {

			$content = $this->improveContent($content);
			/*Add this part of code right into the page to escape conflicts with shortcodes init functionlity*/
			$currentPopupContent = "<div id=\"sg-popup-content-".$this->getId()."\" style=\"display: none;\">&nbsp;<div id=\"sgpb-all-content-".$this->getId()."\">".$content."</div></div>";
			
			add_action('wp_footer', array($this, 'addPopupContentToFooter'), 100, 1);
			do_action("wp_footer", $currentPopupContent);

			$content = ' ';
		}

		return array('html' => $content);
	}

	public  function render() {
		return parent::render();
	}
}
