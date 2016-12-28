<?php

tubeace_header(1);

if( isset($_POST['Submit']) ){

	$current_frequency = $_POST['current_frequency'];

	$cron_name = $_POST['cron_name'];
	$cron_frequency = $_POST['cron_frequency'];

	$keyword = $_POST['keyword'];
	
	$start = $_POST['start'];
	$end = $_POST['end'];
	
	$status = $_POST['status'];
	$sponsor = $_POST['sponsor'];
	$post_date = $_POST['post_date'];
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

	echo"<div class=\"updated\"><p>Updated Cron Job.</p></div>";

	//assoc array of cron values to store in options
	$cronValues=array("keyword"=>$keyword,"start"=>$start,"end"=>$end,"status"=>$status,"sponsor"=>$sponsor,"post_date"=>$post_date,"post_category"=>$post_category,
		"description"=>$description,"performers"=>$performers,"sponsor_link_txt"=>$sponsor_link_txt,"sponsor_link_url"=>$sponsor_link_url,
		"misc1"=>$misc1,"misc2"=>$misc2,"misc3"=>$misc3,"misc4"=>$misc4,"misc5"=>$misc5);
	
	//update (or possibly create new option, check to delete old below) 
	update_site_option("tubeace_cron_pornhub_".$cron_name."_".$cron_frequency, $cronValues);

	//if frequency changed
	if($current_frequency != $cron_frequency){

		#handle frequency group

		//remove from current tubeace_cron_{frequency} options array
		//note: if single name, its not an array
		$existingCronNames = get_site_option('tubeace_cron_pornhub_'.$current_frequency);

		//if multiple cron job names, remove current name from option, enter into new option
		if(count($existingCronNames)>1){

			$existingCronNames = array_diff($existingCronNames, array($cron_name));

			update_site_option("tubeace_cron_pornhub_".$current_frequency, $existingCronNames);
		
		} else { //only one name, delete from options and deactivate group cron hook

			//delete empty group option_name
			$wpdb->delete( $wpdb->prefix.'options', array( 'option_name' => "tubeace_cron_pornhub_".$current_frequency ) );

			//deactive old frequency if none remaining in group
			wp_clear_scheduled_hook( "tubeace_".$current_frequency );
		}

		//delete old set all's option_name
		$wpdb->delete( $wpdb->prefix.'options', array( 'option_name' => "tubeace_cron_pornhub_".$cron_name."_".$current_frequency ) );

		//create new frequency group, or add to existing
		$newCronNames = get_site_option('tubeace_cron_pornhub_'.$cron_frequency);

		//already exists add to it
		if(!empty($newCronNames)){
			$name_arr = array($cron_name);
			$newCronNames = array_merge($newCronNames,$name_arr);

		} else { // doesn't exist create it

			$newCronNames = array($cron_name);
		}

		update_site_option("tubeace_cron_pornhub_".$cron_frequency, $newCronNames);

		//rename set all's option_name
		$wpdb->update( 'options', array('option_name' => "tubeace_cron_pornhub_".$cron_name."_".$cron_frequency ), array('option_name' => "tubeace_cron_pornhub_".$cron_name."_".$current_frequency) );

		//activate new frequency 
		if ( ! wp_next_scheduled( 'tubeace_'.$cron_frequency ) ) {
		  wp_schedule_event( time(), $cron_frequency, 'tubeace_'.$cron_frequency );
		}
	}

} else {

	$cron_frequency = $_GET['freq'];
	$cron_name = $_GET['name'];

}

$option_values = get_site_option('tubeace_cron_pornhub_'.$cron_name.'_'.$cron_frequency);

?>

<h2>Edit Pornhub Import Cron Job</h2>

<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-cron-pornhub-edit.php'); ?>" method="POST">

	<table class="form-table">
	    <tbody>
	        <tr>
	    		<th><label for="cron_name">Name</label></th>
	        	<td>
	        		<?php echo $cron_name ?>
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="cron_frequency">Frequency</label></th>
	        	<td>
        		<?php 

    			if($cron_frequency=='hourly'){
    				$sel_hourly = "selected = \"selected\"";
    			}
    			if($cron_frequency=='twicedaily'){
    				$sel_twicedaily = "selected = \"selected\"";
    			}
    			if($cron_frequency=='daily'){
    				$sel_daily = "selected = \"selected\"";
    			}

        		?>
				  <select name="cron_frequency" id="cron_frequency">
					<option value="hourly" <?php echo $sel_hourly?>>Hourly</option>
					<option value="twicedaily" <?php echo $sel_twicedaily?>>Twice Daily</option>
					<option value="daily" <?php echo $sel_daily?>>Daily</option>
				  </select>
	        	
	        	</td>		        	
	        </tr>		
	    </tbody>
	</table>

	<h3>Search Videos</h3>
	<table class="form-table">
	    <tbody>
	        <tr>
	    		<th><label for="keyword">Keyword</label></th>
	        	<td>
	        		<input name="keyword" type="text" class="input-320" id="keyword" value="<?php echo $option_values['keyword'] ?>">
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="start">Start Page</label></th>
	        	<td>
	        		<input name="start" type="text" class="input-60" id="start" value="<?php echo $option_values['start'] ?>">
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="end">End Page</label></th>
	        	<td>
	        		<input name="end" type="text" class="input-60" id="end" value="<?php echo $option_values['end'] ?>">
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
        		<?php 

        		$status = $option_values['status'];

    			if($status=='publish'){
    				$sel_publish = "selected = \"selected\"";
    			}
    			if($status=='pending'){
    				$sel_pending = "selected = \"selected\"";
    			}
    			if($status=='draft'){
    				$sel_draft = "selected = \"selected\"";
    			}
    			if($status=='future'){
    				$sel_future = "selected = \"selected\"";
    			}
    			if($status=='private'){
    				$sel_private = "selected = \"selected\"";
    			} 

        		?>
				  <select name="status" id="status">
					<option value="publish" <?php echo $sel_publish?>>Publish</option>
					<option value="pending" <?php echo $sel_pending?>>Pending</option>
					<option value="draft" <?php echo $sel_draft?>>Draft</option>
					<option value="future" <?php echo $sel_future?>>Future</option>
					<option value="private" <?php echo $sel_private?>>Private</option>
				  </select>
	        	</td>		        	
	        </tr>
	    	<tr>
	    		<th><label for="sponsor">Author / Sponsor</label></th>
	        	<td>
					  <select name="sponsor" id="sponsor">
						<?php tubeace_get_users_with_role(array('Contributor','Administrator'), $option_values['sponsor']); ?>
					  </select>
					  To add a sponsor, <a href="user-new.php">add a new user</a> with a "Contributor" Role.
	        	</td>
	        </tr>	        
	        <tr>
	    		<th><label for="post_date">Post Date</label></th>
	        	<td>
	        		<input name="post_date" type="text" class="input-160" id="post_date" value="<?php echo $option_values['post_date']; ?>">
	        	</td>		        	
	        </tr>		
	        <tr>
	    		<th><label for="start">Category</label></th>
	        	<td>
					<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
					  <?php wp_category_checklist( 0,0,$option_values['post_category']); ?>
					</ul> 		        		
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="description">Description</label></th>
	        	<td>
	        		<textarea name="description" class="input-320" id="description"><?php echo $option_values['description'] ?></textarea>
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="performers">Performers</label></th>
	        	<td>
	        		<input name="performers" type="text" class="input-320" id="performers" value="<?php echo $option_values['performers'] ?>">
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="sponsor_link_txt">Sponsor Link TXT</label></th>
	        	<td>
	        		<input name="sponsor_link_txt" type="text" class="input-320" id="sponsor_link_txt" value="<?php echo $option_values['sponsor_link_txt'] ?>">
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="sponsor_link_url">Sponsor Link URL</label></th>
	        	<td>
	        		<input name="sponsor_link_url" type="text" class="input-320" id="sponsor_link_url" value="<?php echo $option_values['sponsor_link_url'] ?>">
	        	</td>		        	
	        </tr>		
	        <tr>
	    		<th><label for="misc1">Misc 1</label></th>
	        	<td>
	        		<textarea name="misc1" class="input-320" id="misc1"><?php echo $option_values['misc1'] ?></textarea>
	        	</td>		        	
	        </tr>	
	        <tr>
	    		<th><label for="misc2">Misc 2</label></th>
	        	<td>
	        		<textarea name="misc2" class="input-320" id="misc2"><?php echo $option_values['misc2'] ?></textarea>
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="misc3">Misc 3</label></th>
	        	<td>
	        		<textarea name="misc3" class="input-320" id="misc3"><?php echo $option_values['misc3'] ?></textarea>
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="misc4">Misc 4</label></th>
	        	<td>
	        		<textarea name="misc4" class="input-320" id="misc4"><?php echo $option_values['misc4'] ?></textarea>
	        	</td>		        	
	        </tr>
	        <tr>
	    		<th><label for="misc5">Misc 5</label></th>
	        	<td>
	        		<textarea name="misc5" class="input-320" id="misc5"><?php echo $option_values['misc5'] ?></textarea>
	        	</td>		        	
	        </tr>		        
	    </tbody>
	</table>
	<input type="hidden" name="cron_name" value="<?php echo $cron_name; ?>">
	<input type="hidden" name="current_frequency" value="<?php echo $cron_frequency; ?>">
	<input type="submit" value="Save Changes" class="button-primary" name="Submit">	
</form>