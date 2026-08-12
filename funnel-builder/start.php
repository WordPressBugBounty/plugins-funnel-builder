<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * This file is to initiate WooFunnel core and to run some common methods and decide which WooFunnels core should run
 */
if ( ! class_exists( 'WooFunnel_Loader' ) ) {
	#[AllowDynamicProperties]

	class WooFunnel_Loader {

		public static $plugins       = array();
		public static $loaded        = false;
		public static $ultimate_path = '';
		public static $version       = null;


		public static function include_core() {

			$get_configuration = self::get_the_latest();
			if ( false === self::$loaded && $get_configuration && is_array( $get_configuration ) && isset( $get_configuration['class'] ) ) {

				if ( is_callable( array( $get_configuration['class'], 'load_files' ) ) ) {
					self::$version       = $get_configuration['version'];
					self::$ultimate_path = $get_configuration['plugin_path'] . '/woofunnels/';
					self::$loaded        = true;
					call_user_func( array( $get_configuration['class'], 'load_files' ) );

				}
			}
		}

		public static function register( $configuration ) {
			array_push( self::$plugins, $configuration );
		}

		public static function get_the_latest() {
			$get_all = self::$plugins;
			uasort(
				$get_all,
				function ( $a, $b ) {
					if ( version_compare( $a['version'], $b['version'], '=' ) ) {
						return 0;
					} else {
						return ( version_compare( $a['version'], $b['version'], '<' ) ) ? - 1 : 1;
					}
				}
			);

			$get_most_recent_configuration = end( $get_all );

			return $get_most_recent_configuration;
		}
	}
}


#[AllowDynamicProperties]

class WooFunnel_WFFN {

	public static $version = WFFN_BWF_VERSION;

	public static function register() {

		$configuration = array(
			'basename'    => plugin_basename( WFFN_PLUGIN_FILE ),
			'version'     => self::$version,
			'plugin_path' => dirname( WFFN_PLUGIN_FILE ),
			'class'       => __CLASS__,
			/**
			 * Marks this as a dismantled core (the FunnelKit common-core split:
			 * contact / compatibilities / usage-tracking / includes + fb/). Cores
			 * shipped by un-dismantled plugins do not carry this flag; that is how
			 * maybe_yield_to_full_core() tells them apart during the migration window.
			 */
			'dismantled'  => true,
		);
		WooFunnel_Loader::register( $configuration );
	}

	/**
	 * Transition arbitration.
	 *
	 * While any other plugin still ships an UN-dismantled (full) WooFunnels core,
	 * our minimal core must not win: it no longer ships every class those plugins
	 * hardcode-require (e.g. WooFunnels_Notifications, WooFunnels_Deactivate). So if
	 * any registered core lacks the 'dismantled' marker, withdraw our own
	 * registration and let the full core win include_core() -- we ride it (our fb/
	 * classes still load directly on woofunnels_loaded). Once every core is
	 * dismantled (all carry the marker) nobody withdraws and normal newest-wins
	 * arbitration resumes.
	 *
	 * Hooked on plugins_loaded at priority -2, just before include_core (-1). Lives
	 * here (our registrant), never in the shared WooFunnel_Loader class.
	 */
	public static function maybe_yield_to_full_core() {
		if ( ! class_exists( 'WooFunnel_Loader' ) || ! is_array( WooFunnel_Loader::$plugins ) || WooFunnel_Loader::$loaded ) {
			return;
		}

		$has_full_core = self::full_core_in_pool();

		/**
		 * Full cores shipped by plugins that register LATE are not in the pool yet.
		 * FunnelKit Automations, for example, requires its start.php on
		 * plugins_loaded:1 -- after this hook (-2) and after include_core (-1). If we
		 * only looked at the pool we would not see it, win with our minimal core, and
		 * it would fail (its connector/ framework, WFCO_Common, only exists in its own
		 * core). So force those late cores to register NOW by requiring their start.php,
		 * so include_core (-1) can pick the full core and load it before our own
		 * load_classes (also plugins_loaded:1) needs it.
		 */
		if ( false === $has_full_core ) {
			foreach ( self::undismantled_core_start_files() as $start_file ) {
				require_once $start_file; // runs the sibling's WooFunnel_*::register()
				$has_full_core = true;
			}
		}

		if ( false === $has_full_core ) {
			return; // Only dismantled cores present -- compete on version as usual.
		}

		foreach ( WooFunnel_Loader::$plugins as $key => $config ) {
			if ( isset( $config['class'] ) && __CLASS__ === $config['class'] ) {
				unset( WooFunnel_Loader::$plugins[ $key ] );
			}
		}
		WooFunnel_Loader::$plugins = array_values( WooFunnel_Loader::$plugins );
	}

	/**
	 * @return bool Whether any already-registered core lacks the 'dismantled' marker.
	 */
	private static function full_core_in_pool() {
		foreach ( WooFunnel_Loader::$plugins as $config ) {
			if ( empty( $config['dismantled'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Folders an UN-dismantled WooFunnels core ships that our dismantled core does NOT.
	 *
	 * The dismantle stripped these from FB's woofunnels/: the connector framework
	 * (connector/, home of WFCO_Common), the custom Action Scheduler store
	 * (as-data-store/), the pre-kernel bootstrap loader (bootstrap/), and the licence
	 * UI (legacy/). A plugin whose woofunnels/ ships ANY of them is running a full,
	 * un-dismantled core -- and MUST win arbitration so those classes exist. We probe
	 * on the whole set (not a single folder) because different un-dismantled core
	 * versions carry different subsets: e.g. FunnelKit Automations' current core has
	 * connector/ + as-data-store/ but NO bootstrap/ (kernel already folded elsewhere),
	 * so a bootstrap-only probe misses it and our minimal core wrongly wins, fataling
	 * WFCO_Common.
	 */
	private static function full_core_folder_markers() {
		return array( 'connector', 'as-data-store', 'bootstrap', 'legacy' );
	}

	/**
	 * Active plugins (other than us) that ship an UN-dismantled WooFunnels core which
	 * has not registered yet (e.g. FunnelKit Automations registers late, on
	 * plugins_loaded:1). Detected by the full-core folder markers above; their start.php
	 * sits at the plugin root.
	 *
	 * @return string[] Absolute paths to those start.php files.
	 */
	private static function undismantled_core_start_files() {
		$files = array();

		$plugins = array();
		if ( function_exists( 'wp_get_active_and_valid_plugins' ) ) {
			$plugins = (array) wp_get_active_and_valid_plugins();
		}
		if ( function_exists( 'wp_get_active_network_plugins' ) ) {
			$plugins = array_merge( $plugins, (array) wp_get_active_network_plugins() );
		}

		$our_dir = dirname( WFFN_PLUGIN_FILE );
		$markers = self::full_core_folder_markers();
		foreach ( $plugins as $plugin_file ) {
			$dir = dirname( $plugin_file );
			if ( $dir === $our_dir || ! is_readable( $dir . '/start.php' ) ) {
				continue;
			}
			foreach ( $markers as $marker ) {
				if ( is_dir( $dir . '/woofunnels/' . $marker ) ) {
					$files[] = $dir . '/start.php';
					break;
				}
			}
		}

		return $files;
	}


	public static function load_files() {

		$get_global_path = __DIR__ . '/woofunnels/';

		if ( false === @file_exists( $get_global_path . 'includes/class-woofunnels-api.php' ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'WooFunnels Core should be present in folder \'woofunnels\' in order to run this properly. ', 'funnel-builder' ), self::$version ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			die( 0 );
		}

		/**
		 * Loading Core XL Files
		 */
		require_once dirname( WFFN_PLUGIN_FILE ) . '/woofunnels/' . 'includes/class-woofunnels-dashboard-loader.php';

		if ( self::$version === BWF_VERSION ) {
			do_action( 'woofunnels_loaded', $get_global_path );
		} elseif ( ( defined( 'WFFN_IS_DEV' ) && true === WFFN_IS_DEV ) || ( defined( 'BWF_DEV' ) && true === BWF_DEV ) ) {
			_doing_it_wrong( __FUNCTION__, __( 'WooFunnels Core should be at the same version as declared in your start.php', 'funnel-builder' ), self::$version ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			die( 0 );
		}
	}
}

WooFunnel_WFFN::register();
