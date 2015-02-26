<?php
class QC_User extends SC_Controller {
    public function __construct() {
        add_action( 'show_user_profile', array( $this, 'render_profile_fields' ) );
        add_action( 'edit_user_profile', array( $this, 'render_profile_fields' ) );
        add_action( 'personal_options_update', array( $this, 'save_profile_fields' ) );
        add_action( 'edit_user_profile_update', array( $this, 'save_profile_fields' ) );

        parent::__construct( QC_DIR, 'user' );
    }

    public function render_profile_fields( $user ) {
        $this->render( 'index', array(
            'qcomment_login' => get_the_author_meta( 'qcomment_login', $user->ID ),
        ) );
    }

    public function save_profile_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;

        /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
        update_user_meta( $user_id, 'qcomment_login', $_POST['qcomment_login'] );
    }
}

$qcu = new QC_User();