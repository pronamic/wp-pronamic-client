<?php

namespace Pronamic\WordPress\PronamicClient;

use WP_Error;

class ComplianzModule {
	/**
	 * Instance of this class.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Constructs and initialize Complianz module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct() {
		\add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Plugins loaded.
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		if ( ! \defined( 'cmplz_premium' ) ) {
			return;
		}

		\add_filter( 'pre_http_request', [ $this, 'pre_http_request' ], 10, 3 );
	}

	/**
	 * Pre HTTP request.
	 * 
	 * @param false|array|WP_Error $response    A preemptive return value of an HTTP request. Default false.
	 * @param array                $parsed_args HTTP request arguments.
	 * @param string               $url         The request URL.
	 * @return false|array|WP_Error
	 */
	public function pre_http_request( $response, $parsed_args, $url ) {
		if ( ! \in_array( $url, [ 'https://complianz.io', 'https://complianz.io/' ], true ) ) {
			return $response;
		}

		return \wp_remote_request( 'https://complianz.io.pronamic.directory/', $parsed_args );
	}

	/**
	 * Return an instance of this class.
	 * 
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
