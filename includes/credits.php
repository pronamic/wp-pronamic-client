<?php

/**
 * Get the credits
 *
 * @return string
 */
function pronamic_client_get_credits() {
	return sprintf(
		/* translators: %s: credits text */
		'<span id="pronamic-credits">%s</span>',
		sprintf(
			/* translators: %s: Pronamic */
			__( 'Concept and realisation by %s', 'pronamic_client' ),
			sprintf(
				/* translators: 1: https://www.pronamic.eu/, 2: Pronamic specialist, 3: Pronamic */
				'<a href="%1$s" title="%2$s" rel="developer">%3$s</a>',
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
