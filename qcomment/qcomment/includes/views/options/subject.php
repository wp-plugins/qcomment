<?php
add_settings_field(
    'qc_subject',
    __( 'Thematic ID', 'qcomment' ),
    'qc_subject_render',
    'qcomment',
    'qcomment_section'
);

function qc_subject_render() {
    global $qcomment_data;

    $currentThematicId = get_option( 'qc_subject' );
    echo '<select id="qc_subject" name="qc_subject">';
    foreach ( $qcomment_data[ 'subjects' ] as $key => $subject ) {
        echo '<option value="' . $key . '" ' . selected( $currentThematicId, $key, false ) . '>' . $subject . '</option>';
    }
    echo '</select>';
}