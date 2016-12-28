<?php
require_once(dirname(__FILE__).'/SGPopup.php');

class SGFblikePopup extends SGPopup
{
	public $content;
	public $fblikeOptions;

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setFblikeOptions($options)
	{
		$this->fblikeOptions = $options;
	}

	public function getFblikeOptions()
	{
		return $this->fblikeOptions;
	}

	public static function create($data, $obj = null)
	{
		$obj = new self();
		$options = json_decode($data['options'], true);
		$fblikeOptions = $options['fblikeOptions'];

		$obj->setFblikeOptions($fblikeOptions);
		$obj->setContent($data['fblike']);

		return parent::create($data, $obj);
	}

	public function save($data = array())
	{

		$editMode = $this->getId()?true:false;

		$res = parent::save($data);
		if ($res===false) return false;

		$sgFblikeContent = $this->getContent();
		$fblikeOptions = $this->getFblikeOptions();

		global $wpdb;
		if ($editMode) {
			$sgFblikeContent = stripslashes($sgFblikeContent);
			$sql = $wpdb->prepare("UPDATE ".$wpdb->prefix."sg_fblike_popup SET content=%s, options=%s WHERE id=%d", $sgFblikeContent, $fblikeOptions, $this->getId());
			$res = $wpdb->query($sql);
		}
		else {

			$sql = $wpdb->prepare("INSERT INTO ".$wpdb->prefix."sg_fblike_popup (id, content, options) VALUES (%d, %s, %s)",$this->getId(),$sgFblikeContent, $fblikeOptions);
			$res = $wpdb->query($sql);
		}
		return $res;
	}

	protected function setCustomOptions($id)
	{
		global $wpdb;
		$st = $wpdb->prepare("SELECT content, options FROM ".$wpdb->prefix."sg_fblike_popup WHERE id = %d", $id);
		$arr = $wpdb->get_row($st,ARRAY_A);
		$this->setContent($arr['content']);
		$this->setFblikeOptions($arr['options']);
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

	protected function getExtraRenderOptions()
	{
		$options = json_decode($this->getFblikeOptions(), true);
		$url = $options['fblike-like-url'];
		$layout = $options['fblike-layout'];

		$content = $this->getContent();
		
		$content .= "<div id=\"sg-facebook-like\"><script type=\"text/javascript\">
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = \"//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5\";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>";
		$content .= '<div class = "sg-fb-buttons-wrapper"><div class="fb-like" data-href="'.$url.'" data-layout="'.$layout.'" data-action="like" data-show-faces="true" data-share="true"></div></div></div>';
		$content .= '<style>
			.sg-fb-buttons-wrapper{
				text-align: center;
				min-height: 25px;
			}
			#sgcboxLoadedContent iframe {
				max-width: none !important;
			}
		</style>';
		$hasShortcode = $this->hasPopupContentShortcode($content);

		if($hasShortcode) {

			$content = $this->improveContent($content);
			/*Add this part of code right into the page to escape conflicts with shortcodes init functionlity*/
			$currentPopupContent = "<div id=\"sg-popup-content-".$this->getId()."\" style=\"display: none;\">&nbsp;<div id=\"sgpb-all-content-".$this->getId()."\">".$content."</div></div>";

			add_action('wp_footer', array($this, 'addPopupContentToFooter'), 100, 1);
			do_action("wp_footer", $currentPopupContent);
			$content = ' ';
		}
		else {
			$content = trim($content);
		}
		
		return array('html'=>$content);
	}

	public function render()
	{
		return parent::render();
	}
}
