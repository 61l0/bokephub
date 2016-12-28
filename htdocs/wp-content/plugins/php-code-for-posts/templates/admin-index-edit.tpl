<div class='tabs phpcfp-tabs'>
	<div class='tab tab-snippets activetab'>
		<h3><?php
		if( $snippet->get_id() == 0 ){
			_e( 'Edit Snippet', "phpcodeforposts" );
		} else {
			_e( 'Add Snippet', "phpcodeforposts" );
		}
		?></h3>
		<form action="?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG?>" method="post" id="codeform" <?php if ($snippet->get_id() && PhpCodeforPosts::$options->get_option('ajaxible') == 1) { echo 'class="ajaxible"'; }?>>
			<?php
				echo PhpCodeForPosts::get_hidden_field( 'action', 'save');
				echo PhpCodeForPosts::get_hidden_field( 'item', esc_attr( $snippet->get_id() ) );
				echo PhpCodeForPosts::get_hidden_field( 'actioncode', PhpCodeForPosts::ready_nonce( $snippet->get_id(), 'save' ) );
			?>
			<label for="<?php echo PhpCodeForPosts::__input_id( 'name' ) ?>"><strong><?php _e('Name your code', "phpcodeforposts" ) ?></strong></label>
			<input class="widefat" id="<?php echo PhpCodeForPosts::__input_id( 'name' ) ?>" maxlength="256" name="<?php echo PhpCodeForPosts::__input_name( 'name' ) ?>" placeholder="<?php _e( 'Because everything deserves a name', "phpcodeforposts" ) ?>" type="text" value="<?php echo esc_attr( $snippet->get_name() ) ?>" />
			<br />
			&nbsp;
			<br />
			<label for="<?php echo PhpCodeForPosts::__input_id( 'description' ) ?>"><strong><?php _e( 'Describe your code', "phpcodeforposts" ) ?></strong></label>
			<textarea class="widefat" id="<?php echo PhpCodeForPosts::__input_id( 'description' ) ?>" name="<?php echo PhpCodeForPosts::__input_name( 'description' ) ?>" placeholder="<?php _e( 'A description will help you remember what the code is for', "phpcodeforposts" ) ?>"><?php echo esc_textarea( $snippet->get_description() )?></textarea>
			<br />
			&nbsp;
			<br />
			<label for="code"><strong><?php _e( 'Write your code', "phpcodeforposts" ) ?></strong> <br /><em><?php _e( 'Remember to open your PHP code with', "phpcodeforposts" ) ?> <code>&lt;?php</code></em></label>
			<textarea class="widefat" id="code" name="<?php echo PhpCodeForPosts::__input_name( 'code' ) ?>" placeholder="&lt;php \n//CODE HERE\n?&lt;"><?php echo esc_textarea( $snippet->get_code() )?></textarea>
			<br />
			&nbsp;
			<br />
			<?php if (is_multisite() && (PhpCodeForPosts::$options->get_blog_id() == 1 || PhpCodeForPosts::$options->get_multisite_option( 'crosssite_snippets' ) ) ) { ?>
				<label for="<?php echo PhpCodeForPosts::__input_id( 'shared' ) ?>"><strong><?php _e( 'Share this snippet for all sites to use', "phpcodeforposts") ?></strong>
					<input type="checkbox" id="<?php echo PhpCodeForPosts::__input_id( 'shared' ) ?>" name="<?php echo PhpCodeForPosts::__input_name( 'shared' ) ?>" value="1" <?php checked(1, $snippet->get_shared()) ?> />
				<br />
				&nbsp;
				<br />
			<?php } ?>
			<input class="button-primary" data-failed="<?php esc_attr_e( 'Save Failed', "phpcodeforposts" ) ?>" data-updated="<?php esc_attr_e( 'Saved', "phpcodeforposts" ) ?>" data-updating="<?php esc_attr_e( 'Saving', "phpcodeforposts" ) ?>" id="updatebtn" type="submit" value="<?php esc_attr_e( 'Save Code Snippet', "phpcodeforposts" ) ?>" />
			<a class="button-secondary snippetclosebtn alignright" href="?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG ?>"><?php _e( 'Close', "phpcodeforposts" ) ?></a>

		</form>
	</div>
</div>
<?php
if( PhpCodeForPosts::$options->get_option( 'enable_richeditor' ) ) {
?>
<script>var editor = CodeMirror.fromTextArea(document.getElementById("code"), {lineNumbers: true,matchBrackets: true,mode: "application/x-httpd-php",indentUnit: 4,indentWithTabs: true,enterMode: "keep",tabMode: "shift"});</script>
<?php
}
?>
