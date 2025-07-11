<?php

/**
 * Class WFFN_Ecomm_Tracking_SiteWide
 */

if ( ! class_exists( 'WFFN_Tracking_SiteWide' ) ) {
	class WFFN_Tracking_SiteWide extends WFFN_Ecomm_Tracking_Common {
		public $api_events = [];
		public $gtag_rendered = false;
		private static $ins = null;
		private $pending_events = [];

		public function __construct() {
			$this->admin_general_settings = BWF_Admin_General_Settings::get_instance();
		}

		public function get_pending_events() {
			return $this->pending_events;
		}

		public function add_to_cart_process( $cart_item_key, $product_id, $quantity, $variation_id ) {

			if ( 0 < did_action( 'wfacp_after_checkout_page_found' ) ) {
				return;
			}

			if ( $this->is_fb_pixel() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_add_to_cart_global' ) ) ) {

				$this->pending_events['pixel'][] = array(
					'event'    => 'AddToCart',
					'data'     => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'pixel' ),
					'event_id' => $this->get_event_id( 'AddToCart' )
				);


			}

			if ( $this->ga_code() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_add_to_cart_global' ) ) ) {
				$this->pending_events['ga'][] = array(
					'event' => 'add_to_cart',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'google_ua' ),

				);

			}

			if ( $this->gad_code() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_add_to_cart_global' ) ) ) {


				$this->pending_events['gad'][] = array(
					'event' => 'add_to_cart',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'google_ads' ),

				);


			}


			if ( $this->is_pint_pixel() && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_pint_add_to_cart_global' ) ) ) {


				$this->pending_events['pint'][] = array(
					'event' => 'addtocart',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'pint' ),
				);


			}

			if ( '' !== $this->admin_general_settings->get_option( 'tiktok_pixel' ) && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_add_to_cart_global' ) ) ) {


				$this->pending_events['tiktok'][] = array(
					'event' => 'AddToCart',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'tiktok' ),
				);


			}

			if ( '' !== $this->admin_general_settings->get_option( 'snapchat_pixel' ) && true === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_add_to_cart_global' ) ) ) {


				$this->pending_events['snapchat'][] = array(
					'event' => 'ADD_CART',
					'data'  => $this->get_add_to_cart_prams( $product_id, $variation_id, $quantity, 'snapchat' ),
				);


			}


		}

		public function get_add_to_cart_prams( $product_id, $variation_id, $quantity, $mode ) {

			$product_id = ! empty( $variation_id ) && $variation_id > 0 && ( false === $this->do_treat_variable_as_simple( $mode ) ) ? $variation_id : $product_id;
			$product    = wc_get_product( $product_id );

			// Calculate price
			$price = apply_filters( 'wffn_add_to_cart_tracking_price', $product->get_price(), $product, $variation_id, $quantity, $mode, $this->admin_general_settings );

			// Get category names for the product

			if ( $product->is_type( 'variation' ) ) {
				$cat_id = $product->get_parent_id();
			} else {
				$cat_id = $product->get_id();
			}

			$cat_list       = $this->get_product_tags( 'product_cat', $cat_id );
			$category_names = ! empty( $cat_list ) ? implode( ', ', $cat_list ) : '';

			$quantity = ! empty( $quantity ) ? absint( $quantity ) : 1;

			if ( 'pint' === $mode ) {
				// Prepare line item details including category information
				$line_item = [
					'product_id'       => $this->get_woo_product_content_id( $product_id, $mode ), // Ensure product ID is treated as a string
					'product_name'     => esc_html( $product->get_name() ),
					'product_price'    => floatval( $price ),
					'product_quantity' => $quantity,
					'product_category' => $category_names,
				];

				if ( ! empty( $variation_id ) && $variation_id > 0 ) {
					$variation_product = wc_get_product( $variation_id );
					if ( ! empty ( $variation_product ) ) {
						$variation_name                  = implode( ",", $variation_product->get_variation_attributes() );
						$line_item['product_variant_id'] = $variation_product->get_id();
						$line_item['product_variant']    = $variation_name;
					}
				}


				$tag_list = $this->get_product_tags( 'product_tag', $product->get_id() );
				if ( count( $tag_list ) > 0 ) {
					$line_item['tags'] = implode( ', ', $tag_list );
				}

				// Prepare Pinterest-specific event data
				$event_data = [
					'event_id'       => $this->get_event_id( 'AddToCart' ),
					'value'          => floatval( $price ) * floatval( $quantity ),
					'order_quantity' => $quantity,
					'currency'       => get_woocommerce_currency(),
					'content_type'   => 'product',
					'line_items'     => [ $line_item ], // Include line item in an array
					'traffic_source' => $this->get_traffic_source( 'source' ),
					'user_role'      => $this->get_current_user_role(),
					'event_url'      => $this->getEventRequestUri(),
					'user_roles'     => $this->get_current_user_role(),
				];


				return $event_data;
			}

			$event_data = [
				'value'        => $price,
				'content_name' => esc_html( $product->get_name() ),
				'content_type' => 'product',
				'currency'     => get_woocommerce_currency(),
				'content_ids'  => [ $this->get_woo_product_content_id( $product_id, $mode ) ],
				'contents'     => [
					[
						'id'         => $this->get_woo_product_content_id( $product_id, $mode ),
						'item_price' => $price,
						'quantity'   => $quantity,
						'value'      => $price,
					],
				],
				'user_roles'   => $this->get_current_user_role(),
			];

			if ( 'pixel' === $mode ) {
				unset( $event_data['contents'][0]['value'] );

				if ( true !== $this->is_fb_enable_content_on() ) {
					unset( $event_data['content_ids'] );
					unset( $event_data['contents'] );
				}

			}


			if ( 'tiktok' === $mode ) {
				$event_data['content_id'] = $this->get_woo_product_content_id( $product_id, $mode );
				$event_data['price']      = $price;
				$event_data['quantity']   = $quantity;
				unset( $event_data['content_ids'] );
				unset( $event_data['user_roles'] );
				unset( $event_data['contents'] );
			}

			if ( 'snapchat' === $mode ) {
				$event_data['number_items']  = count( $event_data['content_ids'] );
				$event_data['item_ids']      = $event_data['content_ids'];
				$event_data['price']         = $price;
				$event_data['item_category'] = $category_names;
				unset( $event_data['content_ids'] );
				unset( $event_data['user_roles'] );
				unset( $event_data['contents'] );
			}

			if ( 'google_ua' === $mode ) {
				$total_price = $price;
				if ( function_exists( 'wc_get_price_to_display' ) && absint( $quantity ) > 1 ) {
					$total_price = (float) wc_get_price_to_display( $product, array( 'qty' => $quantity, 'price' => $price ) );
				}
				$event_data['items'][0]['id']       = $product_id;
				$event_data['items'][0]['name']     = esc_html( $product->get_name() );
				$event_data['items'][0]['category'] = $category_names;
				$event_data['items'][0]['quantity'] = $quantity;
				$event_data['items'][0]['price']    = floatval( $price );
				if ( $this->is_ga4_tracking() ) {
					$event_data['value']                 = floatval( $total_price );
					$event_data['items'][0]['item_id']   = $event_data['items'][0]['id'];
					$event_data['items'][0]['item_name'] = esc_html( $event_data['items'][0]['name'] );
					$event_data['items'][0]['currency']  = get_woocommerce_currency();
					if ( $product->is_type( 'variation' ) ) {
						$event_data['items'][0]['item_variant'] = implode( "/", $product->get_variation_attributes() );
					}
					$cat_count = 0;
					if ( is_array( $cat_list ) && count( $cat_list ) > 0 ) {
						foreach ( $cat_list as $cat ) {
							$item_category                            = ( 0 === $cat_count ) ? 'item_category' : 'item_category_' . $cat_count;
							$event_data['items'][0][ $item_category ] = $cat;
							$cat_count ++;
						}
					}

					unset( $event_data['items'][0]['id'] );
					unset( $event_data['items'][0]['name'] );
					unset( $event_data['items'][0]['category'] );
					unset( $event_data['event_category'] );
					unset( $event_data['ecomm_pagetype'] );
					unset( $event_data['ecomm_prodid'] );
					unset( $event_data['ecomm_totalvalue'] );
					unset( $event_data['contents'] );
					unset( $event_data['content_name'] );
					unset( $event_data['content_ids'] );
					unset( $event_data['content_type'] );
				}
			}

			return $event_data;
		}

		/**
		 * @return WFFN_Tracking_SiteWide|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function should_render() {
			return false;
		}

		public function get_user_email() {
			$current_user = wp_get_current_user();

			// not logged in
			if ( empty( $current_user ) || $current_user->ID === 0 ) {
				return '';
			}

			return $current_user->user_email;
		}


		public function track_event_data() {


			$pixel          = false !== $this->is_fb_pixel() ? $this->is_fb_pixel() : '';
			$ga             = false !== $this->ga_code() ? $this->ga_code() : '';
			$gad            = false !== $this->gad_code() ? $this->gad_code() : '';
			$gad_labels     = $this->admin_general_settings->get_option( 'gad_addtocart_global_conversion_label' );
			$tiktok         = $this->admin_general_settings->get_option( 'tiktok_pixel' );
			$pinterest      = $this->admin_general_settings->get_option( 'pint_key' );
			$pint_event     = $this->admin_general_settings->get_option( 'is_pint_page_view_global' );
			$pint_visit     = $this->admin_general_settings->get_option( 'is_pint_page_visit_global' );
			$snapchat       = $this->admin_general_settings->get_option( 'snapchat_pixel' );
			$pixel_event    = $this->admin_general_settings->get_option( 'is_fb_page_view_global' );
			$pixel_view     = $this->admin_general_settings->get_option( 'is_fb_page_product_content_global' );
			$tiktok_view    = $this->admin_general_settings->get_option( 'is_tiktok_page_product_content_global' );
			$ga_event       = $this->admin_general_settings->get_option( 'is_ga_page_view_global' );
			$ga_view_item   = $this->admin_general_settings->get_option( 'is_ga_view_item_global' );
			$gad_event      = $this->admin_general_settings->get_option( 'is_gad_page_view_global' );
			$gad_view_item  = $this->admin_general_settings->get_option( 'is_gad_view_item_global' );
			$tiktok_event   = $this->admin_general_settings->get_option( 'is_tiktok_page_view_global' );
			$snapchat_event = $this->admin_general_settings->get_option( 'is_snapchat_page_view_global' );


			$data = [
				'pixel'          => [
					'id'             => $pixel,
					'settings'       => [
						'page_view' => $pixel_event,
					],
					'data'           => [],
					'conversion_api' => $this->is_conversion_api(),
					'fb_advanced'    => WFFN_Common::pixel_advanced_matching_data(),
				],
				'ga'             => [
					'id'       => $ga,
					'settings' => [
						'page_view' => $ga_event,
					],
					'data'     => []
				],
				'gad'            => [
					'id'       => $gad,
					'labels'   => $gad_labels,
					'settings' => [
						'page_view' => $gad_event,
					],
					'data'     => []
				],
				'tiktok'         => [
					'id'       => $tiktok,
					'settings' => [
						'page_view' => $tiktok_event,

					],
					'data'     => [],
					'advanced' => WFFN_Common::tiktok_advanced_matching_data(),
				],
				'pint'           => [
					'id'       => $pinterest,
					'settings' => [
						'page_view' => $pint_event,

					],
					'data'     => []
				],
				'snapchat'       => [
					'id'       => $snapchat,
					'settings' => [
						'page_view'  => $snapchat_event,
						'user_email' => $this->get_user_email(),
					],
					'data'     => []
				],
				'ajax_endpoint'  => admin_url( 'admin-ajax.php' ),
				'restUrl'        => rest_url() . 'wffn/front',
				'pending_events' => $this->pending_events,
				'is_ajax_mode'   => true,
				'should_render'  => apply_filters( 'wffn_allow_site_wide_tracking_js', true ),
				'is_delay'       => 0,

			];

			if ( true === wffn_string_to_bool( $pixel_view ) && is_array( $this->get_add_to_product_data() ) && count( $this->get_add_to_product_data() ) > 0 ) {
				$data['pixel']['settings']['view_content'] = $pixel_view;
				$data['pixel']['content_data']             = $this->get_add_to_product_data();
			}

			if ( true === wffn_string_to_bool( $tiktok_view ) && is_array( $this->get_add_to_product_data() ) && count( $this->get_add_to_product_data() ) > 0 ) {
				$data['tiktok']['settings']['view_content'] = $tiktok_view;
				$data['tiktok']['content_data']             = $this->get_add_to_product_data();
			}

			if ( true === wffn_string_to_bool( $pint_visit ) && is_array( $this->get_add_to_product_data() ) && count( $this->get_add_to_product_data() ) > 0 ) {
				$data['pint']['settings']['view_content'] = $pint_visit;
				$data['pint']['content_data']             = $this->pint_content_data();
			}

			if ( true === wffn_string_to_bool( $gad_view_item ) && is_array( $this->get_view_items_data( 'gad' ) ) && count( $this->get_view_items_data( 'gad' ) ) > 0 ) {
				$data['gad']['settings']['view_content']  = $gad_view_item;
				$data['gad']['content_data']['view_item'] = $this->get_view_items_data( 'gad' );
			}

			if ( true === wffn_string_to_bool( $ga_view_item ) && is_array( $this->get_view_items_data( 'ga' ) ) && count( $this->get_view_items_data( 'ga' ) ) > 0 ) {
				$data['ga']['settings']['view_content']  = $ga_view_item;
				$data['ga']['content_data']['view_item'] = $this->get_view_items_data( 'ga' );
			}

			return apply_filters( 'wffn_load_sitewide_events_data', $data, $this->admin_general_settings );
		}

		public function tracking_script() {
			$live_or_dev = 'live';
			$suffix      = '.min';

			if ( defined( 'WFFN_IS_DEV' ) && true === WFFN_IS_DEV ) {
				$live_or_dev = 'dev';
				$suffix      = '';
			}
			$instance = BWF_Admin_General_Settings::get_instance();

			if ( false === $this->should_render_global() ) {
				return false;
			}

			if ( false === apply_filters( 'wffn_allow_site_wide_tracking', true, $this->should_render_global(), $instance ) ) {
				return false;
			}

			if ( class_exists( 'WFACP_Core' ) && wffn_is_wc_active() && ! empty( WFACP_Core()->public ) && WFACP_Core()->public->is_native_checkout() ) {
				return false;
			}

			if ( wffn_is_wc_active() && is_order_received_page() ) {
				return false;
			}

			wp_enqueue_script( 'wffn-tracking', plugin_dir_url( WFFN_PLUGIN_FILE ) . 'assets/' . $live_or_dev . '/js/tracks' . $suffix . '.js', [ 'jquery' ], WFFN_VERSION_DEV, array(
				'is_footer' => false,
				'strategy'  => 'defer'
			) );
			wp_localize_script( 'wffn-tracking', 'wffnTracking', $this->track_event_data() );

		}

		public function should_render_global() {

			/** Landing page check */
			$landing_ins = WFFN_Core()->landing_pages;
			if ( $landing_ins instanceof WFFN_Landing_Pages && $landing_ins->is_wflp_page() ) {
				return false;
			}

			if ( function_exists( 'WFOPP_Core' ) ) {
				/** Optin page check */
				$optin_ins = WFOPP_Core()->optin_pages;
				if ( $optin_ins instanceof WFFN_Optin_Pages && $optin_ins->is_wfop_page() ) {
					return false;
				}

				/** Optin thank you page check */
				$optin_ty_ins = WFOPP_Core()->optin_ty_pages;
				if ( $optin_ty_ins instanceof WFFN_Optin_TY_Pages && $optin_ty_ins->is_wfoty_page() ) {
					return false;
				}
			}

			if ( function_exists( 'WFOCU_Core' ) ) {
				/** Upsell page check */
				$upsell_ins = WFOCU_Core()->public;
				if ( $upsell_ins instanceof WFOCU_Public && $upsell_ins->if_is_offer() ) {
					return false;
				}
			}

			/** WC thankyou page check */
			$thank_you_ins = WFFN_Core()->thank_you_pages;
			if ( $thank_you_ins instanceof WFFN_Thank_You_WC_Pages && $thank_you_ins->is_wfty_page() ) {
				return false;
			}

			if ( did_action( 'wfacp_after_template_found' ) ) {
				return false;
			}

			/**
			 * IF Fb not set to fire global AND
			 * IF GA not set to fire global AND
			 * IF GAD not set to fire global AND
			 * IF SNAPCHAT not set to fire global
			 */
			$fb       = ( false === $this->is_fb_pixel() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_fb_add_to_cart_global' ) ) ) );
			$ga       = ( false === $this->ga_code() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_ga_add_to_cart_global' ) ) ) );
			$gad      = ( false === $this->gad_code() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_gad_add_to_cart_global' ) ) ) );
			$snapchat = ( false === $this->snapchat_code() || ( false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_page_view_global' ) ) && false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_snapchat_add_to_cart_global' ) ) ) );
			$pint     = ( false === $this->is_pint_pixel() || false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_pint_add_to_cart_global' ) ) );
			$tiktok   = ( false === $this->tiktok_code() || false === wffn_string_to_bool( $this->admin_general_settings->get_option( 'is_tiktok_add_to_cart_global' ) ) );
			if ( $fb && $ga && $gad && $snapchat && $pint && $tiktok ) {
				return false;
			}

			return true;
		}

		public function get_add_to_product_data() {
			global $post;
			$event_data = array();

			if ( ! function_exists( 'WC' ) || ! is_single() || ! $post instanceof WP_Post ) {
				return $event_data;
			}

			$product = wc_get_product( $post->ID );

			if ( ! $product instanceof WC_Product ) {
				return $event_data;
			}

			$event_data = [
				'content_type'   => $product->get_type(),
				'user_role'      => $this->get_current_user_role(),
				'event_url'      => $this->getRequestUri(),
				'category_name'  => '',
				'currency'       => get_woocommerce_currency(),
				'value'          => $product->get_price(),
				'content_name'   => $product->get_title(),
				'content_ids'    => [ $this->get_woo_product_content_id( $product->get_id(), 'pixel' ) ],
				'product_price'  => $product->get_price(),
				'post_id'        => $post->ID,
				'contents'       => [
					[
						'id'       => $this->get_woo_product_content_id( $product->get_id(), 'pixel' ),
						'quantity' => ( null !== $product->get_stock_quantity() ) ? $product->get_stock_quantity() : 1,
					],
				],
				'traffic_source' => $this->get_traffic_source( 'source' ),
			];

			$landing_page = $this->get_traffic_source( 'referrer' );

			if ( ! empty( $_COOKIE['wffn_referrer'] ) ) {
				$landing_page = wffn_clean( $_COOKIE['wffn_referrer'] );
			}

			$event_data['landing_page'] = ! empty( $landing_page ) ? $landing_page : '';

			$tag_list = $this->get_product_tags( 'product_tag', $product->get_id() );
			if ( count( $tag_list ) ) {
				$event_data['tags'] = implode( ', ', $tag_list );
			}

			if ( $post->post_type === 'product_variation' ) {
				$cat_post_id = $post->post_parent; // get terms from parent
				$cat_list    = $this->get_product_tags( 'product_cat', $cat_post_id );

			} else {
				$cat_list = $this->get_product_tags( 'product_cat', $product->get_id() );
			}

			if ( count( $cat_list ) ) {
				$event_data['category_name'] = implode( ', ', $cat_list );
			}

			return $event_data;
		}

		/**
		 * @return array
		 */
		public function pint_content_data() {
			global $post;
			$event_data = array();

			if ( ! function_exists( 'WC' ) || ! is_single() || ! $post instanceof WP_Post ) {
				return $event_data;
			}

			$product = wc_get_product( $post->ID );

			if ( ! $product instanceof WC_Product ) {
				return $event_data;
			}

			$mode       = 'pint';
			$product_id = $product->get_id();

			// Get category names for the product
			$cat_id         = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
			$cat_list       = $this->get_product_tags( 'product_cat', $cat_id );
			$category_names = ! empty( $cat_list ) ? implode( ', ', $cat_list ) : '';

			$price = $product->get_price();


			// Prepare line item details including category information
			$line_item = [
				'product_id'       => $this->get_woo_product_content_id( $product_id, $mode ), // Ensure product ID is treated as a string
				'product_name'     => $product->get_name(),
				'product_price'    => floatval( $price ),
				'product_quantity' => 1,
				'product_category' => $category_names,
			];

			$tag_list = $this->get_product_tags( 'product_tag', $product->get_id() );
			if ( count( $tag_list ) > 0 ) {
				$line_item['tags'] = implode( ', ', $tag_list );
			}

			// Prepare Pinterest-specific event data
			$event_data = [
				'event_id'       => $this->get_event_id( 'view' ),
				'value'          => floatval( $price ),
				'order_quantity' => 1,
				'currency'       => get_woocommerce_currency(),
				'content_type'   => 'product',
				'line_items'     => [ $line_item ], // Include line item in an array
				'traffic_source' => $this->get_traffic_source( 'source' ),
				'user_role'      => $this->get_current_user_role(),
				'event_url'      => $this->getEventRequestUri(),
			];

			$landing_page = $this->get_traffic_source( 'referrer' );
			if ( ! empty( $_COOKIE['wffn_referrer'] ) ) {
				$landing_page = wffn_clean( $_COOKIE['wffn_referrer'] );
			}
			$event_data['referrer'] = ! empty( $landing_page ) ? $landing_page : '';

			return $event_data;
		}

		public function get_product_tags( $taxonomy, $post_id ) {

			$terms   = get_the_terms( $post_id, $taxonomy );
			$results = array();

			if ( is_wp_error( $terms ) || empty ( $terms ) ) {
				return array();
			}

			// decode special chars
			foreach ( $terms as $term ) {
				$results[] = html_entity_decode( $term->name );
			}

			return $results;

		}

		public function get_view_items_data( $mode ) {
			global $post;
			$event_data = array();
			$categories = '';

			if ( ! function_exists( 'WC' ) || ! is_single() || ! $post instanceof WP_Post ) {
				return $event_data;
			}

			$product = wc_get_product( $post->ID );

			if ( ! $product instanceof WC_Product ) {
				return $event_data;
			}

			if ( $product->get_type() === 'product_variation' ) {
				$cat_post_id = $product->get_parent_id(); // get terms from parent
				$cat_list    = $this->get_product_tags( 'product_cat', $cat_post_id );

			} else {
				$cat_list = $this->get_product_tags( 'product_cat', $product->get_id() );
			}

			if ( is_array( $cat_list ) && count( $cat_list ) > 0 ) {
				$categories = implode( '/', $cat_list );
			}

			$price        = $product->get_price();
			$product_id   = $product->get_id();
			$product_name = $product->get_name();
			$currency     = get_woocommerce_currency();

			$event_data = array(
				'event_category'   => 'ecommerce',
				'ecomm_prodid'     => $this->get_woo_product_content_id( $product_id, $mode ),
				'ecomm_pagetype'   => 'product',
				'ecomm_totalvalue' => $price,
				'items'            => array(
					array(
						'id' => $this->get_woo_product_content_id( $product_id, $mode ),
					)
				),
			);

			if ( 'gad' === $mode ) {
				$event_data['page_title'] = $product_name;
				$event_data['post_id']    = $product_id;
				$event_data['post_type']  = 'product';
				$event_data['value']      = $price;
			}

			if ( 'ga' === $mode ) {
				$event_data['currency']             = $currency;
				$event_data['items'][0]['id']       = $product_id;
				$event_data['items'][0]['name']     = $product->get_name();
				$event_data['items'][0]['category'] = $categories;
				$event_data['items'][0]['quantity'] = 1;
				$event_data['items'][0]['price']    = floatval( $price );
				$event_data['items'][0]['index']    = 0;
				if ( $this->is_ga4_tracking() ) {
					$event_data['value']                 = floatval( $price );
					$event_data['items'][0]['item_id']   = $event_data['items'][0]['id'];
					$event_data['items'][0]['item_name'] = $event_data['items'][0]['name'];
					$event_data['items'][0]['currency']  = $currency;

					$cat_count = 0;
					if ( is_array( $cat_list ) && count( $cat_list ) > 0 ) {
						foreach ( $cat_list as $cat ) {
							$item_category                            = ( 0 === $cat_count ) ? 'item_category' : 'item_category' . $cat_count;
							$event_data['items'][0][ $item_category ] = $cat;
							$cat_count ++;
						}
					}

					unset( $event_data['items'][0]['id'] );
					unset( $event_data['items'][0]['name'] );
					unset( $event_data['items'][0]['category'] );
					unset( $event_data['event_category'] );
					unset( $event_data['ecomm_pagetype'] );
					unset( $event_data['ecomm_prodid'] );
					unset( $event_data['ecomm_totalvalue'] );
				}
			}

			return $event_data;

		}

		public function is_ga4_tracking() {
			$ga_id = $this->admin_general_settings->get_option( 'ga_key' );
			if ( ! empty( $ga_id ) && strpos( $ga_id, "G-" ) !== false ) {
				return true;
			}

			return false;
		}

		public function get_traffic_source( $type = 'source' ) {
			$referrer = wp_get_referer();

			if ( 'referrer' === $type ) {
				return $referrer;
			}

			if ( empty( $referrer ) ) {
				$external = false;
			} else {
				$external = strpos( site_url(), $referrer ) === 0;
			}

			if ( ! $external ) {
				$source = 'direct';
			} else {
				$source = $referrer;
			}

			if ( $source !== 'direct' ) {
				$parse = wp_parse_url( $source );
				if ( isset( $parse['host'] ) ) {
					return $parse['host'];// leave only domain (Issue #70)
				} else {
					return "direct";
				}
			}

			return $source;
		}

		public function get_current_user_role() {
			if ( is_user_logged_in() ) {
				if ( is_super_admin() ) {
					return 'administrator';
				} else {
					return 'customer';
				}
			}

			return 'guest';
		}

		final public function number_format( $value, $format_count = 2 ) {

			$output = number_format( floatval( $value ), wc_get_price_decimals(), '.', '' );

			return apply_filters( 'bwf_analytics_number_format', $output, $value, $format_count, $this );
		}


	}


}

