<?php

namespace Pronamic\WordPress\PronamicClient;

class Plugin {
	/**
	 * Instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @var Pronamic_WP_Extensions_ExtensionsPlugin
	 */
	protected static $instance = null;

	/**
	 * Plugin file
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Constructs and initialize Pronamic WordPress Extensions plugin
	 *
	 * @param string $file
	 */
	private function __construct( $file ) {
		$this->file     = $file;
		$this->dir_path = plugin_dir_path( $file );

		// Actions
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );

		// Filters
		add_filter( 'wp_headers', array( $this, 'wp_headers' ) );

		// Modules.
		$this->modules = array(
			'akismet'            => AkismetModule::get_instance( $this ),
			'google-analytics'   => GoogleAnalyticsModule::get_instance( $this ),
			'google-tag-manager' => GoogleTagManagerModule::get_instance( $this ),
			'gravityforms'       => GravityFormsModule::get_instance( $this ),
			'jetpack'            => JetpackModule::get_instance( $this ),
			'phpmailer'          => PhpMailerModule::get_instance( $this ),
			'scripts'            => ScriptsModule::get_instance( $this ),
			'yoast'              => YoastModule::get_instance( $this ),
		);

		// Admin
		if ( is_admin() ) {
			Admin::get_instance( $this );
		}

		// Updater
		$this->updater = Updater::get_instance( $this );
	}

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( 'pronamic_client', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	}

	/**
	 * Admin bar menu
	 */
	public function admin_bar_menu() {
		if ( current_user_can( 'pronamic_client' ) ) {
			global $wp_admin_bar;

			$wp_admin_bar->add_menu(
				array(
					'id'    => 'pronamic',
					'title' => __( 'Pronamic', 'pronamic_client' ),
					'href'  => __( 'https://www.pronamic.eu/', 'pronamic_client' ),
					'meta'  => array(
						'target' => '_blank',
					),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'parent' => 'pronamic',
					'id'     => 'pronamic_contact',
					'title'  => __( 'Contact', 'pronamic_client' ),
					'href'   => __( 'https://www.pronamic.eu/contact/', 'pronamic_client' ),
					'meta'   => array(
						'target' => '_blank',
					),
				)
			);
		}
	}

	/**
	 * WordPress headers
	 *
	 * @param array $headers
	 * @return array
	 */
	public function wp_headers( $headers ) {
		// Extend the headers with a Pronamic powered by header
		$headers['X-Powered-By'] = _x( 'Pronamic | pronamic.eu | info@pronamic.eu', 'x-powered-by', 'pronamic_client' );

		return $headers;
	}

	/**
	 * Display/include the specified file
	 *
	 * @param string $file
	 */
	public function display( $file ) {
		include plugin_dir_path( $this->file ) . $file;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance( $file = false ) {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self( $file );
		}

		return self::$instance;
	}
}
