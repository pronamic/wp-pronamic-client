<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<hr class="wp-header-end">

	<form action="options.php" method="post">
		<?php wp_nonce_field( 'pronamic_client_settings', 'pronamic_client_nonce' ); ?>

		<?php settings_fields( 'pronamic_client' ); ?>

		<?php do_settings_sections( 'pronamic_client' ); ?>

		<?php submit_button(); ?>
	</form>
</div>
