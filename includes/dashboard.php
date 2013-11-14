<?php

/**
 * Render dashboard
 */
function pronamic_client_dashboard() {
	?>
	<h3><?php _e( 'Support', 'pronamic_client' ); ?></h3>

	<p>
		<?php printf( __( 'Telephone: %s', 'pronamic_client' ), sprintf( '<a href="tel:+31854011580">%s</a>', __( '+31 (0)85 40 11 580', 'pronamic_client' ) ) ); ?><br />
		<?php printf( __( 'E-mail: %s', 'pronamic_client' ), sprintf( '<a href="mailto:%1$s">%1$s</a>', __( 'support@pronamic.eu', 'pronamic_client' ) ) ); ?><br />
		<?php printf( __( 'Website: %s', 'pronamic_client' ), sprintf( '<a href="%1$s">%1$s</a>', __( 'http://pronamic.eu/', 'pronamic_client' ) ) ); ?>
	</p>

	<h3><?php _e( 'News', 'pronamic_client' ); ?></h3>

	<?php

	wp_widget_rss_output( 'http://feeds.feedburner.com/pronamic', array(
		'link'  => __( 'http://pronamic.eu/', 'pronamic_client' ),
		'url'   => 'http://feeds.feedburner.com/pronamic',
		'title' => __( 'Pronamic News', 'pronamic_client' ),
		'items' => 5,
	) );
}
