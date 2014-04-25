<?php

if ( ! function_exists( 'pronamic_field_input_color' ) ) {
	/**
	 * Field dropdown pages
	 *
	 * @param array $args
	 */
	function pronamic_field_input_color( $args ) {
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'code pronamic-color-picker'
		);

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( '.pronamic-color-picker' ).wpColorPicker();
			} );
		</script>
		<?php
	}
}
