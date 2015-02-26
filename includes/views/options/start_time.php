<?php
add_settings_field(
    'qc_start_time',
    __( 'Project start time', 'qcomment' ),
    'qc_start_time_render',
    'qcomment',
    'qcomment_section'
);

function qc_start_time_render() {
    echo '<input name="qc_start_time" type="text" id="qc_start_time" value="' . get_option( 'qc_start_time' )
        . '" class="regular-text" placeholder="2014-03-21 10:00">';
}