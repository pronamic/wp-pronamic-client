<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>

	<h2><?php _e( 'Plugins', 'pronamic_client' ); ?></h2>

	<?php

	$pronamic_plugins = pronamic_client_get_plugins();

	if ( empty( $pronamic_plugins ) ) : ?>

		<p>
			<?php _e( 'No Pronamic plugins found.', 'pronamic_client' ); ?>
		</p>

	<?php else : ?>

		<table class="wp-list-table widefat plugins" cellspacing="0">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Plugin', 'pronamic_client' ); ?></th>
					<th scope="col"><?php _e( 'Author', 'pronamic_client' ); ?></th>
					<th scope="col"><?php _e( 'Version', 'pronamic_client' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $pronamic_plugins as $plugin ) : ?>

					<tr>
						<td>
							<?php echo $plugin['Name']; ?>
						</td>
						<td>
							<?php echo $plugin['Author']; ?>
						</td>
						<td>
							<?php echo $plugin['Version']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php endif; ?>

	<h2><?php _e( 'Themes', 'pronamic_client' ); ?></h2>

	<?php

	$pronamic_themes = pronamic_client_get_themes();

	if ( empty( $pronamic_themes ) ) : ?>

		<p>
			<?php _e( 'No Pronamic themes found.', 'pronamic_client' ); ?>
		</p>

	<?php else : ?>

		<table class="wp-list-table widefat themes" cellspacing="0">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Theme', 'pronamic_client' ); ?></th>
					<th scope="col"><?php _e( 'Author', 'pronamic_client' ); ?></th>
					<th scope="col"><?php _e( 'Version', 'pronamic_client' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $pronamic_themes as $theme ) : ?>

					<tr>
						<td>
							<?php echo $theme['Name']; ?>
						</td>
						<td>
							<?php echo $theme['Author']; ?>
						</td>
						<td>
							<?php echo $theme['Version']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php endif; ?>
</div>
