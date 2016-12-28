<?php
function sgPopupMeta()
{
	$showCurrentUser = SGFunctions::isShowMenuForCurrentUser();
	if(!$showCurrentUser) {return;}

	$screens = array('post', 'page');
	foreach ( $screens as $screen ) {
		add_meta_box( 'prfx_meta', __('Select popup on page load', 'prfx-textdomain'), 'sgPopupCallback', $screen, 'normal');
	}
}
add_action('add_meta_boxes', 'sgPopupMeta');

function sgPopupCallback($post)
{
	wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
	$prfx_stored_meta = get_post_meta( $post->ID );
	?>
	<p class="preview-paragaraph">
<?php
		global $wpdb;
		$proposedTypes = array();
		$orderBy = 'id DESC';
		$proposedTypes = SGPopup::findAll($orderBy);
		function sgCreateSelect($options,$name,$selecteOption) {
			$selected ='';
			$str = "";
			$str .= "<select class=\"choose-popup-type\" name=\"$name\">";
			$str .= "<option value=''>Not selected</option>";
			foreach($options as $option)
			{
				if ($option) {
					$title = $option->getTitle();
					$type = $option->getType();
					$id = $option->getId();
					if ($selecteOption == $id) {
						$selected = "selected";
					}
					else {
						$selected ='';
					}
					$str .= "<option value='".$id."' disable='".$id."' ".$selected." >$title - $type</option>";
				}
			}
			$str .="</select>" ;
			return $str;
		}
		global $post;
		$page = (int)$post->ID;
		$popup = "sg_promotional_popup";
		$popupId = SGPopup::getPagePopupId($page,$popup);
		echo sgCreateSelect($proposedTypes,'sg_promotional_popup',$popupId);
		$SG_APP_POPUP_URL = SG_APP_POPUP_URL;
?>
	</p>
	<input type="hidden" value="<?php echo $SG_APP_POPUP_URL;?>" id="SG_APP_POPUP_URL">
<?php
}

function sgSelectPopupSaved($post_id)
{
	if(empty($_POST['sg_promotional_popup'])) {
		delete_post_meta($post_id, 'sg_promotional_popup');
		return false;
	}
	else {
		update_post_meta($post_id, 'sg_promotional_popup' , $_POST['sg_promotional_popup']);
	}
}

add_action('save_post','sgSelectPopupSaved');