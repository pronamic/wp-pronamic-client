<?php

namespace Pronamic\WordPress\PronamicClient;

class ScriptsModule {
	/**
	 * Instance of this class.
	 *
	 * @since 1.8.1
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
		\add_action( 'wp_print_scripts', array( $this, 'patch_swipebox' ) );
	}

	/**
	 * Patch Swipebox.
	 *
	 * In WordPress 5.6 jQuery was updated from 1.12.4 to 3.5.1.
	 * This resulted in an issue with Swipebox, all clicks
	 * resulted in a black overlay.
	 *
	 * @link https://github.com/brutaldesign/swipebox/issues/383
	 * @link https://make.wordpress.org/core/2020/11/05/updating-core-jquery-to-version-3-part-2/
	 * @link https://jquery.com/upgrade-guide/1.9/#selector-property-on-jquery-objects
	 * @link https://developer.wordpress.org/reference/hooks/wp_print_scripts/
	 * @link https://developer.wordpress.org/reference/functions/wp_deregister_script/
	 * @link https://developer.wordpress.org/reference/functions/wp_script_is/
	 * @return void
	 */
	public function patch_swipebox() {
		$handles = array(
			/**
			 * Pronamic themes register with the handle name 'swipebox':
			 * @link https://gitlab.com/pronamic-themes/timmy/-/blob/ed073aa2876e2b0ba9582201d0378a253193edb1/classes/class-scripts.php#L65-72
			 */
			'swipebox',
			/**
			 * WordPress plugin 'Easy SwipeBox' register with the handle name 'easy-swipebox':
			 * @link https://github.com/leopuleo/easy-swipebox/blob/1.1.1/public/class-easy-swipebox-public.php#L135-L148
			 * @link https://github.com/leopuleo/easy-swipebox/blob/1.1.1/includes/class-easy-swipebox.php#L109
			 */
			'easy-swipebox',
		);

		/**
		 * Filter the enqueued scripts.
		 */
		$handles = \array_filter( $handles, function( $handle ) {
			return \wp_script_is( $handle );
		} );

		$min = \SCRIPT_DEBUG ? '' : '.min';

		foreach ( $handles as $handle ) {
			\wp_deregister_script( $handle );

			\wp_register_script(
				$handle,
				\plugins_url( '../assets/swipebox/js/jquery.swipebox' . $min . '.js', __FILE__ ),
				array( 'jquery' ),
				'mho79-1.4.4',
				true
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
