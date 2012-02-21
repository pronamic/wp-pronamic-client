<?php
/*
Plugin Name: Pronamic Client
Plugin URI: http://pronamic.eu/wordpress/pronamic-client/
Description: WordPress plugin for Pronamic Clients
Version: 0.1.1
Requires at least: 3.0
Author: Pronamic
Author URI: http://pronamic.eu/
*/

/**
 * Initialize the plugin
 */
function pronamic_client_init() {
	// Load plugin text domain
	$relPath = dirname(plugin_basename(__FILE__)) . '/languages/';

	load_plugin_textdomain('pronamic_client', false, $relPath);
}

add_action('init', 'pronamic_client_init');
	
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

/**
 * Menu
 */
function pronamic_client_menu() {
	add_menu_page(
		$pageTitle = __('Pronamic', 'pronamic_client') , 
		$menuTitle = __('Pronamic', 'pronamic_client') , 
		$capability = 'manage_options' , 
		$menuSlug = 'pronamic_client' , 
		$function = 'pronamic_client_page_index' , 
		$iconUrl = plugins_url('images/icon-16x16.png', __FILE__)
	);

	// @see _add_post_type_submenus()
	// @see wp-admin/menu.php
	add_submenu_page(
		$parentSlug = 'pronamic_client' , 
		$pageTitle = __('Checklist', 'pronamic_client') , 
		$menuTitle = __('Checklist', 'pronamic_client') , 
		$capability = 'manage_options' , 
		$menuSlug = 'pronamic_client_checklist' , 
		$function = 'pronamic_client_page_checklist'
	);

	// @see _add_post_type_submenus()
	// @see wp-admin/menu.php
	add_submenu_page(
		$parentSlug = 'pronamic_client' , 
		$pageTitle = __('Virus Scanner', 'pronamic_client') , 
		$menuTitle = __('Virus Scanner', 'pronamic_client') , 
		$capability = 'manage_options' , 
		$menuSlug = 'pronamic_client_virus_scanner' , 
		$function = 'pronamic_client_page_virus_scanner'
	);
}

add_action('admin_menu', 'pronamic_client_menu');

/**
 * Admin enqueue scripts
 * 
 * @param string $hook
 */
function pronamic_client_admin_enqueue_scripts($hook) {
	$isPronamicClient = strpos($hook, 'pronamic_client') !== false;
	
	if($isPronamicClient) {
		// Styles
		wp_enqueue_style(
			'proanmic_client_admin' , 
			plugins_url('css/admin.css', __FILE__)
		);
	}
}

add_action('admin_enqueue_scripts', 'pronamic_client_admin_enqueue_scripts');


/**
 * Admin bar menu
 */
function pronamic_client_admin_bar_menu() {
	global $wp_admin_bar;

	$wp_admin_bar->add_menu(array(
		'id' => 'pronamic' ,  
		'title' => __('Pronamic', 'pronamic_client') ,  
		'href' => __('http://pronamic.eu/', 'pronamic_client') , 
		'meta' => array('target' => '_blank')
	));

	$wp_admin_bar->add_menu(array(
		'parent' => 'pronamic' ,  
		'id' => 'pronamic_contact' , 
		'title' => __('Contact', 'pronamic_client') , 
		'href' => __('http://pronamic.eu/contact/', 'pronamic_client') ,  
		'meta' => array('target' => '_blank') 
	));
}

add_action('admin_bar_menu', 'pronamic_client_admin_bar_menu', 100);

/**
 * Extend the headers with a Pronamic powered by header
 * 
 * @param array $headers
 * @return array
 */
function pronamic_client_headers($headers) {
	$headers['X-Powered-By'] = _x('Pronamic | pronamic.eu | info@pronamic.eu', 'x-powered-by', 'pronamic_client');

	return $headers;
}

add_filter('wp_headers', 'pronamic_client_headers');

/**
 * Get the credits
 * 
 * @return string
 */
function pronamic_client_get_credits() {
	return sprintf('<span id="pronamic-credits">%s</span>', 
		sprintf(__('Concept and realisation by %s', 'pronamic_client') ,
			sprintf('<a href="%s" title="%s" rel="developer">%s</a>' , 
				esc_attr(__('http://pronamic.eu/', 'pronamic_client')) , 
				esc_attr(__('Pronamic - Internet, marketing & WordPress specialist', 'pronamic_client')) ,
				__('Pronamic', 'pronamic_client')
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

add_action('pronamic_credits', 'pronamic_client_credits');

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

add_filter('admin_footer_text', 'pronamic_client_admin_footer');

/**
 * Dashboard setup
 */
function pronamic_client_dashboard_setup() {
	wp_add_dashboard_widget(
		'pronamic_client' , 
		__('Pronamic', 'pronamic_client') , 
		'pronamic_client_dashboard' 
	);
}

add_action('wp_dashboard_setup', 'pronamic_client_dashboard_setup');

/**
 * Render dashboard
 */
function pronamic_client_dashboard() {
	?>
	<h4><?php _e('Support', 'pronamic_client'); ?></h4>

	<p> 
		<?php printf(__('Telephone: %s', 'pronamic_client'), sprintf('<a href="tel:+315164812000">%s</a>', __('+31 516 481 200', 'pronamic_client'))); ?><br />
		<?php printf(__('E-mail: %s', 'pronamic_client'), sprintf('<a href="mailto:%1$s">%1$s</a>', __('support@pronamic.eu', 'pronamic_client'))); ?><br />
		<?php printf(__('Website: %s', 'pronamic_client'), sprintf('<a href="%1$s">%1$s</a>', __('http://pronamic.eu/', 'pronamic_client'))); ?>
	</p> 

	<h4><?php _e('News', 'pronamic_client'); ?></h4>

	<?php

	wp_widget_rss_output('http://feeds.feedburner.com/pronamic', array(
		'link' => __('http://pronamic.eu/', 'pronamic_client') , 
		'url' => 'http://feeds.feedburner.com/pronamic' ,
		'title' => __('Pronamic News', 'pronamic_client') ,
		'items' => 5
	));
}
