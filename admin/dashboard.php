<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div class="postbox-container" id="postbox-container-1">
				<div class="meta-box-sortables">
					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle"><span><?php esc_html_e( 'Support', 'pronamic-client' ); ?></span></h2>
						</div>

						<div class="inside">
							<?php require 'dashboard/support.php'; ?>
						</div>
					</div>

					<div class="postbox">
						<div class="postbox-header">
							<h2 class="hndle"><span><?php esc_html_e( 'Adminer', 'pronamic-client' ); ?></span></h2>
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
							<h2 class="hndle"><span><?php esc_html_e( 'Pronamic.nl News', 'pronamic-client' ); ?></span></h2>
						</div>

						<div class="inside">
							<?php

							wp_widget_rss_output(
								'https://feeds.feedburner.com/pronamic',
								[
									'link'  => __( 'https://www.pronamic.eu/', 'pronamic-client' ),
									'url'   => 'https://feeds.feedburner.com/pronamic',
									'title' => __( 'Pronamic News', 'pronamic-client' ),
									'items' => 5,
								]
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
							<h2 class="hndle"><span><?php esc_html_e( 'Pronamic.eu News', 'pronamic-client' ); ?></span></h2>
						</div>

						<div class="inside">
							<?php

							wp_widget_rss_output(
								'https://feeds.feedburner.com/pronamic-en',
								[
									'link'  => __( 'https://www.pronamic.eu/', 'pronamic-client' ),
									'url'   => 'https://feeds.feedburner.com/pronamic',
									'title' => __( 'Pronamic News', 'pronamic-client' ),
									'items' => 5,
								]
							);

							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
