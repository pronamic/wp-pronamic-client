<?php
/**
 * Standalone entry point: no wp-load.php, no ABSPATH, only native PHP.
 *
 * Credentials arrive via a single-use `?token=` query parameter
 * instead of `$_SESSION`, which would conflict with Adminer's own session use.
 */

$adminer_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pronamic-client-adminer.php';

if ( ! is_readable( $adminer_path ) ) {
	header( 'HTTP/1.1 500 Internal Server Error' );
	exit;
}

/**
 * Read credentials referenced by a token.
 *
 * @param string $token Login token.
 * @return array<string, string>|null
 */
function pronamic_client_adminer_get_credentials( $token ) {
	if ( 1 !== preg_match( '/\A[a-f0-9]{32}\z/', $token ) ) {
		return null;
	}

	$creds_file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . '.' . $token;

	if ( ! is_file( $creds_file ) || filemtime( $creds_file ) < time() - 10 ) {
		return null;
	}

	$data = file_get_contents( $creds_file );

	// Single-use: the file is never needed again after this read attempt.
	unlink( $creds_file );

	if ( false === $data || strlen( $data ) <= 16 ) {
		return null;
	}

	$iv     = substr( $data, 0, 16 );
	$cipher = substr( $data, 16 );
	$key    = hash( 'sha256', $token, true );
	$json   = openssl_decrypt( $cipher, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv );

	if ( false === $json ) {
		return null;
	}

	$credentials = json_decode( $json, true );

	if ( ! is_array( $credentials ) ) {
		return null;
	}

	return $credentials;
}

/**
 * Adminer object
 *
 * @return Adminer
 */
function adminer_object() {
	$token = isset( $_GET['pronamic_client_adminer_token'] ) && is_string( $_GET['pronamic_client_adminer_token'] ) ? $_GET['pronamic_client_adminer_token'] : '';

	$credentials = pronamic_client_adminer_get_credentials( $token );

	if ( null !== $credentials ) {
		// Seed Adminer's own session password server-side, never rendered to the browser.
		Adminer\set_password( $credentials['driver'], $credentials['server'], $credentials['username'], $credentials['password'] );

		header(
			'Location: ' . Adminer\auth_url(
				$credentials['driver'],
				$credentials['server'],
				$credentials['username'],
				$credentials['db']
			),
			true,
			302
		);

		exit;
	}

	class PronamicAdminer extends \Adminer\Adminer {
		public function name(): string {
			return 'Pronamic Adminer';
		}

		/**
		 * Login.
		 *
		 * @link https://www.adminer.org/en/password/
		 * @link https://github.com/vrana/adminer/blob/7247f801bd06e51347d7ea671484e0fa6a883cbb/adminer/include/adminer.inc.php#L142-L152
		 */
		public function login( $login, $password ) {
			if ( defined( 'Adminer\\DRIVER' ) && 'sqlite' === \Adminer\DRIVER ) {
				return true;
			}

			return parent::login( $login, $password );
		}
	}

	return new PronamicAdminer();
}

/**
 * Require Adminer.
 */
require $adminer_path;
