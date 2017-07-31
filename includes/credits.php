<?php

/**
 * Get the credits
 *
 * @return string
 */
function pronamic_client_get_credits() {
	/* translators: %s: credits text */
	return sprintf( '<span id="pronamic-credits">%s</span>',
		/* translators: %s: Pronamic */
		sprintf( __( 'Concept and realisation by %s', 'pronamic_client' ),
			/* translators: 1: https://www.pronamic.eu/, 2: Pronamic specialist, 3: Pronamic */
			sprintf( '<a href="%1$s" title="%2$s" rel="developer">%3$s</a>',
				esc_attr( __( 'https://www.pronamic.eu/', 'pronamic_client' ) ),
				esc_attr( __( 'Pronamic - Internet, marketing & WordPress specialist', 'pronamic_client' ) ),
				__( 'Pronamic', 'pronamic_client' )
			)
		)
	);
}

/**
 * Credits
 */
function pronamic_client_credits() {
	echo pronamic_client_get_credits();
}

add_action( 'pronamic_credits', 'pronamic_client_credits' );
