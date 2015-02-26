<?php
add_settings_field(
    'qc_language',
    __( 'Language', 'qcomment' ),
    'qc_language_render',
    'qcomment',
    'qcomment_section'
);

function qc_language_render() {
    global $qcomment_data;

    $currentLanguageId = get_option( 'qc_language' );
    echo '<select id="qc_language" name="qc_language">';
    foreach ( $qcomment_data[ 'languages' ] as $key => $language ) {
        echo '<option value="' . $key . '" ' . selected( $currentLanguageId, $key, false ) . '>' . $language . '</option>';
    }
    echo '</select>';
}