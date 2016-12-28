jQuery(document).ready(function ($) {

	//only load if on last page
	if(typeof(jQuery('#separator').val()) != "undefined" && jQuery('#separator').val() !== null){
		var start = 1;
		var ids = "";
		process(start,ids);
	}
});


function process(start,ids){
	
	var copy_video_url = jQuery('#copy_video_url').val();
	var copy_sponsor_link_url = jQuery('#copy_sponsor_link_url').val();
	
	var status = jQuery('#status').val();
	var type = jQuery('#type').val();	
	var separator = jQuery('#separator').val();	
	var field = encodeURIComponent(jQuery('#field').val());
	var thumb_source = jQuery('#thumb_source').val();
	var sponsor = jQuery('#sponsor').val();
	var post_category = jQuery('#post_category').val();
	var save_thumbs = jQuery('#save_thumbs').val();
	var save_videos = jQuery('#save_videos').val();
	var detect_duration = jQuery('#detect_duration').val();
	var block_dups = jQuery('#block_dups').val();
	var first_id = jQuery('#first_id').val();
	
	var dataString = 'action=my_action&start='+start+'&';
	dataString = dataString + 'ids='+ids+'&';
	
	dataString = dataString + 'copy_video_url='+copy_video_url+'&';
	dataString = dataString + 'copy_sponsor_link_url='+copy_sponsor_link_url+'&';
		
	dataString = dataString + 'status='+status+'&';
	dataString = dataString + 'type='+type+'&';
	dataString = dataString + 'separator='+separator+'&';
	dataString = dataString + 'field='+field+'&';
	dataString = dataString + 'thumb_source='+thumb_source+'&';
	dataString = dataString + 'sponsor='+sponsor+'&';
	dataString = dataString + 'post_category='+post_category+'&';
	dataString = dataString + 'save_thumbs='+save_thumbs+'&';
	dataString = dataString + 'save_videos='+save_videos+'&';
	dataString = dataString + 'detect_duration='+detect_duration+'&';
	dataString = dataString + 'block_dups='+block_dups+'&';
	dataString = dataString + 'first_id='+first_id+'&';
	
	var setAlls=new Array('post_date','duration','title','description','tags','performers','site','sponsor_link_url','sponsor_link_txt',
	'misc1','misc2','misc3','misc4','misc5');
	
	jQuery.each(setAlls, function(key, value) { 
	  dataString = dataString + 'setall_'+value+'='+encodeURIComponent(jQuery('#'+value).val())+'&';
	});	
	
	jQuery.ajax({
	  type: "POST",
	  dataType: 'json',
	  url: ajaxurl,
	  data: dataString,
	  
	  success: function(json){

		jQuery('#response').append(json.response);
		
		if(json.done==1){
			jQuery('#loading').remove();
			return;
			
		} else {
			
			//increment
			start = start+1;
			
			process(start,json.ids);
		}
	
	  }
	});	
	
}

