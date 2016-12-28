<?php
tubeace_header(1);
?>

<div class="wrap">
  <h2>Import Videos</h2>

<?php

//step 3 start ajax import
if( isset($_REQUEST['Submit2']) ){

	$copy_video_url = $_POST['copy_video_url'];
	$copy_sponsor_link_url = $_POST['copy_sponsor_link_url'];
	
	//hidden values
	$status = $_POST['status'];
	$type = $_POST['type'];
	$separator = $_POST['separator'];
	$field = $_POST['field'];
	$data_format = $_POST['data_format'];
	$sponsor = $_POST['sponsor'];
	$post_category = $_POST['post_category'];
	$thumb_source = $_POST['thumb_source'];
	$save_thumbs = $_POST['save_thumbs'];
	$save_videos = $_POST['save_videos'];
	$detect_duration = $_POST['detect_duration'];
	$block_dups = $_POST['block_dups'];

	//error checking
	if($data_format=="custom"){
	
		//make sure one field selected as title
		if ( !in_array("title", $field) && empty($_POST['setall_title']) ) {
			$errs.= "<br><span class=\"errormsg\">Select a field as 'title' or enter a set to all value for title.</span>";
			$error2=1;
		}
	
		//make sure one field selected as thumb_url
		if ( !in_array("thumb_url", $field) && !in_array("mobile_thumb_url", $field) && !in_array('thumb_url & mobile_thumb_url', $field) && ($thumb_source=="import_file" || $type=="embed")) {
			$errs.= "<br><span class=\"errormsg\">Select a field as 'thumb_url', 'mobile_thumb_url' or 'thumb_url & mobile_thumb_url'.</span>";
			$error2=1;
		}
	
		//make sure one field selected as video_url if type == url
		if ( (!in_array("video_url", $field) && !in_array("video_url & mobile_video_url", $field)) && $type=="url" && $online==1) {
			$errs.= "<br><span class=\"errormsg\">Select a field as 'video_url' or 'video_url & mobile_video_url'.</span>";
			$error2=1;
		}
		
		//make sure one field selected as video_url if type == url
		if ( (!in_array("mobile_video_url", $field) && !in_array("video_url & mobile_video_url", $field)) && $type=="url" && $mobile_online==1) {
			$errs.= "<br><span class=\"errormsg\">Select a field as 'mobile_video_url' or 'video_url & mobile_video_url'.</span>";
			$error2=1;
		}	
		
		//make sure one field selected as embed_code if type == embed
		if ( !in_array("embed_code", $field) && $type=="embed" ) {
			$errs.= "<br><span class=\"errormsg\">Select a field as 'embed_code'.</span>";
			$error2=1;
		}
		
		//make sure no fields selected twice
		$field2 = $field;
		foreach($field2 as $val){
			
			if((++$field2[$val] > 1) && $val!="none"){
				$errs.= "<br><span class=\"errormsg\">$val selected more that once.</span>";
				$error2=1;
			}
		}
	}

	if(!$error2){
		
		$fieldJSON = json_encode($field);
		$post_category = json_encode($post_category);

		echo" 
		<input id='copy_video_url' type='hidden' value='$copy_video_url'>
		<input id='copy_sponsor_link_url' type='hidden' value='$copy_sponsor_link_url'>			
		
		<input id='status' type='hidden' value='$status'>
		<input id='type' type='hidden' value='$type'>	
		<input id='separator' type='hidden' value='$separator'>
		<input id='field' type='hidden' value='$fieldJSON'>
		<input id='thumb_source' type='hidden' value='$thumb_source'>
		<input id='sponsor' type='hidden' value='$sponsor'>
		<input id='post_category' type='hidden' value='$post_category'>
		<input id='save_thumbs' type='hidden' value='$save_thumbs'>
		<input id='save_videos' type='hidden' value='$save_videos'>
		<input id='detect_duration' type='hidden' value='$detect_duration'>
		<input id='block_dups' type='hidden' value='$block_dups'>";

		$setall_array = array('post_date','duration','title','description','tags','performers','site','sponsor_link_url','sponsor_link_txt','misc1','misc2','misc3','misc4','misc5');
		foreach($setall_array as $value){
			
			echo"<input id='$value' type='hidden' value='".$_POST["setall_$value"]."'>";			
		}
		
		
		echo"<div id=\"response\"></div>
		<br /><img id=\"loading\" src=\"".plugins_url("tubeace/images/loading32x32.gif")."\">";
		
		
	}
}

//step 2 - select fields
if(isset($_REQUEST['Submit']) || $error2){

	$status = $_POST['status'];
	$type = $_POST['type'];
	$sponsor = $_POST['sponsor'];
	$paste_data = $_POST['paste_data'];	
	$data_format = $_POST['data_format'];	
	$post_category = $_POST['post_category'];	
	$separator = $_POST['separator'];
	$thumb_source = $_POST['thumb_source'];
	$save_thumbs = $_POST['save_thumbs'];
	$save_videos = $_POST['save_videos'];
	$detect_duration = $_POST['detect_duration'];
	$block_dups = $_POST['block_dups'];	
	
	$tmpName  = $_FILES['uploaded']['tmp_name'];
	$fileSize = $_FILES['uploaded']['size'];	
	
	if($separator=="tab"){
		$separator = "\t";
	} else if($separator=="comma"){
		$separator = ",";
	} else if($separator=="semicolon"){
		$separator = ";";
	} else if($separator=="pipe"){
		$separator = "|";
	}		
	
	//error checking
	
	//only do original error checking if error2 empty
	if(!$error2){
		
		if($type=="embed" && $thumb_source=="ffmpeg"){
			$errs.= $err_import_data = "<br /><span class=\"errormsg\">You cannot Generate Thumbnails from Embed Codes.</span>";
			$error=1;			
		}

		//check import file or paste data given 
		if(empty($tmpName) && empty($paste_data)){
			$errs.= $err_import_data = "<br /><span class=\"errormsg\">Upload Import File or Paste Import Data.</span>";
			$error=1;
		}
		
		//get import data
		if(!empty($tmpName)){//import file
			
			$content = file($tmpName);
			$firstLine = $content[0]; 	
			
		} else { //paste data
			
			$content = $paste_data;
			$paste_dataArr = explode("\n", $content);
			$firstLine = $paste_dataArr[0];
		}
		
		//make sure data contains separator
		if(@substr_count($firstLine, $separator)==0 && empty($err_import_data)){
			$errs.= $err_import_data = "<br /><span class=\"errormsg\">Import Data does not contain any Field Separators as selected below.</span>";
			$error=1;		
		}
		
	}
	
	if(!$error){
		
		//get import data
		if(!empty($tmpName)){//import file
			
			$fp = fopen($tmpName, 'r');
			$content = fread($fp, filesize($tmpName));
			fclose($fp);		
			
		} else { //paste data
			
			$content = stripslashes($paste_data);
			$paste_dataArr = explode("\n", $content);
		}		
		
		$upload_dir = wp_upload_dir();

		if($error2){
			echo"<span class=\"tubeace-errormsg\">You must correct the error(s) below to proceed.</span>";
			echo"$errs <br /><br />";
			
			//get first line from tubeace-import.txt
			$content = file("$upload_dir[basedir]/tubeace-import.txt");
			$firstLine = $content[0];
			
			//get entire file
			$fp = fopen("$upload_dir[basedir]/tubeace-import.txt", 'r');
			$content = fread($fp, filesize("$upload_dir[basedir]/tubeace-import.txt"));
			fclose($fp);				
		}
		
		//skip only if error2 set
		if(!$error2){
			//copy/write import file to tubeace-import.txt
			if(!empty($tmpName)){
				copy($tmpName, "$upload_dir[basedir]/tubeace-import.txt");
			}
			if(!empty($paste_data)){
				
				// Open file to write
				$fp = fopen("$upload_dir[basedir]/tubeace-import.txt", 'w+');
				fwrite($fp, $content);
				fclose($fp);			
			}	
		}
		
		echo"<form action=\"".admin_url('admin.php?page=tubeace/tubeace-import.php')."\" method=\"POST\">";
		
		$field_array = array('video_url','thumb_url','embed_code','title','duration','description','tags','performers','site','sponsor_link_url','sponsor_link_txt','misc1','misc2','misc3','misc4','misc5');				
		
		if($data_format=="custom"){
			
			echo"<span class=\"tubeace-succmsg\">Match the fields in each drop-down menu with the correct data values.</span>";
			
			if($thumb_source=="ffmpeg"){
				echo"<br /><span class=\"tubeace-succmsg\">Hint: Thumbnail URL options not shown since 'Generate Thumbnails' was selected on previous page.</span>";
			}		
			
			echo"	
			<table class=\"vertical-form\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr><th>Select Field</th><th>Value</th></tr>";
			
			$rowcount = 1;
			$i = 0;			
			$lines = explode("\n", $content);
			foreach($fields = explode("$separator", $lines[0]) as $key => $val) {
				
				print"<tr class=\"tr$rowcount\">\n";
				print"<td class=\"center\">\n";
				print"<select name=\"field[$i]\" id=\"field[$i]\">\n";
				print"<option value=\"none\">-none-</option>\n";
				
				//show field values in drop-downs
				foreach($field_array as $value){
					
					if($field[$i]==$value){
						$sel_field = "selected=\"selected\"";
					}
					
					if($value=="video_url" && $type!="embed"){

						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="video_url & mobile_video_url" && $type!="embed" && $set['enable_mobile']==1){
						
						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="thumb_url" && $thumb_source!="ffmpeg"){
						
						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="thumb_url & mobile_thumb_url" && $thumb_source!="ffmpeg" && $set['enable_mobile']==1){
						
						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="embed_code" && $type=="embed"){
						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="duration" && $detect_duration!="1"){
						
						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="mobile_video_url" && $type!="embed" && $set['enable_mobile']==1){
						
						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="mobile_thumb_url" && $thumb_source!="ffmpeg" && $set['enable_mobile']==1){
						
						print"<option value=\"$value\" $sel_field>$value</option>\n";
						
					} elseif($value=="title" || $value=="description" || $value=="tags" || $value=="performers" || $value=="site" ||
							 $value=="sponsor_link_url" || $value=="sponsor_link_txt" || $value=="misc1" ||  $value=="misc2" || $value=="misc3" || 
							 $value=="misc4" || $value=="misc5" || $value=="mobile_sponsor_link_url" || $value=="mobile_sponsor_link_txt" || 
							 $value=="mobile_misc1" ||  $value=="mobile_misc2" || $value=="mobile_misc3" || $value=="mobile_misc4" || $value=="mobile_misc5" ||
							 $value=="sponsor_link_url & mobile_sponsor_link_url"){
	
						print"<option value=\"$value\" $sel_field>$value</option>\n";
					}	
					
					unset($sel_field);
					
				}   
			
				print"</select>\n";
				print"</td>\n";
				
				print"<td>$val</td>\n";
				print"</tr>\n";
			
			   $rowcount++;
			   if($rowcount==3){
				  $rowcount = 1;
			   }
			   
			$i++;   
			}
			
			print"</table>\n";
			
		}
		
		if($data_format=="tubeace"){
			
			echo"<span class=\"tubeace-succmsg\">Verify that the fields match the correct value. If fields do mot match the values, use a correct Tube Ace formated import file.</span>
			<table class=\"vertical-form\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr><th>Field</th><th>Value</th></tr>";			
			
			$field_array = array('title','description','video_url','thumb_url','duration','performers','tags','sponsor_link_url','site','mobile_video_url','mobile_sponsor_link_url');	
			
			$rowcount = 1;
			$i = 0;			
			$lines = explode("\n", $content);
			foreach($fields = explode("$separator", $lines[0]) as $key => $val) {
				
				
				echo"
				<tr class=\"tr$rowcount\">
				  <td class=\"center\"><b>$field_array[$i]</b><input type=\"hidden\" name=\"field[$i]\" value=\"$field_array[$i]\"></td>
				  <td>$val</td>
				</tr>";
			
			   $rowcount++;
			   if($rowcount==3){
				  $rowcount = 1;
			   }
			   
			$i++;  				
				
			}
			
			echo"</table>\n";
			
			
			if($set[enable_web]==1){
				
				echo"<fieldset><legend>Options</legend>";

				if($i<9){
					echo"
					  <input name=\"copy_video_url\" type=\"checkbox\" class=\"checkbox\" value=\"1\"> 
					  Copy <b>video_url</b> value for <b>mobile_video_url</b><br />";
				}
				
				if($i<10){
					echo"
					  <input name=\"copy_sponsor_link_url\" type=\"checkbox\" class=\"checkbox\" value=\"1\"> 
					  Copy <b>sponsor_link_url</b> value for <b>mobile_sponsor_link_url</b>";	
				}
				
				echo"
				</fieldset>";				
			}
			
		}
		
		//Set All's 
		print"<br>";
		print"<span class=\"tubeace-succmsg\">Below you can set the same values to a field for all entries you are about to import.</span>";
		print"<table class=\"vertical-form\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		print"<tr><th class=\"right\"></th><th class=\"left\">Set all fields to this value or leave blank to use value from import file.</th></tr>\n";
	
		$setall_array = array('post_date' => '160', 'duration' => '60','title' => '320t','description' => '320','tags' => '320','performers' => '320','site' => '160',
							  'sponsor_link_url' => '320t','sponsor_link_txt' => '320t','misc1' => '320','misc2' => '320','misc3' => '320','misc4' => '320',
							  'misc5' => '320');
		$rowcount = 1;		
		
		foreach($setall_array as  $fieldName => $value){
			if( ($type=="embed" && $value!="video_url") || ($type=="url" && $value!="embed_code")){
			
				unset($fill_val);
				if($fieldName=="sponsor_link_txt" || $fieldName=="mobile_sponsor_link_txt"){
					$fill_val = "Click Here for Full Video";
				} 
				
				if($fieldName=="post_date"){
					$fill_val = date("Y-m-d H:i:s");
				} 		
			
				echo"<tr class=\"tr$rowcount\"><td class=\"right\"><b>$fieldName</b></td><td class=\"left\">";
				
				if($value=="60" || $value=="160" || $value=="320t"){
					
					//strip t
					$value=str_replace("t","",$value);;
					
					echo"<input type=\"text\" class=\"input-$value\" name=\"setall_$fieldName\" value=\"$fill_val\">";
				} else {
					echo"<textarea class=\"input-$value\" name=\"setall_$fieldName\">$fill_val</textarea>";
				}
				
				if($fieldName=="tags" || $fieldName=="performers"){
					print"(separate by comma.)";
				}
				print"</td></tr>\n";
				$rowcount++;
				if($rowcount==3){
					$rowcount = 1;
				}	
			}		
		} 
		$paste_data =  "asd";



		print"</table>\n";
		

		print"<input name=\"status\" type=\"hidden\" value=\"$status\">\n";
		print"<input name=\"type\" type=\"hidden\" value=\"$type\">\n";
		print"<input name=\"separator\" type=\"hidden\" value=\"$separator\">\n";
		print"<input name=\"paste_data\" type=\"hidden\" value=\"".urlencode($paste_data)."\">\n";
		print"<input name=\"data_format\" type=\"hidden\" value=\"$data_format\">\n";
		print"<input name=\"sponsor\" type=\"hidden\" value=\"$sponsor\">\n";

		if(!empty($post_category)){
			foreach($post_category as $value)
			{
			  echo '<input type="hidden" name="post_category[]" value="'. $value. '">';
			}
		}

		echo"<input name=\"thumb_source\" type=\"hidden\" value=\"$thumb_source\">\n";
		echo"<input name=\"block_dups\" type=\"hidden\" value=\"$block_dups\">\n";
		echo"<input name=\"save_thumbs\" type=\"hidden\" value=\"$save_thumbs\">\n";
		echo"<input name=\"detect_duration\" type=\"hidden\" value=\"$detect_duration\">\n";
		echo"<input name=\"save_videos\" type=\"hidden\" value=\"$save_videos\">\n";
	
		echo"<input class=\"button-primary\" name=\"Submit2\" type=\"submit\" value=\"Import File\">\n";
		echo"</form>\n";
	}
}


//step 1 - set options and enter import data
if (!isset($_REQUEST['Submit']) && !isset($_REQUEST['Submit2']) || $error) { 

	if($error){
		
		echo"<span class=\"tubeace-errormsg\">You must correct the error(s) below to proceed.</span>";
		echo"$errs";
		
		$separator = $_POST['separator'];
		$paste_data = $_POST['paste_data'];	  
	}
	
	?>

		<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-import.php'); ?>" method="post" enctype="multipart/form-data">
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
			        	<th><label for="type">Video Type</label></th>	
			        	<td>
			  				<input type="radio" name="type" id="video_url" value="url" checked> <label for="video_url" class="sel">Video URL (flv or mp4 URL)</label>
			  				<input type="radio" name="type" id="embed_code" value="embed" > <label for="embed_code" class="sel">Embed Code</label>
			        	</td>
			        </tr>
			    	<tr>
			    		<th><label for="sponsor">Author / Sponsor</label></th>
			        	<td>
							  <select name="sponsor" id="sponsor">
								<?php tubeace_get_users_with_role(array('Contributor','Administrator'),0); ?>
							  </select>
							  To add a sponsor, <a href="user-new.php">add a new user</a> with a "Contributor" Role.
			        	</td>
			        </tr>
			    	<tr>
			    		<th><label for="file">Category</label></th>
			        	<td><ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
							<?php wp_category_checklist( $args ); ?></ul> 
			        	</td>
			        </tr>			                
			        <tr>
			        	<th><label for="file">Import File</label></th>
			        	<td>
							<input type="file" name="uploaded" id="file"><br />
							OR Paste below:<br />
	  						<textarea name="paste_data" class="" rows="5">
							<?php
							global $wpdb;
                					$myrows = $wpdb->get_col( "SELECT exp FROM wp_video_upload_view" );
                					print implode(PHP_EOL,$myrows);
							?>
							</textarea>							
			        	</td>
			        </tr>
			        <tr>
			        	<th><label for="file">Data Format</label></th>
			        	<td>
						  <input type="radio" name="data_format" id="custom_format" value="custom"> <label for="custom_format" class="sel">Custom (Select Fields on Next Step)</label> 
						  <input type="radio" name="data_format" id="ta_format" value="tubeace" checked> <label for="ta_format" class="sel">Tube Ace Format</label>
			        	</td>
			        </tr>			        
			        <tr>
			        	<th><label for="file">Field Separator (delimiter)</label></th>
			        	<td>
						  <select name="separator">
							<option value="pipe" selected="selected" selected="selected">Pipe |</option>
							<option value="comma" >Comma ,</option>
							<option value="tab" >Tab</option>
							<option value="semicolon" >Semicolon ;</option>
						   </select>
			        	</td>
			        </tr>
			        <tr>
			        	<th><label for="">Thumbnail Source</label></th>
			        	<td>
			        		<input type="radio" name="thumb_source" id="use_thumb" value="import_file"> <label for="use_thumb" class="sel">Use Thumbnail from Import File</label>	
	  						<input type="radio" name="thumb_source" id="gen_thumbs" value="ffmpeg" checked> <label for="gen_thumbs" class="sel">Generate Thumbnails</label>
	 								        	
			        	</td>
			        </tr>
			        <tr>
			        	<th><label for="">Save Thumbs from Import File</label></th>
			        	<td>
			        		<input name="save_thumbs" type="checkbox" class="checkbox" id="save_thumbs" value="1"/>
			        	</td>
			        </tr>
			        <tr>
			        	<th><label for="">Copy and Save Videos to Server</label></th>
			        	<td>
			        		<input name="save_videos" type="checkbox" class="checkbox" id="save_videos" value="1" checked/>
			        	</td>
			        </tr>			        
			        <tr>
			        	<th><label for="">Detect Video Duration</label></th>
			        	<td>
			        		<input name="detect_duration" type="checkbox" class="checkbox" id="detect_duration" value="1" checked/>
			        	</td>
			        </tr>
			        <tr>
			        	<th><label for="">Block Duplicates (Video URLs and Embed Codes) from importing into database</label></th>
			        	<td>
			        		<input name="block_dups" type="checkbox" class="checkbox" id="block_dups" value="1" checked/>
			        	</td>
			        </tr>			        
			    </tbody>
			</table>
			<p class="submit"><input type="submit" value="Next Step" class="button-primary" name="Submit"></p>
		</form>
	
<?php

}
?>
</div>
