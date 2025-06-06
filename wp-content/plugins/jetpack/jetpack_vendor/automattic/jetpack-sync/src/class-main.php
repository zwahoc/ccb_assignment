<?php
/**
 * This class hooks the main sync actions.
 *
 * @package automattic/jetpack-sync
 */

namespace Automattic\Jetpack\Sync;

use Automattic\Jetpack\Sync\Actions as Sync_Actions;
use Automattic\Jetpack\Sync\Queue\Queue_Storage_Table;

/**
 * Jetpack Sync main class.
 */
class Main {

	/**
	 * Sets up event handlers for the Sync package. Is used from the Config package.
	 *
	 * @action plugins_loaded
	 */
	public static function configure() {
		if ( Actions::sync_allowed() ) {
			add_action( 'plugins_loaded', array( __CLASS__, 'on_plugins_loaded_early' ), 5 );
			add_action( 'plugins_loaded', array( __CLASS__, 'on_plugins_loaded_late' ), 90 );
		}

		// Add REST endpoints.
		add_action( 'rest_api_init', array( 'Automattic\\Jetpack\\Sync\\REST_Endpoints', 'initialize_rest_api' ) );

		// Add IDC disconnect action.
		add_action( 'jetpack_idc_disconnect', array( __CLASS__, 'on_jetpack_idc_disconnect' ), 100 );

		// Any hooks below are special cases that need to be declared even if Sync is not allowed.
		add_action( 'jetpack_site_registered', array( 'Automattic\\Jetpack\\Sync\\Actions', 'do_initial_sync' ), 10, 0 );

		// Sync clean up, when Jetpack is disconnected.
		add_action( 'jetpack_site_disconnected', array( __CLASS__, 'on_jetpack_site_disconnected' ), 1000 );

		// Set up package version hook.
		add_filter( 'jetpack_package_versions', __NAMESPACE__ . '\Package_Version::send_package_version_to_tracker' );

		// Add the custom capabilities for managing modules
		add_filter( 'map_meta_cap', array( __CLASS__, 'module_custom_caps' ), 10, 2 );
	}

	/**
	 * Sets the Module custom capabilities.
	 *
	 * @param  string[] $caps Array of the user's capabilities.
	 * @param  string   $cap  Capability name.
	 * @return string[] The user's capabilities, adjusted as necessary.
	 */
	public static function module_custom_caps( $caps, $cap ) {
		switch ( $cap ) {
			case 'jetpack_manage_modules':
			case 'jetpack_activate_modules':
			case 'jetpack_deactivate_modules':
				$caps = array( 'manage_options' );
				break;
			case 'jetpack_configure_modules':
				$caps = array( 'manage_options' );
				break;
		}
		return $caps;
	}

	/**
	 * Delete all sync related data on Identity Crisis disconnect.
	 */
	public static function on_jetpack_idc_disconnect() {
		Sender::get_instance()->uninstall();
	}

	/**
	 * Sync cleanup on shutdown.
	 */
	public static function on_jetpack_site_disconnected() {
		add_action( 'shutdown', array( __CLASS__, 'sync_cleanup' ), 10000 );
	}

	/**
	 * Delete all sync related data on Site disconnect / clean up custom table.
	 * Needs to happen on shutdown to prevent fatals.
	 */
	public static function sync_cleanup() {
		Sender::get_instance()->uninstall();

		$table_storage = new Queue_Storage_Table( 'test_queue' );
		$table_storage->drop_table();
	}

	/**
	 * Sets the Sync data settings.
	 *
	 * @param array $data_settings An array containing the Sync data options. An empty array indicates that the default
	 *                             values will be used for all Sync data.
	 */
	public static function set_sync_data_options( $data_settings = array() ) {
		( new Data_Settings() )->add_settings_list( $data_settings );
	}

	/**
	 * Initialize the main sync actions.
	 *
	 * @action plugins_loaded
	 */
	public static function on_plugins_loaded_early() {
		/**
		 * Additional Sync modules can be carried out into their own packages and they
		 * will get their own config settings.
		 *
		 * For now additional modules are enabled based on whether the third party plugin
		 * class exists or not.
		 */
		Sync_Actions::initialize_search();
		Sync_Actions::initialize_woocommerce();
		Sync_Actions::initialize_wp_super_cache();

		// We need to define this here so that it's hooked before `updating_jetpack_version` is called.
		add_action( 'updating_jetpack_version', array( 'Automattic\\Jetpack\\Sync\\Actions', 'cleanup_on_upgrade' ), 10, 2 );
	}

	/**
	 * Runs after most of plugins_loaded hook functions have been run.
	 *
	 * @action plugins_loaded
	 */
	public static function on_plugins_loaded_late() {
		/*
		 * Init after plugins loaded and before the `init` action. This helps with issues where plugins init
		 * with a high priority or sites that use alternate cron.
		 */
		Sync_Actions::init();

		// Enable non-blocking Jetpack Sync flow.
		$non_block_enabled = (bool) get_option( 'jetpack_sync_non_blocking', false );

		/**
		 * Filters the option to enable non-blocking sync.
		 *
		 * Default value is false, filter to true to enable non-blocking mode which will have
		 * WP.com return early and use the sync/close endpoint to check-in processed items.
		 *
		 * @since 1.12.3
		 *
		 * @param bool $enabled Should non-blocking flow be enabled.
		 */
		$filtered = (bool) apply_filters( 'jetpack_sync_non_blocking', $non_block_enabled );

		if ( $non_block_enabled !== $filtered ) {
			update_option( 'jetpack_sync_non_blocking', $filtered, false );
		}

		// Initialize health-related hooks after plugins have loaded.
		Health::init();
	}
}
