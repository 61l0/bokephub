<?php

Class PhpCodeForPosts_Exporter
{

    public static function generate_export_file ()
    {
        $snippets = PhpCodeForPosts_Database::load_all_snippets();

        $columns = array(
            "ID",
            "Name",
            "Description",
            "Code",
            "Shared"
        );

        $csvOutput = "\"" . implode( "\",\"", $columns ) . "\"" . "\r\n";

        if( count( $snippets ) > 0 ) {
            while( $snippet = array_shift( $snippets ) ) {
                /* var $snippet = PhpCodeForPosts_Snippet() */
                $_snippet = array(
                    $snippet->get_id(),
                    addslashes( $snippet->get_name() ),
                    addslashes( $snippet->get_description() ),
                    addslashes( PhpCodeForPosts_Snippet::hash_code( $snippet->get_code() ) ),
                    intval( $snippet->get_shared() ),
                );
                $csvOutput .= "\"" . implode( "\",\"", $_snippet ) . "\"" . "\r\n";
            }
        }

        $filename = 'php-code-for-posts-export-' . date( 'Y-m-d' ) . '.csv';
        header( 'Content-Type: Application/CSV; charset=utf-8' );
        header( 'Content-Length: ' . strlen( $csvOutput ) );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        echo $csvOutput;
        exit;
    }

    public static function do_import ()
    {
        $file = isset( $_FILES['csvfile'] ) ? $_FILES['csvfile'] : false;
        if( ! $file || $file['error'] ) {
            throw new Exception( __( 'Error: Invalid file', "phpcodeforposts" ) );
        }

        $open = fopen( $file['tmp_name'], 'r' );
        if( ! $open ) {
            throw new Exception( __( 'Error: Failed to read file', "phpcodeforposts" ) );
        }

        //the first line is the titles, we dont care for them, for now.
        //I cant validate this line really because columns could be what ever the user chooses
        fgets( $open );

        $snippets = array();
        $keepIds = isset( $_POST['keep-ids'] ) ? intval( $_POST['keep-ids'] ) : 0;


        while( ( $line = fgetcsv( $open ) ) != false ) {

            if( empty( $line ) ) {
                continue;
            }

            if( ! self::is_file_line_valid( $line ) ) {
                throw new Exception( __( 'Error: Invalid line, not continuing', "phpcodeforposts" ) );
            }

            $snippet = new PhpCodeForPosts_Snippet();
            $snippet
                ->set_name( $line[1] )
                ->set_description( $line[2] )
                ->set_code( PhpCodeForPosts_Snippet::unhash_code( $line[3] ) )
                ->set_shared( $line[4] );

            if( $keepIds ) {
                $snippet->set_id( $line[0] );
            }

            $snippets[] = $snippet;
        }

        if( count( $snippets ) > 0 ) {
            foreach( $snippets as $snippet ) {
                PhpCodeForPosts_Database::save_snippet( $snippet );
            }
        }

    }

    private static function is_file_line_valid ( $file )
    {
        if( ! filter_var( $file[0], FILTER_VALIDATE_INT ) ) {
            return false;
        }

        if( ! isset( $file[1][0] ) ) {
            return false;
        }

        if( false === base64_decode( $file[3], true ) ) {
            return false;
        }

        if( $file[4] != "0" && $file[4] != "1" ) {
            return false;
        }

        return true;
    }
}