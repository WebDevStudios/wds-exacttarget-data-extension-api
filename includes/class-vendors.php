<?php

/**
 * WDS Exacttarget Data Extension API Vendors
 *
 * @since NEXT
 * @package WDS Exacttarget Data Extension API
 */

/**
 * WDS Exacttarget Data Extension API Vendors.
 *
 * @since NEXT
 */
class WDS_ET_DE_Vendors {

	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Includes that will be loaded.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @var array
	 */
	protected $includes = array();

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin  Main plugin object.
	 * @param  array  $vendors Other vendor files to include.
	 * @return void
	 */
	public function __construct( $plugin, $vendors = array() ) {

		$this->plugin = $plugin; // Parent plugin.

		// Require the vendor files.
		$this->load_vendor_files( $vendors );
	}

	/**
	 * Load the vendor files.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 * @param array $vendors Array of vendor files to include.
	 */
	private function load_vendor_files( $vendors = array() ) {

		// Loop through each of our vendor files and include them.
		foreach ( $this->get_vendors( $vendors ) as $file ) {
			$file = require_once( $this->plugin->path . $file ); // Relative to plugin.
		}
	}

	/**
	 * Get the vendor includes. Note that the files here are all relative to the plugin root folder.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 * @param array $vendors Array of vendor files to include.
	 * @return array An array of includes to require.
	 */
	private function get_vendors( $vendors = array() ) {
		return array_merge( $vendors, array( 'fuelsdk/ET_Client.php' ) );
	}
}
