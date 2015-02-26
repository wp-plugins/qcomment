<?php
class QC_Utils {
    static function get_comments( $status_id, $project_id ) {
        $requestData = array(
            'apicode' => get_option( 'qc_app_key' ),
            'project_id' => $project_id,
            'status' => $status_id,
        );

        return wp_remote_post( 'http://qcomment.ru/api/comments', array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => $requestData,
                'cookies' => array(),
            )
        );
    }

    static function accept_comment( $operation_id, $comment_id, $post_id, $robot_find, $author
            , $comment_name, $comment_message ) {
        $requestData = array(
            'apicode' => get_option( 'qc_app_key' ),
            'comment_id' => $comment_id,
            'operation' => $operation_id,
        );

        $response = wp_remote_post( 'http://qcomment.ru/api/revision', array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'body' => $requestData,
                'cookies' => array(),
            )
        );

        if ( is_wp_error( $response ) ) {
            return json_encode( array(
                'status' => 'no',
                'reason' => $response->get_error_message(),
            ) );
        } else {
            $boughtComments = get_post_meta( $post_id, '_qcomment_bought_comments', true );
            if ( !empty( $boughtComments ) ) {
                $boughtComments = (int)$boughtComments - 1;
                update_post_meta( $post_id, '_qcomment_bought_comments', $boughtComments );
            }

            if ( $robot_find == '0' ) {
                global $wpdb;
                $user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '
                        . $wpdb->usermeta
                        . ' WHERE meta_key = "qcomment_login" AND meta_value = %s', $author )
                    , ARRAY_A );
                if ( $user === NULL ) {
                    $user_id = null;
                }
                else {
                    $user_id = $user[ 'user_id' ];
                }

                $time = current_time('mysql');

                $data = array(
                    'comment_post_ID' => (int)$post_id,
                    'comment_author' => $comment_name,
                    'comment_author_email' => '',
                    'comment_author_url' => '',
                    'comment_content' => $comment_message,
                    'comment_type' => '',
                    'comment_parent' => 0,
                    'user_id' => $user_id,
                    'comment_author_IP' => '127.0.0.1',
                    'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
                    'comment_date' => $time,
                    'comment_approved' => 1,
                );

                $commentID = wp_insert_comment( $data );

                if ( $commentID > 0 ) {
                    return $response[ 'body' ];
                }
                else {
                    return json_encode( array(
                        'status' => 'no',
                        'reason' => 'Комментарий принят, но его не удалось опубликовать в блоге',
                    ) );
                }
            }
            else {
                return $response[ 'body' ];
            }
        }
    }
}