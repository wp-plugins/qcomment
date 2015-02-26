<?php
add_settings_field(
    'qc_tariff_id',
    __( 'Tariff ID', 'qcomment' ),
    'qc_tariff_id_render',
    'qcomment',
    'qcomment_section'
);

function qc_tariff_id_render() {
    global $qcomment_data;

    $currentTariffId = get_option( 'qc_tariff_id' );
    echo '<select id="qc_subject" name="qc_tariff_id">';
    foreach ( $qcomment_data[ 'rates' ] as $key => $rate ) {
        echo '<option value="' . $rate[ 'id' ] . '" ' . selected( $currentTariffId, $key, false ) . '>' . $rate['name']
            . ' (' . $rate['symbols'] . ') ' . $rate['price'] . '</option>';
    }
    echo '</select>';
}