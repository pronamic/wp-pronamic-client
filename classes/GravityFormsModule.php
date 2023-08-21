<?php

namespace Pronamic\WordPress\PronamicClient;

class GravityFormsModule {
	/**
	 * Instance of this class.
	 *
	 * @var GravityFormsModule
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize Gravity Forms module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		$this->setup();
	}

	/**
	 * Setup.
	 *
	 * @return void
	 */
	private function setup() {
		if ( ! \is_admin() ) {
			return;
		}

		\add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Plugins loaded.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/plugins_loaded/
	 * @return void
	 */
	public function plugins_loaded() {
		if ( ! \class_exists( '\GFForms' ) ) {
			return;
		}

		\add_action( 'current_screen', [ $this, 'current_screen' ] );
	}

	/**
	 * Current screen.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/current_screen/
	 * @param \WP_Screen $screen Screen.
	 * @return void
	 */
	public function current_screen( $screen ) {
		if ( 'forms_page_gf_settings' !== $screen->id ) {
			return;
		}

		$user = \wp_get_current_user();

		if ( 'pronamic' === $user->user_login ) {
			return;
		}

		\add_action( 'admin_print_styles-' . $screen->id, [ $this, 'admin_print_styles' ] );
	}

	/**
	 * Admin print styles.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/admin_print_styles-hook_suffix/
	 * @link https://developer.wordpress.org/reference/hooks/admin_print_styles/
	 * @return void
	 */
	public function admin_print_styles() {
		?>
		<style type="text/css">#gform-settings-section-section_license_key_details { display: none; }</style>
		<?php
	}

	/**
	 * Return an instance of this class.
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
