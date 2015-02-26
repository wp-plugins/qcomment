<?php
add_settings_field(
    'qc_group_id',
    __( 'Group ID', 'qcomment' ),
    'qc_group_id_render',
    'qcomment',
    'qcomment_section'
);

function qc_group_id_render() {
    echo '<input name="qc_group_id" type="text" id="qc_group_id" value="' . get_option( 'qc_group_id' )
        . '" class="regular-text">';
}