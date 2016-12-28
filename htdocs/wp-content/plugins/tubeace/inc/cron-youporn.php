<?php

if(!empty($_POST['Delete-youporn'])) {

	$id1 = $_POST['id'];
	$freq = $_POST['freq'];
	$del = $_POST['del'];

	$i=0;

	//since get_option() is cached make an array of deleted cron names so they don't still show in results after deleting
	$deleted_cron_names = array();
	foreach( $id1 as $cron_name) {

		if($del[$cron_name]==1){

			$deleted_cron_names[] = $cron_name;

			$frequency = $freq[$cron_name];

			//remove from frequency group
			$existingCronNames = get_site_option('tubeace_cron_youporn_'.$frequency);

			//if multiple cron job names, remove current name from option, enter into new option
			if(count($existingCronNames)>1){

				//unset($existingCronNames["$cron_name"]);
				$existingCronNames = array_diff($existingCronNames, array($cron_name));

				update_site_option("tubeace_cron_youporn_".$frequency, $existingCronNames);
			
			} else { //only one name, delete from options and deactivate group cron hook


				//delete empty group option_name
				$wpdb->delete( $wpdb->prefix.'options', array( 'option_name' => "tubeace_cron_youporn_".$frequency ) );

				//deactive old frequency if none remaining in group
				wp_clear_scheduled_hook( "tubeace_".$frequency );
			}
			
			//delete set all values
			$wpdb->delete( $wpdb->prefix.'options', array( 'option_name' => "tubeace_cron_youporn_".$cron_name."_".$frequency ) );

			$i++;
		}
	}

	if($i>0){

		echo"<div class=\"updated\"><p><b>Deleted $i Cron Job(s).</b></p></div>";
	} else {
		echo"<div class=\"error\"><p><b>No YouPorn Cron Jobs selected to delete.</b></p></div>";
	}
}

?>

<h3>YouPorn Import Cron Jobs</h3>
<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-cron.php'); ?>" method="post">
<table class="widefat" cellspacing="0">
  <thead>
	<tr>
		<th class="manage-column column-cb check-column"></th>
		<th>Frequency</th>
		<th>Name</th>
		<th>Keyword</th>
		<th>Start Page</th>
		<th>End Page</th>
		<th>Status</th>
		<th>Category</th>
	</tr>
	</thead>
	<tbody>
		<?php

		//if nothing deleted create to prevent error
		if(empty($deleted_cron_names)){
			$deleted_cron_names = array();
		}

		//hourly
		//get options array
		$existingCronNamesHourly = get_site_option('tubeace_cron_youporn_hourly');

		if(!empty($existingCronNamesHourly)){

			foreach($existingCronNamesHourly as $name){

				//make sure not just deleted
				if(!in_array($name, $deleted_cron_names)){

				    $option_values = get_site_option('tubeace_cron_youporn_'.$name.'_hourly');

				    $keyword = $option_values['keyword'];
				    $start = $option_values['start'];
				    $end = $option_values['end'];
				    $status = $option_values['status'];

				    $post_category = $option_values['post_category'];

					echo"
					<tr class=\"alternate\">
						<td>
						    <input name=\"id[$name]\" value=\"$name\" type=\"hidden\">
						    <input name=\"freq[$name]\" value=\"hourly\" type=\"hidden\">
						    <input type=\"checkbox\" name=\"del[$name]\" value=\"1\">
						</td>
						<td>Hourly <br><a href=\"admin.php?page=tubeace/tubeace-cron-youporn-edit.php&freq=hourly&name=$name\">Edit</a> </td>
						<td>$name</td>
						<td>$keyword</td>
						<td>$start</td>
						<td>$end</td>
						<td>".ucfirst($status)."</td>
						<td>";

						$categories = "";
						if(!empty($post_category)){
							foreach($post_category as $catID){

								$categories[].= get_the_category_by_ID( $catID );
							}

							$categories = implode(', ',$categories);
						} else {
							$categories='None';
						}


						echo"$categories</td>			
					</tr>";
				}
			}
		}

		//twicedaily
		//get options array
		$existingCronNamesTwice = get_site_option('tubeace_cron_youporn_twicedaily');

		if(!empty($existingCronNamesTwice)){

			foreach($existingCronNamesTwice as $name){

				//make sure not just deleted
				if(!in_array($name, $deleted_cron_names)){				

				    $option_values = get_site_option('tubeace_cron_youporn_'.$name.'_twicedaily');

				    $keyword = $option_values['keyword'];
				    $start = $option_values['start'];
				    $end = $option_values['end'];
				    $status = $option_values['status'];

				    $post_category = $option_values['post_category'];

					echo"
					<tr class=\"alternate\">
						<td>
						  <input name=\"id[$name]\" value=\"$name\" type=\"hidden\">
						  <input name=\"freq[$name]\" value=\"twicedaily\" type=\"hidden\">
						  <input type=\"checkbox\" name=\"del[$name]\" value=\"1\">
						</td>
						<td>Twice Daily <br><a href=\"admin.php?page=tubeace/tubeace-cron-youporn-edit.php&freq=twicedaily&name=$name\">Edit</a> </td>
						<td>$name</td>
						<td>$keyword</td>
						<td>$start</td>
						<td>$end</td>
						<td>".ucfirst($status)."</td>
						<td>";

						$categories = "";
						if(!empty($post_category)){
							foreach($post_category as $catID){

								$categories[].= get_the_category_by_ID( $catID );
							}

							$categories = implode(', ',$categories);
						} else {
							$categories='None';
						}

						echo"$categories</td>			
					</tr>";
				}
			}
		}

		
		//daily
		//get options array
		$existingCronNamesDaily = get_site_option('tubeace_cron_youporn_daily');

		if(!empty($existingCronNamesDaily)){

			foreach($existingCronNamesDaily as $name){

				//make sure not just deleted
				if(!in_array($name, $deleted_cron_names)){

				    $option_values = get_site_option('tubeace_cron_youporn_'.$name.'_daily');

				    $keyword = $option_values['keyword'];
				    $start = $option_values['start'];
				    $end = $option_values['end'];
				    $status = $option_values['status'];

				    $post_category = $option_values['post_category'];

					echo"
					<tr class=\"alternate\">
						<td>
						  <input name=\"id[$name]\" value=\"$name\" type=\"hidden\">
						  <input name=\"freq[$name]\" value=\"daily\" type=\"hidden\">
						  <input type=\"checkbox\" name=\"del[$name]\" value=\"1\">
						</td>
						<td>Daily <br><a href=\"admin.php?page=tubeace/tubeace-cron-youporn-edit.php&freq=daily&name=$name\">Edit</a> </td>
						<td>$name</td>
						<td>$keyword</td>
						<td>$start</td>
						<td>$end</td>
						<td>".ucfirst($status)."</td>
						<td>";

						$categories = "";
						if(!empty($post_category)){
							foreach($post_category as $catID){

								$categories[].= get_the_category_by_ID( $catID );
							}

							$categories = implode(', ',$categories);
						} else {
							$categories='None';
						}

						echo"$categories</td>			
					</tr>";

				}
			}
		}
			
		?>
  	<tbody>
</table>
<input type="submit" value="Delete Selected" class="button-primary" name="Delete-youporn">
</form>