<?php

namespace Pronamic\WordPress\PronamicClient;

class ScriptsModule {
	/**
	 * Instance of this class.
	 *
	 * @since 1.8.1
	 *
	 * @var Tracking
	 */
	protected static $instance = null;

	/**
	 * Constructs scripts module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'wp_print_scripts', array( $this, 'patch_swipebox' ) );
	}

	/**
	 * Patch Swipebox.
	 *
	 * In WordPress 5.6 jQuery was updated from 1.12.4 to 3.5.1.
	 * This resulted in an issue with Swipebox, all clicks
	 * resulted in a black overlay.
	 *
	 * @link https://github.com/brutaldesign/swipebox/issues/383
	 * @return void
	 */
	public function patch_swipebox() {
		if ( ! \wp_script_is( 'swipebox' ) ) {
			return;
		}

		$min = SCRIPT_DEBUG ? '' : '.min';

		\wp_deregister_script( 'swipebox' );

		\wp_register_script(
			'swipebox',
			\plugins_url( '../assets/swipebox/js/jquery.swipebox' . $min . '.js', __FILE__ ),
			array( 'jquery' ),
			'mho79-1.4.4',
			true
		);
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
