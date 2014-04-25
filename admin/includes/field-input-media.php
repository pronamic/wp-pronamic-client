<?php

if ( ! function_exists( 'pronamic_field_input_media' ) ) {
	/**
	 * Field dropdown pages
	 *
	 * @param array $args
	 */
	function pronamic_field_input_media( $args ) {
		wp_enqueue_media();

		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" data-frame-title="%s" data-button-text="%s" data-library-type="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text code pronamic-media-picker',
			__( 'Select Media', 'pronamic_client' ),
			__( 'Select', 'pronamic_client' ),
			''
		);

		?>
		<script type="text/javascript">
			( function( $ ) {
				$( document ).ready( function() {
					var frame;

					$( '.pronamic-media-picker' ).each( function() {
						var $this = $( this );

						var selectLink = $( '<a />' ).text( 'Select media' );

						$this.after(selectLink);

						selectLink.click( function( event ) {
							var $el = $( this );

							event.preventDefault();

							// If the media frame already exists, reopen it.
							if ( frame ) {
								frame.open();
								return;
							}

							// Create the media frame.
							frame = wp.media.frames.projectAgreement = wp.media( {
								// Set the title of the modal.
								title: $el.data( 'choose' ),

								// Tell the modal to show only images.
								library: {
									type: $this.data( 'library-type' ),
								},

								// Customize the submit button.
								button: {
									// Set the text of the button.
									text: $this.data( 'button-text' ),
									// Tell the button not to close the modal, since we're
									// going to refresh the page when the image is selected.
									close: false
								}
							} );

							// When an image is selected, run a callback.
							frame.on( 'select', function() {
								// Grab the selected attachment.
								var attachment = frame.state().get( 'selection' ).first();

								$this.val( attachment.id );

								frame.close();
							} );

							// Finally, open the modal.
							frame.open();
						} );
					} );
				} );
			} )( jQuery );
		</script>
		<?php
	}
}
