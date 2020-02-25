<?php

class Pronamic_WP_ClientPlugin_GoogleTagManagerModule {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var Pronamic_WP_ClientPlugin_GoogleTagManagerModule
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Pronamic_WP_ClientPlugin_Plugin
	 */
	private $plugin;

	/**
	 * Container ID.
	 *
	 * @var string
	 */
	private $container_id;

	/**
	 * Constructs and initialize Google Tag Manager Module.
	 *
	 * @param Pronamic_WP_ClientPlugin_Plugin $plugin
	 */
	private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Init.
		\add_action( 'init', array( $this, 'init' ) );

		// Admin.
		if ( \is_admin() ) {
			\add_action( 'admin_init', array( $this, 'admin_init' ), 40 );
		}

		// Google Tag Manager Container ID.
		$this->container_id = \get_option( 'pronamic_client_google_tag_manager_container_id' );

		if ( ! empty( $this->container_id ) ) {
			\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

			\add_action( 'wp_head', array( $this, 'head' ), 1 );
			\add_action( 'wp_body_open', array( $this, 'body_open' ), 1 );
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {
		\register_setting(
			'pronamic_client',
			'pronamic_client_google_tag_manager_container_id',
			array(
				'type' => 'string',
			)
		);
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		// Section
		\add_settings_section(
			'pronamic_client_google_tag_manager',
			\__( 'Google Tag Manager', 'pronamic_client' ),
			'__return_null',
			'pronamic_client'
		);

		// GTM Code
		\add_settings_field(
			'pronamic_client_google_tag_manager_container_id',
			\__( 'Google Tag Manager Container ID', 'pronamic_client' ),
			function( $args ) {
				Pronamic_WP_ClientPlugin_Settings::input_text( $args );
			},
			'pronamic_client',
			'pronamic_client_google_tag_manager',
			array(
				'label_for' => 'pronamic_client_google_tag_manager_container_id',
				'classes'   => 'regular-text',
				'description' => sprintf(
					/* translators: 1: hook */
					\esc_html__( 'Your theme needs support for the %1$s hook.', 'pronamic_client' ),
					'<code>wp_body_open</code>'
				),
			)
		);
	}

	/**
	 * Plugins loaded.
	 */
	public function plugins_loaded() {
		$conflicts = array();

		/**
		 * Google Tag Manager for WordPress (By Thomas Geiger).
		 *
		 * @link https://wordpress.org/plugins/duracelltomi-google-tag-manager/
		 * @link https://plugins.trac.wordpress.org/browser/duracelltomi-google-tag-manager/tags/1.11.2/duracelltomi-google-tag-manager-for-wordpress.php#L16
		 */
		if ( \defined( 'GTM4WP_VERSION' ) ) {
			$conflicts[] = 'Google Tag Manager for WordPress (By Thomas Geiger)';
		}

		/**
		 * Notices.
		 *
	 	 * @link https://github.com/Yoast/wordpress-seo/blob/13.1/admin/class-yoast-plugin-conflict.php#L202
	 	 * @link https://plugins.trac.wordpress.org/browser/wk-google-analytics/tags/1.8.0/wk-ga.php#L15
		 */
		$this->admin_notices = array();

		foreach ( $conflicts as $plugin ) {
			$this->admin_notices[] = \sprintf(
				\__( 'The %1$s plugin might cause issues when used in conjunction with %2$s.', 'pronamic_client' ),
				'<em>' . $plugin . '</em>',
				'Pronamic Client'
			);
		}

		\add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Admin notices.
	 */
	public function admin_notices() {
		if ( empty( $this->admin_notices ) ) {
			return;
		}

		foreach ( $this->admin_notices as $notice ) {
			\printf(
				'<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>',
				$notice
			);
		}
	}

	/**
	 * Head.
	 */
	public function head() {
		if ( empty( $this->container_id ) ) {
			return;
		}

		$container_id = $this->container_id;

		include __DIR__ . '/../templates/google-tag-manager-head.php';
	}

	/**
	 * Body open.
	 */
	public function body_open() {
		if ( empty( $this->container_id ) ) {
			return;
		}

		$url = \add_query_arg( 'id', $this->container_id, 'https://www.googletagmanager.com/ns.html' );

		include __DIR__ . '/../templates/google-tag-manager-body.php';
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
