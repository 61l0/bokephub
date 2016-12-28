<a href="http://www.tubeace.com/" target="_blank"><img src="<?php echo plugins_url('/tubeace/images/logo.png') ?>" alt="Tube Ace"></a><br />
<h2>Remove Inactive Videos</h2>
<?php
set_time_limit(0);

if(!empty($_POST['RedTube'])) {

    $start = $_POST['start'];
	$end = $_POST['end'];

	$page = $start;

	while($page<=$end){

		$apicall = "http://api.redtube.com/?data=redtube.Videos.getDeletedVideos&output=json&page=$page";
		$apiresults = file_get_contents($apicall);
		$apiresults = json_decode($apiresults);
		$videos = $apiresults->videos;        

		foreach($videos as $video) {

		    $video_obj = $video->video;

		    $video_id = $video_obj->video_id;
		    $publish_date = $video_obj->publish_date;

		    //get id of post if in db
			$args = array(
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'site',
						'value' => 'redtube.com'
					),
					array(
						'key' => 'video_id',
						'value' => $video_id
					)
				)
			);

			$the_query = new WP_Query( $args );	

		    //if row affected
		    if($the_query->have_posts()){

				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					//set post_status to trash
					wp_update_post( array('ID'=> get_the_ID(), 'post_status' => 'trash'));

					echo"<span class='tubeace-succmsg'>Moved RedTube Video '".get_the_title()."' # <b>$video_id</b> to trash.</span><br>";
				}

			    ob_flush();
			    flush();

		    } else {

		      	echo"<span class='tubeace-errormsg'>RedTube Video # <b>$video_id</b> not in database or already offline.</span><br>";

				ob_flush();
				flush();
		    }
		}

		echo"<span class='tubeace-succmsg'>Page $page done.</span><br>";

		$page++;
	}
}


if(!empty($_POST['Pornhub'])) {

    $start = $_POST['start'];
	$end = $_POST['end'];

	$page = $start;

	while($page<=$end){

		$apicall = "http://www.pornhub.com/webmasters/deleted_videos/?page=$page";
		$apiresults = file_get_contents($apicall);
		$apiresults = json_decode($apiresults);
		$videos = $apiresults->videos;        

		foreach($videos as $video) {

		    //$video_obj = $video->vkey;

		    $video_id = $video->vkey;
		    $publish_date = $video_obj->publish_date;

		    //get id of post if in db
			$args = array(
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'site',
						'value' => 'pornhub.com'
					),
					array(
						'key' => 'video_id',
						'value' => $video_id
					)
				)
			);

			$the_query = new WP_Query( $args );	

		    //if row affected
		    if($the_query->have_posts()){

				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					//set post_status to trash
					wp_update_post( array('ID'=> get_the_ID(), 'post_status' => 'trash'));

					echo"<span class='tubeace-succmsg'>Moved YouPorn Video '".get_the_title()."' # <b>$video_id</b> to trash.</span><br>";
				}

			    ob_flush();
			    flush();

		    } else {

		      	echo"<span class='tubeace-errormsg'>YouPorn Video # <b>$video_id</b> not in database or already offline.</span><br>";

				ob_flush();
				flush();
		    }
		}

		echo"<span class='tubeace-succmsg'>Page $page done.</span><br>";

		$page++;
	}
}



if(!empty($_POST['YouPorn'])) {

    $start = $_POST['start'];
	$end = $_POST['end'];

	$page = $start;

	while($page<=$end){

		$apicall = "http://www.youporn.com/api/webmasters/deleted_videos/?page=$page";
		$apiresults = file_get_contents($apicall);
		$apiresults = json_decode($apiresults);
		$videos = $apiresults->videos;        

		foreach($videos as $video) {

		    $video_obj = $video->video;

		    $video_id = $video_obj->video_id;
		    $publish_date = $video_obj->publish_date;

		    //get id of post if in db
			$args = array(
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'site',
						'value' => 'youporn.com'
					),
					array(
						'key' => 'video_id',
						'value' => $video_id
					)
				)
			);

			$the_query = new WP_Query( $args );	

		    //if row affected
		    if($the_query->have_posts()){

				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					//set post_status to trash
					wp_update_post( array('ID'=> get_the_ID(), 'post_status' => 'trash'));

					echo"<span class='tubeace-succmsg'>Moved YouPorn Video '".get_the_title()."' # <b>$video_id</b> to trash.</span><br>";
				}

			    ob_flush();
			    flush();

		    } else {

		      	echo"<span class='tubeace-errormsg'>YouPorn Video # <b>$video_id</b> not in database or already offline.</span><br>";

				ob_flush();
				flush();
		    }
		}

		echo"<span class='tubeace-succmsg'>Page $page done.</span><br>";

		$page++;
	}
}


if(!empty($_POST['Tube8'])) {

    $start = $_POST['start'];
	$end = $_POST['end'];

	$page = $start;

	while($page<=$end){

		$apicall = "http://api.tube8.com/api.php?action=getdeletedvideos&output=json?page=$page";
		$apiresults = file_get_contents($apicall);
		$apiresults = json_decode($apiresults);
		$videos = $apiresults->videos;        

		foreach($videos as $video) {

		    $video_obj = $video->video;

		    $video_id = $video_obj->video_id;
		    $publish_date = $video_obj->publish_date;

		    //get id of post if in db
			$args = array(
				'post_status' => 'publish',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'site',
						'value' => 'tube8.com'
					),
					array(
						'key' => 'video_id',
						'value' => $video_id
					)
				)
			);

			$the_query = new WP_Query( $args );	

		    //if row affected
		    if($the_query->have_posts()){

				while ( $the_query->have_posts() ) {
					$the_query->the_post();

					//set post_status to trash
					wp_update_post( array('ID'=> get_the_ID(), 'post_status' => 'trash'));

					echo"<span class='tubeace-succmsg'>Moved YouPorn Video '".get_the_title()."' # <b>$video_id</b> to trash.</span><br>";
				}

			    ob_flush();
			    flush();

		    } else {

		      	echo"<span class='tubeace-errormsg'>YouPorn Video # <b>$video_id</b> not in database or already offline.</span><br>";

				ob_flush();
				flush();
		    }
		}

		echo"<span class='tubeace-succmsg'>Page $page done.</span><br>";

		$page++;
	}
}

if(!empty($_POST['PornTube'])) {

	$content = file_get_contents("http://webmasters.porntube.com/deletedvideos");

	$lines = explode("\n", $content);

    $line=1;
    //foreach line
    foreach($lines as $lines_key => $lines_val){

    	foreach($fields = explode("|", $lines_val) as $fields_key => $fields_val) {

            if($fields_key==0){
                $video_id = $fields_val;
            }
            if($fields_key==1){
                $title = $fields_val;
            }
    	}

	    //get id of post if in db
		$args = array(
			'post_status' => 'publish',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'site',
					'value' => 'porntube.com'
				),
				array(
					'key' => 'video_id',
					'value' => $video_id
				)
			)
		);

		$the_query = new WP_Query( $args );	

	    //if row affected
	    if($the_query->have_posts()){

			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				//set post_status to trash
				wp_update_post( array('ID'=> get_the_ID(), 'post_status' => 'trash'));

				echo"<p class='tubeace-succmsg'>Moved PornTube Video '".get_the_title()."' # <b>$video_id</b> to trash.</p>";
			}

		    ob_flush();
		    flush();

	    } else {

	      	echo"<p class='tubeace-errormsg'>PornTube Video # <b>$video_id</b> not in database or already offline.</p>";

			ob_flush();
			flush();
	    }
    }	
}


if(empty($_POST)) {
?>
	<div class="wrap">
		<h3>Remove Inactive RedTube Videos</h3>
		<p>
			<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-remove-inactive.php'); ?>" method="post">
		        <table>
		          <tr>
		            <td>Page Start / End
		            </td>
		            <td>
		              <input class="input-60" name="start" value="1">
		              <input class="input-60" name="end" value="100">
		            </td>
		          </tr>            
		        </table>

				<input type="submit" value="Remove Inactive RedTube Videos" class="button-primary" name="RedTube">
			</form>
		</p>
		
		<h3>Remove Inactive Pornhub Videos</h3>
		<p>
			<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-remove-inactive.php'); ?>" method="post">
		        <table>
		          <tr>
		            <td>Page Start / End
		            </td>
		            <td>
		              <input class="input-60" name="start" value="1">
		              <input class="input-60" name="end" value="100">
		            </td>
		          </tr>            
		        </table>
				<input type="submit" value="Remove Inactive Pornhub Videos" class="button-primary" name="Pornhub">
			</form>
		</p>

		<h3>Remove Inactive YouPorn Videos</h3>
		<p>
			<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-remove-inactive.php'); ?>" method="post">
		        <table>
		          <tr>
		            <td>Page Start / End
		            </td>
		            <td>
		              <input class="input-60" name="start" value="1">
		              <input class="input-60" name="end" value="100">
		            </td>
		          </tr>            
		        </table>
				<input type="submit" value="Remove Inactive YouPorn Videos" class="button-primary" name="YouPorn">
			</form>
		</p>

		<h3>Remove Inactive Tube8 Videos</h3>
		<p>
			<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-remove-inactive.php'); ?>" method="post">
		        <table>
		          <tr>
		            <td>Page Start / End
		            </td>
		            <td>
		              <input class="input-60" name="start" value="1">
		              <input class="input-60" name="end" value="100">
		            </td>
		          </tr>            
		        </table>
				<input type="submit" value="Remove Inactive Tube8 Videos" class="button-primary" name="Tube8">
			</form>
		</p>

		<h3>Remove Inactive PornTube Videos</h3>
		<p>
			<form action="<?php echo admin_url('admin.php?page=tubeace/tubeace-remove-inactive.php'); ?>" method="post">
				<input type="submit" value="Remove Inactive PornTube Videos" class="button-primary" name="PornTube">
			</form>
		</p>

	</div>
<?php
}
?>