<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>

	<h2><?php _e( 'Plugins', 'pronamic-client' ); ?></h2>

	<?php

	$pronamic_plugins = pronamic_client_get_plugins();

	if ( empty( $pronamic_plugins ) ) :
		?>

		<p>
			<?php _e( 'No Pronamic plugins found.', 'pronamic-client' ); ?>
		</p>

	<?php else : ?>

		<table class="wp-list-table widefat plugins" cellspacing="0">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Plugin', 'pronamic-client' ); ?></th>
					<th scope="col"><?php _e( 'Author', 'pronamic-client' ); ?></th>
					<th scope="col"><?php _e( 'Version', 'pronamic-client' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $pronamic_plugins as $pronamic_plugin ) : ?>

					<tr>
						<td>
							<?php echo $pronamic_plugin['Name']; ?>
						</td>
						<td>
							<?php echo $pronamic_plugin['Author']; ?>
						</td>
						<td>
							<?php echo $pronamic_plugin['Version']; ?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>

	<?php endif; ?>

	<h2><?php _e( 'Themes', 'pronamic-client' ); ?></h2>

	<?php

	$pronamic_themes = pronamic_client_get_themes();

	if ( empty( $pronamic_themes ) ) :
		?>

		<p>
			<?php _e( 'No Pronamic themes found.', 'pronamic-client' ); ?>
		</p>

	<?php else : ?>

		<table class="wp-list-table widefat themes" cellspacing="0">
			<thead>
				<tr>
					<th scope="col"><?php _e( 'Theme', 'pronamic-client' ); ?></th>
					<th scope="col"><?php _e( 'Author', 'pronamic-client' ); ?></th>
					<th scope="col"><?php _e( 'Version', 'pronamic-client' ); ?></th>
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
