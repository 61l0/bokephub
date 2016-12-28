<?php
class PhpCodeForPosts_Shortcode {

	const DEFAULT_SHORTCODE = 'php';
	const DEFAULT_MULTISITE_SHORTCODE = 'phpmu';

	public static function get_shortcode()
	{
		$option_value = PhpCodeForPosts::$options->get_option( 'shortcode' );
		return $option_value == '' ? self::DEFAULT_SHORTCODE : $option_value;
	}

	public static function get_multisite_shortcode()
	{
		$option_value = PhpCodeForPosts::$options->get_option( 'multisite_shortcode' );
		return $option_value == '' ? self::DEFAULT_MULTISITE_SHORTCODE : $option_value;
	}

	public static function handle_shortcode( $attributes )
	{
		if (! PhpCodeForPosts::$options->snippet_modifications_allowed( ) ) {
			return '';
		}

		$arguments = array( 'snippet' => 0, 'param' => '' );
		$attributes = shortcode_atts( $arguments, $attributes );

		$snippet_id = intval( $attributes['snippet'] );
		if( $snippet_id == 0 ) {
			return '';
		}

		$snippet = PhpCodeForPosts_Database::load_single_snippet( $snippet_id );
		if (! $snippet->is_loaded() ) {
			return '';
		}

		return self::do_shortcode( $snippet, $attributes );
	}

	public static function handle_multisite_shortcode( $attributes )
	{
		$arguments = array( 'snippet' => 0, 'param' => '', 'blog_id' => 1 );
		$attributes = shortcode_atts( $arguments, $attributes );

		$snippet_id = intval( $attributes['snippet'] );
		if( $snippet_id == 0 ) {
			return '';
		}

		$blog_id = max( 0, intval( $attributes['blog_id'] ) );

		if (! PhpCodeForPosts::$options->get_multisite_option( 'crosssite_snippets' ) && $blog_id > 1) {
			return '';
		}

		$snippet = PhpCodeForPosts_Database::load_single_snippet( $snippet_id, $blog_id );
		if (! $snippet->is_loaded() ) {
			return '';
		}

		return self::do_shortcode( $snippet, $attributes );
	}

	private static function do_shortcode( $snippet, $attributes )
	{
		$snippet_prefix = '?>';

		if (isset( $attributes['param'] ) && $attributes['param'] != '' ) {
			$snippet_pre_prefix = '$_parameters = array(); parse_str(htmlspecialchars_decode("' . $attributes['param'] . '"), $_parameters);';

			if (PhpCodeForPosts::$options->get_option( 'parameter_extraction' )) {
				$snippet_pre_prefix .= 'extract($_parameters);';
			}

			$snippet_prefix = $snippet_pre_prefix . $snippet_prefix;

		}

		ob_start();
		eval( $snippet_prefix . $snippet->get_code() );
		return ob_get_clean();
	}

	public static function handle_extra_shortcode( $content )
	{
		$shortcode = self::get_shortcode();
		$content = str_ireplace( "[/{$shortcode}]", " ?>", $content );
		$content = str_ireplace( "[{$shortcode}]", "<?php ", $content );
		ob_start();
		eval( "?>" . $content );
		return ob_get_clean();
	}

}
