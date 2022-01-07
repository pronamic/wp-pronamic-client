<?php

namespace Pronamic\WordPress\PronamicClient;

class YoastModule {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var YoastModule
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize Yoast module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	/**
	 * Plugins loaded.
	 * 
	 * @return void
	 */
	public function plugins_loaded() {
		if ( ! \defined( 'WPSEO_VERSION' ) ) {
			return;
		}

		\add_filter( 'http_request_args', array( $this, 'http_request_args' ), 1000, 2 );
	}

	/**
	 * HTTP request arguments.
	 * 
	 * @param array  $parsed_args Arguments.
	 * @param string $url         URL.
	 * @return array
	 */
	public function http_request_args( $parsed_args, $url ) {
		if ( 0 !== \strncmp( $url, 'https://my.yoast.com/', 21 ) ) {
			return $parsed_args;
		}

		if ( ! \array_key_exists( 'body', $parsed_args ) ) {
			return $parsed_args;
		}

		if ( ! \is_array( $parsed_args['body'] ) ) {
			return $parsed_args;
		}

		if ( ! \array_key_exists( 'url', $parsed_args['body'] ) ) {
			return $parsed_args;
		}

		$parsed_args['body']['url'] = 'https://yoast.com/';

		return $parsed_args;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.1.0
	 * @return object A single instance of this class.
	 */
	public static function get_instance( $plugin = false ) {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin );
		}

		return self::$instance;
	}
}
