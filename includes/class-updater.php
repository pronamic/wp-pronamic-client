<?php

class Pronamic_WP_ClientPlugin_Updater {
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
	 * @var Pronamic_WP_ClientPlugin_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize updater
	 *
	 * @param Pronamic_WP_ClientPlugin_Plugin $plugin
	 */
	private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Filters
		add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'transient_update_plugins_filter' ) );
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'transient_update_themes_filter' ) );
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
			'timeout'    => ( ( defined( 'DOING_CRON' ) && DOING_CRON ) ? 30 : 3 ),
			'body'       => $body,
			'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
		);

		return $options;
	}

	//////////////////////////////////////////////////

	/**
	 * Transient update plugins filter
	 *
	 * @see https://github.com/WordPress/WordPress/blob/3.7/wp-includes/option.php#L1030
	 *
	 * @param array $update_plugins
	 * @return array
	 */
	public function transient_update_plugins_filter( $update_plugins ) {
		if ( is_object( $update_plugins ) && isset( $update_plugins->response ) && is_array( $update_plugins->response ) ) {
			$pronamic_plugins = pronamic_client_get_plugins();

			$options = $this->get_http_api_options( array(
				'plugins' => json_encode( $pronamic_plugins ),
			) );

			$url = 'http://api.pronamic.eu/plugins/update-check/1.1/';

			$raw_response = wp_remote_post( $url, $options );

			if ( is_wp_error( $raw_response ) || 200 !== wp_remote_retrieve_response_code( $raw_response ) ) {
				return $update_plugins;
			}

			$response = json_decode( wp_remote_retrieve_body( $raw_response ), true );

			if ( is_array( $response ) && isset( $response['plugins'] ) ) {
				foreach ( $response['plugins'] as &$plugin ) {
					$plugin = (object) $plugin;
				}
				unset( $plugin );

				$update_plugins->response = array_merge( $update_plugins->response, $response['plugins'] );
			}
		}

		return $update_plugins;
	}

	/**
	 * Transient update themes filter
	 *
	 * @see https://github.com/WordPress/WordPress/blob/3.7/wp-includes/option.php#L1030
	 *
	 * @param array $update_themes
	 * @return array
	 */
	public function transient_update_themes_filter( $update_themes ) {
		if ( is_object( $update_themes ) && isset( $update_themes->response ) && is_array( $update_themes->response ) ) {
			$pronamic_themes = pronamic_client_get_themes();

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

			$options = $this->get_http_api_options( array(
				'themes' => json_encode( $themes ),
			) );

			$url = 'http://api.pronamic.eu/themes/update-check/1.1/';

			$raw_response = wp_remote_post( $url, $options );

			if ( is_wp_error( $raw_response ) || 200 !== wp_remote_retrieve_response_code( $raw_response ) ) {
				return $update_themes;
			}

			$response = json_decode( wp_remote_retrieve_body( $raw_response ), true );

			if ( is_array( $response ) && isset( $response['themes'] ) ) {
				$update_themes->response = array_merge( $update_themes->response, $response['themes'] );
			}
		}

		return $update_themes;
	}

	//////////////////////////////////////////////////

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
