<script>
var phpcfp_snippetSavesuccess = '<?php _e("Code Snippet Updated")?>';
var phpcfp_snippetSaveFailed = '<?php _e("Could not update snippet, did anything actually change?")?>';
var phpcfp_snippetClosePrompt = '<?php _e("Are you sure you want to go back? unsaved changes will be lost!")?>';
</script>

<div class='wrap'>

	<h2>
		<img src='<?php echo PhpCodeForPosts::web_path_it( PhpCodeForPosts_Menu::MENU_ICON_URL )?>' alt='' class='left-align' />
		<?php _e( PhpCodeForPosts_Menu::MENU_PAGE_TITLE )?>
	</h2>

	<?php
		if (PhpCodeForPosts_Messages::has_error_messages()) {
			PhpCodeForPosts_Messages::display_error_messages();
		} elseif (PhpCodeForPosts_Messages::has_success_messages()) {
			PhpCodeForPosts_Messages::display_success_messages();
		}
	?>
	<div id='phpcfp-notificationbox'></div>

	<ul class='phpcfp-menu'>
		<li class='phpcfp-menuitem'>
			<a href='?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG;?>' class='snippetclosebtn'><?php _e( 'Close', "phpcodeforposts" )?></a>
		</li>
		<li class='phpcfp-menuitem donate'>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<h3><?php _e( 'Support The Plugin', "phpcodeforposts" )?></h3>
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="SFQZ3KDJ4LQBA">
				<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
			</form>
		</li>
	</ul>




