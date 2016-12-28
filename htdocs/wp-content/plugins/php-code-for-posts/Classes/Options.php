<?php
class PhpCodeForPosts_Options {

	const OPTION_NAME = 'phppc_menu';

	protected $options;
	protected $multisite_options;
	public $blog_details;

	public function __construct()
	{
		$this->load_options();
		if (is_multisite()) {
			$this->load_multisite_options();
		}
	}

	public function get_blog_details()
	{
		if (is_null($this->blog_details)) {
			$this->blog_details = get_blog_details();
		}
		return $this->blog_details;
	}

	public function get_blog_id()
	{
		$this->get_blog_details();
		return $this->blog_details->blog_id;
	}

	public function snippet_modifications_allowed()
	{
		return (
			!is_multisite() ||
			$this->get_blog_id() == 1 ||
			$this->get_multisite_option('multisite_snippets') == 1
		);
	}

	public function option_modifications_allowed()
	{
		return (
			!is_multisite() ||
			$this->get_blog_id() == 1 ||
			$this->get_multisite_option('multisite_own_options')
		);
	}

	private function get_defaults()
	{
		return array(
			'complete_deactivation' 	=> 0,
			'content_filter' 			=> 1,
			'enable_richeditor' 		=> 1,
			'shortcode'					=> PhpCodeForPosts_Shortcode::DEFAULT_SHORTCODE,
			'sidebar_filter' 			=> 1,
			'table_version' 			=> 0,
			'ajaxible'					=> 1,
			'parameter_extraction'		=> 0,
			'multisite_setup'			=> 0,
			'multisite_snippets' 		=> 0,
			'crosssite_snippets' 		=> 0,
			'multisite_shortcode' 		=> PhpCodeForPosts_Shortcode::DEFAULT_MULTISITE_SHORTCODE,
			'multisite_own_options'		=> 0,
		);
	}

	private function get_default( $option_name )
	{
		$options = $this->get_defaults();
		return isset( $options[$option_name] ) ? $options[ $option_name ] : false;
	}

	private function load_options()
	{
		$this->options = get_option( self::OPTION_NAME, $this->get_defaults() );
	}

	private function load_multisite_options()
	{
		$this->multisite_options = get_blog_option( 1, self::OPTION_NAME, $this->get_defaults() );
	}

	public function save_options()
	{
		return update_option( self::OPTION_NAME, $this->options );
	}

	public function get_option( $option_name )
	{
		if (! $this->option_modifications_allowed() && $option_name != 'table_version' ) {
			return $this->get_multisite_option( $option_name );
		}

		if (! $this->has_option( $option_name ) ) {
			return $this->get_default( $option_name ) ;
		}

		return $this->options[$option_name];
	}

	public function get_multisite_option( $option_name )
	{
		if (! $this->multisite_has_option( $option_name ) ) {
			return $this->get_default( $option_name ) ;
		}

		return $this->multisite_options[$option_name];
	}

	public function set_option( $option_name, $option_value )
	{
		$this->options[$option_name] = $option_value;
	}

	public function has_option( $option_name )
	{
		return isset( $this->options[$option_name] );
	}

	public function multisite_has_option( $option_name )
	{
		return isset( $this->multisite_options[$option_name] );
	}

	public function save_posted_options( $post_fields )
	{
		if (! $this->option_modifications_allowed()) {
			return false;
		}
		foreach( $this->get_defaults() as $key => $value ) {
			if( isset( $post_fields[$key] ) ) {
				$this->options[$key] = $post_fields[$key];
			}
		}

		if ($this->options['shortcode'] == $this->options['multisite_shortcode']) {
			$this->options['multisite_shortcode'] = $this->get_default( 'multisite_shortcode' );
		}

		if ($this->options['shortcode'] == $this->options['multisite_shortcode']) {
			$this->options['shortcode'] = $this->get_default( 'shortcode' );
		}
		return $this->save_options();
	}
}
