<?php
/*
Plugin Name: Pronamic Client
Plugin URI: http://www.happywp.com/plugins/pronamic-client/
Description: WordPress plugin for Pronamic clients.

Version: 1.2.4
Requires at least: 3.0

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_client
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-client
*/

$dir = plugin_dir_path( __FILE__ );

require_once $dir . 'classes/Pronamic/WP/ClientPlugin/Plugin.php';
require_once $dir . 'classes/Pronamic/WP/ClientPlugin/Updater.php';
require_once $dir . 'classes/Pronamic/WP/ClientPlugin/Admin.php';

/**
 * Bootstrap
 */
Pronamic_WP_ClientPlugin_Plugin::get_instance( __FILE__ );
