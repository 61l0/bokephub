<div class='wrap'>

	<h2>
		<img src='<?php echo PhpCodeForPosts::web_path_it( PhpCodeForPosts_Menu::MENU_ICON_URL )?>' alt='' class='left-align' />
		<?php _e( PhpCodeForPosts_Menu::MENU_PAGE_TITLE )?>
	</h2>

	<?php
		if ( is_multisite() && PhpCodeForPosts::$options->get_option( 'multisite_setup' ) == 0 && PhpCodeForPosts::$options->get_blog_id() == 1) {
	?>
		<div class="phpcfp-multisite-setup">
			<p class='ttl'><strong><?php _e( 'Multisite Setup', "phpcodeforposts" );?></strong></p>
			<p><?php _e( 'It looks like you are on a multisite setup, please read the following information.', "phpcodeforposts" ); ?></p>
			<p><?php _e( 'A new option has been added below to allow you to toggle the ability for sub sites to save code snippets.', "phpcodeforposts"); ?></p>
			<p><?php _e( 'Whilst enabled, sub sites will be able to create code snippets which can lead to potential security vulnerabilities.', "phpcodeforposts"); ?></p>
			<p><?php _e( 'Only enable this setting if you completely trust your sub site admins.', "phpcodeforposts"); ?></p>
			<p><?php _e( 'If this setting is disabled, sub sites will still be able to use snippets you choose to share.', "phpcodeforposts"); ?></p>
			<p><?php _e( 'Sites can also utilise shared shortcodes from other sub sites by passing a new blog_id parameter on the snippet', "phpcodeforposts"); ?></p>
			<p><?php _e( 'For further information on the multisite setup, please see the readme file', "phpcodeforposts"); ?></p>
			<p><a href='?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG?>&amp;action=dismissmultisitemessage' class="button-primary"><?php _e( 'Hide this message', "phpcodeforposts" ); ?></a></p>
		</div>
	<?php } ?>

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
			<a href='#!' data-tab='snippets' class='tabchange activetab-head'><?php _e( 'Snippets', "phpcodeforposts" )?></a>
		</li>
		<?php if ( PhpCodeForPosts::$options->option_modifications_allowed() ) { ?>
		<li class='phpcfp-menuitem'>
			<a href='#!' data-tab='options' class='tabchange'><?php _e( 'Options', "phpcodeforposts" )?></a>
		</li>
		<?php } ?>
		<?php if ( PhpCodeForPosts::$options->snippet_modifications_allowed() ) { ?>
		<li class='phpcfp-menuitem new-snippet'>
			<a href='<?php echo PhpCodeForPosts_Snippet::get_new_snippet_link()?>'><?php _e( 'New Snippet', "phpcodeforposts" )?></a>
		</li>
		<?php } ?>
		<li class='phpcfp-menuitem other'>
			<a href='#!' data-tab='other' class='tabchange'><?php _e( 'Other', "phpcodeforposts" )?></a>
		</li>
		<li class='phpcfp-menuitem export'>
			<a href='#!' data-tab='export' class='tabchange'><?php _e( 'Export', "phpcodeforposts" )?></a>
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




