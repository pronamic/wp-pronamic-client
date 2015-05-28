<?php

$plugin = Pronamic_WP_ClientPlugin_Plugin::get_instance();

$adminer_url = plugins_url( 'vendor/adminer/adminer.php', $plugin->file );

?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<form method="post" action="<?php echo esc_attr( $adminer_url ); ?>" target="_blank">
		<div id="dashboard-widgets-wrap">
				<div class="metabox-holder columns-2" id="dashboard-widgets">
					<div class="postbox-container" id="postbox-container-1">
						<div class="postbox">
							<h3 class="hndle"><span>Snel inloggen</span></h3>
		
							<div class="inside">
							    <form target="_blank" method="post" action="https://login.twinfield.com/default.aspx">
							        <p>
							        	<a target="_blank" href="<?php echo esc_attr( $adminer_url ); ?>"><?php _e( 'Adminer', 'pronamic_client' ); ?></a>

										<input type="hidden" name="auth[driver]" value="server" />
										<input type="hidden" name="auth[server]" value="<?php echo esc_attr( DB_HOST ); ?>" />
										<input type="hidden" name="auth[username]" value="<?php echo esc_attr( DB_USER ); ?>" />
										<input type="hidden" name="auth[password]" value="<?php echo esc_attr( DB_PASSWORD ); ?>" />
										<input type="hidden" name="auth[db]" value="<?php echo esc_attr( DB_NAME ); ?>" />
		
										<?php submit_button( __( 'Login', 'pronamic_client' ), 'primary', 'submit', false ); ?>
									</p>
							    </form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
