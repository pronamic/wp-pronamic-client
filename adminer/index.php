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
	}

	return new PronamicAdminer();
}

/**
 * Include Adminer
 */
require 'adminer.php';
