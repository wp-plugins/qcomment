<?php
add_settings_field(
    'qc_export_comments',
    __( 'Export comments with API', 'qcomment' ),
    'qc_export_comments_render',
    'qcomment',
    'qcomment_section'
);

function qc_export_comments_render() {
    echo '<label for="qc_buy_for_all"><input name="qc_export_comments" type="checkbox" id="qc_export_comments" value="1" '
        . checked( get_option( 'qc_export_comments'), 1, false ) . '></label>';
}