<div class="sg-wp-editor-container">
<?php
	if(POPUP_BUILDER_PKG == POPUP_BUILDER_PKG_FREE) {
		echo SGFunctions::noticeForShortcode();
	}

	$content = @$sgPopupDataHtml;
	$editorId = 'sg_popup_html';
	$settings = array(
		'wpautop' => false,
		'tinymce' => array(
			'width' => '100%',
		),
		'textarea_rows' => '6',
		'media_buttons' => true
	);
	wp_editor($content, $editorId, $settings);
?>
</div>