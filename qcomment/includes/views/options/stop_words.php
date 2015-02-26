<?php
add_settings_field(
    'qc_stop_words',
    __( 'Stop words', 'qcomment' ),
    'qc_stop_words_render',
    'qcomment',
    'qcomment_section'
);

function qc_stop_words_render() {
    echo '<input name="qc_stop_words" type="text" id="qc_stop_words" value="' . get_option( 'qc_stop_words' )
        . '" class="regular-text">';
    echo '<p class="description">' . __( 'Separate with a comma', 'qcomment' ) . '</p>';
}