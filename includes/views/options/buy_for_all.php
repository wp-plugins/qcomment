<?php
add_settings_field(
    'qc_buy_for_all',
    __( 'Buy comments for all new posts', 'qcomment' ),
    'qc_buy_for_all_render',
    'qcomment',
    'qcomment_section'
);

function qc_buy_for_all_render() {
    echo '<label for="qc_buy_for_all"><input name="qc_buy_for_all" type="checkbox" id="qc_buy_for_all" value="1" '
        . checked( get_option( 'qc_buy_for_all'), 1, false ) . '></label>';
}