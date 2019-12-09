<?php

class Pronamic_WP_ClientPlugin_Scripts {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var Pronamic_WP_ClientPlugin_Scripts
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Pronamic_WP_ClientPlugin_Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize updater
	 *
	 * @param Pronamic_WP_ClientPlugin_Plugin $plugin
	 */
	private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'wp_head', array( $this, 'header' ) );
		add_action( 'wp_body_open', array( $this, 'body_open' ) );
		add_action( 'wp_footer', array( $this, 'footer' ) );
	}

	/**
	 * Initialize.
	 */
	public function init() {
		register_setting(
			'pronamic_client',
			'pronamic_client_scripts_header',
			array(
				'type' => 'string',
			)
		);

		register_setting(
			'pronamic_client',
			'pronamic_client_scripts_body_open',
			array(
				'type' => 'string',
			)
		);

		register_setting(
			'pronamic_client',
			'pronamic_client_scripts_footer',
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
		add_settings_section(
			'pronamic_client_scripts',
			__( 'Scripts', 'pronamic_client' ),
			array( $this, 'settings_section' ),
			'pronamic_client'
		);

		// Header
		add_settings_field(
			'pronamic_client_scripts_header',
			__( 'Header', 'pronamic_client' ),
			array( $this, 'field_textarea' ),
			'pronamic_client',
			'pronamic_client_scripts',
			array(
				'label_for' => 'pronamic_client_scripts_header',
				'classes'   => 'large-text',
			)
		);

		// Body open
		add_settings_field(
			'pronamic_client_scripts_body_open',
			__( 'Body open', 'pronamic_client' ),
			array( $this, 'field_textarea' ),
			'pronamic_client',
			'pronamic_client_scripts',
			array(
				'label_for'   => 'pronamic_client_scripts_body_open',
				'classes'     => 'large-text',
				'description' => sprintf(
					/* translators: 1: hook */
					esc_html__( 'Your theme needs support for the %1$s hook.', 'pronamic_client' ),
					'<code>wp_body_open</code>'
				),
			)
		);

		// Footer
		add_settings_field(
			'pronamic_client_scripts_footer',
			__( 'Footer', 'pronamic_client' ),
			array( $this, 'field_textarea' ),
			'pronamic_client',
			'pronamic_client_scripts',
			array(
				'label_for' => 'pronamic_client_scripts_footer',
				'classes'   => 'large-text',
			)
		);
	}

	/**
	 * Settings section.
	 *
	 * @param array $args Arguments.
	 */
	public function settings_section( $args ) {
	}

	/**
	 * Field textarea.
	 *
	 * @param array $args Arguments.
	 */
	public function field_textarea( $args ) {
		printf(
			'<textarea name="%s" id="%s" class="%s" rows="10" cols="60">%s</textarea>',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			$args['classes'],
			esc_textarea( get_option( $args['label_for'] ) )
		);

		if ( ! empty( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				wp_kses_post( $args['description'] )
			);
		}
	}

	public function admin_notices() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( 'pronamic_client_settings' !== $page ) {
			return;
		}

		$show_message = false;

		$plugins = array(
			'google-analytics-for-wordpress/googleanalytics.php',
			'wk-google-analytics/wk-ga.php',
		);

		foreach ( $plugins as $plugin ) {
			if ( is_plugin_active( $plugin ) ) {
				$show_message = $plugin;

				break;
			}
		}

		if ( ! $show_message ) {
			return;
		}

		printf(
			'<div class="notice notice-warning is-dismissible">%1$s</div>',
			esc_html__( 'There is already an analytics plugin active.', 'pronamic_client' )
		);
	}

	/**
	 * Header scripts.
	 */
	public function header() {
		if ( ! get_option( 'pronamic_client_scripts_header' ) ) {
			return;
		}

		echo get_option( 'pronamic_client_scripts_header' );
	}

	/**
	 * Body open scripts.
	 */
	public function body_open() {
		if ( ! get_option( 'pronamic_client_scripts_body_open' ) ) {
			return;
		}

		echo get_option( 'pronamic_client_scripts_body_open' );
	}

	/**
	 * Footer scripts.
	 */
	public function footer() {
		if ( ! get_option( 'pronamic_client_scripts_footer' ) ) {
			return;
		}

		echo get_option( 'pronamic_client_scripts_footer' );
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
