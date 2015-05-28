<?php

class Pronamic_WP_ClientPlugin_Plugin {
	/**
	 * Instance of this class.
	 *
	 * @since 1.1.0
	 *
	 * @var Pronamic_WP_Extensions_ExtensionsPlugin
	 */
	protected static $instance = null;

	//////////////////////////////////////////////////

	/**
	 * Plugin file
	 *
	 * @var string
	 */
	public $file;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize Pronamic WordPress Extensions plugin
	 *
	 * @param string $file
	 */
	private function __construct( $file ) {
		$this->file     = $file;
		$this->dir_path = plugin_dir_path( $file );

		// Includes
		foreach ( glob( $this->dir_path . 'includes/*.php' ) as $filename ) {
			require_once $filename;
		}

		// Actions
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );

		// Filters
		add_filter( 'wp_headers', array( $this, 'wp_headers' ) );

		// Admin
		if ( is_admin() ) {
			Pronamic_WP_ClientPlugin_Admin::get_instance( $this );
		}

		// Updater
		$this->updater = Pronamic_WP_ClientPlugin_Updater::get_instance( $this );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public function init() {

	}

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( 'pronamic_client', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin bar menu
	 */
	public function admin_bar_menu() {
		if ( current_user_can( 'pronamic_client' ) ) {
			global $wp_admin_bar;

			$wp_admin_bar->add_menu( array(
				'id'    => 'pronamic',
				'title' => __( 'Pronamic', 'pronamic_client' ),
				'href'  => __( 'http://pronamic.eu/', 'pronamic_client' ),
				'meta'  => array( 'target' => '_blank' ),
			) );

			$wp_admin_bar->add_menu(array(
				'parent' => 'pronamic',
				'id'     => 'pronamic_contact',
				'title'  => __( 'Contact', 'pronamic_client' ),
				'href'   => __( 'http://pronamic.eu/contact/', 'pronamic_client' ),
				'meta'   => array( 'target' => '_blank' ),
			) );
		}
	}

	//////////////////////////////////////////////////

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

	//////////////////////////////////////////////////

	/**
	 * Display/iinclude the specified file
	 *
	 * @param string $file
	 */
	public function display( $file ) {
		include plugin_dir_path( $this->file ) . $file;
	}

	//////////////////////////////////////////////////

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
