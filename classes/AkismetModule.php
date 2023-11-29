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
	 * Construct Akismet module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_action( 'admin_init', [ $this, 'admin_init' ] );
	}

	/**
	 * Admin init.
	 *
	 * @link https://plugins.trac.wordpress.org/browser/akismet/tags/4.1.3/class.akismet-admin.php#L890
	 */
	public function admin_init() {
		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! array_key_exists( 'page', $_GET ) || 'akismet-key-config' !== \sanitize_text_field( \wp_unslash( $_GET['page'] ) ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( ! array_key_exists( 'view', $_GET ) || 'stats' !== \sanitize_text_field( \wp_unslash( $_GET['view'] ) ) ) {
			return;
		}

		if ( ! \method_exists( '\Akismet', 'get_api_key' ) ) {
			return;
		}

		$api_key = \Akismet::get_api_key();

		if ( 'fc432369b1e26b1aeb4119e2deb0be15' !== \md5( $api_key ) ) {
			return;
		}

		\wp_die( \__( 'Sorry, you are not allowed to view this page.', 'pronamic-client' ) );
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
