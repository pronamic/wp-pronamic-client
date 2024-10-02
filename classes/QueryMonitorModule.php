<?php

namespace Pronamic\WordPress\PronamicClient;

class QueryMonitorModule {
	/**
	 * Instance of this class.
	 *
	 * @var QueryMonitorModule
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Construct Query Monitor module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Plugins loaded.
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		/**
		 * Check if Query Monitor is active.
		 *
		 * @link https://github.com/johnbillion/query-monitor/blob/60da795c040e0f08850891c74a36b2f566cee14d/query-monitor.php#L36
		 */
		if ( ! \defined( 'QM_VERSION' ) ) {
			return;
		}

		\add_filter( 'override_load_textdomain', [ $this, 'override_load_textdomain' ], 10, 2 );
	}

	/**
	 * Only English for Query Monitor plugin.
	 *
	 * @link https://github.com/johnbillion/query-monitor/blob/60da795c040e0f08850891c74a36b2f566cee14d/classes/QueryMonitor.php#L162-L167
	 * @link https://github.com/WordPress/wordpress-develop/blob/2bb5679d666474d024352fa53f07344affef7e69/src/wp-includes/l10n.php#L69-L71
	 * @link https://github.com/WordPress/wordpress-develop/blob/2bb5679d666474d024352fa53f07344affef7e69/src/wp-includes/l10n.php#L711-L726
	 * @link https://github.com/pronamic/wp-pronamic-client/issues/24
	 * @param bool   $override Whether to override the .mo file loading. Default false.
	 * @param string $domain   Text domain. Unique identifier for retrieving translated strings.
	 */
	public function override_load_textdomain( $override, $domain ) {
		if ( 'query-monitor' === $domain ) {
			$override = true;
		}

		return $override;
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
