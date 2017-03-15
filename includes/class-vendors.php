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
		$this->plugin   = $plugin; // Parent plugin.
		$this->includes = $this->get_vendors( $vendors ); // Set the includes.

		// Require the vendor files.
		$this->load_vendor_files();
	}

	/**
	 * Load the vendor files.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 */
	private function load_vendor_files() {
		foreach ( $this->includes as $file ) {

			// Relative to plugin.
			$file = $this->plugin->path . $file;

			if ( file_exists( $file ) ) {

				// Require the file.
				require_once( $file );
			} else {

				// File did not exist, error error!
				throw new Exception( sprintf( __( 'Sorry but %s does not exist.', 'wds-exacttarget-data-extension-api' ), $file ) );
			}
		}
	}

	/**
	 * Get the vendor includes.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 * @return array An array of includes to require.
	 */
	private function get_vendors( $vendors = array() ) {

		/*
		 * The vendor includes.
		 *
		 * Note that the files here are all relative to the plugin root
		 * folder.
		 */
		$vendors = array_merge( $vendors, array(

			// FuelSDK.
			'fuelsdk/ET_Client.php',
		) );

		return apply_filters( 'wdsedeapi_vendors_vendors_includes', $vendors );
	}
}
