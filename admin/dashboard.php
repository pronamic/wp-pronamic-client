<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div class="postbox-container" id="postbox-container-1">
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle"><span><?php esc_html_e( 'Support', 'pronamic_client' ); ?></span></h2>
						</div>

						<div class="inside">
							<?php require 'dashboard/support.php'; ?>
						</div>
					</div>

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle"><span><?php esc_html_e( 'Adminer', 'pronamic_client' ); ?></span></h2>
						</div>

						<div class="inside">
							<?php require 'dashboard/adminer.php'; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="postbox-container" id="postbox-container-2">
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle"><span><?php esc_html_e( 'Pronamic.nl News', 'pronamic_client' ); ?></span></h2>
						</div>

						<div class="inside">
							<?php

							wp_widget_rss_output(
								'http://feeds.feedburner.com/pronamic',
								array(
									'link'  => __( 'http://www.pronamic.eu/', 'pronamic_client' ),
									'url'   => 'http://feeds.feedburner.com/pronamic',
									'title' => __( 'Pronamic News', 'pronamic_client' ),
									'items' => 5,
								)
							);

							?>
						</div>
					</div>
				</div>
			</div>

			<div class="postbox-container" id="postbox-container-3">
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle"><span><?php esc_html_e( 'Pronamic.eu News', 'pronamic_client' ); ?></span></h2>
						</div>

						<div class="inside">
							<?php

							wp_widget_rss_output(
								'http://feeds.feedburner.com/pronamic-en',
								array(
									'link'  => __( 'http://www.pronamic.eu/', 'pronamic_client' ),
									'url'   => 'http://feeds.feedburner.com/pronamic',
									'title' => __( 'Pronamic News', 'pronamic_client' ),
									'items' => 5,
								)
							);

							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
