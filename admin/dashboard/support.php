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
