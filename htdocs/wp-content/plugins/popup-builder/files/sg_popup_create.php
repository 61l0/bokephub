<?php

if(!SG_SHOW_POPUP_REVIEW) {
	echo SGFunctions::addReview();
}

$externalPlugins = IntegrateExternalSettings::getAllExternalPlugins();
$doesntHaveAnyActiveExtensions = IntegrateExternalSettings::doesntHaveAnyActiveExtensions();
?>
<h2>Add New Popup</h2>
<div class="popups-wrapper">
	<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=image">
		<div class="popups-div image-popup">
		</div>
	</a>
	<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=html">
		<div class="popups-div html-popup">
		</div>
	</a>
	<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=fblike">
		<div class="popups-div fblike-popup">
		</div>
	</a>
	<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=shortcode">
		<div class="popups-div shortcode-popup">
		</div>
	</a>
	<?php if(POPUP_BUILDER_PKG >= POPUP_BUILDER_PKG_SILVER): ?>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=iframe">
			<div class="popups-div iframe-popup">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=video">
			<div class="popups-div video-popup">
			</div>
		</a>
		<?php if(POPUP_BUILDER_PKG > POPUP_BUILDER_PKG_SILVER): ?>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=ageRestriction">
			<div class="popups-div age-restriction">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=countdown">
			<div class="popups-div countdown">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=social">
			<div class="popups-div sg-social">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=exitIntent">
			<div class="popups-div sg-exit-intent">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=subscription">
			<div class="popups-div sg-subscription">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=contactForm">
			<div class="popups-div sg-contact-form">
			</div>
		</a>
	    <?php endif; ?>
	<?php endif; ?>
	<?php 
		if(!empty($externalPlugins)) {
			foreach ($externalPlugins as  $externalPlugin) { ?>
				<a class="create-popup-link" href="<?php echo SG_APP_POPUP_ADMIN_URL?>admin.php?page=edit-popup&type=<?php echo $externalPlugin['name']?>">
					<div class="popups-div <?php echo $externalPlugin['name'].'-image';?>">
					</div>
				</a>
			<?php }
		}
	?>
	<?php if (POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_FREE): ?>
			<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div iframe-popup-pro">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div video-popup-pro">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div age-restriction-pro">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div countdown-pro">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div social-pro">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div exit-intent-pro">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div subscription-pro">
			</div>
		</a>
		<a class="create-popup-link" href="<?php echo SG_POPUP_PRO_URL;?>" target="_blank">
			<div class="popups-div contact-pro">
			</div>
		</a>
		
	<?php endif; ?>
	
</div>

<?php

if ($doesntHaveAnyActiveExtensions) : ?>
<div class="add-new-extensions-wrapper">
  <span class="add-new-extensions">
    Extensions
  </span>
</div>
<?php
    global  $POPUP_ADDONS;
    foreach ($POPUP_ADDONS as $addonName) {
        $isExtension = IntegrateExternalSettings::isExtensionExists($addonName);
        if(!$isExtension) : ?>
            <a class="create-popup-link" href="<?php echo SG_MAILCHIMP_EXTENSION_URL;?>" target="_blank">
                <div class="popups-div" <?php echo "id='".$addonName."-pro'";?>>
                </div>
            </a>
    	<?php endif;
    } ?>
<?php endif; ?>