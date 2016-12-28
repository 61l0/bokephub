<?php

	$start = $_POST['start'];
	$end = $start + 1;
	
	$copy_video_url = $_POST['copy_video_url'];
	$copy_sponsor_link_url = $_POST['copy_sponsor_link_url'];	
	
	$status = $_POST['status'];
	$type = $_POST['type'];
	$separator = $_POST['separator'];
	$field = json_decode(stripslashes($_POST['field']));
	$thumb_source = $_POST['thumb_source'];
	$sponsor = $_POST['sponsor'];
	$post_category = json_decode(stripslashes($_POST['post_category']));
	$save_thumbs = $_POST['save_thumbs'];
	$save_videos = $_POST['save_videos'];
	$detect_duration = $_POST['detect_duration'];
	$block_dups = $_POST['block_dups'];
	
	$first_id = $_POST['first_id'];
	$ids = $_POST['ids'];

	$line=$start;

	$upload_dir = wp_upload_dir();

	$fh = fopen("$upload_dir[basedir]/tubeace-import.txt", 'r+');
	$content = fread($fh, filesize("$upload_dir[basedir]/tubeace-import.txt"));
	fclose($fh);
	
	$content = addslashes($content);
	$content = trim($content);
	
	$lines = explode("\n", $content);
	$size = count($lines);
	
	foreach($lines as $lines_key => $lines_val){
		
		//start / end
		if(($lines_key+1 >= $start) && ($lines_key+1 < $end)){
	
			//reset
			unset($insertFields);
			unset($insertValues);
			unset($error);
			
			$saved_thmb = 0;
			
			foreach($fields = explode($separator, $lines[$lines_key]) as $fields_key => $fields_val) {
		
				$fields_val_con = trim($fields_val);
				
				//get title to display below
				if($field[$fields_key]=="title"){
					$title = $fields_val_con;
				}
				if($field[$fields_key]=="description"){
					$description = $fields_val_con;
				}
				if($field[$fields_key]=="duration"){
					$duration = $fields_val_con;
				}
				if($field[$fields_key]=="tags"){
					$tags = $fields_val_con;
				}			
				if($field[$fields_key]=="performers"){
					$performers = $fields_val_con;
				}		
				if($field[$fields_key]=="sponsor_link_url"){
					$sponsor_link_url = $fields_val_con;
				}
				if($field[$fields_key]=="embed_code"){
					$embed_code = $fields_val_con;
				}				
				//get video_url to display below
				if($field[$fields_key]=="video_url"){
					$video_url = $fields_val_con;
					$video_url_encoded = urlencode($fields_val_con);
				}
				//get site to check in exclude array
				if($field[$fields_key]=="site"){
					$site = $fields_val_con;
				}	

				//get thumb_url to check valid image
				if($field[$fields_key]=="thumb_url"){

					//multiple thumbs is possible, so create explode into array
					$thumbsArr = $thumbsArr2 = explode(';', $fields_val_con);
					
					foreach($thumbsArr as $val){

						if(!@getimagesize($val)){
							$response.="<span class=\"tubeace-errormsg\">line # $line skipped - Thumbnail URL $val invalid!</span><br>";
							$errorInsert=1;
						}						
					}




				}							
			
				//check for dups
				//video_url
				if($block_dups==1 && $field[$fields_key]=="video_url"){
				
					$query = "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'video_url' AND meta_value = '$fields_val_con'";
					$results = $wpdb->get_results($query);

					if($wpdb->num_rows>0){
						
						$response.="<span class=\"tubeace-errormsg\">line # $line skipped - Video URL already in database: 
						<a href=\"$fields_val_con\" target=\"_blank\">$fields_val_con</a>";
						$errorInsert=1;
					}
				}
				
				//embed_code
				if($block_dups==1 && $field[$fields_key]=="embed_code"){
				
					$query = "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'embed_code' AND meta_value = '$fields_val_con'";
					$results = $wpdb->get_results($query);

					if($wpdb->num_rows>0){
						
						$response.="<span class=\"tubeace-errormsg\">line # $line skipped - Embed Code already in database</span><br>";
						$errorInsert=1;
					}
				}		

				//setup thumb variables to create thumbs after insert (for id)
				if($save_thumbs==1 && $field[$fields_key]=="thumb_url" && $thumb_source=="import_file"){
	
	
					//standard thumbs
					if($field[$fields_key]=="thumb_url"){
						$saved_thmb=count( $thumbsArr2 );
						$thumb_url = $fields_val_con;
						$thmb_ext = strtolower(substr(strrchr(basename($thumb_url), '.'), 1));
					}
				} 										 		
			}
		
		
			if(isset($excludeArray)){
				if(in_array($site, $excludeArray) && !$error){
					$response.="<span class=\"tubeace-errormsg\">line # $line skipped - $site excluded in Site Definitions</span><br>\n";
					$errorInsert=1;
				}
			}
		
			if(!$errorInsert){

				//description can't be empty
				if(strlen($description)<1){
					$description = ' ';
				}				
			
				//trim
				$insertFields = rtrim($insertFields,", ");
				$insertValues = rtrim($insertValues,", ");
				
				//detect duration
				if($detect_duration==1){
					
					$videoDataArr = tubeace_video_metadata(urldecode($video_url));

					//if error with metadata, add to response
					if(!empty($videoDataArr[error])){
						$response.=$videoDataArr[error];

						echo json_encode(array("response"=>$response));	
						die();
					}

					$duration = round($videoDataArr[duration]);
				} 				

				$performersArr = explode(",", $performers);
				
				//$response.=print_r($performersArr);

				if($save_videos==1 || $thumb_source=="ffmpeg"){
					$post_status = "pending";
				} else {
					$post_status = $status;
				}

				//$response='<br>post status'.$post_status;

				if($post_status=='future'){
					$post_date = '2030-01-01 00:00:00';//must enter future date, or else will be set as 'publish'
				}

				//insert
				$my_post = array(
				  'post_title'    => $title,
				  'post_content'  => $description,
				  'post_date'  	  => $post_date,
				  'post_status'   => $post_status,
				  'post_author'   => $sponsor,
				  'post_category' => $post_category,
				  'tags_input' => $tags,
				  'tax_input' => array( 'performer' => $performersArr ) 
				);

				//$response.=print_r($my_post);

				// Insert the post into the database
				if($lastID = wp_insert_post( $my_post )){

					//add meta value
					add_post_meta($lastID, 'video_url', $video_url);
					add_post_meta($lastID, 'embed_code', $embed_code);
					add_post_meta($lastID, 'thumb_url', $thumb_url);
					add_post_meta($lastID, 'saved_thmb', $saved_thmb);
					add_post_meta($lastID, 'def_thmb', get_site_option('tubeace_def_thmb'));
					add_post_meta($lastID, 'duration', $duration);
					add_post_meta($lastID, 'site', $site);
					add_post_meta($lastID, 'sponsor_link_url', $sponsor_link_url);
					add_post_meta($lastID, 'sponsor_link_txt', $sponsor_link_txt);
					add_post_meta($lastID, 'misc1', $misc1);
					add_post_meta($lastID, 'misc2', $misc2);
					add_post_meta($lastID, 'misc3', $misc3);
					add_post_meta($lastID, 'misc4', $misc4);
					add_post_meta($lastID, 'misc5', $misc5);

					if($thumb_source=="ffmpeg"){
						add_post_meta($lastID, 'await_thumb', 1);
						$genThumbs = 1;
					}

					if($save_videos==1){
						add_post_meta($lastID, 'await_video', 1);
					}					

					//status after
					if($save_videos==1 || $thumb_source=="ffmpeg"){
						add_post_meta($lastID, 'status_after', $status);
					}						

					$response.="<span class=\"tubeace-succmsg\">line # $line titled ".stripslashes($title)." added! </span><br>\n";

                    //do thumbnail
                    $upload_dir = wp_upload_dir();
                    $image_data = file_get_contents($thumb_url);
                    //$filename = basename($thumb_url);
                    $filename = $lastID.'.jpg';
                    if(wp_mkdir_p($upload_dir['path']))
                        $file = $upload_dir['path'] . '/' . $filename;
                    else
                        $file = $upload_dir['basedir'] . '/' . $filename;
                    file_put_contents($file, $image_data);

                    $wp_filetype = wp_check_filetype($filename, null );
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => sanitize_file_name($filename),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
                    $attach_id = wp_insert_attachment( $attachment, $file, $lastID );
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
                    wp_update_attachment_metadata( $attach_id, $attach_data );

                    set_post_thumbnail( $lastID, $attach_id );
					
				} else {
					$response.="<span class=\"tubeace-errormsg\">line # $line titled ".stripslashes($title)." not added!</span><br>\n";
				}

				$ids.= $lastID.",";
				$subPath = tubeace_sub_dir_path($lastID);		
		
				//create thumbs
				if($save_thumbs==1){

					$i = 1;
					foreach($thumbsArr2 as $thumb_url){
						
						//create thumb dir
						$upload_dir = wp_upload_dir();
						$create_path = $upload_dir[basedir]."/tubeace-thumbs/".$subPath;
						wp_mkdir_p($create_path);	
		
						list($width, $height) = getimagesize($thumb_url); 
						$thumbDest = $upload_dir[basedir]."/tubeace-thumbs/".$subPath."/".$lastID."_".$i.".".$thmb_ext;
						
						if($width==$set[thumb_wdth] && $height==$set[thumb_hght]){
							copy($thumb_url,$thumbDest);
						} else {
							tubeace_resize_thumb($thumb_url,$thumbDest,$thmb_ext,$width,$height,get_site_option('tubeace_thumb_width'),get_site_option('tubeace_thumb_height'));	
						}

						//echo'<br>thumb_url'.$thumb_url;
						//echo'<br>thumbDest'.$thumbDest;

						$i++;
						
						$response.= "<span class=\"tubeace-succmsg\">Resized Thumb to ".get_site_option('tubeace_thumb_width')."x".get_site_option('tubeace_thumb_height')."</span><br>";
					}
				}

				//auto-scheduling
				if(get_site_option('tubeace_schedule_per_day')>0){
					
					$schedDate = tubeace_auto_sched_next_date(0);

					$wpdb->update( $wpdb->prefix . 'posts', array('post_date' => "$schedDate 00:00:00", 'post_date_gmt' => "$schedDate 00:00:00"), array('id' => $lastID));
										
					$response.= "<span class=\"tubeace-succmsg\">Video #$lastID Auto-Scheduled to $schedDate</span><br>";
				}
			}
			$response.= "<br>";
			$line++;
		}
		
		if($size==($line-1)){
			$done=1;
		}
	}
	
	if($done && !empty($ids)){

		$ids = rtrim($ids,",");
		
		$ids_arr = $ids_arr2 = $ids_arr3 = explode(',',$ids);

		$linesAdded = count($ids_arr);
		
		foreach($ids_arr as $value){
			$ids_query.= "id = '$value' OR ";
		}
		
		$ids_query = rtrim($ids_query," OR ");

		//handle setall_'s for posts table
		$setall_array = array('post_date','title','description');
		foreach($setall_array as $value){
			
			if(strlen($_POST["setall_$value"])>0){
				
				//display heading
				if(!$setAlls){
					$response.="<h3>Set All's</h3>";
					$setAlls=1;
				}		
							
				if($value=="post_date" && get_site_option('tubeace_schedule_per_day')>0 && !empty($_POST["setall_post_date"])){
					$response.="<br /><span class=\"tubeace-errormsg\">Skipped 'added' since Auto-Schedule enabled</span><br />";
				} else {

					$value2 = $value;

					if($value=="description"){
						$value = "post_excerpt";
					}

					if($value=="title"){
						$value = "post_title";
					}					

					if($value=="post_date" && ($_POST['setall_post_date'] > current_time('mysql'))){

						//set post_status as future if future date
						$query = "UPDATE " . $wpdb->prefix . "posts SET post_status = 'future', post_date_gmt = '".$_POST["setall_post_date"]."' WHERE $ids_query";
						$results = $wpdb->get_results($query);
					}

					$query = "UPDATE " . $wpdb->prefix . "posts SET $value = '".$_POST["setall_$value2"]."' WHERE $ids_query";
					$results = $wpdb->get_results($query);

					$response.="<span class=\"tubeace-succmsg\">Updated all '$value' to:</span> ".esc_html($_POST["setall_$value2"])."<br>";				
				}
			}			
		}

		//handle setall_'s for tags and performers
		$setall_array = array('tags','performers');
		foreach($setall_array as $value){

			foreach($ids_arr2 as $id){

				$my_post = array();
				$my_post['ID'] = $id;

				define('WP_POST_REVISIONS', false );

				if($value=="tags" && strlen($_POST["setall_tags"])>0){

					$my_post['tags_input'] = $_POST["setall_$value"];

					// Update the post into the database
					wp_update_post($my_post);
				}

				if($value=="performers" && strlen($_POST["setall_performers"])>0){

					$performersArr = explode(",", $_POST["setall_performers"]);
					$my_post['tax_input'] = array( 'performer' => $performersArr );

					// Update the post into the database
					wp_update_post($my_post);					
				}
			}

			if($value=="tags" && strlen($_POST["setall_tags"])>0){
				$response.="<span class=\"tubeace-succmsg\">Updated all '$value' to:</span> ".esc_html($_POST["setall_$value"])."<br>";	
			}

			if($value=="performers" && strlen($_POST["setall_performers"])>0){
				$response.="<span class=\"tubeace-succmsg\">Updated all '$value' to:</span> ".ecs_html($_POST["setall_$value"])."<br>";	
			}
		}


		//handle setall_'s for metadata
		$setall_array = array('site','sponsor_link_url','sponsor_link_txt','misc1','misc2','misc3','misc4','misc5');
		foreach($setall_array as $value){


			if(strlen($_POST["setall_$value"])>0){

				foreach($ids_arr3 as $id){

					update_post_meta($id, $value, stripslashes($_POST["setall_$value"]));
				}

				$response.="<span class=\"tubeace-succmsg\">Updated all '$value' to:</span> ".htmlentities(stripslashes(stripslashes($_POST["setall_$value"])))."<br>";	
			}
		}



		//show done
		$end = $first_id + $size - 1;
		

		$response.="<br /><span class=\"tubeace-succmsg\">Import Complete! Added $linesAdded videos to the database!</span>\n";

		//show button for generate thumbs. copy videos
		if($thumb_source=="ffmpeg" || $save_videos==1){
	
			$response.="<a href=\"".admin_url('admin.php?page=tubeace/tubeace-generate-thumbs-copy-videos.php')."\">";
			if($save_videos==1 && $thumb_source=="ffmpeg"){
				$response.="Generate Thumbnails & Copying Videos";
			}
			if($save_videos==1 && $thumb_source!="ffmpeg"){
				$response.="Start Copying Videos";
			}
			if(!$save_videos && $thumb_source=="ffmpeg"){
				$response.="Start Generating Thumbnails";
			}
			$response.="</a>";
		}
		
	}
	
	echo json_encode(array("done"=>$done,"response"=>$response,"ids"=>$ids));	
?>