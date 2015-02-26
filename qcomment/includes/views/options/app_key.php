<?php
add_settings_field(
    'qc_app_key',
    __( 'Application key', 'qcomment' ),
    'qc_app_key_render',
    'qcomment',
    'qcomment_section'
);

function qc_app_key_render() {
    echo '<input name="qc_app_key" type="text" id="qc_app_key" value="' . get_option( 'qc_app_key' )
        . '" class="regular-text">';
}