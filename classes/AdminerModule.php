<?php

namespace Pronamic\WordPress\PronamicClient;

class AdminerModule {
	/**
	 * Instance of this class.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Plugin.
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * Construct Adminer module.
	 *
	 * @param Plugin $plugin Plugin.
	 */
	private function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		\add_action( 'admin_menu', $this->admin_menu( ... ), 110 );
		\add_action( 'admin_bar_menu', $this->admin_bar_menu( ... ), 110 );
		\add_action( 'admin_post_pronamic_client_adminer_login', $this->adminer_login( ... ) );
	}

	/**
	 * Get the Adminer login URL.
	 *
	 * @return string
	 */
	private function get_login_url() {
		return \wp_nonce_url(
			\add_query_arg(
				[
					'action' => 'pronamic_client_adminer_login',
				],
				\admin_url( 'admin-post.php' )
			),
			'pronamic_client_adminer_login'
		);
	}

	/**
	 * Add Adminer to the Pronamic submenu.
	 */
	private function admin_menu() {
		\add_submenu_page(
			'pronamic_client',
			\__( 'Adminer', 'pronamic-client' ),
			\__( 'Adminer', 'pronamic-client' ),
			'pronamic_client',
			$this->get_login_url()
		);
	}

	/**
	 * Add Adminer to the Pronamic admin bar menu.
	 */
	private function admin_bar_menu() {
		if ( ! \current_user_can( 'pronamic_client' ) ) {
			return;
		}

		global $wp_admin_bar;

		$wp_admin_bar->add_menu(
			[
				'parent' => 'pronamic',
				'id'     => 'pronamic_adminer',
				'title'  => \__( 'Adminer', 'pronamic-client' ),
				'href'   => $this->get_login_url(),
				'meta'   => [
					'target' => '_blank',
				],
			]
		);
	}

	/**
	 * Login to Adminer.
	 */
	private function adminer_login() {
		if ( ! \current_user_can( 'pronamic_client' ) ) {
			\wp_die(
				esc_html__( 'You are not allowed to access Adminer.', 'pronamic-client' ),
				esc_html__( 'Forbidden', 'pronamic-client' ),
				403
			);
		}

		\check_admin_referer( 'pronamic_client_adminer_login' );

		$adminer_url = 'https://www.adminer.org/latest.php';

		$filename = 'pronamic-client-adminer-' . md5( gmdate( 'Y-m-d' ) ) . '.php';

		$adminer_path = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . $filename;

		if ( ! \is_file( $adminer_path ) ) {
			$code = \file_get_contents( $adminer_url );

			if ( false === $code ) {
				\wp_die(
					esc_html__( 'Adminer download failed.', 'pronamic-client' ),
					esc_html__( 'Error', 'pronamic-client' ),
					500
				);
			}

			// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
			\file_put_contents( $adminer_path, $code );
		}

		$driver   = 'server';
		$server   = DB_HOST;
		$username = DB_USER;
		$password = DB_PASSWORD;
		$db       = DB_NAME;

		if ( \defined( 'DB_ENGINE' ) && 'sqlite' === \DB_ENGINE ) {
			$driver = 'sqlite';
		}

		if ( \defined( 'FQDB' ) ) {
			$db = \FQDB;
		}

		$token = $this->build_adminer_token(
			[
				'driver'   => $driver,
				'server'   => $server,
				'username' => $username,
				'password' => $password,
				'db'       => $db,
			]
		);

		$adminer_url = \add_query_arg(
			[ 'pronamic_client_adminer_token' => $token ],
			\plugins_url( 'adminer/', $this->plugin->file )
		);

		\wp_safe_redirect( $adminer_url );

		exit;
	}

	/**
	 * Build a single-use Adminer login token.
	 *
	 * The DB credentials are encrypted in a short-lived temp file. The token
	 * identifies the file and derives its encryption key.
	 *
	 * @param array<string, string> $credentials Driver, server, username, password, db.
	 * @return string
	 */
	private function build_adminer_token( array $credentials ) {
		$token      = \md5( \random_bytes( 32 ) );
		$creds_file = \sys_get_temp_dir() . \DIRECTORY_SEPARATOR . '.' . $token;
		$key        = \hash( 'sha256', $token, true );
		$iv         = \random_bytes( 16 );
		$json       = \wp_json_encode( $credentials );
		$cipher     = \openssl_encrypt( $json, 'aes-256-cbc', $key, \OPENSSL_RAW_DATA, $iv );

		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
		\file_put_contents( $creds_file, $iv . $cipher );

		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.chmod_chmod
		\chmod( $creds_file, 0600 );

		return $token;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @param Plugin $plugin Plugin.
	 * @return self
	 */
	public static function get_instance( Plugin $plugin ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin );
		}

		return self::$instance;
	}
}
