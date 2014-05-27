<?php

if ( ! function_exists( 'pronamic_field_input_url' ) ) {
	/**
	 * Field dropdown pages
	 *
	 * @param array $args
	 */
	function pronamic_field_input_url( $args ) {
		wp_enqueue_media();
		wp_enqueue_script( 'proanmic-client-media' );

		printf(
			'<input name="%s" id="%s" type="url" value="%s" class="%s" data-frame-title="%s" data-button-text="%s" data-library-type="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text code pronamic-media-picker',
			__( 'Select Media', 'pronamic_client' ),
			__( 'Select', 'pronamic_client' ),
			''
		);
	}
}
