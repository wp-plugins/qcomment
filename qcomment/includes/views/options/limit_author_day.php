<?php
add_settings_field(
    'qc_limit_author_day',
    __( 'Comments per author daily limit', 'qcomment' ),
    'qc_limit_author_day_render',
    'qcomment',
    'qcomment_section'
);

function qc_limit_author_day_render() {
    echo '<input name="qc_limit_author_day" type="text" id="qc_limit_author_day" value="'
        . get_option( 'qc_limit_author_day' ) . '" class="regular-text">';
}