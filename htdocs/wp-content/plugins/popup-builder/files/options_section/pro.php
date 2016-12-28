<?php
	if (POPUP_BUILDER_PKG !== POPUP_BUILDER_PKG_FREE) {
		require_once(SG_APP_POPUP_FILES ."/sg_popup_pro.php");
	}

	$sgAllSelectedPages = @implode(',', $sgAllSelectedPages);
	$sgAllSelectedPosts = @implode(',', $sgAllSelectedPosts);
	$sgAllSelectedCustomPosts = @implode(',', $sgAllSelectedCustomPosts);
?>
<div id="pro-options">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="postbox-container-2" class="postbox-container">
			<div id="normal-sortables" class="meta-box-sortables ui-sortable">
				<div class="postbox js-advanced-postbox">
					<div class="handlediv js-advanced-title" title="Click to toggle"><br></div>
					<h3 class="hndle ui-sortable-handle js-advanced-title">
						<span>Advanced options</span>
					</h3>
					<div class="advanced-options-content">
						<span class="liquid-width">Show on selected pages:</span><input class="input-width-static js-on-all-pages" type="checkbox" name="allPagesStatus" <?php echo @$sgAllPagesStatus;?>>
						<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">Select page where popup should be shown.</span><br>
						<div class="js-all-pages-content acordion-main-div-content">
							<?php echo createRadiobuttons($pagesRadio, SG_POST_TYPE_PAGE, true, esc_html($sgAllPages), "liquid-width"); ?>
							<div class="js-pages-selectbox-content acordion-main-div-content">
								<span class="liquid-width sg-pages-title">Pages</span>
								<select class="js-all-pages js-multiselect" multiple data-slectbox="all-selected-page" data-sorce="<?php echo SG_POST_TYPE_PAGE; ?>" size="10" class="popup-builder-multiselect">

								</select>
									<img src="<?php echo plugins_url('img/wpAjax.gif', dirname(__FILE__).'../../../../'); ?>" alt="gif" class="spiner-allPages js-sg-spinner sg-hide-element js-sg-import-gif">
								<input type="hidden" class="js-sg-selected-pages" name="all-selected-page" value="<?php echo $sgAllSelectedPages;?>">
							</div>
						</div>
						<span class="liquid-width">Show on selected posts:</span><input class="input-width-static js-on-all-posts" type="checkbox" name="allPostsStatus" <?php echo @$sgAllPostsStatus;?>>
						<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">Select post where popup should be shown.</span><br>
						<div class="js-all-posts-content acordion-main-div-content">
							<?php echo createRadiobuttons($postsRadio, SG_POST_TYPE_POST, true, esc_html($sgAllPosts), "liquid-width"); ?>
							<div class="js-posts-selectbox-content acordion-main-div-content">
								<span class="liquid-width sg-pages-title">Posts</span>
								<select class="js-all-posts js-multiselect" multiple data-slectbox="all-selected-posts" data-sorce="<?php echo SG_POST_TYPE_POST; ?>" size="10" class="popup-builder-multiselect">

								</select>
								<img src="<?php echo plugins_url('img/wpAjax.gif', dirname(__FILE__).'../../../../'); ?>" alt="gif" class="spiner-allPosts js-sg-spinner sg-hide-element js-sg-import-gif">
								<input type="hidden" class="js-sg-selected-posts" name="all-selected-posts" value="<?php echo $sgAllSelectedPosts; ?>">
							</div>
							<div class="js-all-categories-content acordion-main-div-content">
								<span class="liquid-width sg-pages-title">All categories</span>
								<?php
								$categories = SgPopupGetData::getPostsAllCategories();
								echo SGFunctions::createSelectBox($categories, @$sgPostsAllCategories, array("name"=>"posts-all-categories[]","multiple"=>"multiple","size"=>10,"class"=>"popup-builder-multiselect")); ?>
							</div>
						</div>
						
						<span class="liquid-width">Show on selected custom posts:</span><input class="input-width-static js-on-all-custom-posts" type="checkbox" name="allCustomPostsStatus" <?php echo @$sgAllCustomPostsStatus;?>>
						<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">Select post where popup should be shown.</span><br>
						<div class="js-all-custom-posts-content acordion-main-div-content">
							<span class="liquid-width sg-pages-title">Custom Posts</span>
							<?php
								$allCustomPosts = SgPopupGetData::getAllCustomPosts();
								echo SGFunctions::createSelectBox($allCustomPosts, @$sgAllCustomPostsType, array("name"=>"all-custom-posts[]","multiple"=>"multiple","size"=>10,"class"=>"popup-builder-multiselect"))."<br>";
								echo createRadiobuttons($customPostsRadio, 'allCustomPosts', true, esc_html(@$sgAllCustomPosts), "liquid-width");

							?>
							<div class="js-all-custompost-content acordion-main-div-content">
								<span class="liquid-width sg-pages-title"></span><select class="js-all-custom-posts js-multiselect" multiple data-slectbox="all-selected-custom-posts" data-sorce="<?php echo SG_POST_TYPE_POST; ?>" size="10" ></select>
								<img src="<?php echo plugins_url('img/wpAjax.gif', dirname(__FILE__).'../../../../'); ?>" alt="gif" class="spiner-allCustomPosts js-sg-spinner sg-hide-element js-sg-import-gif">
								<input type="hidden" class="js-sg-selected-custom-posts" name="all-selected-custom-posts" value="<?php echo $sgAllSelectedCustomPosts; ?>">
							</div>
						</div>

						<?php if (!sgRemoveOption('onScrolling')): ?>
						<span class="liquid-width">Show while scrolling:</span><input id="js-scrolling-event-inp" class="input-width-static js-checkbox-acordion" type="checkbox" name="onScrolling" <?php echo @$sgOnScrolling;?> >
						<span id="scrollingEvent" class="dashicons dashicons-info same-image-style"></span><span class="infoScrollingEvent samefontStyle">Show the popup whenever the user scrolls the page.</span><br>
						<div class="js-scrolling-content acordion-main-div-content">
							<span class="liquid-width">Show popup after scrolling</span><input class="before-scroling-percent improveOptionsstyle" type="text" name="beforeScrolingPrsent" value="<?php echo esc_attr(@$beforeScrolingPrsent); ?>">
							<span class="span-percent">%</span>
						</div>
						<span class="liquid-width">Show after inactivity</span><input id="js-inactivity-event-inp" class="input-width-static js-checkbox-acordion" type="checkbox" name="inActivityStatus" <?php echo $sgInActivityStatus;?> >
						<span id="scrollingEvent" class="dashicons dashicons-info same-image-style"></span><span class="infoScrollingEvent samefontStyle">Show the popup whenever the user scrolls the page.</span><br>
						<div class="js-inactivity-content acordion-main-div-content">
							<span class="liquid-width">Show popup after</span><input class="improveOptionsstyle before-scroling-percent" type="number" name="inactivity-timout" value="<?php echo esc_attr(@$sgInactivityTimout); ?>">
							<span class="span-percent">Sec</span>
						</div>

						<?php endif; ?>
						<span class="liquid-width">Hide on mobile devices:</span><input class="input-width-static" type="checkbox" name="forMobile" <?php echo @$sgForMobile;?>>
						<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">Don't show the popup for mobile.</span><br>

						<span class="liquid-width">Show only on mobile devices:</span><input class="input-width-static" type="checkbox" name="openMobile" <?php echo @$sgOpenOnMobile;?> />
						<span class="dashicons dashicons-info  same-image-style"></span><span class="infoForMobile samefontStyle">If this option is active popup will not appear on mobile devices</span><br>

						<span class="liquid-width">Show popup in date range:</span><input class="input-width-static js-checkbox-acordion" type="checkbox" name="popup-timer-status" <?php echo $sgPopupTimerStatus;?>>
						<span class="dashicons dashicons-info repeatPopup same-image-style"></span><span class="infoSelectRepeat samefontStyle">Show popup for selected date range. If current date is in selected date range then popup will appear.</span><br>
						<div class="acordion-main-div-content">
							<span class="liquid-width">Start date</span><input class="popup-start-timer" type="text" name="popup-start-timer" value="<?php echo esc_attr(@$sgPopupStartTimer)?>"><br>
							<span class="liquid-width">End date</span><input class="popup-finish-timer" type="text" name="popup-finish-timer" value="<?php echo esc_attr(@$sgPopupFinishTimer)?>"><br>
						</div>
						<span class="liquid-width">Schedule:</span><input class="input-width-static js-checkbox-acordion" type="checkbox" name="popup-schedule-status" <?php echo $sgPopupScheduleStatus;?>>
						<span class="dashicons dashicons-info repeatPopup same-image-style"></span><span class="infoSelectRepeat samefontStyle">Show popup for selected date range. If current date is in selected date range then popup will appear.</span><br>
						<div class="acordion-main-div-content schedule-main-div-content">
							<div class="liquid-width sg-label-div">
							</div><div class="sg-options-content-div">
								<h3 class="sg-h3">Every</h3>
								<?php  echo SGFunctions::createSelectBox($sgWeekDaysArray, @$sgPopupScheduleStartWeeks, array('name'=>'schedule-start-weeks[]', 'class' => 'schedule-start-selectbox sg-margin0', 'multiple'=> 'multiple', 'size'=>7));?>
								<h3 class="sg-h3">From</h3>
								<input type="text" class="sg-time-picker sg-time-picker-style" name="schedule-start-time" value="<?php echo esc_attr(@$sgPopupScheduleStartTime)?>">
								<h3 class="sg-h3">To</h3>
								<input type="text" class="sg-time-picker sg-time-picker-style" name="schedule-end-time" value="<?php echo esc_attr(@$sgPopupScheduleEndTime)?>">
							</div>

						</div>
						<?php if(!sgRemoveOption('showOnlyOnce')): ?>
						<span class="liquid-width">Show popup this often:</span><input class="input-width-static js-checkbox-acordion" id="js-popup-only-once" type="checkbox" name="repeatPopup" <?php echo $sgRepeatPopup;?>>
						<span class="dashicons dashicons-info repeatPopup same-image-style"></span><span class="infoSelectRepeat samefontStyle">Show the popup to a user only once.</span><br>
						<div class="acordion-main-div-content js-popup-only-once-content">
							<span class="liquid-width">Show popup less than </span><input class="before-scroling-percent" type="number" min="1" name="popup-appear-number-limit" value="<?php echo esc_attr(@$sgPopupAppearNumberLimit); ?>">
							<span class="span-percent">for same user</span><br>
							<span class="liquid-width">Expire time</span><input class="before-scroling-percent improveOptionsstyle" type="number" min="1" name="onceExpiresTime" value="<?php echo esc_attr(@$sgOnceExpiresTime); ?>">
							<span class="span-percent">Days</span><br>
							<span class="liquid-width">Page level cookie saving</span>
							<input type="checkbox" name="save-cookie-page-level" <?php echo $sgPopupCookiePageLevel; ?>>
							<span class="dashicons dashicons-info repeatPopup same-image-style"></span><span class="infoSelectRepeat samefontStyle">If this option is checked popup's cookie will be saved for a current page.By default cookie set for all site.</span>
						</div>
						<?php endif;?>

						<span class="liquid-width">Show popup by user status:</span><input class="js-checkbox-acordion js-user-seperator" type="checkbox" name="sg-user-status" <?php echo $sgUserSeperate; ?>>
						<span class="dashicons dashicons-info repeatPopup same-image-style"></span><span class="infoSelectRepeat samefontStyle">Show Popup if the user is logged in to your site or vice versa.</span><br>
						<div class="acordion-main-div-content js-user-seperator-content">
							<span class="liquid-width">User is</span><?php echo SGFunctions::sgCreateRadioElements($usersGroup, @$sgLogedUser);?>
						</div>

						<span class="liquid-width">Disable popup closing:</span><input class="input-width-static" type="checkbox" name="disablePopup" <?php echo $sgDisablePopup;?>>
						<span class="dashicons dashicons-info same-image-style"></span><span class="infoDisablePopup samefontStyle">Disable popup closing in any possible way.</span><br>

						<span class="liquid-width">Disable popup overlay:</span><input class="input-width-static" type="checkbox" name="disablePopupOverlay" <?php echo $sgDisablePopupOverlay;?>>
						<span class="dashicons dashicons-info same-image-style"></span><span class="infoDisablePopup samefontStyle">Disable popup overlay.</span><br>

						<span class="liquid-width">Auto close popup:</span><input id="js-auto-close" class="input-width-static js-checkbox-acordion" type="checkbox" name="autoClosePopup" <?php echo $sgAutoClosePopup;?>>
						<span class="dashicons dashicons-info same-image-style"></span><span class="infoAutoClose samefontStyle">Close popup automatically.</span><br>
						<div class="js-auto-close-content acordion-main-div-content">
							<span class="liquid-width" >After</span><input class="popupTimer improveOptionsstyle" type="text" name="popupClosingTimer" value="<?php echo esc_attr(@$sgPopupClosingTimer);?>"><span class="scroll-percent">seconds</span>
						</div>
						<?php if(POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_PLATINUM): ?>
						<span class="liquid-width">Filter popup for selected countries:</span><input id="js-countris" class="input-width-static js-checkbox-acordion" type="checkbox" name="countryStatus" <?php echo @$sgCountryStatus;?>>
						<span class="dashicons dashicons-info same-image-style"></span><span class="infoAutoClose samefontStyle">Select country where popup should be shown/hidden.</span><br>
						<div class="js-countri-content">
							<span class="liquid-width" ></span><?php echo SGFunctions::sgCreateRadioElements($countriesRadio, @$sgAllowCountries);?>
							<span class="liquid-width" ></span><?php echo SGFunctions::countrisSelect(); ?>
							<input type="button" value="Add" class="addCountry">
							<span class="liquid-width"></span><input type="text" name="countryName" id="countryName" data-role="tagsinput" id="countryName" value="<?php echo esc_attr(@$sgCountryName);?>">
							<span class="liquid-width"></span><input type="hidden" name="countryIso"  id="countryIso" value="<?php echo esc_attr(@$sgCountryIso);?>">
						</div>
						<?php endif;?>
					</div>
					<?php if (POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_FREE) : ?>
						<a href="<?php echo SG_POPUP_PRO_URL; ?>" target="_blank">
							<div class="sgpb-pro pro-options-div">
								<p class="pro-options-title">PRO Features</p>
							</div>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
