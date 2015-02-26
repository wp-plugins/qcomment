<h3>QComment</h3>
<table class="form-table">
    <tr>
        <th><label for="qcomment_login"><?php _e( 'Login', 'qcomment' ); ?></label></th>
        <td>
            <input type="text" name="qcomment_login" id="qcomment_login"
                   value="<?php echo esc_attr( $qcomment_login ); ?>"
                   class="regular-text" /><br />
            <span class="description"><?php _e( 'Please enter your QComment login', 'qcomment' ); ?>.</span>
        </td>
    </tr>
</table>