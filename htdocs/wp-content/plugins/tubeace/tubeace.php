<?php
/*
Plugin Name: Tube Ace
Plugin URI: http://www.tubeace.com
Description: Run an adult video tube featuring a mass video & RedTube import tool.
Version: 1.8.2
Author: Tube Ace
Author URI: http://www.tubeace.com
*/

set_time_limit(0);

function tubeace_register_custom_menu_page() {
   add_options_page('My Options', 'Tube Ace', 'manage_options', 'tubeace/tubeace-settings.php');   
   add_menu_page('Mass Import Videos', 'Tube Ace', 'manage_options', 'tubeace/tubeace-import.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Import', 'Mass Import Videos', 'manage_options', 'tubeace/tubeace-import.php');

   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace RedTube Import', 'RedTube Import', 'manage_options', 'tubeace/tubeace-redtube.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace BangYouLater Import', 'BangYouLater Import', 'manage_options', 'tubeace/tubeace-bangyoulater.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace DrTuber Import', 'DrTuber Import', 'manage_options', 'tubeace/tubeace-drtuber.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace KeezMovies Import', 'KeezMovies Import', 'manage_options', 'tubeace/tubeace-keezmovies.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Pornhub Import', 'Pornhub Import', 'manage_options', 'tubeace/tubeace-pornhub.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Pornhub API Import', 'Pornhub API Import', 'manage_options', 'tubeace/tubeace-pornhub-api.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace PornTube Import', 'PornTube Import', 'manage_options', 'tubeace/tubeace-porntube.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace SpankWire Import', 'SpankWire Import', 'manage_options', 'tubeace/tubeace-spankwire.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Sun Porno Import', 'Sun Porno Import', 'manage_options', 'tubeace/tubeace-sunporno.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Tube8 Import', 'Tube8 Import', 'manage_options', 'tubeace/tubeace-tube8.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Tube8 API Import', 'Tube8 API Import', 'manage_options', 'tubeace/tubeace-tube8-api.php');   
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace xHamster Import', 'xHamster Import', 'manage_options', 'tubeace/tubeace-xhamster.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace XVIDEOS Import', 'XVIDEOS Import', 'manage_options', 'tubeace/tubeace-xvideos.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace YouPorn Import', 'YouPorn Import', 'manage_options', 'tubeace/tubeace-youporn.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace YouPorn API Import', 'YouPorn API Import', 'manage_options', 'tubeace/tubeace-youporn-api.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Generate Thumbnails', 'Generate Thumbnails & Copy Videos', 'manage_options', 'tubeace/tubeace-generate-thumbs-copy-videos.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Settings', 'Settings', 'manage_options', 'tubeace/tubeace-settings.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Cron Jobs', 'Cron Jobs', 'manage_options', 'tubeace/tubeace-cron.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Remove Inactive Videos', 'Remove Inactive Videos', 'manage_options', 'tubeace/tubeace-remove-inactive.php');
   add_submenu_page('tubeace/tubeace-import.php', 'Tube Ace Test Server', 'Test Server', 'manage_options', 'tubeace/tubeace-test-server.php');


   add_submenu_page('null','Edit RedTube Cron Job', 'Tube Ace', 'manage_options', 'tubeace/tubeace-cron-redtube-edit.php');
   add_submenu_page('null','Edit Pornhub Cron Job', 'Tube Ace', 'manage_options', 'tubeace/tubeace-cron-pornhub-edit.php');
   add_submenu_page('null','Edit Tube8 Cron Job', 'Tube Ace', 'manage_options', 'tubeace/tubeace-cron-tube8-edit.php');
   add_submenu_page('null','Edit YouPorn Cron Job', 'Tube Ace', 'manage_options', 'tubeace/tubeace-cron-youporn-edit.php'); 
}
add_action('admin_menu', 'tubeace_register_custom_menu_page');

// Add settings link on plugin page
function tubeace_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=tubeace/tubeace-settings.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'tubeace_settings_link' );

//import js and css
function tubeace_admin_enqueue($hook) {

    
    if( 'tubeace/tubeace-import.php' == $hook ) wp_enqueue_script('tubeace', plugins_url('/tubeace/js/import.js'), array('jquery'), false, true);
    if( 'tubeace/tubeace-generate-thumbs.php' == $hook ) wp_enqueue_script('tubeace', plugins_url('/tubeace/generate-thumbs.js'), array('jquery'), false, true);

    // in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
    wp_localize_script( 'tubeace', 'ajax_object',
    array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    #css
    wp_register_style( 'prefix-style', plugins_url('tubeace.css', __FILE__) );    
    wp_enqueue_style( 'prefix-style' );
}
add_action( 'admin_enqueue_scripts', 'tubeace_admin_enqueue' );

// ajax import callback
function tubeace_ajax_import_callback() {
  global $wpdb;

  include'ajax/import.php';
  die();
}
add_action('wp_ajax_my_action', 'tubeace_ajax_import_callback');

//delete thumb of deleted post
function tubeace_delete_thumb( $postid ) {

  $upload_dir = wp_upload_dir();
  $subPath = tubeace_sub_dir_path($postid); 
  $thumb = $upload_dir[basedir]."/tubeace-thumbs/".$subPath."/".$postid."_1.jpg";

  if(file_exists($thumb)){
    unlink($thumb);
  }
}
add_action( 'deleted_post', 'tubeace_delete_thumb' );

//register performer taxonomy
function tubeace_performers_taxonomy() {

  $labels = array(
    'name' => _x( 'Performers', 'taxonomy general name' ),
    'singular_name' => _x( 'Performer', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Performers' ),
    'popular_items' => __( 'Popular Performers' ),
    'all_items' => __( 'All Performers' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Performers' ), 
    'update_item' => __( 'Update Performer' ),
    'add_new_item' => __( 'Add New Performer' ),
    'new_item_name' => __( 'New Performer Name' ),
    'separate_items_with_commas' => __( 'Separate Performers with commas' ),
    'add_or_remove_items' => __( 'Add or remove Performers' ),
    'choose_from_most_used' => __( 'Choose from the most used Performers' ),
    'menu_name' => __( 'Performers' ),
  ); 

  register_taxonomy('performer','post',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'performer' ),
  ));
}
add_action( 'init', 'tubeace_performers_taxonomy', 0 );

//on activation
function tubeace_activate(){

    //add settings options
    $flowplayer3_code = "
    <script type=\"text/javascript\" src=\"{plugin_url}/libs/flowplayer/flowplayer-3.2.10.min.js\"></script>
    <div style=\"width:868px;height:688px;\" id=\"player\"></div> 
    <script language=\"JavaScript\">
    flowplayer(\"player\", \"{plugin_url}/libs/flowplayer/flowplayer-3.2.11.swf\", { 
    clip: { 
      autoPlay: true,  
      autoBuffering: true,
      url: \"{video_file}\"
    } });
    </script>";

    $flowplayer5_code = "
    <script src=\"{plugin_url}/libs/flowplayer/flowplayer.min.js\"></script>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"{plugin_url}/libs/flowplayer/skin/minimalist.css\" />
    <div class=\"flowplayer\" id=\"player\" data-swf=\"{plugin_url}/libs/flowplayer/flowplayer.swf\">
      <video preload=\"auto\" src=\"{video_file}\"></video>
    </div>";

    $redtube_code = "<iframe src=\"http://embed.redtube.com/?id={video_id}&bgcolor=000000\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

    $bangyoulater_code = "<iframe src=\"http://www.bangyoulater.com/embed.php?id={video_id}\" frameborder=\"0\" height=\"688\" width=\"868\" scrolling=\"no\" name=\"byl_embed_video\"></iframe>";

    $drtuber_code = "<iframe src=\"http://www.drtuber.com/embed/{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";
            
    $keezmovies_code = "<iframe name=\"keezmovies_embed_video\" src=\"http://www.keezmovies.com/embed/-{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

    $porntube_code = "<iframe width=\"868\" height=\"688\" type=\"text/html\" src=\"http://embed.porntube.com/{video_id}\" frameborder=\"0\" allowFullScreen id=\"f1289832\" class=\"porntube-player\"></iframe>";

    $pornhub_code = "<iframe src=\"http://www.pornhub.com/embed/{video_id}\" frameborder=\"0\" height=\"688\" width=\"868\" scrolling=\"no\" name=\"ph_embed_video\"></iframe>";

    $spankwire_code = "<iframe src=\"http://www.spankwire.com/EmbedPlayer.aspx?ArticleId={video_id}\" frameborder=\"0\" height=\"688\" width=\"868\" scrolling=\"no\" name=\"spankwire_embed_video\"></iframe>";

    $sunporno_code = "<iframe src=\"http://embeds.sunporno.com/embed/{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

    $tube8_code = "<iframe src=\"http://www.tube8.com/embed/category/title/{video_id}/\" frameborder=0 height=\"688\" width=\"868\" scrolling=no name=\"t8_embed_video\"></iframe>";

    $youporn_code = "<iframe src=\"http://www.youporn.com/embed/{video_id}/\" frameborder=\"0\" height=\"868\" width=\"868\" scrolling=\"no\" name=\"yp_embed_video\"></iframe>";

    $xhamster_code = "<iframe width=\"868\" height=\"688\" src=\"http://xhamster.com/xembed.php?video={video_id}\" frameborder=\"0\" scrolling=\"no\"></iframe>";

    $xvideos_code = "<iframe src=\"http://flashservice.xvideos.com/embedframe/{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

    add_site_option('tubeace_flowplayer3_code', $flowplayer3_code);
    add_site_option('tubeace_flowplayer5_code', $flowplayer5_code);
    add_site_option('tubeace_default_video_player', 'flowplayer5');
    add_site_option('tubeace_redtube_video_player_code', $redtube_code);
    add_site_option('tubeace_bangyoulater_video_player_code', $bangyoulater_code);
    add_site_option('tubeace_drtuber_video_player_code', $drtuber_code);
    add_site_option('tubeace_keezmovies_video_player_code', $keezmovies_code);
    add_site_option('tubeace_porntube_video_player_code', $porntube_code);
    add_site_option('tubeace_pornhub_video_player_code', $pornhub_code);
    add_site_option('tubeace_spankwire_video_player_code', $spankwire_code);
    add_site_option('tubeace_sunporno_video_player_code', $sunporno_code);
    add_site_option('tubeace_tube8_video_player_code', $tube8_code);
    add_site_option('tubeace_youporn_video_player_code', $youporn_code);
    add_site_option('tubeace_xhamster_video_player_code', $xhamster_code); 
    add_site_option('tubeace_xvideos_video_player_code', $xvideos_code); 
    add_site_option('tubeace_thumb_width', '320');
    add_site_option('tubeace_thumb_height', '240');
    add_site_option('tubeace_frames_option', 'total_frames');
    add_site_option('tubeace_frames_value', '3');
    add_site_option('tubeace_def_thmb', '3');    
    add_site_option('tubeace_ffmpeg_path', '/usr/bin/ffmpeg');
    add_site_option('tubeace_schedule_per_day', '0');
    add_site_option('tubeace_cron_email', get_option('admin_email'));
    add_site_option('tubeace_alternate_video_url', '');

    add_site_option('tubeace_version', '1.8');

    //create pages
    $pagesArr = array('Newest' => 'newest', 'Most Viewed' => 'most-viewed', 'Highest Rated' => 'highest-rated', 'Categories' => 'categories', 'Porn Star List' => 'porn-star-list');

    foreach($pagesArr as $key => $value){

        $my_post = array(
          'post_type'     => 'page',
          'post_title'    => $key,
          'post_name'     => $value,
          'post_status'   => 'publish',
          'post_author'   => 1,
        );

        // Insert the post into the database
        wp_insert_post( $my_post );  
    }  
}
register_activation_hook( __FILE__, 'tubeace_activate' );

//on deactivation
function tubeace_deactivate(){

    //delete settings options
    delete_site_option('tubeace_flowplayer3_code');
    delete_site_option('tubeace_flowplayer5_code');
    delete_site_option('tubeace_default_video_player');
    delete_site_option('tubeace_redtube_video_player_code');
    delete_site_option('tubeace_bangyoulater_video_player_code');
    delete_site_option('tubeace_drtuber_video_player_code');
    delete_site_option('tubeace_xvideos_video_player_code');    
    delete_site_option('tubeace_porntube_video_player_code');
    delete_site_option('tubeace_pornhub_video_player_code');
    delete_site_option('tubeace_sunporno_code_video_player_code');
    delete_site_option('tubeace_xhamster_video_player_code');      
    delete_site_option('tubeace_thumb_width');
    delete_site_option('tubeace_thumb_height');
    delete_site_option('tubeace_frames_option');
    delete_site_option('tubeace_frames_value');
    delete_site_option('tubeace_def_thmb');
    delete_site_option('tubeace_ffmpeg_path');
    delete_site_option('tubeace_schedule_per_day');
    delete_site_option('tubeace_cron_email');
    delete_site_option('tubeace_alternate_video_url');

    //delete /uploads/tube-ace folder
}
register_deactivation_hook( __FILE__, 'tubeace_deactivate' );

//upgrade
function tubeace_upgrade(){

    $version = get_site_option('tubeace_version');
    if(!empty($version)){

        if (version_compare($version, '1.2', '<')){

            $porntube_code = "<iframe width=\"868\" height=\"688\" type=\"text/html\" src=\"http://embed.porntube.com/{video_id}\" frameborder=\"0\" allowFullScreen id=\"f1289832\" class=\"porntube-player\"></iframe>";
            
            //set porntube video player code
            add_site_option('tubeace_porntube_video_player_code', $porntube_code);

            //update version
            update_site_option('tubeace_version', '1.2');
            echo"<div class=\"updated\"><p>Upgrade to Tube Ace 1.2 complete.</p></div>";
        }

        if (version_compare($version, '1.3', '<')){

            $pornhub_code = "<iframe src=\"http://www.pornhub.com/embed/{video_id}\" frameborder=\"0\" height=\"688\" width=\"868\" scrolling=\"no\" name=\"ph_embed_video\"></iframe>";

            $xhamster_code = "<iframe width=\"868\" height=\"688\" src=\"http://xhamster.com/xembed.php?video={video_id}\" frameborder=\"0\" scrolling=\"no\"></iframe>";

            //set porntube video player code
            add_site_option('tubeace_pornhub_video_player_code', $pornhub_code);
            add_site_option('tubeace_xhamster_video_player_code', $xhamster_code);        

            //update version
            update_site_option('tubeace_version', '1.3');
            echo"<div class=\"updated\"><p>Upgrade to Tube Ace 1.3 complete.</p></div>";

        }

        if (version_compare($version, '1.4', '<')){

            //create pages
            $pagesArr = array('Newest' => 'newest', 'Most Viewed' => 'most-viewed', 'Highest Rated' => 'highest-rated', 'Categories' => 'categories', 'Porn Star List' => 'porn-star-list');

            foreach($pagesArr as $key => $value){

                $my_post = array(
                  'post_type'     => 'page',
                  'post_title'    => $key,
                  'post_name'     => $value,
                  'post_status'   => 'publish',
                  'post_author'   => 1,
                );

                // Insert the post into the database
                wp_insert_post( $my_post );  
            }         
    
            //update version
            update_site_option('tubeace_version', '1.4');
            echo"<div class=\"updated\"><p>Upgrade to Tube Ace 1.4 complete.</p></div>";
        }     

        if (version_compare($version, '1.5', '<')){

            $keezmovies_code = "<iframe name=\"keezmovies_embed_video\" src=\"http://www.keezmovies.com/embed/-{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

            $spankwire_code = "<iframe src=\"http://www.spankwire.com/EmbedPlayer.aspx?ArticleId={video_id}\" frameborder=\"0\" height=\"688\" width=\"868\" scrolling=\"no\" name=\"spankwire_embed_video\"></iframe>";

            $tube8_code = "<iframe src=\"http://www.tube8.com/embed/category/title/{video_id}/\" frameborder=0 height=\"688\" width=\"868\" scrolling=no name=\"t8_embed_video\"></iframe>";

            $youporn_code = "<iframe src=\"http://www.youporn.com/embed/{video_id}/\" frameborder=\"0\" height=\"868\" width=\"868\" scrolling=\"no\" name=\"yp_embed_video\"></iframe>";

            //set video player codes
            add_site_option('tubeace_keezmovies_video_player_code', $keezmovies_code);
            add_site_option('tubeace_spankwire_video_player_code', $spankwire_code);
            add_site_option('tubeace_tube8_video_player_code', $tube8_code);
            add_site_option('tubeace_youporn_video_player_code', $youporn_code);    

            //update version
            update_site_option('tubeace_version', '1.5');
            echo"<div class=\"updated\"><p>Upgrade to Tube Ace 1.5 complete.</p></div>";
        }

        if (version_compare($version, '1.6', '<')){

            $bangyoulater_code = "<iframe src=\"http://www.bangyoulater.com/embed.php?id={video_id}\" frameborder=\"0\" height=\"688\" width=\"868\" scrolling=\"no\" name=\"byl_embed_video\"></iframe>";

            $sunporno_code = "<iframe src=\"http://embeds.sunporno.com/embed/{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

            //set video player codes
            add_site_option('tubeace_bangyoulater_video_player_code', $bangyoulater_code);
            add_site_option('tubeace_sunporno_video_player_code', $sunporno_code);
    
            //update version
            update_site_option('tubeace_version', '1.6');
            echo"<div class=\"updated\"><p>Upgrade to Tube Ace 1.6 complete.</p></div>";
        }

        if (version_compare($version, '1.7', '<')){

            $keezmovies_code = "<iframe name=\"keezmovies_embed_video\" src=\"http://www.keezmovies.com/embed/{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

            //update keezmovies video player code
            update_site_option('tubeace_keezmovies_video_player_code', $keezmovies_code);

            //new video player codes
            $drtuber_code = "<iframe src=\"http://www.drtuber.com/embed/{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";
            $xvideos_code = "<iframe src=\"http://flashservice.xvideos.com/embedframe/{video_id}\" frameborder=\"0\" width=\"868\" height=\"688\" scrolling=\"no\"></iframe>";

            //set video player codes
            add_site_option('tubeace_drtuber_video_player_code', $drtuber_code);
            add_site_option('tubeace_xvideos_video_player_code', $xvideos_code);

            //update version
            update_site_option('tubeace_version', '1.7');
            echo"<div class=\"updated\"><p>Upgrade to Tube Ace 1.7 complete.</p></div>";
        }  

        if (version_compare($version, '1.8', '<')){
            //update version

            add_site_option('tubeace_alternate_video_url', '');

            update_site_option('tubeace_version', '1.8');
            echo"<div class=\"updated\"><p>Upgrade to Tube Ace 1.8 complete.</p></div>";
        }  


    }
}
add_action('init', 'tubeace_upgrade');

//alternate video url
function tubeace_alt_url_redirect( $url, $post, $leavename ) {
  
  $tubeace_alternate_video_url = get_site_option( 'tubeace_alternate_video_url' );

  if( $tubeace_alternate_video_url!= false){

    $url = str_replace('{video_page_url}',$url, $tubeace_alternate_video_url);
  }

  return $url;
}
add_filter( 'post_link', 'tubeace_alt_url_redirect', 10, 3 );

function tubeace_hourly_function() {

  $sites = array('redtube','pornhub','tube8','youporn');

  foreach($sites as $site){

    //get options array
    $existingCronNames = get_site_option('tubeace_cron_'.$site.'_hourly');

    foreach($existingCronNames as $name){

      $option_values = get_site_option('tubeace_cron_'.$site.'_'.$name.'_hourly');

      //include 
      include('inc/cron-'.$site.'-option-values.php');

      //include import
      include('inc/import-'.$site.'.php');


      tubeace_cron_email($outputAll, $name);
    }
  }
}
add_action( 'tubeace_hourly', 'tubeace_hourly_function' );

function tubeace_twicedaily_function() {


  $sites = array('redtube','pornhub','tube8','youporn');

  foreach($sites as $site){

    //get options array
    $existingCronNames = get_site_option('tubeace_cron_'.$site.'_twicedaily');

    foreach($existingCronNames as $name){

      $option_values = get_site_option('tubeace_cron_'.$site.'_'.$name.'_twicedaily');

      //include 
      include('inc/cron-'.$site.'-option-values.php');

      //include import
      include('inc/import-'.$site.'.php');


      tubeace_cron_email($outputAll, $name);
    }
  }
}
add_action( 'tubeace_twicedaily', 'tubeace_twicedaily_function' );

function tubeace_daily_function() {

  $sites = array('redtube','pornhub','tube8','youporn');

  foreach($sites as $site){

    //get options array
    $existingCronNames = get_site_option('tubeace_cron_'.$site.'_daily');

    foreach($existingCronNames as $name){

      $option_values = get_site_option('tubeace_cron_'.$site.'_'.$name.'_daily');

      //include 
      include('inc/cron-'.$site.'-option-values.php');

      //include import
      include('inc/import-'.$site.'.php');


      tubeace_cron_email($outputAll, $name);
    }
  }
}
add_action( 'tubeace_daily', 'tubeace_daily_function' );

#various functions
function tubeace_cron_email($message, $name){

  $to = get_site_option('tubeace_cron_email');

  if(!empty($message) && !empty($to)){

    $subject = get_site_option('blogname')." '$name' RedTube Cron Import Update";

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers.= "From: ".get_site_option('admin_email');

    $css = "<style>";
    $css.=".tubeace-succmsg{color:#390;font-weight:bold;}";
    $css.=".tubeace-errormsg{color:red;font-weight:bold;}";
    $css.= "</style>";

    $message = $css.$message;

    mail($to, $subject, $message, $headers);
  }
}


function tubeace_header($show_await_thumbs){

  echo"<a href=\"http://www.tubeace.com/\" target=\"_blank\"><img src=\"".plugins_url('/tubeace/images/logo.png')."\" alt=\"Tube Ace\"></a><br />";

  if($show_await_thumbs==1){

    $args = array(
      'meta_query' => array(
       'relation' => 'OR',
        array(
          'key' => 'await_thumb',
          'value' => 1,
        ),
        array(
          'key' => 'await_video',
          'compare' => 1
        )
      )
    );

    $query = new WP_Query( $args );

    $num_rows = $query->found_posts;

    if($num_rows>0){

      echo"<span class=\"tubeace-errormsg\">There are $num_rows videos awaiting thumbnail generation and/or video copying.</span> <a href=\"".admin_url('admin.php?page=tubeace/tubeace-generate-thumbs-copy-videos.php')."\">Start Thumbnail Generation / Video Copying</a>";
    }
  }
}


//autoscheduling for mass importing
function tubeace_auto_sched_next_date($i){

  global $wpdb;

  $currentDay = date("d");
  $currentMonth = date("m");
  $currentYear = date("Y");
  
  $checkDate = date("Y-m-d",mktime(0, 0, 0, $currentMonth, $currentDay+$i, $currentYear));

  $num = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'post' AND post_date_gmt BETWEEN '$checkDate 00:00:00' AND '$checkDate 23:59:59'" );

  //if less results exist that to sched per day
  if(get_site_option('tubeace_schedule_per_day') > $num){
    return $checkDate;
  } else {
    $i++;
    return tubeace_auto_sched_next_date($i);     
  } 
}

function tubeace_sub_dir_path($id){

  if($id<10){
    $subPath = 0;
  } else {
    $subPath = substr($id, -2);
  }   
  return $subPath;
}

function tubeace_resize_thumb($thumb_source,$thumb_path,$img_ext,$width,$height,$newwidth,$newheight){
  
  if($img_ext=="jpg"){
    if(@imagecreatefromjpeg($thumb_source)==true){
      $source = imagecreatefromjpeg($thumb_source);
      $is_valid_img = true;
    } else {
      $is_valid_img = false;
    }             
  } elseif ($img_ext=="png"){
    if(imagecreatefrompng($thumb_source)==true){
      $source = imagecreatefrompng($thumb_source);
      $is_valid_img = true;             
    } else {
      $is_valid_img = false;
    }             
  } elseif ($img_ext=="gif"){
    if(imagecreatefromgif($thumb_source)==true){          
      $source = imagecreatefromgif($thumb_source);
      $is_valid_img = true;             
    } else {
      $is_valid_img = false;
    }                           
  } else {
    $is_valid_img = false;  
  }
        
  if($is_valid_img){
  
    //CREATES IMAGE WITH NEW SIZES 
    $thumb = imagecreatetruecolor($newwidth, $newheight);       
  
    //RESIZES OLD IMAGE TO NEW SIZES 
    imagecopyresampled($thumb,  $source,  0,  0,  0,  0,  $newwidth,  $newheight,  $width,  $height); 

    //SAVES IMAGE AND SETS QUALITY || NUMERICAL VALUE = QUALITY ON SCALE OF 1-100 
    imagejpeg($thumb,  $thumb_path,  100);      
  }
  return $is_valid_img;
}

function tubeace_ffmpeg_thumbs($id,$duration,$newwidth,$newheight,$thumbPath,$inputFile){
  
  if(get_site_option('tubeace_frames_option')=="total_frames"){
    $sec_between = $duration / get_site_option('tubeace_frames_value');
  } else {
    $sec_between = get_site_option('tubeace_frames_value');
  }
  
  //start at 2 seconds
  $num=1;
  for($sec=2; $sec<=$duration; $sec+=$sec_between){
    $secfl = floor($sec);
    $cmd = get_site_option('tubeace_ffmpeg_path')." -y -ss $secfl -i $inputFile -vframes 1 -f mjpeg -s ".$newwidth."x".$newheight." ".$thumbPath."/".$id."_".$num.".jpg";
    exec($cmd); 
    $num++;
  }
}

function tubeace_video_metadata($video) {  

  $ffmpegPath = get_site_option('tubeace_ffmpeg_path');
  $command = "$ffmpegPath  -i " . $video . " -vstats 2>&1";  
  $output = shell_exec($command);  
  
  // get width/height  
  //$result = ereg ( '[0-9]?[0-9][0-9][0-9]x[0-9][0-9][0-9][0-9]?', $output, $regs );  
  $regex = "/([0-9]{3,4})x([0-9]{3,4})/";
  if (preg_match($regex, $output, $regs)) {
    $result = $regs[0];
  } else {
    $result = "";
  } 
  
  $vals = (explode ( 'x', $regs [0] ));  
  $width = $vals [0] ? $vals [0] : null;  
  $height = $vals [1] ? $vals [1] : null;  
  
  //get duration
  preg_match('/Duration: (\d{2}:\d{2}:\d{2}\.\d{2})/', $output, $matches);
  $duration = $matches[1];
  list($hr,$m,$s) = explode(':', $duration);
  $duration = ( (int)$hr*3600 ) + ( (int)$m*60 ) + $s;
  
  //get framerate
  preg_match('/(\d+.?\d+) (fps|tbr)/', $output, $matches);
  $framerate = $matches[1];
  
  //framecount
  $framecount = floor($duration * $framerate);
  
  if(empty($width)){
    $error="<br /><span class=\"tubeace-errormsg\">Video width metadata not found. Make sure FFmpeg is installed and path is correct in settings.</span>";
    //return false;
  } elseif(empty($height)){
    $error="<br /><span class=\"tubeace-errormsg\">Video height metadata not found. Make sure FFmpeg is installed and path is correct in settings.</span>";
    //return false;   
  } elseif(empty($duration)){
    $error="<br /><span class=\"tubeace-errormsg\">Video duration metadata not found. Make sure FFmpeg is installed and path is correct in settings.</span>";
    //return false;   
  } elseif(empty($framerate)){
    $error="<br /><span class=\"tubeace-errormsg\">Video framerate metadata not found. Make sure FFmpeg is installed and path is correct in settings.</span>";
    //return false;   
  } elseif(empty($framecount)){
    $error="<br /><span class=\"tubeace-errormsg\">Video framecount metadata not found. Make sure FFmpeg is installed and path is correct in settings.</span>";
    //return false;   
  } 

  return array ('error' => $error, 'width' => $width, 'height' => $height, 'duration' => $duration,  'framerate' => $framerate,   'framecount' => $framecount);  
  
} 


function tubeace_get_users_with_role( $roles, $current_selected ) {

  global $wpdb;
  if ( ! is_array( $roles ) )
      $roles = array_walk( explode( ",", $roles ), 'trim' );
  $sql = '
      SELECT  ID, display_name
      FROM        ' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
      ON          ' . $wpdb->users . '.ID             =       ' . $wpdb->usermeta . '.user_id
      WHERE       ' . $wpdb->usermeta . '.meta_key        =       \'' . $wpdb->prefix . 'capabilities\'
      AND     (
  ';
  $i = 1;
  foreach ( $roles as $role ) {
      $sql .= ' ' . $wpdb->usermeta . '.meta_value    LIKE    \'%"' . $role . '"%\' ';
      if ( $i < count( $roles ) ) $sql .= ' OR ';
      $i++;
  }
  $sql .= ' ) ';
  $sql .= ' ORDER BY display_name ';

  $results = $wpdb->get_results( $sql);
  foreach ($results as $result){

      unset($selected);

      if($result->ID == $current_selected){
        $selected = "selected =\"selected\"";
      }

      echo "<option value=\"$result->ID\" $selected>$result->display_name</option>\n";
    }
}
?>