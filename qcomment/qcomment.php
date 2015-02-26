<?php
/*
Plugin Name: QComment
Plugin URI: http://qcomment.ru/
Description: This plugin integrates QComment service into WordPress.
Version: 1.1.1
Author: Vladimir Statsenko
Author URI: http://www.simplecoding.org
License: private
*/

if ( !function_exists( 'add_action' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( function_exists( 'add_action' ) ) {
    // plugin definitions
    define( 'QC_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
    define( 'QC_URL', plugins_url( '', __FILE__ ) );
}

if ( is_admin() ) {
    require_once( QC_DIR . 'config.php' );

    load_plugin_textdomain( 'qcomment', false, 'qcomment/lang' );

    require_once( QC_DIR . 'includes/controllers/qc_ajax.php' );
    require_once( QC_DIR . 'libs/sc_controller.php' );
    require_once( QC_DIR . 'libs/helpers.php' );
    require_once( QC_DIR . 'includes/controllers/qc_options.php' );
    require_once( QC_DIR . 'includes/controllers/qc_user.php' );
    require_once( QC_DIR . 'includes/controllers/qc_edit_post.php' );
}

require_once( QC_DIR . 'includes/utils/qc_utils.php' );
require_once( QC_DIR . 'includes/controllers/qc_auto_accept.php' );

register_deactivation_hook( __FILE__, 'qcomment_deactivation' );

function qcomment_deactivation() {
    wp_clear_scheduled_hook( 'qcomment_check_comments' );
}

add_filter( 'cron_schedules', 'qcomment_cron_every_20_mins' );

function qcomment_cron_every_20_mins( $schedules ) {
    $schedules['cron_every_20_mins'] = array(
        'interval' => 1200,
        'display' => __( 'Once every 20 minutes', 'qcomment' )
    );
    return $schedules;
}