<?php
tubeace_header(1);
?>

<h2>Test Server</h2>

<h3>PHP Values</h3>

<table class="widefat" cellspacing="0">
  <thead>
	  <tr>
	    <th class="manage-column column-cb check-column">PHP Setting</th>
	    <th class="manage-column column-cb check-column">Current Value</th>
	    <th class="manage-column column-cb check-column">Required Value</th>
	    <th class="manage-column column-cb check-column">Possible Errors</th>
	  </tr>
  </thead>

	  <tr class="tr1">
	    <td>allow_url_fopen</td>
	    <td><?php $allow_url_fopen = ini_get('allow_url_fopen'); 
		if($allow_url_fopen==1 ){
			print"On";
		} else {
			print"Off";
		}
		?></td>
	    <td>On</td>
	    <td>API calls, Videos and Thumbnail files on remote servers won't be accessed.</td>
	  </tr>
	  <tr class="tr2">
	    <td>post_max_size</td>
	    <td><?php echo ini_get('post_max_size'); ?></td>
	    <td>20M or greater</td>
	    <td>Import file uploading will fail if the video exceeds this PHP setting.</td>
	  </tr>
	  <tr class="tr1">
	    <td>upload_max_filesize</td>
	    <td><?php echo ini_get('upload_max_filesize'); ?></td>
	    <td>20M or greater</td>
	    <td>Import file uploading will fail if the video exceeds this PHP setting.</td>
	  </tr>
	  <tr class="tr2">
	    <td>memory_limit</td>
	    <td><?php echo ini_get('memory_limit'); ?></td>
	    <td>20M or greater</td>
	    <td>Import uploading will fail if the video exceeds this PHP setting.</td>
	  </tr>
	  <tr class="tr1">
	    <td>max_execution_time</td>
	    <td><?php echo ini_get('max_execution_time'); ?></td>
	    <td>60 (seconds) or greater</td>
	    <td>Plugin may timeout and not complete entire process. Usually when mass importing videos.</td>
	  </tr>
	  <tr class="tr2">
	    <td>max_input_time</td>
	    <td><?php echo ini_get('max_input_time'); ?></td>
	    <td>60 (seconds) or greater</td>
	    <td>Plugin may timeout and not complete entire process. Usually when mass importing videos.</td>
	  </tr>
	  <tr class="tr1">
	    <td>HTTP_KEEP_ALIVE</td>
	    <td><?php echo $_SERVER['HTTP_KEEP_ALIVE']; ?></td>
	    <td>60 (seconds) or greater</td>
	    <td>Plugin may timeout and not complete entire process. Usually when mass importing videos.</td>
	  </tr>
	  <tr class="tr2">
	    <td>magic_quotes_gpc</td>
	    <td><?php
		if(ini_get('magic_quotes_gpc')==0){ 
			echo "Off";
		} else {
			echo "On";
		} 
		
		?></td>
	    <td>Off</td>
	    <td>Backlashes will be added to doublequotes causing HTML code to not display correctly in browser.</td>
	  </tr>

</table>






<h3>Check if GD Graphics Library is installed </h3>
<table class="widefat" cellspacing="0">
  <tr>
    <td><?php
if (extension_loaded('gd') && function_exists('gd_info')) {
    echo "<span class=\"tubeace-succmsg\">GD is installed</span>";
} else {
	echo "<span class=\"tubeace-errormsg\">GD NOT installed!</span>";
}
?></td>
    <td>If not installed, thumbnails can't be resized.</td>
  </tr>
</table>



<h3>Check Directories</h3>
<table class="widefat" cellspacing="0">
  <tr>
    <td>Directory Name</td>
    <td>Directory Exists</td>
  </tr>
  <tr class="tr1">
    <td width="44%">/wp-content/uploads/</td>
    <td width="56%"><?php
    if(file_exists('../wp-content/uploads')){
		echo '<span class="tubeace-succmsg">exists</span>';			
	} else {
		echo '<span class="tubeace-errormsg">does NOT exist!</span>';
	}
	?></td>
  </tr>
  <tr class="tr2">
    <td>/wp-content/uploads/tubeace-thumbs/</td>
    <td><?php
    if(file_exists('../wp-content/uploads/tubeace-thumbs')){
		echo '<span class="tubeace-succmsg">exists</span>';		
	} else {
		echo '<span class="tubeace-errormsg">does NOT exist!</span>';
	}
	?></td>
  </tr>

</table>