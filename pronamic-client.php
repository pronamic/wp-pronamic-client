<?php
/*
Plugin Name: Pronamic Client
Plugin URI: http://www.pronamic.eu/plugins/pronamic-client/
Description: WordPress plugin for Pronamic clients.

Version: 1.3.0
Requires at least: 3.0

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_client
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-client
*/

$dir = plugin_dir_path( __FILE__ );

require_once $dir . 'includes/class-plugin.php';
require_once $dir . 'includes/class-updater.php';
require_once $dir . 'includes/class-admin.php';

/**
 * Bootstrap
 */
Pronamic_WP_ClientPlugin_Plugin::get_instance( __FILE__ );
