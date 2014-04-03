<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div class="postbox-container" id="postbox-container-1">
				<div class="meta-box-sortables">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Support', 'pronamic_client' ); ?></span></h3>
		
						<div class="inside">
						    <?php include 'dashboard/support.php'; ?>
						</div>
					</div>
	
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Adminer', 'pronamic_client' ); ?></span></h3>
		
						<div class="inside">
							<?php include 'dashboard/adminer.php'; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="postbox-container" id="postbox-container-2">
				<div class="meta-box-sortables">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Pronamic.nl News', 'pronamic_client' ); ?></span></h3>
		
						<div class="inside">
						    <?php 

						    wp_widget_rss_output( 'http://feeds.feedburner.com/pronamic', array(
							    'link'  => __( 'http://pronamic.eu/', 'pronamic_client' ),
							    'url'   => 'http://feeds.feedburner.com/pronamic',
							    'title' => __( 'Pronamic News', 'pronamic_client' ),
							    'items' => 5,
						    ) );
						    
						    ?>
						</div>
					</div>
				</div>
			</div>

			<div class="postbox-container" id="postbox-container-3">
				<div class="meta-box-sortables">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'HappyWP.com News', 'pronamic_client' ); ?></span></h3>
		
						<div class="inside">
						    <?php 

						    wp_widget_rss_output( 'http://feeds.feedburner.com/happywp', array(
							    'link'  => __( 'http://pronamic.eu/', 'pronamic_client' ),
							    'url'   => 'http://feeds.feedburner.com/pronamic',
							    'title' => __( 'Pronamic News', 'pronamic_client' ),
							    'items' => 5,
						    ) );

						    ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>