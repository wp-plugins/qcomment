<div class="wrap">
    <h2><?php _e( 'QComment settings', 'qcomment' ); ?></h2>

    <form method="POST" action="options.php">
        <?php settings_fields( 'qcomment' );	//pass slug name of page, also referred
        //to in Settings API as option group name
        do_settings_sections( 'qcomment' ); 	//pass slug name of page
        submit_button();
        ?>
    </form>
</div>