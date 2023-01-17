<?php

// Prevent direct file access.
if ( ! defined( '\ABSPATH' ) ) {
	exit;
}

$files_to_delete = [];

$nonce = array_key_exists( 'pronamic_client_nonce', $_POST ) ? \sanitize_text_field( \wp_unslash( $_POST['pronamic_client_nonce'] ) ) : null;

if ( null !== $nonce && \wp_verify_nonce( $nonce, 'pronamic_client_scanner_delete_files' ) ) {
	$delete = array_key_exists( 'delete', $_POST ) ? \sanitize_text_field( \wp_unslash( $_POST['delete'] ) ) : null;

	$pronamic_client_action = array_key_exists( 'action2', $_POST ) ? \sanitize_text_field( \wp_unslash( $_POST['action2'] ) ) : null;

	if ( 'delete' === $pronamic_client_action && array_key_exists( 'files', $_POST ) ) {
		$files_to_delete = filter_var( $_POST['files'], \FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY );
	}
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
					<?php foreach ( array( 'thead', 'tfoot' ) as $html_tag ) : ?>

						<<?php echo $html_tag; ?>>
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
						</<?php echo $html_tag; ?>>

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

							foreach ( $infection_files as $filename ) :
								?>

								<tr>
									<th class="check-column" scope="row">
										<input type="checkbox" value="<?php echo esc_attr( $filename ); ?>" name="files[]" />
									</th>
									<td><?php echo $filename; ?></td>
									<td><?php echo size_format( filesize( $filename ) ); ?></td>
									<td><?php echo date_i18n( __( 'F j, Y g:i a', 'pronamic_client' ), filectime( $filename ) ); ?></td>
									<td>
										<textarea cols="60" rows="4" readonly="readonly"><?php echo esc_html( file_get_contents( $filename, true ) ); ?></textarea>
									</td>
									<td>
										<?php

										if ( $delete === $filename || in_array( $filename, $files_to_delete, true ) ) {
											unlink( $filename );

											echo 'Deleted';
										} else {
											?>

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
						<?php

						wp_nonce_field( 'pronamic_client_scanner_delete_files', 'pronamic_client_nonce' );

						?>

						<select name="action2">
							<option selected="selected" value="-1"><?php _e( 'Actions', 'pronamic_client' ); ?></option>
							<option value="delete"><?php _e( 'Delete', 'pronamic_client' ); ?></option>
						</select>

						<input id="doaction2" class="button-secondary action" type="submit" value="Uitvoeren" name="">
					</div>
				</div>
			</form>

			<?php
		endif;

	endforeach;
	?>
</div>
