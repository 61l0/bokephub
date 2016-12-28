/* 
 * WP Socializer - Admin page functions
 * Author: Aakash Chakravarthy
 * Version: 2.9
 *
 */

$j = jQuery.noConflict();
$j(document).ready(function(){

	// Tab Initializements
	var tabs = $j("#content").tabs({
		fx: {opacity: 'toggle', duration: 'fast'},
		/*select: function(event, ui){
			window.location.hash = ui.tab.hash;
		}*/
	});
	var subTabs = $j("#tab-3").tabs({fx: {opacity: 'toggle', duration: 'fast'} });
	
	// Sorter Initializements
	$j('.sbSelList').sortable({
		stop : wpsr_socialbt_selectedgenerator,
		opacity : 0.5,
		scroll : true,
		revert : true
	});
	
	// Toolbar
	$j('.parentLi').hover(function(){
		$j(this).children('ul').show();
	}, function(){
		$j(this).children('ul').fadeOut('fast');
	});
	
	$j('.parentLi li, .btn').click(function(){
		var id = $j('#subTabs .ui-state-active').attr('data-editor');
		var openTag = ($j(this).attr('openTag') == null) ? '' : $j(this).attr('openTag');
		var closeTag = ($j(this).attr('closeTag') == null) ? '' : $j(this).attr('closeTag');
		awQuickTags(id, openTag, closeTag,'');
		$j(this).parent('.childMenu').fadeOut('fast');
	});
	
	// Window
	$j('.window').click(function(e){
		if(e.target === this){
			$j(this).hide();
			$j('body').css('overflow', 'auto');
		}
	});
	
	$j('[data-win]').click(function(){
		$j('body').css('overflow', 'hidden');
		$j('.window .inWindow').hide();
		$j('.window').show().children('.' + $j(this).attr('data-win')).show().css('width', $j(this).attr('data-width'));
		$j('.window h2').text($j(this).attr('data-title'));
	});
	
	// Oneclick functions
	$j.ajax({
        type: "GET",
		url: $j('.tmplUrl').text(),
		dataType: "xml",
		success: function(xml){
			$j(xml).find('item').each(function(){
				name = $j(this).attr('name');
				author = $j(this).attr('author');
				version = $j(this).attr('version');
				image = $j('.tmplImg').text() + $j(this).attr('image');
				url = $j(this).attr('url')
				vars = $j(this).find('settings').text();
				temp1 = 'wpsr_template[1][content]==' + $j(this).find('template1').text() + ';;';
				temp2 = 'wpsr_template[2][content]==' + $j(this).find('template2').text() + ';;';
				cnt1 = 'wpsr_content1==' + $j(this).find('custom1').text() + ';;';
				cnt2 = 'wpsr_content2==' + $j(this).find('custom2').text() + ';;';
				
				nameHtml = '<div class="templateHead">' + name + '</div>';
				versionHtml = '<div class="templateDetails">by <a href=' + url + ' target="_blank">' + author + '</a> version ' + version + '</div>';
				applyBt = '<span class="templateBt applyBt">Select this template</span>';
				hiddenHtml = '<textarea class="templateCont">' + vars + temp1 + temp2 + cnt1 + cnt2 + '</textarea>';
		
				itemContent = applyBt + hiddenHtml + nameHtml + versionHtml;
				$j('.templatesList').append('<div class="templateItem" rel="' + image + '">' + itemContent + '</div>');
			});
		}
	});
	
	$j('.templateItem').live('mouseenter', function(e){
		$j(this).append('<div class="tooltip">Preview: <br/><img src="' + $j(this).attr('rel') + '"/></div>');
		$j('.tooltip').css({ top: '75px' });
	}).live('mouseleave', function(){
		$j(this).children('.tooltip').remove();
	});
	
	$j('.applyBt').live('click', function(){
		cnt = $j(this).next('.templateCont').text();
		cntSplit = cnt.split(';;');
		
		$j(this).fadeOut(500).fadeIn(500).addClass('applyBtYellow');
		
		for(i=0; i<cntSplit.length; i++){
			element = cntSplit[i].split('==');
			eleId = ConvertValue('#' + $j.trim(element[0]));
			eleValue = $j.trim(element[1]);
					
			if($j(eleId).attr('type') == 'checkbox'){
				if(eleValue == 0){
					$j(eleId).removeAttr('checked');
				}else{
					$j(eleId).attr('checked', 'checked');
				}
			}else if($j(eleId).attr('type') == 'radio'){
				$j(eleId + '[value=' + eleValue + ']').attr('checked', 'checked');
			}else{
				$j(eleId).val(eleValue);
			}
		}
		
		alert(' Template is applied. Go to "Edit templates" page and customize the arrangement or alignment if needed ' );
	});
	
	// Add social button
	$j('.sbAdd').click(function(){
		var text =  $j(this).siblings('.sbName').text();
		var appendList = '<li><span class="sbName">' + text + '</span><span class="sbDelete">x</span></li>';
		var pixel = $j(this).attr('data-pixel');
	
		$j('#sbSelList_' + pixel + 'px' ).append(appendList);
		wpsr_socialbt_selectedgenerator();
	});

	// Delete social button
	$j('.sbDelete').live("click", function() {
		$j(this).parent().fadeOut('slow', function(){
			$j(this).remove();
			wpsr_socialbt_selectedgenerator();
		});
	});
	
	// Basic Admin Functions
	$j('h3:not(".noToggle")').append('<span class="maxMin" title="Collapse">-</span>');

	$j('h3 .maxMin').toggle(
		function(){ 
			$j(this).parent().next().slideUp();
			$j(this).text('+'); 
		},
		function(){ 
			$j(this).parent().next().slideDown(); 
			$j(this).text('-'); 
		}
	);
	
	$j('.inWindow').prepend('<h2></h2>');
	$j('.message').prepend('<span class="popClose">x</span>');
	
	$j('[data-tab]').click(function(e){
		e.preventDefault();
		tabs.tabs('option', 'active', $j(this).attr('data-tab')-1);
	});
	
	setTimeout(function(){
		$j('.autoHide').slideUp('slow', function(){ $j(this).remove(); });
	}, 8000);
	
	$j('.message .popClose').click(function(){
		$j(this).parent().slideUp();
	});
	
	$j('.toggleNext').live('click', function(){
		$j(this).next().slideToggle();
	});
	
	// Intro box
	if($j('.introWrap').length != 0){
	$j.ajax({
        type: "GET",
		url: 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent('select * from xml where url="http://vaakash.github.io/wpsr.xml"') + '&format=xml',
		dataType: "xml",
		success: function(xml){
			try{
				ver = '[version="' + $j('.wpsrVer').text() + '"]';
				$j(xml).find('content' + ver).each(function(){
					$j('.infoContent').hide();
					$j('.infoContent').html($j(this).text());
					$j('.infoContent').fadeIn();
				});
			}
			catch(err){
				$j('.infoContent').html('<p>For more details: </p>');
			}
		},
		error: function(err){
			$j('.infoContent').html('<b>New in this version: </b> <a href="http://www.aakashweb.com/wordpress-plugins/wp-socializer/" target="_blank">Click here</a> to view the new features and changes');
		}
	});
	iUrl = 'http://stats.wordpress.com/g.gif?host=vaakash.kodingen.com&blog=24923956&v=ext&post=0&rand=' + Math.random() + '&ref=' + encodeURI($j('.blogUrl').text());
	$j('.introWrap').after('<img src="' + iUrl + '" class="statImg"/>');
	}
	
	//Help tab
	$j('.helpTab').click(function(){
			$j.ajax({
				type: "GET",
				url: 'http://query.yahooapis.com/v1/public/yql?q=' + encodeURIComponent('select * from xml where url="http://vaakash.github.io/wpsr-help.xml"') + '&format=xml',
				dataType: "xml",
				success: function(xml){
					ver = '[version="' + $j('.wpsrVer').text() + '"]';
					
					general = $j(xml).find('general').text();
					specific = $j(xml).find('content' + ver).text();

					$j('.helpBox').hide();
					$j('.helpBox').html(general + specific);
					$j('.helpBox').fadeIn();

				},
				error: function(err){
					$j('.helpBox').html('<p>An error occured while getting the help file.</p> For WP Socializer plugin support, use the free <a href="http://www.aakashweb.com/forum/" target="_blank">Aakash Web support forum</a>')
				}
			});
			
	});
	
	// Tooltip 
	$j('.showTooltip').live('mouseover', function(e) {
		var tip = '<img src="' + $j('.wpsrAdminUrl').text() + 'images/buttons/' + $j(this).attr('data-image') + '.png" />';
		$j(this).append('<div class="tooltip">' + tip + '</div>');
		$j('.tooltip').css({
			'margin-top': -$j(this).children('.tooltip').outerHeight(),
			'margin-left': -($j(this).children('.tooltip').outerWidth() + 20)
		});
	}).mouseleave(function(){
		$j(this).children('div.tooltip').remove();
	});
	
	// Retweet Page
	$j('#wpsr_retweet_topsysettings').hide();
	$j('#wpsr_retweet_service').change(function(){
		if($j('#wpsr_retweet_service').val() == 'topsy'){
			$j('#wpsr_retweet_topsysettings').slideDown();
		}
		if($j('#wpsr_retweet_service').val() != 'topsy'){
			$j('#wpsr_retweet_topsysettings').slideUp();
		}
	});
	if($j('#wpsr_retweet_service').val() == 'topsy'){
		$j('#wpsr_retweet_topsysettings').slideDown();
	}
	
	$j('#wpsr_retweet_twittersettings').hide();
	$j('#wpsr_retweet_service').change(function(){
		if($j('#wpsr_retweet_service').val() == 'twitter'){
			$j('#wpsr_retweet_twittersettings').slideDown();
		}
		if($j('#wpsr_retweet_service').val() != 'twitter'){
			$j('#wpsr_retweet_twittersettings').slideUp();
		}
	});
	if($j('#wpsr_retweet_service').val() == 'twitter'){
		$j('#wpsr_retweet_twittersettings').slideDown();
	}
	
	// Facebook Page
	
	$j('#wpsr_facebook_counterplacement').hide();
	$j('#wpsr_facebook_counter').change(function(){
		if($j('#wpsr_facebook_counter').val() == '1'){
			$j('#wpsr_facebook_counterplacement').slideDown();
		}
		if($j('#wpsr_facebook_counter').val() != '1'){
			$j('#wpsr_facebook_counterplacement').slideUp();
		}
	});
	if($j('#wpsr_facebook_counter').val() == '1'){
		$j('#wpsr_facebook_counterplacement').slideDown();
	}
	
	// smartload settings
	
	$j('#wpsr_lazload_timeout').hide();
	$j('#wpsr_settings_smartload').change(function(){
		if($j(this).val() == 'timeout'){
			$j('#wpsr_lazload_timeout').slideDown();
		}
		if($j(this).val() != 'timeout'){
			$j('#wpsr_lazload_timeout').slideUp();
		}
	});
	if($j('#wpsr_settings_smartload').val() == 'timeout'){
		$j('#wpsr_lazload_timeout').slideDown();
	}

	// Live search
	$j('#sbFilter').keyup(function(event){
		var search_text = $j(this).val();
		var rg = new RegExp(search_text,'i');
		$j('#sbList li').each(function(){
			if($j.trim($j(this).text()).search(rg) == -1){
				$j(this).hide();
			}else{
				$j(this).show();
			}
		});
	});
	
	// Share
	$j('.wpsr_share_wrap li').not('.wpsr_pressthis').mouseenter(function(){
		$this = $j(this);
		$j('.wpsr_share_iframe').remove();
		$j('body').append('<iframe class="wpsr_share_iframe"></iframe>');
		$j('.wpsr_share_iframe').css({
			position: 'absolute',
			top: $this.offset()['top'],
			left: $this.offset()['left'] + 55,
			width: $this.attr('data-width'),
			height: $this.attr('data-height'),
		}).attr('src', $this.attr('data-url')).hide().fadeIn();		
	});
	
	$j('.wpsr_share_iframe').live('mouseout', function(){
		$j(this).remove();
	});
	
	$j('.wpsr_pressthis a').click(function(e){
		e.preventDefault();
		newwindow2=window.open('press-this.php?t=WP Socializer - all in one social buttons plugin for WordPress&u=http://www.aakashweb.com/wordpress-plugins/wp-socializer/&s=WP Socializer is an advanced plugin for inserting all kinds of Social bookmarking and sharing buttons. It has super cool features to insert the buttons into posts, sidebar. It also has Floating sharebar in two modes of orientation. Custom icons can be used for social icons. Check out to know more information on this plugin.','name','height=400,width=700');
	});
	
	$j('#wpsr_reset').click(function(){
		var res = confirm('Do you want to reset the settings ?');
		if(res == false){
			return false;
		}
	});
	
	// Floating share bar
	$j('.floatBtsSel').sortable({
		stop: wpsr_floatingbt_selectedgenerator,
		opacity : 0.5,
		scroll : true,
		placeholder: "floatbts_highlight"
		//revert : true
	});
	$j('.floatBtsList li').click(function(){
		$j('.floatBtsSel').append($j(this).clone());
		wpsr_floatingbt_selectedgenerator();
	});
	
	$j('.floatBtsSel li').live('dblclick', function(){
		$j(this).remove();
		wpsr_floatingbt_selectedgenerator();
	});
	
	$j('#wpsr_floatbts_reset').click(function(){
		var res = confirm('Do you want to reset the settings ?');
		if(res == false){
			return false;
		}
	});
	
	// Checkbox unchecked value
	$j('#content input[type=checkbox]').each(function(){
		clone = $j(this).clone().attr('type', 'hidden').attr('value', 0);
		$j(this).before(clone);
	});
	
	// PROMO
	$j('.promo_p1').mouseenter(function(){
		$j(this).hide();
		$j('.promo_p2').show();
	});
	$j('.promo_p2').mouseleave(function(){
		$j(this).hide();
		$j('.promo_p1').show();
	});
	$j('.promo_p2').hide();

});

function wpsr_socialbt_selectedgenerator(){
	// 16 px
	var sbSel_16px = [];
	$j("#sbSelList_16px li .sbName").each(function(){
		sbSel_16px.push($j(this).text());
	});
	$j("#wpsr_socialbt_selected16px").val(sbSel_16px.join(','));
	
	// 32 px
	var sbSel_32px = [];
	$j("#sbSelList_32px li .sbName").each(function(){
		sbSel_32px.push($j(this).text());
	});
	$j("#wpsr_socialbt_selected32px").val(sbSel_32px.join(','));
}

function wpsr_floatingbt_selectedgenerator(){
	var floatbts_sel = [];
	$j(".floatBtsSel li").each(function(){
		floatbts_sel.push($j(this).text());
	});
	
	$j("#wpsr_floatbts_selectedbts").val(floatbts_sel.join(','));
}

function ConvertValue(id){
      var test = id.replace(/[[]/g,'\\[');
    return test.replace(/]/g,'\\]');
}

function openAddthis(){
	window.open ("http://www.addthis.com/bookmark.php?v=250&username=vaakash&title=WP Socializer - Wordpress plugin&url=http://www.aakashweb.com/wordpress-plugins/wp-socializer/", "open_window","location=0,status=0,scrollbars=1,width=500,height=600");
}

function openServiceSelctor(windowUrl, id){
	var atw = window.open(windowUrl + document.getElementById(id).value, 'wpsr_services_popup','width=620,height=660,scrollbars=1');
	atw.focus();
	return false;
}

function addthisSetTargetField(gotValue, targetField){
	document.getElementById(targetField).value = gotValue;
	window.focus();
}

var wpsr_closeiframe = function(){
	$j('.wpsr_share_iframe').remove();
}

/* AW Quick tag editor */
function awQuickTags(tbField,openTg,closeTg,btType){contentBox=document.getElementById(tbField);var src;var href;var style;var divStyle;var divId;if(document.selection){contentBox.focus();sel=document.selection.createRange();if(btType==''){sel.text=insertTagsAll('',openTg,sel.text,closeTg,'');}if(btType=='a'){sel.text=insertTagLink('',openTg,sel.text,closeTg,'');}if(btType=='img'){sel.text=insertTagImage('',openTg,sel.text,closeTg,'');}if(btType=='replace'){sel.text=insertTagReplacable('',openTg,sel.text,closeTg,'');}}else if(contentBox.selectionStart||contentBox.selectionStart=='0'){var startPos=contentBox.selectionStart;var endPos=contentBox.selectionEnd;var front=(contentBox.value).substring(0,startPos);var back=(contentBox.value).substring(endPos,contentBox.value.length);var selectedText=contentBox.value.substring(startPos,endPos);if(btType==''){contentBox.value=insertTagsAll(front,openTg,selectedText,closeTg,back);contentBox.selectionStart=startPos+contentBox.value.length;contentBox.selectionEnd=startPos+openTg.length+selectedText.length;}if(btType=='a'){contentBox.value=insertTagLink(front,openTg,selectedText,closeTg,back);contentBox.selectionStart=startPos+contentBox.value.length;contentBox.selectionEnd=startPos+openTg.length+selectedText.length+8+href.length;}if(btType=='img'){contentBox.value=insertTagImage(front,openTg,selectedText,closeTg,back);contentBox.selectionStart=startPos+contentBox.value.length;contentBox.selectionEnd=startPos+openTg.length+selectedText.length+7+src.length+closeTg.length;}if(btType=='replace'){contentBox.value=insertTagReplacable(front,openTg,selectedText,closeTg,back);contentBox.selectionStart=startPos+contentBox.value.length;contentBox.selectionEnd=startPos+openTg.length;}}else{contentBox.value+=myValue;contentBox.focus();}function insertTagsAll(frontText,openTag,selectedText,closeTag,backText){return frontText+openTg+selectedText+closeTg+backText;}function insertTagLink(frontText,openTag,selectedText,closeTag,backText){href=prompt('Enter the URL of the Link','http://');if(href!='http://'&&href!=null){return frontText+openTg+'href="'+href+'">'+selectedText+closeTg+backText;}else{return frontText+selectedText+backText;}}function insertTagImage(frontText,openTag,selectedText,closeTag,backText){src=prompt('Enter the URL of the Image','http://');if(src!='http://'&&src!=null){return frontText+openTg+'src="'+src+'" '+closeTg+selectedText+backText;}else{return frontText+selectedText+backText;}}function insertTagImage(frontText,openTag,selectedText,closeTag,backText){src=prompt('Enter the URL of the Image','http://');if(src!='http://'&&src!=null){return frontText+openTg+'src="'+src+'" '+closeTg+selectedText+backText;}else{return frontText+selectedText+backText;}}function insertTagReplacable(frontText,openTag,selectedText,closeTag,backText){return frontText+openTg+backText;}}function awQuickTagsHeading(tbField,headingBox){contentBox=document.getElementById(tbField);hBox=document.getElementById(headingBox);contentBox.focus();if(document.selection){contentBox.focus();sel=document.selection.createRange();sel.text='<h'+hBox.value+'>'+sel.text+'</h'+hBox.value+'>';}else if(contentBox.selectionStart||contentBox.selectionStart=='0'){var startPos=contentBox.selectionStart;var endPos=contentBox.selectionEnd;var front=(contentBox.value).substring(0,startPos);var back=(contentBox.value).substring(endPos,contentBox.value.length);var selectedText=contentBox.value.substring(startPos,endPos);contentBox.value=front+'<h'+hBox.value+'>'+selectedText+'</h'+hBox.value+'>'+back;contentBox.selectionStart=startPos+contentBox.value.length;contentBox.selectionEnd=startPos+4+selectedText.length;}}