<?php

class Pronamic_WP_ClientPlugin_Settings {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var Pronamic_WP_ClientPlugin_Settings
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
			array( $this, 'settings_section' ),
			'pronamic_client'
		);

		add_settings_field(
			'pronamic_client_phpmailer_sender',
			__( 'PHPMailer Sender', 'pronamic_client' ),
			array( $this, 'input_text' ),
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
	 * Settings section.
	 *
	 * @param array $args Arguments.
	 */
	public function settings_section( $args ) {
	}

	/**
	 * Input text.
	 *
	 * @param array $args Arguments.
	 */
	public function input_text( $args ) {
		$defaults = array(
			'type'        => 'text',
			'classes'     => 'regular-text',
			'description' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$name  = $args['label_for'];
		$value = get_option( $name );

		$atts = array(
			'name'  => $name,
			'id'    => $name,
			'type'  => $args['type'],
			'class' => $args['classes'],
			'value' => $value,
		);

		$html = '';

		foreach ( $atts as $key => $value ) {
			$html .= sprintf( '%s="%s" ', $key, esc_attr( $value ) );
		}

		$html = trim( $html );

		printf(
			'<input %s />',
			// @codingStandardsIgnoreStart
			$html
			// @codingStandardsIgnoreEn
		);

		if ( ! empty( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				wp_kses(
					$args['description'],
					array(
						'br'   => array(),
						'code' => array()
					)
				)
			);
		}
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
