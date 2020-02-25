<?php

class Pronamic_WP_ClientPlugin_GoogleAnalyticsModule {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var Pronamic_WP_ClientPlugin_Tracking
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Pronamic_WP_ClientPlugin_Plugin
	 */
	private $plugin;

	/**
	 * Tracking ID.
	 *
	 * @var string
	 */
	private $tracking_id;

	/**
	 * Constructs and initialize updater
	 *
	 * @param Pronamic_WP_ClientPlugin_Plugin $plugin
	 */
	private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Init.
		\add_action( 'init', array( $this, 'init' ) );

		// Admin.
		if ( \is_admin() ) {
			\add_action( 'admin_init', array( $this, 'admin_init' ), 30 );
		}
		
		// Google Analytics Tracking ID.
		$this->tracking_id = \get_option( 'pronamic_client_google_analytics_tracking_id' );

		if ( ! empty( $this->tracking_id ) ) {
			\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

			\add_action( 'wp_head', array( $this, 'head' ), 1 );
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {
		\register_setting(
			'pronamic_client',
			'pronamic_client_google_analytics_tracking_id',
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
			'pronamic_client_google_analytics',
			\__( 'Google Analytics', 'pronamic_client' ),
			'__return_null',
			'pronamic_client'
		);

		// GA Code
		\add_settings_field(
			'pronamic_client_google_analytics_tracking_id',
			\__( 'Google Analytics Tracking ID', 'pronamic_client' ),
			function( $args ) {
				Pronamic_WP_ClientPlugin_Settings::input_text( $args );
			},
			'pronamic_client',
			'pronamic_client_google_analytics',
			array(
				'label_for' => 'pronamic_client_google_analytics_tracking_id',
				'classes'   => 'regular-text',
			)
		);
	}

	/**
	 * Plugins loaded.
	 */
	public function plugins_loaded() {
		$conflicts = array();

		/**
		 * Google Analytics for WordPress (By MonsterInsights).
		 *
		 * @link https://wordpress.org/plugins/google-analytics-for-wordpress/
		 * @link https://plugins.trac.wordpress.org/browser/google-analytics-for-wordpress/tags/7.10.4/googleanalytics.php#L297
		 */
		if ( \defined( 'MONSTERINSIGHTS_VERSION' ) ) {
			$conflicts[] = 'Google Analytics for WordPress (By MonsterInsights)';
		}

		/**
		 * Google Analytics (By WebKinder).
		 *
		 * @link https://wordpress.org/plugins/wk-google-analytics/
		 */
		if ( \defined( 'WK_GOOGLE_ANALYTICS_DIR' ) ) {
			$notices[] = 'Google Analytics (By WebKinder)';
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
	 * GA header.
	 */
	public function head() {
		if ( empty( $this->tracking_id ) ) {
			return;
		}

		$url = \add_query_arg( 'id', $this->tracking_id, 'https://www.googletagmanager.com/gtag/js' );

		$tracking_id = $this->tracking_id;

		include __DIR__ . '/../templates/google-analytics-tracking-code.php';
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