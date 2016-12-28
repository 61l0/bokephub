/*
 * WP Socializer shortcode inserting javascript in TinyMCE editor
 * http://www.aakashweb.com
 * v1.2
 * Added since WP Socializer v2.0
*/
function wpsr_insert_shortcode(type, edparam) {
	var choice = prompt('Select the button to insert: \n\n1.Social buttons \n2.Addthis \n3.Sharethis \n4.Retweet \n5.Google +1 \n6.Digg \n7.Facebook \n8.StumbleUpon \n9.Reddit \n10.LinkedIn \n11.Pinterest \n\nExample: 4 \n\n Please refer "http://bit.ly/wpsrParams" for parameters.', '');
	var btncode;
	
	if (choice !== null) {
		switch (jQuery.trim(choice)) {
			case '1':
				btncode = "socialbts"; break;
			case '2':
				btncode = "addthis"; break;
			case '3':
				btncode = "sharethis"; break;
			case '4':
				btncode = "retweet"; break;
			case '5':
				btncode = "plusone"; break;
			case '6':
				btncode = "digg"; break;
			case '7':
				btncode = "facebook"; break;
			case '8':
				btncode = "stumbleupon"; break;
			case '9':
				btncode = "reddit"; break;
			case '10':
				btncode = "linkedin"; break;
			case '11':
				btncode = "pinterest"; break;
			default:
				return '';
		}
		
		var shortcode = "[wpsr_" + btncode + "]";
		
		if (type === 'visual') {
			edparam.execCommand('mceInsertContent', false, shortcode);
		}else{
			edInsertContent(edCanvas, shortcode);
		}
		
	}else{
		return '';
	}
}

// For adding button in the visual editing toolbox
(function() {
	tinymce.create('tinymce.plugins.WPSRButton', {
	
		init : function(ed, url) {	
			ed.addButton('wpsrbutton', {
				title : 'Insert WP Socializer buttons',
				image : url + '/icon.png',
				onclick : function() {
					wpsr_insert_shortcode('visual', ed);
                }
			});	
		},
		
		getInfo : function() {
			return {
				longname : 'WP Socializer',
				author : 'Aakash Chakravarthy',
				authorurl : 'http://www.aakashweb.com/',
				infourl : 'http://www.aakashweb.com/',
				version : '1.2'
			};
		}

	});
	
	tinymce.PluginManager.add('wpsrbutton', tinymce.plugins.WPSRButton);
})();

// For adding button in the code editing toolbox

if(document.getElementById("ed_toolbar")){
	qt_toolbar = document.getElementById("ed_toolbar");
	edButtons[edButtons.length] = new edButton("ed_wpsrbutton", "WP Socializer", "", "","");
	var qt_button = qt_toolbar.lastChild;
	while (qt_button.nodeType != 1){
		qt_button = qt_button.previousSibling;
	}
	qt_button = qt_button.cloneNode(true);
	qt_button.value = 'WP Socializer';
	qt_button.title = 'Insert WP Socializer buttons';
	qt_button.onclick = function(){ wpsr_insert_shortcode('code', ''); };
	qt_button.id = "ed_wpsrbutton";
	qt_toolbar.appendChild(qt_button);
}