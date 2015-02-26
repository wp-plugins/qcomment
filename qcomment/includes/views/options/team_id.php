<?php
add_settings_field(
    'qc_team_id',
    __( 'Authors team ID', 'qcomment' ),
    'qc_team_id_render',
    'qcomment',
    'qcomment_section'
);

function qc_team_id_render() {
    echo '<input name="qc_team_id" type="text" id="qc_team_id" value="' . get_option( 'qc_team_id' ) . '" class="regular-text">';
    echo '<p class="description">' . __( 'View in', 'qcomment' )
        . ' <a href="http://qcomment.ru/user/teams" target="_blank">' . __( 'your teams list', 'qcomment' ) . '</a></p>';
}
