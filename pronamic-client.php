<?php
/**
 * Pronamic Client
 *
 * @package   PronamicClient
 * @author    Pronamic
 * @copyright 2023 Pronamic
 * 
 * @wordpress-plugin
 * Plugin Name: Pronamic Client
 * Plugin URI: https://wp.pronamic.directory/plugins/pronamic-client/
 * Description: WordPress plugin for Pronamic clients.
 * Version: 2.0.1
 * Requires at least: 3.0
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pronamic-client
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoload.
 */
require __DIR__ . '/vendor/autoload_packages.php';

/**
 * Bootstrap
 */
\Pronamic\WordPress\PronamicClient\Plugin::get_instance( __FILE__ );

\Pronamic\WordPress\Updater\Plugin::instance()->setup();

\Pronamic\MollieUserAgent\Plugin::instance()->setup();
