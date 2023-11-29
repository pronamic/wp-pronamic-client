<?php

/**
 * Render dashboard
 */
function pronamic_client_dashboard() {
	?>
	<h3><?php esc_html_e( 'Support', 'pronamic-client' ); ?></h3>

	<p>
		<?php

		printf(
			/* translators: %s: e-mail link */
			__( 'E-mail: %s', 'pronamic-client' ),
			sprintf(
				'<a href="mailto:%1$s">%1$s</a>',
				__( 'support@pronamic.eu', 'pronamic-client' )
			)
		);

		echo '<br />';

		printf(
			/* translators: %s: website link */
			__( 'Website: %s', 'pronamic-client' ),
			sprintf(
				'<a href="%1$s">%1$s</a>',
				__( 'https://www.pronamic.eu/', 'pronamic-client' )
			)
		);

		?>
	</p>

	<h3><?php esc_html_e( 'News', 'pronamic-client' ); ?></h3>

	<?php

	wp_widget_rss_output(
		'https://feeds.feedburner.com/pronamic',
		[
			'link'  => __( 'https://www.pronamic.eu/', 'pronamic-client' ),
			'url'   => 'https://feeds.feedburner.com/pronamic',
			'title' => __( 'Pronamic News', 'pronamic-client' ),
			'items' => 5,
		]
	);
}
