<?php
require_once(SG_APP_POPUP_CLASSES.'/sgDataTable/SGPopupTable.php');
$allData = SGPopup::findAll();

if(!SG_SHOW_POPUP_REVIEW) {
	echo SGFunctions::addReview();
}
?>
<div class="wrap">
	<div class="headers-wrapper">
	<h2 class="add-new-buttons">Popups <a href="<?php echo admin_url();?>admin.php?page=create-popup" class="add-new-h2">Add New</a></h2>
		<?php if(POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_FREE): ?>
				<input type="button" class="main-update-to-pro" value="Upgrade to PRO version" onclick="window.open('<?php echo SG_POPUP_PRO_URL;?>')">
		<?php endif; ?>
		<?php if(POPUP_BUILDER_PKG != POPUP_BUILDER_PKG_FREE): ?>
			<div class="export-import-buttons-wrraper">
				<?php if(!empty($allData)):?>
					<a href= "admin-post.php?action=popup_export" ><input type="button" value="Export" class="button"></a>
				<?php endif;?>
				<input id="js-upload-export-file" class="button" type="button" value="Import"><img src="<?php echo plugins_url('img/wpAjax.gif', dirname(__FILE__).'../'); ?>" alt="gif" class="sg-hide-element js-sg-import-gif">
			</div>
			<div class="clear"></div>
		<?php endif; ?>
	</div>
	<?php
		$table = new SGPB_PopupsView();
		echo $table;
		SGFunctions::showInfo();
	?>
</div>
