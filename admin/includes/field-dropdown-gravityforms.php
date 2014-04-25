<?php

if ( ! function_exists( 'pronamic_field_dropdown_gravityforms' ) ) {
	/**
	 * Field dropdown Gravity Forms
	 *
	 * @param array $args
	 */
	function pronamic_field_dropdown_gravityforms( $args ) {
		$name = $args['label_for'];

		$forms = array();

		if ( method_exists( 'RGFormsModel', 'get_forms' ) ) {
			$forms = RGFormsModel::get_forms();
		}

		if ( empty( $forms ) ) {
			_e( 'You don\'t have any Gravity Forms forms.', 'pronamic_client' );
		} else {
			$form_id = get_option( $name );

			printf( '<select name="%s" id="%s">', $name, $name );

			printf(
				'<option value="%s" %s>%s</option>',
				'',
				selected( $form_id, '', false ),
				__( '&mdash; Select a form &mdash;', 'pronamic_client' )
			);

			foreach ( $forms as $form ) {
				printf( '<option value="%s" %s>%s</option>', $form->id, selected( $form_id, $form->id, false ), $form->title );
			}

			printf( '</select>' );
		}
	}
}
