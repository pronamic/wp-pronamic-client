<?php

$pronamic_client = \Pronamic\WordPress\PronamicClient\Plugin::get_instance();

$adminer_url = plugins_url( 'adminer/', $pronamic_client->file );

$driver = 'server';

$inputs = [
	'driver'   => 'server',
	'server'   => DB_HOST,
	'username' => DB_USER,
	'password' => DB_PASSWORD,
	'db'       => DB_NAME,
];

if ( defined( 'DB_ENGINE' ) && 'sqlite' === DB_ENGINE ) {
	$inputs['driver'] = 'sqlite';
}

if ( defined( 'FQDB' ) ) {
	$inputs['db'] = FQDB;

	unset( $inputs['password'] );
}

?>
<form target="_blank" method="post" action="<?php echo esc_url( $adminer_url ); ?>">
	<p>
		<a target="_blank" href="<?php echo esc_url( $adminer_url ); ?>"><?php _e( 'Adminer', 'pronamic-client' ); ?></a>
	</p>

	<p>
		<?php

		/**
		 * Adminer authentication.
		 *
		 * @link https://github.com/vrana/adminer/blob/v4.7.7/adminer/include/auth.inc.php#L51-L75
		 */

		foreach ( $inputs as $key => $value ) {
			printf(
				'<input type="hidden" name="%s" value="%s">',
				esc_attr( 'auth[' . $key . ']' ),
				esc_attr( $value )
			);
		}

		submit_button( __( 'Login', 'pronamic-client' ), 'primary', 'submit', false );

		?>
	</p>
</form>
