<?php
class QC_Options extends SC_Controller {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'qcomment_check_comments', array( $this, 'check_comments' ) );
        add_action( 'admin_notices', array( $this, 'show_errors' ) );

        parent::__construct( QC_DIR, 'options' );
    }

    public function admin_menu() {
        add_options_page(
            'QComment'
            , 'QComment'
            , 'manage_options'
            , 'qcomment'
            , array( $this, 'render_options_page' )
        );
    }

    public function admin_init() {
        add_settings_section(
            'qcomment_section',
            '',
            array( $this, 'render_section' ),
            'qcomment'
        );

        $this->render( 'app_key' );
        $this->render( 'load_comments' );
        $this->render( 'buy_for_all' );
        $this->render( 'subject' );
        $this->render( 'tariff_id' );
        $this->render( 'task' );
        $this->render( 'group' );
        $this->render( 'languages' );
        $this->render( 'min_rating' );
        $this->render( 'team_id' );
        $this->render( 'comment_configs' );
        $this->render( 'start_time' );
        $this->render( 'end_time' );
        $this->render( 'limit_field' );
        $this->render( 'day_limit' );
        $this->render( 'limit_hour' );
        $this->render( 'limit_url' );
        $this->render( 'limit_url_day' );
        $this->render( 'limit_author' );
        $this->render( 'limit_author_day' );
        $this->render( 'max_turn' );
        $this->render( 'stop_words' );
        $this->render( 'export_comments' );
        $this->render( 'pay' );

        // Register our setting so that $_POST handling is done for us and
        // our callback function just has to echo the <input>
        register_setting( 'qcomment', 'qc_app_key' );
        register_setting( 'qcomment', 'qc_load_comments' );
        register_setting( 'qcomment', 'qc_buy_for_all', array( $this, 'check_by_for_all' ) );
        register_setting( 'qcomment', 'qc_subject' );
        register_setting( 'qcomment', 'qc_tariff_id' );
        register_setting( 'qcomment', 'qc_task' );
        register_setting( 'qcomment', 'qc_group_id' );
        register_setting( 'qcomment', 'qc_language' );
        register_setting( 'qcomment', 'qc_min_rating' );
        register_setting( 'qcomment', 'qc_team_id' );
        register_setting( 'qcomment', 'qc_comment_configs' );
        register_setting( 'qcomment', 'qc_start_time' );
        register_setting( 'qcomment', 'qc_end_time' );
        register_setting( 'qcomment', 'qc_limit' );
        register_setting( 'qcomment', 'qc_min_day_limit' );
        register_setting( 'qcomment', 'qc_max_day_limit' );
        register_setting( 'qcomment', 'qc_limit_hour' );
        register_setting( 'qcomment', 'qc_limit_url' );
        register_setting( 'qcomment', 'qc_limit_url_day' );
        register_setting( 'qcomment', 'qc_limit_author' );
        register_setting( 'qcomment', 'qc_limit_author_day' );
        register_setting( 'qcomment', 'qc_max_turn' );
        register_setting( 'qcomment', 'qc_stop_words' );
        register_setting( 'qcomment', 'qc_export_comments', array( $this, 'set_cron' ) );
        register_setting( 'qcomment', 'qc_pay' );
    }

    public function render_options_page() {
        $this->render( 'index' );
    }

    public function render_section() {
    }

    public function set_cron( $value ) {
        if ( $value == '1' ) {
            //включаем wp cron
            if ( ! wp_next_scheduled( 'qcomment_check_comments' ) ) {
                $res = wp_schedule_event( time(), 'cron_every_20_mins', 'qcomment_check_comments');
            }
        }
        else {
            //отключаем wp cron
            wp_clear_scheduled_hook( 'qcomment_check_comments' );
        }
        return $value;
    }

    public function check_by_for_all( $value ) {
        /*
         * qc_app_key
         * qc_subject
         * qc_tariff_id
         * qc_task
         * qc_pay
         */
        if ( $value !== null ) {
            $fields_errors = array();
            if ( empty( $_POST[ 'qc_app_key' ] ) ) {
                $fields_errors[] = __( 'Application key', 'qcomment' );
            }
            if ( empty( $_POST[ 'qc_subject' ] ) ) {
                $fields_errors[] = __( 'Thematic ID', 'qcomment' );
            }
            if ( empty( $_POST[ 'qc_tariff_id' ] ) ) {
                $fields_errors[] = __( 'Tariff ID', 'qcomment' );
            }
            if ( empty( $_POST[ 'qc_task' ] ) ) {
                $fields_errors[] = __( 'Task for authors', 'qcomment' );
            }
            if ( empty( $_POST[ 'qc_pay' ] ) ) {
                $fields_errors[] = __( 'Comments to buy', 'qcomment' );
            }
            if ( count( $fields_errors ) > 0 ) {
                $info = '<p>' . __( 'Next fields are required for automatic comments buying', 'qcomment' ) . ':</p>';
                $info .= '<ol>';
                foreach ( $fields_errors as $field ) {
                    $info .= '<li>' . $field . '</li>';
                }
                $info .= '</ol>';
                add_settings_error( 'qc_buy_for_all', 'empty-fields', $info, 'error' );
            }
        }
        return $value;
    }

    public function show_errors() {
        settings_errors( 'qc_buy_for_all', false, true );
    }
}

$qco = new QC_Options();