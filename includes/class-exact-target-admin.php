<?php
/**
 * WDS Exacttarget Data Extension API Exact Target Admin
 *
 * @since NEXT
 * @package WDS Exacttarget Data Extension API
 */

require_once dirname( __FILE__ ) . '/../vendor/cmb2/init.php';

/**
 * WDS Exacttarget Data Extension API Exact Target Admin class.
 *
 * @since NEXT
 */
class WDS_ET_DE_Exact_Target_Admin {

	/**
	 * Parent plugin class
	 *
	 * @var    class
	 * @since  NEXT
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug
	 *
	 * @var    string
	 * @since  NEXT
	 */
	protected $key = 'wds_exacttarget_data_extension_api_admin';

	/**
	 * Options page metabox id.
	 *
	 * @var    string
	 * @since  NEXT
	 */
	protected $metabox_id = 'wds_exacttarget_data_extension_api_admin_metabox';

	/**
	 * Options Page title
	 *
	 * @var    string
	 * @since  NEXT
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 *
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->title = __( 'Exacttarget Data Extensions API Settings', 'wds-exacttarget-data-extension-api' );
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 *
	 * @author Aubrey Portwood
	 */
	public function hooks() {

		// Hook in.
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Options page.
		$this->options_page_hooks();

		// After the debug field, try and run the debug hook.
		add_action( 'cmb2_after_options-page_form_wds_exacttarget_data_extension_api_admin_metabox', array( $this, 'debug_hooks' ) );
	}

	/**
	 * Options page hooks.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 */
	public function options_page_hooks() {

		// Multi-site installs.
		if ( $this->plugin->is_multisite() ) {

			// If multi-site, add to Network > Settings menu.
			add_action( 'network_admin_menu', array( $this, 'add_options_page' ) );

			// Override CMB's getter.
			add_filter( 'cmb2_override_option_get_' . $this->key, array( $this, 'get_override' ) );

			// Override CMB's setter.
			add_filter( 'cmb2_override_option_save_' . $this->key, array( $this, 'update_override' ) );

		} else {

			// Add to individual site menu if not multi-site.
			add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		}

		// The options page (added always).
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}

	/**
	 * Replaces get_option with get_site_option.
	 *
	 * @since  NEXT
	 * @author Aubrey Portwood
	 */
	public function get_override( $test, $default = false ) {
		return get_site_option( $this->key, $default );
	}

	/**
	 * Replaces update_option with update_site_option.
	 *
	 * @since  NEXT
	 * @author Aubrey Portwood
	 */
	public function update_override( $test, $option_value ) {
		return update_site_option( $this->key, $option_value );
	}

	/**
	 * Register our setting to WP
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function admin_init() {

		register_setting( $this->key, $this->key );
	}

	/**
	 * Fire the debug hook.
	 *
	 * If enabled, this is where API tests should be run,
	 * on the <code>wds_et_de_debug</code> hook, so they
	 * can easily be enabled/disabled.
	 *
	 * @author Aubrey Portwood
	 * @since  NEXT
	 */
	public function debug_hooks() {

		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {

			// Only when WP_DEBUG allows.
			return;
		}

		// The CMB2 key.
		$key = 'wds_exacttarget_data_extension_api_admin';

		if ( is_multisite() ) {
			$settings = get_site_option( $key );
		} else {
			$settings = get_option( $key );
		}

		if ( isset( $settings['debug_hooks'] ) && 'on' === $settings['debug_hooks'] ) {

			// Start an area where people can dump output.
			echo sprintf( '<h3>%s</h3>', esc_attr__( 'Debug', 'wds-exacttarget-data-extension-api' ) );

			// Echo our markup for our debug field. Inline styles to prevent having to deal with a seperate stylesheet.
			echo '<pre style="background:#fff;border-radius:2px;border:1px solid #dadada;max-height:500px;overflow:scroll;padding:10px;">';

			// If debug hook enabled, fire the hook.
			do_action( 'wds_et_de_debug' );

			// End output.
			echo '</pre>';
		}
	}

	/**
	 * Add menu options page
	 *
	 * @since  NEXT
	 * @return void
	 *
	 * @author Aubrey Portwood
	 */
	public function add_options_page() {

		$parent_slug = $this->plugin->is_multisite() ? 'settings.php' : 'options-general.php';

		// Add a sub-menu page to options.
		$this->options_page = add_submenu_page( $parent_slug, $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );

		// Include CMB CSS in the head to avoid FOUC.
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function admin_page_display() {

		?>
		<div class="wrap cmb2-options-page <?php echo esc_attr( $this->key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add custom fields to the options page.
	 *
	 * @since  NEXT
	 * @return void
	 *
	 * @author Aubrey Portwood
	 */
	public function add_options_page_metabox() {

		// Bail on ajax requests.
		if ( wp_doing_ajax() ) {
			return;
		}

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(

				// These are important, don't remove.
				'key'   => 'options-page',
				'value' => array( $this->key ),
			),
		) );

		// Loading icon.
		$loading = '<span title="Checking connection...">?</span>';

		$cmb->add_field( array(
			'name'    => __( 'Client ID', 'wds-exacttarget-data-extension-api' ),
			'desc'    => sprintf( __( 'Client ID %s', 'wds-exacttarget-data-extension-api' ), $loading ),
			'id'      => 'client_id',
			'type'    => 'text',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Client Secret', 'wds-exacttarget-data-extension-api' ),
			'desc'    => sprintf( __( 'Client Secret %s', 'wds-exacttarget-data-extension-api' ), $loading ),
			'id'      => 'client_secret',
			'type'    => 'text',
		) );

		// Only in developer mode.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {

			/*
			 * Add a field where a hook can be enabled/disabled.
			 *
			 * This allows developers (of the plugin) to test API for development with
			 * a separate plugin.
			 *
			 * @author Aubrey Portwood
			 */
			$cmb->add_field( array(
				'name' => __( 'Execute Debug Hooks', 'wds-exacttarget-data-extension-api' ),
				'desc' => sprintf( __( 'When this is enabled, the action %s will be fired when this options page is loaded. This is where you can hook in and test the API.', 'wds-exacttarget-data-extension-api' ), '<code>wds_et_de_debug</code>' ),
				'id'   => 'debug_hooks',
				'type' => 'checkbox',
			) );
		}
	}
}
