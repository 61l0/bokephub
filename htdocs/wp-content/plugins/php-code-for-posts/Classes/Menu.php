<?php
class PhpCodeForPosts_Menu {

	const MENU_PAGE_TITLE = 'PHP Code For Posts';
	const MENU_TITLE = 'PHP Code';
	const MENU_CAPABILITY = 'manage_options';
	const MENU_SLUG = 'phppc_menu';
	const MENU_ICON_URL = 'php-icon.png';

	public function __construct()
	{
		if (!is_admin())
			return;

		add_action( 'admin_menu' , 			array( __CLASS__ , 'add_menu_page'	 	) );
		add_action( 'wp_enqueue_scripts', 	array( __CLASS__ , 'add_menu_scripts'	) );
		add_action( 'wp_enqueue_styles',	array( __CLASS__ , 'add_menu_styles' 	) );
	}

	public static function add_menu_page()
	{
		$menu_page = add_menu_page(
			__(self::MENU_PAGE_TITLE, "phpcodeforposts" ),
			__(self::MENU_TITLE, "phpcodeforposts" ),
			self::MENU_CAPABILITY,
			self::MENU_SLUG,
			array( __CLASS__, 'show_menu_page' ),
			PhpCodeForPosts::web_path_it( self::MENU_ICON_URL )
		);

		self::add_menu_styles($menu_page);
		self::add_menu_scripts($menu_page);
	}


	public static function add_menu_styles()
	{
		wp_register_style( 'phppc_styles', PhpCodeForPosts::web_path_it( 'style.css' ) );
		wp_enqueue_style( 'phppc_styles' );

		if( PhpCodeForPosts::$options->get_option( 'enable_richeditor' ) ) {
			wp_register_style( 'phppc_codemirror', PhpCodeForPosts::web_path_it( 'Codemirror/lib/codemirror.css' ) );
			wp_enqueue_style( 'phppc_codemirror' );
		}
	}

	public static function add_menu_scripts()
	{
		wp_register_script( 'phppc_script', PhpCodeForPosts::web_path_it( 'PHPPostCode.js' ), array( 'jquery' ) );
		wp_enqueue_script( 'phppc_script' );

		if( PhpCodeForPosts::$options->get_option( 'enable_richeditor' ) ) {
			wp_register_script( 'Codemirror_lang_clike', PhpCodeForPosts::web_path_it( 'Codemirror/lang/clike.js' ) );
			wp_register_script( 'Codemirror_lang_css', PhpCodeForPosts::web_path_it( 'Codemirror/lang/css.js' ) );
			wp_register_script( 'Codemirror_lang_htmlmixed', PhpCodeForPosts::web_path_it( 'Codemirror/lang/htmlmixed.js' ) );
			wp_register_script( 'Codemirror_lang_javascript', PhpCodeForPosts::web_path_it( 'Codemirror/lang/javascript.js' ) );
			wp_register_script( 'Codemirror_lang_php', PhpCodeForPosts::web_path_it( 'Codemirror/lang/php.js' ) );
			wp_register_script( 'Codemirror_lang_xml', PhpCodeForPosts::web_path_it( 'Codemirror/lang/xml.js' ) );
			wp_register_script( 'Codemirror_addon_matchbrackets', PhpCodeForPosts::web_path_it( 'Codemirror/addon/matchbrackets.js' ) );
			wp_register_script( 'Codemirror', PhpCodeForPosts::web_path_it( 'Codemirror/lib/codemirror.js' ) );

			wp_enqueue_script( 'Codemirror' );
			wp_enqueue_script( 'Codemirror_lang_clike' );
			wp_enqueue_script( 'Codemirror_lang_css' );
			wp_enqueue_script( 'Codemirror_lang_htmlmixed' );
			wp_enqueue_script( 'Codemirror_lang_javascript' );
			wp_enqueue_script( 'Codemirror_lang_php' );
			wp_enqueue_script( 'Codemirror_lang_xml' );
			wp_enqueue_script( 'Codemirror_addon_matchbrackets' );
		}
	}

	public static function show_menu_page()
	{
		$action = isset( $_GET['action'] ) ? $_GET['action'] : '';

		switch( $action ) {
			case 'dismissmultisitemessage':
				PhpCodeForPosts::$options->set_option( 'multisite_setup', 1 );
				PhpCodeForPosts::$options->save_options();
			case '':
			case 'delete':
				include PhpCodeForPosts::directory_path_it( 'templates/admin-index-top.tpl' );
				self::show_menu_page_default();
				break;

			case 'edit':
			case 'add':
				if ( PhpCodeForPosts::$options->snippet_modifications_allowed() ) {
					include PhpCodeForPosts::directory_path_it( 'templates/admin-index-top-edit.tpl' );
					self::show_menu_page_edit();
					break;
				}
				self::show_menu_page_notallowed();


		}
		include PhpCodeForPosts::directory_path_it( 'templates/admin-index-bottom.tpl' );
	}

	public static function show_menu_page_default()
	{
		if(! PhpCodeForPosts_Install::check_plugin_table_exists() ) {
			PhpCodeForPosts_Install::upgrade_table();
		}
		include PhpCodeForPosts::directory_path_it( 'templates/admin-index-default.tpl' );
	}

	public static function show_menu_page_edit()
	{
		if(! PhpCodeForPosts::check_nonce( $_REQUEST['actioncode'], $_REQUEST['item'], $_REQUEST['action'] ) ) {
			echo 'An Error Occured';
			return ;
		}
		if ($_REQUEST['item'] == 0) {
			$snippet = new PhpCodeForPosts_Snippet();
		} else {
			$snippet = PhpCodeForPosts_Database::load_single_snippet( $_REQUEST['item'] );
		}
		include PhpCodeForPosts::directory_path_it( 'templates/admin-index-edit.tpl' );
	}

	public static function show_menu_page_notallowed()
	{
		include PhpCodeForPosts::directory_path_it( 'templates/admin-index-top-notallowed.tpl' );
		include PhpCodeForPosts::directory_path_it( 'templates/admin-index-notallowed.tpl' );
	}
}
