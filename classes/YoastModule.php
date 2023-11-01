<?php

namespace Pronamic\WordPress\PronamicClient;

class YoastModule {
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

		\add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Plugins loaded.
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		if ( ! \defined( 'WPSEO_VERSION' ) ) {
			return;
		}

		if ( ! \defined( 'PRONAMIC_CLIENT_YOAST_URL' ) ) {
			\define( 'PRONAMIC_CLIENT_YOAST_URL', 'https://yoast.com' );
		}

		\add_filter( 'http_request_args', [ $this, 'http_request_args' ], 1000, 2 );

		if ( \is_admin() ) {
			\add_action( 'current_screen', [ $this, 'current_screen' ] );
		}
	}

	/**
	 * Current screen.
	 *
	 * @since 1.9.2
	 * @link https://developer.wordpress.org/reference/hooks/current_screen/
	 * @param \WP_Screen $screen Screen.
	 * @return void
	 */
	public function current_screen( $screen ) {
		if ( ! in_array(
			$screen->base,
			[
				'post',
				'seo_page_wpseo_tools',
				'seo_page_wpseo_workouts',
				'toplevel_page_wpseo_dashboard',
				'wpseo_tools',
			],
			true
		) ) {
			return;
		}

		\add_action( 'admin_print_scripts', [ $this, 'admin_print_scripts' ] );
	}

	/**
	 * HTTP request arguments.
	 *
	 * @param array  $parsed_args Arguments.
	 * @param string $url         URL.
	 * @return array
	 */
	public function http_request_args( $parsed_args, $url ) {
		if ( ! \str_starts_with( $url, 'https://my.yoast.com/' ) ) {
			return $parsed_args;
		}

		if ( ! \array_key_exists( 'body', $parsed_args ) ) {
			return $parsed_args;
		}

		if ( ! \is_array( $parsed_args['body'] ) ) {
			return $parsed_args;
		}

		if ( ! \array_key_exists( 'url', $parsed_args['body'] ) ) {
			return $parsed_args;
		}

		$parsed_args['body']['url'] = PRONAMIC_CLIENT_YOAST_URL;

		return $parsed_args;
	}

	/**
	 * Admin print scripts.
	 *
	 * @since 1.9.1
	 * @link https://stackoverflow.com/questions/7775767/javascript-overriding-xmlhttprequest-open
	 * @link https://developer.mozilla.org/en-US/docs/Web/API/URL
	 * @link https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams
	 * @link https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Functions/arguments
	 */
	public function admin_print_scripts() {
		?>
		<script type="text/javascript">
			( function( open ) {
				XMLHttpRequest.prototype.open = function( method, url ) {
					if ( url.startsWith( 'https://my.yoast.com/' ) ) {
						const url_object = new URL( url );

						url_object.searchParams.set( 'site', <?php echo \wp_json_encode( PRONAMIC_CLIENT_YOAST_URL ); ?> );

						arguments[1] = url_object.toString();
					}

					return open.apply( this, arguments );
				}
			} )( XMLHttpRequest.prototype.open );
		</script>
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
