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

		if ( is_multisite() ) {
			$this->settings = get_site_option( $key );
		} else {
			$this->settings = get_option( $key );
		}

		// Detect if we should overwrite the settings.
		$this->detect_overwrite_settings();
	}

	/**
	 * Overwrite the client_id and secret settings from POST.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 */
	private function detect_overwrite_settings() {

		// If the client_id or secret is being POST'ed, use that instead.
		if ( isset( $_POST['client_id'] ) ) {
			$this->settings['client_id'] = sanitize_text_field( $_POST['client_id'] );
		}

		if ( isset( $_POST['client_secret'] ) ) {
			$this->settings['client_secret'] = sanitize_text_field( $_POST['client_secret'] );
		}

		/**
		 * Filter the settings.
		 *
		 * @author Aubrey Portwood
		 * @since  NEXT
		 *
		 * @var array
		 */
		$this->settings = apply_filters( 'wds_exacttarget_data_extension_api_fuelsdk_config_settings', $this->settings );
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
		return isset( $this->settings['client_id'] )
			? $this->settings['client_id']
			: '';
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
		return isset( $this->settings['client_secret'] )
			? $this->settings['client_secret']
			: '';
	}
}
