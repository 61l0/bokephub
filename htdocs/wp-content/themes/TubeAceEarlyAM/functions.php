<?php

function tubeace_enqueue()  {

	// Register Bootstrap CSS
	wp_register_style( 'twitter-bootstrap', 'http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css', array(), '3.0.2', 'all' );
	wp_enqueue_style( 'twitter-bootstrap' );

  //jQuery
  wp_enqueue_script( 'jquery' );

  //rotating thumbs
  wp_enqueue_script(
    'thumbs-script',
    get_template_directory_uri() . '/js/thumbs.js'
  );

	// Loads our main stylesheet.
	wp_enqueue_style( 'tubeace-style', get_stylesheet_uri() );
}
add_action('wp_enqueue_scripts', 'tubeace_enqueue');

function tube_get_limited_string($output, $max_char){

    $output = str_replace(']]>', ']]&gt;', $output);
    $output = strip_tags($output);

  	if ((strlen($output)>$max_char) && ($espacio = strpos($output, " ", $max_char ))){
        //$output = substr($output, 0, $espacio).'...';
        $output = substr($output, 0, $espacio).''; //no ellipsis
		    return $output;
    } else {
        return $output;
    }
}

function tubeace_duration($seconds){

    $hours = floor($seconds / 3600);
    $mins = floor(($seconds - $hours*3600) / 60);
    $s = $seconds - ($hours*3600 + $mins*60);

    $mins = ($mins<10?"0".$mins:"".$mins);
    $s = ($s<10?"0".$s:"".$s);

    $time = ($hours>0?$hours.":":"").$mins.":".$s;
    return $time;
}

function tubeace_thumb($prefix,$title){

    $saved_thmb = get_post_meta( get_the_ID(),'saved_thmb',true);

    $subPath = tubeace_sub_dir_path(get_the_ID());

    $upload_dir = wp_upload_dir();
    $thumb_url = $upload_dir[baseurl]."/tubeace-thumbs/".$subPath."/";

    if($saved_thmb==1){
      $thumb = $thumb_url."/".get_the_ID()."_1.jpg";
    } elseif($saved_thmb>1) {

      $def_thmb = get_post_meta( get_the_ID(),'def_thmb',true);

      $thumb = $thumb_url."/".get_the_ID()."_".$def_thmb.".jpg";

      $rotate_thumbs = "onmouseover=\"thumbStart('$prefix-".get_the_ID()."', $saved_thmb, '$thumb_url');\"
       onmouseout=\"thumbStop('$prefix-".get_the_ID()."', '$thumb_url', '$def_thmb');\"";
    } else {
      return;
    }

    $thumb = "<img class=\"img-responsive\" src=\"$thumb\" $rotate_thumbs id=\"$prefix-".get_the_ID()."\" alt=\"".esc_attr($title)."\">";
    return $thumb;
}


require( get_template_directory() . '/inc/custom-header.php' );

function register_my_menu() {
    register_nav_menu('tubeace-menu', 'Tube Ace Menu' );
}
add_action( 'init', 'register_my_menu' );

function tubeace_pagination(){

    if ( function_exists( 'wp_paginate' ) ) {

      wp_paginate();

    } else {

      $val="<div class=\"nav-previous\">".previous_posts_link()."</div>";
      $val.="<div class=\"nav-previous\">".next_posts_link()."</div>";

      echo $val;
    }
}

// unregister all default WP Widgets
function unregister_default_wp_widgets() {
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Meta');
  unregister_widget('WP_Widget_Search');
  unregister_widget('WP_Widget_Tag_Cloud');
  unregister_widget('WP_Widget_RSS');
  unregister_widget('WP_Widget_Akismet');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

if ( function_exists('register_sidebar') ) {
  register_sidebar(array(
    'name' => 'Main Sidebar',
    'id' => 'sidebar-1',
    'description' => __('Main Sidebar'),
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
  ));

  register_sidebar( array(
    'name' => 'Header Widget Area',
    'id' => 'header-sidebar-1',
    'description' => 'Appears just below the header area',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));

  register_sidebar( array(
    'name' => 'Footer Widget Area',
    'id' => 'footer-sidebar-1',
    'description' => 'Appears in the footer area',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
  ));
}

function tubeace_video_player(){

  $embed_code = get_post_meta( get_the_ID(),'embed_code',true);

  $video_id = get_post_meta( get_the_ID(),'video_id',true);
  $site = get_post_meta( get_the_ID(),'site',true);

  if(!empty($embed_code)){

    $code = "<div class=\"flex-video\">$embed_code</div>";

  } elseif($site =="bangyoulater.com"){

    $code = get_site_option('tubeace_bangyoulater_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="drtuber.com"){

    $code = get_site_option('tubeace_drtuber_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="pornhub.com"){

    $code = get_site_option('tubeace_pornhub_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="keezmovies.com"){

    $code = get_site_option('tubeace_keezmovies_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="porntube.com"){

    $code = get_site_option('tubeace_porntube_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($video_id>1 && $site =="redtube.com"){

    $code = get_site_option('tubeace_redtube_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="spankwire.com"){

    $code = get_site_option('tubeace_spankwire_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="sunporno.com"){

    $code = get_site_option('tubeace_sunporno_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="tube8.com"){

    $code = get_site_option('tubeace_tube8_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="xhamster.com"){

    $code = get_site_option('tubeace_xhamster_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="xvideos.com"){

    $code = get_site_option('tubeace_xvideos_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } elseif($site =="youporn.com"){

    $code = get_site_option('tubeace_youporn_video_player_code');
    $code = str_replace("{video_id}", $video_id, $code);
    $code = "<div class=\"flex-video\">$code</div>";

  } else {

    if(get_site_option('tubeace_default_video_player')=="flowplayer3"){

      $code = get_site_option('tubeace_flowplayer3_code');
      $code = str_replace("{plugin_url}", plugins_url('tubeace'), $code);
      $code = str_replace("{video_file}", get_post_meta(get_the_ID(), 'video_url',true), $code);
      $code = "<div class=\"flex-video\">$code</div>";
    }

    if(get_site_option('tubeace_default_video_player')=="flowplayer5"){

      $code = get_site_option('tubeace_flowplayer5_code');
      $code = str_replace("{plugin_url}", plugins_url('tubeace'), $code);
      $code = str_replace("{video_file}", get_post_meta(get_the_ID(), 'video_url',true), $code);
    }

  }
  echo $code;
}


function tubeace_sponsor_link(){

  $sponsor_link_url = get_post_meta( get_the_ID(),'sponsor_link_url',true);
  $sponsor_link_txt = get_post_meta( get_the_ID(),'sponsor_link_txt',true);

  $sponsor_link = "<a class=\"sponsor_link\" href=\"$sponsor_link_url\">$sponsor_link_txt</a>";

  echo $sponsor_link;
}

function tubeace_misc($id){

  $misc = get_post_meta( get_the_ID(),"misc$id",true);
  echo $misc;
}


function video_add() {

global $wpdb;
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "video_upload";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `name` text NULL,
        `desc` text NULL,
        `dur` text NULL,
        `tags` text NULL,
        `performers` text NULL,
        `source` text NULL,
        `site` text NULL,
        `ip` text NULL,
        `username` text NULL,
        `tipestamp` datetime NULL,

    UNIQUE (`id`)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
    // starts output buffering
    ob_start();
      $html = ob_get_clean();
    // does the inserting, in case the form is filled and submitted
	    if ( isset( $_POST["submit_form"] ) && $_POST["title"] != "" && $_POST["reg"] == "acc") {
	        $table = $wpdb->prefix."video_upload";
	        $title = strip_tags($_POST["title"], "");
	        $desc = strip_tags($_POST["desc"], "");
	        $tags = strip_tags($_POST["tags"], "");
	        $perf = strip_tags($_POST["perf"], "");
	        $wpdb->insert(
	            $table,
	            array(
	                'name' => $title,
	                'desc' => $desc,
	                'tags' => $tags,
	                'performers' => $perf
	            )
	        );
	        $product = intval($wpdb->insert_id);//numb
	        $html = "<script type=\"text/javascript\">      document.location.href=\"http://bokephub.com/up/basic.php?id=";
	        $html .= $product;
	        $html .= "\";    </script>";
	    }
	elseif(isset( $_REQUEST["id"] ) && $_REQUEST["id"] != "" ) {
	 	   $id2 = $_REQUEST["id"];
		   $id2 = $id2 ;//numb
		   $html = "<h3> Thanks for your support! Film will be alavaible soon. </h3><br> <h4> If you want to upload more videos   <a href=\"http://bokephub.com/add-video\">click HERE</a>. </h4>";
		   }


	elseif(true) {
		if(isset( $_POST["submit_form"] ) && $_POST["title"] == "" ) { 	$titleError = "<p style=\"color:red;\"> Title reqired </p>";	}
		elseif(isset( $_POST["submit_form"] ) && $_POST["reg"] != "acc"){$regError = "<p style=\"color:red;\"> You must accept terms and conditions! </p>";    }

	?>


		<form action="#v_form" method="post" id="v_form">
		  	<p>Title:<br>
		        <input class="text4" type="text" name="title" id="title" />
		        <?php echo $titleError;?></p>
		        <p>Description:<br>
		        <textarea class="text4" name="desc" id="desc"  rows="3" cols="30"/></textarea></p>
		       <p>Tags:<br>
		        <input class="text4" type="text" name="tags" id="desc" /></p>
		       <p>Performers:<br>
		        <input class="text4" type="text" name="perf" id="desc" /></p>
			<p><input type="checkbox" name="reg" value="acc"> I agree to the Terms and Conditions<br><?php echo $regError;?></p>
		    <p><input class="btn2" type="submit" name="submit_form" value="Next step" /></p>
		    </form>
		    <?php

		}
    return $html;
}
function theme_prefix_setup() {
add_theme_support( 'custom-logo', array(
	'height'      => 100,
	'width'       => 400,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
) );
}
add_action( 'after_setup_theme', 'theme_prefix_setup' );

function theme_prefix_the_custom_logo() {
	
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}

}


?>
