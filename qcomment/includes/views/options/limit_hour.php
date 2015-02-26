<?php
add_settings_field(
    'qc_limit_hour',
    __( 'Comments per hour limit', 'qcomment' ),
    'qc_limit_hour_render',
    'qcomment',
    'qcomment_section'
);

function qc_limit_hour_render() {
    echo '<input name="qc_limit_hour" type="text" id="qc_limit_hour" value="' . get_option( 'qc_limit_hour' )
        . '" class="regular-text">';
}