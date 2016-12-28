<div class='tabs phpcfp-tabs'>
    <div class='tab tab-snippets activetab'>
        <?php if (PhpCodeForPosts::$options->snippet_modifications_allowed()) {?>
        <h3><?php _e( 'Saved Code Snippets', "phpcodeforposts" ) ?></h3>
        <form action='?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG ?>' method='post'>
        <?php echo PhpCodeForPosts::get_hidden_field( 'action', 'bulkdelete' ) ?>
        <?php echo PhpCodeForPosts::get_hidden_field( 'item', '' ) ?>
        <?php wp_nonce_field( 'bulkdelete', PhpCodeForPosts::POSTFIELD_PREFIX . '[actioncode]') ?>
        <table cellpadding='5' cellspacing='0' class='wp-list-table widefat fixed posts striped'>
        <thead>
        <tr>
        <td class='manage-column column-cb check-column' scope='col'><input type='checkbox'></td>
        <th class='manage-column' scope='col'><?php _e( 'ID', "phpcodeforposts" ) ?></th>
        <th class='manage-column' scope='col'><?php _e( 'Name', "phpcodeforposts" ); ?></th>
        <th class='manage-column' scope='col'><?php _e( 'Description', "phpcodeforposts" ); ?></th>
        <th class='manage-column' scope='col'><?php _e( 'Shortcode', "phpcodeforposts" ); ?></th>
        <?php
        if ( is_multisite() && ( PhpCodeForPosts::$options->get_blog_id() == 1 || PhpCodeForPosts::$options->get_multisite_option('crosssite_snippets') ) ) {
        ?>
        <th class='manage-column' scope='col'><?php _e( 'Shared', "phpcodeforposts" ) ?></th>
        <?php
        }
        ?>
        </tr>
        </thead>
        <tbody id='TPSL'
               data-noitems='<tr class="no-items"><td colspan="4" class="colspanchange">No Code Found.</td></tr>'>
        <?php
					$snippets = PhpCodeForPosts_Database::load_all_snippets();

					if( count( $snippets ) > 0 ) {
        foreach( $snippets as $index => $snippet ) {
        include PhpCodeForPosts::directory_path_it( 'templates/admin-snippet-row.tpl' );
        }
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td class='manage-column column-cb check-column' scope='col'><input type='checkbox'></td>
            <th class='manage-column' scope='col'><?php _e( 'ID', "phpcodeforposts" ) ?></th>
            <th class='manage-column' scope='col'><?php _e( 'Name', "phpcodeforposts" ) ?></th>
            <th class='manage-column' scope='col'><?php _e( 'Description', "phpcodeforposts" ) ?></th>
            <th class='manage-column' scope='col'><?php _e( 'Shortcode', "phpcodeforposts" ) ?></th>
            <?php
							if (is_multisite() && ( PhpCodeForPosts::$options->get_blog_id() == 1 ||
            PhpCodeForPosts::$options->get_multisite_option('crosssite_snippets') ) ) {
            ?>
            <th class='manage-column' scope='col'><?php _e( 'Shared', "phpcodeforposts" ) ?></th>
            <?php
							}
						?>
        </tr>
        </tfoot>
        </table>
        <p><input type="submit" class="button-secondary" value="Deleted Selected Snippets"/></p>
        </form>
        <?php } ?>
        <?php if (is_multisite() && PhpCodeForPosts::$options->get_blog_id() > 1) { ?>
        <h3><?php _e( 'Shared Snippets', "phpcodeforposts" ); ?></h3>
        <table cellpadding="5" cellspacing="0" class="wp-list-table widefat fixed posts striped">
            <thead>
            <tr>
                <th class='manage-column' scope='col'><?php _e( 'ID', "phpcodeforposts" ) ?></th>
                <th class='manage-column' scope='col'><?php _e( 'Name', "phpcodeforposts" ); ?></th>
                <th class='manage-column' scope='col'><?php _e( 'Description', "phpcodeforposts" ); ?></th>
                <th class='manage-column' scope='col'><?php _e( 'Shortcode', "phpcodeforposts" ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
						$sharedSnippets = PhpCodeForPosts_Database::load_multisite_shared_snippets();

						if( count( $sharedSnippets ) ) {
							foreach( $sharedSnippets as $index => $snippet ) {
            include PhpCodeForPosts::directory_path_it( 'templates/admin-snippet-shared-row.tpl' );
            }
            } else { ?>
            <tr>
                <td align="center" colspan="4"><?php _e( 'There are no shared snippets', "phpcodeforposts" );?></td>
            </tr>
            <?php }	?>
            </tbody>
            <tfoot>
            <tr>
                <th class='manage-column' scope='col'><?php _e( 'ID', "phpcodeforposts" ) ?></th>
                <th class='manage-column' scope='col'><?php _e( 'Name', "phpcodeforposts" ) ?></th>
                <th class='manage-column' scope='col'><?php _e( 'Description', "phpcodeforposts" ) ?></th>
                <th class='manage-column' scope='col'><?php _e( 'Shortcode', "phpcodeforposts" ) ?></th>
            </tr>
            </tfoot>
        </table>
        <?php } ?>
    </div>
    <?php if (PhpCodeForPosts::$options->option_modifications_allowed()) { ?>
    <div class='tab tab-options'>
        <h3><?php _e('Plugin Options') ?></h3>
        <form action='?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG ?>' method='post'>
            <?php echo PhpCodeForPosts::get_hidden_field( 'action', 'updateoptions' ) ?>
            <?php echo PhpCodeForPosts::get_hidden_field( 'item', '' ) ?>
            <?php wp_nonce_field( 'updateoptions', PhpCodeForPosts::POSTFIELD_PREFIX . '[actioncode]' ) ?>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'complete_deactivation', __('Remove all options and tables on uninstall', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'content_filter', __('Parse inline plugin shortcode tags inside post content (HTML Editor Only)', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'sidebar_filter', __('Parse inline plugin shortcode tags inside sidebar text widgets', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'enable_richeditor', __('Enable Codemirror\'s rich editor for code snippets', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'parameter_extraction', __('Extract shortcode params to their own variables', "phpcodeforposts" ) ); ?>
                *</p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'ajaxible', __('Use Ajax to save snippets', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_input_field( 'shortcode', PhpCodeForPosts::$options->
                get_option('shortcode'), __('Change the shortcode for the plugin (not recommended if you already have
                snippets!)', "phpcodeforposts" ) ); ?></p>
            <div class='clearall'></div>
            <?php if ( is_multisite() && PhpCodeForPosts::$options->get_blog_id() == 1) {?>
            <p><strong><?php _e('Multisite Options', "phpcodeforposts"); ?>*</strong></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'multisite_snippets', __('Allow custom snippets for sub-sites?', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'crosssite_snippets', __('Allow sharing of snippets between sites', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_checkbox_field( 'multisite_own_options', __('Allow per-site options for multisite, e.g. shortcode', "phpcodeforposts" ) ); ?></p>
            <p class='formlabel'><?php echo PhpCodeForPosts::get_input_field( 'multisite_shortcode', PhpCodeForPosts::$options->get_option('multisite_shortcode'), __('Change the multisite shortcode. Do not make this the same as the other snippet!', "phpcodeforposts" ) ); ?></p>
            <?php } ?>

            <p><input type='submit' class='button-primary' value='<?php _e( ' Save Options', "phpcodeforposts" ) ?>' />
            </p>
        </form>
        <p>*<em><?php _e('See the readme for more information on this', "phpcodeforposts");?></em></p>
    </div>
    <?php } ?>

    <div class='tab tab-other'>
        <iframe src='http://www.jamiefraser.co.uk/php-code-for-posts/' width="100%" frameborder="0"
                height="415px"></iframe>
    </div>

    <div class='tab tab-export'>
        <h3><?php _e( 'Import / Export', "phpcodeforposts" );?></h3>
        <p><strong><?php _e( 'Export', "phpcodeforposts" );?></strong></p>
        <form action="?page=<?php echo PhpCodeForPosts_Menu::MENU_SLUG ?>" method="post">
            <?php echo PhpCodeForPosts::get_hidden_field( 'action', 'generate-export' ) ?>
            <?php echo PhpCodeForPosts::get_hidden_field( 'item', '' ) ?>
            <?php wp_nonce_field( 'generate-export', PhpCodeForPosts::POSTFIELD_PREFIX . '[actioncode]' ) ?>
            <input type="submit" value="<?php _e( 'Generate CSV export file', " phpcodeforposts" ) ?>" />
        </form>

        <p><strong style="color:white; background:red">The importer is largely untested, as such, I would not recommend using this on a production site without first taking a backup.</strong></p>
        <p><strong><?php _e( 'Import', "phpcodeforposts" ); ?></strong></p>
        <form action='?page=<?php echo PhpCodeforPosts_Menu::MENU_SLUG ?>' method="post" enctype="multipart/form-data">
            <?php echo PhpCodeForPosts::get_hidden_field( 'action', 'do-import' ) ?>
            <?php echo PhpCodeForPosts::get_hidden_field( 'item', '' ) ?>
            <?php wp_nonce_field( 'do-import', PhpCodeForPosts::POSTFIELD_PREFIX . '[actioncode]' ) ?>
            <label><?php _e("Select CSV File", "phpcodeforposts")?> <input type="file" accept="text/csv" name="csvfile"/></label><br />
            <select name="keep-ids">
                <option value="0" selected="selected"><?php _e("Do NOT maintain IDs (recommended)", "phpcodeforposts")?></option>
                <option value="1"><?php _e("Maintain IDs (may overwrite snippets)", "phpcodeforposts")?></option>
            </select><br />
            <input type="submit" value="<?php _e('Import Snippets', " phpcodeforposts")?>" />
        </form>
    </div>

</div>



