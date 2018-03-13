<?php
/**
 * Global helper functions.
 *
 * Functions that don't really belong in any of the other classes.
 *
 * @since 1.0.0
 * @package WDS Exacttarget Data Extension API
 */

/**
 * Helpers.
 *
 * These are functions that we can use globally throughout
 * the plugin.
 *
 * @since 1.0.0
 * @author Aubrey Portwood
 */
class WDS_ET_DE_Helpers {

	/**
	 * Parent plugin class.
	 *
	 * @var   class
	 * @since 1.0.0
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Is this multi-site?
	 *
	 * This extends is_multisite by also allowing us to set a constant
	 * to make the things behave as if it's a single install, but while
	 * multi-site is active.
	 *
	 * @author Aubrey Portwood
	 * @since 1.0.0
	 * @return boolean True if we want things to behave like mulitisite, false if not.
	 */
	public function is_multisite() {

		// You can set this to true and it will make each subsite, on multisite, to have it's own settings.
		$force_single_install_on_multisite = defined( 'WDS_EXACTTARGET_FORCE_SINGLE_INSTALL_ON_MULTISITE' ) && WDS_EXACTTARGET_FORCE_SINGLE_INSTALL_ON_MULTISITE;

		if ( is_multisite() && ! $force_single_install_on_multisite ) {
			return true;
		}

		return false;
	}
}
