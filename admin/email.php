<?php

/**
 * Email page.
 *
 * @link https://nl.wordpress.org/plugins/check-email/
 * @link https://plugins.trac.wordpress.org/browser/check-email/trunk/check-email.php
 */

?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<h2><?php esc_html_e( 'Current mail settings', 'pronamic_client' ); ?></h2>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SendMail path (UNIX)', 'pronamic_client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'sendmail_path' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SMTP server (Windows)', 'pronamic_client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'SMTP' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SMTP port (Windows)', 'pronamic_client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'smtp_port' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Add X-header', 'pronamic_client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'mail.add_x_header' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php

					printf(
						__( 'Function %s location', 'pronamic_client' ),
						'<code>wp_mail()</code>'
					);

					?>
				</th>
				<td>
					<?php

					$reflection_wp_mail = new ReflectionFunction( 'wp_mail' );

					printf(
						__( 'In %1$s on line %2$s.', 'pronamic_client' ),
						'<code>' . $reflection_wp_mail->getFileName() . '</code>',
						'<code>' . $reflection_wp_mail->getStartLine() . '</code>'
					);

					?>
				</td>
			</tr>
		</tbody>
	</table>

	<h2><?php esc_html_e( 'Send a test email', 'pronamic_client' ); ?></h2>

	<?php

	$message = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_STRING );

	if ( 'pronamic_client_test_email_sent_no' === $message ) :
		?>

		<div class="error">
			<p>
				<?php esc_html_e( 'The test email has not been sent by WordPress.', 'pronamic_client' ); ?>
			</p>
		</div>

		<?php
	endif;

	if ( 'pronamic_client_test_email_sent_yes' === $message ) :
		?>

		<div class="updated">
			<p>
				<?php esc_html_e( 'The test email has been sent by WordPress. Please note this does not mean it has been delivered.', 'pronamic_client' ); ?>
			</p>
		</div>

	<?php endif; ?>

	<p>
		<?php

		printf(
			__( 'Use a service like %s to test if this WordPress installation is sending emails.', 'pronamic_client' ),
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( 'https://www.mail-tester.com/' ),
				esc_html( 'mail-tester.com' )
			)
		);

		?>
	</p>

	<form method="post" action="">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pronamic_client_test_email_to"><?php esc_html_e( 'Send To', 'pronamic_client' ); ?></label>
					</th>
					<td>
						<input name="pronamic_client_test_email_to" type="email" id="pronamic_client_test_email_to" value="" class="regular-text" />
					</td>
				</tr>
			</tbody>
		</table>

		<?php

		/**
		 * Submit button.
		 *
		 * @link https://developer.wordpress.org/reference/functions/submit_button/
		 * @link https://plugins.trac.wordpress.org/browser/check-email/trunk/check-email.php#L177
		 */
		submit_button(
			__( 'Send test email', 'pronamic_client' ),
			'primary',
			'pronamic_client_send_test_email'
		);

		/**
		 * Nonce.
		 *
		 * @link https://developer.wordpress.org/reference/functions/wp_nonce_field/
		 */
		wp_nonce_field(
			'pronamic_client_send_test_email',
			'pronamic_client_send_test_email_nonce'
		);

		?>
	</form>

	<h2><?php esc_html_e( 'Tools', 'pronamic_client' ); ?></h2>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">
					<?php esc_html_e( 'Title', 'pronamic_client' ); ?>
				</th>
				<th scope="col">
					<?php esc_html_e( 'Link', 'pronamic_client' ); ?>
				</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'mail-tester.com', 'pronamic_client' ); ?>
				</th>
				<td>
					<a href="https://www.mail-tester.com/">https://www.mail-tester.com/</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Dig (DNS lookup) - G Suite toolbox', 'pronamic_client' ); ?>
				</th>
				<td>
					<a href="https://toolbox.googleapps.com/apps/dig/">https://toolbox.googleapps.com/apps/dig/</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SPF Record Testing Tools', 'pronamic_client' ); ?>
				</th>
				<td>
					<a href="https://www.kitterman.com/spf/validate.html">https://www.kitterman.com/spf/validate.html</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
