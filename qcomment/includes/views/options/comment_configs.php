<?php
add_settings_field(
    'qc_comment_configs',
    __( 'Comments types', 'qcomment' ),
    'qc_comment_configs_render',
    'qcomment',
    'qcomment_section'
);

function qc_comment_configs_render() {
    global $qcomment_data;

    $current_types = get_option( 'qc_comment_configs' );
    foreach ( $qcomment_data[ 'comment_configs' ] as $key => $value ) {
        $checked = '';
        if ( is_array( $current_types ) && in_array( $key, $current_types ) ) {
            $checked = 'checked="checked"';
        }
        echo '<label><input name="qc_comment_configs[]" type="checkbox" value="' . $key . '" '
            . $checked . '> ' . $value . '</label><br>';
    }
}