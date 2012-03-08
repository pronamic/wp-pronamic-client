<div class="wrap">
	<?php screen_icon('pronamic_client'); ?>

	<h2>
		<?php _e('Checklist', 'pronamic_client'); ?>
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
				<?php _e('Language', 'pronamic_client'); ?>
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
				<?php _e('Is Dutch', 'pronamic_client'); ?>
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
				<?php _e('Timezone', 'pronamic_client'); ?>
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
				<?php _e('Site Visibility', 'pronamic_client'); ?>
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
				<?php _e('Category Base', 'pronamic_client'); ?>
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
				<?php _e('Function wp_head() in header.php', 'pronamic_client'); ?>
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
				<?php _e('Function wp_footer() in footer.php', 'pronamic_client'); ?>
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
		<tr>
			<?php 
			
			$headerFile = get_template_directory() . '/header.php'; 
			$headerFileContent = file_get_contents($headerFile);
			$hasWpHeadFunction = strpos($headerFileContent, "wp_title('');");
			
			?>
			<th scope="row">
				<?php _e('Function wp_title(\'\') in header.php', 'pronamic_client'); ?>
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
	</table>
	
	<h3>Plugins</h3>

	<?php  ?>

	<?php 
	
	$activePlugins = get_plugins();
	$preferedPlugins = array(
		'google-analytics-for-wordpress/googleanalytics.php' => array(
			'slug' => 'google-analytics-for-wordpress' , 
			'name' => 'Google Analytics for WordPress'
		) , 
		'wordpress-seo/wp-seo.php' => array(
			'slug' => 'wordpress-seo' , 
			'name' => 'WordPress SEO by Yoast' 
		) , 
		'w3-total-cache/w3-total-cache.php' => array(
			'slug' => 'w3-total-cache' , 
			'name' => 'W3 Total Cache' 
		) ,
		'gravityforms/gravityforms.php' => array(
			'slug' => 'gravityforms' , 
			'name' => 'Gravity Forms' 
		) ,
		'gravityforms-nl/gravityforms-nl.php' => array(
			'slug' => 'gravityforms-nl' , 
			'name' => 'Gravity Forms (nl)' 
		) ,
		'akismet/akismet.php' => array(
			'slug' => 'akismet' , 
			'name' => 'Akismet' 
		) , 
		'wp-mail-smtp/wp_mail_smtp.php' => array(
			'slug' => 'wp-mail-smtp' , 
			'name' => 'WP-Mail-SMTP' 
		) , 
		'jetpack/jetpack.php' => array(
			'slug' => 'jetpack' , 
			'name' => 'Jetpack by WordPress.com'
		) , 
		'backwpup/backwpup.php' => array(
			'slug' => 'backwpup' , 
			'name' => 'BackWPup'
		) , 
		'sucuri-scanner/sucuri.php' => array(
			'slug' => 'sucuri-scanner' , 
			'name' => 'Sucuri Scanner'
		)
	);
	
	?>

	<table class="form-table">
		<?php foreach($preferedPlugins as $file => $data): ?>

		<tr>
			<td>
				<?php echo $data['name']; ?>
			</td>
			<td>
				<?php if(is_plugin_active($file)): ?>
				&#9745;
				<?php else: ?>
				&#9744;
				<?php endif; ?>
			</td>
			<td>
				<?php 
				
				$searchUrl = add_query_arg(
					array(
						'tab' => 'search' , 
						'type' => 'term' , 
						's' => $data['slug']
					) , 
					'plugin-install.php'
				);
				
				?>
				<a href="<?php echo $searchUrl; ?>">
					<?php _e('Search Plugin', 'pronamic_client'); ?>
				</a>
			</td>
		</tr>

		<?php endforeach; ?>
	</table>
</div>