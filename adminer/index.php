<?php
/**
 * Standalone entry point: no wp-load.php, no ABSPATH, only native PHP.
 *
 * Credentials arrive via a single-use `?token=` query parameter
 * instead of `$_SESSION`, which would conflict with Adminer's own session use.
 */

/**
 * Read credentials referenced by a token.
 *
 * @param string $token Login token.
 * @return array<string, string>|null
 */
function pronamic_client_adminer_get_credentials( $token ) {
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

$token = ( isset( $_GET['token'] ) && is_string( $_GET['token'] ) ) ? $_GET['token'] : '';

$adminer_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pronamic-client-adminer.php';
$credentials  = null;

if ( '' !== $token ) {
	$credentials = pronamic_client_adminer_get_credentials( $token );
}

if ( null !== $credentials ) {
	// DRIVER/SERVER/DB constants are baked from $_GET at bootstrap, before auth.inc.php runs.
	$_GET[ $credentials['driver'] ] = $credentials['server'];
	$_GET['username']               = $credentials['username'];
	$_GET['db']                     = $credentials['db'];
}

/**
 * Adminer object
 *
 * @return Adminer
 */
function adminer_object() {
	global $credentials;

	if ( null !== $credentials ) {
		// Seed Adminer's own session password server-side, never rendered to the browser.
		\Adminer\set_password( $credentials['driver'], $credentials['server'], $credentials['username'], $credentials['password'] );
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
			if ( defined( 'DRIVER' ) && 'sqlite' === DRIVER ) {
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
