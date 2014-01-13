<?php

class Pronamic_WP_ClientPlugin_Extensions_API {
    /**
     * Instance of this class.
     *
     * @since 1.1.0
     *
     * @var self
     */
    protected static $instance = null;

    //////////////////////////////////////////////////

    /**
     * Plugin
     *
     * @var Pronamic_WP_ClientPlugin_Plugin
     */
    private $plugin;

    //////////////////////////////////////////////////

    /**
     * Settings group
     *
     * @var string
     */
    const SETTINGS_GROUP = 'pronamic_client_extensions';

    /**
     * Plugins data option
     *
     * @var string
     */
    const PLUGINS_DATA_SETTING = 'pronamic_client_extensions_plugins_data';

    /**
     * Themes data option
     *
     * @var string
     */
    const THEMES_DATA_SETTING = 'pronamic_client_extensions_themes_data';

    //////////////////////////////////////////////////

    /**
     * Constructs and initialize admin
     */
    private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
        $this->plugin = $plugin;

        // Actions
        add_action( 'admin_init', array( $this, 'periodically_check_extensions' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    //////////////////////////////////////////////////

    /**
     * Periodically checks plugins for updates and valid license key.
     *
     * TODO Implement
     */
    public function periodically_check_extensions() {
        // Check transient to see if an extensions check needs to be done

        // Get list of extensions

        // Start looping through extensions

        // Contact server for updates and license information

        // If license is incorrect, deactivate extension and remove from list of extensions

        // End looping through extensions

        // Save updated list of extensions
    }

    //////////////////////////////////////////////////

    /**
     * Activate plugin.
     *
     * TODO Implement
     *
     * @return bool
     */
    public function activate() {
        return true;
    }

    /**
     * Deactivate plugin.
     *
     * TODO Implement
     *
     * @return bool
     */
    public function deactivate() {
        return true;
    }

    /**
     * Check plugin license.
     *
     * TODO Implement
     *
     * @return bool
     */
    public function check() {
        return true;
    }

    /**
     * TODO Check if needed. WooThemes uses this to check if a connection is available before loading the list of products.
     *
     * @return bool
     */
    public function ping() {
       return true;
    }

    /**
     * Makes a request to the API server.
     *
     * // TODO Implement using "wp_remote_get"
     *
     * @return mixed $data
     */
    public function request() {
        return array();
    }

    //////////////////////////////////////////////////

    /**
     * Registers all extension settings
     */
    public function register_settings() {

        register_setting( self::SETTINGS_GROUP, self::PLUGINS_DATA_SETTING, array( $this, 'save_plugins_data_setting' ) );
        register_setting( self::SETTINGS_GROUP, self::THEMES_DATA_SETTING , array( $this, 'save_themes_data_setting' ) );
    }

    //////////////////////////////////////////////////

    /**
     * Saves the plugins data setting.
     *
     * @param mixed $plugins_data
     *
     * @return mixed $plugins_data
     */
    public function save_plugins_data_setting( $plugins_data ) {
        $nonce = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';

        if ( ! wp_verify_nonce( $nonce, self::SETTINGS_GROUP . '-options' ) ) {
            return $plugins_data;
        }

        foreach ( $plugins_data as $slug => $plugin_data ) {
            if ( isset( $plugin_data['license_key'] ) ) {
                if ( true ) { // TODO Replace "true" by a call to a function for validating license keys. Also check if the key has changed at all, to save request time.
                    continue;
                }
            }

            unset( $plugins_data[ $slug ] );
        }

        $current_plugins_data = $this->get_plugins();

        return array_merge(
            is_array( $current_plugins_data ) ? $current_plugins_data : array(),
            is_array( $plugins_data )         ? $plugins_data         : array()
        );
    }

    /**
     * Saves the themes data setting.
     *
     * @param mixed $themes_data
     *
     * @return mixed $themes_data
     */
    public function save_themes_data_setting( $themes_data ) {
        $nonce = isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '';

        if ( ! wp_verify_nonce( $nonce, self::SETTINGS_GROUP . '-options' ) ) {
            return $themes_data;
        }

        $current_themes_data = $this->get_themes();

        foreach ( $current_themes_data as $slug => $current_theme_data ) {
            if ( isset( $themes_data[ $slug ] ) && isset( $themes_data[ $slug ] ) && strlen( $themes_data[ $slug ]['license_key'] ) > 0 ) {
                if ( true ) { // TODO Replace "true" by a call to a function for validating license keys. Also check if the key has changed at all, to save request time.
                    $current_themes_data[ $slug ]->license_key = $themes_data[ $slug ]['license_key'];
                }
            } else if ( isset( $themes_data[ $slug ] ) ) {
                unset( $themes_data[ $slug ] );
            }
        }

        return $themes_data;
    }

    //////////////////////////////////////////////////

    /**
     * Returns all Pronamic plugins with extra data.
     *
     * @return mixed $plugins
     */
    public function get_plugins() {
        $plugins = get_plugins();

        $plugins_data = get_option( self::PLUGINS_DATA_SETTING );

        foreach ( $plugins as $slug => $plugin ) {
            if ( isset( $plugin['Author'] ) && strpos( $plugin['Author'], 'Pronamic' ) === false ) {
                unset( $plugins[ $slug ] );

                continue;
            }

            $plugins[ $slug ]['license_key'] = null;

            if ( isset( $plugins_data[ $slug ] ) && isset( $plugins_data[ $slug ]['license_key'] ) ) {
                $plugins[ $slug ]['license_key'] = $plugins_data[ $slug ]['license_key'];
            }
        }

        return $plugins;
    }

    /**
     * Returns all Pronamic themes with extra data.
     *
     * @return mixed $themes
     */
    public function get_themes() {
        $themes = wp_get_themes();

        $themes_data = get_option( self::THEMES_DATA_SETTING );

        foreach ( $themes as $slug => $theme ) {
            if ( isset( $theme['Author'] ) && strpos( $theme['Author'], 'Pronamic' ) === false ) {
                unset( $themes[ $slug ] );

                continue;
            }

            $themes[ $slug ]->license_key = null;

            if ( isset( $themes_data[ $slug ] ) && isset( $themes_data[ $slug ]['license_key'] ) ) {
                $themes[ $slug ]->license_key = $themes_data[ $slug ]['license_key'];
            }
        }

        return $themes;
    }

    //////////////////////////////////////////////////
    // Singleton
    //////////////////////////////////////////////////

    /**
     * Return an instance of this class.
     *
     * @since 1.1.0
     *
     * @param Pronamic_WP_ClientPlugin_Plugin $plugin
     *
     * @return Pronamic_WP_ClientPlugin_Extensions_API A single instance of this class.
     */
    public static function get_instance( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self( $plugin );
        }

        return self::$instance;
    }
}