<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

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
				<th colspan="2"><?php _e( 'WordPres', 'pronamic_client' ); ?></td>
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
			<tr>
				<?php

				$header_file          = get_template_directory() . '/header.php';
				$header_file_content  = file_get_contents( $header_file );
				$has_wp_head_function = strpos( $header_file_content, 'wp_head();' );

				?>
				<th scope="row">
					<?php _e( 'Function wp_head() in header.php', 'pronamic_client' ); ?>
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
					<?php _e( 'Function wp_footer() in footer.php', 'pronamic_client' ); ?>
				</th>
				<td>
					<span class="dashicons dashicons-<?php echo esc_attr( $has_wp_footer_function ? 'yes' : 'no' ); ?>"></span>

					<?php $has_wp_footer_function ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
				</td>
			</tr>
			<tr>
				<?php

				$manager_role     = get_role( 'manager' );
				$has_manager_role = null !== $manager_role;

				?>
				<th scope="row">
					<?php _e( 'WordPress user role \'manager\'', 'pronamic_client' ); ?>
				</th>
				<td>
					<?php if ( $has_manager_role ) : ?>

						<span class="dashicons dashicons-yes"></span>

					<?php endif; ?>

					<?php $has_manager_role ? esc_html_e( 'Yes', 'pronamic_client' ) : esc_html_e( 'No', 'pronamic_client' ); ?>
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

							$searchUrl = add_query_arg(
								array(
									'tab'  => 'search',
									'type' => 'term',
									's'    => $data['slug'],
								),
								'plugin-install.php'
							);

							?>
							<a href="<?php echo $searchUrl; ?>">
								<?php _e( 'Search Plugin', 'pronamic_client' ); ?>
							</a>

						<?php endif; ?>

					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
</div>
