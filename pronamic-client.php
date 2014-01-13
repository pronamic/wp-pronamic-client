<?php
/*
Plugin Name: Pronamic Client
Plugin URI: http://pronamic.eu/wordpress/pronamic-client/
Description: WordPress plugin for Pronamic clients.

Version: 1.1.4
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_client
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-client
*/

$dir = dirname( __FILE__ );

require_once $dir . '/includes/credits.php';
require_once $dir . '/includes/dashboard.php';
require_once $dir . '/includes/functions.php';
require_once $dir . '/includes/version.php';
require_once $dir . '/classes/Pronamic/WP/ClientPlugin/Plugin.php';
require_once $dir . '/classes/Pronamic/WP/ClientPlugin/Updater.php';
require_once $dir . '/classes/Pronamic/WP/ClientPlugin/Admin.php';
require_once $dir . '/classes/Pronamic/WP/ClientPlugin/Extensions_API.php';

/**
 * Bootstrap
 */
Pronamic_WP_ClientPlugin_Plugin::get_instance( __FILE__ );
