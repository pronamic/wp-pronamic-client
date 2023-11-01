<?php

namespace Pronamic\WordPress\PronamicClient;

class JetpackModule {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var Tracking
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Construct Jetpack module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_filter( 'jetpack_just_in_time_msgs', [ $this, 'disable_jetpack_just_in_time_msgs_for_pronamic' ], 50 );
	}

	/**
	 * Disable Jetpack just in time messages for Pronamic user.
	 *
	 * @link https://github.com/Automattic/jetpack/blob/6.8.1/class.jetpack-jitm.php#L21-L31
	 * @link https://github.com/Automattic/jetpack/blob/6.8.1/class.jetpack.php#L665
	 *
	 * @since 1.3.2
	 *
	 * @param bool $show_jitm Whether to show just in time messages.
	 * @return bool False if current user login is 'pronamic', otherwise the passed in value.
	 */
	public function disable_jetpack_just_in_time_msgs_for_pronamic( $show_jitm ) {
		// Prevent fatal error if  plugin is network activated.
		if ( ! \function_exists( '\wp_get_current_user' ) ) {
			return $show_jitm;
		}

		$user = \wp_get_current_user();

		if ( 'pronamic' === $user->user_login ) {
			return false;
		}

		return $show_jitm;
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
