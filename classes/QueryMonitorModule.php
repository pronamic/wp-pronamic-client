<?php

namespace Pronamic\WordPress\PronamicClient;

class QueryMonitorModule {
	/**
	 * Instance of this class.
	 *
	 * @var YoastModule
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize Yoast module.
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
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

		\add_filter( 'plugin_locale', array( $this, 'plugin_locale' ), 10, 2 );
	}

	/**
	 * For English for Query Monitor plugin.
	 * 
	 * @link https://github.com/johnbillion/query-monitor/blob/60da795c040e0f08850891c74a36b2f566cee14d/classes/QueryMonitor.php#L162-L167
	 * @link https://github.com/WordPress/wordpress-develop/blob/2bb5679d666474d024352fa53f07344affef7e69/src/wp-includes/l10n.php#L69-L71
	 * @param string $locale The plugin's current locale.
	 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
	 * @return string
	 */
	public function plugin_locale( $locale, $domain ) {
		if ( 'query-monitor' !== $domain ) {
			return $locale;
		}

		return 'en_US';
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
