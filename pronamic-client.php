<?php
/*
Plugin Name: Pronamic Client
Plugin URI: https://www.pronamic.eu/plugins/pronamic-client/
Description: WordPress plugin for Pronamic clients.

Version: 1.9.7
Requires at least: 3.0

Author: Pronamic
Author URI: http://www.pronamic.eu/

Text Domain: pronamic_client
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-client
*/

require __DIR__ . '/vendor/autoload.php';

/**
 * Bootstrap
 */
\Pronamic\WordPress\PronamicClient\Plugin::get_instance( __FILE__ );
