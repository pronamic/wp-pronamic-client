<?php

if ( ! function_exists( 'pronamic_field_textarea' ) ) {
	/**
	 * Field dropdown pages
	 *
	 * @param array $args
	 */
	function pronamic_field_textarea( $args ) {
		printf(
			'<textarea name="%s" id="%s" class="%s" rows="10" cols="60">%s</textarea>',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			'regular-text code',
			esc_textarea( get_option( $args['label_for'] ) )
		);
	}
}
