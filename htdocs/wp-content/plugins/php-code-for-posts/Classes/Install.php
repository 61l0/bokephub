<?php
class PhpCodeforPosts_Install {

	const TABLEVERSION = 4;

	private static function get_database()
	{
		return PhpCodeForPosts_Database::get_db();
	}

	private static function get_database_table_name()
	{
		return PhpCodeForPosts_Database::get_full_table_name();
	}

	private static function get_options()
	{
		return PhpCodeForPosts::$options;
	}

	public static function check_plugin_table_exists()
	{
		$db = self::get_database();
		self::check_table();
		$result = $db->get_results( "SHOW TABLES LIKE '" . self::get_database_table_name() . "'" );
		return count( $result );
	}

	public static function complete_table_upgrade()
	{
		$options = self::get_options();
		$options->set_option( 'table_version', self::TABLEVERSION );
		$options->save_options();
	}

	public static function upgrade_table()
	{
		$db = self::get_database();
		$table = "CREATE TABLE " . self::get_database_table_name() ." (
			id int(10) NOT NULL AUTO_INCREMENT,
			name varchar(256) NOT NULL DEFAULT 'Untitled Function',
			description text,
			code longtext NOT NULL,
			shared tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
			PRIMARY KEY(id),
			INDEX (shared)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $table );
		self::complete_table_upgrade();
	}

	public static function check_table()
	{
		$options = self::get_options();
		if( $options->get_option( 'table_version' ) !== self::TABLEVERSION ){
			self::upgrade_table();
		}
	}

	public static function uninstall_hook()
	{
		$options = self::get_options();
		if( $options->get_option( 'complete_deactivation' ) == '1' ) {
			delete_option( PhpCodeForPosts_Options::OPTION_NAME );
			$db = self::get_database();
			$db->query( 'DROP TABLE ' . self::get_database_table_name() );
		}
	}

	public static function activation_hook()
	{
		self::check_table();
	}
}
