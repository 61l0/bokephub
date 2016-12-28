<?php
/*
 * Admin Page for WP Socializer Plugin - In widgets and posts
 * Author : Aakash Chakravarthy
*/

function wpsr_check_hja(){
	if (class_exists('html_javascript_adder_widget') && HJA_VERSION >= '3.2'){
		return 'installed';
	}elseif(HJA_VERSION < '3.2'){
		return 'upgradable';
	}else{
		return 'not-installed';
	}
}

function wpsr_admin_page_other(){
	global $wpsr_shortcodes_list;
	
?>

<div class="wrap">
	<h2><img width="32" height="32" src="<?php echo WPSR_ADMIN_URL; ?>images/wp-socializer.png" align="absmiddle"/> WP Socializer - Shortcodes</h2>
	
	<div class="miniWrap">
	
		<div class="message updated"><p><strong><?php _e('Note:', 'wpsr'); ?></strong> <?php _e('Inorder to use the buttons in <em>Widgets</em> and <em>Posts</em>, you need to use the below shortcodes.', 'wpsr'); ?></p>
		</div>
		
		<h2>Available Shortcodes</h2><br />
		
		<table class="widefat scTable">
			<thead>
				<tr>
					<th>Button</th>
					<th>Shortcode</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Button</th>
					<th>Shortcode</th>
				</tr>
			</tfoot>
			<tbody>
			   <?php 
					foreach($wpsr_shortcodes_list as $name => $shortcode){
						echo "<tr><td>$name</td><td>$shortcode</td></tr>";
					}
				?>
			</tbody>
		</table>
		
		<small class="smallText"><?php _e('These shortcodes have several parameters. You can use them to customize the buttons. View the parameters in this page.', 'wpsr'); ?> 
		<a href="http://www.aakashweb.com/docs/wp-socializer-docs/function-reference/" target="_blank">Refer the Parameters here.</a></small>
		
		<br />
		
		<h2>In widgets</h2>
		<p><?php _e('You must use the above shortcodes in', 'wpsr'); ?> <a href="http://www.aakashweb.com/wordpress-plugins/html-javascript-adder/" target="_blank">HTML Javascript adder</a> <a href="#note" onclick="javascript:$j('.hjaNote').addClass('updated');">(?)</a> <?php _e('plugin to insert the buttons in the widgets.', 'wpsr'); ?></p>

		<p><center>
			<?php
				$nonce = wp_create_nonce('wpsr-nonce');
				if (wpsr_check_hja() == 'installed'){
					echo '<em>' . __('HTML Javascript Adder is installed, Go to Widgets page and use the shortcodes in this widget' ,'wpsr') . '</em>';
				}elseif(wpsr_check_hja() == 'upgradable'){
					$updateUrl = 'update.php?action=upgrade-plugin&plugin=html-javascript-adder%2Fhtml-javascript-adder.php&_wpnonce=' . $nonce;
					echo "<a class='button-primary' href='$updateUrl'>" . __('Upgrade HTML Javascript Adder plugin' ,'wpsr') . "</a><br>";
					echo "<br><span class='smallText error'>" . __('The current version (' ,'wpsr') . HJA_VERSION . __(') is not compatible with WP Socializer. It is recommended to upgrade' ,'wpsr') . "</span>";
				}elseif(wpsr_check_hja() == 'not-installed'){
					$installUrl = 'update.php?action=install-plugin&plugin=html-javascript-adder&_wpnonce=' . $nonce;
					echo "<a class='button-primary' href='$installUrl'>" . __('Install HTML Javascript Adder plugin' ,'wpsr') . "</a>";
				}
			?>
		</center></p>
		
		<br />
		
		<h2><?php _e('Inside Posts', 'wpsr'); ?></h2>
		<p><?php _e('Inorder to use the buttons inside posts, you must use the above shortcodes in the post editor.', 'wpsr'); ?></p>
		
		<hr />
		
		<p class="smallText hjaNote"><strong><?php _e('Note:', 'wpsr'); ?></strong> <?php _e('HTML Javascript Adder is a wordpress plugin for inserting HTML, Javascripts in widgets with more than 60,000 downloads. This is from the same author of this plugin. The latest version of HJA can insert WP Socializer buttons in widgets.', 'wpsr'); ?></p>
		<a name="note" id="note"></a>
	</div>
	
</div>
<?php
}
?>