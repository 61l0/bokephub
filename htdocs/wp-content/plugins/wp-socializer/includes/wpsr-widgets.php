<?php
/*
 * Widget for WP Socializer plugin
 * Version : 1.1
 * Author : Aakash Chakravarthy
 * Since : 2.3
 */

class WPSR_Widget extends WP_Widget {
	/** constructor */
	function WPSR_Widget() {
		$widget_ops = array('classname' => 'widget_wp_socializer', 'description' => __("Add Facebook like box and Google+ page badge to the sidebar.", 'wpsr') );
		$control_ops = array('width' => 360, 'height' => 500);
		$this->WP_Widget('wp_socializer', 'WP Socializer', $widget_ops, $control_ops);
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		$widget_type = $instance['widget_type'];
		
		$fblike_url = $instance['fblike_url'];
		$fblike_color = $instance['fblike_color'];
		$fblike_border = $instance['fblike_border'];
		$fblike_size = explode(',', $instance['fblike_size']);
		$fblike_showfaces = $instance['fblike_showfaces'];
		$fblike_showstream = $instance['fblike_showstream'];
		$fblike_showheader = $instance['fblike_showheader'];
		
		$gplus_pageid = $instance['gplus_pageid'];
		$gplus_badgetype = $instance['gplus_badgetype'];
		
		$output = "\n\n<!-- Begin WP Socializer Widget v" . WPSR_VERSION . "-->\n";
		
		//Start the Output
		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;
			
		if($widget_type == 'facebook-like'){
		
			$output .= '<iframe src="//www.facebook.com/plugins/likebox.php?href=' . urlencode($fblike_url) . '&amp;width=' . trim($fblike_size[0]) . '&amp;height=' . trim($fblike_size[1]) . '&amp;colorscheme=' . $fblike_color . '&amp;show_faces=' . $fblike_showfaces . '&amp;border_color=' . $fblike_border . '&amp;stream=' . $fblike_showstream . '&amp;header=' . $fblike_showheader . '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . trim($fblike_size[0]) . 'px; height:' . trim($fblike_size[1]) . 'px;" allowTransparency="true"></iframe>';
			
		}elseif($widget_type == 'googleplus-badge'){
			
			$output .= '<script type="text/javascript">(function() {var po = document.createElement("script");po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(po, s);})()</script>';
			$output .= '<div class="g-page" data-width="250" data-rel="publisher" data-href="https://plus.google.com/' . $gplus_pageid .'" ' . (($gplus_badgetype == 'smallbadge') ? 'data-layout="landscape"' : '') . '></div>';
			
		}
		$output .= "\n<!-- End WP Socializer Widget v" . WPSR_VERSION . "-->\n\n";
		
		echo $output;
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['widget_type'] = strip_tags($new_instance['widget_type']);
		
		$instance['fblike_url'] = strip_tags($new_instance['fblike_url']);
		$instance['fblike_color'] = strip_tags($new_instance['fblike_color']);
		$instance['fblike_border'] = strip_tags($new_instance['fblike_border']);
		$instance['fblike_size'] = strip_tags($new_instance['fblike_size']);
		$instance['fblike_showfaces'] = strip_tags($new_instance['fblike_showfaces']);
		$instance['fblike_showstream'] = strip_tags($new_instance['fblike_showstream']);
		$instance['fblike_showheader'] = strip_tags($new_instance['fblike_showheader']);
		
		$instance['gplus_pageid'] = strip_tags($new_instance['gplus_pageid']);
		$instance['gplus_badgetype'] = strip_tags($new_instance['gplus_badgetype']);
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance){
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '', 'widget_type' => 'facebook-like', 'fblike_url' => '',
			'fblike_color' => 'light', 'fblike_border' => '', 'fblike_size' => '300,290',
			'fblike_showfaces' => 1, 'fblike_showstream' => 1, 'fblike_showheader' => 1,
			'gplus_pageid' => '', 'gplus_badgetype' => 'badge'
		));
		
		$title = $instance['title'];
		$widget_type = $instance['widget_type'];
		
		$fblike_url = $instance['fblike_url'];
		$fblike_color = $instance['fblike_color'];
		$fblike_border = $instance['fblike_border'];
		$fblike_size = $instance['fblike_size'];
		$fblike_showfaces = $instance['fblike_showfaces'];
		$fblike_showstream = $instance['fblike_showstream'];
		$fblike_showheader = $instance['fblike_showheader'];
		
		$gplus_pageid = $instance['gplus_pageid'];
		$gplus_badgetype = $instance['gplus_badgetype'];
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
		<h4>Select the type of Widget</h4>
		<select id="<?php echo $this->get_field_id('widget_type'); ?>" class="widefat" name="<?php echo $this->get_field_name('widget_type'); ?>" onChange="wpsr_widgettype(this);">
		  <option <?php echo $widget_type == 'facebook-like' ? ' selected="selected"' : ''; ?> value="facebook-like">Facebook Like Box</option>
		  <option <?php echo $widget_type == 'googleplus-badge' ? ' selected="selected"' : ''; ?> value="googleplus-badge">Google+ Badge</option>
		</select>
		
		<div class="wpsr_settings wpsr_widget_facebook-like" <?php echo ($widget_type != 'facebook-like') ? 'style="display:none"' : '' ; ?>>
			<h4>Settings</h4>
			<table width="100%" height="266" border="0">
			  <tr>
				<td height="34">Facebook Page URL</td>
				<td><input class="widefat" id="<?php echo $this->get_field_id('fblike_url'); ?>" name="<?php echo $this->get_field_name('fblike_url'); ?>" type="text" value="<?php echo $fblike_url; ?>" /></td>
			  </tr>
			  <tr>
				<td height="37">Width, Height</td>
				<td><input class="widefat" id="<?php echo $this->get_field_id('fblike_size'); ?>" name="<?php echo $this->get_field_name('fblike_size'); ?>" type="text" value="<?php echo $fblike_size; ?>" /></td>
			  </tr>
			  <tr>
				<td height="37">Color Scheme</td>
				<td><select id="<?php echo $this->get_field_id('fblike_color'); ?>" class="widefat" name="<?php echo $this->get_field_name('fblike_color'); ?>">
		  <option <?php echo $fblike_color == 'light' ? ' selected="selected"' : ''; ?> value="light">Light</option>
		  <option <?php echo $fblike_color == 'dark' ? ' selected="selected"' : ''; ?> value="dark">Dark</option>
		</select></td>
			  </tr>
			  <tr>
				<td height="34">Border Color</td>
				<td><input class="widefat" id="<?php echo $this->get_field_id('fblike_border'); ?>" name="<?php echo $this->get_field_name('fblike_border'); ?>" type="text" value="<?php echo $fblike_border; ?>" /></td>
			  </tr>
			  <tr>
				<td height="35">Show Faces</td>
				<td><input id="<?php echo $this->get_field_id('fblike_showfaces'); ?>" type="checkbox" name="<?php echo $this->get_field_name('fblike_showfaces'); ?>" value="true" <?php echo $fblike_showfaces == "true" ? 'checked="checked"' : ""; ?>/></td>
			  </tr>
			  <tr>
				<td height="37">Show Stream</td>
				<td><input id="<?php echo $this->get_field_id('fblike_showstream'); ?>" type="checkbox" name="<?php echo $this->get_field_name('fblike_showstream'); ?>" value="true" <?php echo $fblike_showstream == "true" ? 'checked="checked"' : ""; ?>/></td>
			  </tr>
			  <tr>
				<td>Show Header</td>
				<td><input id="<?php echo $this->get_field_id('fblike_showheader'); ?>" type="checkbox" name="<?php echo $this->get_field_name('fblike_showheader'); ?>" value="true" <?php echo $fblike_showheader == "true" ? 'checked="checked"' : ""; ?>/></td>
			  </tr>
		  </table>
		</div>
		
		<div class="wpsr_settings wpsr_widget_googleplus-badge" <?php echo ($widget_type != 'googleplus-badge') ? 'style="display:none"' : '' ; ?>>
			<h4>Settings</h4>
			<table width="100%" height="81" border="0">
			  <tr>
				<td height="35">Google+ Page ID</td>
				<td><input class="widefat" id="<?php echo $this->get_field_id('gplus_pageid'); ?>" name="<?php echo $this->get_field_name('gplus_pageid'); ?>" type="text" value="<?php echo $gplus_pageid; ?>" /></td>
			  </tr>
			  <tr>
				<td>Badge type</td>
				<td><select id="<?php echo $this->get_field_id('gplus_badgetype'); ?>" class="widefat" name="<?php echo $this->get_field_name('gplus_badgetype'); ?>">
		  <option <?php echo $gplus_badgetype == 'badge' ? ' selected="selected"' : ''; ?> value="badge">Standard Badge</option>
		  <option <?php echo $gplus_badgetype == 'smallbadge' ? ' selected="selected"' : ''; ?> value="smallbadge">Small Badge</option>
		</select></td>
			  </tr>
		  </table>
		</div>
		
		<div class="wpsr_support"><a href="http://bit.ly/hjadonate" target="_blank">Donate</a> | <a href="http://www.aakashweb.com/wordpress-plugins/wp-socializer/" target="_blank">Support</a> | <a href="http://facebook.com/aakashweb" target="_blank">Like</a><br />
		<small style="opacity: 0.7">For using other buttons in the widget, use the <a href="http://www.aakashweb.com/wordpress-plugins/html-javascript-adder/" target="_blank">HTML Javascript Adder</a> plugin.</small>		</div>
		
		
		<?php 
	}

}

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget("WPSR_Widget");' ) );

function wpsr_widget_admin_scripts(){
	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			
		});
		function wpsr_widgettype(obj){
			jQuery('.wpsr_settings').hide();
			jQuery('.wpsr_widget_' + jQuery(obj).val()).fadeIn();
		}
	</script>
	
	<style type="text/css">
		.wpsr_settings h4{
			border-bottom: 1px solid #DFDFDF;
			margin: 20px -11px 10px -11px;
			padding: 5px 11px 5px 11px;
			border-top: 1px solid #DFDFDF;
			background-color: #fff;
			background-image: -ms-linear-gradient(top,#fff,#f9f9f9);
			background-image: -moz-linear-gradient(top,#fff,#f9f9f9);
			background-image: -o-linear-gradient(top,#fff,#f9f9f9);
			background-image: -webkit-gradient(linear,left top,left bottom,from(#fff),to(#f9f9f9));
			background-image: -webkit-linear-gradient(top,#fff,#f9f9f9);
			background-image: linear-gradient(top,#fff,#f9f9f9);
		}
		.wpsr_support{
			border: 1px solid #DFDFDF;
			padding: 5px 13px;
			background: #F9F9F9;
			text-decoration:none;
			margin: 10px -13px;
		}
		.wpsr_support a{
			text-decoration: none;
		}
		.wpsr_support a:hover{
			text-decoration: underline;
		}
	</style>
	<?php
}
add_action('sidebar_admin_page', 'wpsr_widget_admin_scripts');
?>