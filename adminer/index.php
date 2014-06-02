<?php

$access = false;

if ( filter_has_var( INPUT_POST, 'pronamic_auth' ) ) {
	$auth = filter_input( INPUT_POST, 'pronamic_auth', FILTER_SANITIZE_STRING );
	$auth = json_decode( base64_decode( $auth ) );

	$_POST['auth'] = array(
		'driver'   => $auth->driver,
		'server'   => $auth->server,
		'username' => $auth->username,
		'password' => $auth->password,
		'db'       => $auth->db,
	);

	$key = 'wordpress_logged_in_' . $auth->cookiehash;

	$access = filter_has_var( INPUT_COOKIE, $key );
} else {
	$access = filter_has_var( INPUT_COOKIE, 'adminer_sid' );
}

if ( ! $access ) {
	header( 'Status: 403 Forbidden', true, 403 );

	header( 'HTTP/1.1 403 Forbidden', true, 403 );

	exit;
}

/**
 * Adminer object
 *
 * @return Adminer
 */
function adminer_object() {
	class PronamicAdminer extends Adminer {
		public function name() {
			return 'Pronamic Adminer';
		}
	}

	return new PronamicAdminer();
}

/**
 * Include Adminer
 */
include 'adminer.php';
