<?php

/**
 * Get Pronamic plugins
 *
 * @return array
 */
function pronamic_client_get_plugins() {
	$pronamic_plugins = array();

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
	$pronamic_themes = array();

	$themes = wp_get_themes();

	foreach ( $themes as $slug => $theme ) {
		if ( isset( $theme['Author'] ) && false !== strpos( $theme['Author'], 'Pronamic' ) ) {
			$pronamic_themes[ $slug ] = $theme;
		}
	}

	return $pronamic_themes;
}
