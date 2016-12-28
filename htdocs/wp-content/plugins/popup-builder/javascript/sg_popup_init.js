function SgPopupInit(popupData) {
	this.popupData = popupData;
	this.cloneToHtmlPopup();
	this.reopenPopupAfterSubmission();
}

SgPopupInit.prototype.cloneToHtmlPopup = function() {
	var currentPopupId = this.popupData['id'];

	/*When content does not have shortcode*/
	if(jQuery("#sgpb-all-content-"+currentPopupId).length == 0) {
		return;
	}
	
	jQuery("#sgpb-all-content-"+currentPopupId).appendTo(jQuery('.sg-current-popup-'+currentPopupId));

	this.popupResizing(currentPopupId);
	jQuery('#sgcolorbox').bind('sgPopupCleanup', function() {
		jQuery('#sgpb-all-content-'+currentPopupId).appendTo(jQuery("#sg-popup-content-"+currentPopupId));
	});

	this.shortcodeInPopupContent();
}

SgPopupInit.prototype.reopenPopupAfterSubmission = function() {

	var reopenSubmission = this.popupData['reopenAfterSubmission'];
	var currentPopupId = this.popupData['id'];
	SGPopup.setCookie('sgSubmitReloadingForm', currentPopupId, -10);
	var that = this;

	if(reopenSubmission) {
		jQuery("#sgcboxLoadedContent form").submit(function() {
			SGPopup.setCookie('sgSubmitReloadingForm', currentPopupId);
		});
	}
}

SgPopupInit.prototype.popupResizing = function(currentPopupId) {

	var width = this.popupData['width'];
	var height = this.popupData['height'];
	var maxWidth = this.popupData['maxWidth'];
	var maxHeight = this.popupData['maxHeight'];

	if(maxWidth == '' && maxHeight == '') {
		jQuery.sgcolorbox.resize({'width': width, 'height': height});
	}
}

SgPopupInit.prototype.shortcodeInPopupContent = function() {

	jQuery(".sg-show-popup").bind('click',function() {
		var sgPopupID = jQuery(this).attr("data-sgpopupid");
		var sgInsidePopup = jQuery(this).attr("insidepopup");

		if(typeof sgInsidePopup == 'undefined' || sgInsidePopup != 'on') {
			return false;
		}
		
		jQuery.sgcolorbox.close();
		
		jQuery('#sgcolorbox').bind("sgPopupClose", function() {
			if(sgPopupID == '') {
				return;
			}
			sgPoupFrontendObj = new SGPopup();
			sgPoupFrontendObj.showPopup(sgPopupID,false);
			sgPopupID = '';
		});
	});
}

SgPopupInit.prototype.overallInit = function() {
	jQuery('.sg-popup-close').bind('click', function() {
		jQuery.sgcolorbox.close();
	});

	//Facebook reInit
	if(jQuery('#sg-facebook-like').length && typeof FB !== 'undefined') {
		FB.XFBML.parse();
	}
}

SgPopupInit.prototype.initByPopupType = function() {
	var data = this.popupData;
	var popupObj = {};
	var popupType = data['type'];
	var popupId = data['id'];

	switch(popupType) {
		case 'countdown':
			var popupObj = new SGCountdown();
			popupObj.init();
			break;
		case 'contactForm':
			popupObj = new SgContactForm();
			popupObj.buildStyle();
			break;
		case 'social':
			popupObj = new SgSocialFront();
			popupObj.init();
			break;
		case 'subscription':
			popupObj = new SgSubscription();
			popupObj.init();
			break;
		case 'ageRestriction':
			popupObj = new SGAgeRestriction();
			popupObj.setPopupId(popupId);
			popupObj.init();
			break;
	}

	return popupObj;
}