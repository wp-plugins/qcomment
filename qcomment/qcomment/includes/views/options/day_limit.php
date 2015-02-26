<?php
add_settings_field(
    'qc_day_limit',
    __( 'Random daily comments number.', 'qcomment' ),
    'qc_day_limit_render',
    'qcomment',
    'qcomment_section'
);

function qc_day_limit_render() {
    echo '<input name="qc_min_day_limit" type="text" id="qc_min_day_limit" value="' . get_option( 'qc_min_day_limit' )
        . '"> - ';
    echo '<input name="qc_max_day_limit" type="text" id="qc_max_day_limit" value="' . get_option( 'qc_max_day_limit' )
        . '">';
}