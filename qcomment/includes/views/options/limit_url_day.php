<?php
add_settings_field(
    'qc_limit_url_day',
    __( 'Comments per url day limit', 'qcomment' ),
    'qc_limit_url_day_render',
    'qcomment',
    'qcomment_section'
);

function qc_limit_url_day_render() {
    echo '<input name="qc_limit_url_day" type="text" id="qc_limit_url_day" value="' . get_option( 'qc_limit_url_day' )
        . '" class="regular-text">';
}