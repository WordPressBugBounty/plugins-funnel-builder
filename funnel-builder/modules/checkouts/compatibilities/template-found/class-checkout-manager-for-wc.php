<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkout Manager for WooCommerce By QuadLayers (WooCommerce Checkout Manager 7.6.5)
 * Plugin URI: https://wordpress.org/plugins/woocommerce-checkout-manager/
 */
if ( ! class_exists( 'WFACP_Compatibility_With_Checkout_Manager_For_WC' ) ) {
	#[AllowDynamicProperties]
	class WFACP_Compatibility_With_Checkout_Manager_For_WC {
		public function __construct() {
			add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_locale' ] );
			add_action( 'wfacp_before_process_checkout_template_loader', [ $this, 'remove_posted_data' ] );
			add_filter( 'wfacp_form_field_key', [ $this, 'update_key' ], 10, 2 );

		}

		public function remove_locale() {

			WFACP_Common::remove_actions( 'woocommerce_get_country_locale_default', 'WOOCCM_Fields_Handler', 'remove_fields_priority' );
			WFACP_Common::remove_actions( 'woocommerce_get_country_locale_base', 'WOOCCM_Fields_Handler', 'remove_fields_priority' );
			WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'WOOCCM_Checkout_Controller', 'enqueue_scripts' );

		}

		public function remove_posted_data() {
			WFACP_Common::remove_actions( 'woocommerce_checkout_posted_data', 'WOOCCM_Fields_Handler', 'remove_address_fields' );
		}

		public function update_key( $key, $args ) {
			if ( false !== strpos( $key, 'wooccm' ) && isset( $args['id'] ) && ! empty( $args['id'] ) ) {
				$key = $args['id'];
			}

			return $key;
		}
	}

	WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Checkout_Manager_For_WC(), 'wfacp-checkout-manager-wc' );


}