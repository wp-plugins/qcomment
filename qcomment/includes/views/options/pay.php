<?php
add_settings_field(
    'qc_pay',
    __( 'Comments to buy', 'qcomment' ),
    'qc_pay_render',
    'qcomment',
    'qcomment_section'
);

function qc_pay_render() {
    echo '<input name="qc_pay" type="text" id="qc_pay" value="' . get_option( 'qc_pay' )
        . '" class="regular-text">';
}