<a href="http://www.tubeace.com/" target="_blank"><img src="<?php echo plugins_url('/tubeace/images/logo.png') ?>" alt="Tube Ace"></a><br />
<?php

if(!empty($_POST['Submit'])) {

	echo"<span class=\"tubeace-succmsg\">Updated Settings.</span>";

	$tubeace_flowplayer3_code = stripslashes($_POST['tubeace_flowplayer3_code']);
	$tubeace_flowplayer5_code = stripslashes($_POST['tubeace_flowplayer5_code']);
	$tubeace_default_video_player = stripslashes($_POST['tubeace_default_video_player']);
	$tubeace_redtube_video_player_code = stripslashes($_POST['tubeace_redtube_video_player_code']);
	$tubeace_bangyoulater_video_player_code = stripslashes($_POST['tubeace_bangyoulater_video_player_code']);
	$tubeace_drtuber_video_player_code = stripslashes($_POST['tubeace_drtuber_video_player_code']);
	$tubeace_keezmovies_video_player_code = stripslashes($_POST['tubeace_keezmovies_video_player_code']);
	$tubeace_porntube_video_player_code = stripslashes($_POST['tubeace_porntube_video_player_code']);
	$tubeace_pornhub_video_player_code = stripslashes($_POST['tubeace_pornhub_video_player_code']);
	$tubeace_spankwire_video_player_code = stripslashes($_POST['tubeace_spankwire_video_player_code']);
	$tubeace_sunporno_video_player_code = stripslashes($_POST['tubeace_sunporno_video_player_code']);
	$tubeace_tube8_video_player_code = stripslashes($_POST['tubeace_tube8_video_player_code']);
	$tubeace_xhamster_video_player_code = stripslashes($_POST['tubeace_xhamster_video_player_code']);
	$tubeace_xvideos_video_player_code = stripslashes($_POST['tubeace_xvideos_video_player_code']);
	$tubeace_youporn_video_player_code = stripslashes($_POST['tubeace_youporn_video_player_code']);
	$tubeace_thumb_width = trim($_POST['tubeace_thumb_width']);
	$tubeace_thumb_height = trim($_POST['tubeace_thumb_height']);
	$tubeace_frames_option = trim($_POST['tubeace_frames_option']);
	$tubeace_frames_value = trim($_POST['tubeace_frames_value']);
	$tubeace_def_thmb = trim($_POST['tubeace_def_thmb']);
	$tubeace_ffmpeg_path = stripslashes($_POST['tubeace_ffmpeg_path']);
	$schedule_per_day = trim($_POST['tubeace_schedule_per_day']);
	$tubeace_cron_email = stripslashes($_POST['tubeace_cron_email']);
	$tubeace_alternate_video_url = stripslashes($_POST['tubeace_alternate_video_url']);
	
	update_site_option('tubeace_flowplayer3_code', $tubeace_flowplayer3_code);
	update_site_option('tubeace_flowplayer5_code', $tubeace_flowplayer5_code);
	update_site_option('tubeace_default_video_player', $tubeace_default_video_player);
	update_site_option('tubeace_redtube_video_player_code', $tubeace_redtube_video_player_code);
	update_site_option('tubeace_bangyoulater_video_player_code', $tubeace_bangyoulater_video_player_code);
	update_site_option('tubeace_drtuber_video_player_code', $tubeace_drtuber_video_player_code);
	update_site_option('tubeace_keezmovies_video_player_code', $tubeace_keezmovies_video_player_code);
	update_site_option('tubeace_porntube_video_player_code', $tubeace_porntube_video_player_code);
	update_site_option('tubeace_pornhub_video_player_code', $tubeace_pornhub_video_player_code);
	update_site_option('tubeace_spankwire_video_player_code', $tubeace_spankwire_video_player_code);
	update_site_option('tubeace_sunporno_video_player_code', $tubeace_sunporno_video_player_code);
	update_site_option('tubeace_tube8_video_player_code', $tubeace_tube8_video_player_code);
	update_site_option('tubeace_xhamster_video_player_code', $tubeace_xhamster_video_player_code);
	update_site_option('tubeace_xvideos_video_player_code', $tubeace_xvideos_video_player_code);
	update_site_option('tubeace_youporn_video_player_code', $tubeace_youporn_video_player_code);
	update_site_option('tubeace_thumb_width', $tubeace_thumb_width);
	update_site_option('tubeace_thumb_height', $tubeace_thumb_height);
	update_site_option('tubeace_frames_option', $tubeace_frames_option);
	update_site_option('tubeace_frames_value', $tubeace_frames_value);
	update_site_option('tubeace_def_thmb', $tubeace_def_thmb);
	update_site_option('tubeace_ffmpeg_path', $tubeace_ffmpeg_path);
	update_site_option('tubeace_schedule_per_day', $schedule_per_day);
	update_site_option('tubeace_cron_email', $tubeace_cron_email);
	update_site_option('tubeace_alternate_video_url', $tubeace_alternate_video_url);
}

?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2>Settings</h2>

	<?php

	$tubeace_flowplayer3_code = get_site_option( 'tubeace_flowplayer3_code' );
	$tubeace_flowplayer5_code = get_site_option( 'tubeace_flowplayer5_code' );
	$tubeace_default_video_player = get_site_option( 'tubeace_default_video_player' );
	$tubeace_redtube_video_player_code = get_site_option( 'tubeace_redtube_video_player_code' );
	$tubeace_bangyoulater_video_player_code = get_site_option( 'tubeace_bangyoulater_video_player_code' );
	$tubeace_drtuber_video_player_code = get_site_option( 'tubeace_drtuber_video_player_code' );
	$tubeace_keezmovies_video_player_code = get_site_option( 'tubeace_keezmovies_video_player_code' );
	$tubeace_porntube_video_player_code = get_site_option( 'tubeace_porntube_video_player_code' );
	$tubeace_pornhub_video_player_code = get_site_option( 'tubeace_pornhub_video_player_code' );
	$tubeace_spankwire_video_player_code = get_site_option( 'tubeace_spankwire_video_player_code' );
	$tubeace_sunporno_video_player_code = get_site_option( 'tubeace_sunporno_video_player_code' );
	$tubeace_tube8_video_player_code = get_site_option( 'tubeace_tube8_video_player_code' );
	$tubeace_xhamster_video_player_code = get_site_option( 'tubeace_xhamster_video_player_code' );
	$tubeace_xvideos_video_player_code = get_site_option( 'tubeace_xvideos_video_player_code' );
	$tubeace_youporn_video_player_code = get_site_option( 'tubeace_youporn_video_player_code' );
	$tubeace_thumb_width = get_site_option( 'tubeace_thumb_width' );
	$tubeace_thumb_height = get_site_option( 'tubeace_thumb_height' );
	$tubeace_frames_option = get_site_option( 'tubeace_frames_option' );
	$tubeace_frames_value = get_site_option( 'tubeace_frames_value' );
	$tubeace_def_thmb = get_site_option( 'tubeace_def_thmb' );
	$tubeace_ffmpeg_path = get_site_option( 'tubeace_ffmpeg_path' );
	$tubeace_schedule_per_day = get_site_option( 'tubeace_schedule_per_day' );
	$tubeace_cron_email = get_site_option( 'tubeace_cron_email' );
	$tubeace_alternate_video_url = get_site_option( 'tubeace_alternate_video_url' );
	?>

	<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-settings.php'); ?>" method="post">

		<table class="form-table">
		    <tbody>
		        <tr>
		    		<th><label for="tubeace_flowplayer3_code">Flowplayer 3 Code</label></th>
		        	<td>
		        		<textarea name="tubeace_flowplayer3_code" rows="10" style="width:600px"><?php echo $tubeace_flowplayer3_code;  ?></textarea>
		        	</td>		        	
		        </tr>		
		        <tr>
		    		<th><label for="tubeace_flowplayer5_code">Flowplayer 5 Code</label></th>
		        	<td>
		        		<textarea name="tubeace_flowplayer5_code" rows="5" style="width:600px"><?php echo $tubeace_flowplayer5_code; ?></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="tubeace_default_video_player">Default Video Player</label></th>
		        	<td>
				        <select name="tubeace_default_video_player">
				        <?php

				        if( $tubeace_default_video_player=="flowplayer3" ){
				        	echo"<option value=\"flowplayer3\" selected>Flowplayer 3</option>";	
				    	} else {
							echo"<option value=\"flowplayer5\" selected>Flowplayer 5</option>";	

				    	} ?>
				          
				          <option value="flowplayer3">Flowplayer 3</option>
				          <option value="flowplayer5">Flowplayer 5</option>        
				      	</select>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="video_player_code">RedTube Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_redtube_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_redtube_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="video_player_code">BangYouLater Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_bangyoulater_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_bangyoulater_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>	
		        <tr>
		    		<th><label for="video_player_code">DrTuber Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_drtuber_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_drtuber_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>	
		        <tr>
		    		<th><label for="video_player_code">KeezMovies Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_keezmovies_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_keezmovies_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>		        
		        <tr>
		    		<th><label for="video_player_code">PornTube Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_porntube_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_porntube_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="video_player_code">Pornhub Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_pornhub_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_pornhub_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="video_player_code">SpankWire Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_spankwire_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_spankwire_video_player_code ?></textarea>
		        	</td>
		        </tr>
		        <tr>
		    		<th><label for="tubeace_sunporno_video_player_code">Sun Porno Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_sunporno_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_sunporno_video_player_code ?></textarea>
		        	</td>
		        </tr>	
		        <tr>
		    		<th><label for="video_player_code">Tube8 Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_tube8_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_tube8_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>		        	             
		        <tr>
		    		<th><label for="video_player_code">xHamster Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_xhamster_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_xhamster_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="video_player_code">XVIDEOS Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_xvideos_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_xvideos_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>		        
		        <tr>
		    		<th><label for="video_player_code">YouPorn Video Player Code</label></th>
		        	<td>
		        		<textarea name="tubeace_youporn_video_player_code" rows="3" style="width:600px"><?php echo $tubeace_youporn_video_player_code ?></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="file">Thumbnail Dimensions</label></th>
		        	<td>
						Width <input type="text" value="<?php echo $tubeace_thumb_width ?>" name="tubeace_thumb_width" class="small-text">
						Height <input type="text" value="<?php echo $tubeace_thumb_height ?>" name="tubeace_thumb_height" class="small-text">
		        	</td>		        	
		        </tr>	
		        <tr>
		    		<th><label for="file">Thumbnail Generation Method</label></th>
		        	<td>
				        <select name="tubeace_frames_option">
				        <?php

				        if($tubeace_frames_option=='total_frames'){
				        	echo"<option value=\"total_frames\" selected>Total Frames</option>";	
				    	} else {
							echo"<option value=\"sec_between\" selected>Seconds Between Frames</option>";	

				    	} ?>
				          
				          <option value="sec_between">Seconds Between Frames</option>
				          <option value="total_frames">Total Frames</option>        
				      	</select>
        				Value
        				<input class="small-text" name="tubeace_frames_value" type="text" value="<?php echo $tubeace_frames_value ?>">
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="tubeace_def_thmb">Default Thumbnail Frame</label></th>
		        	<td>
        				<input class="small-text" name="tubeace_def_thmb" type="text" value="<?php echo $tubeace_def_thmb ?>">
		        	</td>		        	
		        </tr>

		    	<tr>
		    		<th><label for="file">FFmpeg Path</label></th>
		        	<td>
						<input type="text" value="<?php echo $tubeace_ffmpeg_path ?>" name="tubeace_ffmpeg_path">
		        	</td>
		        </tr>
		        <tr>
		    		<th><label for="file">Auto-Scheduling on Imported Videos</label></th>
		        	<td>
						Max videos per day <input type="text" value="<?php echo $tubeace_schedule_per_day ?>" name="tubeace_schedule_per_day" class="small-text"> (Set value to 0 to disable Auto-Scheduling.)
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="file">Email Cron Output To</label></th>
		        	<td>
						<input class="input-320" type="text" value="<?php echo $tubeace_cron_email ?>" name="tubeace_cron_email">
		        	</td>		        	
		        </tr>		        
		        <tr>
		    		<th><label for="file">Alternate Video URL</label></th>
		        	<td>
						<input class="input-320" type="text" value="<?php echo $tubeace_alternate_video_url ?>" name="tubeace_alternate_video_url">
						<br />Use {video_page_url} macro for original Video Page URL. Example: http://www.yoursite.com/out.php?{video_page_url}
		        	</td>		        	
		        </tr>		        
		    </tbody>
		</table>
		<input type="submit" value="Save Changes" class="button-primary" name="Submit">
	</form>
</div>