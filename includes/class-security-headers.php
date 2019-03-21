<?php

class Pronamic_WP_ClientPlugin_SecurityHeaders {
	/**
	 * Instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @var Pronamic_WP_Extensions_ExtensionsPlugin
	 */
	protected static $instance = null;

	//////////////////////////////////////////////////

	/**
	 * Plugin
	 *
	 * @var Pronamic_WP_ClientPlugin_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize security headers
	 */
	private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'send_headers', array( $this, 'security_headers' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Security Headers
	 */
	public function security_headers() {

		// Enforce the use of HTTPS.
		if ( is_ssl() ) {
			header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
		}

		// Send a HTTP header to disable content type sniffing in browsers which support it.
		send_nosniff_header();

		// Block access if XSS attack is suspected.
		header( 'X-XSS-Protection: 1; mode=block' );

		// Send a HTTP header to limit rendering of pages to same origin iframes.
		send_frame_options_header();
	}

	//////////////////////////////////////////////////

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.1.0
	 *
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
