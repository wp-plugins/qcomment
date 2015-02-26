<?php
add_settings_field(
    'qc_tariff_id',
    __( 'Tariff ID', 'qcomment' ),
    'qc_tariff_id_render',
    'qcomment',
    'qcomment_section'
);

function qc_tariff_id_render() {


    if ( false === ( $tariff = get_transient( 'tariff' ) ) ) {
        $resp = wp_remote_get( 'http://qcomment.ru/api/getTariff' );
        $tariff = json_decode($resp['body'],true);
        set_transient( 'tariff',$tariff, 24 * HOUR_IN_SECONDS );
    }
    $currentTariffId = get_option( 'qc_tariff_id' );
    echo '<select id="qc_subject" name="qc_tariff_id">';
    foreach ( $tariff as $key => $rate ) {
        echo '<option value="' . $rate[ 'id' ] . '" ' . selected( $currentTariffId, $key, false ) . '>' . $rate['name']
            . ' (' . $rate['symbols'] . ') ' . $rate['price'] . '</option>';
    }
    echo '</select>';
}