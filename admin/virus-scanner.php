<?php

$delete = filter_input( INPUT_GET, 'delete', FILTER_SANITIZE_STRING );

$action = filter_input( INPUT_POST, 'action2', FILTER_SANITIZE_STRING );

$files_to_delete = array();

if ( 'delete' === $action ) {
	$files_to_delete = filter_input( INPUT_POST, 'files', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
}

?>
<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>

	<h2><?php _e( 'Uploads', 'pronamic_client' ); ?></h2>

	<?php

	$upload_dir = wp_upload_dir();
	$base_dir   = $upload_dir['basedir'];

	$directories = array(
		$base_dir,
	);

	foreach ( $directories as $dir ) :
		if ( is_dir( $dir ) ) :

			$rdi       = new RecursiveDirectoryIterator( $dir );
			$dirs_only = new ParentIterator( $rdi );
			$iter      = new RecursiveIteratorIterator( $dirs_only, RecursiveIteratorIterator::CHILD_FIRST );

			?>
			<form method="post" action="">
				<table cellspacing="0" class="widefat fixed">
					<?php foreach ( array( 'thead', 'tfoot' ) as $tag ) : ?>

						<<?php echo $tag; ?>>
							<tr>
								<th id="cb" class="manage-column column-cb check-column" scope="col">
									<input type="checkbox" />
								</th>
								<th scope="col"><?php _e( 'File', 'pronamic_client' ); ?></th>
								<th scope="col"><?php _e( 'Size', 'pronamic_client' ); ?></th>
								<th scope="col"><?php _e( 'Date', 'pronamic_client' ); ?></th>
								<th scope="col"><?php _e( 'Content', 'pronamic_client' ); ?></th>
								<th scope="col"><?php _e( 'Actions', 'pronamic_client' ); ?></th>
							</tr>
						</<?php echo $tag; ?>>

					<?php endforeach; ?>

					<tbody>

						<?php foreach ( $iter as $key => $leaf ) : ?>

							<tr>
								<th class="check-column" scope="row">

								</th>
								<td><?php echo $key; ?></td>
								<td colspan="4"></td>
							</tr>

							<?php

							$infection_files = glob( '{' . $key . '/*.php,' . $key . '/.htaccess}', GLOB_BRACE );

							foreach ( $infection_files as $filename ) : ?>

								<tr>
									<th class="check-column" scope="row">
										<input type="checkbox" value="<?php echo esc_attr( $filename ); ?>" name="files[]" />
									</th>
									<td><?php echo $filename; ?></td>
									<td><?php echo size_format( filesize( $filename ) ); ?></td>
									<td><?php echo date_i18n( __( 'F j, Y g:i a', 'pronamic_client' ), filectime( $filename ) ); ?></td>
									<td>
										<textarea cols="60" rows="4" readonly="readonly"><?php echo esc_html( file_get_contents( $filename ) ); ?></textarea>
									</td>
									<td>
										<?php

										if ( $delete === $filename || in_array( $filename, $files_to_delete, true ) ) {
											unlink( $filename );

											echo 'Deleted';
										} else { ?>

										<a href="<?php echo add_query_arg( 'delete', $filename, 'admin.php?page=pronamic_client_virus_scanner' ); ?>">
											<?php _e( 'Delete', 'pronamic_client' ); ?>
										</a>

										<?php } ?>
									</td>
								</tr>

							<?php endforeach; ?>

						<?php endforeach; ?>
					</tbody>
				</table>

				<div class="tablenav bottom">
					<div class="alignleft actions">
						<select name="action2">
							<option selected="selected" value="-1"><?php _e( 'Actions', 'pronamic_client' ); ?></option>
							<option value="delete"><?php _e( 'Delete', 'pronamic_client' ); ?></option>
						</select>

						<input id="doaction2" class="button-secondary action" type="submit" value="Uitvoeren" name="">
					</div>
				</div>
			</form>

		<?php endif;

	endforeach; ?>
</div>
