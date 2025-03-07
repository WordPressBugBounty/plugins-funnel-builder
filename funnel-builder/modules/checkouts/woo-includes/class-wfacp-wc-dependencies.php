<?php
defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'WFACP_WC_Dependencies' ) ) {
	/**
	 * WC Dependency Checker
	 * Checks if WooCommerce is enabled
	 */
	#[AllowDynamicProperties]
	class WFACP_WC_Dependencies {

		private static $active_plugins;

		public static function woocommerce_active_check() {

			if ( ! self::$active_plugins ) {
				self::init();
			}

			if ( class_exists( 'WooCommerce' ) ) {
				return true;
			}

			return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
		}

		public static function init() {

			self::$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() ) {
				self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			}
		}

	}
}