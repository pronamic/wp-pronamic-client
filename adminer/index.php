<?php

if ( \PHP_SESSION_NONE === \session_status() ) {
	\session_start();
}

$adminer_path = $_SESSION['pronamic_client_adminer_path'] ?? null;

if ( ! \is_string( $adminer_path ) || '' === $adminer_path || ! \is_readable( $adminer_path ) ) {
	http_response_code( 403 );

	exit( 'Adminer access denied.' );
}

/**
 * Adminer object
 *
 * @return Adminer
 */
function adminer_object() {
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
