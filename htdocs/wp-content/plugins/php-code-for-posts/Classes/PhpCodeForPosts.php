<?php

class PhpCodeForPosts
{

    const POSTFIELD_PREFIX = 'phpcodeforposts';
    const AJAXHOOK = 'phpcodeforposts_ajax';
    const TEXTDOMAIN = 'phpcodeforposts';

    static $directory_path;
    static $web_path;
    static $options;
    static $variables = array();
    static $_vars = array();

    public function __construct ( $directory_path, $web_path )
    {
        if( ! $directory_path ) {
            throw new InvalidArgumentException( _e( 'Please specify the directory path' ) );
        }
        if( ! $web_path ) {
            throw new InvalidArgumentException( _e( 'Please specify the web path' ) );
        }

        self::$directory_path = $directory_path;
        self::$web_path = $web_path;

        $this->load_class_dependencies();

        add_action( 'init', array( __CLASS__, 'check_post' ) );
        add_action( 'wp_ajax_' . self::AJAXHOOK, array( __CLASS__, 'check_ajax' ) );

        add_shortcode( PhpCodeForPosts_Shortcode::get_shortcode(), array( 'PhpCodeForPosts_Shortcode', 'handle_shortcode' ) );
        if( is_multisite() ) {
            add_shortcode( PhpCodeForPosts_Shortcode::get_multisite_shortcode(), array( 'PhpCodeForPosts_Shortcode', 'handle_multisite_shortcode' ) );
        }

        add_filter( 'widget_text', 'do_shortcode', 5, 1 );

        if( PhpCodeForPosts::$options->get_option( 'content_filter' ) ) {
            add_filter( 'the_content', array( 'PhpCodeForPosts_Shortcode', 'handle_extra_shortcode' ), 0, 1 );
        }

        if( PhpCodeForPosts::$options->get_option( 'content_filter' ) ) {
            add_filter( 'widget_text', array( 'PhpCodeForPosts_Shortcode', 'handle_extra_shortcode' ), 0, 1 );
        }
    }

    private function load_class_dependencies ()
    {
        require( self::directory_path_it( 'Classes/Options.php' ) );
        require( self::directory_path_it( 'Classes/Menu.php' ) );
        require( self::directory_path_it( 'Classes/Database.php' ) );
        require( self::directory_path_it( 'Classes/Snippet.php' ) );
        require( self::directory_path_it( 'Classes/Messages.php' ) );
        require( self::directory_path_it( 'Classes/Install.php' ) );
        require( self::directory_path_it( 'Classes/Shortcode.php' ) );
        require( self::directory_path_it( 'Classes/Exporter.php' ) );

        PhpCodeForPosts::$options = new PhpCodeForPosts_Options;
        new PhpCodeForPosts_Menu();
    }

    public static function set_variable ( $key, $value )
    {
        PhpCodeForPosts::$variables[$key] = $value;
    }

    public static function get_variable ( $key )
    {
        return PhpCodeForPosts::$variables[$key];
    }


    public static function check_post ()
    {
        ob_start();
        if( ! ( is_admin() && isset( $_POST[PhpCodeForPosts::POSTFIELD_PREFIX] ) && ! isset( $_POST['nopost'] ) ) ) {
            return;
        }

        $post = $_POST[PhpCodeForPosts::POSTFIELD_PREFIX];
        $action = isset( $post['action'] ) ? $post['action'] : false;
        $action_code = isset( $post['actioncode'] ) ? $post['actioncode'] : false;
        $item = isset( $post['item'] ) ? $post['item'] : false;

        if(
            $action === false
            || $action_code === false
            || $item === false
            || ! self::check_nonce( $action_code, $item, $action )
        ) {
            return;
        }

        self::check_actions( $post, false );
        ob_end_flush();
    }

    public static function check_ajax ()
    {
        if( ! isset( $_POST[PhpCodeForPosts::POSTFIELD_PREFIX] ) ) {
            return;
        }

        $post = $_POST[PhpCodeForPosts::POSTFIELD_PREFIX];

        $action = isset( $post['action'] ) ? $post['action'] : false;
        $action_code = isset( $post['actioncode'] ) ? $post['actioncode'] : false;
        $item = isset( $post['item'] ) ? $post['item'] : false;

        if(
            $action === false
            || $action_code === false
            || $item === false
            || ! self::check_nonce( $action_code, $item, $action )
        ) {
            echo "nope";
        } else {
            self::check_actions( $post, true );
        }
        wp_die();
    }

    private static function check_actions ( $post, $ajax = false )
    {

        switch( $post['action'] ) {
            case 'updateoptions':
                $state = PhpCodeForPosts::$options->save_posted_options( $post );

                if( $ajax ) {
                    echo (int)$state;
                    exit;
                }

                if( $state ) {
                    PhpCodeForPosts_Messages::add_success_message( __( 'Options saved', "phpcodeforposts" ) );
                } else {
                    PhpCodeForPosts_Messages::add_error_message( __( 'Failed to save options - have they changed?', "phpcodeforposts" ) );
                }
                break;


            case 'save':
                $state = PhpCodeForPosts_Snippet::save_posted_snippet( $post );
                if( $ajax ) {
                    echo (int)$state;
                    exit;
                }

                if( $state ) {
                    $shortcode = '[' . PhpCodeForPosts_Shortcode::get_shortcode() . ' snippet=' . PhpCodeForPosts_Snippet::$last_saved_snippet->get_id() . ']';
                    PhpCodeForPosts_Messages::add_success_message( __( 'Snippet saved', "phpcodeforposts" ) . ' - ' . $shortcode );
                } else {
                    PhpCodeForPosts_Messages::add_error_message( __( 'Failed to save snippet - has it changed?', "phpcodeforposts" ) );
                }
                header( 'location:admin.php' . html_entity_decode( PhpCodeForPosts_Snippet::$last_saved_snippet->get_snippet_edit_link() ) );
                exit;

                break;


            case 'delete':
                $state = PhpCodeForPosts_Database::delete_snippet_by_id( $post['item'] );
                if( $ajax ) {
                    $msg = $state ? __( 'Snippet Deleted', "phpcodeforposts" ) : __( 'Failed to delete snippet - it may not exist', "phpcodeforposts" );
                    self::send_json_response( $state ? 1 : 0, $msg );
                }

                if( $state ) {
                    PhpCodeForPosts_Messages::add_success_message( __( 'Snippet deleted', "phpcodeforposts" ) );
                } else {
                    PhpCodeForPosts_Messages::add_error_message( __( 'Failed to delete snippet - it may not exist', "phpcodeforposts" ) );
                }
                break;

            case 'bulkdelete':
                $snippets_to_delete = $post['delete'];

                if( count( $snippets_to_delete ) == 0 ) {
                    if( $ajax ) {
                        self::send_json_response( true, __( 'Nothing To Delete', "phpcodeforposts" ) );
                    }
                    break; //do nothing.
                }

                $deleted_count = 0;
                foreach( $snippets_to_delete as $record => $one ) {
                    try {
                        if( PhpCodeForPosts_Database::delete_snippet_by_id( intval( $record ) ) ) {
                            $deleted_count++;
                        }
                    } catch( InvalidArgumentException $e ) {
                        //do nothing.
                    }
                }

                if( $deleted_count > 0 ) {
                    $msg = sprintf(
                        _n( '%d Snippet deleted', '%d Snippets deleted', $deleted_count, "phpcodeforposts" ),
                        $deleted_count
                    );

                    if( $ajax ) {
                        self::send_json_response( true, $msg );
                    } else {
                        PhpCodeForPosts_Messages::add_success_message( $msg );
                    }
                } else {
                    $msg = __( 'Failed to delete snippets', "phpcodeforposts" );
                    if( $ajax ) {
                        self::send_json_response( false, $msg );
                    } else {
                        PhpCodeForPosts_Messages::add_error_message( $msg );
                    }
                }
                break;

            case 'generate-export':
                PhpCodeForPosts_Exporter::generate_export_file();
                break;

            case 'do-import':
                try {
                    PhpCodeForPosts_Exporter::do_import();
                    PhpCodeForPosts_Messages::add_success_message( __( 'Snippets Imported Successfully', "phpcodeforposts" ) );
                } catch( Exception $e ) {
                    PhpCodeForPosts_Messages::add_error_message( $e->getMessage() );
                }
                break;
        }
    }

    private static function send_json_response ( $state, $msg )
    {
        $json = json_encode( array( 'state' => $state, 'msg' => $msg ) );
        header( 'Content-Type:application/json' );
        header( 'Content-Length:' . strlen( $json ) );
        echo $json;
        exit;
    }

    public static function web_path_it ( $file )
    {
        return PhpCodeForPosts::$web_path . $file;
    }

    public static function directory_path_it ( $file )
    {
        return PhpCodeForPosts::$directory_path . $file;
    }

    public static function get_checkbox_field ( $key, $label )
    {

        return sprintf(
            '%1$s<input name="%2$s" id="%3$s" type="checkbox" value="1" %5$s /><label for="%3$s">%4$s</label>',
            self::get_hidden_field( $key, 0 ),
            self::__input_name( $key ),
            self::__input_id( $key ),
            $label,
            checked( self::$options->get_option( $key ), 1, false )
        );
    }

    public static function get_hidden_field ( $key, $value )
    {
        return sprintf(
            '<input type="hidden" name="%s" value="%s" />',
            self::__input_name( $key ),
            esc_attr( $value )
        );
    }

    public static function get_input_field ( $key, $value, $label )
    {
        return sprintf(
            '<label for="%1$s">%2$s</label> <input type="text" name="%3$s" value="%4$s" id="%1$s" />',
            self::__input_id( $key ),
            esc_html( $label, "phpcodeforposts" ),
            self::__input_name( $key ),
            esc_attr( $value )
        );
    }

    public static function __input_name ( $key )
    {
        return self::POSTFIELD_PREFIX . '[' . $key . ']';
    }

    public static function __input_id ( $key )
    {
        return self::POSTFIELD_PREFIX . '_' . $key;
    }

    public static function check_nonce ( $action_code, $item, $action )
    {
        return wp_verify_nonce( $action_code, $item . $action );
    }

    public static function ready_nonce ( $item, $action )
    {
        return wp_create_nonce( $item . $action );
    }
}
