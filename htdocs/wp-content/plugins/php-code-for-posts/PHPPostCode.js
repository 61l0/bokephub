jQuery(document).ready(function($){
	var nmb=$("div#phpcfp-notificationbox");
	var tb=$("tbody#TPSL");
	var editfrm=$("form#codeform.ajaxible");
	var savebtn=$("form#codeform.ajaxible input#updatebtn");
	var at=$("div.tabs>div.tab");
	var tc=$("a.tabchange");
	var c='activetab-head';
	function addMsg(s,m){var c=s==1?'updated':'error';var x=$('<div class="'+c+'"><p>'+m+'</p></div>');nmb.append(x);window.setTimeout(function(){remObj(x);},5000)}
	function remObj(x){x.remove()}
	function _(e){e.preventDefault();e.stopPropagation();}
	function req(d,cb){$.ajax({method:'post',url:ajaxurl,data:d}).done(function(r){cb(r)})}
	function csFb(r){if(r==1){savebtn.val(savebtn.attr('data-updated'));addMsg(1,phpcfp_snippetSavesuccess);}else{savebtn.val(savebtn.attr('data-failed'));addMsg(0,phpcfp_snippetSaveFailed);}window.setTimeout(function(){savebtn.val(savebtn.attr('data-original'))},5000)}
	function sRC(){var e=tb.children('tr').length;if(e==0){tb.html(tb.attr("data-noitems"))}}
	editfrm.submit(function(e){_(e);savebtn.attr('data-original',savebtn.val());savebtn.val(savebtn.attr('data-updating'));req('action=phpcodeforposts_ajax&nopost=1&'+editfrm.serialize(),csFb)});
	$('a.snippetclosebtn').click(function(e){return confirm(phpcfp_snippetClosePrompt)})
	$('a.phppc-ajaxible').click(function(e){_(e);var $t=$(this);var ctext=$t.attr('data-confirm');if ((ctext!=''?confirm(ctext):true)){req($t.attr('data-ajax'), function(r){addMsg(r.state,r.msg);if(r.state==1){var action=$t.attr("data-action");if(action=="remove"){remObj($($t.attr("data-remove")));sRC();}}});}});
	$("a.tabchange").click(function(e){_(e);at.hide();tc.removeClass(c);var $this=$(this);$("div.tabs div.tab-"+$this.attr("data-tab")).show();$this.addClass(c);});
	sRC();at.hide();$("div.tabs>div.tab.activetab").show();
});
