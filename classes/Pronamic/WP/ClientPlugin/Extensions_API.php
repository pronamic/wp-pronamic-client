<?php

/**
 * Class Pronamic_WP_ClientPlugin_Extensions_API
 *
 * TODO Implement error handling
 *
 * @author Stefan Boonstra
 */
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
     * @const Pronamic_WP_ClientPlugin_Plugin
     */
    private $plugin;

    //////////////////////////////////////////////////

    /**
     * Licenses API url
     *
     * @const string
     */
//    const API_URL = 'http://wp.pronamic.eu/api/licenses';
    const API_URL = 'http://sb.beta.pronamic.nl/api/licenses';

    /**
     * API version
     *
     * @const string
     */
    const API_VERSION = '1.0';

    //////////////////////////////////////////////////

    /**
     * Settings group
     *
     * @const string
     */
    const SETTINGS_GROUP = 'pronamic_client_extensions';

    /**
     * Plugins data option
     *
     * @const string
     */
    const PLUGINS_DATA_SETTING = 'pronamic_client_extensions_plugins_data';

    /**
     * Themes data option
     *
     * @const string
     */
    const THEMES_DATA_SETTING = 'pronamic_client_extensions_themes_data';

    /**
     * Active licenses option
     *
     * @const string
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

        add_action( 'admin_init', array( $this, 'maybe_deactivate_license' ) );
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

            if ( $plugin_key === 'wp-pronamic-ideal/pronamic-ideal.php' ) {
                $plugin['license_key_requested'] = true;
                $plugin['slug'] = 'pronamic-ideal';

                $this->plugins[ $plugin_key ] = $plugin;
            }
        }

        // TODO Remove test code
        foreach ( $this->themes as $theme_key => $theme ) {

            if ( $theme_key === 'wt-orbis' ) {
                $theme->license_key_requested = true;
                $theme->slug = 'orbis';

                $this->themes[ $theme_key ] = $theme;
            }
        }
    }

    //////////////////////////////////////////////////

    /**
     * Check if a license deactivation has been requested by a user.
     */
    public function maybe_deactivate_license() {

        $license_key = filter_input( INPUT_GET, 'deactivate_license', FILTER_SANITIZE_STRING );

        if ( strlen( $license_key ) > 0 ) {

            $this->deactivate_license( $license_key );

            wp_redirect( remove_query_arg( 'deactivate_license') );
        }
    }

    //////////////////////////////////////////////////

    /**
     * Periodically check if license keys are valid.
     */
    public function periodically_check_licenses() {

        // Should be 40 characters or less
        $transient = 'pronamic_client_periodic_license_check';

        if ( get_site_transient( $transient ) == true ) {
            return;
        }

        $active_licenses = $this->get_active_licenses();

        foreach ( $active_licenses as $license_key => $license ) {

            if ( ! $this->check_license( $license_key ) ) {
                $this->deactivate_license( $license_key );
            }
        }

        // Check licenses on a daily basis
        set_site_transient( $transient, true, 60 * 60 * 24 );
    }

    //////////////////////////////////////////////////
    // API methods
    //////////////////////////////////////////////////

    /**
     * Activate license. Adds the activated license to the active licenses array.
     *
     * @param string $license_key
     * @param string $slug
     * @param string $product_type (optional, defaults to 'pronamic_plugin')
     *
     * @return bool
     */
    public function activate_license( $license_key, $slug, $product_type = 'pronamic_plugin' ) {

        if ( strlen( $license_key ) <= 0 ||
             strlen( $slug ) <= 0 ||
             strlen( $product_type ) <= 0 ) {
            return false;
        }

        if ( $this->is_license_active( $license_key ) ) {
            return true;
        }

        $response = $this->request(
            'activate',
            array(
                'license_key'  => $license_key,
                'slug'         => $slug,
                'product_type' => $product_type
            )
        );

        if ( isset( $response['success'] ) && $response['success'] ) {
            return $this->add_active_license( $license_key );
        }

        return false;
    }

    /**
     * Deactivate plugin. Removes the deactivated license from the active licenses array.
     *
     * @param string $license_key
     *
     * @return bool
     */
    public function deactivate_license( $license_key ) {

        if ( strlen( $license_key ) <= 0 ) {
            return false;
        }

        if ( ! $this->is_license_active( $license_key ) ) {
            return true;
        }

        $response = $this->request( 'deactivate', array( 'license_key'  => $license_key ) );

        if ( isset( $response['success'] ) && $response['success'] ) {
            return $this->remove_active_license( $license_key );
        }

        return false;
    }

    /**
     * Check extension license.
     *
     * @param string $license_key
     *
     * @return bool
     */
    public function check_license( $license_key ) {

        if ( strlen( $license_key ) <= 0 ) {
            return false;
        }

        $response = $this->request( 'check', array( 'license_key'  => $license_key ) );

        if ( isset( $response['success'] ) && $response['success'] ) {
            return true;
        }

        return false;
    }

    /**
     * Makes a request to the API server.
     *
     * $action is allowed to be one of the following values:
     * - check
     * - activate
     * - deactivate
     *
     * $parameters can consist of the following keys:
     * - license_key
     * - slug
     * - product_type
     *
     * @param string $action     (optional, defaults to 'check_license')
     * @param array  $parameters (optional, defaults to an empty array)
     *
     * @return array $data
     */
    public function request( $action = 'check', $parameters = array() ) {

        $valid_actions    = array( 'check', 'activate', 'deactivate' );
        $valid_parameters = array( 'license_key', 'slug', 'product_type' );

        if ( ! in_array( $action, $valid_actions ) ) {
            return array( 'success' => false, 'message' => __( 'Invalid request action', 'pronamic_client' ) );
        }

        // Only keep valid parameters
        if ( is_array( $parameters ) ) {
            foreach ( $parameters as $parameter_key => $parameter ) {

                if ( ! in_array( $parameter_key, $valid_parameters ) ) {
                    unset( $parameters[ $parameter_key ] );
                }
            }
        }

        $raw_response = wp_remote_get(
            self::API_URL . '/' . $action . '/' . self::API_VERSION . '/',
            array(
                // Variables as seen in this plugin's Updater class
                'timeout'     => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 30 ), // TODO Set non-cron time to a lower amount of seconds again.
                'body'        => array_merge(
                    array(
                        'site'    => home_url(),
                        'network' => is_multisite(),
                    ),
                    $parameters
                ),
                'user-agent'  => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),

                // Variables from the WooThemes updater
                'method'      => 'POST',
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'cookies'     => array(),
                'ssl_verify'  => false, 'username' => 'pronamic', 'password' => 'pronamic'
            )
        );

        if ( is_wp_error( $raw_response ) || wp_remote_retrieve_response_code( $raw_response ) != 200 ) {
            return array( 'success' => false  );
        }

        return json_decode( wp_remote_retrieve_body( $raw_response ), true );
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

        foreach ( $plugins as $plugin_key => $plugin ) {
            if ( isset( $plugin['Author'] ) && strpos( $plugin['Author'], 'Pronamic' ) === false ) {
                unset( $plugins[ $plugin_key ] );

                continue;
            }

            $plugins[ $plugin_key ]['license_key'] = null;

            if ( isset( $plugins_data[ $plugin_key ] ) && isset( $plugins_data[ $plugin_key ]['license_key'] ) ) {
                $plugins[ $plugin_key ]['license_key'] = $plugins_data[ $plugin_key ]['license_key'];
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

        foreach ( $themes as $theme_key => $theme ) {
            if ( isset( $theme['Author'] ) && strpos( $theme['Author'], 'Pronamic' ) === false ) {
                unset( $themes[ $theme_key ] );

                continue;
            }

            $themes[ $theme_key ]->license_key = null;

            if ( isset( $themes_data[ $theme_key ] ) && isset( $themes_data[ $theme_key ]['license_key'] ) ) {
                $themes[ $theme_key ]->license_key = $themes_data[ $theme_key ]['license_key'];
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

        foreach ( $plugins_data as $plugin_key => $plugin_data ) {

            if ( isset( $plugin_data['license_key'] ) &&
                 isset( $plugin_data['slug'] ) &&
                 strlen( $plugin_data['license_key'] ) > 0 &&
                 strlen( $plugin_data['slug'] ) > 0 ) {

                // Activate license
                if ( ! $this->is_license_active( $plugin_data['license_key'] ) ) {

                    $this->activate_license( $plugin_data['license_key'], $plugin_data['slug'] );

                // If the license key has changed, check if the license should still be active
                } else if ( $this->is_license_active( $plugin_data['license_key'] ) && $plugin_data['license_key'] !== $current_plugins_data[ $plugin_key ]['license_key'] ) {

                    if ( ! $this->check_license( $plugin_data['license_key'] ) ) {
                        $this->deactivate_license( $plugin_data['license_key'] );
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

        $new_themes_data = array();

        $themes = $this->get_themes();

        foreach ( $themes_data as $theme_key => $theme_data ) {

            if ( isset( $theme_data['license_key'] ) &&
                 isset( $theme_data['slug'] ) &&
                 strlen( $theme_data['license_key'] ) > 0 &&
                 strlen( $theme_data['slug'] ) > 0 ) {

                $new_themes_data[ $theme_key ] = array( 'license_key' => $theme_data['license_key'] );

                // Activate license
                if ( ! $this->is_license_active( $theme_data['license_key'] ) ) {

                    $this->activate_license( $theme_data['license_key'], $theme_data['slug'], 'pronamic_theme' );

                // If the license key has change, check if the license should still be active
                } else if ( $this->is_license_active( $theme_data['license_key'] ) && $theme_data['license_key'] !== $themes[ $theme_key ]->license_key ) {

                    if ( ! $this->check_license( $theme_data['license_key'] ) ) {
                        $this->deactivate_license( $theme_data['license_key'] );
                    }
                }
            } else {
                $new_themes_data[ $theme_key ] = array( 'license_key' => $themes[ $theme_key ]->license_key );
            }
        }

        return $new_themes_data;
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