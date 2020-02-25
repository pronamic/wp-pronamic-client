<?php

class Pronamic_WP_ClientPlugin_PhpMailerModule {
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
	 * Sender.
	 *
	 * @var string
	 */
	private $sender;

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
			\add_action( 'admin_init', array( $this, 'admin_init' ), 20 );
		}

		// Sender.
		$this->sender = \get_option( 'pronamic_client_phpmailer_sender' );

		if ( ! empty( $this->sender) ) {
			add_action( 'phpmailer_init', array( $this, 'phpmailer_sender' ) );
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {
		register_setting(
			'pronamic_client',
			'pronamic_client_phpmailer_sender',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		// Settings - General.
		add_settings_section(
			'pronamic_client_email',
			__( 'Email', 'pronamic_client' ),
			'__return_null',
			'pronamic_client'
		);

		add_settings_field(
			'pronamic_client_phpmailer_sender',
			__( 'PHPMailer Sender', 'pronamic_client' ),
			function( $args ) {
				$args['type'] = 'email';

				Pronamic_WP_ClientPlugin_Admin::input_text( $args );
			},
			'pronamic_client',
			'pronamic_client_email',
			array(
				'label_for'   => 'pronamic_client_phpmailer_sender',
				'classes'     => 'regular-text',
				'description' => sprintf(
					/* translators: %s: <code>spf=neutral...</code> */
					__( 'Optionally set a PHPMailer Sender e-mail address to, for example, resolve SPF neutral notifications such as: %s.', 'pronamic_client' ),
					sprintf(
						'<br /><code>%s</code>',
						'spf=neutral (●●●●●●●●.●●●: ●.●.●.● is neither permitted nor denied by best guess record for domain of ●●●●●●●●@●●●●●●●●.●●●) smtp.mailfrom=●●●●●●●●@●●●●●●●●.●●●'
					)
				),
			)
		);
	}

	/**
	 * Set PHPMailer sender.
	 *
	 * @since 1.4.0
	 *
	 * @param PHPMailer $phpmailer PHPMailer object.
	 *
	 * @return PHPMailer
	 */
	public function phpmailer_sender( $phpmailer ) {
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( ! empty( $phpmailer->Sender ) ) {
			return;
		}

		if ( empty( $this->sender ) ) {
			return;
		}

		if ( ! filter_var( $this->sender, FILTER_VALIDATE_EMAIL ) ) {
			return;
		}

		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$phpmailer->Sender = $this->sender;
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
