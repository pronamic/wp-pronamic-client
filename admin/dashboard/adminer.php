<?php

$pronamic_client = \Pronamic\WordPress\PronamicClient\Plugin::get_instance();

$auth_url = wp_nonce_url(
	add_query_arg(
		[
			'action' => 'pronamic_client_adminer_login',
		],
		admin_url( 'admin-post.php' )
	),
	'pronamic_client_adminer_login'
);

?>
<p>
	<a class="button button-primary" target="_blank" href="<?php echo esc_url( $auth_url ); ?>"><?php esc_html_e( 'Login', 'pronamic-client' ); ?></a>
</p>
