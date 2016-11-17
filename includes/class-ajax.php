<?php
/**
 * AJAX Requests.
 *
 * Handles all AJAX requests.
 *
 * @since NEXT
 * @package WDS Exacttarget Data Extension API
 */

/**
 * AJAX Requests.
 *
 * @since NEXT
 */
class WDS_ET_DE_Ajax {

	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Admin AJAX actions.
	 *
	 * Add your actions here and create public methods by the same name.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @var array
	 */
	protected $admin_actions = array(
		'wds_exacttarget_data_extension_api_check_connection',
	);

	/**
	 * Public AJAX actions.
	 *
	 * Add your actions here and create public methods by the same name.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @var array
	 */
	protected $public_actions = array(
		// None so far.
	);

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->create_endpoints();
	}

	/**
	 * Create endpoints for our actions.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 */
	private function create_endpoints() {

		// Admin AJAX.
		foreach ( $this->admin_actions as $action ) {
			add_action( "wp_ajax_{$action}", array( $this, $action ) );
		}

		// Public AJAX.
		foreach ( $this->public_actions as $action ) {
			add_action( "wp_ajax_nopriv_{$action}", array( $this, $action ) );
		}
	}

	/*
	 * AJAX Handlers:
	 * ===================================
	 *
	 * This is where, if you add an action above, you add
	 * a function by the same name here to handle the response.
	 */

	/**
	 * Check the Exact Target Client ID/Secret connection details.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 */
	function wds_exacttarget_data_extension_api_check_connection() {

		if ( ! current_user_can( 'manage_options ' ) ) {

			// This user cannot perform this AJAX request.
			wp_send_json_error();
		}

		// Get the client ID and secret the user typed in.
		$client_id     = isset( $_POST['client_id'] ) ? sanitize_text_field( $_POST['client_id'] ) : false;
		$client_secret = isset( $_POST['client_secret'] ) ? sanitize_text_field( $_POST['client_secret'] ) : false;

		if ( ! $client_id || ! $client_secret ) {

			// We need both for them to work.
			wp_send_json_error();
		}

		// Create an API instance.
		$api = new WDS_ET_DE_API();

		if ( $api->connect() ) {

			// A connection with those details was able to be made.
			wp_send_json_success();
		}

		// We never made a connection.
		wp_send_json_error();
	}
}
