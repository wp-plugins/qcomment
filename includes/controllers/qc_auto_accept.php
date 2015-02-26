<?php
class QC_Auto_Accept {
    public function __construct() {
        add_action( 'qcomment_check_comments', array( $this, 'check_comments' ) );
    }

    public function check_comments() {
        global $sc_log;
        $sc_log->Info( 'Checking comments' );

        global $wpdb;
        $posts = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->postmeta
            . ' WHERE meta_key="_qcomment_bought_comments" AND CAST(meta_value AS UNSIGNED INTEGER) > 0', ARRAY_A );
        $sc_log->Info( 'Found posts: ' . count( $posts ) );
        if ( !empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $autoCheck = get_post_meta( $post[ 'post_id' ], '_qcomment_auto_check_comments', true );
                if ( $autoCheck == 1 ) {
                    $project_id = get_post_meta( $post[ 'post_id' ], '_qcomment_project_id', true );
                    $comments = QC_Utils::get_comments( 0, $project_id );
                    $sc_log->Info( 'Getting comments' );

                    if ( !is_wp_error( $comments ) ) {
                        $data = (array)json_decode( $comments[ 'body' ] );
                        $sc_log->Info( 'Post: ' . $post[ 'post_id' ] . '; comments: ' . count( $data ) );
                        if ( !empty( $data ) ) {
                            if ( !isset( $data[ 'error_code' ] ) ) {
                                foreach ( $data as $comment ) {
                                    QC_Utils::accept_comment( 0, $comment->id, $post[ 'post_id' ]
                                        , $comment->robot_find, $comment->author, $comment->name
                                        , $comment->message );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

$qcaa = new QC_Auto_Accept();