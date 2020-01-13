<?php

$pronamic_client = Pronamic_WP_ClientPlugin_Plugin::get_instance();

$adminer_url = plugins_url( 'adminer/index.php', $pronamic_client->file );

?>
<form target="_blank" method="post" action="<?php echo esc_attr( $adminer_url ); ?>">
	<p>
		<a target="_blank" href="<?php echo esc_attr( $adminer_url ); ?>"><?php _e( 'Adminer', 'pronamic_client' ); ?></a>

		<?php

		$auth             = new stdClass();
		$auth->driver     = 'server';
		$auth->server     = DB_HOST;
		$auth->username   = DB_USER;
		$auth->password   = DB_PASSWORD;
		$auth->db         = DB_NAME;
		$auth->cookiehash = COOKIEHASH;

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		$auth = base64_encode( wp_json_encode( $auth ) );

		?>
		<input type="hidden" name="pronamic_auth" value="<?php echo esc_attr( $auth ); ?>" />

		<?php submit_button( __( 'Login', 'pronamic_client' ), 'primary', 'submit', false ); ?>
	</p>
</form>
