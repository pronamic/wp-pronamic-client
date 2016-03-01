<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>

	<?php

	$language      = get_locale();
	$is_dutch      = 'nl_NL' === $language;
	$timezone      = get_option( 'timezone_string' );
	$blog_public   = get_option( 'blog_public' );
	$category_base = get_option( 'category_base' );

	?>
	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2"><?php _e( 'WordPres', 'pronamic_client' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<th scope="row">
					<?php _e( 'Language', 'pronamic_client' ); ?>
				</th>
				<td>
					<span class="dashicons dashicons-yes"></span> <?php echo $language; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Is Dutch', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $is_dutch ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Timezone', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $is_dutch && 'Europe/Amsterdam' === $timezone ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php echo $timezone; ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Site Visibility', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $blog_public ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php $blog_public ? esc_html_e( 'Public', 'pronamic_client' ) : esc_html_e( 'Private', 'pronamic_client' ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Category Base', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $is_dutch && 'categorie' === $category_base ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php echo $category_base; ?>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Config', 'pronamic_client' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php

			$constants = array(
				'WP_DEBUG',
				'SCRIPT_DEBUG',
				'SAVEQUERIES',
				'JETPACK_DEV_DEBUG',
				'WP_MEMORY_LIMIT',
				'WP_MAX_MEMORY_LIMIT',
			);

			foreach ( $constants as $constant ) : ?>

				<tr>
					<th scope="row">
						<code><?php echo esc_html( $constant ); ?></code>
					</th>
					<td>
						<?php if ( ! defined( $constant ) || false === constant( $constant ) ) : ?>

							<span class="dashicons dashicons-yes"></span>

						<?php endif; ?>

						<code><?php echo esc_html( defined( $constant ) ? constant( $constant ) : '' ); ?></code>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Theme', 'pronamic_client' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<?php

				$header_file          = get_template_directory() . '/header.php';
				$header_file_content  = file_get_contents( $header_file );
				$has_wp_head_function = strpos( $header_file_content, 'wp_head();' );

				?>
				<th scope="row">
					<?php printf( __( 'Function %s in %s', 'pronamic_client' ), '<code>wp_head();</code>', '<code>header.php</code>' ); ?>
				</th>
				<td>
					<span class="dashicons dashicons-<?php echo esc_attr( $has_wp_head_function ? 'yes' : 'no' ); ?>"></span>

					<?php $has_wp_head_function ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>
			<tr>
				<?php

				$footer_file            = get_template_directory() . '/footer.php';
				$footer_file_content    = file_get_contents( $footer_file );
				$has_wp_footer_function = strpos( $footer_file_content, 'wp_footer();' );

				?>
				<th scope="row">
					<?php printf( __( 'Function %s in %s', 'pronamic_client' ), '<code>wp_footer();</code>', '<code>footer.php</code>' ); ?>
				</th>
				<td>
					<span class="dashicons dashicons-<?php echo esc_attr( $has_wp_footer_function ? 'yes' : 'no' ); ?>"></span>

					<?php $has_wp_footer_function ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>
			<tr>
				<?php

				$theme_support_html5 = get_theme_support( 'html5' );

				?>
				<th scope="row">
					<?php _e( 'Theme Support HTML5', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $theme_support_html5 ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php $theme_support_html5 ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>
			<tr>
				<?php

				$theme_support_title_tag = get_theme_support( 'title-tag' );

				?>
				<th scope="row">
					<?php _e( 'Theme Support Title Tag', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $theme_support_title_tag ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php $theme_support_title_tag ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Users', 'pronamic_client' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<?php

				$user_pronamic      = get_user_by( 'login', 'pronamic' );
				$has_user_pronamic  = false !== $user_pronamic;

				?>
				<th scope="row">
					<?php _e( 'WordPress user \'pronamic\'', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $has_user_pronamic ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php $has_user_pronamic ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>

			<?php if ( false !== $user_pronamic ) : ?>

				<tr>
					<?php

					$has_email_pronamic = 'info@pronamic.nl' === $user_pronamic->user_email;

					?>
					<th scope="row">
						<?php _e( 'WordPress user \'pronamic\' email \'info@pronamic.nl\'', 'pronamic_client' ); ?>
					</th>
					<td>
						<?php if ( $has_email_pronamic ) : ?>

							<span class="dashicons dashicons-yes"></span>

						<?php endif; ?>

						<?php echo esc_html( $user_pronamic->user_email ); ?>
					</td>
				</tr>

			<?php endif; ?>

			<tr>
				<?php

				$role_manager     = get_role( 'manager' );
				$has_role_manager = null !== $role_manager;

				?>
				<th scope="row">
					<?php _e( 'WordPress user role \'manager\'', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $has_role_manager ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php $has_role_manager ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Gravity Forms', 'pronamic_client' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<?php

				$rg_gforms_enable_html5 = (bool) get_option( 'rg_gforms_enable_html5' );

				?>
				<th scope="row">
					<?php _e( 'Gravity Forms Output HTML5', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $rg_gforms_enable_html5 ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php $rg_gforms_enable_html5 ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>
			<tr>
				<?php

				$rg_gforms_currency = get_option( 'rg_gforms_currency' );

				?>
				<th scope="row">
					<?php _e( 'Gravity Forms Currency', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $is_dutch && 'EUR' === $rg_gforms_currency ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<code><?php echo esc_html( $rg_gforms_currency ); ?></code>
				</td>
			</tr>
		</tbody>
	</table>

	<?php

	$plugins = array(
		'google-analytics-for-wordpress/googleanalytics.php' => array(
			'slug' => 'google-analytics-for-wordpress',
			'name' => 'Google Analytics for WordPress',
		),
		'wordpress-seo/wp-seo.php' => array(
			'slug' => 'wordpress-seo',
			'name' => 'WordPress SEO by Yoast',
		),
		'gravityforms/gravityforms.php' => array(
			'slug' => 'gravityforms',
			'name' => 'Gravity Forms',
		),
		'gravityforms-nl/gravityforms-nl.php' => array(
			'slug' => 'gravityforms-nl',
			'name' => 'Gravity Forms (nl)',
		),
		'akismet/akismet.php' => array(
			'slug' => 'akismet',
			'name' => 'Akismet',
		),
		'iwp-client/init.php' => array(
			'slug' => 'iwp-client',
			'name' => 'InfiniteWP Client',
		),
		'jetpack/jetpack.php' => array(
			'slug' => 'jetpack',
			'name' => 'Jetpack by WordPress.com',
		),
		'sucuri-scanner/sucuri.php' => array(
			'slug' => 'sucuri-scanner',
			'name' => 'Sucuri Scanner',
		),
		'regenerate-thumbnails/regenerate-thumbnails.php' => array(
			'slug' => 'regenerate-thumbnails',
			'name' => 'Regenerate Thumbnails',
		),
		'posts-to-posts/posts-to-posts.php' => array(
			'slug' => 'posts-to-posts',
			'name' => 'Posts 2 Posts',
		),
	);

	?>

	<table class="pronamic-status-table widefat striped" cellspacing="0">
		<thead>
			<tr>
				<th colspan="3"><?php _e( 'Plugins', 'pronamic_client' ); ?></td>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $plugins as $file => $data ) : ?>

				<tr>
					<th scope="row">
						<?php echo $data['name']; ?>
					</th>
					<td>
						<?php if ( is_plugin_active( $file ) ) : ?>
						
							<span class="dashicons dashicons-yes"></span>

						<?php else : ?>

							<?php

							$search_url = add_query_arg(
								array(
									'tab'  => 'search',
									'type' => 'term',
									's'    => $data['slug'],
								),
								'plugin-install.php'
							);

							?>
							<a href="<?php echo esc_attr( $search_url ); ?>">
								<?php esc_html_e( 'Search Plugin', 'pronamic_client' ); ?>
							</a>

						<?php endif; ?>

					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</div>
