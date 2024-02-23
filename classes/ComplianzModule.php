<?php

namespace Pronamic\WordPress\PronamicClient;

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

		\add_filter( 'http_response', [ $this, 'http_response' ], 10, 3 );
	}

	/**
	 * HTTP response.
	 * 
	 * @param array  $response    HTTP resposne.
	 * @param array  $parsed_args Parsed arguments.
	 * @param string $url         URL.
	 * @return array
	 */
	public function http_response( $response, $parsed_args, $url ) {
		if ( 'https://complianz.io' !== $url ) {
			return $response;
		}

		if ( ! \array_key_exists( 'body', $parsed_args ) ) {
			return $response;
		}

		$body = $parsed_args['body'];

		if ( ! \is_array( $body ) ) {
			return $response;
		}

		if ( ! \array_key_exists( 'edd_action', $body ) ) {
			return $response;
		}

		$edd_action = $body['edd_action'];

		if ( 'activate_license' !== $edd_action ) {
			return $response;
		}

		$data = \json_decode( \wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $data ) ) {
			return $response;
		}

		$data['success']          = true;
		$data['license']          = 'valid';
		$data['activations_left'] = 1;
		$data['license_limit']    = 0;
		$data['site_count']       = 1;
		$data['expires']          = 'lifetime';

		unset( $data['error'] );

		$response['body'] = \wp_json_encode( $data );

		return $response;
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
