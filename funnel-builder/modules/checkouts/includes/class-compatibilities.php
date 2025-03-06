<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WFACP_Plugin_Compatibilities' ) ) {
	/**
	 * #[AllowDynamicProperties]
	 *
	 * class WFACP_Plugin_Compatibilities
	 * Loads all the compatibilities files we have to provide compatibility with each plugin
	 */
	#[AllowDynamicProperties]
	class WFACP_Plugin_Compatibilities {
		public static $plugin_compatibilities = array();

		public static function load_all_compatibilities() {
			// load all the WFACP_Compatibilities files automatically

			add_action( 'plugins_loaded', [ __CLASS__, 'plugin_loaded' ], - 1 );
			add_action( 'after_setup_theme', [ __CLASS__, 'setup_theme' ], - 1 );
			add_action( 'wfacp_template_load', [ __CLASS__, 'template_found' ], - 1 );
		}

		public static function register( $object, $slug ) {
		}


		public static function plugin_loaded() {
			$files = array(
				'gateways/class-afterpay.php'                                    => class_exists( 'WC_Gateway_Afterpay' ),//Move to template found
				'gateways/class-amazon-pay.php'                                  => function_exists( 'wc_apa' ),
				'gateways/class-angel-eye-ppcp.php'                              => class_exists( 'AngellEYE_Gateway_Paypal' ),
				'gateways/class-angel-eye.php'                                   => class_exists( 'AngellEYE_Gateway_Paypal' ),
				'gateways/class-datatrans.php'                                   => function_exists( 'woocommerce_datatranscw_is_plugin_page' ),
				'gateways/class-kco.php'                                         => class_exists( 'KCO' ),
				'gateways/class-paynowone.php'                                   => function_exists( 'woocommerce_payonecw_is_plugin_page' ),//Move to setup theme
				'gateways/class-payoneer-checkout.php'                           => class_exists( 'Inpsyde\Modularity\Package' ),
				'gateways/class-paypal-express.php'                              => function_exists( 'wc_gateway_ppec' ),
				'gateways/class-paysoncheckout-for-wc.php'                       => class_exists( 'PaysonCheckout_For_WooCommerce' ),
				'gateways/class-peachpay-for-wc.php'                             => function_exists( 'peachpay_init' ),
				'gateways/class-przelewy24-payment-gateway.php'                  => function_exists( 'woocommerce_p24_add_gateway' ),
				'gateways/class-stripe.php'                                      => function_exists( 'woocommerce_gateway_stripe' ),
				'gateways/class-wc-affirm.php'                                   => function_exists( 'affirm' ),
				'gateways/class-wc-gateway-redsys.php'                           => defined( 'REDSYS_VERSION' ),
				'gateways/class-wc-twint.php'                                    => function_exists( 'woocommerce_datatranscw_create_checkouts' ),
				'gateways/class-webtoffy.php'                                    => defined( 'EH_PAYPAL_VERSION' ),
				'gateways/class-woocommerce-paypal-payments.php'                 => class_exists( '\WooCommerce\PayPalCommerce\PluginModule', true ),
				'gateways/class-woocommerce-quickpay.php'                        => function_exists( 'init_quickpay_gateway' ),
				'plugins/class-aioseo.php'                                       => function_exists( 'aioseo' ),
				'plugins/class-borlabs-cookie.php'                               => defined( 'BORLABS_COOKIE_VERSION' ),
				'plugins/class-breakdance-builder.php'                           => defined( 'BREAKDANCE_WOO_DIR' ),
				'plugins/class-complianz-gdpr-ccpa-cookie-consent.php'           => class_exists( 'COMPLIANZ' ),
				'plugins/class-conditional-discounts-for-wc-by-orion.php'        => function_exists( 'run_wad' ),
				'plugins/class-ddpro.php'                                        => function_exists( 'ddp_check_ddpdm' ),
				'plugins/class-divi-body-commerce.php'                           => defined( 'DE_DB_WOO_VERSION' ),
				'plugins/class-germanized.php'                                   => class_exists( 'WooCommerce_Germanized' ),
				'plugins/class-happy-elementor.php'                              => function_exists( 'ha_let_the_journey_begin' ),
				'plugins/class-indeed-ultimate-affiliate-pro.php'                => class_exists( 'UAP_Main' ),
				'plugins/class-lubenda-cookie-solution.php'                      => class_exists( 'iubenda' ),
				'plugins/class-optimizepress.php'                                => ( isset( $_REQUEST['page'] ) && is_string( $_REQUEST['page'] ) && false !== strpos( $_REQUEST['page'], 'optimizepress' ) ),
				'plugins/class-oxygen-builder.php'                               => defined( 'CT_VERSION' ),
				'plugins/class-pdf-invoice-packing-slip.php'                     => class_exists( 'WPO_WCPDF' ),
				'plugins/class-pixel-cog.php'                                    => defined( 'PIXEL_COG_VERSION' ),
				'plugins/class-polylang.php'                                     => defined( 'POLYLANG_VERSION' ),
				'plugins/class-product-composite.php'                            => class_exists( 'WC_Composite_Products' ),
				'plugins/class-shortpixel-image-optimizer.php'                   => defined( 'SHORTPIXEL_PLUGIN_FILE' ),// MOve to template found
				'plugins/class-siteorigin.php'                                   => class_exists( 'SiteOrigin_Panels' ),
				'plugins/class-skyverge-url-coupons.php'                         => function_exists( 'wc_url_coupons' ),
				'plugins/class-ti-wishlist.php'                                  => class_exists( 'TINVWL_URL' ),
				'plugins/class-wc-avatax.php'                                    => class_exists( 'WC_AvaTax_Loader' ),
				'plugins/class-wc-chained-product.php'                           => defined( 'WC_CP_PLUGIN_DIRNAME' ),
				'plugins/class-wc-force-sell.php'                                => class_exists( 'WC_Force_Sells' ),
				'plugins/class-wc-post-nl.php'                                   => class_exists( 'WCPOST' ) || class_exists( 'WooCommerce_PostNL' ),
				'plugins/class-wcbooster.php'                                    => class_exists( 'WC_Jetpack' ),
				'plugins/class-wcl-parcel.php'                                   => class_exists( 'WCMYPA' ),
				'plugins/class-wf-cart-hooper-.php'                              => class_exists( 'WFCH_Core' ),
				'plugins/class-wfacp-wc-membership.php'                          => class_exists( 'WC_Memberships_Loader' ),
				'plugins/class-woo-cart-abandonment-recovery.php'                => class_exists( 'CARTFLOWS_CA_Loader' ),
				'plugins/class-woo-products-addons-by-wc.php'                    => function_exists( 'woocommerce_product_addons_init' ),
				'plugins/class-woochimp.php'                                     => function_exists( 'SSWCMC' ) || function_exists( '_mc4wp_load_plugin' ) || class_exists( 'WooChimp' ),
				'plugins/class-woocommerce-checkout-field-editor.php'            => function_exists( 'wc_checkout_fields_load' ),
				'plugins/class-woocommerce-coupon-messages.php'                  => function_exists( 'woocommerce_coupon_messages_plugins_loaded' ),
				'plugins/class-woocommerce-pre-orders.php'                       => function_exists( 'woocommerce_pre_orders_load_block_classes' ),
				'plugins/class-woosb.php'                                        => function_exists( 'woosb_init' ),
				'plugins/class-wp-zasielkovna.php'                               => function_exists( 'run_wp_zasielkovna_shipping' ),//Move to template found
				'plugins/class-wpc-quanity-premium.php'                          => function_exists( 'woopq_init' ),
				'plugins/class-wpml.php'                                         => class_exists( 'SitePress' ),
				'plugins/class-xlwcty.php'                                       => class_exists( 'XLWCTY_Core' ),
				'plugins/class-yith-discount.php'                                => class_exists( 'YITH_WC_Dynamic_Discounts' ),
				'plugins/class-yith-subscription.php'                            => defined( 'YITH_YWSBS_VERSION' ),
				'plugins/class-yith-woocommerce-ajax-product-filter-premium.php' => class_exists( 'YITH_WCAN' ) || defined( 'YITH_WCAN' ),
				'plugins/class-yth-wcdppm.php'                                   => function_exists( 'yith_wcdppm_install' ),
				'others/class-buy-now-btn-by-wpismylife.php'                     => class_exists( 'Buy_Now_Woo\Plugin' ),//MOve to template found
				'others/class-extended-coupon.php'                               => ( class_exists( 'WJECF_Bootstrap' ) && class_exists( 'WC_Subscriptions_Coupon' ) ),//MOve to template,
				'others/class-redis-cache.php'                                   => defined( 'WP_REDIS_FILE' ),
				'others/class-seo-wp.php'                                        => defined( 'WPSEO_VERSION' ),
				'others/class-wc-deposite.php'                                   => class_exists( '\Webtomizer\WCDP\WC_Deposits' ),
				'others/class-woocommerce-checkout-addons.php'                   => class_exists( 'WC_Checkout_Add_Ons_Loader' ),
				'others/class-wpawll-customizer.php'                             => class_exists( 'WPAWLL_Customizer' ),
				'others/class-wpml-wcml.php'                                     => class_exists( 'SitePress' ) && class_exists( 'woocommerce_wpml' ) && class_exists( 'WCML_Cart' ),
				'library/class-elementor.php'                                    => defined( 'ELEMENTOR_VERSION' ),
				'library/class-jetpack.php'                                      => class_exists( 'JETPACK__VERSION' ),
				'library/class-oxygen-elementor-conflict.php'                    => defined( 'ELEMENTOR_VERSION' ) && defined( 'CT_VERSION' ),
				'others/class-themes.php'                                        => true,
				'others/class-wc-order-pay.php'                                  => true,
				'library/class-add-address-field.php'                            => true,
				'library/class-insert-field-after-other-field.php'               => true,
			);
			self::add_files( $files );
		}

		public static function setup_theme() {
			$files = [
				'setup-theme/class-active-campaign-for-wc.php'          => class_exists( 'Activecampaign_For_Woocommerce' ),
				'setup-theme/class-aelia-cs.php'                        => class_exists( 'Aelia\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher' ),
				'setup-theme/class-beaver-builder.php'                  => class_exists( 'FLBuilderLoader' ),
				'setup-theme/class-brazil-field-2.0.php'                => class_exists( 'Extra_Checkout_Fields_For_Brazil_Front_End' ),
				'setup-theme/class-bridge-core.php'                     => class_exists( 'BridgeCore' ),
				'setup-theme/class-cartflows.php'                       => class_exists( 'Cartflows_Checkout_Markup' ),
				'setup-theme/class-checkout-field-editor.php'           => function_exists( 'thwcfd_init_checkout_field_editor_lite' ) || defined( 'WOOCCM_PATH' ) || function_exists( 'woocommerce_init_checkout_field_editor' ),
				'setup-theme/class-convert-kit-wc.php'                  => class_exists( 'CKWC_Integration' ),
				'setup-theme/class-divi-builder.php'                    => class_exists( 'ET_Builder_Plugin', false ),
				'setup-theme/class-elementor-pro.php'                   => defined( 'ELEMENTOR_VERSION' ) && class_exists( 'ElementorPro\Plugin' ),
				'setup-theme/class-fifu.php'                            => function_exists( 'fifu_woo_template' ),
				'setup-theme/class-gumlet.php'                          => class_exists( 'Gumlet' ),
				'setup-theme/class-product-bundles.php'                 => function_exists( 'wc_pb_get_bundled_item' ),
				'setup-theme/class-quantites-and-units.php'             => class_exists( 'WC_Quantities_and_Units' ),
				'setup-theme/class-smart-coupon.php'                    => class_exists( 'WC_Smart_Coupons' ),
				'setup-theme/class-thrive-theme-builder.php'            => defined( 'TVE_PLUGIN_FILE' ) || function_exists( 'thrive_theme' ),
				'setup-theme/class-wc-billing-fields-ro.php'            => function_exists( 'woocommerce_billing_fields_ro' ),
				'setup-theme/class-wc-min-max-qty.php'                  => class_exists( 'WC_Min_Max_Quantities' ),
				'setup-theme/class-wc-shipping-rates-by-city.php'       => class_exists( 'ShipRate_Public' ),
				'setup-theme/class-wc-thanku-customizer-page.php'       => class_exists( 'WOOCOMMERCE_THANK_YOU_PAGE_CUSTOMIZER' ),
				'setup-theme/class-wc_custom_thankyou.php'              => class_exists( 'WC_Custom_Thankyou' ),
				'setup-theme/class-wfacp-woocommerce-gift-card.php'     => function_exists( 'WC_GC' ),
				'setup-theme/class-woo-credit.php'                      => class_exists( 'Woo_Download_Credits_Platinum' ),
				'setup-theme/class-woomulti.php'                        => class_exists( 'WOOMULTI_CURRENCY' ),
				'setup-theme/class-wpbisnis-ongkir.php'                 => class_exists( 'WPBisnis_WC_Indo_Ongkir_Init' ),
				'setup-theme/fox-woocs-exchange.php'                    => class_exists( 'WOOCS' ),
				'themes/class-abolire.php'                              => defined( 'ABOLIRE_THEME_VERSION' ),
				'themes/class-astra.php'                                => defined( 'ASTRA_THEME_VERSION' ),
				'themes/class-atelier.php'                              => function_exists( 'sf_custom_styles' ),
				'themes/class-avada.php'                                => defined( 'AVADA_VERSION' ),
				'themes/class-biagiotti.php'                            => function_exists( 'ts_product_image_on_checkout' ),
				'themes/class-blosky.php'                               => class_exists( 'Blocksy_Manager' ),
				'themes/class-brick.php'                                => defined( 'BRICKS_VERSION' ),
				'themes/class-buddyboss.php'                            => function_exists( 'buddyboss_theme' ),
				'themes/class-buzzstore.php'                            => function_exists( 'buzzstorepro_setup' ),
				'themes/class-divi.php'                                 => function_exists( 'et_setup_theme' ),
				'themes/class-edumy.php'                                => class_exists( 'Edumy_Elementor_Extensions' ) && class_exists( 'Elementor\Plugin' ),
				'themes/class-electro-extension.php'                    => class_exists( 'Electro_Elementor_Extensions' ) && class_exists( 'Elementor\Plugin' ),
				'themes/class-electro.php'                              => class_exists( 'TGM_Plugin_Activation' ),
				'themes/class-elessi.php'                               => function_exists( 'elessi_setup' ),
				'themes/class-enfold.php'                               => function_exists( 'avia_lang_setup' ),
				'themes/class-estore.php'                               => function_exists( 'estore_setup' ),
				'themes/class-flatsome.php'                             => class_exists( 'Flatsome_Default' ),
				'themes/class-generatepress.php'                        => function_exists( 'generate_setup' ),
				'setup-theme/class-generatepress-addons.php'            => ( defined( 'GP_PREMIUM_VERSION' ) || defined( 'GENERATE_VERSION' ) ),
				'themes/class-goya.php'                                 => function_exists( 'goya_theme_setup' ),
				'themes/class-hestia.php'                               => defined( 'HESTIA_VERSION' ),
				'themes/class-jupiter.php'                              => class_exists( 'MK_Customizer' ),
				'themes/class-konte.php'                                => class_exists( 'Konte_WooCommerce_Template_Checkout' ),
				'themes/class-legenda.php'                              => function_exists( 'etheme_theme_setup' ),
				'themes/class-leka.php'                                 => function_exists( 'arexworks_woocommerce_before_checkout_form' ),
				'themes/class-nitro.php'                                => class_exists( 'WR_Nitro' ),
				'themes/class-north.php'                                => function_exists( 'thb_body_classes' ),
				'themes/class-ocean.php'                                => class_exists( 'OCEANWP_Theme_Class' ),
				'themes/class-online-shop.php'                          => function_exists( 'online_shop_customize_register' ),
				'themes/class-porto.php'                                => function_exists( 'porto_setup' ),
				'themes/class-puca.php'                                 => function_exists( 'puca_tbay_setup' ),
				'themes/class-rehub.php'                                => defined( 'RH_MAIN_THEME_VERSION' ),
				'themes/class-revo.php'                                 => function_exists( 'revo_setup' ),
				'themes/class-salient.php'                              => function_exists( 'nectar_hooks_init' ),
				'themes/class-savoy.php'                                => defined( 'NM_THEME_DIR' ),
				'themes/class-shella.php'                               => defined( 'SHELLA__PATH' ),
				'themes/class-shop-isle-by-themeIsle.php'               => function_exists( 'shop_isle_setup' ),
				'themes/class-shopkeeper.php'                           => defined( 'SHOPKEEPER_WOOCOMMERCE_IS_ACTIVE' ),
				'themes/class-shoptimizer.php'                          => defined( 'SHOPTIMIZER_CORE' ),
				'themes/class-sonaar-for-elementor.php'                 => class_exists( 'Elementor_Sonaar' ) && class_exists( 'Elementor\Plugin' ),
				'themes/class-storefront.php'                           => function_exists( 'storefront_is_woocommerce_activated' ),
				'themes/class-theme-gen.php'                            => function_exists( 'thegem_setup' ),
				'themes/class-theme-nave.php'                           => defined( 'NEVE_VERSION' ),
				'themes/class-theme-pro.php'                            => function_exists( 'x_bootstrap' ),
				'themes/class-themify-ultra.php'                        => function_exists( 'themify_is_themify_theme' ),
				'themes/class-twenty-twenty-22.php'                     => function_exists( 'twentytwentytwo_support' ),
				'themes/class-uncode.php'                               => function_exists( 'uncode_setup' ),
				'themes/class-understrap.php'                           => function_exists( 'understrap_setup_theme_default_settings' ),
				'themes/class-unero.php'                                => function_exists( 'unero_setup' ),
				'themes/class-us-theme.php'                             => function_exists( 'us_woocomerce_dequeue_checkout_styles' ),
				'themes/class-woodmart.php'                             => function_exists( 'woodmart_load_classes' ),
				'themes/class-x-store.php'                              => function_exists( 'etheme_theme_setup' ),
				'themes/class-xpro.php'                                 => function_exists( 'x_bootstrap' ),
				'themes/class-zerif-by-themeIsle.php'                   => function_exists( 'zerif_setup' ),
				'ecrm/class-activewoo.php'                              => ( function_exists( 'G3D_APP' ) || class_exists( 'WC_Active_Woo' ) ),
				'ecrm/class-aelia-vat-field.php'                        => class_exists( 'Aelia\WC\EU_VAT_Assistant\WC_Aelia_EU_VAT_Assistant' ) && isset( $GLOBALS['wc-aelia-eu-vat-assistant'] ),
				'ecrm/class-automate-woo.php'                           => class_exists( 'AW_Birthdays_Addon' ) || class_exists( 'AutomateWoo\Hooks' ),
				'ecrm/class-aweber.php'                                 => class_exists( 'WC_Aweber' ),
				'ecrm/class-constant-contact.php'                       => function_exists( 'wc_constant_contact' ),
				'ecrm/class-conversio.php'                              => class_exists( 'Conversio_Front_End' ),
				'ecrm/class-creative-mail-by-constant-contact.php'      => function_exists( '_load_ce4wp_plugin' ),
				'ecrm//class-get-response-wc.php'                       => class_exists( '\Getresponse\WordPress\GetResponse' ),
				'ecrm/class-hubspot-makewebbetter.php'                  => class_exists( 'Hubwoo_Public' ),
				'ecrm/class-klaviyo.php'                                => class_exists( 'WooCommerceKlaviyo' ),
				'ecrm/class-mailjet-for-wp.php'                         => class_exists( 'MailjetPlugin\Includes\Mailjet' ),
				'ecrm//class-metorik-helper.php'                        => class_exists( 'Metorik_Helper_Carts' ),
				'ecrm/class-sst.php'                                    => class_exists( 'SimpleSalesTax' ),
				'ecrm/class-wc-ac-hook.php'                             => class_exists( 'mtreherne\WC_AC_Hook\WC_AC_Hook' ),
				'ecrm/class-wc-active-campaign-integration.php'         => class_exists( 'AW_Newsletter' ),
				'ecrm/class-wc-eu-vat.php'                              => class_exists( 'WC_EU_VAT_Compliance_VAT_Number' ),
				'ecrm/class-wc-eu-vat-official.php'                     => defined( 'WC_EU_VAT_VERSION' ),
				'ecrm/class-wc-subscribe-to-newsletter.php'             => class_exists( 'WC_Subscribe_To_Newsletter' ),
				'ecrm/class-wcdrip.php'                                 => function_exists( 'wcdrip_get_settings' ),
				'fields/class-affiliatewp-checkout-referrals-wc.php'    => class_exists( 'AffiliateWP_Checkout_Referrals' ),
				'fields/class-eu-uk-vat-manager-for-wc.php'             => class_exists( 'Alg_WC_EU_VAT' ),
				'fields/class-fluent-pro.php'                           => defined( 'FLUENTCAMPAIGN_DIR_FILE' ),
				'fields/class-mailpoet.php'                             => defined( 'MAILPOET_VERSION' ),
				'fields/class-omnisend-for-wc.php'                      => function_exists( 'omnisend_woocommerce_menu' ),
				'fields/class-ry-wc-ecpay.php'                          => class_exists( 'RY_WEI' ),
				'fields/class-sg-checkout-location-picker-for-wc.php'   => function_exists( 'run_sg_checkout_location_picker' ),
				'fields/class-sumo-payment-plans.php'                   => class_exists( 'SUMOPaymentPlans' ),
				'fields/class-th-checkout-field-editor-pro.php'         => function_exists( 'run_thwcfe' ),
				'fields/class-the-courier-guy-shipping-for-wc.php'      => class_exists( 'CustomPluginDependencies' ),
				'fields/class-tickera-bridge-for-wc.php'                => class_exists( 'TC_WooCommerce_Bridge' ),
				'fields/class-uncanny-group-of-learn-dash.php'          => function_exists( 'ulgm' ),
				'fields/class-wc-disability-vat-exemption.php'          => class_exists( 'WC_Disability_VAT_Exemption' ),
				'fields/class-wc-multiple-customer-addresses.php'       => function_exists( 'wcmca_init_act' ),
				'fields/class-wc-multivender-marketplace.php'           => class_exists( 'WCFMmp' ),
				'fields/class-wc-quaderno.php'                          => class_exists( 'WooCommerce_Quaderno' ),
				'fields/class-wc-shipping-multiple-address.php'         => class_exists( 'WC_Ship_Multiple' ),
				'fields/class-wc-taxamo.php'                            => class_exists( 'WooCommerce_Taxamo' ),
				'fields/class-wcnl-post-code-checker.php'               => class_exists( 'WPO_WC_Postcode_Checker' ),
				'fields/class-wfacp-FFI-API.php'                        => class_exists( 'Ffl_Api_Public' ),
				'fields/class-wfacp-class-tipping.php'                  => function_exists( 'wpslash_tipping_woocommerce_checkout_order_review_form' ),
				'fields/class-wfacp-class-wc-pdf-italian-addon.php'     => class_exists( 'WooCommerce_Italian_add_on' ),
				'fields/class-wfacp-coderockz-woo-delivery.php'         => class_exists( 'Coderockz_Woo_Delivery_Public' ),
				'fields/class-wfacp-delivery-date-for-wc.php'           => class_exists( 'DDFW_Public' ),
				'fields/class-wfacp-facturare-wc.php'                   => function_exists( 'run_woo_facturare' ),
				'fields/class-wfacp-fattura-24.php'                     => class_exists( 'FATT_24_PLUGIN_DATA' ),
				'fields/class-wfacp-mailchimp-for-wc.php'               => class_exists( 'MailChimp_Newsletter' ),
				'fields/class-wfacp-mds-colivery.php'                   => class_exists( 'MdsColliveryService' ),
				'fields/class-wfacp-order-delivery-date-tyche-lite.php' => class_exists( 'Order_Delivery_Date_Lite' ),
				'fields/class-wfacp-order-delivery-date-tyche.php'      => class_exists( 'order_delivery_date' ),
				'fields/class-wfacp-routeapp-integration.php'           => function_exists( 'run_routeapp' ),
				'fields/class-wfacp-shipping-email-phone.php'           => class_exists( 'F4\WCSPE\Core\Hooks' ),
				'fields/class-wfacp-tefacturo-lt.php'                   => function_exists( 'add_c_comp' ) || function_exists( 'add_ruc' ) || function_exists( 'custom_checkout_question_field' ),
				'fields/class-wfacp-transdirect-shipping.php'           => function_exists( 'woocommerce_transdirect_init' ),
				'fields/class-wfacp-wc-delivery.php'                    => class_exists( 'WooCommerce_Delivery' ),
				'fields/class-wfacp-wc-german-market.php'               => class_exists( 'Woocommerce_German_Market' ),
				'fields/class-wfacp-wc-italian-add-on.php'              => class_exists( 'WooCommerce_Italian_add_on_plus' ),
				'fields/class-wfacp-wc-myparcel-2.1.4.php'              => class_exists( 'WCMYPA' ),
				'fields/class-wfacp-wc-order-delivery-date.php'         => class_exists( 'WC_Order_Delivery' ),
				'fields/class-wfacp-wc-sendinblue.php'                  => defined( 'SENDINBLUE_WC_ROOT_PATH' ),
				'fields/class-wfacp-wc-timologia.php'                   => function_exists( 'tfwc_get_keys_labels' ),
				'fields/class-wfacp-wfirma-wc.php'                      => class_exists( 'WPDesk\WooCommerceWFirma\WoocommerceIntegration' ),
				'fields/class-wfacp-woo-fakturownia.php'                => class_exists( 'FakturowniaVendor\WPDesk\Invoices\Field\FormField' ),
				'fields/class-wfacp-woo-mailerlite.php'                 => class_exists( 'Woo_Mailerlite' ),
				'fields/class-wfacp-woo-postnl.php'                     => class_exists( 'Woocommerce_PostNL_Postcode_Fields' ) || class_exists( 'WCPOST' ),
				'fields/class-wfacp-woocommerce-ups.php'                => class_exists( 'UPS_WooCommerce_Shipping' ),
				'fields/class-wfacp-yth-wc-points-rewards.php'          => function_exists( 'yith_ywpar_premium_constructor' ),
				'fields/class-woo-delivery-slots-primium.php'           => class_exists( 'Iconic_WDS' ),
				'fields/class-woocommerce-zoom-meeting.php'             => ( function_exists( 'woocommerce_to_zoom_checkout_fields' ) || function_exists( 'woocommerce_to_zoom_meetings_checkout_fields' ) ),
				'fields/class-yth-delivery-date-shipping-manager.php'   => function_exists( 'yith_delivery_date_init_plugin' ),
				'fields/class-yth-wc-eu-vat.php'                        => function_exists( 'yith_ywev_premium_init' ),
				'template-found/class-price-based-on-countries.php'     => class_exists( 'WC_Product_Price_Based_Country' ),
			];
			self::add_files( $files );
		}

	public static function template_found() {
		$files = array(
			'template-found/chronopost.php'                                           => class_exists( 'Chronopost_Food' ),
			'template-found/class-advanced-dynamic-pricing.php'                       => class_exists( 'WDP_Functions' ),
			'template-found/class-auto-address-populated.php'                         => class_exists( 'WC_Address_Validation' ),
			'template-found/class-autonami.php'                                       => class_exists( 'BWFAN_Core' ),
			'template-found/class-checkout-manager-for-wc.php'                        => function_exists( 'WOOCCM' ),
			'template-found/class-checkout-user-switch.php'                           => class_exists( 'user_switching' ),
			'template-found/class-checkout-wc.php'                                    => class_exists( 'Objectiv\Plugins\Checkout\Main' ),
			'template-found/class-currency-per-product.php'                           => class_exists( 'Alg_WC_CPP' ),
			'template-found/class-dhl-wc.php'                                         => class_exists( 'PR_DHL_Front_End_Paket' ),
			'template-found/class-donation-for-wc.php'                                => class_exists( 'WcDonation' ),
			'template-found/class-ebanx.php'                                          => class_exists( 'WC_EBANX' ),
			'template-found/class-ecpay-logistics.php'                                => class_exists( 'ECPayShippingMethods' ),
			'template-found/class-elementor.php'                                      => defined( 'ELEMENTOR_VERSION' ),
			'template-found/class-everypay.php'                                       => function_exists( 'everypay_init' ),
			'template-found/class-finale.php'                                         => class_exists( 'WCCT_Appearance' ),
			'template-found/class-foo-events.php'                                     => class_exists( 'FooEvents_Checkout_Helper' ),
			'template-found/class-free-gift-by-woocommerce.php'                       => class_exists( 'FP_Free_Gift' ),
			'template-found/class-gift-cart-make-web-better.php'                      => class_exists( 'Woocommerce_Gift_Cards_Lite_Public' ),
			'template-found/class-google-captcha-pro.php'                             => function_exists( 'gglcptchpr_init' ),
			'template-found/class-gutenberg-product-block.php'                        => class_exists( '\Automattic\WooCommerce\Blocks\Package' ),
			'template-found/class-improved-variable-product-attributes.php'           => class_exists( 'WC_Improved_Variable_Product_Attributes_Init' ),
			'template-found/class-infusewoo.php'                                      => defined( 'INFUSEDWOO_PRO_VER' ),
			'template-found/class-iw_tcs_freebie.php'                                 => class_exists( 'IW_TCS_Freebie' ),
			'template-found/class-japanized-for-woocommerce.php'                      => class_exists( 'JP4WC_Delivery' ),
			'template-found/class-klarna.php'                                         => apply_filters( 'wfacp_klarna_compatability', class_exists( 'WC_Klarna_Payments' ) || class_exists( 'WC_Stripe_Manager' ) ),
			'template-found/class-local-pickup-plus.php'                              => class_exists( 'WC_Shipping_Local_Pickup_Plus' ),
			'template-found/class-magic-order.php'                                    => function_exists( 'load_custom_mgo_style_front' ) || function_exists( 'magic_order_header' ),
			'template-found/class-marcado-pago.php'                                   => class_exists( 'WC_WooMercadoPago_Init' ) || class_exists( 'WC_PropulsePay' ) || class_exists( 'MercadoPago\Woocommerce\WoocommerceMercadoPago' ),
			'template-found/class-maximum-products-per-user.php'                      => class_exists( 'Alg_WC_MPPU_Core' ),
			'template-found/class-mondialrelay-wp.php'                                => class_exists( 'class_MRWP_public' ),
			'template-found/class-ogf.php'                                            => function_exists( 'ogf_customize_register' ),
			'template-found/class-one-page-checkout.php'                              => class_exists( 'PP_One_Page_Checkout' ),
			'template-found/class-p24.php'                                            => function_exists( 'woocommerce_p24_init' ),
			'template-found/class-paidmembership-pro.php'                             => function_exists( 'pmprowoo_is_purchasable' ),
			'template-found/class-paytrace.php'                                       => class_exists( 'WC_Paytrace' ),
			'template-found/class-points-and-rewards-for-wc.php'                      => class_exists( 'Points_Rewards_For_WooCommerce_Public' ),
			'template-found/class-post-smartship.php'                                 => function_exists( 'wb_prinetti_requirements' ),
			'template-found/class-ppcp-credit-card.php'                               => class_exists( 'WooCommerce\PayPalCommerce\WcGateway\Gateway\CreditCardGateway' ),
			'template-found/class-pys.php'                                            => class_exists( 'PixelYourSite\EventsManager' ),
			'template-found/class-rey-core.php'                                       => defined( 'REY_THEME_DIR' ),
			'template-found/class-sendcloud-shipping.php'                             => function_exists( 'sendcloudshipping_add_service_point_to_checkout' ),
			'template-found/class-seo-by-rank-math.php'                               => class_exists( 'RankMath\WooCommerce\WooCommerce' ),
			'template-found/class-sg-optimizer.php'                                   => defined( 'SiteGround_Optimizer\VERSION' ),
			'template-found/class-shipmondo-pakkelabels.php'                          => function_exists( 'shipmondo_is_woocommerce_active' ),
			'template-found/class-simple-product-options-for-wc.php'                  => function_exists( 'Pektsekye_PO' ),
			'template-found/class-square.php'                                         => class_exists( 'WooCommerce_Square_Loader' ),
			'template-found/class-strolik-core.php'                                   => function_exists( 'osf_checkout_before_customer_details_container' ),
			'template-found/class-subscription-gifting.php'                           => class_exists( 'WCS_Gifting' ),
			'template-found/class-thrive-leads.php'                                   => class_exists( 'Thrive\Theme\Integrations\WooCommerce\Filters' ) || function_exists( 'tve_leads_display_form_lightbox' ),
			'template-found/class-tool-tip.php'                                       => true,
			'template-found/class-translatepress.php'                                 => class_exists( 'TRP_Translate_Press' ),
			'template-found/class-twilio-sms-notification.php'                        => class_exists( 'WC_Twilio_SMS' ),
			'template-found/class-uk-address-postcode-validation-ideal-postcodes.php' => class_exists( 'WC_IdealPostcodes' ),
			'template-found/class-variation-swatch.php'                               => defined( 'CFVSW_FILE' ),
			'template-found/class-wc-braintree.php'                                   => class_exists( 'WC_PayPal_Braintree_Loader' ),
			'template-found/class-wc-buy-one-get-one-free.php'                        => class_exists( 'WC_BOGOF_Cart' ),
			'template-found/class-wc-e-abi-postoffice-plugin.php'                     => class_exists( 'WC_Eabi_Itella_Smartship_Smartpost' ),
			'template-found/class-wc-gamipress-payment-gateway.php'                   => function_exists( 'gamipress_wc_points_gateway_after_order_total' ),
			'template-found/class-wc-gift-certificate-pro.php'                        => class_exists( 'Ignite_Gift_Certs' ),
			'template-found/class-wc-parcel-pro.php'                                  => class_exists( 'ParcelPro' ) && class_exists( 'Parcelpro_Public' ),
			'template-found/class-wc-payments-gpay-applepay.php'                      => class_exists( 'WC_Payments' ),
			'template-found/class-wc-points-and-rewards.php'                          => class_exists( 'WC_Points_Rewards_Cart_Checkout' ),
			'template-found/class-wc-select-city.php'                                 => class_exists( 'WC_City_Select' ),
			'template-found/class-wc-side-cart-xootix.php'                            => class_exists( 'Xoo_Wsc_Cart' ),
			'template-found/class-wc_radio_buttons.php'                               => class_exists( 'WC_Radio_Buttons' ),
			'template-found/class-wcnl-parcel-conflict-resolver.php'                  => class_exists( 'WooCommerce_MyParcel_Frontend' ),
			'template-found/class-wcnl-postcode.php'                                  => class_exists( 'WPO\WC\Postcode_Checker\WC_NLPostcode_Fields' ),
			'template-found/class-webtoffee.php'                                      => class_exists( 'HF_Woocommerce_Subscription' ),
			'template-found/class-woo-bulk-discounting.php'                           => class_exists( 'Woo_Bulk_Discount_Plugin_t4m' ),
			'template-found/class-woo-gift-cards.php'                                 => class_exists( 'WC_Gift_Cards' ),
			'template-found/class-woo-mundipagg-payments.php'                         => class_exists( 'Woocommerce\Mundipagg\Controller\Gateways' ),
			'template-found/class-woo-order-signature-light.php'                      => function_exists( 'swph_woo_sign_add_customer_signature' ),
			'template-found/class-woo-order-signature-pro.php'                        => function_exists( 'swph_display_signature_pad' ),
			'template-found/class-woo-payment-gateway.php'                            => class_exists( 'WC_Braintree_Manager' ),
			'template-found/class-woo-shipping-dpd-baltic.php'                        => class_exists( 'Dpd' ),
			'template-found/class-woo-social-login.php'                               => class_exists( 'WOO_Slg_Public' ),
			'template-found/class-woo-variation-swatches.php'                         => class_exists( 'Woo_Variation_Swatches' ),
			'template-found/class-woo-wallet.php'                                     => class_exists( 'WooWallet' ),
			'template-found/class-woocommerce-payments.php'                           => function_exists( 'wcpay_init' ),
			'template-found/class-woof-product-price-filter.php'                      => class_exists( 'WOOF' ),
			'template-found/class-woongkir.php'                                       => class_exists( 'Woongkir' ),
			'template-found/class-wooocommerce-dhl-wpdesk.php'                        => defined( 'WOOCOMMERCE_DHL_VERSION' ),
			'template-found/class-wooocommerce-inpost-wpdesk.php'                     => defined( 'WOOCOMMERCE_PACZKOMATY_INPOST_VERSION' ),
			'template-found/class-wp-affiliate.php'                                   => class_exists( 'Affiliate_WP' ),
			'template-found/class-wpdm-page-template.php'                             => class_exists( 'wpdm_page_template' ),
			'template-found/class-x.php'                                              => function_exists( 'x_output_generated_styles' ),
			'template-found/class-xl-nmi-gateway-for-wc.php'                          => class_exists( 'NMI_Gateway_Woocommerce_Loader' ),
			'template-found/class-yith-gift-card-premium.php'                         => class_exists( 'YITH_YWGC_Frontend_Premium' ),
			'template-found/class-yith-multiple-shipping-address-wc.php'              => function_exists( 'yith_wcmas_init' ) || class_exists( 'YITH_Multiple_Addresses_Shipping_Frontend' ),
			'template-found/litespeed.php'                                            => defined( 'LSCWP_V' ),
			'template-found/nextgen-social-login.php'                                 => class_exists( 'NextendSocialLogin' ),
			'template-found/wp-fusion-abandoned-cart.php'                             => class_exists( 'WPF_Abandoned_Cart_Woocommerce' ),
			'plugins/class-lumise-customized-product.php'                             => class_exists( 'Fancy_Product_Designer' ) || isset( $GLOBALS['lumise'] ),
			'library/class-subscriptions.php'                                         => class_exists( 'WC_Subscriptions' ),
			'template-found/weglot.php'                                               => defined( 'WEGLOT_NAME' ),
			'template-found/class-yaycurrency.php'                                    => defined( 'YAY_CURRENCY_FILE' ),
			'template-found/class-storeapps-coupons.php'                              => class_exists( 'WC_SC_Coupon_Actions' )
		);
		self::add_files( $files );

		}

		public static function add_files( $paths ) {
			try {
				foreach ( $paths as $file => $condition ) {
					if ( false === $condition ) {
						continue;
					}

					include_once plugin_dir_path( WFACP_PLUGIN_FILE ) . '/compatibilities/' . $file;
				}
			} catch ( Exception|Error $e ) {
				if ( defined( 'BWF_DEV' ) && true === BWF_DEV ) {
					trigger_error( $e->getMessage() );
				}
			}

		}
	}
}
WFACP_Plugin_Compatibilities::load_all_compatibilities();
