<?php
/**
 * Plugin Name: Pronamic Client
 * Plugin URI: https://wp.pronamic.directory/plugins/pronamic-client/
 * Description: WordPress plugin for Pronamic clients.
 *
 * Version: 2.0.3
 * Requires at least: 3.0
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic-client
 * Domain Path: /languages/
 *
 * License: GPL-2.0-or-later
 *
 * GitHub URI: https://github.com/pronamic/wp-pronamic-client
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2024 Pronamic
 * @license   GPL-2.0-or-later
 * @package   PronamicClient
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
