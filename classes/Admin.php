<?php

namespace Pronamic\WordPress\PronamicClient;

class Admin {
	/**
	 * Instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize admin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Includes
		foreach ( glob( $this->plugin->dir_path . 'admin/includes/*.php' ) as $filename ) {
			require_once $filename;
		}

		// Actions
		add_action( 'admin_init', [ $this, 'admin_init' ] );

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		add_action( 'wp_dashboard_setup', [ $this, 'dashboard_setup' ] );
	}

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		// Maybe update
		global $pronamic_client_db_version;

		if ( get_option( 'pronamic_client_db_version' ) !== $pronamic_client_db_version ) {
			$this->upgrade();

			update_option( 'pronamic_client_db_version', $pronamic_client_db_version );
		}

		// Adminer
		if ( filter_has_var( INPUT_GET, 'pronamic_adminer' ) ) {
			include $this->plugin->display( 'adminer/index.php' );

			exit;
		}

		// Test email.
		if ( filter_has_var( INPUT_POST, 'pronamic_client_send_test_email' ) ) {
			$this->maybe_send_test_email();
		}
	}

	/**
	 * Maybe send test email.
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_nonce_field/
	 * @link https://developer.wordpress.org/reference/functions/wp_mail/
	 * @link https://plugins.trac.wordpress.org/browser/check-email/trunk/check-email.php#L73
	 */
	private function maybe_send_test_email() {
		if ( ! \current_user_can( 'pronamic_client' ) ) {
			return;
		}

		if ( ! array_key_exists( 'pronamic_client_send_test_email_nonce', $_POST ) ) {
			return;
		}

		$nonce = \sanitize_text_field( \wp_unslash( $_POST['pronamic_client_send_test_email_nonce'] ) );

		if ( ! wp_verify_nonce( $nonce, 'pronamic_client_send_test_email' ) ) {
			return;
		}

		$email_data = filter_input( INPUT_POST, 'pronamic_client_test_email', FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY );

		if ( null === $email_data ) {
			return;
		}

		$email = (object) filter_var_array(
			$email_data,
			[
				'from'    => \FILTER_VALIDATE_EMAIL,
				'to'      => \FILTER_VALIDATE_EMAIL,
				'subject' => \FILTER_UNSAFE_RAW,
				'message' => \FILTER_UNSAFE_RAW,
				'headers' => \FILTER_UNSAFE_RAW,
			]
		);

		if ( empty( $email->to ) ) {
			return;
		}

		$email->headers = \explode( "\r\n", $email->headers );

		if ( $email->from ) {
			$email->headers[] = 'From: ' . $email->from;
		}

		// Extend message with date.
		$email->message .= "\r\n";
		$email->message .= "\r\n";

		$email->message .= sprintf(
			/* translators: %s: sent date */
			__( 'Sent: %s', 'pronamic-client' ),
			\gmdate( 'r' )
		);

		$result = wp_mail( $email->to, $email->subject, $email->message, $email->headers );

		/**
		 * Redirect.
		 *
		 * @link https://developer.wordpress.org/reference/functions/admin_url/
		 * @link https://developer.wordpress.org/reference/functions/wp_get_referer/
		 * @link https://developer.wordpress.org/reference/functions/wp_safe_redirect/
		 * @link https://github.com/WordPress/WordPress/blob/5.3/wp-admin/includes/misc.php#L1204-L1230
		 * @link https://developer.wordpress.org/reference/functions/wp_removable_query_args/
		 */
		$location = admin_url( 'admin.php' );

		$location = add_query_arg(
			[
				'page'    => 'pronamic_client_email',
				'message' => 'pronamic_client_test_email_sent_' . ( $result ? 'yes' : 'no' ),
			],
			$location
		);

		wp_safe_redirect( $location );

		exit;
	}

	/**
	 * Get menu icon URL.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
	 * @return string
	 * @throws \Exception Throws exception when retrieving menu icon fails.
	 */
	private function get_menu_icon_url() {
		/**
		 * Icon URL.
		 *
		 * Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme.
		 * This should begin with 'data:image/svg+xml;base64,'.
		 *
		 * We use a SVG image with default fill color #A0A5AA from the default admin color scheme:
		 * https://github.com/WordPress/WordPress/blob/5.2/wp-includes/general-template.php#L4135-L4145
		 *
		 * The advantage of this is that users with the default admin color scheme do not see the repaint:
		 * https://github.com/WordPress/WordPress/blob/5.2/wp-admin/js/svg-painter.js
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
		 */
		$file = __DIR__ . '/../images/pronamic-icon-wp-admin-fresh-base.svgo-min.svg';

		if ( ! \is_readable( $file ) ) {
			throw new \Exception(
				\sprintf(
					'Could not read WordPress admin menu icon from file: %s.',
					\esc_html( $file )
				)
			);
		}

		$svg = \file_get_contents( $file, true );

		if ( false === $svg ) {
			throw new \Exception(
				\sprintf(
					'Could not read WordPress admin menu icon from file: %s.',
					\esc_html( $file )
				)
			);
		}

		$icon_url = \sprintf(
			'data:image/svg+xml;base64,%s',
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			\base64_encode( $svg )
		);

		return $icon_url;
	}

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Pronamic', 'pronamic-client' ), // page title
			__( 'Pronamic', 'pronamic-client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client', // menu slug
			[ $this, 'page_dashboard' ], // function
			$this->get_menu_icon_url() // icon URL
			// 0 // position
		);

		// @see wp-admin/menu.php
		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Checklist', 'pronamic-client' ), // page title
			__( 'Checklist', 'pronamic-client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_checklist', // menu slug
			[ $this, 'page_checklist' ] // function
		);

		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Extensions', 'pronamic-client' ), // page title
			__( 'Extensions', 'pronamic-client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_extensions', // menu slug
			[ $this, 'page_extensions' ] // function
		);

		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Email', 'pronamic-client' ), // page title
			__( 'Email', 'pronamic-client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_email', // menu slug
			[ $this, 'page_email' ] // function
		);

		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Settings', 'pronamic-client' ), // page title
			__( 'Settings', 'pronamic-client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_settings', // menu slug
			[ $this, 'page_settings' ] // function
		);
	}

	/**
	 * Admin enqueue scripts
	 *
	 * @param string $hook
	 */
	public function admin_enqueue_scripts( $hook ) {
		wp_register_script( 'proanmic-client-media', plugins_url( 'admin/js/media.js', $this->plugin->file ) );

		wp_localize_script(
			'proanmic-client-media',
			'pronamicClientMedia',
			[
				'browseText' => __( 'Browse…', 'pronamic-client' ),
			]
		);

		wp_register_style( 'proanmic-client-admin', plugins_url( 'admin/css/admin.css', $this->plugin->file ) );

		$enqueue = strpos( $hook, 'pronamic_client' ) !== false;

		if ( $enqueue ) {
			// Styles
			wp_enqueue_style( 'proanmic-client-admin' );
		}
	}

	/**
	 * Page options
	 */
	public function page_options() {
		$this->plugin->display( 'admin/page-options.php' );
	}

	/**
	 * Dashboard setup
	 */
	public function dashboard_setup() {
		wp_add_dashboard_widget(
			'pronamic_client',
			__( 'Pronamic', 'pronamic-client' ),
			'pronamic_client_dashboard'
		);
	}

	/**
	 * Input text.
	 *
	 * @param array $args Arguments.
	 */
	public static function input_text( $args ) {
		$defaults = [
			'type'        => 'text',
			'classes'     => 'regular-text',
			'description' => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$name  = $args['label_for'];
		$value = get_option( $name );

		$atts = [
			'name'  => $name,
			'id'    => $name,
			'type'  => $args['type'],
			'class' => $args['classes'],
			'value' => $value,
		];

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
	 * Page index
	 */
	public function page_dashboard() {
		$this->plugin->display( 'admin/dashboard.php' );
	}

	/**
	 * Page checklist
	 */
	public function page_checklist() {
		$this->plugin->display( 'admin/checklist.php' );
	}

	/**
	 * Page extensions
	 */
	public function page_extensions() {
		$this->plugin->display( 'admin/extensions.php' );
	}

	/**
	 * Page email
	 */
	public function page_email() {
		$this->plugin->display( 'admin/email.php' );
	}

	/**
	 * Page settings
	 */
	public function page_settings() {
		$this->plugin->display( 'admin/settings.php' );
	}

	/**
	 * Upgrade
	 */
	public function upgrade() {
		global $wp_roles;

		$wp_roles->add_cap( 'administrator', 'pronamic_client' );
		$wp_roles->add_cap( 'editor', 'pronamic_client' );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance( Plugin $plugin ) {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin );
		}

		return self::$instance;
	}
}
