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
		 */
		public function login( $login, $password ) {
			return true;
		}
	}

	return new PronamicAdminer();
}

/**
 * Include Adminer
 */
require 'adminer.php';
