<?php

$pronamic_client = \Pronamic\WordPress\PronamicClient\Plugin::get_instance();

$adminer_url = plugins_url( 'adminer/index.php', $pronamic_client->file );

?>
<form target="_blank" method="post" action="<?php echo esc_attr( $adminer_url ); ?>">
	<p>
		<a target="_blank" href="<?php echo esc_attr( $adminer_url ); ?>"><?php _e( 'Adminer', 'pronamic_client' ); ?></a>
	</p>

	<p>
		<?php

		/**
		 * Adminer authentication.
		 *
		 * @link https://github.com/vrana/adminer/blob/v4.7.7/adminer/include/auth.inc.php#L51-L75
		 */

		?>
		<input type="hidden" name="auth[driver]" value="server">
		<input type="hidden" name="auth[server]" value="<?php echo esc_attr( DB_HOST ); ?>">
		<input type="hidden" name="auth[username]" value="<?php echo esc_attr( DB_USER ); ?>">
		<input type="hidden" name="auth[password]" value="<?php echo esc_attr( DB_PASSWORD ); ?>">
		<input type="hidden" name="auth[db]" value="<?php echo esc_attr( DB_NAME ); ?>">

		<?php submit_button( __( 'Login', 'pronamic_client' ), 'primary', 'submit', false ); ?>
	</p>
</form>
