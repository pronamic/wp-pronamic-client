<?php
/*
Plugin Name: Pronamic Client
Plugin URI: http://pronamic.eu/wordpress/pronamic-client/
Description: WordPress plugin for Pronamic Clients
Version: 0.1
Requires at least: 3.0
Author: Pronamic
Author URI: http://pronamic.eu/
*/

function pronamicClientPage() {
	include 'admin/page.php';
}

function pronamicClientMenu() {
	add_menu_page(
		$pageTitle = 'Pronamic' , 
		$menuTitle = 'Pronamic' , 
		$capability = 'manage_options' , 
		$menuSlug = 'pronamic_client' , 
		$function = 'pronamicClientPage' , 
		$iconUrl = plugins_url('images/icon-16x16.png', __FILE__)
	);
}

add_action('admin_menu', 'pronamicClientMenu');