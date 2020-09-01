<?php

namespace Pronamic\WordPress\PronamicClient;

class Updater {
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
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize updater
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		\add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Filters
		\add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );

		// Transients
		$transient_update_plugins = 'update_plugins';
		$transient_update_themes  = 'update_themes';

		\add_filter( 'pre_set_site_transient_' . $transient_update_plugins, array( $this, 'transient_update_plugins_filter' ) );
		\add_filter( 'pre_set_site_transient_' . $transient_update_themes, array( $this, 'transient_update_themes_filter' ) );
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		$force_check = \filter_input( INPUT_GET, 'force-check', FILTER_VALIDATE_BOOLEAN );

		if ( $force_check ) {
			\delete_option( 'pronamic_client_plugins_update_check_response' );
			\delete_option( 'pronamic_client_themes_update_check_response' );
		}
	}

	/**
	 * Time to live depends on current filter.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/5.5/wp-includes/update.php#L297-L315
	 * @link https://github.com/WordPress/WordPress/blob/5.5/wp-includes/update.php#L504-L522
	 * @link https://github.com/WordPress/WordPress/blob/5.5/wp-includes/plugin.php
	 * @return int
	 */
	private function get_caching_ttl() {
		if ( \doing_filter( 'upgrader_process_complete' ) ) {
			return 0;
		}

		if ( \doing_filter( 'load-update-core.php' ) ) {
			return \MINUTE_IN_SECONDS;
		}

		if ( \doing_filter( 'load-update.php' ) || \doing_filter( 'load-plugins.php' ) || \doing_filter( 'load-themes.php' ) ) {
			return \HOUR_IN_SECONDS;
		}

		if ( \wp_doing_cron() ) {
			return 2 * \HOUR_IN_SECONDS;
		}

		return 12 * \HOUR_IN_SECONDS;
	}

	/**
	 * Get plugins update check.
	 *
	 * @return array{plugins: array, timestamp: int}
	 */
	public function get_plugins_update_check() {
		$response = array();

		$value = \get_option( 'pronamic_client_plugins_update_check_response' );

		if ( \is_array( $value ) ) {
			$response = $value;
		}

		if ( \array_key_exists( 'timestamp', $response ) ) {
			$timestamp = \intval( $response['timestamp'] );

			if ( \time() - $timestamp < $this->get_caching_ttl() ) {
				return $response;
			}
		}

		// Update last_checked for current to prevent multiple blocking requests if request hangs.
		$response['timestamp'] = \time();

		\update_option( 'pronamic_client_plugins_update_check_response', $response, false );

		$request_response = $this->request_plugins_update_check();

		if ( false !== $request_response ) {
			$response = $request_response;

			$response['timestamp'] = \time();

			\update_option( 'pronamic_client_plugins_update_check_response', $response, false );	
		}

		return $response;
	}

	/**
	 * Get themes update check.
	 *
	 * @return array{themes: array, timestamp: int}
	 */
	public function get_themes_update_check() {
		$response = array();

		$value = \get_option( 'pronamic_client_themes_update_check_response' );

		if ( \is_array( $value ) ) {
			$response = $value;
		}

		if ( \array_key_exists( 'timestamp', $response ) ) {
			$timestamp = \intval( $response['timestamp'] );

			if ( \time() - $timestamp < $this->get_caching_ttl() ) {
				return $response;
			}
		}

		// Update last_checked for current to prevent multiple blocking requests if request hangs.
		$response['timestamp'] = \time();

		\update_option( 'pronamic_client_themes_update_check_response', $response, false );

		$request_response = $this->request_themes_update_check();

		if ( false !== $request_response ) {
			$response = $request_response;

			$response['timestamp'] = \time();

			\update_option( 'pronamic_client_themes_update_check_response', $response, false );	
		}

		return $response;
	}

	/**
	 * Plugins API
	 *
	 * @see https://github.com/WordPress/WordPress/blob/4.1/wp-admin/includes/plugin-install.php#L55-L66
	 */
	public function plugins_api( $result, $action, $args ) {
		/* @todo */
		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the HTTP API options
	 *
	 * @param array $body
	 * @return array
	 */
	private function get_http_api_options( $body ) {
		$options = array(
			'timeout'    => ( \wp_doing_cron() ? 30 : 3 ),
			'body'       => $body,
			'user-agent' => 'WordPress/' . \get_bloginfo( 'version' ) . '; ' . \get_bloginfo( 'url' ),
		);

		return $options;
	}

	//////////////////////////////////////////////////

	/**
	 * Request plugins update check.
	 */
	private function request_plugins_update_check() {
		$pronamic_plugins = \pronamic_client_get_plugins();

		if ( false === $pronamic_plugins ) {
			return false;
		}

		$options = $this->get_http_api_options(
			array(
				'plugins' => \wp_json_encode( $pronamic_plugins ),
			)
		);

		$url = 'https://api.pronamic.eu/plugins/update-check/1.2/';

		$raw_response = \wp_remote_post( $url, $options );

		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( \is_wp_error( $raw_response ) || '200' != \wp_remote_retrieve_response_code( $raw_response ) ) {
			return false;
		}

		$response = \json_decode( \wp_remote_retrieve_body( $raw_response ), true );

		return $response;
	}

	/**
	 * Transient update plugins filter.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/3.7/wp-includes/option.php#L1030
	 * @param array $update_plugins
	 * @return array
	 */
	public function transient_update_plugins_filter( $update_plugins ) {
		if ( \is_object( $update_plugins ) && isset( $update_plugins->response ) && \is_array( $update_plugins->response ) ) {
			$response = $this->get_plugins_update_check();

			if ( \is_array( $response ) && \array_key_exists( 'plugins', $response ) ) {
				foreach ( $response['plugins'] as &$plugin ) {
					$plugin = (object) $plugin;
				}
				unset( $plugin );

				$update_plugins->response = \array_merge( $update_plugins->response, $response['plugins'] );
			}
		}

		return $update_plugins;
	}

	/**
	 * Request themes update check.
	 */
	private function request_themes_update_check() {
		$pronamic_themes = \pronamic_client_get_themes();

		if ( false === $pronamic_themes ) {
			return false;
		}

		$themes = array();

		foreach ( $pronamic_themes as $theme ) {
			$checked[ $theme->get_stylesheet() ] = $theme->get( 'Version' );

			$themes[ $theme->get_stylesheet() ] = array(
				'Name'       => $theme->get( 'Name' ),
				'Title'      => $theme->get( 'Name' ),
				'Version'    => $theme->get( 'Version' ),
				'Author'     => $theme->get( 'Author' ),
				'Author URI' => $theme->get( 'AuthorURI' ),
				'Template'   => $theme->get_template(),
				'Stylesheet' => $theme->get_stylesheet(),
			);
		}

		$options = $this->get_http_api_options(
			array(
				'themes' => \wp_json_encode( $themes ),
			)
		);

		$url = 'https://api.pronamic.eu/themes/update-check/1.2/';

		$raw_response = \wp_remote_post( $url, $options );

		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( \is_wp_error( $raw_response ) || '200' != \wp_remote_retrieve_response_code( $raw_response ) ) {
			return false;
		}

		$response = \json_decode( \wp_remote_retrieve_body( $raw_response ), true );

		return $response;
	}

	/**
	 * Transient update themes filter.
	 *
	 * @see https://github.com/WordPress/WordPress/blob/3.7/wp-includes/option.php#L1030
	 *
	 * @param array $update_themes
	 * @return array
	 */
	public function transient_update_themes_filter( $update_themes ) {
		if ( \is_object( $update_themes ) && isset( $update_themes->response ) && \is_array( $update_themes->response ) ) {
			$response = $this->get_themes_update_check();

			if ( \is_array( $response ) && \array_key_exists( 'themes', $response ) ) {
				$update_themes->response = \array_merge( $update_themes->response, $response['themes'] );
			}
		}

		return $update_themes;
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
