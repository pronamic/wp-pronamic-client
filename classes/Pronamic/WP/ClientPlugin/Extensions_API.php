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

    /**
     * Active licenses option
     *
     * @var string
     */
    const ACTIVE_LICENSES_SETTING = 'pronamic_client_extensions_active_licenses';

    //////////////////////////////////////////////////

    /**
     * Plugins
     *
     * @var array
     */
    protected $plugins;

    /**
     * Themes
     *
     * @var array
     */
    protected $themes;

    //////////////////////////////////////////////////

    /**
     * Active licenses
     *
     * @var array
     */
    protected $active_licenses;

    //////////////////////////////////////////////////

    /**
     * Constructs and initialize admin
     */
    private function __construct( Pronamic_WP_ClientPlugin_Plugin $plugin ) {
        $this->plugin = $plugin;

        // Actions
        add_action( 'admin_init', array( $this, 'load_product_data' ), 9 );

        add_action( 'admin_init', array( $this, 'periodically_check_licenses' ) );

        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    //////////////////////////////////////////////////

    /**
     * Load plugin and theme data.
     */
    public function load_product_data() {

        $this->plugins = $this->get_plugins();
        $this->themes  = $this->get_themes();

        // TODO Remove test code
        foreach ( $this->plugins as $plugin_key => $plugin ) {
            $plugin['license_key_requested'] = true;

            $this->plugins[ $plugin_key ] = $plugin;

            break;
        }
    }

    //////////////////////////////////////////////////

    /**
     * Periodically check if license keys are valid.
     */
    public function periodically_check_licenses() {

        // TODO Check transient to see if an extensions check needs to be done

        $active_licenses = $this->get_active_licenses();

        foreach ( $active_licenses as $license_key => $license ) {

            if ( ! $this->check_license( $license_key ) ) {
                $this->deactivate_license( $license_key );
            }
        }
    }

    //////////////////////////////////////////////////
    // API methods
    //////////////////////////////////////////////////

    /**
     * Activate license. Adds the activated license to the active licenses array.
     *
     * TODO Implement
     *
     * @param string $license_key
     * @param string $slug
     * @param string $product_type (optional, defaults to 'plugin')
     *
     * @return bool
     */
    public function activate_license( $license_key, $slug, $product_type = 'plugin' ) {
        return true;

        // Add license to active licenses if successful
    }

    /**
     * Deactivate plugin. Removes the deactivated license from the active licenses array.
     *
     * TODO Implement
     *
     * @param string $license_key
     *
     * @return bool
     */
    public function deactivate_license( $license_key ) {
        return true;

        // Remove license from active licenses
    }

    /**
     * Check extension license.
     *
     * TODO Implement
     *
     * @param string $license_key
     *
     * @return bool
     */
    public function check_license( $license_key ) {
        return true;

        // Only check, no further actions should be taken by this method
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
    // Extensions
    //////////////////////////////////////////////////

    /**
     * Returns all Pronamic plugins with extra data.
     *
     * @param bool $cache (optional, defaults to true)
     *
     * @return mixed $plugins
     */
    public function get_plugins( $cache = true ) {

        if ( $cache && isset( $this->plugins ) ) {
            return $this->plugins;
        }

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

        $this->plugins = apply_filters( 'pronamic_client_plugins', $plugins );

        return $this->plugins;
    }

    /**
     * Returns all Pronamic themes with extra data.
     *
     * @param bool $cache (optional, defaults to true)
     *
     * @return mixed $themes
     */
    public function get_themes( $cache = true ) {

        if ( $cache && isset( $this->themes ) ) {
            return $this->themes;
        }

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

        $this->themes = apply_filters( 'pronamic_client_themes', $themes );

        return $this->themes;
    }

    //////////////////////////////////////////////////
    // Licenses
    //////////////////////////////////////////////////

    /**
     * Returns all active licenses
     *
     * @param bool $cache (optional, defaults to true)
     *
     * @return array
     */
    public function get_active_licenses( $cache = true ) {

        if ( $cache && isset( $this->active_licenses ) ) {
            return $this->active_licenses;
        }

        $active_licenses = get_option( self::ACTIVE_LICENSES_SETTING );

        if ( is_array( $active_licenses ) ) {
            return $active_licenses;
        }

        return array();
    }

    /**
     * Checks if a license is active
     *
     * @param string $license_key
     * @param bool   $cache       (optional, defaults to true)
     *
     * @return array
     */
    public function is_license_active( $license_key, $cache = true ) {

        return array_key_exists( $license_key, $this->get_active_licenses( $cache ) );
    }

    /**
     * Add a license to the active licenses array and save to the database.
     *
     * @param string $license_key
     *
     * @return bool $success
     */
    protected function add_active_license( $license_key ) {

        $active_licenses = $this->get_active_licenses( /* $cache = */ false );

        if ( strlen( $license_key ) > 0 ) {
            $active_licenses[ $license_key ] = $license_key;
        }

        return $this->update_active_licenses( $active_licenses );
    }

    /**
     * Remove a license from the active licenses array and save to the database.
     *
     * @param string $license_key
     *
     * @return bool $success
     */
    protected function remove_active_license( $license_key ) {

        $active_licenses = $this->get_active_licenses( /* $cache = */ false );

        if ( isset( $active_licenses[ $license_key ] ) ) {
            unset( $active_licenses[ $license_key ] );
        }

        return $this->update_active_licenses( $active_licenses );
    }

    /**
     * Save active licenses to the database.
     *
     * @param array $active_licenses (optional, defaults to $this->$active_licenses)
     *
     * @return bool $success
     */
    protected function update_active_licenses( $active_licenses = null ) {

        if ( isset( $active_licenses ) ) {
            $this->active_licenses = $active_licenses;
        }

        return update_option( self::ACTIVE_LICENSES_SETTING, $this->active_licenses );
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

        $current_plugins_data = $this->get_plugins();

        foreach ( $plugins_data as $slug => $plugin_data ) {

            if ( isset( $plugin_data['license_key'] ) && strlen( $plugin_data['license_key'] ) > 0 ) {

                // Activate plugin
                if ( ! $this->is_license_active( $plugin_data['license_key'] ) && isset( $plugin_data['activate'] ) ) {

                    $this->activate_license( $slug, $plugin_data['license_key'] );

                // If the license key has changed, check if the license should still be active
                } else if ( $this->is_license_active( $slug ) && $plugin_data['license_key'] !== $current_plugins_data[ $slug ]['license_key'] ) {

                    if ( ! $this->check_license( $plugin_data['license_key'] ) ) {
                        $this->deactivate_license( $slug, $plugin_data['license_key'] );
                    }
                }
            }
        }

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