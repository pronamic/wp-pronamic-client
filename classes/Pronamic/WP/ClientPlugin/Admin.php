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
		
		if ( get_option( 'pronamic_client_db_version' ) != $pronamic_client_db_version ) {
			$this->upgrade();
		
			update_option( 'pronamic_client_db_version', $pronamic_client_db_version );
		}
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
			array( $this, 'page_index' ), // function
			plugins_url( 'images/icon-16x16.png', $this->plugin->file ) // icon URL
			// 0 // position
		);
	
		// @see _add_post_type_submenus()
		// @see wp-admin/menu.php
		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Checklist', 'pronamic_client' ), // page title
			__( 'Checklist', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_checklist', // menu slug
			array( $this, 'page_checklist' ) // function
		);
	
		// @see _add_post_type_submenus()
		// @see wp-admin/menu.php
		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Virus Scanner', 'pronamic_client' ), // page title
			__( 'Virus Scanner', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_virus_scanner', // menu slug
			array( $this, 'page_virus_scanner' ) // function
		);

		add_submenu_page(
			'pronamic_client', // parent slug
			__( 'Pronamic Extensions', 'pronamic_client' ), // page title
			__( 'Extensions', 'pronamic_client' ), // menu title
			'pronamic_client', // capability
			'pronamic_client_extensions', // menu slug
			array( $this, 'page_extensions' ) // function
		);
	}

	/**
	 * Admin enqueue scripts
	 *
	 * @param string $hook
	 */
	function admin_enqueue_scripts( $hook ) {
		$enqueue = strpos( $hook, 'pronamic_client' ) !== false;
	
		if ( $enqueue ) {
			// Styles
			wp_enqueue_style(
				'proanmic_client_admin' ,
				plugins_url( 'css/admin.css', $this->plugin->file )
			);
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
	public function page_index() {
		$this->plugin->display( 'admin/index.php' );
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
		if ( null == self::$instance ) {
			self::$instance = new self( $plugin );
		}
	
		return self::$instance;
	}
}
