<?php

/**
 * Render dashboard
 */
function pronamic_client_dashboard() {
	?>
	<h3><?php esc_html_e( 'Support', 'pronamic_client' ); ?></h3>

	<p>
		<?php

		printf(
			/* translators: %s: e-mail link */
			__( 'E-mail: %s', 'pronamic_client' ),
			sprintf(
				'<a href="mailto:%1$s">%1$s</a>',
				__( 'support@pronamic.eu', 'pronamic_client' )
			)
		);

		echo '<br />';

		printf(
			/* translators: %s: website link */
			__( 'Website: %s', 'pronamic_client' ),
			sprintf(
				'<a href="%1$s">%1$s</a>',
				__( 'https://www.pronamic.eu/', 'pronamic_client' )
			)
		);

		?>
	</p>

	<h3><?php esc_html_e( 'News', 'pronamic_client' ); ?></h3>

	<?php

	wp_widget_rss_output( 'https://feeds.feedburner.com/pronamic', array(
		'link'  => __( 'https://www.pronamic.eu/', 'pronamic_client' ),
		'url'   => 'https://feeds.feedburner.com/pronamic',
		'title' => __( 'Pronamic News', 'pronamic_client' ),
		'items' => 5,
	) );
}
