<?php

global $wpdb;

// @see https://github.com/woothemes/woocommerce/blob/v2.1.3/includes/admin/views/html-admin-page-status-report.php

?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<table class="widefat" cellspacing="0">
		<thead>
			<tr>
				<th colspan="2"><?php _e( 'WordPres', 'pronamic_client' ); ?></td>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td><?php _e( 'Home URL', 'pronamic_client' ); ?></td>
				<td><?php echo home_url(); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Site URL', 'pronamic_client' ); ?></td>
				<td><?php echo site_url(); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Version', 'pronamic_client' ); ?></td>
				<td><?php bloginfo( 'version' ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Multisite', 'pronamic_client' ); ?></td>
				<td><?php echo is_multisite() ? __( 'Yes', 'pronamic_client' ): __( 'No', 'pronamic_client' ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Debug Mode', 'pronamic_client' ); ?></td>
				<td><?php echo defined( 'WP_DEBUG' ) && WP_DEBUG ?  __( 'Yes', 'pronamic_client' ) : __( 'No', 'pronamic_client' ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Language', 'pronamic_client' ); ?></td>
				<td><?php echo defined( 'WPLANG' ) && WPLANG ? WPLANG : __( 'Default', 'pronamic_client' ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Max Upload Size','pronamic_client' ); ?></td>
				<td><?php echo size_format( wp_max_upload_size() ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Absolute Path','pronamic_client' ); ?></td>
				<td><?php echo esc_html( ABSPATH ); ?></td>
			</tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Hosting', 'pronamic_client' ); ?></td>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td><?php _e( 'Web Server Info', 'pronamic_client' ); ?></td>
				<td><?php echo esc_html( filter_input( INPUT_SERVER, 'SERVER_SOFTWARE', FILTER_SANITIZE_STRING ) ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'PHP Version', 'pronamic_client' ); ?></td>
				<td><?php echo esc_html( phpversion() ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'MySQL Version', 'pronamic_client' ); ?></td>
				<td><?php echo esc_html( $wpdb->db_version() ); ?></td>
			</tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Plugins', 'pronamic_client' ); ?></td>
			</tr>
		</thead>

		<?php

		$plugins = array();

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		foreach ( $active_plugins as $plugin ) {
			$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$dirname = dirname( $plugin );
			$version_string = '';

			if ( ! empty( $plugin_data['Name'] ) ) {
				$plugin_name = $plugin_data['Name'];

				if ( ! empty( $plugin_data['PluginURI'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'pronamic_client' ) . '">' . $plugin_name . '</a>';
				}

				$plugins[] = $plugin_name . ' ' . __( 'by', 'pronamic_client' ) . ' ' . $plugin_data['Author'] . ' ' . __( 'version', 'pronamic_client' ) . ' ' . $plugin_data['Version'] . $version_string;
			}
		}

		?>

		<tbody>
			<tr>
				<td><?php _e( 'Number Plugins', 'pronamic_client' ); ?></td>
				<td><?php echo count( $plugins ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Installed Plugins', 'pronamic_client' ); ?></td>
				<td><?php echo implode( '<br/>', $plugins ); ?></td>
			</tr>
		</tbody>

		<thead>
			<tr>
				<th colspan="2"><?php _e( 'Performance', 'pronamic_client' ); ?></td>
			</tr>
		</thead>

		<?php global $wpdb; ?>

		<tbody>
			<tr>
				<td><?php _e( 'Number Autoload Options', 'pronamic_client' ); ?></td>
				<td><?php

				$query = "SELECT COUNT( option_id ) FROM $wpdb->options WHERE autoload = 'yes';";

				echo $wpdb->get_var( $query );

				?></td>
			</tr>
			<tr>
				<td><?php _e( 'Number Transient Options', 'pronamic_client' ); ?></td>
				<td><?php

				$query = "SELECT COUNT( option_id ) FROM $wpdb->options WHERE option_name LIKE '_transient_%';";

				echo $wpdb->get_var( $query );

				?></td>
		</tbody>
	</table>
</div>