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

require_once dirname( __FILE__ ) . '/includes/credits.php';
require_once dirname( __FILE__ ) . '/includes/dashboard.php';
require_once dirname( __FILE__ ) . '/includes/functions.php';
require_once dirname( __FILE__ ) . '/includes/version.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic/WP/ClientPlugin/Plugin.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic/WP/ClientPlugin/Updater.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic/WP/ClientPlugin/Admin.php';

/**
 * Bootstrap
 */
Pronamic_WP_ClientPlugin_Plugin::get_instance( __FILE__ );
