<tr class="snippet-id-<?php echo $snippet->get_id()?>">
	<td valign="middle"><?php echo esc_html( $snippet->get_id() ); ?></td>
	<td valign="middle"><?php echo esc_html( $snippet->get_name() ); ?></td>
	<td valign="middle"><?php echo esc_html( $snippet->get_description() ) ?></td>
	<td valign="middle"><code><?php echo $snippet->get_multisite_display_shortcode() ?></code></td>
</tr>
