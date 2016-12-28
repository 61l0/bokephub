<?php
$defaultVaules = SgPopupGetData::getDefaultValues();
$tableDeleteValue = SgPopupGetData::getValue('tables-delete-status','settings');
$usrsSelectedRoles = SgPopupGetData::getValue('plugin_users_role', 'settings');
$sgSelectedTimeZone = SgPopupGetData::getValue('sg-popup-time-zone','settings');
$tableDeleteSatatus =  SgPopupGetData::sgSetChecked($tableDeleteValue);

if (isset($_GET['saved']) && $_GET['saved']==1) {
	echo '<div id="default-message" class="updated notice notice-success is-dismissible" ><p>Popup updated.</p></div>';
}
?>
<div class="crud-wrapper">
<div class="sg-settings-wrapper">
	<div id="special-options">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox popup-builder-special-postbox">
						<div class="handlediv js-special-title" title="Click to toggle"><br></div>
						<h3 class="hndle ui-sortable-handle js-special-title">
							<span>General Settings</span>
						</h3>
						<div class="special-options-content">
							<form method="POST" action="<?php echo SG_APP_POPUP_ADMIN_URL;?>admin-post.php?action=save_settings" id="sg-settings-form">
								<span class="liquid-width">Delete popup data:</span>
								<input type="checkbox" name="tables-delete-status" <?php echo $tableDeleteSatatus;?>>
								<br><span class="liquid-width sg-aligin-with-multiselect">User role who can use plugin:</span>
								<?php echo SGFunctions::createSelectBox($defaultVaules['usersRoleList'], @$usrsSelectedRoles, array("name"=>"plugin_users_role[]","multiple"=>"multiple","class"=>"sg-selectbox","size"=>count($defaultVaules['usersRoleList']))); ?><br>
							
								<?php if(POPUP_BUILDER_PKG != POPUP_BUILDER_PKG_FREE) {
									require_once(SG_APP_POPUP_FILES ."/sg_params_arrays.php");
								 ?>
									<span class="liquid-width">Popup time zone:</span><?php echo SGFunctions::createSelectBox($sgTimeZones,@$sgSelectedTimeZone, array('name'=>'sg-popup-time-zone','class'=>'sg-selectbox'))?>
								<?php }?>
								<div class="setting-submit-wrraper">
									<input type="submit" class="button-primary" value="<?php echo 'Save Changes'; ?>">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
