<?php
set_time_limit(0);

tubeace_header(1);

//populate single form
if($_POST['m']=="single"){ 

	$video_id = $_POST['video_id'];
	$url = "http://api.redtube.com/?data=redtube.Videos.getVideoById&video_id=$video_id&output=json&thumbsize=big";
	$data = file_get_contents($url);

	$data = json_decode($data);
	
	if(!empty($data->video->tags)){

		foreach($data->video->tags as $tag){
			
			$tags.= $tag.", ";
		}
	}

	if(!empty($data->video->stars)){

		foreach($data->video->stars as $star){
			
			$performers.= $star.", ";
		}	
	}
	
	$tags = rtrim($tags,", ");
	$performers = rtrim($performers,", ");
	
	$video_id = $data->video->video_id;
	$default_thumb = $data->video->default_thumb;
	$url = $data->video->url;
	$title = $data->video->title;
	$duration = $data->video->duration;
	
	sscanf($duration, "%d:%d:%d", $hours, $minutes, $seconds);
	$duration = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
	
	//check	duplicate
	//$sql = mysql_query("SELECT * FROM videos WHERE video_id = '$video_id' AND site= 'redtube'", $conn);
	//$num = mysql_num_rows($sql);
	
	if($num>0){
		echo"<span class=\"errormsg\">WARNING: Video already exists in database!</a>";
	}
	
	echo"
	<div class=\"wrap\">
		<h2>RedTube Import - Single Video</h2>
		<form action=\"".admin_url('admin.php?page=tubeace/tubeace-redtube.php')."\" method=\"post\">

			<table class=\"form-table\">
			    <tbody>
			        <tr>
			    		<th><label for=\"video_id\">Thumbnail</label></th>
			        	<td>
						  <a href=\"$url\" target=\"_blank\"><img src=\"$default_thumb\"><a href=\"$url\" target=\"_blank\"><br>
						  <a href=\"$url\" target=\"_blank\">View Video Page</a>
			        	</td>		        	
			        </tr>	
			    	<tr>
			    		<th><label for=\"post_status\">Status</label></th>
			        	<td>
							  <select name=\"post_status\" id=\"post_status\">
								<option>publish</option>
								<option>pending</option>
								<option>draft</option>
								<option>future</option>
								<option>private</option>
							  </select>
			        	</td>	
			        </tr>	

			        <tr>
			    		<th><label for=\"post_date\">Post Date</label></th>
			        	<td>
			        	  <input type=\"text\" class=\"\" name=\"post_date\" value=\"".date("Y-m-d H:i:s")."\">
						</td>		        	
			        </tr>			   
			    	<tr>
			    		<th><label for=\"file\">Author / Sponsor</label></th>
			        	<td>
							  <select name=\"sponsor\" id=\"sponsor\">";
								tubeace_get_users_with_role(array('Contributor','Administrator'),null);
								echo"
							  </select>
							  To add a sponsor, <a href=\"user-new.php\">add a new user</a> with a \"Contributor\" Role.
			        	</td>
			        </tr>		        
			    	<tr>
			    		<th><label for=\"category\">Category</label></th>
			        	<td><ul id=\"categorychecklist\" data-wp-lists=\"list:category\" class=\"categorychecklist form-no-clear\">";
							wp_category_checklist( $args );
							echo"</ul> 
			        	</td>
			        </tr>	
			        <tr>
			    		<th><label for=\"title\">Title</label></th>
			        	<td>
			        	  <input type=\"text\" class=\"input-400\" name=\"title\" value=\"$title\">
						</td>		        	
			        </tr>	
			        <tr>
			    		<th><label for=\"tags\">Tags</label></th>
			        	<td>
			        	  <input type=\"text\" class=\"input-400\" name=\"tags\" value=\"$tags\">
						</td>		        	
			        </tr>
			        <tr>
			    		<th><label for=\"performers\">Performers</label></th>
			        	<td>
			        	  <input type=\"text\" class=\"input-400\" name=\"performers\" value=\"$performers\">
						</td>		        	
			        </tr>	
			        <tr>
			    		<th><label for=\"duration\">Duration</label></th>
			        	<td>
			        	  <input type=\"text\" class=\"input-60\" name=\"duration\" value=\"$duration\">
						</td>		        	
			        </tr>	
			        <tr>
			    		<th><label for=\"description\">Description</label></th>
			        	<td>
			        	  <textarea name=\"description\" class=\"input-mini\" rows=\"5\" cols=\"40\"></textarea>
						</td>		        	
			        </tr>			        
			        <tr>
			    		<th><label for=\"sponsor_link_txt\">Sponsor Link TXT</label></th>
			        	<td>
			        	  <input type=\"text\" class=\"input-400\" name=\"sponsor_link_txt\">
						</td>		        	
			        </tr>
			        <tr>
			    		<th><label for=\"sponsor_link_url\">Sponsor Link URL</label></th>
			        	<td>
			        	  <input type=\"text\" class=\"input-400\" name=\"sponsor_link_url\">
						</td>		        	
			        </tr>		        
			        <tr>
			    		<th><label for=\"misc1\">Misc 1</label></th>
			        	<td>
			        	  <textarea name=\"misc1\"></textarea>
						</td>		        	
			        </tr>	
			        <tr>
			    		<th><label for=\"misc2\">Misc 2</label></th>
			        	<td>
			        	  <textarea name=\"misc2\"></textarea>
						</td>		        	
			        </tr>	
			        <tr>
			    		<th><label for=\"misc3\">Misc 3</label></th>
			        	<td>
			        	  <textarea name=\"misc3\"></textarea>
						</td>		        	
			        </tr>	
			        <tr>
			    		<th><label for=\"misc4\">Misc 4</label></th>
			        	<td>
			        	  <textarea name=\"misc4\"></textarea>
						</td>		        	
			        </tr>
			        <tr>
			    		<th><label for=\"misc5\">Misc 5</label></th>
			        	<td>
			        	  <textarea name=\"misc5\"></textarea>
						</td>		        	
			        </tr>			 
			        <tr>
			    		<th></th>
			        	<td>
						 	<input type=\"hidden\" name=\"m\" value=\"import\">
						  	<input type=\"hidden\" name=\"video_id\" value=\"$video_id\">	  
						  
						    <input class=\"button-primary\" type=\"submit\" name=\"Submit\" value=\"Add Video\">
						</td>		        	
			        </tr>		
			    </tbody>
			</table>
		</form>
	</div>";	
}

//import single
if($_POST['m']=="import"){

	$video_id = $_POST['video_id'];
	$post_date = $_POST['post_date'];
	$post_status = $_POST['post_status'];
	$sponsor = $_POST['sponsor'];
	$title = $_POST['title'];
	$tags = $_POST['tags'];
	$duration = $_POST['duration'];
	$post_category = $_POST['post_category'];
	
	$description = $_POST['description'];
	$performers = $_POST['performers'];
	$sponsor_link_txt = $_POST['sponsor_link_txt'];
	$sponsor_link_url = $_POST['sponsor_link_url'];
	$misc1 = $_POST['misc1'];
	$misc2 = $_POST['misc2'];
	$misc3 = $_POST['misc3'];
	$misc4 = $_POST['misc4'];
	$misc5 = $_POST['misc5'];
		
	$performersArr = explode(",", $performers);

	//insert
	$my_post = array(
	  'post_title'    => $title,
	  'post_content'  => $description,
	  'post_status'   => $post_status,
	  'post_author'   => $sponsor,
	  'post_category' => $post_category,
	  'tags_input' => $tags,
	  'tax_input' => array( 'performer' => $performersArr ) 
	);

	//print_r($my_post);

	$lastID = wp_insert_post( $my_post );


	//add meta value
	add_post_meta($lastID, 'video_id', $video_id);
	add_post_meta($lastID, 'duration', $duration);
	add_post_meta($lastID, 'site', 'redtube.com');
	add_post_meta($lastID, 'sponsor_link_url', $sponsor_link_url);
	add_post_meta($lastID, 'sponsor_link_txt', $sponsor_link_txt);
	add_post_meta($lastID, 'misc1', $misc1);
	add_post_meta($lastID, 'misc2', $misc2);
	add_post_meta($lastID, 'misc3', $misc3);
	add_post_meta($lastID, 'misc4', $misc4);
	add_post_meta($lastID, 'misc5', $misc5);

	$subPath = tubeace_sub_dir_path($lastID);		

	//create thumb dir
	$upload_dir = wp_upload_dir();
	$create_path = $upload_dir['basedir']."/tubeace-thumbs/".$subPath;
	wp_mkdir_p($create_path);

	$data = file_get_contents("http://api.redtube.com/?data=redtube.Videos.getVideoById&video_id=$video_id&output=json&thumbsize=big");
	
	$data = json_decode($data);	
	
	$i = 1;

	$tubeace_thumb_width = get_site_option( 'tubeace_thumb_width' );
	$tubeace_thumb_height = get_site_option( 'tubeace_thumb_height' );

	foreach($data->video->thumbs as $val){
		
		list($width, $height) = getimagesize($val->src);
		
		$thumbDest = $upload_dir['basedir']."/tubeace-thumbs/".$subPath."/".$lastID."_".$i.".jpg";

		tubeace_resize_thumb($val->src,$thumbDest,'jpg',$width,$height,$tubeace_thumb_width,$tubeace_thumb_height);
		$i++;
	}	
	
	$frames = $i-1;	

	$tubeace_def_thmb = get_site_option( 'tubeace_def_thmb' );

	add_post_meta($lastID, 'saved_thmb', $frames);
	add_post_meta($lastID, 'def_thmb', $tubeace_def_thmb);	

	echo"
	<div class=\"wrap\">
		<h2>RedTube Import - Single Video</h2>
		<span class=\"tubeace-succmsg\">Video titled '$title' added! </span>
	</div>";
}

//import all
if($_GET['all']){

	$tagv = urlencode($_GET['tag']);
	$keyword = urlencode($_GET['keyword']);
	
	$start = $_GET['start'];
	$end = $_GET['end'];
	
	$status = $_GET['status'];
	$sponsor = $_GET['sponsor'];
	$post_date = $_GET['post_date'];
	$post_category = $_GET['post_category'];
	
	$description = $_GET['description'];
	$performers = $_GET['performers'];
	$sponsor_link_txt = $_GET['sponsor_link_txt'];
	$sponsor_link_url = $_GET['sponsor_link_url'];
	$misc1 = $_GET['misc1'];
	$misc2 = $_GET['misc2'];
	$misc3 = $_GET['misc3'];
	$misc4 = $_GET['misc4'];
	$misc5 = $_GET['misc5'];	

	echo"
	<div class=\"wrap\">
	  <h2>RedTube Import</h2>";

	  include('inc/import-redtube.php');

	echo"
	</div>";
}

//cron
if($_GET['cron']){


	$tag = $_GET['tag'];
	$keyword = $_GET['keyword'];
	
	$start = $_GET['start'];
	$end = $_GET['end'];
	
	$status = $_GET['status'];
	$sponsor = $_GET['sponsor'];
	$post_date = $_GET['post_date'];
	$post_category = $_GET['post_category'];
	
	$description = $_GET['description'];
	$performers = $_GET['performers'];
	$sponsor_link_txt = $_GET['sponsor_link_txt'];
	$sponsor_link_url = $_GET['sponsor_link_url'];
	$misc1 = $_GET['misc1'];
	$misc2 = $_GET['misc2'];
	$misc3 = $_GET['misc3'];
	$misc4 = $_GET['misc4'];
	$misc5 = $_GET['misc5'];	

	$cron_name = $_GET['cron_name'];	
	$cron_frequency = $_GET['cron_frequency'];	

	echo"
	<div class=\"wrap\">
	  <h2>RedTube Cron Setup</h2>";

	//assoc array of cron values to store in options
	$cronValues=array("keyword"=>$keyword,"tag"=>$tag,"start"=>$start,"end"=>$end,"status"=>$status,"sponsor"=>$sponsor,"post_date"=>$post_date,"post_category"=>$post_category,
		"description"=>$description,"performers"=>$performers,"sponsor_link_txt"=>$sponsor_link_txt,"sponsor_link_url"=>$sponsor_link_url,
		"misc1"=>$misc1,"misc2"=>$misc2,"misc3"=>$misc3,"misc4"=>$misc4,"misc5"=>$misc5);


	if ( ! wp_next_scheduled( 'tubeace_'.$cron_frequency ) ) {
	  wp_schedule_event( time(), $cron_frequency, 'tubeace_'.$cron_frequency );
	}

	//get existing cron jobs (serialized array) for frequency
	$existingCronNames = get_site_option('tubeace_cron_redtube_'.$cron_frequency);

	//make sure cron name not empty
	if(empty($cron_name)){

		echo"<div class=\"error\"><p>Cron name required to create cron import!</p></div>";

	//if none exist, add new
	} elseif(empty($existingCronNames)){

		$cronNames = array($cron_name);

		update_site_option('tubeace_cron_redtube_'.$cron_frequency, $cronNames);	
		update_site_option("tubeace_cron_redtube_".$cron_name."_".$cron_frequency, $cronValues);

		echo"<div class=\"updated\"><p>Added $cron_frequency cron named '$cron_name'.</p></div>";

	} else { //if already exists, we need to add name to tubeace_cron_redtube_{frequency}

		//first, make sure name doesn't exist
		if(in_array($cron_name,$existingCronNames)){

			echo"<div class=\"error\"><p>Cron name '$cron_name' already used!</p></div>";

		} else {

			$cron_name_arr = array($cron_name);//make array to merge
			$cronNames = array_merge($existingCronNames,$cron_name_arr);

			update_site_option('tubeace_cron_redtube_'.$cron_frequency, $cronNames);	
			update_site_option("tubeace_cron_redtube_".$cron_name."_".$cron_frequency, $cronValues);

			echo"<div class=\"updated\"><p>Added another $cron_frequency cron named '$cron_name'.</p></div>";
		}
	}

	echo"
	</div>";	
}

//form
if(empty($_POST) && empty($_GET['all']) && empty($_GET['cron'])) {
?>
<div class="wrap">
	<h2>RedTube Import</h2>
	<h3>Import Single Video</h3>
	<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-redtube.php'); ?>" method="post">
		<table class="form-table">
		    <tbody>
		        <tr>
		    		<th><label for="video_id">RedTube Video ID</label></th>
		        	<td>
		        		<input name="video_id" type="text" class="input-160" id="id">
		        	</td>		        	
		        </tr>	
		        <tr>
		    		<td></td>
		        	<td>
		        		<input type="hidden" name="page" value="tubeace/tubeace-redtube.php">
		        		<input type="hidden" name="m" value="single">
		        		<input type="submit" value="View" class="button-primary" name="Submit">
		        	</td>		        	
		        </tr>	
		    </tbody>
		</table>
	</form>

	<h3>Search Videos</h3>
	<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-redtube.php'); ?>" method="get">
		<table class="form-table">
		    <tbody>
		        <tr>
		    		<th><label for="keyword">Keyword</label></th>
		        	<td>
		        		<input name="keyword" type="text" class="input-320" id="keyword">
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="tag">Tag</label></th>
		        	<td>
		        		<input name="tag" type="text" class="input-320" id="tag">
		        	</td>		        	
		        </tr>		
		        <tr>
		    		<th><label for="start">Start Page</label></th>
		        	<td>
		        		<input name="start" type="text" class="input-60" id="start" value="1">
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="end">End Page</label></th>
		        	<td>
		        		<input name="end" type="text" class="input-60" id="end" value="20">
		        	</td>		        	
		        </tr>
		    </tbody>
		</table>

		<h3>SET ALL Values</h3>

		<table class="form-table">
		    <tbody>
		        <tr>
		    		<th><label for="status">Status</label></th>
		        	<td>
					  <select name="status" id="status">
						<option value="publish">Publish</option>
						<option value="pending">Pending</option>
						<option value="draft">Draft</option>
						<option value="future">Future</option>
						<option value="private">Private</option>
					  </select>
		        	</td>		        	
		        </tr>
		        <tr>
		        	<th><label for="sponsor">Author / Sponsor</label></th>
		        	<td>
						  <select name="sponsor" id="sponsor">
							<?php tubeace_get_users_with_role(array('Contributor','Administrator'), null); ?>
						  </select>
						  To add a sponsor, <a href="user-new.php">add a new user</a> with a "Contributor" Role.
		        	</td>	
			    </tr>		        
		        <tr>
		    		<th><label for="post_date">Post Date</label></th>
		        	<td>
		        		<input name="post_date" type="text" class="input-160" id="post_date" value="<?php echo date("Y-m-d H:i:s"); ?>">
		        	</td>		        	
		        </tr>		
		        <tr>
		    		<th><label for="start">Category</label></th>
		        	<td>
						<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
						  <?php wp_category_checklist( $args ); ?>
						</ul> 		        		
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="description">Description</label></th>
		        	<td>
		        		<textarea name="description" class="input-320" id="description"></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="performers">Performers</label></th>
		        	<td>
		        		<input name="performers" type="text" class="input-320" id="performers">
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="sponsor_link_txt">Sponsor Link TXT</label></th>
		        	<td>
		        		<input name="sponsor_link_txt" type="text" class="input-320" id="sponsor_link_txt">
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="sponsor_link_url">Sponsor Link URL</label></th>
		        	<td>
		        		<input name="sponsor_link_url" type="text" class="input-320" id="sponsor_link_url">
		        	</td>		        	
		        </tr>		
		        <tr>
		    		<th><label for="misc1">Misc 1</label></th>
		        	<td>
		        		<textarea name="misc1" class="input-320" id="misc1"></textarea>
		        	</td>		        	
		        </tr>	
		        <tr>
		    		<th><label for="misc2">Misc 2</label></th>
		        	<td>
		        		<textarea name="misc2" class="input-320" id="misc2"></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="misc3">Misc 3</label></th>
		        	<td>
		        		<textarea name="misc3" class="input-320" id="misc3"></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="misc4">Misc 4</label></th>
		        	<td>
		        		<textarea name="misc4" class="input-320" id="misc4"></textarea>
		        	</td>		        	
		        </tr>
		        <tr>
		    		<th><label for="misc5">Misc 5</label></th>
		        	<td>
		        		<textarea name="misc5" class="input-320" id="misc5"></textarea>
		        	</td>		        	
		        </tr>		        
		        <tr>
		    		<th></th>
		        	<td>
		        		<input type="hidden" name="page" value="tubeace/tubeace-redtube.php">
						<input type="submit" value="Import All" class="button-primary" name="all">
		        	</td>		        	
		        </tr>
		    </tbody>
		</table>

		<h3>Cron</h3>

		<table class="form-table">
		    <tbody>		

		    	<tr>
		    		<th><label for="cron_name">Cron Name</label></th>
		        	<td>
		        		<input name="cron_name" type="text" class="input-320" id="cron_name"> (No spaces, use underscore)
		        	</td>		        	
		        </tr>
		    	<tr>
		    		<th><label for="cron_frequency">Frequency</label></th>
		        	<td>
					  <select name="cron_frequency" id="cron_frequency">
						<option value="hourly">Hourly</option>
						<option value="twicedaily">Twice Daily</option>
						<option value="daily">Daily</option>
					  </select>		        		

		        	</td>		        	
		        </tr>		        
		        <tr>
		    		<th></th>
		        	<td>
						<input type="submit" value="Save as Cron" class="button-primary" name="cron">
		        	</td>		        	
		        </tr>		        
		    </tbody>
		</table>
	</form>
</div>
<?php
}
?>