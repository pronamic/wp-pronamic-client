<div class="wrap">
	<?php screen_icon( 'pronamic_client' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

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
                        <th scope="col"><?php _e( 'Activate'   , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Plugin'     , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Author'     , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Version'    , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'License Key', 'pronamic_client' ); ?></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ( $pronamic_plugins as $plugin_slug => $plugin ) : ?>

                        <?php $is_plugin_active = is_plugin_active( $plugin_slug ); ?>

                        <tr>
                            <td>
                                <input type="checkbox" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::PLUGINS_DATA_SETTING . '[' . $plugin_slug . '][activate]'; ?>" <?php echo $is_plugin_active ? 'disabled="disabled"' : ''; ?> />
                            </td>
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
                                <?php if ( $is_plugin_active ) : ?>

                                    <?php _e( 'Deactivate', 'pronamic_client' ); ?>

                                <?php else: ?>

                                    <input type="text" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::PLUGINS_DATA_SETTING . '[' . $plugin_slug . '][license_key]'; ?>" value="<?php echo htmlspecialchars( $plugin['license_key'] ); ?>" />

                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
            <?php submit_button( __( 'Save', 'pronamic_client' ) ); ?>

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
                        <th scope="col"></th>
                        <th scope="col"><?php _e( 'Theme'      , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Author'     , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'Version'    , 'pronamic_client' ); ?></th>
                        <th scope="col"><?php _e( 'License Key', 'pronamic_client' ); ?></th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ( $pronamic_themes as $theme_slug => $theme ) : ?>

                        <tr>
                            <td>
                                <input type="radio" name="active_theme" <?php checked( $theme->get_stylesheet(), $current_theme->get_stylesheet() ); ?> />
                            </td>
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
                                <input type="text" name="<?php echo Pronamic_WP_ClientPlugin_Extensions_API::THEMES_DATA_SETTING . '[' . $theme_slug . '][license_key]'; ?>" value="<?php echo htmlspecialchars( $theme->license_key ); ?>" />
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
            <?php submit_button( __( 'Save', 'pronamic_client' ) ); ?>

        <?php endif; ?>

    </form>

</div>