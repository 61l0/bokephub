<?php

		//outputAll used for cron emails
		$output="";
		$outputAll="";

	  	for($page=$start;$page<=$end;$page++){

	  		echo $call = "http://www.pornhub.com/webmasters/search?search=$keyword&thumbsize=medium&page=$page";

			$data = file_get_contents($call);

			$data = json_decode($data);

			$videos	= $data->videos;				
		 
		 	//result #
		 	$r=0;
			
		 	//var_dump($videos);

			foreach($videos as $video) {

				$title = addslashes($video->title);
				$video_id = $video->video_id;

				//reset
				$errorInsert = "";
				$duration="";
				$hours="";
				$minutes="";
				$seconds="";
				
				$tags="";
				$performersArr="";

				$args = array(
					'meta_query' => array(
					 'relation' => 'AND',
						array(
							'key' => 'video_id',
							'value' => $video_id,
						),
						array(
							'key' => 'site',
							'value' => 'pornhub.com'
						)
					)
				);

				$query = new WP_Query( $args );

				$num_rows = $query->found_posts;

				if($num_rows>0){
					echo $output ="<span class=\"tubeace-errormsg\">Video skipped! Video titled '$title' already exists in database.</span><br>";
					$outputAll.= $output;
					ob_flush();
					flush();						
				} else {	
	
					//check thumbs valid
					foreach($video->thumbs as $val){

						if(!@getimagesize($val->src) && !$errorInsert){
							echo $output ="<span class=\"tubeace-errormsg\">Video '$title' skipped - Thumbnail URL invalid!</span><br>";
							$outputAll.= $output;
							$errorInsert=1;
						}
					}					

					if(!$errorInsert){

						$str_time = $video->duration;
						$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);
						sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
						$duration = $hours * 3600 + $minutes * 60 + $seconds;						
				
						if(!empty($video->tags)){

							$tags = "";
							foreach($video->tags as $tag){
								
								$tags.= $tag->tag_name.", ";
							}
							
							$tags = addslashes(rtrim($tags,", "));
						}

						if(!empty($video->pornstars)){

							$performersArr = array();
							foreach($video->pornstars as $pornstar){
								
								$performersArr[] = $pornstar->pornstar_name;
							}
						}						

						//make pending until thumb generated
						if($status=="publish"){
							$status_alt = "draft";
						} 

						//insert
						$my_post = array(
						  'post_title'    => $title,
						  'post_content'  => $description,
						  'post_status'   => $status_alt,
						  'post_author'   => $sponsor,
						  'post_category' => $post_category,
						  'tags_input' => $tags
						  //'tax_input' => array( 'performer' => $performersArr ) // won't work for cron
						);

						// Insert the post into the database
						if($lastID = wp_insert_post( $my_post )){

							//custom taxonomy must be done using wp_set_object_terms() for cron
							wp_set_object_terms($lastID, $performersArr, 'performer');							

							//add meta value
							add_post_meta($lastID, 'video_id', $video_id);
							add_post_meta($lastID, 'duration', $duration);
							add_post_meta($lastID, 'site', 'pornhub.com');
							add_post_meta($lastID, 'sponsor_link_url', $sponsor_link_url);
							add_post_meta($lastID, 'sponsor_link_txt', $sponsor_link_txt);
							add_post_meta($lastID, 'misc1', $misc1);
							add_post_meta($lastID, 'misc2', $misc2);
							add_post_meta($lastID, 'misc3', $misc3);
							add_post_meta($lastID, 'misc4', $misc4);
							add_post_meta($lastID, 'misc5', $misc5);

							echo $output ="<span class=\"tubeace-succmsg\">Video titled '$title' added!</span><br>\n";
							$outputAll.= $output;
						} else {
							echo $output ="<span class=\"tubeace-errormsg\">Video titled '$title' not added!</span><br>\n";
							$outputAll.= $output;
						}

						ob_flush();
						flush();	

						$ids.= $lastID.",";
						$subPath = tubeace_sub_dir_path($lastID);

						//create thumb dir
						$upload_dir = wp_upload_dir();
						$create_path = $upload_dir[basedir]."/tubeace-thumbs/".$subPath;
						wp_mkdir_p($create_path);					
						
						$i = 1;
						foreach($video->thumbs as $val){
							
							list($width, $height) = getimagesize($val->src); 
							$thumbDest = $upload_dir[basedir]."/tubeace-thumbs/".$subPath."/".$lastID."_".$i.".jpg";
							
							if($width==get_site_option('tubeace_thumb_width') && $height==get_site_option('tubeace_thumb_height')){
								copy($val->src,$thumbDest);
							} else {
								tubeace_resize_thumb($val->src,$thumbDest,'jpg',$width,$height,get_site_option('tubeace_thumb_width'),get_site_option('tubeace_thumb_height'));	
							}
					
							$i++;
						}	
						
						$frames = $i-1;

						//update
						add_post_meta($lastID, 'saved_thmb', $frames);
						add_post_meta($lastID, 'def_thmb', get_site_option('tubeace_def_thmb'));	

						//thumbs generated, no set to publish
						if($status=="publish"){

							$my_post = array(
							  'ID'           => $lastID,
							  'post_status' => 'publish'
							);
							wp_update_post( $my_post );

						} 						

						//auto-scheduling
						if(get_site_option('tubeace_schedule_per_day')>0){
							
							$schedDate = tubeace_auto_sched_next_date(0);

							$wpdb->update( $wpdb->prefix . 'posts', array('post_date' => "$schedDate 00:00:00", 'post_date_gmt' => "$schedDate 00:00:00"), array('id' => $lastID));
												
							echo $output ="<span class=\"tubeace-succmsg\">Video #$lastID Auto-Scheduled to $schedDate</span><br>";
							$outputAll.= $output;
						}
					}
				}

				$r++;

				if($r==20){
					echo $output ="<b>Imported Page $page</b><br><br>";	
					$outputAll.= $output;
				    ob_flush();
				    flush();	
				}				
			}

			
	  	}

?>