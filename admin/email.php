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

	<h2><?php esc_html_e( 'Current mail settings', 'pronamic-client' ); ?></h2>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SendMail path (UNIX)', 'pronamic-client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'sendmail_path' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SMTP server (Windows)', 'pronamic-client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'SMTP' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SMTP port (Windows)', 'pronamic-client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'smtp_port' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Add X-header', 'pronamic-client' ); ?>
				</th>
				<td>
					<code><?php echo esc_html( ini_get( 'mail.add_x_header' ) ); ?></code>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php

					printf(
						/* translators: %s: <code>wp_mail()</code> */
						__( 'Function %s location', 'pronamic-client' ),
						'<code>wp_mail()</code>'
					);

					?>
				</th>
				<td>
					<?php

					$reflection_wp_mail = new ReflectionFunction( 'wp_mail' );

					printf(
						/* translators: 1: filename, 2: line */
						__( 'In %1$s on line %2$s.', 'pronamic-client' ),
						'<code>' . $reflection_wp_mail->getFileName() . '</code>',
						'<code>' . $reflection_wp_mail->getStartLine() . '</code>'
					);

					?>
				</td>
			</tr>
		</tbody>
	</table>

	<h2><?php esc_html_e( 'Send a test email', 'pronamic-client' ); ?></h2>

	<?php

	// phpcs:ignore WordPress.Security.NonceVerification
	$message = array_key_exists( 'message', $_GET ) ? \sanitize_text_field( \wp_unslash( $_GET['message'] ) ) : '';

	if ( 'pronamic_client_test_email_sent_no' === $message ) :
		?>

		<div class="error">
			<p>
				<?php esc_html_e( 'The test email has not been sent by WordPress.', 'pronamic-client' ); ?>
			</p>
		</div>

		<?php
	endif;

	if ( 'pronamic_client_test_email_sent_yes' === $message ) :
		?>

		<div class="updated">
			<p>
				<?php esc_html_e( 'The test email has been sent by WordPress. Please note this does not mean it has been delivered.', 'pronamic-client' ); ?>
			</p>
		</div>

	<?php endif; ?>

	<p>
		<?php

		printf(
			/* translators: %s: <a href="https://www.mail-tester.com">mail-tester.com</a> */
			__( 'Use a service like %s to test if this WordPress installation is sending emails.', 'pronamic-client' ),
			sprintf(
				'<a href="%s">%s</a>',
				esc_url( 'https://www.mail-tester.com/' ),
				esc_html( 'mail-tester.com' )
			)
		);

		?>
	</p>

	<form method="post" action="">
		<?php

		$email = (object) [
			'from'    => '',
			'to'      => '',
			'subject' => \sprintf(
				/* translators: %s: site url */
				\__( 'Test email from %s', 'pronamic-client' ),
				\get_bloginfo( 'url' )
			),
			'message' => \sprintf(
				/* translators: %s: site url */
				__( 'This test email proves that your WordPress installation at %s can send emails.', 'pronamic-client' ),
				\get_bloginfo( 'url' )
			),
			'headers' => [],
		];

		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pronamic_client_test_email_to"><?php esc_html_e( 'Send To', 'pronamic-client' ); ?></label>
					</th>
					<td>
						<input name="pronamic_client_test_email[to]" type="email" id="pronamic_client_test_email_to" value="<?php echo \esc_attr( $email->to ); ?>" class="regular-text" required="required"/>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_client_test_email_from"><?php esc_html_e( 'From Email', 'pronamic-client' ); ?></label>
					</th>
					<td>
						<input name="pronamic_client_test_email[from]" type="email" id="pronamic_client_test_email_from" value="<?php echo \esc_attr( $email->from ); ?>" class="regular-text" />

						<p class="description"><?php \esc_html_e( 'Leave empty to use WordPress default sender.', 'pronamic-client' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_client_test_email_subject"><?php esc_html_e( 'Subject', 'pronamic-client' ); ?></label>
					</th>
					<td>
						<input name="pronamic_client_test_email[subject]" type="text" id="pronamic_client_test_email_subject" value="<?php echo \esc_attr( $email->subject ); ?>" class="regular-text" required="required" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_client_test_email_message"><?php esc_html_e( 'Message', 'pronamic-client' ); ?></label>
					</th>
					<td>
						<textarea name="pronamic_client_test_email[message]" id="pronamic_client_test_email_message" cols="60" rows="4" required="required"><?php echo \esc_textarea( $email->message ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pronamic_client_test_email_headers"><?php esc_html_e( 'Headers', 'pronamic-client' ); ?></label>
					</th>
					<td>
						<textarea name="pronamic_client_test_email[headers]" id="pronamic_client_test_email_headers" cols="60" rows="4"><?php echo \esc_textarea( implode( "\r\n", $email->headers ) ); ?></textarea>
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
			__( 'Send test email', 'pronamic-client' ),
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

	<h2><?php esc_html_e( 'Tools', 'pronamic-client' ); ?></h2>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">
					<?php esc_html_e( 'Title', 'pronamic-client' ); ?>
				</th>
				<th scope="col">
					<?php esc_html_e( 'Link', 'pronamic-client' ); ?>
				</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'mail-tester.com', 'pronamic-client' ); ?>
				</th>
				<td>
					<a href="https://www.mail-tester.com/">https://www.mail-tester.com/</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'mail-tester.com/spf-dkim-check', 'pronamic-client' ); ?>
				</th>
				<td>
					<a href="https://www.mail-tester.com/spf-dkim-check">https://www.mail-tester.com/spf-dkim-check</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Dig (DNS lookup) - G Suite toolbox', 'pronamic-client' ); ?>
				</th>
				<td>
					<a href="https://toolbox.googleapps.com/apps/dig/">https://toolbox.googleapps.com/apps/dig/</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'SPF Record Testing Tools', 'pronamic-client' ); ?>
				</th>
				<td>
					<a href="https://www.kitterman.com/spf/validate.html">https://www.kitterman.com/spf/validate.html</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'dmarcian\'s SPF Record Checker', 'pronamic-client' ); ?>
				</th>
				<td>
					<a href="https://dmarcian.com/spf-survey/">https://dmarcian.com/spf-survey/</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'DMARC Analyzer - DKIM record Checker', 'pronamic-client' ); ?>
				</th>
				<td>
					<a href="https://www.dmarcanalyzer.com/dkim/dkim-check/">https://www.dmarcanalyzer.com/dkim/dkim-check/</a>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'DKIMValidator — DKIM, SPF, SpamAssassin validator', 'pronamic-client' ); ?>
				</th>
				<td>
					<a href="https://dkimvalidator.com/">https://dkimvalidator.com/</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
