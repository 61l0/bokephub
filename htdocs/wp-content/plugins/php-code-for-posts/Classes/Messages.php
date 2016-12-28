<?php
class PhpCodeForPosts_Messages {

	const SESSION_KEY = 'phpcodeforposts_messages';
	const TYPE_SUCCESS = 'success';
	const TYPE_ERROR = 'error';



	/**
	 * SUCCESS MESSAGES
	**/

	public static function add_success_message( $msg )
	{
		self::add_message( self::TYPE_SUCCESS, $msg );
	}

	public static function has_success_messages()
	{
		return self::has_message_of_type( self::TYPE_SUCCESS );
	}

	public static function return_success_messages()
	{
		return self::return_messages_of_type( self::TYPE_SUCCESS );
	}

	public static function display_success_messages()
	{
		if(! self::has_success_messages() ) {
			return;
		}

		self::display_messages( self::TYPE_SUCCESS, self::return_success_messages() );
	}




	/**
	 * ERROR MESSAGES
	**/
	public static function add_error_message( $msg )
	{
		self::add_message( self::TYPE_ERROR, $msg );
	}

	public static function has_error_messages()
	{
		return self::has_message_of_type( self::TYPE_ERROR );
	}

	public static function return_error_messages()
	{
		return self::return_messages_of_type( self::TYPE_ERROR );
	}

	public static function display_error_messages()
	{
		if(! self::has_error_messages() ) {
			return;
		}

		self::display_messages( self::TYPE_ERROR, self::return_error_messages() );
	}



	/**
	 * PRIVATE FUNCTIONS
	**/
	private static function get_default_structure()
	{
		return array(
			self::TYPE_SUCCESS 	=> array(),
			self::TYPE_ERROR	=> array(),
		);
	}

	private static function add_message( $type, $msg )
	{
		if(! isset( $_SESSION[self::SESSION_KEY] ) ) {
			$_SESSION[self::SESSION_KEY] = self::get_default_structure();
		}

		if(! isset( $_SESSION[self::SESSION_KEY] [$type] ) ) {
			throw new Exception( __( 'Invalid Message Type', "phpcodeforposts" ) );
		}

		$_SESSION[self::SESSION_KEY] [$type] [] = $msg;
	}

	private static function has_message_of_type( $type )
	{
		if(! isset( $_SESSION[self::SESSION_KEY] ) ) {
			return false;
		}
		return count( $_SESSION[self::SESSION_KEY] [$type] ) > 0;
	}

	private static function return_messages_of_type( $type )
	{
		if (
			!isset( $_SESSION[self::SESSION_KEY] )
			||
			!isset( $_SESSION[self::SESSION_KEY] [$type] )
		) {
			return array();
		}

		$messages = $_SESSION[self::SESSION_KEY] [$type];
		$_SESSION[self::SESSION_KEY] [$type] = array();
		return $messages;
	}

	private static function display_messages( $type, Array $messages )
	{
		if( count( $messages ) ) {
			foreach( $messages as $message ) {
				printf(
					'<div class="%s" id="setting-error-setting_updated"><p>%s</p></div>',
					$type == self::TYPE_SUCCESS ? 'updated' : 'error',
					$message
				);
			}
		}
	}
}
