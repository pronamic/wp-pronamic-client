<?php

$adminer_url  = 'https://www.adminer.org/latest.php';
$temp_dir     = sys_get_temp_dir();
$today        = gmdate( 'Y-m-d' );
$filename     = 'adminer-' . md5( $today ) . '.php';
$adminer_path = $temp_dir . DIRECTORY_SEPARATOR . $filename;

if ( ! file_exists( $adminer_path ) ) {
	$code = file_get_contents( $adminer_url );

	if ( false === $code ) {
		http_response_code( 500 );

		exit( 'Adminer download failed.' );
	}

	// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
	file_put_contents( $adminer_path, $code );
}

/**
 * Adminer object
 *
 * @return Adminer
 */
function adminer_object() {
	class PronamicAdminer extends \Adminer\Adminer {
		public function name() {
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
