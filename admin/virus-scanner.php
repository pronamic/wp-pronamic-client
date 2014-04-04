<?php 

$delete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_STRING);

$action = filter_input(INPUT_POST, 'action2', FILTER_SANITIZE_STRING);

$filesToDelete = array();

if($action == 'delete') {
	$filesToDelete = filter_input(INPUT_POST, 'files', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
}

?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<h3><?php _e( 'Uploads', 'pronamic_client' ); ?></h3>

	<?php 
	
	$uploadDir = wp_upload_dir();
	$baseDir = $uploadDir['basedir'];

	$directories = array(
		$baseDir 
	);

	foreach($directories as $dir): if(is_dir($dir)): 

	$rdi = new RecursiveDirectoryIterator($dir);
	$dirsOnly = new ParentIterator($rdi);
	$iter = new RecursiveIteratorIterator($dirsOnly, RecursiveIteratorIterator::CHILD_FIRST);
	
	?>
	<form method="post" action="">
		<table cellspacing="0" class="widefat fixed">
			<?php foreach(array('thead', 'tfoot') as $tag): ?>
	
			<<?php echo $tag; ?>>
				<tr>
					<th id="cb" class="manage-column column-cb check-column" scope="col">
						<input type="checkbox" />
					</th>
					<th scope="col"><?php _e('File', 'pronamic_client'); ?></th>
					<th scope="col"><?php _e('Size', 'pronamic_client'); ?></th>
					<th scope="col"><?php _e('Date', 'pronamic_client'); ?></th>
					<th scope="col"><?php _e('Content', 'pronamic_client'); ?></th>
					<th scope="col"><?php _e('Actions', 'pronamic_client'); ?></th>
				</tr>
			</<?php echo $tag; ?>>
	
			<?php endforeach; ?>
	
			<tbody>
			
				<?php foreach ($iter as $key => $leaf): ?>
				
				<tr>
					<th class="check-column" scope="row">
						
					</th>
					<td><?php echo $key; ?></td>
					<td colspan="4"></td>
				</tr>
			
				<?php 
	
				$infectionFiles = glob('{' . $key . '/*.php,' . $key . '/.htaccess}', GLOB_BRACE);
	
				foreach($infectionFiles as $filename): ?>
				
				<tr>
					<th class="check-column" scope="row">
						<input type="checkbox" value="<?php echo esc_attr($filename); ?>" name="files[]" />
					</th>
					<td><?php echo $filename; ?></td>
					<td><?php echo size_format(filesize($filename)); ?></td>
					<td><?php echo date_i18n(__('F j, Y g:i a', 'pronamic_client'), filectime($filename)); ?></td>
					<td>
						<textarea cols="60" rows="4" readonly="readonly"><?php echo esc_html(file_get_contents($filename)); ?></textarea>
					</td>
					<td>
						<?php 
	
						if($delete == $filename || in_array($filename, $filesToDelete)) {
							unlink($filename);
							
							echo 'Deleted';
						} else { ?> 
	
						<a href="<?php echo add_query_arg('delete', $filename, 'admin.php?page=pronamic_client_virus_scanner'); ?>">
							<?php _e('Delete', 'pronamic_client'); ?>
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
					<option selected="selected" value="-1"><?php _e('Actions', 'pronamic_client'); ?></option>
					<option value="delete"><?php _e('Delete', 'pronamic_client'); ?></option>
				</select>
	
				<input id="doaction2" class="button-secondary action" type="submit" value="Uitvoeren" name="">
			</div>
		</div>
	</form>

	<?php endif; endforeach; ?>
	
	<?php 
	
	$dir = WP_CONTENT_DIR . '/w3tc';
		
	function empty_dir($dir) {
	    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir),
	                                              RecursiveIteratorIterator::CHILD_FIRST);
	    foreach ($iterator as $path) {
	      if ($path->isDir()) {
	         rmdir($path->__toString());
	      } else {
	         unlink($path->__toString());
	      }
	    }
	//    rmdir($dir);
	}
	
	$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
	
	if($action == 'empty-w3tc') {
		empty_dir($dir);
	}
	
	?>
	<p>
		<a href="<?php echo add_query_arg('action', 'empty-w3tc', 'admin.php?page=pronamic_client_virus_scanner'); ?>">
			<?php _e('Empty W3TC directory', 'pronamic_client'); ?>
		</a>
	</p>
</div>