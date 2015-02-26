<?php
function qc_get_value( $post_id, $name ) {
    $value = get_post_meta( $post_id, '_qcomment_' . $name, true );

    if ( empty( $value ) ) {
        $value = get_option( 'qc_' . $name );
    }
    return $value;
}