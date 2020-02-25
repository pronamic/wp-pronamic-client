<?php

class Pronamic_WP_ClientPlugin_Tracking {
	/**
	 * Instance of this class.
	 *
	 * @since 1.4.0
	 *
	 * @var Pronamic_WP_ClientPlugin_Tracking
	 */
	protected static $instance = null;

	/**
	 * Plugin
	 *
	 * @var Pronamic_WP_ClientPlugin_Plugin
	 */
	private $plugin;

	/**
	 * Constructs and initialize updater
	 *
	 * @param Pronamic_WP_ClientPlugin_Plugin $plugin
	 */
	private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Init
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Admin
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		
		// GA
		if ( get_option( 'pronamic_client_google_analytics_id' ) ) {
			add_action( 'wp_head', array( $this, 'ga_header' ) );
		}

		// GTM
		if ( get_option( 'pronamic_client_google_tag_manager_container_id' ) ) {
			add_action( 'wp_head', array( $this, 'gtm_header' ) );
			add_action( 'wp_body_open', array( $this, 'gtm_body_open' ) );
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {
		register_setting(
			'pronamic_client',
			'pronamic_client_google_analytics_id',
			array(
				'type' => 'string',
			)
		);

		register_setting(
			'pronamic_client',
			'pronamic_client_google_tag_manager_container_id',
			array(
				'type' => 'string',
			)
		);
	}

	/**
	 * Admin initialize.
	 */
	public function admin_init() {
		// Section
		add_settings_section(
			'pronamic_client_tracking',
			__( 'Tracking', 'pronamic_client' ),
			array( $this, 'settings_section' ),
			'pronamic_client'
		);

		// GA Code
		add_settings_field(
			'pronamic_client_google_analytics_id',
			__( 'GA Code', 'pronamic_client' ),
			array( $this, 'input_text' ),
			'pronamic_client',
			'pronamic_client_tracking',
			array(
				'label_for' => 'pronamic_client_google_analytics_id',
				'classes'   => 'regular-text',
			)
		);

		// GTM Code
		add_settings_field(
			'pronamic_client_google_tag_manager_container_id',
			__( 'GTM Code', 'pronamic_client' ),
			array( $this, 'input_text' ),
			'pronamic_client',
			'pronamic_client_tracking',
			array(
				'label_for' => 'pronamic_client_google_tag_manager_container_id',
				'classes'   => 'regular-text',
				'description' => sprintf(
					/* translators: 1: hook */
					esc_html__( 'Your theme needs support for the %1$s hook.', 'pronamic_client' ),
					'<code>wp_body_open</code>'
				),
			)
		);
	}

	/**
	 * Settings section.
	 *
	 * @param array $args Arguments.
	 */
	public function settings_section( $args ) {
	}

	/**
	 * Input text.
	 *
	 * @param array $args Arguments.
	 */
	public function input_text( $args ) {
		$defaults = array(
			'type'        => 'text',
			'classes'     => 'regular-text',
			'description' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$name  = $args['label_for'];
		$value = get_option( $name );

		$atts = array(
			'name'  => $name,
			'id'    => $name,
			'type'  => $args['type'],
			'class' => $args['classes'],
			'value' => $value,
		);

		$html = '';

		foreach ( $atts as $key => $value ) {
			$html .= sprintf( '%s="%s" ', $key, esc_attr( $value ) );
		}

		$html = trim( $html );

		printf(
			'<input %s />',
			// @codingStandardsIgnoreStart
			$html
			// @codingStandardsIgnoreEn
		);

		if ( ! empty( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				wp_kses_post( $args['description'] )
			);
		}
	}

	/**
	 * Admin notices.
	 */
	public function admin_notices() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

		if ( 'pronamic_client_settings' !== $page ) {
			return;
		}

		$show_message = false;

		$plugins = array(
			'google-analytics-for-wordpress/googleanalytics.php',
			'wk-google-analytics/wk-ga.php',
		);

		foreach ( $plugins as $plugin ) {
			if ( is_plugin_active( $plugin ) ) {
				$show_message = $plugin;

				break;
			}
		}

		if ( ! $show_message ) {
			return;
		}

		printf(
			'<div class="notice notice-warning is-dismissible">%1$s</div>',
			esc_html__( 'There is already an analytics plugin active.', 'pronamic_client' )
		);
	}

	/**
	 * GA header.
	 */
	public function ga_header() {
		if ( ! get_option( 'pronamic_client_google_analytics_id' ) ) {
			return;
		}

		?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( get_option( 'pronamic_client_google_analytics_id' ) ); ?>"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', '<?php echo esc_attr( get_option( 'pronamic_client_google_analytics_id' ) ); ?>');
		</script>

		<?php
	}

	/**
	 * GTM header.
	 */
	public function gtm_header() {
		if ( ! get_option( 'pronamic_client_google_tag_manager_container_id' ) ) {
			return;
		}

		?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo esc_attr( get_option( 'pronamic_client_google_tag_manager_container_id' ) ); ?>');</script>
		<!-- End Google Tag Manager -->

		<?php
	}

	/**
	 * GTM body open.
	 */
	public function gtm_body_open() {
		if ( ! get_option( 'pronamic_client_google_tag_manager_container_id' ) ) {
			return;
		}

		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( get_option( 'pronamic_client_google_tag_manager_container_id' ) ); ?>"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->

		<?php
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
