<?php
add_settings_field(
    'qc_max_turn',
    __( 'Maximum simultaneous authors', 'qcomment' ),
    'qc_max_turn_render',
    'qcomment',
    'qcomment_section'
);

function qc_max_turn_render() {
    echo '<input name="qc_max_turn" type="text" id="qc_max_turn" value="' . get_option( 'qc_max_turn' )
        . '" class="regular-text">';
}