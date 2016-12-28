<?php
class PhpCodeForPosts_Snippet {

	protected $id;
	protected $name;
	protected $description;
	protected $code = "<?php \n//CODE HERE\n?>";
	protected $shared;

	static $last_saved_snippet = '';

	public static function create_from_database_object( StdClass $object )
	{
		$snippet = new PhpCodeForPosts_Snippet();
		$snippet
			->set_id( $object->id )
			->set_name( $object->name )
			->set_description( $object->description )
			->set_code( self::unhash_code( $object->code ) )
			->set_shared( $object->shared )
		;
		return $snippet;
	}

	public function set_id( $id )
	{
		$this->id = intval( $id );
		return $this;
	}

	public function get_id()
	{
		return (int) $this->id;
	}

	public function set_name( $name )
	{
		$this->name = $name;
		return $this;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_shared( $shared )
	{
		$this->shared = $shared;
		return $this;
	}

	public function get_shared()
	{
		return $this->shared;
	}

	public function set_description( $description )
	{
		$this->description = $description;
		return $this;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_code( $code )
	{
		$this->code = $code;
		return $this;
	}

	public function get_code()
	{
		return $this->code;
	}

	static function hash_code( $code )
	{
		$code = base64_encode( stripslashes( $code ) );
		return $code;
	}

	static function unhash_code( $code )
	{
		$code = base64_decode( $code );
		return $code;
	}

	public function get_table_delete_checkbox()
	{
		return sprintf(
			'<input type="checkbox" name="%s[delete][%s]" value="1">',
			PhpCodeForPosts::POSTFIELD_PREFIX,
			$this->get_id()
		);
	}

	public function get_snippet_edit_link()
	{
		return sprintf(
			'?page=%s&amp;action=edit&amp;item=%s&amp;actioncode=%s',
			PhpCodeForPosts_Menu::MENU_SLUG,
			$this->get_id(),
			PhpCodeForPosts::ready_nonce( $this->get_id(), 'edit' )
		);
	}

	public function get_snippet_trash_link()
	{
		return sprintf(
			'?page=%s&amp;action=delete&amp;item=%s&amp;actioncode=%s',
			PhpCodeForPosts_Menu::MENU_SLUG,
			$this->get_id(),
			PhpCodeForPosts::ready_nonce( $this->get_id(), 'delete' )
		);
	}

	public function get_snippet_ajax_trash_data()
	{
		return sprintf(
			'action=%1$s&%2$s[action]=delete&%2$s[actioncode]=%3$s&%2$s[item]=%4$d&nopost=1',
			PhpCodeforPosts::AJAXHOOK,
			PhpCodeForPosts::POSTFIELD_PREFIX,
			PhpCodeForPosts::ready_nonce( $this->get_id(), 'delete' ),
			$this->get_id()
		);
	}

	public static function get_new_snippet_link()
	{
		return sprintf(
			'?page=%s&amp;action=edit&amp;item=0&amp;actioncode=%s',
			PhpCodeForPosts_Menu::MENU_SLUG,
			PhpCodeForPosts::ready_nonce( 0, 'edit' )
		);
	}

	public function get_delete_warning_message()
	{
		return
			__( 'Wait, if you delete this item you cannot get it back', "phpcodeforposts" ) . "\r\n"
			. __( 'Do you wish to continue?', "phpcodeforposts" );
	}

	public function get_display_shortcode()
	{
		return sprintf( '[%s snippet=%s]', PhpCodeForPosts_Shortcode::get_shortcode(), $this->get_id() );
	}

	public function get_multisite_display_shortcode()
	{
		return sprintf( '[%s snippet=%s]', PhpCodeForPosts_Shortcode::get_multisite_shortcode(), $this->get_id() );
	}

	public static function save_posted_snippet( $post_fields )
	{
		$snippet_id = isset( $post_fields['item'] ) ? $post_fields['item'] : 0;
		$snippet = $snippet_id > 0 ? PhpCodeForPosts_Database::load_single_snippet( $snippet_id ) : new PhpCodeForPosts_Snippet();

		$snippet->set_name( stripslashes($post_fields['name']) );
		$snippet->set_code( $post_fields['code'] );
		$snippet->set_description( stripcslashes($post_fields['description']) );
		$snippet->set_shared( intval($post_fields['shared']) );

		PhpCodeForPosts_Snippet::$last_saved_snippet = $snippet;

		return PhpCodeForPosts_Database::save_snippet( $snippet );
	}

	public function get_array_for_db()
	{
		return array(
		    'id' => $this->get_id(),
			'name' => $this->get_name(),
			'description' => $this->get_description(),
			'code' => PhpCodeForPosts_Snippet::hash_code( $this->get_code() ),
			'shared' => $this->get_shared()
		);
	}

	public function get_array_format_for_db()
	{
		return array(
		    '%d',
			'%s',
			'%s',
			'%s',
			'%d'
		);
	}

	public function get_where_for_update()
	{
		return array(
			'id' => $this->get_id()
		);
	}

	public function get_where_format_for_update()
	{
		return array(
			'%d'
		);
	}

	public function is_loaded()
	{
		return $this->get_id() > 0;
	}
}
