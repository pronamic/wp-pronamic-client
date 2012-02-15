<div class="wrap">
	<?php screen_icon('pronamic-client'); ?>

	<h2>
		<?php _e('Pronamic', 'pronamic-client'); ?>
	</h2>

	<?php 
	
	$language = get_option('WPLANG', WPLANG);
	$isDutch = $language == 'nl_NL'; 
	$timezone = get_option('timezone_string');
	$blogPublic = get_option('blog_public');
	$categoryBase = get_option('category_base');

	?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<?php _e('Language', 'pronamic-client'); ?>
			</th>
			<td>
                <?php echo $language; ?>
			</td>
			<td>
				&#9745;
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Is Dutch', 'pronamic-client'); ?>
			</th>
			<td>
                <?php echo $isDutch ? 'true' : 'false'; ?>
			</td>
			<td>
				<?php if($isDutch): ?>
				&#9745;
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Timezone', 'pronamic-client'); ?>
			</th>
			<td>
				<?php echo $timezone; ?>
			</td>
			<td>
				<?php if($isDutch && $timezone == 'Europe/Amsterdam'): ?>
				&#9745;
				<?php elseif($isDutch): ?>
				&#9744;
				<?php else: ?>
				&#9744;
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Site Visibility', 'pronamic-client'); ?>
			</th>
			<td>
				<?php echo $blogPublic; ?>
			</td>
			<td>
				<?php if($blogPublic): ?>
				&#9745;
				<?php else: ?>
				&#9744;
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Category Base', 'pronamic-client'); ?>
			</th>
			<td>
                <?php echo $categoryBase; ?>
			</td>
			<td>
				<?php if($isDutch && $categoryBase == 'categorie'): ?>
				&#9745;
				<?php else: ?>
				&#9744;
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<?php 
			
			$headerFile = get_template_directory() . '/header.php'; 
			$headerFileContent = file_get_contents($headerFile);
			$hasWpHeadFunction = strpos($headerFileContent, 'wp_head();');
			
			?>
			<th scope="row">
				<?php _e('Function wp_head() in header.php', 'pronamic-client'); ?>
			</th>
			<td>
                <?php echo $hasWpHeadFunction ? 'true' : 'false'; ?>
			</td>
			<td>
				<?php if($hasWpHeadFunction): ?>
				&#9745;
				<?php else: ?>
				&#9744;
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<?php 
			
			$footerFile = get_template_directory() . '/footer.php'; 
			$footerFileContent = file_get_contents($footerFile);
			$hasWpFooterFunction = strpos($footerFileContent, 'wp_footer();');
			
			?>
			<th scope="row">
				<?php _e('Function wp_footer() in footer', 'pronamic-client'); ?>
			</th>
			<td>
                <?php echo $hasWpFooterFunction ? 'true' : 'false'; ?>
			</td>
			<td>
				<?php if($hasWpFooterFunction): ?>
				&#9745;
				<?php else: ?>
				&#9744;
				<?php endif; ?>
			</td>
		</tr>
	</table>
	
	<h3>Plugins</h3>

	<?php  ?>

	<?php 
	
	$activePlugins = get_plugins();
	$preferedPlugins = array(
		'google-analytics-for-wordpress/googleanalytics.php' => 'Google Analytics for WordPress' , 
		'wordpress-seo/wp-seo.php' => 'WordPress SEO by Yoast' , 
		'w3-total-cache/w3-total-cache.php' => 'W3 Total Cache' ,
		'gravityforms/gravityforms.php' => 'Gravity Forms' ,
		'gravityforms-nl/gravityforms-nl.php' => 'Gravity Forms (nl)' ,
		'akismet/akismet.php' => 'Akismet' , 
		'wp-mail-smtp/wp_mail_smtp.php' => 'WP-Mail-SMTP' , 
		'jetpack/jetpack.php' => 'Jetpack by WordPress.com' , 
		'backwpup/backwpup.php' => 'BackWPup'
	);
	
	?>

	<table class="form-table">
		<?php foreach($preferedPlugins as $file => $name): ?>

		<tr>
			<td>
				<?php echo $name; ?>
			</td>
			<td>
				<?php $installed = is_plugin_active($file); ?>
				<?php echo $installed ? 'true' : 'false'; ?>
			</td>
			<td>
				<?php if($installed): ?>
				&#9745;
				<?php else: ?>
				&#9744;
				<?php endif; ?>
			</td>
		</tr>

		<?php endforeach; ?>
	</table>
	
	<h3>Infection</h3>

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

	$delete = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_STRING);
	
	?>
	<table>
		<thead>
			<tr>
				<th scope="col">File</th>
				<th scope="col">Size</th>
				<th scope="col">Content</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>

		<tbody>
		
			<?php foreach ($iter as $key => $leaf): ?>
			
			<tr>
				<td><?php echo $key; ?></td>
				<td colspan="3"></td>
			</tr>
		
			<?php 

			$infectionFiles = glob('{' . $key . '/*.php,' . $key . '/.htaccess}', GLOB_BRACE);

			foreach($infectionFiles as $filename): ?>
			
			<tr>
				<td><?php echo $filename; ?></td>
				<td><?php echo filesize($filename); ?></td>
				<td>
					<pre style="width: 25em; height: 5em; overflow: auto;"><?php echo esc_html(file_get_contents($filename)); ?></pre>
				</td>
				<td>
					
					<?php 

					if($delete == $filename) {
						unlink($filename);
						
						echo 'Deleted';
					} else { ?> 

					<a href="<?php echo add_query_arg('delete', $filename, 'admin.php?page=pronamic_client'); ?>">
						Delete
					</a>
					
					<?php } ?>
				</td>
			</tr>
			
			<?php endforeach; ?>
		
			<?php endforeach; ?>
		</tbody>
	</table>
	
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
	<a href="<?php echo add_query_arg('action', 'empty-w3tc', 'admin.php?page=pronamic_client'); ?>">
		Empty W3TC
	</a>
</div>