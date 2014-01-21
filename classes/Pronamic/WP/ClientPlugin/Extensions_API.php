<?php

/**
 * Class Pronamic_WP_ClientPlugin_Extensions_API
 *
 * TODO Implement error handling.
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

//        $this->check_licenses( array( 'sdw9iu1d3a4uh7h7aus1oid8', 'tdw3iu1d5a1uh2h7wut2oid9' ) );
        $this->activate_licenses( array( 'sdw9iu1d3a4uh7h7aus1oid8', 'tdw3iu1d5a1uh2h7wut2oid9' ) );
//        $this->check_licenses( array( 'sdw9iu1d3a4uh7h7aus1oid8', 'tdw3iu1d5a1uh2h7wut2oid9' ) );
    }

    //////////////////////////////////////////////////

    /**
     * Check if a license deactivation has been requested by a user.
     */
    public function maybe_deactivate_license() {

        $license_key = filter_input( INPUT_GET, 'deactivate_license', FILTER_SANITIZE_STRING );

        if ( strlen( $license_key ) > 0 ) {

            $this->deactivate_licenses( $license_key );

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

        if ( get_site_transient( $transient ) !== false ) {
            return;
        }

        $active_licenses = $this->get_active_licenses();

        foreach ( $active_licenses as $license_key => $license ) {

            if ( ! $this->check_licenses( $license_key ) ) {
                $this->deactivate_licenses( $license_key );
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
     * TODO Describe describe how to the extensions array should be built up.
     * TODO Should probably reference the yet to create description of the request method.
     *
     * license_key
     * slug
     * product_type
     *
     * @param array $extensions
     *
     * @return array
     */
    public function activate_licenses( $extensions ) {

        if ( ! is_array( $extensions ) ) {
            return false;
        }

        $response_extensions = $this->request( 'activate', $extensions );

        // Set successfully returned license keys to active
        foreach ( $response_extensions as $license_key => $response_extension ) {

            if ( isset( $response_extension['success'] ) && $response_extension['success'] ) {
                $response_extensions[ $license_key ]['success'] = $this->add_active_license( $license_key );
            }
        }

        return $response_extensions;
    }

    /**
     * Deactivate plugin. Removes the deactivated license from the active licenses array.
     *
     * @param array|string $license_keys
     *
     * @return array
     */
    public function deactivate_licenses( $license_keys ) {

        if ( is_string( $license_keys ) && strlen( $license_keys ) <= 0 ) {
            return false;
        } else if ( is_string( $license_keys ) ) {
            $license_keys = array( $license_keys );
        }

        if ( count( $license_keys ) <= 0 ) {
            return false;
        }

        $extensions = array();

        foreach ( $license_keys as $license_key ) {

            // Only deactivate active license keys
            if ( $this->is_license_active( $license_key ) ) {
                $extensions[] = array( 'license_key' => $license_key );
            }
        }

        $response_extensions = $this->request( 'deactivate', $extensions );

        // Deactivate license keys
        foreach ( $response_extensions as $license_key => $response_extension ) {

            if ( isset( $response_extension['success'] ) && $response_extension['success'] ) {
                $response_extensions[ $license_key ]['success'] = $this->remove_active_license( $license_key );
            }
        }

        return $response_extensions;
    }

    /**
     * Check extension license.
     *
     * @param array|string $license_keys
     *
     * @return array
     */
    public function check_licenses( $license_keys ) {

        if ( is_string( $license_keys ) && strlen( $license_keys ) <= 0 ) {
            return array();
        } else if ( is_string( $license_keys ) ) {
            $license_keys = array( $license_keys );
        }

        if ( count( $license_keys ) <= 0 ) {
            return array();
        }

        $extensions = array();

        foreach ( $license_keys as $license_key ) {
            $extensions[] = array( 'license_key' => $license_key );
        }

        return $this->request( 'check', $extensions );
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
     * @param array  $extensions (optional, defaults to an empty array)
     *
     * @return array $data
     */
    public function request( $action = 'check', $extensions = array() ) {

        $valid_actions    = array( 'check', 'activate', 'deactivate' );

        if ( ! in_array( $action, $valid_actions ) ) {
            return array( 'success' => false, 'message' => __( 'Invalid request action', 'pronamic_client' ) );
        }

        $raw_response = wp_remote_get(
            self::API_URL . '/' . $action . '/' . self::API_VERSION . '/',
            array(
                // Variables as seen in this plugin's Updater class
                'timeout'     => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 15 ),
                'body'        => array(
                    'data'    => json_encode(array('extensions' => $extensions)),
                    'site'    => home_url(),
                    'network' => is_multisite(),
                ),
                'user-agent'  => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),

                // Variables from the WooThemes updater
                'method'      => 'POST',
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'cookies'     => array(),
                'ssl_verify'  => false,
            )
        );
var_dump($raw_response['body']); die();
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
     * Pronamic plugins can hook into the 'pronamic_client_plugins' filter, which allows them to indicate whether or not
     * they require a license key. A Pronamic plugin can indicate to require a license key by setting the following
     * array keys:
     *
     * $plugin['license_key_requested'] = true;
     * $plugin['slug']                  = 'plugin-slug';
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
     * Pronamic themes can hook into the 'pronamic_client_themes' filter, which allows them to indicate whether or not
     * they require a license key. A Pronamic theme can indicate to require a license key by setting the following
     * magic variables:
     *
     * $theme->license_key_requested = true;
     * $theme->slug                  = 'theme-slug';
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

                    $this->activate_licenses( $plugin_data['license_key'], $plugin_data['slug'] );

                // If the license key has changed, check if the license should still be active
                } else if ( $this->is_license_active( $plugin_data['license_key'] ) && $plugin_data['license_key'] !== $current_plugins_data[ $plugin_key ]['license_key'] ) {

                    if ( ! $this->check_licenses( $plugin_data['license_key'] ) ) {
                        $this->deactivate_licenses( $plugin_data['license_key'] );
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

                    $this->activate_licenses( $theme_data['license_key'], $theme_data['slug'], 'pronamic_theme' );

                // If the license key has change, check if the license should still be active
                } else if ( $this->is_license_active( $theme_data['license_key'] ) && $theme_data['license_key'] !== $themes[ $theme_key ]->license_key ) {

                    if ( ! $this->check_licenses( $theme_data['license_key'] ) ) {
                        $this->deactivate_licenses( $theme_data['license_key'] );
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