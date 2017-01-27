<?php
/*
 * Plugin Name: WDS ExactTarget Data Extension API
 * Plugin URI:  http://webdevstudios.com
 * Description: A library for pushing data to ExactTarget Data Extensions.
 * Version:     0.0.0
 * Author:      WebDevStudios
 * Author URI:  http://webdevstudios.com
 * Donate link: http://webdevstudios.com
 * License:     GPLv2
 * Text Domain: wds-exacttarget-data-extension-api
 * Domain Path: /languages
 *
 * @link        http://webdevstudios.com
 *
 * @package     WDS Exacttarget Data Extension API
 * @version     0.0.0
 *
 * @author      Aubrey Portwood, Kellen Mace
 */

/**
 * Copyright (c) 2016 WebDevStudios (email : contact@webdevstudios.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */

/**
 * Autoloads files with classes when needed
 *
 * @since  NEXT
 * @param  string $class_name Name of the class being requested.
 * @return void
 */
function wds_exacttarget_data_extension_api_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'WDS_ET_DE_' ) ) {
		return;
	}

	$filename = strtolower( str_replace(
		'_', '-',
		substr( $class_name, strlen( 'WDS_ET_DE_' ) )
	) );

	WDS_ET_DE::include_file( $filename );
}
spl_autoload_register( 'wds_exacttarget_data_extension_api_autoload_classes' );

/**
 * Main initiation class
 *
 * @since  NEXT
 */
final class WDS_ET_DE {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  NEXT
	 */
	const VERSION = '0.0.0';

	/**
	 * This file.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @var string
	 */
	protected $file = '';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  NEXT
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var WDS_ET_DE
	 * @since  NEXT
	 */
	protected static $single_instance = null;

	/**
	 * Instance of WDS_ET_DE_Vendors
	 *
	 * @since NEXT
	 * @var WDS_ET_DE_Vendors
	 */
	protected $vendors;

	/**
	 * Instance of WDS_ET_DE_Fuelsdk_Config
	 *
	 * @since NEXT
	 * @var WDS_ET_DE_Fuelsdk_Config
	 */
	protected $fuelsdk_config;

	/**
	 * Instance of WDS_ET_DE_Helpers
	 *
	 * @since NEXT
	 * @var WDS_ET_DE_Helpers
	 */
	protected $helpers;

	/**
	 * Instance of WDS_ET_DE_Exact_Target_Admin
	 *
	 * @since NEXT
	 * @var WDS_ET_DE_Exact_Target_Admin
	 */
	protected $admin;

	/**
	 * Instance of WDS_ET_DE_Ajax
	 *
	 * @since NEXT
	 * @var WDS_ET_DE_Ajax
	 */
	protected $ajax;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  NEXT
	 * @return WDS_ET_DE A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  NEXT
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
		$this->file     = __FILE__;
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
		$this->vendors        = new WDS_ET_DE_Vendors( $this );
		$this->helpers        = new WDS_ET_DE_Helpers( $this );
		$this->admin          = new WDS_ET_DE_Exact_Target_Admin( $this );
		$this->fuelsdk_config = new WDS_ET_DE_Fuelsdk_Config( $this );
		$this->ajax           = new WDS_ET_DE_Ajax( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
	}


	/**
	 * The plugin version.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 *
	 * @return string The version.
	 */
	public function version() {
		return self::VERSION;
	}

	/**
	 * Init hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function init() {
		load_plugin_textdomain( 'wds-exacttarget-data-extension-api', false, dirname( $this->basename ) . '/languages/' );
		$this->plugin_classes();
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  NEXT
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'file':
			case 'vendors':
			case 'fuelsdk_config':
			case 'helpers':
			case 'admin':
			case 'ajax':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  NEXT
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/class-' . $filename . '.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  NEXT
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the WDS_ET_DE object and return it.
 * Wrapper for WDS_ET_DE::get_instance()
 *
 * @since  NEXT
 * @return WDS_ET_DE  Singleton instance of plugin class.
 */
function wds_exacttarget_data_extension_api() {
	return WDS_ET_DE::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', array( wds_exacttarget_data_extension_api(), 'hooks' ) );
