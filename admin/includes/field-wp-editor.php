<?php

if ( ! function_exists( 'pronamic_field_wp_editor' ) ) {
	/**
	 * Field WordPress editor
	 *
	 * @param array $args
	 */
	function pronamic_field_wp_editor( $args ) {
		$name = $args['label_for'];

		wp_editor( get_option( $name, '' ), $name );
	}
}
