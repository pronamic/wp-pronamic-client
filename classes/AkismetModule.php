<?php

namespace Pronamic\WordPress\PronamicClient;

class AkismetModule {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var Tracking
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize updater
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Admin init.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/akismet/tags/4.1.3/class.akismet-admin.php#L890
	 */
	public function admin_init() {
		if ( 'akismet-key-config' !== \filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
			return;
		}

		if ( 'stats' !== \filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING ) ) {
			return;
		}

		if ( ! \method_exists( '\Akismet', 'get_api_key' ) ) {
			return;
		}

		$api_key = \Akismet::get_api_key();

		if ( 'fc432369b1e26b1aeb4119e2deb0be15' !== \md5( $api_key ) ) {
			return;
		}

		\wp_die( \__( 'Sorry, you are not allowed to view this page.', 'prnonamic_client' ) );
	}

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
