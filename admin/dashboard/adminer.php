<?php 

$plugin = Pronamic_WP_ClientPlugin_Plugin::get_instance();

$adminer_url = plugins_url( 'vendor/adminer/adminer.php', $plugin->file );

?>
<form target="_blank" method="post" action="<?php echo esc_attr( $adminer_url ); ?>">
	<p>
		<a target="_blank" href="<?php echo esc_attr( $adminer_url ); ?>"><?php _e( 'Adminer', 'pronamic_client' ); ?></a>

		<input type="hidden" name="auth[driver]" value="server" />
		<input type="hidden" name="auth[server]" value="<?php echo esc_attr( DB_HOST ); ?>" />
		<input type="hidden" name="auth[username]" value="<?php echo esc_attr( DB_USER ); ?>" />
		<input type="hidden" name="auth[password]" value="<?php echo esc_attr( DB_PASSWORD ); ?>" />
		<input type="hidden" name="auth[db]" value="<?php echo esc_attr( DB_NAME ); ?>" />

		<?php submit_button( __( 'Login', 'pronamic_client' ), 'primary', 'submit', false ); ?>
	</p>
</form>
