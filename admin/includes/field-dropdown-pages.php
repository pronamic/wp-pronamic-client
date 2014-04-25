<?php

if ( ! function_exists( 'pronamic_field_dropdown_pages' ) ) {
	/**
	 * Field dropdown pages
	 *
	 * @param array $args
	 */
	function pronamic_field_dropdown_pages( $args ) {
		$name = $args['label_for'];

		wp_dropdown_pages( array(
			'name'             => $name,
			'selected'         => get_option( $name, '' ),
			'show_option_none' => __( '&mdash; Select a page &mdash;', 'pronamic_client' ),
		) );
	}
}
