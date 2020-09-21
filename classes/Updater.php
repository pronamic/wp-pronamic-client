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

	/**
	 * Plugin
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize updater
	 *
	 * @param Plugin $plugin
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_filter( 'http_response', array( $this, 'http_response' ), 10, 3 );

		\add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );
	}

	/**
	 * HTTP Response.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/5.5/wp-includes/class-http.php#L437-L446
	 * @param array  $response    HTTP response.
	 * @param array  $parsed_args HTTP request arguments.
	 * @param string $url         The request URL.
	 * @return array
	 */
	public function http_response( $response, $parsed_args, $url ) {
		if ( ! \array_key_exists( 'method', $parsed_args ) ) {
			return $repsonse;
		}

		if ( 'POST' !== $parsed_args['method'] ) {
			return $response;
		}

		if ( false !== strpos( $url, '//api.wordpress.org/plugins/update-check/' ) ) {
			$response = $this->extend_response_with_pronamic( $response, $parsed_args, 'plugins' );
		}

		if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/' ) ) {
			$response = $this->extend_response_with_pronamic( $response, $parsed_args, 'themes' );
		}

		return $response;
	}

	/**
	 * Extends WordPress.org API repsonse with Pronamic API response.
	 *
	 * @param array $response    HTTP response.
	 * @param array $parsed_args HTTP request arguments.
	 * @return array
	 */
	public function extend_response_with_pronamic( $response, $parsed_args, $type ) {
		$data = \json_decode( \wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $data ) ) {
			return $response;
		}

		$pronamic_data = false;

		switch ( $type ) {
			case 'plugins':
				$pronamic_data = $this->request_plugins_update_check( $parsed_args );

				break;
			case 'themes':
				$pronamic_data = $this->request_themes_update_check( $parsed_args );

				break;
		}

		if ( false === $pronamic_data ) {
			return $response;
		}

		if ( ! array_key_exists( $type, $data ) ) {
			$data[ $type ] = array();
		}

		$data[ $type ] = array_merge( $data[ $type ], $pronamic_data[ $type ] );

		$response['body'] = \wp_json_encode( $data );

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

	/**
	 * Remote post.
	 *
	 * @param string $url         URL to retrieve.
	 * @param array  $args        Request arguments.
	 * @param array  $parsed_args Parsed request arguments.
	 * @return array
	 */
	private function remote_post( $url, $args, $parsed_args ) {
		$keys = array(
			'timeout',
			'user-agent',
			'headers',
		);

		foreach ( $keys as $key ) {
			if ( \array_key_exists( $key, $parsed_args ) ) {
				$args[ $key ] = $parsed_args[ $key ];
			}
		}

		return \wp_remote_post( $url, $args );
	}

	/**
	 * Request plugins update check.
	 *
	 * @param array $parsed_args HTTP request arguments.
	 * @return array
	 */
	private function request_plugins_update_check( $parsed_args ) {
		$pronamic_plugins = \pronamic_client_get_plugins();

		if ( false === $pronamic_plugins ) {
			return false;
		}

		$raw_response = $this->remote_post(
			'https://api.pronamic.eu/plugins/update-check/1.2/',
			array(
				'body' => array(
					'plugins' => \wp_json_encode( $pronamic_plugins ),
				),
			),
			$parsed_args
		);

		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( \is_wp_error( $raw_response ) || '200' != \wp_remote_retrieve_response_code( $raw_response ) ) {
			return false;
		}

		$response = \json_decode( \wp_remote_retrieve_body( $raw_response ), true );

		return $response;
	}

	/**
	 * Request themes update check.
	 *
	 * @param array $parsed_args HTTP request arguments.
	 * @return array
	 */
	private function request_themes_update_check( $parsed_args ) {
		$pronamic_themes = \pronamic_client_get_themes();

		if ( false === $pronamic_themes ) {
			return false;
		}

		$themes = array();

		foreach ( $pronamic_themes as $theme ) {
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

		$raw_response = $this->remote_post(
			'https://api.pronamic.eu/themes/update-check/1.2/',
			array(
				'body' => array(
					'themes' => \wp_json_encode( $themes ),
				),
			),
			$parsed_args
		);

		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( \is_wp_error( $raw_response ) || '200' != \wp_remote_retrieve_response_code( $raw_response ) ) {
			return false;
		}

		$response = \json_decode( \wp_remote_retrieve_body( $raw_response ), true );

		return $response;
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
