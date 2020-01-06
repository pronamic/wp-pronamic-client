<?php

class Pronamic_WP_ClientPlugin_Admin {
	/**
	 * Instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @var self
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
	 * Constructs and initialize admin
	 */
	private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Includes
		foreach ( glob( $this->plugin->dir_path . 'admin/includes/*.php' ) as $filename ) {
			require_once $filename;
		}

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_setup' ) );
	}

	//////////////////////////////////////////////////

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
		$nonce = filter_input( INPUT_POST, 'pronamic_client_send_test_email_nonce', FILTER_SANITIZE_STRING );

		if ( ! wp_verify_nonce( $nonce, 'pronamic_client_send_test_email' ) ) {
			return;
		}

		$to = filter_input( INPUT_POST, 'pronamic_client_test_email_to', FILTER_VALIDATE_EMAIL );

		if ( empty( $to ) ) {
			return;
		}

		$subject = sprintf(
			__( 'Test email from %s', 'pronamic_client' ),
			get_bloginfo( 'url' )
		);

		$message = sprintf(
			__( 'This test email proves that your WordPress installation at %s can send emails.', 'pronamic_client' ),
			get_bloginfo( 'url' )
		);

		$message .= "\r\n";
		$message .= "\r\n";

		$message .= sprintf(
			__( 'Sent: %s', 'pronamic_client' ),
			date( 'r' )
		);

		$result = wp_mail( $to, $subject, $message );

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
			array(
				'page'    => 'pronamic_client_email',
				'message' => 'pronamic_client_test_email_sent_' . ( $result ? 'yes' : 'no' ),
			),
			$location
		);

		wp_safe_redirect( $location );

		exit;
	}

	//////////////////////////////////////////////////

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Pronamic', 'pronamic_client' ), // page title
			__( 'Pronamic', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client', // menu slug
			array( $this, 'page_dashboard' ), // function
			plugins_url( 'images/icon-16x16.png', $this->plugin->file ) // icon URL
			// 0 // position
		);

		// @see wp-admin/menu.php
		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Checklist', 'pronamic_client' ), // page title
			__( 'Checklist', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_checklist', // menu slug
			array( $this, 'page_checklist' ) // function
		);

		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Extensions', 'pronamic_client' ), // page title
			__( 'Extensions', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_extensions', // menu slug
			array( $this, 'page_extensions' ) // function
		);

		// @see wp-admin/menu.php
		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Virus Scanner', 'pronamic_client' ), // page title
			__( 'Scanner', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_virus_scanner', // menu slug
			array( $this, 'page_virus_scanner' ) // function
		);

		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Email', 'pronamic_client' ), // page title
			__( 'Email', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_email', // menu slug
			array( $this, 'page_email' ) // function
		);

		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Settings', 'pronamic_client' ), // page title
			__( 'Settings', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_settings', // menu slug
			array( $this, 'page_settings' ) // function
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
			array(
				'browseText' => __( 'Browse…', 'pronamic_client' ),
			)
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

	//////////////////////////////////////////////////

	/**
	 * Dashboard setup
	 */
	public function dashboard_setup() {
		wp_add_dashboard_widget(
			'pronamic_client',
			__( 'Pronamic', 'pronamic_client' ),
			'pronamic_client_dashboard'
		);
	}

	//////////////////////////////////////////////////
	// Pages
	//////////////////////////////////////////////////

	/**
	 * Page index
	 */
	public function page_dashboard() {
		$this->plugin->display( 'admin/dashboard.php' );
	}

	/**
	 * Page virus scanner
	 */
	public function page_virus_scanner() {
		$this->plugin->display( 'admin/virus-scanner.php' );
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

	//////////////////////////////////////////////////
	// Upgrade
	//////////////////////////////////////////////////

	/**
	 * Upgrade
	 */
	public function upgrade() {
		global $wp_roles;

		$wp_roles->add_cap( 'administrator', 'pronamic_client' );
		$wp_roles->add_cap( 'editor', 'pronamic_client' );
	}

	//////////////////////////////////////////////////
	// Singleton
	//////////////////////////////////////////////////

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin );
		}

		return self::$instance;
	}
}
