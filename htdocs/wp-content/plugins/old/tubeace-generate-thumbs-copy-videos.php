<?php
set_time_limit(0);

tubeace_header(0);
?>

<div class="wrap">

	<h2>Generate Thumbnails / Copy Videos</h2>

		<?php
		add_filter( 'post_limits', 'my_post_limits' );
		function my_post_limits( $limit ) {
		    return '';
		}

		$args = array(
			'meta_query' => array(
			 'relation' => 'OR',
				array(
					'key' => 'await_thumb',
					'value' => 1,
				),
				array(
					'key' => 'await_video',
					'value' => 1
				)
			)
		);

		$query = new WP_Query( $args );


		$num_rows = $query->found_posts;


		while ( $query->have_posts() ) :
			$query->the_post();

			$video_url = get_post_meta(get_the_ID(), 'video_url', true);
			$videoExt = strrchr($video_url, '.');

			$upload_dir = wp_upload_dir();
			$subPath = tubeace_sub_dir_path(get_the_ID());

			echo"Videos awaiting Thumbnail Generation and/or Video Copying: $num_rows<br/><br/>";

			 ob_flush();
			 flush();				

			//determine video save path
			//if copy video
			if(get_post_meta(get_the_ID(), 'await_video', true)==1){

				//create video path
				$create_path = $upload_dir[basedir]."/tubeace-videos/".$subPath;
				wp_mkdir_p($create_path);

				$savedFile = $create_path."/".get_the_ID().$videoExt;

				//update video _url
				$new_video_url = $upload_dir[baseurl]."/tubeace-videos/".$subPath."/".get_the_ID().$videoExt;
				update_post_meta(get_the_ID(), 'video_url', $new_video_url);

			} else {

				$savedFile = $upload_dir[basedir]."/saved".$videoExt;
			}

			//save video to server, needs to be done either way
			if (!copy($video_url, $savedFile)) {
				echo "failed to copy $video_url...\n";
				exit();
			}	

			//if await_video
			if(get_post_meta(get_the_ID(), 'await_video', true)==1){

				update_post_meta(get_the_ID(), 'await_video', 0);	
				echo"<span class=\"tubeace-succmsg\">Copied Video '<b>".get_the_title()."</b>' to server.</span><br/><br/>";

				//update await_video
				update_post_meta(get_the_ID(), 'await_video', 0);
			}						

			//if await_thumb
			if(get_post_meta(get_the_ID(), 'await_thumb', true)==1){

				echo "Generating thumbnails for video '<b>".get_the_title()."</b>'... ";

			    ob_flush();
			    flush();	

				
				//create thumb dir
				$create_path = $upload_dir[basedir]."/tubeace-thumbs/".$subPath;
				wp_mkdir_p($create_path);	

				$videoDataArr = tubeace_video_metadata($video_url);

				$duration = round($videoDataArr[duration],2);
				if($duration==0) $duration = $vi['duration'];
				
				tubeace_ffmpeg_thumbs(get_the_ID(),$duration,get_site_option('tubeace_thumb_width'),get_site_option('tubeace_thumb_height'),$create_path,$savedFile);
				
				//get frames
				if(get_site_option('tubeace_frames_option')=="total_frames"){ //total frames
					$frames = get_site_option('tubeace_frames_value');
				} else { //seconds between frames
					$sec = get_site_option('tubeace_frames_value');
					$frames = floor((($duration - 2) / $sec));
				}	

				//update meta
				update_post_meta(get_the_ID(), 'saved_thmb', $frames);
				update_post_meta(get_the_ID(), 'def_thmb', get_site_option('tubeace_def_thmb'));	
				update_post_meta(get_the_ID(), 'await_thumb', 0);	

				echo"<span class=\"tubeace-succmsg\">$frames Thumbs generated with dimensions: ". get_site_option('tubeace_thumb_width') . "x" . get_site_option('tubeace_thumb_height')."</span><br/><br/>";

				$thumbs_dir = $upload_dir[baseurl]."/tubeace-thumbs/".$subPath;

				for($i=1; $i<=$frames; $i++){

					echo" <img src=\"".$thumbs_dir."/".get_the_id()."_".$i.".jpg\"> ";
				}

				echo"<br /><br />";
			}

			//update status
			$status_after = get_post_meta(get_the_ID(), 'status_after', true);

			$my_post = array();
			$my_post['ID'] = get_the_ID();
			$my_post['post_status'] = $status_after;

			// Update the post into the database
			wp_update_post($my_post);			


			$num_rows--;

		    ob_flush();
		    flush();

		endwhile;

		echo"<h2>All Thumbnails Done.</h2>";
?>
</div>