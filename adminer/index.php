<?php

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
 * Include Adminer
 */
require 'adminer.php';
