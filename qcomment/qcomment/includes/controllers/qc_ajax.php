<?php
class QC_Ajax {
    public function __construct() {
        add_action( 'wp_ajax_qcomment_save_project', array( $this, 'save_project' ) );
        add_action( 'wp_ajax_qcomment_buy_comments', array( $this, 'buy_comments' ) );
        add_action( 'wp_ajax_qcomment_activate_project', array( $this, 'activate_project' ) );
        add_action( 'wp_ajax_qcomment_deactivate_project', array( $this, 'deactivate_project' ) );
        add_action( 'wp_ajax_qcomment_get_project_status', array( $this, 'get_project_status' ) );
        add_action( 'wp_ajax_qcomment_get_comments', array( $this, 'get_comments' ) );
        add_action( 'wp_ajax_qcomment_accept_comment', array( $this, 'accept_comment' ) );
        add_action( 'wp_ajax_qcomment_decline_comment', array( $this, 'decline_comment' ) );
    }

    public function save_project() {

        $allowedFields = array(
            'title',
            'url',
            'subjects',
            'tarif_id',
            'bonus',
            'task',
            'group_id',
            'language',
            'min_rating',
            'team_id',
            'comment_configs',
            'start_time',
            'end_time',
            'limit',
            'min_day_limit',
            'max_day_limit',
            'limit_hour',
            'limit_url',
            'limit_url_day',
            'limit_author',
            'limit_author_day',
            'max_turn',
            'stop_words',
        );

        $project_id = get_post_meta( $_POST[ 'post_id' ], '_qcomment_project_id', true );
        if ( (int)$project_id > 0 ) {
            echo json_encode( array(
                    'error_code' => '1',
                    'status' => 'no',
                    'reason' => 'Проект уже создан',
            ) );
            die();
        }

        $requestData = array(
            'apicode' => get_option( 'qc_app_key' ),
        );

        if ( isset( $_POST ) ) {
            foreach ( $_POST as $key => $value ) {
                if ( in_array( $key, $allowedFields ) ) {
                    update_post_meta( $_POST[ 'post_id' ], '_qcomment_' . $key, $value );
                    if ( $key === 'comment_configs' ) {
                        $requestData[ $key ] = implode( ',', $value );
                    }
                    else {
                        $requestData[ $key ] = $value;
                    }
                }
            }
        }
        //запрос на создание проекта
        $response = wp_remote_post( 'http://qcomment.ru/api/newproject', array(
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
            $error_message = $response->get_error_message();
            echo json_encode( array(
                    'status' => 'no',
                    'reason' => $error_message,
            ) );
        } else {
            $body = json_decode( $response[ 'body' ] );
            if ( $body->status === 'ok' ) {
                update_post_meta( $_POST[ 'post_id' ], '_qcomment_project_id', $body->project_id );
            }
            echo $response[ 'body' ];
        }

        die();
    }

    public function buy_comments() {

        if ( isset( $_POST[ 'comments_count' ] ) && isset( $_POST[ 'project_id' ] ) ) {
            $requestData = array(
                'apicode' => get_option( 'qc_app_key' ),
                'project_id' => $_POST[ 'project_id' ],
                'pay' => $_POST[ 'comments_count' ],
            );

            //запрос на оплату проекта
            $response = wp_remote_post( 'http://qcomment.ru/api/payproject', array(
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
                $error_message = $response->get_error_message();
                echo json_encode( array(
                        'status' => 'no',
                        'reason' => $error_message,
                ) );
            } else {
                $boughtComments = get_post_meta( $_POST[ 'post_id' ], '_qcomment_bought_comments', true );
                if ( !empty( $boughtComments ) ) {
                    $boughtComments = (int)$boughtComments + (int)$_POST[ 'comments_count' ];
                }
                else {
                    $boughtComments = (int)$_POST[ 'comments_count' ];
                }
                update_post_meta( $_POST[ 'post_id' ], '_qcomment_bought_comments', $boughtComments );

                if ( isset( $_POST[ 'auto_check_comments' ] ) && $_POST[ 'auto_check_comments' ] === 'true' ) {
                    update_post_meta( $_POST[ 'post_id' ], '_qcomment_auto_check_comments', 1 );
                }
                else {
                    update_post_meta( $_POST[ 'post_id' ], '_qcomment_auto_check_comments', 0 );
                }

                echo $response[ 'body' ];
            }
        }
        else {
            echo json_encode( array(
                    'error_code' => '1',
                    'status' => 'no',
                    'reason' => 'Не достаточно данных',
            ) );
        }

        die();
    }

    public function activate_project() {
        if (isset($_POST['project_id'])) {
            $requestData = array(
                'apicode' => get_option( 'qc_app_key' ),
                'project_id' => $_POST['project_id'],
                'status' => 1,
            );

            //запрос на оплату проекта
            $response = wp_remote_post( 'http://qcomment.ru/api/projectstatus', array(
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
                $error_message = $response->get_error_message();
                echo json_encode( array(
                        'status' => 'no',
                        'reason' => $error_message,
                ) );
            } else {
                echo $response[ 'body' ];
            }
        }
        else {
            echo json_encode( array(
                    'error_code' => '1',
                    'status' => 'no',
                    'reason' => 'Не достаточно данных',
            ) );
        }

        die();
    }

    public function deactivate_project() {
        if (isset($_POST['project_id'])) {
            $requestData = array(
                'apicode' => get_option( 'qc_app_key' ),
                'project_id' => $_POST['project_id'],
                'status' => 2,
            );

            //запрос на оплату проекта
            $response = wp_remote_post( 'http://qcomment.ru/api/projectstatus', array(
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
                $error_message = $response->get_error_message();
                echo json_encode( array(
                        'status' => 'no',
                        'reason' => $error_message,
                ) );
            } else {
                echo $response[ 'body' ];
            }
        }
        else {
            echo json_encode( array(
                    'error_code' => '1',
                    'status' => 'no',
                    'reason' => 'Не достаточно данных',
            ) );
        }

        die();
    }

    public function get_project_status() {
        if ( isset( $_POST[ 'project_id' ] ) && 0 < (int)$_POST[ 'project_id' ] ) {
            $requestData = array(
                'apicode' => get_option( 'qc_app_key' ),
                'project_id' => $_POST['project_id'],
            );

            $response = wp_remote_post( 'http://qcomment.ru/api/projectstatus', array(
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
                echo json_encode( array(
                        'status' => 'no',
                        'reason' => $response->get_error_message(),
                ) );
            } else {
                echo $response[ 'body' ];
            }
        }
        else {
            echo json_encode( array(
                    'error_code' => '1',
                    'status' => 'no',
                    'reason' => 'Не указан ID проекта',
            ) );
        }

        die();
    }

    public function get_comments() {
        if ( isset( $_POST[ 'project_id' ] ) ) {
            $comments = array();

            global $qcomment_data;

            $statuses = array();

            $mode = get_option( 'qc_load_comments' );
            if ( 1 == $mode ) {
                $statuses = $qcomment_data[ 'comments_statuses' ];
            }
            if ( 2 == $mode ) {
                $statuses = array( '1' => 'оплачен' );
            }

            foreach ( $statuses as $id => $status ) {
                $response = QC_Utils::get_comments( $id, $_POST[ 'project_id' ] );

                if ( is_wp_error( $response ) ) {
                    echo json_encode( array(
                        'status' => 'no',
                        'reason' => $response->get_error_message(),
                    ) );
                    break;
                } else {
                    $data = (array)json_decode( $response[ 'body' ] );
                    if ( !isset( $data[ 'error_code' ] ) ) {
                        $comments = array_merge( $comments, $data );
                    }
                }
            }
            echo json_encode( $comments );
        }
        else {
            echo json_encode( array(
                'error_code' => '1',
                'status' => 'no',
                'reason' => 'Не указан ID проекта',
            ) );
        }

        die();
    }

    public function accept_comment() {
        if ( isset( $_POST[ 'comment_id' ] ) && 0 < (int)$_POST[ 'comment_id' ] ) {
            echo QC_Utils::accept_comment( 0, $_POST[ 'comment_id' ], $_POST[ 'post_id' ]
                ,$_POST[ 'comment_data' ][ 'robot_find' ], $_POST[ 'comment_data' ] [ 'author' ]
                , $_POST[ 'comment_data' ][ 'name' ], $_POST[ 'comment_data' ][ 'message' ] );
        }
        else {
            echo json_encode( array(
                'status' => 'no',
                'reason' => 'Не указан ID комментария',
            ) );
        }

        die();
    }

    public function decline_comment() {
        if ( isset( $_POST[ 'comment_id' ] ) && 0 < (int)$_POST[ 'comment_id' ] ) {
            $requestData = array(
                'apicode' => get_option( 'qc_app_key' ),
                'comment_id' => $_POST[ 'comment_id' ],
                'operation' => 2,
                'reason' => $_POST[ 'reason' ],
            );
            if ( isset( $_POST[ 'rewrite' ] ) && !empty( $_POST[ 'rewrite' ] ) ) {
                $requestData[ 'operation' ] = 1;
            }

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
                echo json_encode( array(
                    'status' => 'no',
                    'reason' => $response->get_error_message(),
                ) );
            } else {
                echo $response[ 'body' ];
            }
        }
        else {
            echo json_encode( array(
                'status' => 'no',
                'reason' => 'Не указан ID комментария',
            ) );
        }

        die();
    }
}

$qca = new QC_Ajax();