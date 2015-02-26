<?php
add_settings_field(
    'qc_load_comments',
    __( 'Load comments', 'qcomment' ),
    'qc_load_comments_render',
    'qcomment',
    'qcomment_section'
);

function qc_load_comments_render() {
    echo '<select id="qc_load_comments" name="qc_load_comments">';
    echo '<option value="1" ' . selected( get_option( 'qc_load_comments' ), 1, false )
        . '>' . __( 'all', 'qcomment' ) . '</option>';
    echo '<option value="2" ' . selected( get_option( 'qc_load_comments' ), 2, false )
        . '>' . __( 'approved only', 'qcomment' ) . '</option>';
    echo '</select>';
}
