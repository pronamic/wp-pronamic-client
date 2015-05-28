<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<?php

	$language     = get_locale();
	$isDutch      = 'nl_NL' === $language;
	$timezone     = get_option( 'timezone_string' );
	$blogPublic   = get_option( 'blog_public' );
	$categoryBase = get_option( 'category_base' );

	?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<?php _e( 'Language', 'pronamic_client' ); ?>
			</th>
			<td>
                <?php echo $language; ?>
			</td>
			<td>
				<span class="dashicons dashicons-yes"></span>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Is Dutch', 'pronamic_client' ); ?>
			</th>
			<td>
                <?php echo $isDutch ? 'true' : 'false'; ?>
			</td>
			<td>
				<?php echo $isDutch ? '<span class="dashicons dashicons-yes"></span>' : ''; ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Timezone', 'pronamic_client' ); ?>
			</th>
			<td>
				<?php echo $timezone; ?>
			</td>
			<td>
				<?php if ( $isDutch && 'Europe/Amsterdam' === $timezone ) : ?>
					<span class="dashicons dashicons-yes"></span>
				<?php elseif ( $isDutch ) : ?>
					
				<?php else : ?>
					
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Site Visibility', 'pronamic_client' ); ?>
			</th>
			<td>
				<?php echo $blogPublic; ?>
			</td>
			<td>
				<?php echo $blogPublic ? '<span class="dashicons dashicons-yes"></span>' : ''; ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'Category Base', 'pronamic_client' ); ?>
			</th>
			<td>
                <?php echo $categoryBase; ?>
			</td>
			<td>
				<?php echo ( $isDutch && 'categorie' === $categoryBase ) ? '<span class="dashicons dashicons-yes"></span>' : ''; ?>
			</td>
		</tr>
		<tr>
			<?php

			$headerFile        = get_template_directory() . '/header.php';
			$headerFileContent = file_get_contents( $headerFile );
			$hasWpHeadFunction = strpos( $headerFileContent, 'wp_head();' );

			?>
			<th scope="row">
				<?php _e( 'Function wp_head() in header.php', 'pronamic_client' ); ?>
			</th>
			<td>
                <?php echo $hasWpHeadFunction ? 'true' : 'false'; ?>
			</td>
			<td>
				<?php echo $hasWpHeadFunction ? '<span class="dashicons dashicons-yes"></span>' : ''; ?>
			</td>
		</tr>
		<tr>
			<?php

			$footerFile          = get_template_directory() . '/footer.php';
			$footerFileContent   = file_get_contents( $footerFile );
			$hasWpFooterFunction = strpos( $footerFileContent, 'wp_footer();' );

			?>
			<th scope="row">
				<?php _e( 'Function wp_footer() in footer.php', 'pronamic_client' ); ?>
			</th>
			<td>
                <?php echo $hasWpFooterFunction ? 'true' : 'false'; ?>
			</td>
			<td>
				<?php echo $hasWpFooterFunction ? '<span class="dashicons dashicons-yes"></span>' : ''; ?>
			</td>
		</tr>
	</table>

	<h3>Plugins</h3>

	<?php  ?>

	<?php

	$activePlugins = get_plugins();
	$preferedPlugins = array(
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

	<table class="form-table">

		<?php foreach ( $preferedPlugins as $file => $data ) : ?>

			<tr>
				<td>
					<?php echo $data['name']; ?>
				</td>
				<td>
					<?php echo is_plugin_active( $file ) ? '<span class="dashicons dashicons-yes"></span>' : ''; ?>
				</td>
				<td>
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
				</td>
			</tr>

		<?php endforeach; ?>

	</table>
</div>
