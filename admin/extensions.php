<div class="wrap">
	<?php screen_icon( 'pronamic_client' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

    <?php settings_errors(); ?>

	<h3><?php _e( 'Plugins', 'pronamic_client' ); ?></h3>

    <form method="post" action="options.php">

        <?php

        settings_fields( Pronamic_WP_ClientPlugin_Extensions_API::SETTINGS_GROUP );

        $pronamic_plugins = Pronamic_WP_ClientPlugin_Plugin::get_instance()->extensions_api->get_plugins();

        if ( empty( $pronamic_plugins ) ) : ?>

            <p>
                <?php _e( 'No Pronamic plugins found.', 'pronamic_client' ); ?>
            </p>

        <?php else : ?>

            <table class="wp-list-table widefat plugins" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col"><?php _e( 'Plugin'     , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Author'     , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Version'    , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'License Key', 'pronamic_client' ); ?></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ( $pronamic_plugins as $plugin_key => $plugin ) : ?>

                        <tr>
                            <td>
                                <?php echo $plugin['Name']; ?>
                            </td>
                            <td>
                                <?php echo $plugin['Author']; ?>
                            </td>
                            <td>
                                <?php echo $plugin['Version']; ?>
                            </td>
                            <td>
                                <?php if ( Pronamic_WP_ClientPlugin_Plugin::get_instance()->extensions_api->is_license_active( $plugin['license_key'] ) ) : ?>

                                    <input type="hidden" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::PLUGINS_DATA_SETTING . '[' . $plugin_key . '][license_key]'; ?>" value="<?php echo htmlspecialchars( $plugin['license_key'] ); ?>" />

                                <a href="<?php echo add_query_arg( 'deactivate_license', $plugin['license_key'] ); ?>"><?php _e( 'Deactivate', 'pronamic_client' ); ?></a>

                                <?php elseif ( isset( $plugin['license_key_requested'] ) && $plugin['license_key_requested'] ) : ?>

                                    <input type="text" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::PLUGINS_DATA_SETTING . '[' . $plugin_key . '][license_key]'; ?>" value="<?php echo htmlspecialchars( $plugin['license_key'] ); ?>" />

                                <?php endif; ?>
                            </td>
                        </tr>

                        <input type="hidden" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::PLUGINS_DATA_SETTING . '[' . $plugin_key . '][slug]'; ?>" value="<?php echo isset( $plugin['slug'] ) ? htmlspecialchars( $plugin['slug'] ) : ''; ?>" />

                    <?php endforeach; ?>

                </tbody>
            </table>
            <?php submit_button( __( 'Activate', 'pronamic_client' ) ); ?>

        <?php endif; ?>

        <h3><?php _e( 'Themes', 'pronamic_client' ); ?></h3>

        <?php

        $pronamic_themes = Pronamic_WP_ClientPlugin_Plugin::get_instance()->extensions_api->get_themes();
        $current_theme   = wp_get_theme();

        if ( empty( $pronamic_themes ) ) : ?>

            <p>
                <?php _e( 'No Pronamic themes found.', 'pronamic_client' ); ?>
            </p>

        <?php else : ?>

            <table class="wp-list-table widefat themes" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col"><?php _e( 'Theme'      , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Author'     , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Version'    , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'License Key', 'pronamic_client' ); ?></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ( $pronamic_themes as $theme_key => $theme ) : ?>

                        <tr>
                            <td>
                                <?php echo $theme['Name']; ?>
                            </td>
                            <td>
                                <?php echo $theme['Author']; ?>
                            </td>
                            <td>
                                <?php echo $theme['Version']; ?>
                            </td>
                            <td>
                                <?php if ( Pronamic_WP_ClientPlugin_Plugin::get_instance()->extensions_api->is_license_active( $theme->license_key ) ) : ?>

                                    <input type="hidden" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::THEMES_DATA_SETTING . '[' . $theme_key . '][license_key]'; ?>" value="<?php echo htmlspecialchars( $theme->license_key ); ?>" />

                                    <a href="<?php echo add_query_arg( 'deactivate_license', $theme->license_key ); ?>"><?php _e( 'Deactivate', 'pronamic_client' ); ?></a>

                                <?php elseif ( $theme->license_key_requested ) : ?>

                                    <input type="text" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::THEMES_DATA_SETTING . '[' . $theme_key . '][license_key]'; ?>" value="<?php echo htmlspecialchars( $theme->license_key ); ?>" />

                                <?php endif; ?>
                            </td>
                        </tr>

                        <input type="hidden" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::THEMES_DATA_SETTING . '[' . $theme_key . '][slug]'; ?>" value="<?php echo htmlspecialchars( $theme->slug ); ?>" />

                    <?php endforeach; ?>

                </tbody>
            </table>
            <?php submit_button( __( 'Activate', 'pronamic_client' ) ); ?>

        <?php endif; ?>

    </form>

</div>