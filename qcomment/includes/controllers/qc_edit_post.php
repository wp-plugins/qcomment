<?php
class QC_Edit_Post extends SC_Controller {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_boxes' ) );
        add_action( 'save_post', array( $this, 'save_postdata' ) );

        parent::__construct( QC_DIR, 'edit_post' );
    }

    public function add_boxes() {
        $types = get_post_types( array (
            'public' => true,
            'show_ui' => true
        ), 'names' );

        foreach ( $types as $type ) {
            add_meta_box(
                'qcomment_settings',
                'QComment',
                array( $this, 'show_box' ),
                $type,
                'normal',
                'low'
            );
        }
    }

    public function save_postdata() {
        global $post;
        $project_id = get_post_meta( $post->ID, '_qcomment_project_id', true );
        if ( empty( $project_id ) && 1 === (int)get_option( 'qc_buy_for_all' ) ) {
            $requestData = array();

            $qc_app_key = get_option( 'qc_app_key' );
            if ( empty( $qc_app_key ) ) {
                return;
            }
            else {
                $requestData[ 'apicode' ] = $qc_app_key;
            }

            $requestData[ 'title' ] = $post->post_title;
            $requestData[ 'url' ] = get_permalink( $post->ID );

            $qc_subject = get_option( 'qc_subject' );
            if ( empty( $qc_subject ) ) {
                return;
            }
            else {
                $requestData[ 'subjects' ] = $qc_subject;
            }

            $qc_tariff_id = get_option( 'qc_tariff_id' );
            if ( empty( $qc_tariff_id ) ) {
                return;
            }
            else {
                $requestData[ 'tarif_id' ] = $qc_tariff_id;
            }

            $qc_task = get_option( 'qc_task' );
            if ( empty( $qc_task ) ) {
                return;
            }
            else {
                $requestData[ 'task' ] = $qc_task;
            }

            $qc_group_id = get_option( 'qc_group_id' );
            if ( !empty( $qc_group_id ) ) {
                $requestData[ 'group_id' ] = $qc_group_id;
            }

            $qc_language = get_option( 'qc_language' );
            if ( !empty( $qc_language ) ) {
                $requestData[ 'language' ] = $qc_language;
            }

            $qc_min_rating = get_option( 'qc_min_rating' );
            if ( !empty( $qc_min_rating ) ) {
                $requestData[ 'min_rating' ] = $qc_min_rating;
            }

            $qc_team_id = get_option( 'qc_team_id' );
            if ( !empty( $qc_team_id ) ) {
                $requestData[ 'team_id' ] = $qc_team_id;
            }

            $qc_comment_configs = get_option( 'qc_comment_configs' );
            if ( !empty( $qc_comment_configs ) ) {
                $requestData[ 'comment_configs' ] = implode( ',', $qc_comment_configs );
            }

            $qc_start_time = get_option( 'qc_start_time' );
            if ( !empty( $qc_start_time ) ) {
                $requestData[ 'start_time' ] = $qc_start_time;
            }

            $qc_end_time = get_option( 'qc_end_time' );
            if ( !empty( $qc_end_time ) ) {
                $requestData[ 'end_time' ] = $qc_end_time;
            }

            $qc_limit = get_option( 'qc_limit' );
            if ( !empty( $qc_limit ) ) {
                $requestData[ 'limit' ] = $qc_limit;
            }

            $qc_min_day_limit = get_option( 'qc_min_day_limit' );
            if ( !empty( $qc_min_day_limit ) ) {
                $requestData[ 'min_day_limit' ] = $qc_min_day_limit;
            }

            $qc_max_day_limit = get_option( 'qc_max_day_limit' );
            if ( !empty( $qc_max_day_limit ) ) {
                $requestData[ 'max_day_limit' ] = $qc_max_day_limit;
            }

            $qc_limit_hour = get_option( 'qc_limit_hour' );
            if ( !empty( $qc_limit_hour ) ) {
                $requestData[ 'limit_hour' ] = $qc_limit_hour;
            }

            $qc_limit_author = get_option( 'qc_limit_author' );
            if ( !empty( $qc_limit_author ) ) {
                $requestData[ 'limit_author' ] = $qc_limit_author;
            }

            $qc_limit_author_day = get_option( 'qc_limit_author_day' );
            if ( !empty( $qc_limit_author_day ) ) {
                $requestData[ 'limit_author_day' ] = $qc_limit_author_day;
            }

            $qc_max_turn = get_option( 'qc_max_turn' );
            if ( !empty( $qc_max_turn ) ) {
                $requestData[ 'max_turn' ] = $qc_max_turn;
            }

            $qc_stop_words = get_option( 'qc_stop_words' );
            if ( !empty( $qc_stop_words ) ) {
                $requestData[ 'stop_words' ] = $qc_stop_words;
            }

            $qc_pay = get_option( 'qc_pay' );
            if ( empty( $qc_pay ) ) {
                return;
            }
            else {
                $requestData[ 'pay' ] = $qc_pay;
            }

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

            if ( !is_wp_error( $response ) ) {
                $body = json_decode( $response[ 'body' ] );
                if ( $body->status === 'ok' ) {
                    update_post_meta( $post->ID, '_qcomment_project_id', $body->project_id );
                }
            }
        }
    }

    public function show_box() {
        global $qcomment_data;

        wp_enqueue_script( 'angular', QC_URL . '/js/angular.min.js', array( 'jquery', 'underscore' ), '1.0', true );
        wp_enqueue_script( 'qcomment_main', QC_URL . '/js/main.js', array( 'angular', 'jquery-ui-dialog' ), '1.0', true );
        wp_enqueue_script( 'qcomment_api', QC_URL . '/js/api.js', array( 'qcomment_main' ), '1.0', true );
        wp_enqueue_script( 'qcomment_project', QC_URL . '/js/project.js', array( 'qcomment_api' ), '1.0', true );
        wp_enqueue_script( 'qcomment_tabs', QC_URL . '/js/tabs.js', array( 'qcomment_api' ), '1.0', true );
        wp_enqueue_script( 'qcomment_dialog', QC_URL . '/js/dialog.js', array( 'qcomment_api' ), '1.0', true );
        wp_enqueue_script( 'qcomment_comment_buttons', QC_URL . '/js/comment_buttons.js', array( 'qcomment_api' ), '1.0', true );
        wp_enqueue_script( 'qcomment_comments', QC_URL . '/js/comments.js', array( 'qcomment_api' ), '1.0', true );

        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_style( 'qcomment_main', QC_URL . '/css/main.css', array(), '1.0' );

        $data[ 'app_key' ] = get_option( 'qc_app_key' );

        $data[ 'post_id' ] = get_the_ID();

        $data[ 'url' ] = get_permalink( $data[ 'post_id' ] );

        $data[ 'project_id' ] = get_post_meta( $data[ 'post_id' ], '_qcomment_project_id', true );

        $data[ 'title' ] = qc_get_value( $data[ 'post_id' ], 'title' );
        $data[ 'title' ] = !empty( $data[ 'title' ] ) ? $data[ 'title' ] : get_the_title();

        $data[ 'subject' ] = qc_get_value( $data[ 'post_id' ], 'subject' );
        $data[ 'rates' ] = $qcomment_data[ 'rates' ][ 0 ][ 'id' ];
        $data[ 'tarif_id' ] = qc_get_value( $data[ 'post_id' ], 'tarif_id' );
        $data[ 'bonus' ] = qc_get_value( $data[ 'post_id' ], 'bonus' );
        $data[ 'task' ] = qc_get_value( $data[ 'post_id' ], 'task' );
        $data[ 'group_id' ] = qc_get_value( $data[ 'post_id' ], 'group_id' );
        $data[ 'language' ] = qc_get_value( $data[ 'post_id' ], 'language' );
        $data[ 'min_rating' ] = qc_get_value( $data[ 'post_id' ], 'min_rating' );
        $data[ 'team_id' ] = qc_get_value( $data[ 'post_id' ], 'team_id' );
        $data[ 'comment_configs' ] = qc_get_value( $data[ 'post_id' ], 'comment_configs' );
        $data[ 'start_time' ] = qc_get_value( $data[ 'post_id' ], 'start_time' );
        $data[ 'end_time' ] = qc_get_value( $data[ 'post_id' ], 'end_time' );
        $data[ 'limit' ] = qc_get_value( $data[ 'post_id' ], 'limit' );
        $data[ 'min_day_limit' ] = qc_get_value( $data[ 'post_id' ], 'min_day_limit' );
        $data[ 'max_day_limit' ] = qc_get_value( $data[ 'post_id' ], 'max_day_limit' );
        $data[ 'limit_hour' ] = qc_get_value( $data[ 'post_id' ], 'limit_hour' );
        $data[ 'limit_author' ] = qc_get_value( $data[ 'post_id' ], 'limit_author' );
        $data[ 'limit_author_day' ] = qc_get_value( $data[ 'post_id' ], 'limit_author_day' );
        $data[ 'max_turn' ] = qc_get_value( $data[ 'post_id' ], 'max_turn' );
        $data[ 'stop_words' ] = qc_get_value( $data[ 'post_id' ], 'stop_words' );

        wp_localize_script( 'qcomment_main', 'projectDefaults', $data );

        echo '<div ng-app="QComment">';
        $this->render( 'no_key' );
        $this->render( 'index', $data );
        $this->render( 'comments' );
        echo '</div>';
    }
}

$qcep = new QC_Edit_Post();