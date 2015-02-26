<?php
add_settings_field(
    'qc_limit_url',
    __( 'Comments per url limit', 'qcomment' ),
    'qc_limit_url_render',
    'qcomment',
    'qcomment_section'
);

function qc_limit_url_render() {
    echo '<input name="qc_limit_url" type="text" id="qc_limit_url" value="' . get_option( 'qc_limit_url' )
        . '" class="regular-text">';
}