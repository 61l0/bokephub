<div class='wrap'>

	<h2>
		<img src='<?php echo PhpCodeForPosts::web_path_it( PhpCodeForPosts_Menu::MENU_ICON_URL )?>' alt='' class='left-align' />
		<?php _e( PhpCodeForPosts_Menu::MENU_PAGE_TITLE )?>
	</h2>

	<div id='phpcfp-notificationbox'></div>

	<ul class='phpcfp-menu'>
		<li class='phpcfp-menuitem'>
			<a href='?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG;?>'><?php _e( 'Back', "phpcodeforposts" )?></a>
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




