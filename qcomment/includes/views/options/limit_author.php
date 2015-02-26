<?php
add_settings_field(
    'qc_limit_author',
    __( 'Comments per author limit', 'qcomment' ),
    'qc_limit_author_render',
    'qcomment',
    'qcomment_section'
);

function qc_limit_author_render() {
    echo '<input name="qc_limit_author" type="text" id="qc_limit_author" value="' . get_option( 'qc_limit_author' )
        . '" class="regular-text">';
}