<?php

/**
 * Settings fields callbacks
 * https://github.com/pronamic/wp-pronamic-framework/blob/develop/includes/settings-fields.php
 *
 * @package Pronamic Framework
 * @since 1.5.0
 */

if ( ! function_exists( 'pronamic_field_input_text' ) ) {
	/**
	 * Field dropdown pages
	 *
	 * @param array $args
	 */
	function pronamic_field_input_text( $args ) {
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text'
		);

		if ( isset( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				$args['description']
			);
		}
	}
}
