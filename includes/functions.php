<?php

/**
 * Get Pronamic plugins
 *
 * @link https://developer.wordpress.org/reference/functions/get_plugins/
 * @return array|false
 */
function pronamic_client_get_plugins() {
	if ( ! function_exists( 'get_plugins' ) ) {
		return false;
	}

	$pronamic_plugins = [];

	$plugins = get_plugins();

	foreach ( $plugins as $file => $plugin ) {
		if ( isset( $plugin['Author'] ) && false !== strpos( $plugin['Author'], 'Pronamic' ) ) {
			$pronamic_plugins[ $file ] = $plugin;
		}
	}

	return $pronamic_plugins;
}

/**
 * Get Pronamic themes
 *
 * @return array
 */
function pronamic_client_get_themes() {
	if ( ! function_exists( 'wp_get_themes' ) ) {
		return false;
	}

	$pronamic_themes = [];

	$themes = wp_get_themes();

	foreach ( $themes as $slug => $theme ) {
		if ( isset( $theme['Author'] ) && false !== strpos( $theme['Author'], 'Pronamic' ) ) {
			$pronamic_themes[ $slug ] = $theme;
		}
	}

	return $pronamic_themes;
}
