<?php
/*
Plugin Name: Pronamic Client
Plugin URI: http://pronamic.eu/wordpress/pronamic-client/
Description: WordPress plugin for Pronamic clients.

Version: 1.0.0
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_client
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-client
*/

require_once dirname( __FILE__ ) . '/includes/version.php';

/**
 * Initialize the plugin
 */
function pronamic_client_init() {
	// Load plugin text domain
	$relPath = dirname( plugin_basename( __FILE__ ) ) . '/languages/';

	load_plugin_textdomain( 'pronamic_client', false, $relPath );
}

add_action('init', 'pronamic_client_init');

function pronamic_client_transient_update_plugins_filter( $transient ) {
	if ( empty( $transient->checked ) )
		return $transient;

	$pronamic_plugins = pronamic_client_get_plugins();
//var_dump($pronamic_plugins);
//exit;
	foreach ( $pronamic_plugins as $file => $plugin ) {
		$slug = pathinfo( $file, PATHINFO_DIRNAME );
		$slug = $plugin['Name'];

		if ( $slug !== 'pronamic-updater-test' )
			continue;

		$api_url = 'http://themes.pronamic.nl/2.0/';
		$api_url = add_query_arg( 'slug', $slug, $api_url );

		$response = wp_remote_get( $api_url );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != '200' ) {
			break;
		}
		
		$data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $data ) {
			$local_version  = $plugin['Version'];
			$remote_version = $data->version;
			
			if ( version_compare( $remote_version, $local_version, '>' ) ) {
				$plugin = array(
					'slug'        => $slug,
					'new_version' => $remote_version,
					'url'         => null,
					'package'     => $data->download_link,
				);

				$transient->response[$file] = (object) $plugin;
			}
		}
	}
	
	return $transient;
}

//add_filter( 'pre_set_site_transient_update_themes', 'pronamic_client_transient_update_themes_filter' );
add_filter( 'pre_set_site_transient_update_plugins', 'pronamic_client_transient_update_plugins_filter' );

function pronamic_client_get_plugins() {
	// @see https://github.com/afragen/github-updater/blob/1.7.4/classes/class-plugin-updater.php#L68
	$plugins = get_plugins();

	$pronamic_plugins = array();
	
	foreach ( $plugins as $file => $plugin ) {
		if ( isset( $plugin['Author'] ) && $plugin['Author'] == 'Pronamic' ) {
			$pronamic_plugins[$file] = $plugin;
		}
	}
	
	return $pronamic_plugins;
}

function pronamic_client_get_themes() {
	$themes = wp_get_themes();

	$pronamic_themes = array();
	
	foreach ( $themes as $slug => $theme ) {
		if ( isset( $theme['Author'] ) && $theme['Author'] == 'Pronamic' ) {
			$pronamic_themes[$slug] = $theme;
		}
	}
	
	return $pronamic_themes;
}

/**
 * Page index
 */
function pronamic_client_page_index() {
	include 'admin/index.php';
}

/**
 * Page virus scanner
 */
function pronamic_client_page_virus_scanner() {
	include 'admin/virus-scanner.php';
}

/**
 * Page checklist
 */
function pronamic_client_page_checklist() {
	include 'admin/checklist.php';
}

function pronamic_updater_page() {
	include 'admin/updater.php';
}

/**
 * Menu
 */
function pronamic_client_menu() {
	add_menu_page(
		__( 'Pronamic', 'pronamic_client' ), // page title
		__( 'Pronamic', 'pronamic_client' ), // menu title
		'pronamic_client', // capability
		'pronamic_client', // menu slug
		'pronamic_client_page_index', // function
		plugins_url( 'images/icon-16x16.png', __FILE__ ) // icon URL
		// 0 // position
	);

	// @see _add_post_type_submenus()
	// @see wp-admin/menu.php
	add_submenu_page(
		'pronamic_client', // parent slug
		__( 'Checklist', 'pronamic_client' ), // page title
		__( 'Checklist', 'pronamic_client' ), // menu title
		'pronamic_client', // capability
		'pronamic_client_checklist', // menu slug
		'pronamic_client_page_checklist' // function
	);

	// @see _add_post_type_submenus()
	// @see wp-admin/menu.php
	add_submenu_page(
		'pronamic_client', // parent slug
		__('Virus Scanner', 'pronamic_client' ), // page title
		__('Virus Scanner', 'pronamic_client' ), // menu title
		'pronamic_client', // capability
		'pronamic_client_virus_scanner', // menu slug
		'pronamic_client_page_virus_scanner' // function
	);
	
	$hook = add_dashboard_page(
		__( 'Pronamic Updates', 'pronamic_client' ),
		__( 'Pronamic Updates', 'pronamic_client' ),
		'manage_options',
		'pronamic_updater',
		'pronamic_updater_page'
	);
}

add_action( 'admin_menu', 'pronamic_client_menu' );

/**
 * Admin enqueue scripts
 *
 * @param string $hook
 */
function pronamic_client_admin_enqueue_scripts( $hook ) {
	$isPronamicClient = strpos( $hook, 'pronamic_client' ) !== false;

	if ( $isPronamicClient ) {
		// Styles
		wp_enqueue_style(
			'proanmic_client_admin' ,
			plugins_url('css/admin.css', __FILE__)
		);
	}
}

add_action( 'admin_enqueue_scripts', 'pronamic_client_admin_enqueue_scripts' );


/**
 * Admin bar menu
 */
function pronamic_client_admin_bar_menu() {
	if ( current_user_can( 'pronamic_client' ) ) {
		global $wp_admin_bar;

		$wp_admin_bar->add_menu( array(
			'id'    => 'pronamic' ,
			'title' => __( 'Pronamic', 'pronamic_client' ),
			'href'  => __( 'http://pronamic.eu/', 'pronamic_client' ),
			'meta'  => array( 'target' => '_blank' )
		) );

		$wp_admin_bar->add_menu(array(
			'parent' => 'pronamic',
			'id'     => 'pronamic_contact',
			'title'  => __( 'Contact', 'pronamic_client'),
			'href'   => __( 'http://pronamic.eu/contact/', 'pronamic_client' ),
			'meta'   => array( 'target' => '_blank' )
		) );
	}
}

add_action( 'admin_bar_menu', 'pronamic_client_admin_bar_menu', 100 );

/**
 * Extend the headers with a Pronamic powered by header
 *
 * @param array $headers
 * @return array
 */
function pronamic_client_headers($headers) {
	$headers['X-Powered-By'] = _x( 'Pronamic | pronamic.eu | info@pronamic.eu', 'x-powered-by', 'pronamic_client' );

	return $headers;
}

add_filter( 'wp_headers', 'pronamic_client_headers' );

/**
 * Get the credits
 *
 * @return string
 */
function pronamic_client_get_credits() {
	return sprintf( '<span id="pronamic-credits">%s</span>',
		sprintf( __( 'Concept and realisation by %s', 'pronamic_client' ),
			sprintf( '<a href="%s" title="%s" rel="developer">%s</a>',
				esc_attr( __( 'http://pronamic.eu/', 'pronamic_client' ) ),
				esc_attr( __( 'Pronamic - Internet, marketing & WordPress specialist', 'pronamic_client' ) ),
				__( 'Pronamic', 'pronamic_client' )
			)
		)
	);
}

/**
 * Credits
 */
function pronamic_client_credits() {
	echo pronamic_client_get_credits();
}

add_action( 'pronamic_credits', 'pronamic_client_credits' );

/**
 * Extend the admin footer text with a Pronamic text
 *
 * @param string $text
 * @return string
 */
function pronamic_client_admin_footer($text) {
	$text .= ' | ' . pronamic_client_get_credits();

	return $text;
}

add_filter( 'admin_footer_text', 'pronamic_client_admin_footer' );

/**
 * Dashboard setup
 */
function pronamic_client_dashboard_setup() {
	wp_add_dashboard_widget(
		'pronamic_client',
		__( 'Pronamic', 'pronamic_client' ),
		'pronamic_client_dashboard'
	);
}

add_action( 'wp_dashboard_setup', 'pronamic_client_dashboard_setup' );

/**
 * Render dashboard
 */
function pronamic_client_dashboard() {
	?>
	<h3><?php _e( 'Support', 'pronamic_client' ); ?></h3>

	<p>
		<?php printf( __( 'Telephone: %s', 'pronamic_client' ), sprintf( '<a href="tel:+31854011580">%s</a>', __( '+31 (0)85 40 11 580', 'pronamic_client' ) ) ); ?><br />
		<?php printf( __( 'E-mail: %s', 'pronamic_client' ), sprintf( '<a href="mailto:%1$s">%1$s</a>', __( 'support@pronamic.eu', 'pronamic_client' ) ) ); ?><br />
		<?php printf( __( 'Website: %s', 'pronamic_client' ), sprintf( '<a href="%1$s">%1$s</a>', __( 'http://pronamic.eu/', 'pronamic_client' ) ) ); ?>
	</p>

	<h3><?php _e( 'News', 'pronamic_client' ); ?></h3>

	<?php

	wp_widget_rss_output( 'http://feeds.feedburner.com/pronamic', array(
		'link'  => __( 'http://pronamic.eu/', 'pronamic_client' ),
		'url'   => 'http://feeds.feedburner.com/pronamic',
		'title' => __( 'Pronamic News', 'pronamic_client' ),
		'items' => 5
	) );
}

/**
 * Admin initialize
 */
function pronamic_client_admin_init() {
	// Maybe update
	global $pronamic_client_db_version;

	if ( get_option( 'pronamic_client_db_version' ) != $pronamic_client_db_version ) {
		pronamic_client_upgrade();

		update_option( 'pronamic_client_db_version', $pronamic_client_db_version );
	}
}

add_action( 'admin_init', 'pronamic_client_admin_init' );

/**
 * Upgrade
 */
function pronamic_client_upgrade() {
	global $wp_roles;

	$wp_roles->add_cap( 'administrator', 'pronamic_client' );
	$wp_roles->add_cap( 'editor', 'pronamic_client' );
}
