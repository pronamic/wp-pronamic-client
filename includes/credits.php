<?php

/**
 * Get the credits
 *
 * @return string
 */
function pronamic_client_get_credits() {
	return sprintf( '<span id="pronamic-credits">%s</span>',
		sprintf( __( 'Concept and realisation by %s', 'pronamic_client' ),
			sprintf( '<a href="%s" title="%s" rel="developer">%s</a>',
				esc_attr( __( 'http://pronamic.eu/', 'pronamic_client' ) ),
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
