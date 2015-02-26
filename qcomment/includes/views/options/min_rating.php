<?php
add_settings_field(
    'qc_min_rating',
    __( 'Minimal author rating', 'qcomment' ),
    'qc_min_rating_render',
    'qcomment',
    'qcomment_section'
);

function qc_min_rating_render() {
    global $qcomment_data;

    $currentRating = get_option( 'qc_min_rating' );
    echo '<select id="qc_min_rating" name="qc_min_rating">';
    foreach ( $qcomment_data[ 'author_rating' ] as $key => $rating ) {
        echo '<option value="' . $key . '" ' . selected( $currentRating, $key, false ) . '>' . $rating . '</option>';
    }
    echo '</select>';
}