<?php
	$popupType = @$_GET['type'];
	if (!$popupType) {
		$popupType = 'html';
	}
	//Get current paths for popup, for addons it different
	$paths = IntegrateExternalSettings::getCurrentPopupAppPaths($popupType);
	//Get current form action, for addons it different
	$currentActionName = IntegrateExternalSettings::getCurrentPopupAdminPostActionName($popupType);
	
	$popupAppPath = $paths['app-path'];
	$popupFilesPath = $paths['files-path'];

	$popupName = "SG".ucfirst(strtolower($popupType));
	$popupClassName = $popupName."Popup";
	require_once($popupAppPath ."/classes/".$popupClassName.".php");
	$obj = new $popupClassName();

	global $removeOptions;
	$removeOptions = $obj->getRemoveOptions();

	if (isset($_GET['id'])) {
		$id = (int)$_GET['id'];
	 	$result = call_user_func(array($popupClassName, 'findById'), $id);
	 	if (!$result) {
	 		wp_redirect(SG_APP_POPUP_ADMIN_URL."page=edit-popup&type=".$popupType."");
	 	}

		switch ($popupType) {
			case 'iframe':
				$sgPopupDataIframe = $result->getUrl();
				break;
			case 'video':
				$sgPopupDataVideo = $result->getRealUrl();
				$sgVideoOptions = $result->getVideoOptions();
				break;
			case 'image':
				$sgPopupDataImage = $result->getUrl();
				break;
			case 'html':
				$sgPopupDataHtml = $result->getContent();
				break;
			case 'fblike':
				$sgPopupDataFblike = $result->getContent();
				$sgFlikeOptions = $result->getFblikeOptions();
				break;
			case 'shortcode':
				$sgPopupDataShortcode = $result->getShortcode();
				break;
			case 'ageRestriction':
				$sgPopupAgeRestriction = ($result->getContent());
				$sgYesButton = sgSafeStr($result->getYesButton());
				$sgNoButton = sgSafeStr($result->getNoButton());
				$sgRestrictionUrl = sgSafeStr($result->getRestrictionUrl());
				break;
			case 'countdown':
				$sgCoundownContent = $result->getCountdownContent();
				$countdownOptions = json_decode(sgSafeStr($result->getCountdownOptions()),true);
				$sgCountdownNumbersBgColor = $countdownOptions['countdownNumbersBgColor'];
				$sgCountdownNumbersTextColor = $countdownOptions['countdownNumbersTextColor'];
				$sgDueDate = $countdownOptions['sg-due-date'];
				@$sgGetCountdownType = $countdownOptions['sg-countdown-type'];
				$sgCountdownLang = $countdownOptions['counts-language'];
				@$sgCountdownPosition = $countdownOptions['coundown-position'];
				@$sgSelectedTimeZone = $countdownOptions['sg-time-zone'];
				@$sgCountdownAutoclose = $countdownOptions['countdown-autoclose'];
				break;
			case 'social':
				$sgSocialContent = ($result->getSocialContent());
				$sgSocialButtons = sgSafeStr($result->getButtons());
				$sgSocialOptions = sgSafeStr($result->getSocialOptions());
				break;
			case 'exitIntent':
				$sgExitIntentContent = $result->getContent();
				$exitIntentOptions = $result->getExitIntentOptions();
				break;
			case 'subscription':
				$sgSunbscriptionContent = $result->getContent();
				$subscriptionOptions = $result->getSubscriptionOptions();
				break;
			case 'contactForm':
				$params = $result->getParams();
				$sgContactFormContent = $result->getContent();
			break;
		}

		$title = $result->getTitle();
		$jsonData = json_decode($result->getOptions(), true);
		$sgEscKey = @$jsonData['escKey'];
		$sgScrolling = @$jsonData['scrolling'];
		$sgScaling = @$jsonData['scaling'];
		$sgCloseButton = @$jsonData['closeButton'];
		$sgReposition = @$jsonData['reposition'];
		$sgOverlayClose = @$jsonData['overlayClose'];
		$sgReopenAfterSubmission = @$jsonData['reopenAfterSubmission'];
		$sgOverlayColor = @$jsonData['sgOverlayColor'];
		$sgContentBackgroundColor = @$jsonData['sg-content-background-color'];
		$sgContentClick = @$jsonData['contentClick'];
		$sgContentClickBehavior = @$jsonData['content-click-behavior'];
		$sgClickRedirectToUrl = @$jsonData['click-redirect-to-url'];
		$sgRedirectToNewTab = @$jsonData['redirect-to-new-tab'];
		$sgOpacity = @$jsonData['opacity'];
		$sgPopupFixed = @$jsonData['popupFixed'];
		$sgFixedPostion = @$jsonData['fixedPostion'];
		$sgOnScrolling = @$jsonData['onScrolling'];
		$sgInActivityStatus = @$jsonData['inActivityStatus'];
		$sgInactivityTimout = @$jsonData['inactivity-timout'];
		$beforeScrolingPrsent = @$jsonData['beforeScrolingPrsent'];
		$duration = @$jsonData['duration'];
		$delay = @$jsonData['delay'];
		$sgTheme3BorderColor = @$jsonData['sgTheme3BorderColor'];
		$sgTheme3BorderRadius = @$jsonData['sgTheme3BorderRadius'];
		$sgThemeCloseText = @$jsonData['theme-close-text'];
		$effect =@$jsonData['effect'];
		$sgInitialWidth = @$jsonData['initialWidth'];
		$sgInitialHeight = @$jsonData['initialHeight'];
		$sgWidth = @$jsonData['width'];
		$sgHeight = @$jsonData['height'];
		$sgMaxWidth = @$jsonData['maxWidth'];
		$sgMaxHeight = @$jsonData['maxHeight'];
		$sgForMobile = @$jsonData['forMobile'];
		$sgOpenOnMobile = @$jsonData['openMobile'];
		$sgAllPagesStatus = @$jsonData['allPagesStatus'];
		$sgAllPostsStatus = @$jsonData['allPostsStatus'];
		$sgAllCustomPostsStatus = @$jsonData['allCustomPostsStatus'];
		$sgPostsAllCategories = @$jsonData['posts-all-categories'];
		$sgRepeatPopup = @$jsonData['repeatPopup'];
		$sgPopupAppearNumberLimit = @$jsonData['popup-appear-number-limit'];
		$sgPopupCookiePageLevel = @$jsonData['save-cookie-page-level'];
		$sgDisablePopup = @$jsonData['disablePopup'];
		$sgDisablePopupOverlay = @$jsonData['disablePopupOverlay'];
		$sgPopupClosingTimer = @$jsonData['popupClosingTimer'];
		$sgAutoClosePopup = @$jsonData['autoClosePopup'];
		$sgCountryStatus = @$jsonData['countryStatus'];
		$sgAllSelectedPages = @$jsonData['allSelectedPages'];
		$sgAllSelectedCustomPosts = @$jsonData['allSelectedCustomPosts'];
		$sgAllPostStatus = @$jsonData['showAllPosts'];
		$sgAllSelectedPosts = @$jsonData['allSelectedPosts'];
		$sgAllowCountries = @$jsonData['allowCountries'];
		$sgAllPages = @$jsonData['showAllPages'];
		$sgAllPosts = @$jsonData['showAllPosts'];
		$sgAllCustomPosts = @$jsonData['showAllCustomPosts'];
		$sgAllCustomPostsType = @$jsonData['all-custom-posts'];
		$sgLogedUser = @$jsonData['loggedin-user'];
		$sgUserSeperate = @$jsonData['sg-user-status'];
		$sgCountryName = @$jsonData['countryName'];
		$sgCountryIso = @$jsonData['countryIso'];
		$sgPopupTimerStatus = @$jsonData['popup-timer-status'];
		$sgPopupScheduleStatus = @$jsonData['popup-schedule-status'];
		$sgPopupScheduleStartWeeks = @$jsonData['schedule-start-weeks'];
		$sgPopupScheduleStartTime = @$jsonData['schedule-start-time'];
		$sgPopupScheduleEndTime = @$jsonData['schedule-end-time'];
		$sgPopupFinishTimer = @$jsonData['popup-finish-timer'];
		$sgPopupStartTimer = @$jsonData['popup-start-timer'];
		$sgColorboxTheme = @$jsonData['theme'];
		$sgOverlayCustomClasss = @$jsonData['sgOverlayCustomClasss'];
		$sgContentCustomClasss = @$jsonData['sgContentCustomClasss'];
		$sgOnceExpiresTime = @$jsonData['onceExpiresTime'];
		$sgRestrictionAction = @$jsonData['restrictionAction'];
		$yesButtonBackgroundColor = @sgSafeStr($jsonData['yesButtonBackgroundColor']);
		$noButtonBackgroundColor = @sgSafeStr($jsonData['noButtonBackgroundColor']);
		$yesButtonTextColor = @sgSafeStr($jsonData['yesButtonTextColor']);
		$noButtonTextColor = @sgSafeStr($jsonData['noButtonTextColor']);
		$yesButtonRadius = @sgSafeStr($jsonData['yesButtonRadius']);
		$noButtonRadius = @sgSafeStr($jsonData['noButtonRadius']);
		$sgSocialOptions = json_decode(@$sgSocialOptions,true);
		$sgShareUrl = $sgSocialOptions['sgShareUrl'];
		$shareUrlType = @sgSafeStr($sgSocialOptions['shareUrlType']);
		$fbShareLabel = @sgSafeStr($sgSocialOptions['fbShareLabel']);
		$lindkinLabel = @sgSafeStr($sgSocialOptions['lindkinLabel']);
		$googLelabel = @sgSafeStr($sgSocialOptions['googLelabel']);
		$twitterLabel = @sgSafeStr($sgSocialOptions['twitterLabel']);
		$pinterestLabel = @sgSafeStr($sgSocialOptions['pinterestLabel']);
		$sgMailSubject = @sgSafeStr($sgSocialOptions['sgMailSubject']);
		$sgMailLable = @sgSafeStr($sgSocialOptions['sgMailLable']);
		$sgSocialButtons = json_decode(@$sgSocialButtons,true);
		$sgTwitterStatus = @sgSafeStr($sgSocialButtons['sgTwitterStatus']);
		$sgFbStatus = @sgSafeStr($sgSocialButtons['sgFbStatus']);
		$sgEmailStatus = @sgSafeStr($sgSocialButtons['sgEmailStatus']);
		$sgLinkedinStatus = @sgSafeStr($sgSocialButtons['sgLinkedinStatus']);
		$sgGoogleStatus = @sgSafeStr($sgSocialButtons['sgGoogleStatus']);
		$sgPinterestStatus = @sgSafeStr($sgSocialButtons['sgPinterestStatus']);
		$sgSocialTheme = @sgSafeStr($sgSocialOptions['sgSocialTheme']);
		$sgSocialButtonsSize = @sgSafeStr($sgSocialOptions['sgSocialButtonsSize']);
		$sgSocialLabel = @sgSafeStr($sgSocialOptions['sgSocialLabel']);
		$sgSocialShareCount = @sgSafeStr($sgSocialOptions['sgSocialShareCount']);
		$sgRoundButton = @sgSafeStr($sgSocialOptions['sgRoundButton']);
		$sgPushToBottom = @sgSafeStr($jsonData['pushToBottom']);
		$exitIntentOptions = json_decode(@$exitIntentOptions, true);
		$sgExitIntentTpype = @$exitIntentOptions['exit-intent-type'];
		$sgExitIntntExpire = @$exitIntentOptions['exit-intent-expire-time'];
		$sgExitIntentAlert = @$exitIntentOptions['exit-intent-alert'];
		$sgVideoOptions = json_decode(@$sgVideoOptions, true);
		$sgVideoAutoplay = $sgVideoOptions['video-autoplay'];
		$sgFlikeOptions = json_decode(@$sgFlikeOptions, true);
		$sgFblikeurl = @$sgFlikeOptions['fblike-like-url'];
		$sgFbLikeLayout = @$sgFlikeOptions['fblike-layout'];
		$subscriptionOptions = json_decode(@$subscriptionOptions, true);
		$sgSubsFirstNameStatus = $subscriptionOptions['subs-first-name-status'];
		$sgSubsLastNameStatus = $subscriptionOptions['subs-last-name-status'];
		$sgSubscriptionEmail = @$subscriptionOptions['subscription-email'];
		$sgSubsFirstName = @$subscriptionOptions['subs-first-name'];
		$sgSubsLastName = @$subscriptionOptions['subs-last-name'];
		$sgSubsButtonBgcolor = @$subscriptionOptions['subs-button-bgcolor'];
		$sgSubsBtnWidth = @$subscriptionOptions['subs-btn-width'];
		$sgSubsBtnHeight = @$subscriptionOptions['subs-btn-height'];
		$sgSubsTextHeight = @$subscriptionOptions['subs-text-height'];
		$sgSubsBtnTitle = @$subscriptionOptions['subs-btn-title'];
		$sgSubsTextInputBgcolor = @$subscriptionOptions['subs-text-input-bgcolor'];
		$sgSubsTextBordercolor = @$subscriptionOptions['subs-text-bordercolor'];
		$sgSubsTextWidth = @$subscriptionOptions['subs-text-width'];
		$sgSubsButtonColor = @$subscriptionOptions['subs-button-color'];
		$sgSubsInputsColor = @$subscriptionOptions['subs-inputs-color'];
		$sgSubsPlaceholderColor = @$subscriptionOptions['subs-placeholder-color'];
		$sgSubsValidateMessage = @$subscriptionOptions['subs-validation-message'];
		$sgSuccessMessage = @$subscriptionOptions['subs-success-message'];
		$sgSubsBtnProgressTitle = @$subscriptionOptions['subs-btn-progress-title'];
		$sgSubsTextBorderWidth = @$subscriptionOptions['subs-text-border-width'];
		$sgSubsDontShowAfterSubmitting = @$subscriptionOptions['subs-dont-show-after-submitting'];
		$contactFormOptions = json_decode(@$params, true);
		$sgContactNameLabel = @$contactFormOptions['contact-name'];
		$sgContactNameStatus = @$contactFormOptions['contact-name-status'];
		$sgShowFormToTop = @$contactFormOptions['show-form-to-top'];
		$sgContactNameRequired = @$contactFormOptions['contact-name-required'];
		$sgContactSubjectLabel = @$contactFormOptions['contact-subject'];
		$sgContactSubjectStatus = @$contactFormOptions['contact-subject-status'];
		$sgContactSubjectRequired = @$contactFormOptions['contact-subject-required'];
		$sgContactEmailLabel = @$contactFormOptions['contact-email'];
		$sgContactMessageLabel = @$contactFormOptions['contact-message'];
		$sgContactValidationMessage = @$contactFormOptions['contact-validation-message'];
		$sgContactSuccessMessage = @$contactFormOptions['contact-success-message'];
		$sgContactInputsWidth = @$contactFormOptions['contact-inputs-width'];
		$sgContactInputsHeight = @$contactFormOptions['contact-inputs-height'];
		$sgContactInputsBorderWidth = @$contactFormOptions['contact-inputs-border-width'];
		$sgContactTextInputBgcolor = @$contactFormOptions['contact-text-input-bgcolor'];
		$sgContactTextBordercolor = @$contactFormOptions['contact-text-bordercolor'];
		$sgContactInputsColor = @$contactFormOptions['contact-inputs-color'];
		$sgContactPlaceholderColor = @$contactFormOptions['contact-placeholder-color'];
		$sgContactBtnWidth = @$contactFormOptions['contact-btn-width'];
		$sgContactBtnHeight = @$contactFormOptions['contact-btn-height'];
		$sgContactBtnTitle = @$contactFormOptions['contact-btn-title'];
		$sgContactBtnProgressTitle = @$contactFormOptions['contact-btn-progress-title'];
		$sgContactButtonBgcolor = @$contactFormOptions['contact-button-bgcolor'];
		$sgContactButtonColor = @$contactFormOptions['contact-button-color'];
		$sgContactAreaWidth = @$contactFormOptions['contact-area-width'];
		$sgContactAreaHeight = @$contactFormOptions['contact-area-height'];
		$sgContactResize = @$contactFormOptions['sg-contact-resize'];
		$sgContactValidateEmail = @$contactFormOptions['contact-validate-email'];
		$sgContactResiveEmail = @$contactFormOptions['contact-receive-email'];
		$sgContactFailMessage = @$contactFormOptions['contact-fail-message'];
	}

	$dataPopupId = @$id;
	/* For layze loading get selected data */
	if(!isset($id)) {
		$dataPopupId = "-1";
	}

	$sgPopup = array(
		'escKey'=> true,
		'closeButton' => true,
		'scrolling'=> true,
		'scaling'=>false,
		'opacity'=>0.8,
		'reposition' => true,
		'width' => '640px',
		'height' => '480px',
		'initialWidth' => '300',
		'initialHeight' => '100',
		'maxWidth' => false,
		'maxHeight' => false,
		'overlayClose' => true,
		'reopenAfterSubmission' => false,
		'contentClick'=>false,
		'fixed' => false,
		'top' => false,
		'right' => false,
		'bottom' => false,
		'left' => false,
		'duration' => 1,
		'delay' => 0,
		'theme-close-text' => 'Close',
		'content-click-behavior' => 'close',
	);

	$popupProDefaultValues = array(
		'closeType' => false,
		'onScrolling' => false,
		'inactivity-timout' => '0',
		'inActivityStatus' => false,
		'video-autoplay' => false,
		'forMobile' => false,
		'openMobile' => false,
		'repetPopup' => false,
		'disablePopup' => false,
		'disablePopupOverlay' => false,
		'redirect-to-new-tab' => true,
		'autoClosePopup' => false,
		'fbStatus' => true,
		'twitterStatus' => true,
		'emailStatus' => true,
		'linkedinStatus' => true,
		'googleStatus' => true,
		'pinterestStatus' => true,
		'sgSocialLabel'=>true,
		'roundButtons'=>false,
		'sgShareUrl' => 'http://',
		'pushToBottom' => true,
		'allPages' => "all",
		'allPosts' => "all",
		'allCustomPosts' => "all",
		'allPagesStatus' => false,
		'allPostsStatus' => false,
		'allCustomPostsStatus' => false,
		'onceExpiresTime' => 7,
		'popup-appear-number-limit' => 1,
		'save-cookie-page-level' => false,
		'overlay-custom-classs' => 'sg-popup-overlay',
		'content-custom-classs' => 'sg-popup-content',
		'countryStatus' => false,
		'sg-user-status' => false,
		'allowCountries' => 'allow',
		'loggedin-user' => 'true',
		'countdownNumbersTextColor' => '',
		'countdownNumbersBgColor' => '',
		'countDownLang' => 'English',
		'popup-timer-status' => false,
		'popup-schedule-status' => false,
		'countdown-position' => true,
		'countdown-autoclose' => true,
		'time-zone' => 'Etc/GMT',
		'due-date' => date('M d y H:i', strtotime(' +1 day')),
		'popup-start-timer' => date('M d y H:i'),
		'schedule-start-time' => date("H:i"),
		'exit-intent-type' => "soft",
		'exit-intent-expire-time' => '1',
		'subs-first-name-status' => true,
		'subs-last-name-status' => true,
		'subscription-email' => 'Email *',
		'subs-first-name' => 'First name',
		'subs-last-name' => 'Last name',
		'subs-button-bgcolor' => '#239744',
		'subs-button-color' => '#FFFFFF',
		'subs-text-input-bgcolor' => '#FFFFFF',
		'subs-inputs-color' => '#000000',
		'subs-placeholder-color' => '#CCCCCC',
		'subs-text-bordercolor' => '#CCCCCC',
		'subs-btn-title' => 'Subscribe',
		'subs-text-height' => '30px',
		'subs-btn-height' => '30px',
		'subs-text-width' => '200px',
		'subs-btn-width' => '200px',
		'subs-dont-show-after-submitting' => false,
		'subs-text-border-width' => '2px',
		'subs-success-message' =>'You have successfully subscribed to the newsletter',
		'subs-validation-message' => 'This field is required.',
		'subs-btn-progress-title' => 'Please wait...',
		'contact-name' => 'Name *',
		'contact-name-required' => true,
		'contact-name-status' => true,
		'show-form-to-top' => false,
		'contact-subject-status' => true,
		'contact-subject-required' => true,
		'contact-email' => 'E-mail *',
		'contact-message' => 'Message *',
		'contact-subject' => 'Subject *',
		'contact-success-message' => 'Your message has been successfully sent.',
		'contact-btn-title' => 'Contact',
		'contact-validate-email' => 'Please enter a valid email.',
		'contact-receive-email' => get_option('admin_email'),
		'contact-fail-message' => 'Unable to send.'
	);

	$escKey = sgBoolToChecked($sgPopup['escKey']);
	$closeButton = sgBoolToChecked($sgPopup['closeButton']);
	$scrolling = sgBoolToChecked($sgPopup['scrolling']);
	$scaling = sgBoolToChecked($sgPopup['scaling']);
	$reposition	= sgBoolToChecked($sgPopup['reposition']);
	$overlayClose = sgBoolToChecked($sgPopup['overlayClose']);
	$reopenAfterSubmission = sgBoolToChecked($sgPopup['reopenAfterSubmission']);
	$contentClick = sgBoolToChecked($sgPopup['contentClick']);
	$contentClickBehavior = $sgPopup['content-click-behavior'];

	$closeType = sgBoolToChecked($popupProDefaultValues['closeType']);
	$onScrolling = sgBoolToChecked($popupProDefaultValues['onScrolling']);
	$inActivityStatus = sgBoolToChecked($popupProDefaultValues['inActivityStatus']);
	$userSeperate = sgBoolToChecked($popupProDefaultValues['sg-user-status']);
	$forMobile = sgBoolToChecked($popupProDefaultValues['forMobile']);
	$openMobile = sgBoolToChecked($popupProDefaultValues['openMobile']);
	$popupTimerStatus = sgBoolToChecked($popupProDefaultValues['popup-timer-status']);
	$popupScheduleStatus = sgBoolToChecked($popupProDefaultValues['popup-schedule-status']);
	$repetPopup = sgBoolToChecked($popupProDefaultValues['repetPopup']);
	$disablePopup = sgBoolToChecked($popupProDefaultValues['disablePopup']);
	$disablePopupOverlay = sgBoolToChecked($popupProDefaultValues['disablePopupOverlay']);
	$autoClosePopup = sgBoolToChecked($popupProDefaultValues['autoClosePopup']);
	$fbStatus = sgBoolToChecked($popupProDefaultValues['fbStatus']);
	$twitterStatus = sgBoolToChecked($popupProDefaultValues['twitterStatus']);
	$emailStatus = sgBoolToChecked($popupProDefaultValues['emailStatus']);
	$linkedinStatus = sgBoolToChecked($popupProDefaultValues['linkedinStatus']);
	$googleStatus = sgBoolToChecked($popupProDefaultValues['googleStatus']);
	$pinterestStatus = sgBoolToChecked($popupProDefaultValues['pinterestStatus']);
	$socialLabel = sgBoolToChecked($popupProDefaultValues['sgSocialLabel']);
	$roundButtons = sgBoolToChecked($popupProDefaultValues['roundButtons']);
	$countdownAutoclose = sgBoolToChecked($popupProDefaultValues['countdown-autoclose']);
	$shareUrl = $popupProDefaultValues['sgShareUrl'];
	$pushToBottom = sgBoolToChecked($popupProDefaultValues['pushToBottom']);
	$allPages = $popupProDefaultValues['allPages'];
	$allPosts = $popupProDefaultValues['allPosts'];
	$allCustomPosts = $popupProDefaultValues['allCustomPosts'];
	$allPagesStatus = sgBoolToChecked($popupProDefaultValues['allPagesStatus']);
	$allPostsStatus = sgBoolToChecked($popupProDefaultValues['allPostsStatus']);
	$allCustomPostsStatus = sgBoolToChecked($popupProDefaultValues['allCustomPostsStatus']);
	$contactNameStatus = sgBoolToChecked($popupProDefaultValues['contact-name-status']);
	$showFormToTop = sgBoolToChecked($popupProDefaultValues['show-form-to-top']);
	$contactNameRequired = sgBoolToChecked($popupProDefaultValues['contact-name-required']);
	$contactSubjectStatus = sgBoolToChecked($popupProDefaultValues['contact-subject-status']);
	$contactSubjectRequired = sgBoolToChecked($popupProDefaultValues['contact-subject-required']);
	$saveCookiePageLevel = sgBoolToChecked($popupProDefaultValues['save-cookie-page-level']);
	$onceExpiresTime = $popupProDefaultValues['onceExpiresTime'];
	$popupAppearNumberLimit = $popupProDefaultValues['popup-appear-number-limit'];
	$countryStatus = sgBoolToChecked($popupProDefaultValues['countryStatus']);
	$allowCountries = $popupProDefaultValues['allowCountries'];
	$logedUser = $popupProDefaultValues['loggedin-user'];
	$countdownNumbersTextColor = $popupProDefaultValues['countdownNumbersTextColor'];
	$countdownNumbersBgColor = $popupProDefaultValues['countdownNumbersBgColor'];
	$countdownLang = $popupProDefaultValues['countDownLang'];
	$countdownPosition = $popupProDefaultValues['countdown-position'];
	$timeZone = $popupProDefaultValues['time-zone'];
	$dueDate = $popupProDefaultValues['due-date'];
	$popupStartTimer = $popupProDefaultValues['popup-start-timer'];
	$scheduleStartTime = $popupProDefaultValues['schedule-start-time'];
	$inactivityTimout = $popupProDefaultValues['inactivity-timout'];
	$exitIntentType = $popupProDefaultValues['exit-intent-type'];
	$exitIntentExpireTime = $popupProDefaultValues['exit-intent-expire-time'];
	$subsFirstNameStatus = sgBoolToChecked($popupProDefaultValues['subs-first-name-status']);
	$subsLastNameStatus = sgBoolToChecked($popupProDefaultValues['subs-last-name-status']);
	$subscriptionEmail = $popupProDefaultValues['subscription-email'];
	$subsFirstName = $popupProDefaultValues['subs-first-name'];
	$subsLastName = $popupProDefaultValues['subs-last-name'];
	$subsButtonBgcolor = $popupProDefaultValues['subs-button-bgcolor'];
	$subsButtonColor = $popupProDefaultValues['subs-button-color'];
	$subsInputsColor = $popupProDefaultValues['subs-inputs-color'];
	$subsBtnTitle = $popupProDefaultValues['subs-btn-title'];
	$subsPlaceholderColor = $popupProDefaultValues['subs-placeholder-color'];
	$subsTextHeight = $popupProDefaultValues['subs-text-height'];
	$subsBtnHeight = $popupProDefaultValues['subs-btn-height'];
	$subsSuccessMessage = $popupProDefaultValues['subs-success-message'];
	$subsValidationMessage = $popupProDefaultValues['subs-validation-message'];
	$subsTextWidth = $popupProDefaultValues['subs-text-width'];
	$subsBtnWidth = $popupProDefaultValues['subs-btn-width'];
	$subsDontShowAfterSubmitting = $popupProDefaultValues['subs-dont-show-after-submitting'];
	$subsBtnProgressTitle = $popupProDefaultValues['subs-btn-progress-title'];
	$subsTextBorderWidth = $popupProDefaultValues['subs-text-border-width'];
	$subsTextBordercolor = $popupProDefaultValues['subs-text-bordercolor'];
	$subsTextInputBgcolor = $popupProDefaultValues['subs-text-input-bgcolor'];
	$contactName = $popupProDefaultValues['contact-name'];
	$contactEmail = $popupProDefaultValues['contact-email'];
	$contactMessage = $popupProDefaultValues['contact-message'];
	$contactSubject = $popupProDefaultValues['contact-subject'];
	$contactSuccessMessage = $popupProDefaultValues['contact-success-message'];
	$contactBtnTitle = $popupProDefaultValues['contact-btn-title'];
	$contactValidateEmail = $popupProDefaultValues['contact-validate-email'];
	$contactResiveEmail = $popupProDefaultValues['contact-receive-email'];
	$contactFailMessage = $popupProDefaultValues['contact-fail-message'];
	$overlayCustomClasss = $popupProDefaultValues['overlay-custom-classs'];
	$contentCustomClasss = $popupProDefaultValues['content-custom-classs'];
	$redirectToNewTab = $popupProDefaultValues['redirect-to-new-tab'];

	function sgBoolToChecked($var)
	{
		return ($var?'checked':'');
	}

	function sgRemoveOption($option)
	{
		global $removeOptions;
		return isset($removeOptions[$option]);
	}

	$width = $sgPopup['width'];
	$height = $sgPopup['height'];
	$opacityValue = $sgPopup['opacity'];
	$top = $sgPopup['top'];
	$right = $sgPopup['right'];
	$bottom = $sgPopup['bottom'];
	$left = $sgPopup['left'];
	$initialWidth = $sgPopup['initialWidth'];
	$initialHeight = $sgPopup['initialHeight'];
	$maxWidth = $sgPopup['maxWidth'];
	$maxHeight = $sgPopup['maxHeight'];
	$deafultFixed = $sgPopup['fixed'];
	$defaultDuration = $sgPopup['duration'];
	$defaultDelay = $sgPopup['delay'];
	$themeCloseText = $sgPopup['theme-close-text'];

	$sgCloseButton = @sgSetChecked($sgCloseButton, $closeButton);
	$sgEscKey = @sgSetChecked($sgEscKey, $escKey);
	$sgContentClick = @sgSetChecked($sgContentClick, $contentClick);
	$sgOverlayClose = @sgSetChecked($sgOverlayClose, $overlayClose);
	$sgReopenAfterSubmission = @sgSetChecked($sgReopenAfterSubmission, $reopenAfterSubmission);
	$sgReposition = @sgSetChecked($sgReposition, $reposition);
	$sgScrolling = @sgSetChecked($sgScrolling, $scrolling);
	$sgScaling = @sgSetChecked($sgScaling, $scaling);
	$sgCountdownAutoclose = @sgSetChecked($sgCountdownAutoclose, $countdownAutoclose);

	$sgCloseType = @sgSetChecked($sgCloseType, $closeType);
	$sgOnScrolling = @sgSetChecked($sgOnScrolling, $onScrolling);
	$sgInActivityStatus = @sgSetChecked($sgInActivityStatus, $inActivityStatus);
	$sgForMobile = @sgSetChecked($sgForMobile, $forMobile);
	$sgOpenOnMobile = @sgSetChecked($sgOpenOnMobile, $openMobile);
	$sgPopupCookiePageLevel = @sgSetChecked($sgPopupCookiePageLevel, $saveCookiePageLevel);
	$sgUserSeperate = @sgSetChecked($sgUserSeperate, $userSeperate);
	$sgPopupTimerStatus = @sgSetChecked($sgPopupTimerStatus, $popupTimerStatus);
	$sgPopupScheduleStatus = @sgSetChecked($sgPopupScheduleStatus, $popupScheduleStatus);
	$sgRepeatPopup = @sgSetChecked($sgRepeatPopup, $repetPopup);
	$sgDisablePopup = @sgSetChecked($sgDisablePopup, $disablePopup);
	$sgDisablePopupOverlay = @sgSetChecked($sgDisablePopupOverlay, $disablePopupOverlay);
	$sgAutoClosePopup = @sgSetChecked($sgAutoClosePopup, $autoClosePopup);
	$sgFbStatus = @sgSetChecked($sgFbStatus, $fbStatus);
	$sgTwitterStatus = @sgSetChecked($sgTwitterStatus, $twitterStatus);
	$sgEmailStatus = @sgSetChecked($sgEmailStatus, $emailStatus);
	$sgLinkedinStatus = @sgSetChecked($sgLinkedinStatus, $linkedinStatus);
	$sgGoogleStatus = @sgSetChecked($sgGoogleStatus, $googleStatus);
	$sgPinterestStatus = @sgSetChecked($sgPinterestStatus, $pinterestStatus);
	$sgRoundButtons = @sgSetChecked($sgRoundButton, $roundButtons);
	$sgSocialLabel = @sgSetChecked($sgSocialLabel, $socialLabel);
	$sgPopupFixed = @sgSetChecked($sgPopupFixed, $deafultFixed);
	$sgPushToBottom = @sgSetChecked($sgPushToBottom, $pushToBottom);
	
	$sgAllPagesStatus = @sgSetChecked($sgAllPagesStatus, $allPagesStatus);
	$sgAllPostsStatus = @sgSetChecked($sgAllPostsStatus, $allPostsStatus);
	$sgAllCustomPostsStatus = @sgSetChecked($sgAllCustomPostsStatus, $allCustomPostsStatus);
	$sgCountdownPosition = @sgSetChecked($sgCountdownPosition, $countdownPosition);
	$sgVideoAutoplay = @sgSetChecked($sgVideoAutoplay, $videoAutoplay);
	$sgSubsLastNameStatus = @sgSetChecked($sgSubsLastNameStatus, $subsLastNameStatus);
	$sgSubsFirstNameStatus = @sgSetChecked($sgSubsFirstNameStatus, $subsFirstNameStatus);
	$sgSubsDontShowAfterSubmitting = @sgSetChecked($sgSubsDontShowAfterSubmitting, $subsDontShowAfterSubmitting);
	$sgCountryStatus = @sgSetChecked($sgCountryStatus, $countryStatus);
	/* Contact popup otions */
	$sgContactNameStatus = @sgSetChecked($sgContactNameStatus, $contactNameStatus);
	$sgContactNameRequired = @sgSetChecked($sgContactNameRequired, $contactNameRequired);
	$sgContactSubjectStatus = @sgSetChecked($sgContactSubjectStatus, $contactSubjectStatus);
	$sgContactSubjectRequired = @sgSetChecked($sgContactSubjectRequired, $contactSubjectRequired);
	$sgShowFormToTop = @sgSetChecked($sgShowFormToTop, $showFormToTop);
	$sgRedirectToNewTab = @sgSetChecked($sgRedirectToNewTab, $redirectToNewTab);

	function sgSetChecked($optionsParam,$defaultOption)
	{
		if (isset($optionsParam)) {
			if ($optionsParam == '') {
				return '';
			}
			else {
				return 'checked';
			}
		}
		else {
			return $defaultOption;
		}
	}

	$sgOpacity = @sgGetValue($sgOpacity, $opacityValue);
	$sgWidth = @sgGetValue($sgWidth, $width);
	$sgHeight = @sgGetValue($sgHeight, $height);
	$sgInitialWidth = @sgGetValue($sgInitialWidth, $initialWidth);
	$sgInitialHeight = @sgGetValue($sgInitialHeight, $initialHeight);
	$sgMaxWidth = @sgGetValue($sgMaxWidth, $maxWidth);
	$sgMaxHeight = @sgGetValue($sgMaxHeight, $maxHeight);
	$sgThemeCloseText = @sgGetValue($sgThemeCloseText, $themeCloseText);
	$duration = @sgGetValue($duration, $defaultDuration);
	$sgOnceExpiresTime = @sgGetValue($sgOnceExpiresTime, $onceExpiresTime);
	$sgPopupAppearNumberLimit = @sgGetValue($sgPopupAppearNumberLimit, $popupAppearNumberLimit);
	$delay = @sgGetValue($delay, $defaultDelay);
	$sgInactivityTimout = @sgGetValue($sgInactivityTimout, $inactivityTimout);
	$sgContentClickBehavior = @sgGetValue($sgContentClickBehavior, $contentClickBehavior);
	$sgPopupStartTimer = @sgGetValue($sgPopupStartTimer, $popupStartTimer);
	$sgPopupFinishTimer = @sgGetValue($sgPopupFinishTimer, '');
	$sgPopupScheduleStartTime = @sgGetValue($sgPopupScheduleStartTime, $scheduleStartTime);
	$sgPopupDataIframe = @sgGetValue($sgPopupDataIframe, 'http://');
	$sgShareUrl = @sgGetValue($sgShareUrl, $shareUrl);
	$sgPopupDataHtml = @sgGetValue($sgPopupDataHtml, '');
	$sgPopupDataImage = @sgGetValue($sgPopupDataImage, '');
	$sgAllowCountries = @sgGetValue($sgAllowCountries, $allowCountries);
	$sgAllPages = @sgGetValue($sgAllPages, $allPages);
	$sgAllPosts = @sgGetValue($sgAllPosts, $allPosts);
	$sgAllCustomPosts = @sgGetValue($sgAllCustomPosts, $allCustomPosts);
	$sgLogedUser = @sgGetValue($sgLogedUser, $logedUser);
	$sgCountdownNumbersTextColor = @sgGetValue($sgCountdownNumbersTextColor, $countdownNumbersTextColor);
	$sgCountdownNumbersBgColor = @sgGetValue($sgCountdownNumbersBgColor, $countdownNumbersBgColor);
	$sgCountdownLang = @sgGetValue($sgCountdownLang, $countdownLang);
	$sgSelectedTimeZone  = @sgGetValue($sgSelectedTimeZone, $timeZone);
	$sgDueDate = @sgGetValue($sgDueDate, $dueDate);
	$sgExitIntentTpype = @sgGetValue($sgExitIntentTpype, $exitIntentType);
	$sgExitIntntExpire = @sgGetValue($sgExitIntntExpire, $exitIntentExpireTime);
	$sgSubsTextWidth = @sgGetValue($sgSubsTextWidth, $subsTextWidth);
	$sgSubsBtnWidth = @sgGetValue($sgSubsBtnWidth, $subsBtnWidth);
	$sgSubsTextInputBgcolor = @sgGetValue($sgSubsTextInputBgcolor, $subsTextInputBgcolor);
	$sgSubsButtonBgcolor  = @sgGetValue($sgSubsButtonBgcolor, $subsButtonBgcolor);
	$sgSubsTextBordercolor = @sgGetValue($sgSubsTextBordercolor, $subsTextBordercolor);
	$sgSubscriptionEmail = @sgGetValue($sgSubscriptionEmail, $subscriptionEmail);
	$sgSubsFirstName = @sgGetValue($sgSubsFirstName, $subsFirstName);
	$sgSubsLastName = @sgGetValue($sgSubsLastName, $subsLastName);
	$sgSubsButtonColor = @sgGetValue($sgSubsButtonColor, $subsButtonColor);
	$sgSubsInputsColor = @sgGetValue($sgSubsInputsColor, $subsInputsColor);
	$sgSubsBtnTitle = @sgGetValue($sgSubsBtnTitle, $subsBtnTitle);
	$sgSubsPlaceholderColor = @sgGetValue($sgSubsPlaceholderColor, $subsPlaceholderColor);
	$sgSubsTextHeight = @sgGetValue($sgSubsTextHeight, $subsTextHeight);
	$sgSubsBtnHeight = @sgGetValue($sgSubsBtnHeight, $subsBtnHeight);
	$sgSuccessMessage = @sgGetValue($sgSuccessMessage, $subsSuccessMessage);
	$sgSubsValidateMessage = @sgGetValue($sgSubsValidateMessage, $subsValidationMessage);
	$sgSubsBtnProgressTitle = @sgGetValue($sgSubsBtnProgressTitle, $subsBtnProgressTitle);
	$sgSubsTextBorderWidth = @sgGetValue($sgSubsTextBorderWidth, $subsTextBorderWidth);
	$sgContactNameLabel = @sgGetValue($sgContactNameLabel, $contactName);
	$sgContactSubjectLabel = @sgGetValue($sgContactSubjectLabel, $contactSubject);
	$sgContactEmailLabel = @sgGetValue($sgContactEmailLabel, $contactEmail);
	$sgContactMessageLabel = @sgGetValue($sgContactMessageLabel, $contactMessage);
	$sgContactValidationMessage = @sgGetValue($sgContactValidationMessage, $subsValidationMessage);
	$sgContactSuccessMessage = @sgGetValue($sgContactSuccessMessage, $contactSuccessMessage);
	$sgContactInputsWidth = @sgGetValue($sgContactInputsWidth, $subsTextWidth);
	$sgContactInputsHeight = @sgGetValue($sgContactInputsHeight, $subsTextHeight);
	$sgContactInputsBorderWidth = @sgGetValue($sgContactInputsBorderWidth, $subsTextBorderWidth);
	$sgContactTextInputBgcolor = @sgGetValue($sgContactTextInputBgcolor, $subsTextInputBgcolor);
	$sgContactTextBordercolor = @sgGetValue($sgContactTextBordercolor, $subsTextBordercolor);
	$sgContactInputsColor = @sgGetValue($sgContactInputsColor, $subsInputsColor);
	$sgContactPlaceholderColor = @sgGetValue($sgContactPlaceholderColor, $subsPlaceholderColor);
	$sgContactBtnWidth = @sgGetValue($sgContactBtnWidth, $subsBtnWidth);
	$sgContactBtnHeight = @sgGetValue($sgContactBtnHeight, $subsBtnHeight);
	$sgContactBtnTitle = @sgGetValue($sgContactBtnTitle, $contactBtnTitle);
	$sgContactBtnProgressTitle = @sgGetValue($sgContactBtnProgressTitle, $subsBtnProgressTitle);
	$sgContactButtonBgcolor = @sgGetValue($sgContactButtonBgcolor, $subsButtonBgcolor);
	$sgContactButtonColor = @sgGetValue($sgContactButtonColor, $subsButtonColor);
	$sgContactAreaWidth = @sgGetValue($sgContactAreaWidth, $subsTextWidth);
	$sgContactAreaHeight = @sgGetValue($sgContactAreaHeight, '');
	$sgContactValidateEmail = @sgGetValue($sgContactValidateEmail, $contactValidateEmail);
	$sgContactResiveEmail = @sgGetValue($sgContactResiveEmail, $contactResiveEmail);
	$sgContactFailMessage = @sgGetValue($sgContactFailMessage, $contactFailMessage);
	$sgOverlayCustomClasss = @sgGetValue($sgOverlayCustomClasss, $overlayCustomClasss);
	$sgContentCustomClasss = @sgGetValue($sgContentCustomClasss, $contentCustomClasss);
	$sgAllSelectedPages = @sgGetValue($sgAllSelectedPages, array());
	$sgAllSelectedPosts = @sgGetValue($sgAllSelectedPosts, array());
	$sgAllSelectedCustomPosts = @sgGetValue($sgAllSelectedCustomPosts, array());
	
	function sgGetValue($getedVal,$defValue)
	{
		if (!isset($getedVal)) {
			return $defValue;
		}
		else {
			return $getedVal;
		}
	}

	$radioElements = array(
		array(
			'name'=>'shareUrlType',
			'value'=>'activeUrl',
			'additionalHtml'=>''.'<span>'.'Use active URL'.'</span></span>
							<span class="span-width-static"></span><span class="dashicons dashicons-info scrollingImg sameImageStyle sg-active-url"></span><span class="info-active-url samefontStyle">If this option is active Share URL will be current page URL.</span>'
		),
		array(
			'name'=>'shareUrlType',
			'value'=>'shareUrl',
			'additionalHtml'=>''.'<span>'.'Share url'.'</span></span>'.' <input class="input-width-static sg-active-url" type="text" name="sgShareUrl" value="'.@$sgShareUrl.'">'
		)
	);

	$countriesRadio = array(
		array(
			'name'=>'allowCountries',
			'value'=>'allow',
			'additionalHtml'=>'<span class="countries-radio-text allow-countries">Allow</span>',
			'newline' => false
		),
		array(
			'name'=>'allowCountries',
			'value'=>'disallow',
			'additionalHtml'=>'<span class="countries-radio-text">Disallow</span>',
			'newline' => true
		)
	);

	$usersGroup = array(
		array(
			'name'=>'loggedin-user',
			'value'=>'true',
			'additionalHtml'=>'<span class="countries-radio-text allow-countries">logged in</span>',
			'newline' => false
		),
		array(
			'name'=>'loggedin-user',
			'value'=>'false',
			'additionalHtml'=>'<span class="countries-radio-text">not logged in</span>',
			'newline' => true
		)
	);

	function sgCreateRadioElements($radioElements,$checkedValue)
	{
		$content = '';
		for ($i = 0; $i < count($radioElements); $i++) {
			$checked = '';
			$radioElement = @$radioElements[$i];
			$name = @$radioElement['name'];
			$label = @$radioElement['label'];
			$value = @$radioElement['value'];
			$additionalHtml = @$radioElement['additionalHtml'];
			if ($checkedValue == $value) {
				$checked = 'checked';
			}
			$content .= '<span  class="liquid-width"><input class="radio-btn-fix" type="radio" name="'.$name.'" value="'.$value.'" '.$checked.'>';
			$content .= $additionalHtml."<br>";
		}
		return $content;
	}

	$contentClickOptions = array(
		array(
			"title" => "Close Popup:",
			"value" => "close",
			"info" => ""
		),
		array(
			"title" => "Redirect:",
			"value" => "redirect",
			"info" => ""
		)
	);

	$pagesRadio = array(
		array(
			"title" => "Show on all pages:",
			"value" => "all",
			"info" => ""
		),
		array(
			"title" => "Show on selected pages:",
			"value" => "selected",
			"info" => "",
			"data-attributes" => array(
				"data-name" => SG_POST_TYPE_PAGE,
				"data-popupid" => $dataPopupId,
				"data-loading-number" => 0,
				"data-selectbox-role" => "js-all-pages"
			)
		)
	);

	$postsRadio = array(
		array(
			"title" => "Show on all posts:",
			"value" => "all",
			"info" => ""
		),
		array(
			"title" => "Show on selected post:",
			"value" => "selected",
			"info" => "",
			"data-attributes" => array(
				"data-name" => SG_POST_TYPE_POST,
				"data-popupid" => $dataPopupId,
				"data-loading-number" => 0,
				"data-selectbox-role" => "js-all-posts"
			)
							
		),
		array(
			"title" => "Show on selected categories",
			"value" => "allCategories",
			"info" => "",
			"data-attributes" => array(
				"class" => 'js-all-categories'
			)
		)
	);

	$customPostsRadio = array(
		array(
			"title" => "Show on all custom posts:",
			"value" => "all",
			"info" => ""
		),
		array(
			"title" => "Show on selected custom post:",
			"value" => "selected",
			"info" => "",
			"data-attributes" => array(
				"data-name" => 'allCustomPosts',
				"data-popupid" => $dataPopupId,
				"data-loading-number" => 0,
				"data-selectbox-role" => "js-all-custom-posts"
			)
							
		)
	);

	$radiobuttons = array(
		array(
				"title" => "Soft mode:",
				"value" => "soft",
				"info" => "<span class=\"dashicons dashicons-info repositionImg sameImageStyle\"></span>
							<span class='infoReposition samefontStyle'>
								If the user navigate away from the site the popup will appear.
							</span>"
			),
		array(
				"title" => "Aggressive mode:",
				"value" => "aggressive",
				"info" => "<span class=\"dashicons dashicons-info repositionImg sameImageStyle\"></span>
							<span class='infoReposition samefontStyle'>
								If the user try to navigate elsewhere he/she will be interrupted and forced to read the message and choose to leave or stay.
								After the alert box popup will appear.
							</span>"
			),
		array(
				"title" => "Soft and Aggressive modes:",
				"value" => "softAndAgressive",
				"info" => "<span class=\"dashicons dashicons-info repositionImg sameImageStyle\"></span>
							<span class='infoReposition samefontStyle'>
								This will enable the both modes. Depends which action will be triggered first.
							</span>"
			),
		array(
				"title" => "Aggressive without popup:",
				"value" => "agresiveWithoutPopup",
				"info" => "<span class='dashicons dashicons-info repositionImg sameImageStyle'></span>
							<span class='infoReposition samefontStyle'>
								Tha same as aggressive mode but without a popup.
							</span>"
			),

	);


	function createRadiobuttons($elements, $name, $newLine, $selectedInput, $class)
	{
		$str = "";
		
		foreach ($elements as $key => $element) {
			$breakLine = "";
			$infoIcon = "";
			$title = "";
			$value = "";
			$infoIcon = "";
			$checked = "";

			if(isset($element["title"])) {
				$title = $element["title"];
			}
			if(isset($element["value"])) {
				$value = $element["value"];
			}
			if($newLine) {
				$breakLine = "<br>";
			}
			if(isset($element["info"])) {
				$infoIcon = $element['info'];
			}
			if($element["value"] == $selectedInput) {
				$checked = "checked";
			}
			$attrStr = '';
			if(isset($element['data-attributes'])) {
				foreach ($element['data-attributes'] as $key => $dataValue) {
					$attrStr .= $key.'="'.$dataValue.'" ';
				}
			}

			$str .= "<span class=".$class.">".$element['title']."</span>
				<input type=\"radio\" name=".$name." ".$attrStr." value=".$value." $checked>".$infoIcon.$breakLine;
		}

		echo $str;
	}

	$sgPopupEffects = array(
		'No effect' => 'No Effect',
		'flip' => 'flip',
		'shake' => 'shake',
		'wobble' => 'wobble',
		'swing' => 'swing',
		'flash' => 'flash',
		'bounce' => 'bounce',
		'bounceIn' => 'bounceIn',
		'pulse' => 'pulse',
		'rubberBand' => 'rubberBand',
		'tada' => 'tada',
		'slideInUp' => 'slideInUp',
		'jello' => 'jello',
		'rotateIn' => 'rotateIn',
		'fadeIn' => 'fadeIn'
	);

	$sgPopupTheme = array(
		'colorbox1.css',
		'colorbox2.css',
		'colorbox3.css',
		'colorbox4.css',
		'colorbox5.css',
		'colorbox6.css'
	);

	$sgFbLikeButtons = array(
		'standard' => 'Standard',
		'box_count' => 'Box with count',
		'button_count' => 'Button with count',
		'button' => 'Button'
	);

	$sgTheme = array(
		'flat' => 'flat',
		'classic' => 'classic',
		'minima' => 'minima',
		'plain' => 'plain'
	);

	$sgThemeSize = array(
		'8' => '8',
		'10' => '10',
		'12' => '12',
		'14' => '14',
		'16' => '16',
		'18' => '18',
		'20' => '20',
		'24' => '24'
	);

	$sgSocialCount = array(
		'true' => 'True',
		'false' => 'False',
		'inside' => 'Inside'
	);

	$sgCountdownType = array(
		1 => 'DD:HH:MM:SS',
		2 => 'DD:HH:MM'
	);

	$sgCountdownlang = array(
		'English' => 'English',
		'German' => 'German',
		'Spanish' => 'Spanish',
		'Arabic' => 'Arabic',
		'Italian' => 'Italian',
		'Italian' => 'Italian',
		'Dutch' => 'Dutch',
		'Norwegian' => 'Norwegian',
		'Portuguese' => 'Portuguese',
		'Russian' => 'Russian',
		'Swedish' => 'Swedish',
		'Chinese' => 'Chinese'
	);

	$sgExitIntentSelectOptions = array(
		"perSesion" => "per Session",
		"1" => "per minute",
		"10080" => "per 7 days",
		"43200" => "per month",
		"always" => "always"
	);
	$sgTextAreaResizeOptions = array(
		'both' => 'Both',
		'horizontal' => 'Horizontal',
		'vertical' => 'Vertical',
		'none' => 'None',
		'inherit' => 'Inherit'
	);

	$sgWeekDaysArray = array(
		'Mon' => 'Monday',
		'Tue' => 'Tuesday',
		'Wed' => 'Wendnesday',
		'Thu' => 'Thursday',
		'Fri' => 'Friday',
		'Sat' => 'Saturday',
		'Sun' => 'Sunday'
	);

	if (POPUP_BUILDER_PKG != POPUP_BUILDER_PKG_FREE) {
		require_once(SG_APP_POPUP_FILES ."/sg_params_arrays.php");
	}

	function sgCreateSelect($options,$name,$selecteOption)
	{
		$selected ='';
		$str = "";
		$checked = "";
		if ($name == 'theme' || $name == 'restrictionAction') {

				$popup_style_name = 'popup_theme_name';
				$firstOption = array_shift($options);
				$i = 1;
				foreach ($options as $key) {
					$checked ='';

					if ($key == $selecteOption) {
						$checked = "checked";
					}
					$i++;
					$str .= "<input type='radio' name=\"$name\" value=\"$key\" $checked class='popup_theme_name' sgPoupNumber=".$i.">";

				}
				if ($checked == ''){
					$checked = "checked";
				}
				$str = "<input type='radio' name=\"$name\" value=\"".$firstOption."\" $checked class='popup_theme_name' sgPoupNumber='1'>".$str;
				return $str;
		}
		else {
			@$popup_style_name = ($popup_style_name) ? $popup_style_name : '';
			$str .= "<select name=$name class=$popup_style_name input-width-static >";
			foreach ($options as $key=>$option) {
				if ($key == $selecteOption) {
					$selected = "selected";
				}
				else {
					$selected ='';
				}
				$str .= "<option value='".$key."' ".$selected." >$option</potion>";
			}

			$str .="</select>" ;
			return $str;

		}

	}
	
	if(!SG_SHOW_POPUP_REVIEW) {
		echo SGFunctions::addReview();
	}

	if (isset($_GET['saved']) && $_GET['saved']==1) {
		echo '<div id="default-message" class="updated notice notice-success is-dismissible" ><p>Popup updated.</p></div>';
	}
	if (isset($_GET["titleError"])): ?>
		<div class="error notice" id="title-error-message">
			<p>Invalid Title</p>
		</div>
	<?php endif; ?>
<form method="POST" action="<?php echo SG_APP_POPUP_ADMIN_URL;?>admin-post.php" id="add-form">
<input type="hidden" name="action" value="<?php echo $currentActionName;?>">
	<div class="crud-wrapper">
		<div class="cereate-title-wrapper">
			<div class="sg-title-crud">
				<?php if (isset($id)): ?>
					<h2>Edit popup</h2>
				<?php else: ?>
					<h2>Create new popup</h2>
				<?php endif; ?>
			</div>
			<div class="button-wrapper">
				<p class="submit">
					<?php if (POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_FREE): ?>
						<input class="crud-to-pro" type="button" value="Upgrade to PRO version" onclick="window.open('<?php echo SG_POPUP_PRO_URL;?>')"><div class="clear"></div>
					<?php endif; ?>
					<input type="submit" id="sg-save-button" class="button-primary" value="<?php echo 'Save Changes'; ?>">
				</p>
			</div>
		</div>
		<div class="clear"></div>
		<div class="general-wrapper">
		<div id="titlediv">
			<div id="titlewrap">
				<input  id="title" class="sg-js-popup-title" type="text" name="title" size="30" value="<?php echo esc_attr(@$title)?>" spellcheck="true" autocomplete="off" required = "required"  placeholder='Enter title here'>
			</div>
		</div>
			<div id="left-main-div">
				<div id="sg-general">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="postbox-container-2" class="postbox-container">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div class="postbox popupBuilder_general_postbox sgSameWidthPostBox" style="display: block;">
									<div class="handlediv generalTitle" title="Click to toggle"><br></div>
									<h3 class="hndle ui-sortable-handle generalTitle" style="cursor: pointer"><span>General</span></h3>
									<div class="generalContent sgSameWidthPostBox">
										<?php require_once($popupFilesPath."/main_section/".$popupType.".php");?>
										<input type="hidden" name="type" value="<?php echo $popupType;?>">
										<span class="liquid-width" id="theme-span">Popup theme:</span>
										<?php echo  sgCreateSelect($sgPopupTheme,'theme',esc_html(@$sgColorboxTheme));?>
										<div class="theme1 sg-hide"></div>
										<div class="theme2 sg-hide"></div>
										<div class="theme3 sg-hide"></div>
										<div class="theme4 sg-hide"></div>
										<div class="theme5 sg-hide"></div>
										<div class="theme6 sg-hide"></div>
										<div class="sg-popup-theme-3 themes-suboptions sg-hide">
											<span class="liquid-width">Border color:</span>
											<div id="color-picker"><input  class="sgOverlayColor" id="sgOverlayColor" type="text" name="sgTheme3BorderColor" value="<?php echo esc_attr(@$sgTheme3BorderColor); ?>" /></div>
											<br><span class="liquid-width">Border radius:</span>
											<input class="input-width-percent" type="number" min="0" max="50" name="sgTheme3BorderRadius" value="<?php echo esc_attr(@$sgTheme3BorderRadius); ?>">
											<span class="span-percent">%</span>
										</div>
										<div class="sg-popup-theme-4 themes-suboptions sg-hide">
											<span class="liquid-width">Close button text:</span>
											<input type="text" name="theme-close-text" value="<?php echo esc_attr($sgThemeCloseText);?>">
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div id="effect">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="postbox-container-2" class="postbox-container">
							<div id="normal-sortables" class="meta-box-sortables ui-sortable">
								<div class="postbox popupBuilder_effect_postbox sgSameWidthPostBox" style="display: block;">
									<div class="handlediv effectTitle" title="Click to toggle"><br></div>
									<h3 class="hndle ui-sortable-handle effectTitle" style="cursor: pointer"><span>Effects</span></h3>
									<div class="effectsContent">
										<span class="liquid-width">Effect type:</span>
										<?php echo  sgCreateSelect($sgPopupEffects,'effect',esc_html(@$effect));?>
										<span class="js-preview-effect"></span>
										<div class="effectWrapper"><div id="effectShow" ></div></div>

										<span  class="liquid-width">Effect duration:</span>
										<input class="input-width-static" type="text" name="duration" value="<?php echo esc_attr($duration); ?>" pattern = "\d+" title="It must be number" /><span class="dashicons dashicons-info contentClick infoImageDuration sameImageStyle"></span><span class="infoDuration samefontStyle">Specify how long the popup appearance animation should take (in sec).</span></br>

										<span  class="liquid-width">Popup opening delay:</span>
										<input class="input-width-static" type="text" name="delay" value="<?php echo esc_attr($delay);?>"  pattern = "\d+" title="It must be number"/><span class="dashicons dashicons-info contentClick infoImageDelay sameImageStyle"></span><span class="infoDelay samefontStyle">Specify how long the popup appearance should be delayed after loading the page (in sec).</span></br>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			<?php require_once($popupFilesPath."/options_section/".$popupType.".php");?>
			</div>
			<div id="right-main-div">
				<div id="right-main">
					<div id="dimentions">
						<div id="post-body" class="metabox-holder columns-2">
							<div id="postbox-container-2" class="postbox-container">
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<div class="postbox popupBuilder_dimention_postbox sgSameWidthPostBox" style="display: block;">
										<div class="handlediv dimentionsTitle" title="Click to toggle"><br></div>
										<h3 class="hndle ui-sortable-handle dimentionsTitle" style="cursor: pointer"><span>Dimensions</span></h3>
										<div class="dimensionsContent">
											<span class="liquid-width">Width:</span>
											<input class="input-width-static" type="text" name="width" value="<?php echo esc_attr($sgWidth); ?>"  pattern = "\d+(([px]+|\%)|)" title="It must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span class="liquid-width">Height:</span>
											<input class="input-width-static" type="text" name="height" value="<?php echo esc_attr($sgHeight);?>" pattern = "\d+(([px]+|\%)|)" title="It must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span class="liquid-width">Max width:</span>
											<input class="input-width-static" type="text" name="maxWidth" value="<?php echo esc_attr($sgMaxWidth);?>"  pattern = "\d+(([px]+|\%)|)" title="It must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span class="liquid-width">Max height:</span>
											<input class="input-width-static" type="text" name="maxHeight" value="<?php echo esc_attr(@$sgMaxHeight);?>"   pattern = "\d+(([px]+|\%)|)" title="It must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span class="liquid-width">Initial width:</span>
											<input class="input-width-static" type="text" name="initialWidth" value="<?php echo esc_attr($sgInitialWidth);?>"  pattern = "\d+(([px]+|\%)|)" title="It must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
											<span class="liquid-width">Initial height:</span>
											<input class="input-width-static" type="text" name="initialHeight" value="<?php echo esc_attr($sgInitialHeight);?>"  pattern = "\d+(([px]+|\%)|)" title="It must be number  + px or %" /><img class='errorInfo' src="<?php echo plugins_url('img/info-error.png', dirname(__FILE__).'../') ?>"><span class="validateError">It must be a number + px or %</span><br>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<div id="options">
						<div id="post-body" class="metabox-holder columns-2">
							<div id="postbox-container-2" class="postbox-container">
								<div id="normal-sortables" class="meta-box-sortables ui-sortable">
									<div class="postbox popupBuilder_options_postbox sgSameWidthPostBox" style="display: block;">
										<div class="handlediv optionsTitle" title="Click to toggle"><br></div>
										<h3 class="hndle ui-sortable-handle optionsTitle" style="cursor: pointer"><span>Options</span></h3>
										<div class="optionsContent">
											<span class="liquid-width">Dismiss on &quot;esc&quot; key:</span><input class="input-width-static" type="checkbox" name="escKey"  <?php echo $sgEscKey;?>/>
											<span class="dashicons dashicons-info escKeyImg sameImageStyle"></span><span class="infoEscKey samefontStyle">The popup will be dismissed when user presses on 'esc' key.</span></br>

											<span class="liquid-width" id="createDescribeClose">Show &quot;close&quot; button:</span><input class="input-width-static" type="checkbox" name="closeButton" <?php echo $sgCloseButton;?> />
											<span class="dashicons dashicons-info CloseImg sameImageStyle"></span><span class="infoCloseButton samefontStyle">The popup will contain 'close' button.</span><br>

											<span class="liquid-width">Enable content scrolling:</span><input class="input-width-static" type="checkbox" name="scrolling" <?php echo $sgScrolling;?> />
											<span class="dashicons dashicons-info scrollingImg sameImageStyle"></span><span class="infoScrolling samefontStyle">If the containt is larger then the specified dimentions, then the content will be scrollable.</span><br>

											<span class="liquid-width">Enable reposition:</span><input class="input-width-static" type="checkbox" name="reposition" <?php echo $sgReposition;?> />
											<span class="dashicons dashicons-info repositionImg sameImageStyle"></span><span class="infoReposition samefontStyle">The popup will be resized/repositioned automatically when window is being resized.</span><br>

											<span class="liquid-width">Enable scaling:</span><input class="input-width-static" type="checkbox" name="scaling" <?php echo $sgScaling;?> />
											<span class="dashicons dashicons-info scrollingImg sameImageStyle"></span><span class="infoScaling samefontStyle">Resize popup according to screen size</span><br>

											<span class="liquid-width">Dismiss on overlay click:</span><input class="input-width-static" type="checkbox" name="overlayClose" <?php echo $sgOverlayClose;?> />
											<span class="dashicons dashicons-info overlayImg sameImageStyle"></span><span class="infoOverlayClose samefontStyle">The popup will be dismissed when user clicks beyond of the popup area.</span><br>

											<span class="liquid-width">Dismiss on content click:</span><input class="input-width-static js-checkbox-contnet-click" type="checkbox" name="contentClick" <?php echo $sgContentClick;?> />
											<span class="dashicons dashicons-info contentClick sameImageStyle"></span><span class="infoContentClick samefontStyle">The popup will be dismissed when user clicks inside popup area.</span><br>
											
											<span class="liquid-width">Reopen after form submission:</span><input class="input-width-static" type="checkbox" name="reopenAfterSubmission" <?php echo $sgReopenAfterSubmission;?> />
											<span class="dashicons dashicons-info overlayImg sameImageStyle"></span><span class="infoReopenSubmiting samefontStyle">If checked, the popup will reopen after form submission.</span><br>

											<div class="sg-hide sg-full-width js-content-click-wrraper">
												<?php echo createRadiobuttons($contentClickOptions, "content-click-behavior", true, esc_html($sgContentClickBehavior), "liquid-width"); ?>
												<div class="sg-hide js-readio-buttons-acordion-content sg-full-width">
													<span class="liquid-width">Url:</span><input class="input-width-static" type="text" name='click-redirect-to-url' value="<?php echo esc_attr(@$sgClickRedirectToUrl); ?>"> 
													<span class="liquid-width">Redirect to new tab:</span><input type="checkbox" name="redirect-to-new-tab" <?php echo $sgRedirectToNewTab; ?> >
												</div>
											</div>

											<span class="liquid-width">Change overlay color:</span><div id="color-picker"><input  class="sgOverlayColor" id="sgOverlayColor" type="text" name="sgOverlayColor" value="<?php echo esc_attr(@$sgOverlayColor); ?>" /></div><br>
											
											<span class="liquid-width">Change background color:</span><div id="color-picker"><input  class="sgOverlayColor" id="sgOverlayColor" type="text" name="sg-content-background-color" value="<?php echo esc_attr(@$sgContentBackgroundColor); ?>" /></div><br>

											<span class="liquid-width" id="createDescribeOpacitcy">Background overlay opacity:</span><div class="slider-wrapper">
												<input type="text" class="js-decimal" value="<?php echo esc_attr($sgOpacity);?>" rel="<?php echo esc_attr($sgOpacity);?>" name="opacity"/>
												<div id="js-display-decimal" class="display-box"></div>
											</div><br>

											<span class="liquid-width">Overlay custom class:</span><input class="input-width-static" type="text" name="sgOverlayCustomClasss" value="<?php echo esc_attr(@$sgOverlayCustomClasss);?>">
											<br>

											<span class="liquid-width">Content custom class:</span><input class="input-width-static" type="text" name="sgContentCustomClasss" value="<?php echo esc_attr(@$sgContentCustomClasss);?>">
											<br>

											<span  class="liquid-width" id="createDescribeFixed">Popup location:</span><input class="input-width-static js-checkbox-acordion" type="checkbox" name="popupFixed" <?php echo $sgPopupFixed;?> />
											<div class="js-popop-fixeds">
												<span class="fix-wrapper-style" >&nbsp;</span>
												<div class="fixed-wrapper">
													<div class="js-fixed-position-style" id="fixed-position1" data-sgvalue="1"></div>
													<div class="js-fixed-position-style" id="fixed-position2"data-sgvalue="2"></div>
													<div class="js-fixed-position-style" id="fixed-position3" data-sgvalue="3"></div>
													<div class="js-fixed-position-style" id="fixed-position4" data-sgvalue="4"></div>
													<div class="js-fixed-position-style" id="fixed-position5" data-sgvalue="5"></div>
													<div class="js-fixed-position-style" id="fixed-position6" data-sgvalue="6"></div>
													<div class="js-fixed-position-style" id="fixed-position7" data-sgvalue="7"></div>
													<div class="js-fixed-position-style" id="fixed-position8" data-sgvalue="8"></div>
													<div class="js-fixed-position-style" id="fixed-position9" data-sgvalue="9"></div>
												</div>
											</div>
											<input type="hidden" name="fixedPostion" class="js-fixed-postion" value="<?php echo esc_attr(@$sgFixedPostion);?>">
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
					<?php require_once("options_section/pro.php"); ?>
				</div>
				</div>
				<div class="clear"></div>
				<?php 
					$isActivePopup = SgPopupGetData::isActivePopup(@$id);
					if(!@$id) $isActivePopup = 'checked';
				?>
				<input class="sg-hide-element" name="isActiveStatus" data-switch-id="'.$id.'" type="checkbox" <?php echo $isActivePopup; ?> >
				<input type="hidden" class="button-primary" value="<?php echo esc_attr(@$id);?>" name="hidden_popup_number" />
			</div>
		</div>
</form>
<?php
SGFunctions::showInfo();