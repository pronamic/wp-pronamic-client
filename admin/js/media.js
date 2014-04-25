jQuery( document ).ready( function( $ ) {
	var frame;

	$( '.pronamic-media-picker' ).each( function() {
		var $this = $( this );

		var selectLink = $( '<a />' ).text( pronamicClientMedia.browseText );

		$this.after(selectLink);
		$this.after( ' ' );

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
				title: pronamicClientMedia.browseText,

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

				$this.val( attachment.attributes.url );

				frame.close();
			} );

			// Finally, open the modal.
			frame.open();
		} );
	} );
} );
