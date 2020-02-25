<?php

class Pronamic_WP_ClientPlugin_Settings {
	/**
	 * Input text.
	 *
	 * @param array $args Arguments.
	 */
	public static function input_text( $args ) {
		$defaults = array(
			'type'        => 'text',
			'classes'     => 'regular-text',
			'description' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$name  = $args['label_for'];
		$value = get_option( $name );

		$atts = array(
			'name'  => $name,
			'id'    => $name,
			'type'  => $args['type'],
			'class' => $args['classes'],
			'value' => $value,
		);

		$html = '';

		foreach ( $atts as $key => $value ) {
			$html .= sprintf( '%s="%s" ', $key, esc_attr( $value ) );
		}

		$html = trim( $html );

		printf(
			'<input %s />',
			// @codingStandardsIgnoreStart
			$html
			// @codingStandardsIgnoreEn
		);

		if ( ! empty( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				wp_kses(
					$args['description'],
					array(
						'br'   => array(),
						'code' => array()
					)
				)
			);
		}
	}
}
