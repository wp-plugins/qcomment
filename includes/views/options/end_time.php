<?php
add_settings_field(
    'qc_end_time',
    __( 'Project end time', 'qcomment' ),
    'qc_end_time_render',
    'qcomment',
    'qcomment_section'
);

function qc_end_time_render() {
    echo '<input name="qc_end_time" type="text" id="qc_end_time" value="' . get_option( 'qc_end_time' )
        . '" class="regular-text" placeholder="2014-03-22 19:30">';
}