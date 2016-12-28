<tr class="snippet-id-<?php echo $snippet->get_id()?>">
	<th class="check-column" scope="row">
		<?php echo $snippet->get_table_delete_checkbox(); ?>
	</th>
	<td valign="middle"><?php echo esc_html( $snippet->get_id() ); ?></td>
	<td valign="middle">
		<?php echo esc_html( $snippet->get_name() ); ?>
		<div class="row-actions">
			<span class="edit">
				<a href="<?php echo $snippet->get_snippet_edit_link()?>"><?php _e( 'Edit', "phpcodeforposts" )?></a>
			</span> |
			<span class="trash">
				<a href="<?php echo $snippet->get_snippet_trash_link()?>"
					class="phppc-ajaxible"
					data-ajax='<?php echo $snippet->get_snippet_ajax_trash_data();?>'
					data-confirm="<?php echo esc_attr( $snippet->get_delete_warning_message() );?>"
					data-action="remove"
					data-remove="tr.snippet-id-<?php echo $snippet->get_id()?>"><?php _e( 'Delete', "phpcodeforposts" ) ?></a>
			</span>
		</div>
	</td>
	<td valign="middle"><?php echo esc_html( $snippet->get_description() ) ?></td>
	<td valign="middle"><code><?php echo $snippet->get_display_shortcode() ?></code></td>
	<?php if ( is_multisite() && ( PhpCodeForPosts::$options->get_blog_id() == 1 || PhpCodeForPosts::$options->get_multisite_option('crosssite_snippets') ) ) { ?>
		<td valign="middle"><input type="checkbox" <?php checked(1, $snippet->get_shared()) ?> disabled="disabled" /></td>
	<?php } ?>
</tr>
