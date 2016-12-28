<?php
set_time_limit(0);

tubeace_header(1);
?>

<div class="wrap">
  <h2>Sun Porno Import</h2>

<?php


if(isset($_POST['import'])){

	$post_date = $_POST['post_date'];
	$status = $_POST['status'];
	$sponsor = $_POST['sponsor'];
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

	$tmpName  = $_FILES['uploaded']['tmp_name'];
	$fileSize = $_FILES['uploaded']['size'];		

	$upload_dir = wp_upload_dir();

	if(!empty($tmpName)){
		copy($tmpName, "$upload_dir[basedir]/tubeace-sunporno.csv");
	} else {
		echo"Import file not uploaded.";
		$error=1;
	}

	if(empty($error)){

        $fh = fopen("$upload_dir[basedir]/tubeace-sunporno.csv", 'r+');
        $content = fread($fh, filesize("$upload_dir[basedir]/tubeace-sunporno.csv"));
        fclose($fh);
        
        $content = trim($content);

        $lines = explode("\n", $content);
        $size = count($lines);
        
        $fieldArr = array('video_url','thumb','title','embed_code','categories','duration');

        $line=1;
        //foreach line
        foreach($lines as $lines_key => $lines_val){

            //reset
            $errorInsert = "";
            $duration = "";
            $thumbs = "";

            //foreach fields
            foreach($fields = explode("|", $lines_val) as $fields_key => $fields_val) {


                $fields_val_con = trim($fields_val);

                if($fieldArr[$fields_key]=="video_url"){
                    $video_url = $fields_val_con;

					$video_id = rtrim($video_url,"/"); 
					$video_id = strrchr($video_id,"/");
					$video_id = ltrim($video_id,"/");           
                }
                if($fieldArr[$fields_key]=="title"){
                    $title = $fields_val_con;
                }           
                if($fieldArr[$fields_key]=="thumb"){
                    $thumb = $fields_val_con;

                    //get bigger thumb
                    $thumb = str_replace("160x120","320x240", $thumb);

                }                
                if($fieldArr[$fields_key]=="duration"){
                    $duration = $fields_val_con;
                }
                if($fieldArr[$fields_key]=="categories"){
                   $categories = $fields_val_con;
                }                

            }

            
            //check for duplicate
			$args = array(
				'meta_query' => array(
				 'relation' => 'AND',
					array(
						'key' => 'video_id',
						'value' => $video_id,
					),
					array(
						'key' => 'site',
						'value' => 'sunporno.com'
					)
				)
			);

			$query = new WP_Query( $args );

			$num_rows = $query->found_posts;

            if ($num_rows > 0){

              echo"<span class=\"tubeace-errormsg\">Video skipped! Video titled <b>'$title'</b> already exists in database.</span><br>";
              flush();
            } elseif(!is_numeric($duration)) { //check for corrupt lines

              echo"<span class=\"tubeace-errormsg\">Video skipped! Video titled <b>'$title'</b> duration not an integer: $duration.</span><br>";
              flush();              
            } elseif(empty($thumb)) {

              echo"<span class=\"tubeace-errormsg\">Video skipped! Video titled <b>'$title'</b> Thumb field empty.</span><br>";
              flush();  
            } else {            


				if(!@getimagesize($thumb) && !@$errorInsert){
					echo"<span class=\"tubeace-errormsg\">Video '$title' skipped - Thumbnail URL invalid!</span><br>";
					flush();
					$errorInsert=1;
				}
                

                if(@!$errorInsert){   

                    //join
                    $tags = $categories;

                    $tagsArr = explode(",", $tags);

                    $tagsUC = "";
                    foreach($tagsArr as $tag){
                      //uc words
                      $tagsUC.=ucwords($tag).",";
                    }

                    $tags = addslashes(rtrim($tagsUC,","));

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
					);

					// Insert the post into the database
					if($lastID = wp_insert_post( $my_post )){

						//custom taxonomy must be done using wp_set_object_terms() for cron
						wp_set_object_terms($lastID, $performersArr, 'performer');							

						//add meta value
						add_post_meta($lastID, 'video_id', $video_id);
						add_post_meta($lastID, 'duration', $duration);
						add_post_meta($lastID, 'site', 'sunporno.com');
						add_post_meta($lastID, 'sponsor_link_url', $sponsor_link_url);
						add_post_meta($lastID, 'sponsor_link_txt', $sponsor_link_txt);
						add_post_meta($lastID, 'misc1', $misc1);
						add_post_meta($lastID, 'misc2', $misc2);
						add_post_meta($lastID, 'misc3', $misc3);
						add_post_meta($lastID, 'misc4', $misc4);
						add_post_meta($lastID, 'misc5', $misc5);

						echo "<span class=\"tubeace-succmsg\">Added line# $line '$title'</span><br>\n";

					} else {
						echo "<span class=\"tubeace-errormsg\">Line# $line '$title' not added.</span><br>\n";

					}
					ob_flush();
					flush();	

					$subPath = tubeace_sub_dir_path($lastID);

					//create thumb dir
					$upload_dir = wp_upload_dir();
					$create_path = $upload_dir[basedir]."/tubeace-thumbs/".$subPath;
					wp_mkdir_p($create_path);	

                    $i = 1;

                    //only single thumb
					list($width, $height) = getimagesize($thumb);

					$thumbDest = $upload_dir[basedir]."/tubeace-thumbs/".$subPath."/".$lastID."_".$i.".jpg";

					if($width==320 && $height==240){

					copy($thumb,$thumbDest);

					} else {

						tubeace_resize_thumb($thumb,$thumbDest,'jpg',$width,$height,320,240);
					}
						
					$frames = 1;

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
						ob_flush();
						flush();							
					}


                }
            }
            

            $line++;
        }  
        echo"All Done.";    
	}
}


//form
if(empty($_POST) && empty($_POST['import'])) {
?>

	<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-sunporno.php'); ?>" method="post" enctype="multipart/form-data">
		<table class="form-table">
		    <tbody>
		        <tr>
		        	<th><label for="file">Import File</label></th>
		        	<td>
						<input type="file" name="uploaded" id="file">				
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
		        		<input type="hidden" name="page" value="tubeace/tubeace-sunporno.php">
						<input type="submit" value="Import" class="button-primary" name="import">
		        	</td>		        	
		        </tr>
		    </tbody>
		</table>
	</form>
<?php
}
?>
</div>