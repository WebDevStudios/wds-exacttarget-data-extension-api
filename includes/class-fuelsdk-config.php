<?php
/**
 * FuelSDK Configuration.
 *
 * @since NEXT
 * @package WDS Exacttarget Data Extension API
 */

/**
 * FuelSDK Configuration.
 *
 * @since  NEXT
 * @author Aubrey Portwood
 */
class WDS_ET_DE_Fuelsdk_Config {

	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Admin Settings.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// The CMB2 key.
		$key = 'wds_exacttarget_data_extension_api_admin';

		// Grab our settings.
		$this->settings = $this->plugin->is_multisite() ? get_site_option( $key ) : get_option( $key );
	}

	/**
	 * Get the Client ID.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @return string The Client ID.
	 */
	public function get_client_id() {
		return isset( $this->settings['client_id'] ) ? $this->settings['client_id'] : '';
	}

	/**
	 * Get the Client Secret.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @return string The Client Secret.
	 */
	public function get_client_secret() {
		return isset( $this->settings['client_secret'] ) ? $this->settings['client_secret'] : '';
	}
}
