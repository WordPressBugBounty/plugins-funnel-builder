<?php

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'WFACP_Common' ) ) {
	/**
	 * Aero checkout Common Class
	 *
	 */
	#[AllowDynamicProperties]
	abstract class WFACP_Common extends WFACP_Common_Helper {

		public static $customizer_key_prefix = '';
		public static $customizer_key_data = [];
		public static $post_data = [];
		public static $customizer_fields_default = array();
		public static $exchange_keys = [];
		private static $wfacp_id = 0;
		private static $wfacp_section = '';
		private static $product_data = [];

		public static function init() {
			/**
			 * Loading WooFunnels core
			 */
			if ( apply_filters( 'wfacp_skip_common_loading', false ) ) {
				return;
			}
			add_action( 'plugins_loaded', [ __CLASS__, 'plugins_loaded' ], - 1 );
			add_action( 'init', [ __CLASS__, 'register_post_type' ], 5 );
			add_action( 'wc_ajax_get_refreshed_fragments', [ __CLASS__, 'wc_ajax_get_refreshed_fragments' ], - 1 );
			add_action( 'woocommerce_checkout_update_order_review', [ __CLASS__, 'woocommerce_checkout_update_order_review' ], - 1 );
			add_action( 'woocommerce_before_checkout_process', [ __CLASS__, 'woocommerce_before_checkout_process' ] );
			add_filter( 'woocommerce_form_field_hidden', [ __CLASS__, 'woocommerce_form_field_hidden' ], 10, 4 );
			add_filter( 'woocommerce_form_field_wfacp_radio', [ __CLASS__, 'woocommerce_form_field_wfacp_radio' ], 10, 4 );
			add_filter( 'woocommerce_form_field_wfacp_start_divider', [ __CLASS__, 'woocommerce_form_field_wfacp_start_divider' ], 10, 4 );
			add_filter( 'woocommerce_form_field_wfacp_end_divider', [ __CLASS__, 'woocommerce_form_field_wfacp_end_start_divider' ], 10, 4 );
			add_action( 'woocommerce_form_field_wfacp_html', [ __CLASS__, 'process_wfacp_html' ], 10, 4 );
			add_filter( 'wcct_get_restricted_action', [ __CLASS__, 'wcct_get_restricted_action' ] );
			add_shortcode( 'wfacp_order_custom_field', [ __CLASS__, 'wfacp_order_custom_field' ] );
			add_action( 'wfacp_get_fragments', [ __CLASS__, 'initializeTemplate' ] );
			add_action( 'wfob_before_remove_bump_from_cart', [ __CLASS__, 'wfob_order_bump_fragments' ] );
			add_action( 'wfob_before_add_to_cart', [ __CLASS__, 'wfob_order_bump_fragments' ] );
			add_action( 'woofunnels_loaded', [ __CLASS__, 'include_notification_class' ] );


			add_action( 'woocommerce_locate_template', [ __CLASS__, 'woocommerce_locate_template' ] );
			add_filter( 'wfacp_billing_field', [ __CLASS__, 'check_wc_validations_billing' ], 10, 2 );
			add_filter( 'wfacp_shipping_field', [ __CLASS__, 'check_wc_validations_shipping' ], 10, 2 );


			//try to resolve cache

			add_filter( 'woocommerce_checkout_update_order_review_expired', [ __CLASS__, 'do_not_show_session_expired_message' ] );

			add_action( 'wp_loaded', [ __CLASS__, 'initiate_track_and_analytics' ], 99 );

			add_filter( 'post_type_link', array( __CLASS__, 'post_type_permalinks' ), 10, 3 );
			add_action( 'pre_get_posts', array( __CLASS__, 'add_cpt_post_names_to_main_query' ), 20 );

			add_filter( 'bwf_general_settings_default_config', function ( $fields ) {
				$fields['checkout_page_base'] = 'checkouts';

				return $fields;
			} );

			add_action( 'wfacp_after_checkout_page_found', function () {
				add_filter( 'woocommerce_payment_gateways', [ __CLASS__, 'unset_gateways' ], 1000 );
			} );
			add_filter( 'woofunnels_global_settings', function ( $menu ) {
				array_push( $menu, array(
					'title'    => __( 'Checkout', 'funnel-builder' ),
					'slug'     => 'wfacp',
					'link'     => admin_url( 'admin.php?page=wfacp&tab=settings' ),
					'priority' => 30,
				) );

				return $menu;
			} );

			add_filter( 'woofunnels_global_settings_fields', array( __CLASS__, 'add_global_settings_fields' ) );
			add_filter( 'bwf_general_settings_fields', function ( $fields ) {
				$fields['checkout_page_base'] = array(
					'type'      => 'input',
					'inputType' => 'text',
					'label'     => __( 'Checkout Page', 'funnel-builder' ),
					'hint'      => __( '', 'funnel-builder' ),
				);

				return $fields;
			} );
			add_action( 'woocommerce_checkout_order_processed', [ __CLASS__, 'update_aero_field' ], 15, 3 );


			/*------------------------------Enable Order Total---------------------------------*/

			add_shortcode( 'wfacp_order_total', [ __CLASS__, 'wfacp_order_total' ] );

			add_action( 'template_redirect', [ __CLASS__, 'do_wc_ajax' ], - 1 );

			add_action( 'init', [ __CLASS__, 'setup_fields_billing' ], 20 );
			add_action( 'wfacp_template_load', [ __CLASS__, 'include_third_party_field' ] );
			add_filter( 'wfacp_import_checkout_settings', [ __CLASS__, 'add_third_party_fields_to_checkout_form' ], 10, 3 );
		}

		public static function add_global_settings_fields( $fields ) {
			$fields["wfacp"] = WFACP_Common::all_global_settings_fields();

			return $fields;
		}

		public static function plugins_loaded() {

			/**
			 * @since 1.6.0
			 * Detect heartbeat call from our customizer page
			 * Remove some unwanted warnings and error
			 */
			if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'heartbeat' && isset( $_REQUEST['data'] ) ) {
				if ( isset( $_REQUEST['data']['wfacp_customize'] ) ) {
					add_filter( 'customize_loaded_components', array( __CLASS__, 'remove_menu_support' ), 99 );
				}
			}


			if ( ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'wfacp' ) ) {
				$section             = filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW );
				self::$wfacp_section = is_null( $section ) ? 'design' : $section;
			}

			if ( isset( $_REQUEST['wfacp_id'] ) && $_REQUEST['wfacp_id'] > 0 ) {
				$wfacp_id = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
				if ( is_null( $wfacp_id ) ) {
					$wfacp_id = filter_input( INPUT_POST, 'wfacp_id', FILTER_UNSAFE_RAW );
				}
				self::set_id( absint( $wfacp_id ) );
			} else if ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) {
				$post_id = filter_input( INPUT_GET, 'post', FILTER_UNSAFE_RAW );
				$post    = get_post( $post_id );
				if ( ! is_null( $post ) && $post->post_type == self::get_post_type_slug() ) {
					self::set_id( absint( $post_id ) );
				}
			} else if ( isset( $_REQUEST['elementor-preview'] ) ) {
				$post_id = filter_input( INPUT_GET, 'elementor-preview', FILTER_UNSAFE_RAW );
				$post    = get_post( $post_id );
				if ( ! is_null( $post ) && $post->post_type == self::get_post_type_slug() ) {
					self::set_id( absint( $post_id ) );
				}
			} else if ( isset( $_REQUEST['post'] ) ) {
				$post_id = filter_input( INPUT_GET, 'post', FILTER_UNSAFE_RAW );
				$post    = get_post( $post_id );
				if ( ! is_null( $post ) && $post->post_type == self::get_post_type_slug() ) {
					self::set_id( absint( $post_id ) );
				}
			} else {
				self::set_id( 0 );
			}
		}

		/**
		 * Get current Page id
		 * @return int
		 */
		public static function set_id( $wfacp_id = 0 ) {
			if ( is_numeric( $wfacp_id ) && $wfacp_id > 0 ) {
				self::$wfacp_id              = absint( $wfacp_id );
				self::$customizer_key_prefix = WFACP_SLUG . '_c_' . self::get_id();
			}
		}

		/** Get current Page id
		 * @return int
		 */
		public static function get_id() {
			if ( self::is_disabled() ) {
				return 0;
			}

			if ( self::$wfacp_id == 0 && ! is_admin() && ! self::is_disabled() && function_exists( 'WC' ) && ! is_null( WC()->session ) ) {
				$wfacp_id = WC()->session->get( 'wfacp_id', 0 );
				if ( $wfacp_id > 0 ) {
					self::$wfacp_id = absint( $wfacp_id );
				}
			}

			return self::$wfacp_id;
		}

		/**
		 * Setup checkout page when get_refreshed_fragments ajax called
		 */
		public static function wc_ajax_get_refreshed_fragments() {
			if ( isset( $_REQUEST['wfacp_id'] ) && 0 < absint( $_REQUEST['wfacp_id'] ) ) {
				$wfacp_id = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
				$wfacp_id = absint( $wfacp_id );
				self::initTemplateLoader( $wfacp_id );
			}
		}

		/**
		 * Initialize template when woocommerce ajax running is running
		 *
		 * @param $wfacp_id
		 */
		private static function initTemplateLoader( $wfacp_id ) {
			self::set_id( $wfacp_id );
			$instances = WFACP_Core()->template_loader->load_template( $wfacp_id );
			if ( ! is_null( $instances ) ) {
				do_action( 'wfacp_before_process_checkout_template_loader', $wfacp_id, $instances );
				self::disable_wcct_pricing();
			} else {
				WFACP_Common::pc( '(initTemplateLoader) May be setup page Layout class is not found ' );
			}

		}


		/**
		 * Setup checkout page when update_order_review ajax called
		 */
		public static function woocommerce_checkout_update_order_review( $posted_data ) {

			$post_data = [];
			parse_str( $posted_data, $post_data );

			if ( isset( $post_data['_wfacp_post_id'] ) ) {

				self::$post_data = $post_data;
				if ( isset( $post_data['wfacp_exchange_keys'] ) ) {
					$exchange_keys       = urldecode( $post_data['wfacp_exchange_keys'] );
					self::$exchange_keys = json_decode( $exchange_keys, true );
				}
				self::handling_post_data( $post_data );
				$wfacp_id = absint( $post_data['_wfacp_post_id'] );
				self::initTemplateLoader( $wfacp_id );

			}
		}

		/**
		 * Setup checkout page when before_checkout_process hooks executed
		 */
		public static function woocommerce_before_checkout_process() {
			if ( isset( $_REQUEST['_wfacp_post_id'] ) ) {
				$post_id  = filter_input( INPUT_POST, '_wfacp_post_id', FILTER_UNSAFE_RAW );
				$wfacp_id = absint( $post_id );
				self::initTemplateLoader( $wfacp_id );
			}
		}


		public static function set_data() {

			self::$customizer_key_prefix = WFACP_SLUG . '_c_' . WFACP_Common::get_id();
			/** wfacpkirki */
			if ( class_exists( 'wfacpkirki' ) ) {
				wfacpkirki::add_config( WFACP_SLUG, array(
					'option_type' => 'option',
					'option_name' => WFACP_Common::$customizer_key_prefix,
				) );
			}
		}

		/**
		 * GEt Current open step
		 * @return string
		 */
		public static function get_current_step() {
			return self::$wfacp_section;
		}

		/**
		 * Get title of checkout page
		 * @return string
		 */

		public static function get_page_name() {
			return get_the_title( self::$wfacp_id );
		}

		public static function register_post_type() {
			/**
			 * Funnel Post Type
			 */

			register_post_type( self::get_post_type_slug(), apply_filters( 'wfacp_post_type_args', array(
				'labels'              => array(
					'name'          => __( 'Checkout', 'funnel-builder' ),
					'singular_name' => __( 'Checkout', 'funnel-builder' ),
					'add_new'       => __( 'Add Checkout page', 'funnel-builder' ),
					'add_new_item'  => __( 'Add New Checkout page', 'funnel-builder' ),
					'search_items'  => sprintf( esc_html__( 'Search %s', 'funnel-builder' ), 'Checkout Pages' ),
					'all_items'     => sprintf( esc_html__( 'All %s', 'funnel-builder' ), 'Checkout Pages' ),
					'edit_item'     => sprintf( esc_html__( 'Edit %s', 'funnel-builder' ), 'Checkout' ),
					'view_item'     => sprintf( esc_html__( 'View %s', 'funnel-builder' ), 'Checkout' ),
					'update_item'   => sprintf( esc_html__( 'Update %s', 'funnel-builder' ), 'Checkout' ),
					'new_item_name' => sprintf( esc_html__( 'New %s', 'funnel-builder' ), 'Checkout' ),

				),
				'public'              => true,
				'show_ui'             => true,
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => true,
				'hierarchical'        => false,
				'show_in_nav_menus'   => false,
				'rewrite'             => apply_filters( 'wfacp_rewrite_slug', [ 'slug' => self::get_url_rewrite_slug() ] ),
				'query_var'           => true,
				'supports'            => array( 'title', 'elementor', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'author' ),
				'has_archive'         => false,
				'show_in_rest'        => true,
				'capabilities'        => array(
					'create_posts' => 'do_not_allow', // Prior to Wordpress 4.5, this was false.
				),
			) ) );
			add_filter( 'theme_wfacp_checkout_templates', [ __CLASS__, 'registered_page_templates' ], 9999, 4 );
		}

		public static function registered_page_templates( $templates ) {
			$all_templates = wp_get_theme()->get_post_templates();
			$path          = [

				'wfacp-full-width.php' => __( 'FunnelKit Boxed', 'funnel-builder' ),
				'wfacp-canvas.php'     => __( 'FunnelKit Canvas For Page Builder', 'funnel-builder' )
			];
			if ( isset( $all_templates['page'] ) && count( $all_templates['page'] ) > 0 ) {
				$paths = array_merge( $all_templates['page'], $path );
			} else {
				$paths = $path;
			}
			if ( is_array( $paths ) && is_array( $templates ) ) {
				$paths = array_merge( $paths, $templates );
			}

			return $paths;

		}


		/**
		 * Get Post_type slug
		 * @return string
		 */
		public static function get_post_type_slug() {
			return 'wfacp_checkout';
		}

		public static function get_url_rewrite_slug() {
			$bwb_admin_setting_obj = BWF_Admin_General_Settings::get_instance();
			$slug_url              = $bwb_admin_setting_obj->get_option( 'checkout_page_base' );

			return empty( $slug_url ) ? 'checkouts' : $slug_url;
		}

		public static function get_formatted_product_name( $product ) {
			$formatted_variation_list = self::get_variation_attribute( $product );

			$arguments = array();
			if ( ! empty( $formatted_variation_list ) && count( $formatted_variation_list ) > 0 ) {
				foreach ( $formatted_variation_list as $att => $att_val ) {
					if ( $att_val == '' ) {
						$att_val = __( 'any' );
					}
					$att         = strtolower( $att );
					$att_val     = strtolower( $att_val );
					$arguments[] = "$att: $att_val";
				}
			}

			return sprintf( '%s (#%d) %s', $product->get_title(), $product->get_id(), ( count( $arguments ) > 0 ) ? '(' . implode( ',', $arguments ) . ')' : '' );
		}

		public static function get_variation_attribute( $variation ) {
			if ( is_a( $variation, 'WC_Product_Variation' ) ) {
				$variation_attributes = $variation_attributes_basic = $variation->get_attributes();
			} else {

				$variation_attributes = array();
				if ( is_array( $variation ) ) {
					foreach ( $variation as $key => $value ) {
						$variation_attributes[ str_replace( 'attribute_', '', $key ) ] = $value;
					}
				}
			}

			return ( $variation_attributes );
		}

		public static function search_products( $term, $include_variations = false ) {
			global $wpdb;
			$like_term     = '%' . $wpdb->esc_like( $term ) . '%';
			$post_types    = array( 'product', 'product_variation' );
			$post_statuses = current_user_can( 'edit_private_products' ) ? array( 'private', 'publish' ) : array( 'publish' );
			$type_join     = '';
			$type_where    = '';
			$Sql_Query     = $wpdb->prepare( "SELECT DISTINCT posts.ID FROM {$wpdb->posts} posts
				LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				$type_join
				WHERE (
					posts.post_title LIKE %s
					OR (
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
				)
				AND posts.post_type IN ('" . implode( "','", $post_types ) . "')
				AND posts.post_status IN ('" . implode( "','", $post_statuses ) . "')
				$type_where
				ORDER BY posts.post_parent ASC, posts.post_title ASC", $like_term, $like_term );

			$product_ids = $wpdb->get_col( $Sql_Query );

			if ( is_numeric( $term ) ) {
				$post_id       = absint( $term );
				$product_ids[] = $post_id;
			}

			return wp_parse_id_list( $product_ids );
		}

		public static function array_flatten( $array ) {
			if ( ! is_array( $array ) ) {
				return false;
			}
			$result = iterator_to_array( new RecursiveIteratorIterator( new RecursiveArrayIterator( $array ) ), false );

			return $result;
		}

		public static function get_default_product_config() {
			return [
				'title'           => '',
				'discount_type'   => 'percent_discount_sale',
				'discount_amount' => 0,
				'discount_price'  => 0,
				'quantity'        => 1,
			];

		}

		public static function is_load_admin_assets( $screen_type = 'single' ) {
			$screen = get_current_screen();

			if ( filter_input( INPUT_GET, 'page' ) == 'wfacp' && filter_input( INPUT_GET, 'wfacp_id' ) > 0 ) {
				//&& filter_input( INPUT_GET, 'id' ) !== ''
				return true;
			}

			return apply_filters( 'wfacp_enqueue_scripts', false, $screen_type, $screen );
		}

		public static function get_admin_menu() {
			$sections = [
				[
					'slug' => 'design',
					'name' => __( 'Design', 'funnel-builder' ),
					'icon' => '<i class="dashicons dashicons-art"></i>',

				],
				[

					'slug' => 'product',
					'name' => __( 'Products', 'funnel-builder' ),
					'icon' => '<i class="dashicons dashicons-cart"></i>',

				],
				[
					'slug' => 'fields',
					'name' => __( 'Fields', 'funnel-builder' ),
					'icon' => '<i class="dashicons dashicons-menu-alt"></i>',
				],
				[
					'slug' => 'optimization',
					'name' => __( 'Optimizations', 'funnel-builder' ),
					'icon' => '<i class="dashicons dashicons-chart-area"></i>',
				],
				[
					'slug' => 'settings',
					'name' => __( 'Settings', 'funnel-builder' ),
					'icon' => '<i class="dashicons dashicons-admin-generic"></i>',
				],

			];

			$pages = apply_filters( 'wfacp_builder_section_pages', $sections );
			if ( empty( $pages ) ) {
				$pages = $sections;
			}

			return $pages;
		}

		public static function get_discount_type_keys() {

			$discounted = [
				'fixed_discount_reg'    => sprintf( __( '%s Fixed Amount on Regular Price', 'funnel-builder' ), get_woocommerce_currency_symbol() ),
				'fixed_discount_sale'   => sprintf( __( '%s Fixed Amount on Sale Price', 'funnel-builder' ), get_woocommerce_currency_symbol() ),
				'percent_discount_reg'  => __( '% on Regular Price', 'funnel-builder' ),
				'percent_discount_sale' => __( '% on Sale Price', 'funnel-builder' ),
			];

			return $discounted;

		}

		/**
		 * save product against checkout page id
		 *
		 * @param $wfacp_id
		 * @param $product
		 */
		public static function update_page_product( $wfacp_id, $product ) {
			if ( $wfacp_id < 1 ) {
				return;
			}

			if ( empty( $product ) ) {
				$product = [];
			}
			update_post_meta( $wfacp_id, '_wfacp_selected_products', $product );
		}

		/**Update product settings
		 *
		 * @param $wfacp_id
		 * @param $settings
		 */
		public static function update_page_product_setting( $wfacp_id, $settings ) {
			if ( $wfacp_id < 1 ) {
				return;
			}
			if ( empty( $settings ) ) {
				$settings = [];
			}

			update_post_meta( $wfacp_id, '_wfacp_selected_products_settings', $settings );
		}

		public static function update_page_design( $page_id, $data ) {

			if ( $page_id < 1 ) {
				return $data;
			}
			if ( ! is_array( $data ) ) {
				$data = self::default_design_data();
			}

			update_post_meta( $page_id, '_wfacp_selected_design', $data );
			do_action( 'wfacp_update_page_design', $page_id, $data );

			return $data;
		}

		public static function get_fieldset_data( $page_id ) {
			$data = self::get_post_meta_data( $page_id, '_wfacp_fieldsets_data' );
			if ( empty( $data ) ) {
				$data                                = [];
				$layout_data                         = self::get_page_layout( $page_id );
				$prepare_data                        = self::prepare_fieldset( $layout_data );
				$data['current_step']                = $layout_data['current_step'];
				$data['have_billing_address']        = wc_string_to_bool( $layout_data['have_billing_address'] );
				$data['have_shipping_address']       = wc_string_to_bool( $layout_data['have_shipping_address'] );
				$data['have_billing_address_index']  = $layout_data['have_billing_address_index'];
				$data['have_shipping_address_index'] = $layout_data['have_shipping_address_index'];
				$data['have_coupon_field']           = $layout_data['have_coupon_field'];
				$data['fieldsets']                   = $prepare_data['fieldsets'];
			}

			return $data;
		}

		public static function update_page_layout( $page_id, $data, $update_switcher = true ) {
			if ( $page_id == 0 ) {
				return $data;
			}
			if ( isset( $data['address_order'] ) ) {
				update_post_meta( $page_id, '_wfacp_save_address_order', $data['address_order'] );
			}
			$prepare_data = self::prepare_fieldset( $data );
			unset( $data['wfacp_id'], $data['action'], $data['wfacp_nonce'] );

			$fieldset_data = [
				'have_billing_address'        => $data['have_billing_address'],
				'have_shipping_address'       => $data['have_shipping_address'],
				'have_billing_address_index'  => $data['have_billing_address_index'],
				'have_shipping_address_index' => $data['have_shipping_address_index'],
				'have_shipping_method'        => $data['have_shipping_method'],
				'have_coupon_field'           => $data['have_coupon_field'],
				'current_step'                => $data['current_step'],
				'fieldsets'                   => $prepare_data['fieldsets'],
			];


			//this meta use for printing the Form
			update_post_meta( $page_id, '_wfacp_fieldsets_data', $fieldset_data );
			//this meta use for woocommerce_checkout_field filter hooks
			update_post_meta( $page_id, '_wfacp_checkout_fields', $prepare_data['checkout_fields'] );
			update_post_meta( $page_id, '_wfacp_version', WFACP_VERSION );

			//this meta use form generate form at form builder
			update_post_meta( $page_id, '_wfacp_page_layout', $data );

			do_action( 'wfacp_update_page_layout', $page_id, $data );
			unset( $prepare_data, $fieldset_data );
		}


		public static function update_page_custom_fields( $wfacp_id, $data = [] ) {
			if ( $wfacp_id == 0 ) {
				return;
			}
			update_post_meta( $wfacp_id, '_wfacp_page_custom_field', $data );
			do_action( 'wfacp_page_update_custom_field', $wfacp_id, $data );
		}

		/**
		 * remove unnecessay keys from single product array
		 */
		public static function remove_product_keys( $product ) {
			unset( $product['image'] );
			unset( $product['price'] );
			unset( $product['regular_price'] );
			unset( $product['sale_price'] );

			return $product;
		}

		public static function set_customizer_fields_default_vals( $data ) {

			if ( ! is_array( $data ) || count( $data ) == 0 ) {
				return;
			}

			$default_values = array();
			foreach ( $data as $panel_single ) {
				if ( empty( $panel_single ) ) {
					continue;
				}
				/** Panel */
				foreach ( $panel_single as $panel_key => $panel_arr ) {
					/** Section */
					if ( is_array( $panel_arr['sections'] ) && count( $panel_arr['sections'] ) > 0 ) {
						foreach ( $panel_arr['sections'] as $section_key => $section_arr ) {
							$section_key_final = $panel_key . '_' . $section_key;
							/** Fields */
							if ( is_array( $section_arr['fields'] ) && count( $section_arr['fields'] ) > 0 ) {
								foreach ( $section_arr['fields'] as $field_key => $field_data ) {
									$field_key_final = $section_key_final . '_' . $field_key;

									if ( isset( $field_data['default'] ) ) {
										$default_values[ $field_key_final ] = $field_data['default'];
									}
								}
							}
						}
					}
				}
			}
			self::$customizer_fields_default = $default_values;

		}

		public static function get_page_custom_fields( $wfacp_id ) {

			$fields = self::get_post_meta_data( $wfacp_id, '_wfacp_page_custom_field' );

			if ( ! is_array( $fields ) || empty( $fields ) ) {
				$fields = [ 'advanced' => [] ];
			}

			$advanced_fields = self::get_advanced_fields();
			if ( is_array( $advanced_fields ) && count( $advanced_fields ) > 0 ) {
				foreach ( $advanced_fields as $key => $field ) {
					$fields['advanced'][ $key ] = $field;
				}
			}

			return apply_filters( 'wfacp_custom_fields', $fields );
		}

		/**
		 * Return Schema and model data for global setting in admin page
		 *
		 * @param bool $only_model
		 *
		 * @return array
		 */
		public static function global_settings( $only_model = false ) {
			if ( $only_model ) {
				return self::get_frontend_global_settings();
			}

			$output      = self::get_default_global_settings();
			$save_models = get_option( '_wfacp_global_settings', [] );
			$models      = [];
			$tabs        = [];
			foreach ( $output as $key => $value ) {
				if ( ! is_array( $value ) ) {
					continue;
				}
				foreach ( $value as $k => $group_data ) {
					if ( ! isset( $group_data['fields'] ) && count( $group_data['fields'] ) == 0 ) {
						continue;
					}
					foreach ( $group_data['fields'] as $index => $field ) {
						if ( ! isset( $field['model'] ) ) {
							continue;
						}
						$model   = trim( $field['model'] );
						$default = isset( $field['default'] ) ? $field['default'] : '';
						if ( ! empty( $save_models[ $model ] ) ) {
							$default = $save_models[ $model ];
						}
						$models[ $model ] = $default;
					}
					$tabs[] = $group_data['wfacp_data'];
					unset( $group_data['wfacp_data'] );
				}

			}


			return [
				'schema' => $output,
				'tabs'   => $tabs,
				'model'  => apply_filters( 'wfacp_global_setting_fields_model', $models ),
			];
		}

		/**
		 * Optimization
		 * @return array
		 */
		public static function get_frontend_global_settings() {
			$save_models                            = get_option( '_wfacp_global_settings', [] );
			$models                                 = [];
			$models['invalid_email_field']          = __( '%s is not a valid email address.', 'woocommerce' );
			$models['inline_email_field']           = apply_filters( 'wfacp_inline_email_field_message', __( 'Please enter a valid email address', 'woocommerce' ) );
			$models['error_required_msg']           = __( '%s is a required field.', 'woocommerce' );
			$models['field_required_msg']           = __( '%s is a required field.', 'woocommerce' );
			$models['phone_number_invalid']         = __( '%s Enter valid number', 'woocommerce' );
			$models['override_checkout_page_id']    = 0;
			$models['wfacp_set_shipping_method']    = '';
			$models['wfacp_google_address_key']     = '';
			$models['wfacp_global_external_script'] = '';
			$models['wfacp_checkout_global_css']    = '';
			$models['phone_inline_number_number']   = apply_filters( 'wfacp_phone_inline_number_message', __( 'The provided phone number is not valid', 'woocommerce' ) );

			if ( is_array( $save_models ) && 0 < count( $save_models ) ) {
				$keys_to_remove = [
					'invalid_email_field',
					'inline_email_field',
					'error_required_msg',
					'field_required_msg',
					'phone_number_invalid',
					'phone_inline_number_number'
				];

				foreach ( $keys_to_remove as $key ) {
					if ( isset( $save_models[ $key ] ) ) {
						unset( $save_models[ $key ] );
					}
				}
			}

			return wp_parse_args( $save_models, $models );
		}

		public static function base_url() {
			$slug = self::get_url_rewrite_slug();

			return home_url( "/{$slug}/" );
		}


		/**
		 * This function print our custom hidden field type `hidden`
		 *
		 * @param $field
		 * @param $key
		 * @param $args
		 * @param $value
		 *
		 * @return string
		 */
		public static function woocommerce_form_field_hidden( $field, $key, $args, $value ) {
			$args['input_class'][] = 'wfacp_hidden_field';
			$field                 = '<input type="' . esc_attr( $args['type'] ) . '" class="input-hidden ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( is_string( $value ) && ! empty( $value ) ? do_shortcode( $value ) : '' ) . '"  />';

			return $field;
		}


		/**
		 * This function print our custom radion field type `wfacp_radio`
		 *
		 * @param $field
		 * @param $key
		 * @param $args
		 * @param $value
		 *
		 * @return string
		 */
		public static function woocommerce_form_field_wfacp_radio( $field, $key, $args, $value ) {

			$label_id        = $args['id'];
			$args['class'][] = 'wfacp_custom_field_radio_wrap';
			if ( $args['required'] ) {
				$args['class'][] = 'validate-required';
				$required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
			} else {
				$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
			}
			$sort              = $args['priority'] ? $args['priority'] : '';
			$field_container   = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';
			$field             = '';
			$custom_attributes = [];

			unset( $args['input_class'][0] );
			unset( $args['label_class'][0] );
			if ( ! empty( $args['options'] ) ) {
				foreach ( $args['options'] as $option_key => $option_text ) {
					$field .= "<span class='wfacp_radio_options_group'>";
					$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
					$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . '">' . $option_text . '</label>';
					$field .= '</span>';
				}
			}

			$field_html = '';
			if ( $args['label'] && 'checkbox' !== $args['type'] ) {
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . $args['label'] . $required . '</label>';
			}

			$field_html .= '<span class="woocommerce-input-wrapper wfacp-form-control">' . $field;

			if ( $args['description'] ) {
				$field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
			}

			$field_html .= '</span>';

			$container_class = esc_attr( implode( ' ', $args['class'] ) );
			$container_id    = esc_attr( $args['id'] ) . '_field';
			$field           = sprintf( $field_container, $container_class, $container_id, $field_html );

			return $field;
		}


		/**
		 * This function print our custom start div tag field type `_wfacp_start_divider`
		 * this field for separet some field from other fields
		 *
		 * @param $field
		 * @param $key
		 * @param $args
		 * @param $value
		 *
		 * @return string
		 */
		public static function woocommerce_form_field_wfacp_start_divider( $field, $key, $args, $value ) {

			$template = wfacp_template();
			if ( null == $template ) {
				return '';
			}
			$index      = $template->get_shipping_billing_index();
			$data       = self::get_address_field_order( self::get_id() );
			$address_id = '';
			if ( 'shipping' == $index ) {
				$address_id = 'shipping-address';
			} else if ( 'billing' == $index ) {
				$address_id = 'address';
			}

			if ( ( $index == 'shipping' && 'wfacp_divider_shipping' == $args['id'] && 'radio' == $data[ 'display_type_' . $address_id ] ) || ( $index == 'billing' && 'wfacp_divider_billing' == $args['id'] && 'radio' == $data[ 'display_type_' . $address_id ] ) ) {
				$args['label_class'][] = 'wfacp_divider_second_child';
			}

			ob_start();
			echo '<div class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">';

			if ( 'wfacp_divider_billing' == $args['id'] ) {
				do_action( 'wfacp_divider_billing' );

			}
			if ( 'wfacp_divider_shipping' == $args['id'] ) {
				do_action( 'wfacp_divider_shipping' );
			}

			return ob_get_clean();

		}

		/**
		 * This function print our custom start div tag field type `wfacp_end_start_divider`
		 * this field for separet some field from other fields
		 *
		 * @param $field
		 * @param $key
		 * @param $args
		 * @param $value
		 *
		 * @return string
		 */
		public static function woocommerce_form_field_wfacp_end_start_divider( $field, $key, $args, $value ) {
			$template = wfacp_template();
			if ( null == $template ) {
				return '';
			}

			if ( isset( $args['id'] ) && 'wfacp_divider_billing_end' == $args['id'] ) {
				do_action( 'wfacp_divider_billing_end' );
			}
			if ( isset( $args['id'] ) && 'wfacp_divider_shipping_end' == $args['id'] ) {
				do_action( 'wfacp_divider_shipping_end' );
			}

			return '<div class="wfacp_clear"></div> </div>';
		}


		/**
		 * Get all product of checkout page Setting
		 *
		 * @param $wfacp_id
		 *
		 * @return array|mixed
		 */

		public static function get_page_product_settings( $wfacp_id ) {
			$wfacp_id = absint( $wfacp_id );
			$settings = self::get_post_meta_data( $wfacp_id, '_wfacp_selected_products_settings' );

			if ( ! is_array( $settings ) ) {
				return [
					'add_to_cart_setting' => '2',
				];
			}

			$settings = apply_filters( 'wfacp_page_product_settings', $settings );

			return $settings;

		}

		public static function get_post_meta_data( $item_id, $meta_key = '', $force = false ) {
			if ( empty( $item_id ) ) {
				return '';
			}
			$wfacp_cache_obj = WooFunnels_Cache::get_instance();
			$cache_key       = 'wfacp_post_meta' . $item_id;

			$force = apply_filters( 'wfacp_get_post_meta_data', $force, $item_id, $meta_key );

			/** When force enabled */
			if ( true === $force && ! empty( $meta_key ) ) {
				return get_post_meta( $item_id, $meta_key, true );
			}

			$cache_data = $wfacp_cache_obj->get_cache( $cache_key, WFACP_SLUG );
			if ( false !== $cache_data ) {
				$post_meta = $cache_data;
			} else {
				$post_meta = get_post_meta( $item_id );
				$post_meta = self::parsed_query_results( $post_meta );
				if ( ! empty( $post_meta ) ) {
					$wfacp_cache_obj->set_cache( $cache_key, $post_meta, WFACP_SLUG );
				}
			}

			if ( empty( $post_meta ) ) {
				return '';
			}

			if ( ! empty( $meta_key ) ) {
				return isset( $post_meta[ $meta_key ] ) ? $post_meta[ $meta_key ] : '';
			}

			return $post_meta;
		}

		public static function parsed_query_results( $results ) {
			$parsed_results = array();
			if ( is_array( $results ) && count( $results ) > 0 ) {
				foreach ( $results as $key => $result ) {
					$parsed_results[ $key ] = maybe_unserialize( $result['0'] );
				}
			}

			return $parsed_results;
		}

		/**
		 * Get all product of checkout page
		 *
		 * @param $wfacp_id
		 *
		 * @return array|mixed
		 */

		public static function get_page_product( $wfacp_id ) {

			$wfacp_id = absint( $wfacp_id );
			$product  = self::get_post_meta_data( $wfacp_id, '_wfacp_selected_products' );

			if ( ! is_array( $product ) ) {
				return [];
			}

			return apply_filters( 'wfacp_save_products', $product );

		}

		/**
		 * Get checkout page default settings
		 *
		 * @param $page_id
		 *
		 * @return array|mixed|string
		 */
		public static function get_page_settings( $page_id ) {

			$data              = self::get_post_meta_data( $page_id, '_wfacp_page_settings' );
			$current_version   = WFACP_Common::get_checkout_page_version();
			$buttons_positions = self::smart_buttons_positions();
			$default_data      = [
				'coupons'                             => '',
				'enable_coupon'                       => 'false',
				'disable_coupon'                      => 'false',
				'hide_quantity_switcher'              => 'false',
				'enable_delete_item'                  => false,
				'hide_product_image'                  => true,
				'is_hide_additional_information'      => 'true',
				'hide_quick_view'                     => 'false',
				'hide_you_save'                       => 'true',
				'close_after_x_purchase'              => 'false',
				'total_purchased_allowed'             => '',
				'close_checkout_after_date'           => 'false',
				'close_checkout_on'                   => '',
				'close_checkout_redirect_url'         => '',
				'total_purchased_redirect_url'        => '',
				'hide_best_value'                     => false,
				'best_value_product'                  => '',
				'best_value_text'                     => __( 'Best Value', 'woofunnels-aero-checkout' ),
				'best_value_position'                 => 'below',
				'enable_custom_name_in_order_summary' => 'false',
				'autocomplete_enable'                 => 'false',
				'autocomplete_google_key'             => '',
				'preferred_countries_enable'          => 'false',
				'enable_autopopulate_fields'          => 'true',
				'enable_autopopulate_state'           => 'true',
				'autopopulate_state_service'          => 'zippopotamus',
				'override_tracking_events'            => 'false',

				'preferred_countries'                        => '',
				'enable_smart_buttons'                       => 'false',
				'override_global_track_event'                => 'false',
				'pixel_is_page_view'                         => 'false',
				'google_ua_is_page_view'                     => 'false',
				'pixel_add_to_cart_event'                    => 'false',
				'pixel_add_to_cart_event_position'           => 'load',
				'pixel_initiate_checkout_event'              => 'false',
				'pixel_initiate_checkout_event_position'     => 'load',
				'pixel_add_payment_info_event'               => 'false',
				'google_ua_add_to_cart_event'                => 'false',
				'google_ua_add_to_cart_event_position'       => 'load',
				'google_ua_initiate_checkout_event'          => 'false',
				'google_ua_initiate_checkout_event_position' => 'load',
				'google_ua_add_payment_info_event'           => 'false',
				'auto_fill_url_autoresponder'                => 'select_email_provider',
				'smart_button_position'                      => $buttons_positions[0],
				'enable_google_autocomplete'                 => 'false',
				'enable_phone_flag'                          => 'true',
				'enable_phone_validation'                    => 'false',
				'save_phone_number_type'                     => 'false',
				'enable_live_validation'                     => 'false',
				'enable_address_field_number_validation'     => 'false',
				'address_field_number_validation_message'    => __( 'House/Building number is required in {{address_field}}', 'woofunnel-aero-checkout' ),
			];


			if ( is_array( $data ) && count( $data ) > 0 ) {
				foreach ( $default_data as $key => $val ) {
					if ( ! isset( $data[ $key ] ) ) {
						$data[ $key ] = $val;
					}
				}
				$output = $data;
			} else {
				$output = $default_data;
			}

			return apply_filters( 'wfacp_page_settings', $output );
		}


		public static function get_checkout_fields( $page_id ) {
			$data = self::get_post_meta_data( $page_id, '_wfacp_checkout_fields' );
			if ( empty( $data ) ) {
				$layout_data  = self::get_page_layout( $page_id );
				$prepare_data = self::prepare_fieldset( $layout_data );
				$data         = $prepare_data['checkout_fields'];
			}

			return $data;
		}

		/**
		 * Prepare fieldset using this prepration we display section wise field on frontend
		 *
		 * @param $data
		 *
		 * @return array
		 */
		public static function prepare_fieldset( $data ) {

			$fieldsets             = $data['fieldsets'];
			$checkout_fields       = [];
			$have_billing_address  = wc_string_to_bool( $data['have_billing_address'] );
			$have_shipping_address = wc_string_to_bool( $data['have_shipping_address'] );

			$hide_apply_cls_type = '';
			if ( $have_shipping_address && $have_billing_address ) {
				$have_billing_address_index  = absint( $data['have_billing_address_index'] );
				$have_shipping_address_index = absint( $data['have_shipping_address_index'] );

				if ( $have_billing_address_index < $have_shipping_address_index ) {
					$hide_apply_cls_type = 'shipping';
				} else {
					$hide_apply_cls_type = 'billing';
				}
			}

			if ( ! is_array( $fieldsets ) ) {
				return [
					'fieldset' => [],
					'fields'   => [],
				];
			}
			$address_field_order = WFACP_Common::get_address_field_order( WFACP_Common::get_id() );

			foreach ( $fieldsets as $step => $sections ) {
				if ( is_array( $sections ) && count( $sections ) > 0 ) {
					foreach ( $sections as $section_index => $section ) {
						if ( ! isset( $section['fields'] ) || count( $section['fields'] ) == 0 ) {
							continue;
						}
						$fields       = $section['fields'];
						$newFields    = [];
						$custom_index = 0;
						foreach ( $fields as $field_index => $field ) {
							$field_id   = isset( $field['id'] ) ? $field['id'] : '';
							$field_type = isset( $field['field_type'] ) ? $field['field_type'] : '';
							if ( empty( $field_type ) && isset( $field['is_wfacp_field'] ) && isset( $field['allow_delete'] ) ) {
								$field_type          = 'advanced';
								$field['field_type'] = $field_type;
							}
							if ( ( $field_id == 'address' || $field_id == 'shipping-address' ) && in_array( $field_type, [ 'billing', 'shipping' ] ) ) {
								$field_type = 'billing';
								if ( $field_id == 'shipping-address' ) {
									$field_type = 'shipping';
								}
								// Merge address field into separate fields
								$add_fields = self::get_address_fields( $field_type . '_', true );
								if ( is_array( $add_fields ) && count( $add_fields ) > 0 ) {

									$newFields[ 'wfacp_start_divider_' . $field_type ] = self::get_start_divider_field( $field_type );

									$addRessData    = $fields[ $field_index ];
									$fields_options = $addRessData['fields_options'];

									$fields_options = apply_filters( 'wfacp_address_fields_' . $field_type, $fields_options );
									foreach ( $fields_options as $field_key => $field_value ) {
										if ( is_null( $field_value ) ) {
											continue;
										}
										$temp_key   = $field_type . '_' . $field_key;
										$temp_value = array_values( $field_value );
										if ( ! isset( $add_fields[ $temp_key ] ) ) {
											continue;
										}
										if ( ( false == $have_billing_address && 'shipping_same_as_billing' == $temp_key ) ) {
											continue;
										}
										if ( false == $have_shipping_address && 'billing_same_as_shipping' == $temp_key ) {
											continue;
										}
										$val = $add_fields[ $temp_key ];
										if ( 'true' === $temp_value[0] ) {

											if ( 'shipping_same_as_billing' == $temp_key && 'billing' == $hide_apply_cls_type ) {
												continue;
											}
											if ( 'billing_same_as_shipping' == $temp_key && 'shipping' == $hide_apply_cls_type ) {
												continue;
											}
											if ( isset( $temp_value[1] ) && '' !== $temp_value[1] ) {
												$val['label'] = $temp_value[1];
											}
											if ( isset( $temp_value[2] ) ) {
												$val['placeholder'] = $temp_value[2];
											}
											if ( isset( $field_value['required'] ) ) {
												$val['required'] = $field_value['required'];
											}

											$val['id'] = $temp_key;
											if ( 'shipping' == $hide_apply_cls_type && 'shipping' == $field_type && 'shipping_same_as_billing' != $temp_key ) {
												if ( wc_string_to_bool( $fields_options['same_as_billing']['same_as_billing'] ) === true ) {
													$val['class'][] = 'wfacp_' . $field_type . '_fields';
													$val['class'][] = 'wfacp_' . $field_type . '_field_hide';

												}
											}
											if ( 'billing' == $hide_apply_cls_type && 'billing' == $field_type && 'billing_same_as_shipping' != $temp_key ) {
												if ( wc_string_to_bool( $fields_options['same_as_shipping']['same_as_shipping'] ) === true ) {
													$val['class'][] = 'wfacp_' . $field_type . '_fields';
													$val['class'][] = 'wfacp_' . $field_type . '_field_hide';
												}
											}

											if ( isset( $val['required'] ) && 'false' === $val['required'] ) {
												unset( $val['required'] );
											}

											/**
											 * Address Same as billing or use different section start
											 */
											if ( 'shipping_same_as_billing' == $temp_key || 'billing_same_as_shipping' == $temp_key ) {
												$val['label']         = $temp_value[1];
												$display_type         = $address_field_order[ 'display_type_' . $field_id ];
												$val['radio_options'] = 'no';
												if ( isset( $temp_value[2] ) && '' != $temp_value[2] && 'radio' == $display_type ) {
													$val['label_2']       = $temp_value[2];
													$val['radio_options'] = 'yes';
												}
											}
											/**
											 * Address Same as billing or use different section end here
											 */


											$val['address_group']                        = true;
											$checkout_fields[ $field_type ][ $temp_key ] = $val;
											$newFields[ $custom_index ]                  = $val;
											$custom_index ++;
										} else {

											if ( $val['type'] == 'country' ) {
												$val['id']            = $temp_key;
												$val['class'][]       = 'wfacp_country_field_hide';
												$default_customer_add = get_option( 'woocommerce_default_customer_address', '' );

												if ( '' == $default_customer_add ) {
													$wc_default = wc_get_base_location();
													if ( isset( $wc_default['country'] ) && '' !== $wc_default['country'] ) {
														$default_country = trim( $wc_default['country'] );
													} elseif ( class_exists( 'WC_Geolocation' ) ) {
														$ip_data = self::get_geo_ip();

														if ( is_array( $ip_data ) && isset( $ip_data['country'] ) ) {
															$default_country = $ip_data['country'];
														}
													}
												} else {
													$wc_default = wc_get_base_location();
													if ( isset( $wc_default['country'] ) && '' !== $wc_default['country'] ) {
														$default_country = trim( $wc_default['country'] );
													}
												}

												$val['default'] = $default_country;
												if ( isset( $val['required'] ) ) {
													unset( $val['required'] );
												}
												$checkout_fields[ $field_type ][ $temp_key ] = $val;
												$newFields[ $custom_index ]                  = $val;
												$custom_index ++;
											}
										}
										unset( $temp_key, $temp_value, $field_value );
									}
									$newFields[ 'wfacp_end_divider_' . $field_type ] = self::get_end_divider_field( $field_type );
									unset( $fields[ $field_index ], $fields_options, $addRessData, $add_fields );
								}
							} else {
								if ( isset( $field['required'] ) && 'false' === $field['required'] ) {
									unset( $field['required'] );
								}

								$checkout_fields[ $field_type ][ $field_id ] = $field;
								$newFields[ $custom_index ]                  = $field;
								$custom_index ++;
							}
						}
						$fieldsets[ $step ][ $section_index ]['fields'] = $newFields;
					}
				}
			}

			unset( $data, $newFields, $custom_index );

			return [
				'fieldsets'       => $fieldsets,
				'checkout_fields' => $checkout_fields,
			];
		}

		public static function get_address_fields( $type = 'billing_', $unset = false ) {

			$unset_address_fields = [
				'billing_'  => [ 'billing_company', 'billing_country', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_same_as_shipping' ],
				'shipping_' => [
					'shipping_company',
					'shipping_country',
					'shipping_address_1',
					'shipping_address_2',
					'shipping_city',
					'shipping_state',
					'shipping_postcode',
					'shipping_same_as_billing'
				],
			];

			$unset_address_fields = apply_filters( 'wfacp_unset_address_fields', $unset_address_fields );
			$countries            = new WC_Countries();
			$country              = $countries->get_base_country();

			if ( is_admin() ) {
				do_action( 'wfacp_before_get_address_field_admin' );
				remove_all_filters( 'woocommerce_default_address_fields' );
			}
			$fields = $countries->get_default_address_fields();
			$fields = apply_filters( 'wfacp_default_' . $type . 'address_fields', $fields, $country );

			$locale = $countries->get_country_locale();

			if ( isset( $locale[ $country ] ) ) {
				$fields = wc_array_overlay( $fields, $locale[ $country ] );
			}

			$address_fields = array();

			foreach ( $fields as $key => $value ) {
				if ( 'state' === $key ) {
					$value['country_field'] = $type . 'country';
				}

				if ( ! isset( $value['type'] ) || '' == $value['type'] ) {
					$value['type'] = 'text';
				}
				if ( ! isset( $value['cssready'] ) || '' == $value['cssready'] ) {
					$value['cssready'] = [];
				}
				$field_key                                   = $type . $key;
				$address_fields[ $field_key ]                = $value;
				$address_fields[ $field_key ]['field_type']  = str_replace( '_', '', $type );
				$address_fields[ $field_key ]['placeholder'] = isset( $value['label'] ) ? $value['label'] : '';
				if ( $field_key == 'shipping_state' || $field_key == 'billing_state' ) {
					$address_fields[ $field_key ]['class'][] = 'update_totals_on_change';
				}

				if ( false == $unset && in_array( $field_key, $unset_address_fields[ $type ] ) ) {

					unset( $address_fields[ $field_key ] );
				}
			}
			if ( false != $unset ) {
				if ( 'shipping_' === $type ) {

					$address_fields['shipping_same_as_billing'] = [
						'label'          => WFACP_Common::translation_string_to_check( __( 'Use a different shipping address', 'funnel-builder' ) ),
						'label_2'        => '',
						'type'           => 'checkbox',
						'value'          => 'off',
						'is_wfacp_field' => true,
						'class'          => [],
						'priority'       => 100,
					];
				} else {
					$address_fields['billing_same_as_shipping'] = [
						'label'          => WFACP_Common::translation_string_to_check( __( 'Use a different billing address', 'funnel-builder' ) ),
						'label_2'        => '',
						'type'           => 'checkbox',
						'value'          => 'off',
						'is_wfacp_field' => true,
						'class'          => [],
						'priority'       => 100,
					];
				}
			}


			if ( 'hidden' !== get_option( 'woocommerce_checkout_phone_field', 'required' ) ) {
				$address_fields['billing_phone'] = array(
					'label'        => __( 'Phone', 'woocommerce' ),
					'type'         => 'tel',
					'class'        => array( 'form-row-wide' ),
					'validate'     => array( 'phone' ),
					'placeholder'  => '',
					'autocomplete' => 'tel',
					'priority'     => 100,
				);
				//added 3.4.1
				$address_fields['shipping_phone'] = array(
					'label'        => WFACP_Common::translation_string_to_check( __( 'Shipping Phone', 'woofunnels-aero-checkout' ) ),
					'type'         => 'tel',
					'class'        => array( 'form-row-wide' ),
					'validate'     => array( 'phone' ),
					'placeholder'  => '',
					'autocomplete' => 'tel',
					'priority'     => 100,
				);
			}
			if ( 'billing_' === $type ) {
				$address_fields['billing_email'] = array(
					'label'        => __( 'Email', 'woocommerce' ),
					'required'     => true,
					'type'         => 'email',
					'class'        => array( 'form-row-wide' ),
					'validate'     => array( 'email' ),
					'autocomplete' => 'no' === get_option( 'woocommerce_registration_generate_username' ) ? 'email' : 'email username',
					'priority'     => 110,
				);
			}


			return apply_filters( 'wfacp_' . $type . 'field', $address_fields, $type );
		}


		public static function get_start_divider_field( $unique_key = '' ) {

			if ( '' == $unique_key ) {
				$unique_key = uniqid( 'wfacp_field_' );
			}

			return [
				'type'        => 'wfacp_start_divider',
				'label_class' => [ 'wfacp_divider_field', 'wfacp_divider_' . $unique_key ],
				'id'          => 'wfacp_divider_' . $unique_key,
			];
		}

		public static function get_end_divider_field( $unique_key = '' ) {
			if ( empty( $unique_key ) ) {
				$unique_key = uniqid( 'wfacp_field_' );
			}

			return [
				'type' => 'wfacp_end_divider',
				'id'   => 'wfacp_divider_' . $unique_key . '_end',
			];
		}

		public static function calculate_discount( $options ) {
			if ( ! isset( $options['wfacp_product_rp'] ) ) {
				return null;
			}

			$discount_type = $options['wfacp_discount_type'];
			$reg_price     = floatval( $options['wfacp_product_rp'] );
			$price         = floatval( $options['wfacp_product_p'] );
			$value         = floatval( $options['wfacp_discount_amount'] );
			switch ( $discount_type ) {
				case 'fixed_discount_reg':
					if ( 0 == $value ) {
						$discounted_price = $reg_price;
						break;
					}
					$discounted_price = $reg_price - ( $value );
					break;
				case 'fixed_discount_sale':
					if ( 0 == $value ) {
						$discounted_price = $price;
						break;
					}
					$discounted_price = $price - ( $value );

					break;
				case 'percent_discount_reg':
					if ( 0 == $value ) {
						$discounted_price = $price;
						break;
					}
					$discounted_price = ( $value > 0 ) ? $reg_price - ( ( $value / 100 ) * $reg_price ) : $reg_price;
					break;
				case 'percent_discount_sale':
					if ( 0 == $value ) {
						$discounted_price = $price;
						break;
					}
					$discounted_price = ( $value > 0 ) ? $price - ( ( $value / 100 ) * $price ) : $price;
					break;
				case 'flat_price':
					$discounted_price = ( $value > 0 ) ? ( $value ) : $price;
					break;
				default:
					$discounted_price = $price;
					break;
			}
			if ( $discounted_price < 0 ) {
				$discounted_price = 0;
			}

			return $discounted_price;
		}

		public static function wc_get_product( $product_id, $unique_key ) {

			if ( isset( self::$product_data[ $unique_key ][ $product_id ] ) ) {
				return self::$product_data[ $unique_key ][ $product_id ];
			}
			self::$product_data[ $unique_key ][ $product_id ] = wc_get_product( $product_id );

			return self::$product_data[ $unique_key ][ $product_id ];
		}


		public static function process_wfacp_html( $field, $key, $args, $value ) {

			if ( is_null( WC()->session ) ) {
				return '';
			}

			WC()->session->set( 'wfacp_' . $key . '_field', $args );
			$status = apply_filters( 'wfacp_html_fields_' . $key, true, $field, $key, $args, $value );

			if ( $status ) {
				if ( 'order_summary' === $key ) {
					self::order_summary_html( $args );
				} elseif ( 'shipping_calculator' === $key ) {

					WC()->session->set( 'shipping_calculator_' . self::get_id(), $args );
					include WFACP_TEMPLATE_COMMON . '/shipping-options.php';
				} elseif ( 'order_coupon' === $key ) {
					WC()->session->set( 'order_coupon_' . self::get_id(), $args );
					include WFACP_TEMPLATE_COMMON . '/order-coupon.php';
				}
			} else {
				do_action( 'process_wfacp_html', $field, $key, $args, $value );
			}

			return '';
		}


		/**
		 * @param WC_Product_Variable $product ;
		 */
		public static function get_default_variation( $product ) {

			if ( $product instanceof WC_Product_Variable ) {
				$var_data = $product->get_data();

				if ( isset( $var_data['default_attributes'] ) && count( $var_data['default_attributes'] ) > 0 ) {
					$attributes = $var_data['default_attributes'];
					$matched_id = self::find_matching_product_variation( $product, $attributes );

					if ( ! is_null( $matched_id ) && $matched_id > 0 ) {
						return self::get_first_variation( $product, $matched_id );
					}

					return self::get_first_variation( $product );

				} else {
					return self::get_first_variation( $product );
				}
			}

			return [];
		}

		/**
		 * Find matching product variation
		 *
		 * @param WC_Product $product
		 * @param array $attributes
		 *
		 * @return int Matching variation ID or 0.
		 */
		public static function find_matching_product_variation( $product, $attributes ) {

			foreach ( $attributes as $key => $value ) {
				if ( strpos( $key, 'attribute_' ) === 0 ) {
					continue;
				}

				unset( $attributes[ $key ] );
				$attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
			}

			if ( class_exists( 'WC_Data_Store' ) ) {

				$data_store = WC_Data_Store::load( 'product' );

				return $data_store->find_matching_product_variation( $product, $attributes );

			} else {

				return $product->get_matching_variation( $attributes );

			}

			return null;
		}

		/**
		 * CHeck is blank attribute present in Variation Attribute
		 * @return boolean
		 */
		public static function is_invalid_variation_attribute( $variation_attributes ) {
			$blank_attribute = array_filter( $variation_attributes, function ( $v ) {
				return is_null( $v ) || empty( $v );
			} );

			return is_array( $blank_attribute ) && ! empty( $blank_attribute );
		}

		/**
		 * get first available variation
		 *
		 * @param $product WC_Product_Variable
		 */
		public static function get_first_variation( $product, $vars_id = 0 ) {
			if ( $product instanceof WC_Product_Variable ) {
				$vars               = $product->get_available_variations();
				$product_attributes = $product->get_variation_attributes();
				if ( count( $vars ) == 0 ) {
					return [];
				}
				$available_variable = [];
				foreach ( $vars as $v ) {
					$vid = $v['variation_id'];
					// If variation id pass in function then return matched vars
					if ( $vars_id > 0 && $vid == $vars_id ) {
						return $v;
					}
					if ( ( wc_string_to_bool( $v['is_in_stock'] ) && $v['is_purchasable'] ) ) {
						$available_variable[ $vid ] = $v;
					}
				}
				if ( empty( $available_variable ) ) {
					return [];
				}

				if ( isset( $available_variable[ $vars_id ] ) ) {
					return $available_variable[ $vars_id ];
				}
				$first_key = key( $available_variable );
				//check any any case
				$variation_attributes = $available_variable[ $first_key ]['attributes'];
				if ( self::is_invalid_variation_attribute( $variation_attributes ) ) {
					$available_variable[ $first_key ]['attributes'] = self::map_variation_attributes( wc_get_product( $first_key )->get_attributes(), $product_attributes );
				}

				return $available_variable[ $first_key ];

			}

			return [];
		}


		public static function map_variation_attributes( $variation_attr, $product_attr ) {


			$new_product_attr = [];
			foreach ( $product_attr as $k => $item ) {
				$k                      = strtolower( $k );//Lowering the Attribute keys
				$k                      = str_replace( ' ', '-', $k );
				$new_product_attr[ $k ] = $item;
			}
			$output = [];
			foreach ( $variation_attr as $key => $attr ) {
				if ( empty( $attr ) ) {
					$key  = str_replace( ' ', '-', $key );
					$attr = $new_product_attr[ $key ][0];
				}
				$output[ 'attribute_' . $key ] = $attr;
			}

			return $output;
		}

		/**
		 * Check stock of the product
		 *
		 * @param $product_obj
		 * @param $new_qty
		 *
		 * @return bool
		 */
		public static function check_manage_stock( $product_obj, $new_qty ) {

			if ( ! $product_obj instanceof WC_Product ) {

				return false;
			}
			if ( $new_qty < 1 ) {
				return false;
			}

			// when stock management is on in product
			if ( true == $product_obj->managing_stock() ) {

				$available_qty = $product_obj->get_stock_quantity();
				if ( $available_qty < $new_qty ) {

					if ( ! in_array( $product_obj->get_backorders(), [ 'yes', 'notify' ] ) ) {
						return false;
					}
				}
			} else {
				// for non stock managerment
				return $product_obj->is_in_stock();
			}

			return true;
		}

		/**
		 * get pixel initiated pixel checkout data
		 * @return array
		 */

		public static function analytics_checkout_data() {


			$final    = [];
			$services = WFACP_Analytics::get_available_service();
			foreach ( $services as $service => $analytic ) {
				/**
				 * @var $analytic WFACP_Analytics;
				 */
				$final[ $service ] = $analytic->get_checkout_data();

			}

			return apply_filters( 'wfacp_checkout_data', $final, WC()->cart );
		}

		public static function analytics_add_to_cart_data() {
			$final    = [];
			$services = WFACP_Analytics::get_available_service();
			foreach ( $services as $service => $analytic ) {
				/**
				 * @var $analytic WFACP_Analytics;
				 */
				$final[ $service ] = $analytic->get_add_to_cart_data();
			}

			return $final;
		}

		/**
		 * @param $product_obj WC_Product
		 * @param $cart_item []
		 */
		public static function analytics_item( $product_obj, $cart_item ) {


			$final    = [];
			$services = WFACP_Analytics::get_available_service();
			foreach ( $services as $service => $analytic ) {
				/**
				 * @var $analytic WFACP_Analytics;
				 */
				$final[ $service ] = $analytic->get_item( $product_obj, $cart_item );
			}

			return apply_filters( 'wfacp_item_added_to_cart', $final, $product_obj, $cart_item );
		}


		public static function get_variable_product_type() {
			return [ 'variable', 'variable-subscription' ];
		}

		public static function get_variation_product_type() {
			return [ 'variation', 'subscription_variation' ];
		}

		public static function get_subscription_product_type() {

			if ( ! class_exists( 'WC_Subscriptions_Product' ) ) {
				return [];
			}

			return [ 'variable-subscription', 'subscription', 'subscription_variation' ];
		}

		/**
		 * Copy data from old checkout page to new checkout page
		 *
		 * @param $post_id
		 *
		 * @return int|null|WP_Error
		 */
		public static function make_duplicate( $post_id ) {
			if ( $post_id > 0 ) {
				$post = get_post( $post_id );
				if ( ! is_null( $post ) && $post->post_type === self::get_post_type_slug() ) {

					$args        = [
						'post_title'   => $post->post_title . ' - ' . __( 'Copy', 'funnel-builder' ),
						'post_content' => $post->post_content,
						'post_name'    => sanitize_title( $post->post_title . ' - ' . __( 'Copy', 'funnel-builder' ) ),
						'post_type'    => self::get_post_type_slug(),
						'post_status'  => 'draft',
					];
					$new_post_id = wp_insert_post( $args );
					if ( ! is_wp_error( $new_post_id ) ) {
						self::get_duplicate_data( $new_post_id, $post_id );
						update_post_meta( $new_post_id, '_wfacp_version', WFACP_VERSION );

						return $new_post_id;
					}
				}
			}

			return null;
		}

		public static function get_duplicate_data( $new_post_id, $post_id ) {

			$data = [
				'_wfacp_selected_products'          => get_post_meta( $post_id, '_wfacp_selected_products', true ),
				'_wfacp_selected_products_settings' => get_post_meta( $post_id, '_wfacp_selected_products_settings', true ),
				'_wfacp_selected_design'            => get_post_meta( $post_id, '_wfacp_selected_design', true ),
				'_wfacp_page_layout'                => get_post_meta( $post_id, '_wfacp_page_layout', true ),
				'_wfacp_page_settings'              => get_post_meta( $post_id, '_wfacp_page_settings', true ),
				'_wfacp_page_custom_field'          => get_post_meta( $post_id, '_wfacp_page_custom_field', true ),
				'_wfacp_fieldsets_data'             => get_post_meta( $post_id, '_wfacp_fieldsets_data', true ),
				'_wfacp_checkout_fields'            => get_post_meta( $post_id, '_wfacp_checkout_fields', true ),
				'_wfacp_product_switcher_setting'   => get_post_meta( $post_id, '_wfacp_product_switcher_setting', true ),
				'_wfacp_save_address_order'         => get_post_meta( $post_id, '_wfacp_save_address_order', true ),
				'_post_description'                 => get_post_meta( $post_id, '_post_description', true ),
				'_wp_page_template'                 => get_post_meta( $post_id, '_wp_page_template', true ),
			];

			foreach ( $data as $meta_key => $meta_value ) {
				update_post_meta( $new_post_id, $meta_key, $meta_value );
			}
			//copy customizer setting
			update_option( WFACP_SLUG . '_c_' . $new_post_id, get_option( WFACP_SLUG . '_c_' . $post_id, [] ), 'no' );
			do_action( 'wfacp_duplicate_pages', $new_post_id, $post_id, $data );
		}

		public static function wc_dropdown_variation_attribute_options( $args = array() ) {
			$args = wp_parse_args( apply_filters( 'woocommerce_wfacp_dropdown_variation_attribute_options_args', $args ), array(
				'options'          => false,
				'attribute'        => false,
				'product'          => false,
				'selected'         => false,
				'name'             => '',
				'id'               => '',
				'class'            => '',
				'show_option_none' => __( 'Choose an option', 'woocommerce' ),
			) );

			// Get selected value.
			if ( false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product ) {
				$selected_key = 'attribute_' . sanitize_title( $args['attribute'] );

				$selected_data = filter_input( INPUT_POST, $selected_key, FILTER_UNSAFE_RAW );

				$args['selected'] = ! is_null( $selected_data ) ? wc_clean( urldecode( wp_unslash( $selected_data ) ) ) : $args['product']->get_variation_default_attribute( $args['attribute'] );
			}

			$options               = $args['options'];
			$product               = $args['product'];
			$attribute             = $args['attribute'];
			$name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
			$id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
			$class                 = $args['class'];
			$show_option_none      = (bool) $args['show_option_none'];
			$show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' ); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

			if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
				$attributes = $product->get_variation_attributes();
				$options    = $attributes[ $attribute ];
			}

			$html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
			$html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';

			if ( ! empty( $options ) ) {
				if ( $product && taxonomy_exists( $attribute ) ) {
					// Get terms if this is a taxonomy - ordered. We need the names too.
					$terms = wc_get_product_terms( $product->get_id(), $attribute, array(
						'fields' => 'all',
					) );

					foreach ( $terms as $term ) {
						if ( in_array( $term->slug, $options, true ) ) {
							$html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
						}
					}
				} else {
					foreach ( $options as $option ) {
						// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
						$selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
						$html     .= '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
					}
				}
			}

			$html .= '</select>';

			echo apply_filters( 'woocommerce_wfacp_dropdown_variation_attribute_options_html', $html, $args ); // WPCS: XSS ok.
		}

		public static function wfacp_order_custom_field( $atts ) {

			$atts = shortcode_atts( array(
				'order_id' => 0,
				'field_id' => '',
				'type'     => 'value',
			), $atts );

			$field = $atts['field_id'];
			if ( '' == $field ) {
				return '';
			}

			$order_id = absint( $atts['order_id'] );
			if ( 0 === $order_id && isset( $_REQUEST['order_id'] ) && absint( $_REQUEST['order_id'] ) > 0 ) {
				$order_id = absint( $_REQUEST['order_id'] );
			}

			$order_id = apply_filters( 'wfacp_custom_field_order_id', $order_id );
			if ( empty( $order_id ) ) {
				return '';
			}

			$meta_keys = [
				'billing_email',
				'billing_first_name',
				'billing_last_name',
				'billing_phone',
				'billing_country',
				'billing_city',
				'billing_address_1',
				'billing_address_2',
				'billing_postcode',
				'billing_company',
				'billing_state',
				'shipping_first_name',
				'shipping_last_name',
				'shipping_phone',
				'shipping_country',
				'shipping_city',
				'shipping_address_1',
				'shipping_address_2',
				'shipping_postcode',
				'shipping_state',

			];
			$order     = wc_get_order( $order_id );
			if ( $atts['type'] == 'value' ) {
				if ( in_array( $field, $meta_keys ) ) {
					$field = '_' . $field;
				}

				$metadata = wfacp_get_order_meta( $order, $field );
				if ( is_string( $metadata ) ) {
					return $metadata;
				}
			} else {
				$fpos = strpos( $field, '_' );
				if ( 0 === $fpos ) {
					$field = substr( $field, 1, strlen( $field ) );

				}
				$wfacp_id = wfacp_get_order_meta( $order, '_wfacp_post_id' );
				if ( empty( $wfacp_id ) ) {
					return '';
				}

				$wfacp_id = absint( $wfacp_id );

				$checkout_fields = get_post_meta( $wfacp_id, '_wfacp_checkout_fields', true );
				if ( ! is_array( $checkout_fields ) || count( $checkout_fields ) == 0 ) {
					return '';
				}
				foreach ( $checkout_fields as $field_typ => $fieldset ) {
					foreach ( $fieldset as $field_key => $field_vl ) {
						$pos = strpos( $field_key, '_' );
						if ( 0 === $pos ) {
							$field_key = substr( $field_key, 1, strlen( $field_key ) );
						}
						if ( $field_key === $field ) {
							return $field_vl['label'];
						}
					}
				}
			}

			return '';
		}


		public static function get_fragments( $wfacp_id ) {

			if ( isset( $_REQUEST['post_data'] ) ) {
				$post_data = [];
				$temp_data = filter_input( INPUT_POST, 'post_data', FILTER_UNSAFE_RAW );
				parse_str( $temp_data, $post_data );
				self::$post_data = $post_data;
				if ( isset( $post_data['wfacp_exchange_keys'] ) ) {
					$exchange_keys       = urldecode( $post_data['wfacp_exchange_keys'] );
					self::$exchange_keys = json_decode( $exchange_keys, true );
				}
			}

			do_action( 'wfacp_get_fragments', $wfacp_id, $_REQUEST );

			// Get order review fragment
			ob_start();
			woocommerce_order_review();
			$woocommerce_order_review = ob_get_clean();

			return apply_filters( 'woocommerce_update_order_review_fragments', array(
				'.woocommerce-checkout-review-order-table' => $woocommerce_order_review,
			) );
		}

		public static function wfob_order_bump_fragments() {

			if ( isset( $_REQUEST['wfacp_id'] ) ) {
				$wfacp_id = absint( $_REQUEST['wfacp_id'] );
				self::initializeTemplate( $wfacp_id );
			}
		}

		public static function initializeTemplate( $wfacp_id ) {
			self::initTemplateLoader( $wfacp_id );
			do_action( 'wfacp_intialize_template_by_ajax', $wfacp_id );
		}

		/**
		 * Get the product row subtotal.
		 *
		 * Gets the tax etc to avoid rounding issues.
		 *
		 * When on the checkout (review order), this will get the subtotal based on the customer's tax rate rather than the base rate.
		 *
		 * @param WC_Product $product Product object.
		 * @param int $quantity Quantity being purchased.
		 *
		 * @return string formatted price
		 */
		public static function get_product_subtotal( $product, $cart_item, $row = false, $strike_through = false ) {
			if ( $product->is_taxable() ) {

				if ( WC()->cart->display_prices_including_tax() ) {
					$row_price        = round( $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'], wc_get_price_decimals() );
					$product_subtotal = wc_price( $row_price );
					if ( ! wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
						$product_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
					}
				} else {
					$row_price        = round( $cart_item['line_subtotal'], wc_get_price_decimals() );
					$product_subtotal = wc_price( $row_price );
					if ( wc_prices_include_tax() && WC()->cart->get_subtotal_tax() > 0 ) {
						$product_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
					}
				}
			} else {
				$row_price        = $cart_item['line_subtotal'];
				$product_subtotal = wc_price( $row_price );
			}

			if ( $strike_through == true ) {
				$quantity              = $cart_item['quantity'];
				$product_regular_price = $product->get_regular_price();
				$product_regular_price *= $quantity;
				$subtotal              = $row_price;

				if ( $product_regular_price > 0 && ( round( $subtotal, 2 ) !== round( $product_regular_price, 2 ) ) ) {
					if ( $subtotal > $product_regular_price ) {

						$product_subtotal = wc_price( $subtotal );
					} else {

						$product_subtotal = wc_format_sale_price( $product_regular_price, $subtotal );
					}
				} else {

					$product_subtotal = wc_price( $subtotal );
				}

				return $product_subtotal;

			}

			if ( true == $row ) {
				return $row_price;
			}

			return apply_filters( 'woocommerce_cart_product_subtotal', $product_subtotal, $product, $cart_item['quantity'], WC()->cart );

		}

		public static function remove_menu_support( $component ) {

			$i = array_search( 'nav_menus', $component );
			if ( is_numeric( $i ) ) {
				unset( $component[ $i ] );
			}

			return $component;
		}

		public static function get_base_country( $key = 'billing_country', $base = '' ) {

			$allowed_countries = WC()->countries->get_allowed_countries();
			if ( 'shipping_country' == $key ) {
				$woocommerce_ship_to_countries = get_option( 'woocommerce_ship_to_countries' );
				if ( 'disabled' !== $woocommerce_ship_to_countries ) {
					$allowed_countries = WC()->countries->get_shipping_countries();
				}
			}

			if ( is_array( $allowed_countries ) && count( $allowed_countries ) == 1 ) {
				$country = array_keys( $allowed_countries );

				return apply_filters( 'wfacp_default_' . $key, $country[0], 'single' );
			}

			$found_country   = '';
			$wc_default      = wc_get_base_location();
			$default_country = ( isset( $wc_default['country'] ) && '' !== $wc_default['country'] ) ? trim( $wc_default['country'] ) : '';

			if ( in_array( $base, [ 'geolocation', 'geolocation_ajax' ], true ) ) {
				$found_country = $default_country;
				if ( class_exists( 'WC_Geolocation' ) ) {
					$ip_data = self::get_geo_ip();
					if ( is_array( $ip_data ) && isset( $ip_data['country'] ) && '' !== $ip_data['country'] ) {
						$country       = trim( $ip_data['country'] );
						$found_country = isset( $allowed_countries[ $country ] ) ? $country : $found_country;
					}
				}
			} else if ( $base == 'base' ) {
				// Shop Base Address
				$found_country = $default_country;
			}

			return apply_filters( 'wfacp_default_' . $key, $found_country, $base );
		}

		/**
		 *
		 * @param $pro WC_Subscriptions_Product
		 * @param $price_data []
		 */
		public static function get_subscription_price( $pro, $price_data ) {

			$trial_length = WC_Subscriptions_Product::get_trial_length( $pro );
			$signup_fee   = WC_Subscriptions_Product::get_sign_up_fee( $pro );
			// Product now in free trial and with signup fee


			$display_type = get_option( 'woocommerce_tax_display_cart' );
			if ( 'incl' == $display_type && $signup_fee > 0 ) {
				$signup_fee = self::get_price_sign_up_fee( $pro, 'inc_tax' );
			}


			if ( $trial_length > 0 && $signup_fee > 0 ) {
				return $signup_fee * $price_data['quantity'];
			}
			if ( $trial_length > 0 && $signup_fee == 0 ) {
				return 0;
			} elseif ( $trial_length == 0 && $signup_fee > 0 ) {
				return $price_data['price'] + ( $signup_fee * $price_data['quantity'] );
			}

			return $price_data['price'];
		}


		/**
		 * Display proper subscription price
		 *
		 * @param $_product WC_Product
		 * @param $cart_item WC_Cart
		 * @param $cart_item_key
		 *
		 * @return string
		 */

		public static function display_subscription_price( $_product, $cart_item, $cart_item_key ) {
			if ( ! wp_doing_ajax() && $cart_item['quantity'] > 1 ) {
				$price = $_product->get_price();
				$price = $price / $cart_item['quantity'];
				if ( $price > 0 ) {
					$_product->set_price( $price );
				}
			}

			return apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
		}

		public static function get_signup_fee( $price ) {
			if ( empty( $price ) ) {
				return $price;
			}
			if ( is_string( $price ) ) {
				$price = strval( $price );
			}
			global $wfacp_product_switcher_quantity;
			if ( ! empty( $price ) && ! is_null( $wfacp_product_switcher_quantity ) && $wfacp_product_switcher_quantity > 0 ) {
				$price *= $wfacp_product_switcher_quantity;
			}

			return $price;
		}


		/**
		 * @param $pro WC_Product_Subscription
		 * @param $product_data
		 * @param $cart_item
		 * @param $cart_item_key
		 *
		 * @return string
		 */

		public static function subscription_product_string( $pro, $product_data, $cart_item, $cart_item_key ) {
			$temp_price = floatval( $pro->get_price() );

			if(isset($product_data['quantity'] )){
				$qty=$product_data['quantity'];
			}else{
				$qty=$cart_item['quantity'];
			}


			$temp_price *= ( isset( $qty ) && $qty > 0 ) ? absint( $qty ) : 1;
			$temp_data  = [
				'price' => wc_price( $temp_price ),
			];
			global $wfacp_product_switcher_quantity;
			if ( '' !== $cart_item_key && ! isset( WC()->cart->removed_cart_contents[ $cart_item_key ] ) ) {
				$wfacp_product_switcher_quantity = $cart_item['quantity'];
			} else {
		    	if(isset($product_data['org_quantity'])){
					$wfacp_product_switcher_quantity = $qty * $product_data['org_quantity'];
				}else{
					$wfacp_product_switcher_quantity = $qty;
				}

			}
			add_filter( 'woocommerce_subscriptions_product_sign_up_fee', 'WFACP_Common::get_signup_fee' );
			$final_price = WC_Subscriptions_Product::get_price_string( $pro, $temp_data );
			remove_filter( 'woocommerce_subscriptions_product_sign_up_fee', 'WFACP_Common::get_signup_fee' );
			unset( $wfacp_product_switcher_quantity );

			return $final_price;
		}

		/**
		 * Get coupon display total.
		 *
		 * @param string|WC_Coupon $coupon Coupon data or code.
		 */
		public static function wc_cart_totals_coupon_total( $coupon ) {
			if ( is_string( $coupon ) ) {
				$coupon = new WC_Coupon( $coupon );
			}
			$amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
			$discount_amount_html = wc_price( $amount );

			if ( $coupon->get_free_shipping() && empty( $amount ) ) {
				$discount_amount_html = __( 'Free shipping coupon', 'woocommerce' );
			}

			return $discount_amount_html;
		}

		/**
		 * Get a coupon label.
		 *
		 * @param string|WC_Coupon $coupon Coupon data or code.
		 * @param bool $echo Echo or return.
		 *
		 * @return string
		 */
		public static function wc_cart_totals_coupon_label( $coupon, $echo = false ) {
			if ( is_string( $coupon ) ) {
				$coupon = new WC_Coupon( $coupon );
			}
			$label = $coupon->get_code();
			if ( $echo ) {
				echo $label; // WPCS: XSS ok.
			} else {
				return $label;
			}
		}

		public static function get_default_global_page_builder() {
			$default_builder = BWF_Admin_General_Settings::get_instance()->get_option( 'default_selected_builder' );
			if ( 'wp_editor' === $default_builder ) {
				$default_builder = 'embed_forms';
			}
			if ( 'customizer' === $default_builder ) {
				$default_builder = 'pre_built';
			}

			return ( ! empty( $default_builder ) ) ? $default_builder : 'elementor';
		}

		public static function get_default_template_based_on_builder( $template_type ) {
			$templates       = WFACP_Core()->template_loader->get_templates();
			$default_builder = 'elementor_1';
			if ( is_array( $templates ) && count( $templates ) > 0 ) {
				reset( $templates[ $template_type ] );
				$default_builder = key( $templates[ $template_type ] );
			}

			return $default_builder;
		}

		public static function get_page_design( $page_id, $is_admin = false ) {
			$design_data     = self::get_post_meta_data( $page_id, '_wfacp_selected_design', $is_admin );
			$default_builder = self::get_default_global_page_builder();
			if ( is_array( $design_data ) && isset( $design_data['selected_type'] ) && empty( $design_data['selected_type'] ) ) {
				$design_data['selected_type']   = $default_builder;
				$design_data['selected']        = self::get_default_template_based_on_builder( $default_builder );
				$design_data['template_active'] = 'no';
				update_post_meta( $page_id, '_wfacp_selected_design', $design_data );
			}

			if ( empty( $design_data ) || ! is_array( $design_data ) ) {

				if ( $is_admin ) {
					$design_data = array(
						'selected_type'   => $default_builder,
						'selected'        => self::get_default_template_based_on_builder( $default_builder ),
						'template_active' => 'no'
					);
				} else {
					$design_data = self::default_design_data();
				}
			} else {
				if ( 'elementor' === $design_data['selected_type'] && ! class_exists( '\Elementor\Plugin' ) ) {
					$design_data = self::default_design_data();
				}

				if ( 'oxy' === $design_data['selected_type'] && ! defined( 'CT_VERSION' ) ) {
					$design_data = self::default_design_data();
				}
			}

			return $design_data;
		}

		public static function get_option( $field, $all = false ) {

			if ( true == $all ) {
				$defaults   = self::$customizer_fields_default;
				$saved_data = get_option( self::$customizer_key_prefix, [] );
				if ( null == $defaults ) {
					$defaults = [];
				}
				if ( is_bool( $saved_data ) ) {
					$saved_data = [];
				}

				return array_merge( $saved_data, $defaults );

			}

			if ( empty( $field ) ) {
				return '';
			}

			/** If data not fetched once */
			if ( empty( self::$customizer_key_data ) ) {
				self::$customizer_key_data = get_option( self::$customizer_key_prefix );
			}

			/** Field found in customizer get option */
			if ( isset( $field ) ) {

				if ( is_array( self::$customizer_key_data ) && isset( self::$customizer_key_data[ $field ] ) ) {
					$value = self::$customizer_key_data[ $field ];
					$value = self::maybe_convert_html_tag( $value );

					return $value;
				}
			}

			/** Field found in customizer fields default */
			if ( is_array( self::$customizer_fields_default ) && isset( self::$customizer_fields_default[ $field ] ) ) {
				$value = self::$customizer_fields_default[ $field ];
				$value = self::maybe_convert_html_tag( $value );

				return $value;
			}

			return '';
		}


		public static function update_page_settings( $page_id, $data ) {
			if ( $page_id < 1 ) {
				return $data;
			}

			if ( ! is_array( $data ) ) {
				$data = [];
			}

			$data['update_time'] = time();
			$data['user_id']     = get_current_user_id();
			update_post_meta( $page_id, '_wfacp_page_settings', $data );

			return $data;
		}

		public static function get_fragments_attr() {
			return 'data-time="' . time() . '"';
		}

		public static function initiate_track_and_analytics() {
			include __DIR__ . '/class-track-analytics.php';
		}

		/**
		 * Modify permalink
		 *
		 * @param string $post_link post link.
		 * @param array $post post data.
		 * @param string $leavename leave name.
		 *
		 * @return string
		 */
		public static function post_type_permalinks( $post_link, $post, $leavename ) {

			$bwb_admin_setting = BWF_Admin_General_Settings::get_instance();

			if ( isset( $post->post_type ) && self::get_post_type_slug() === $post->post_type && empty( trim( $bwb_admin_setting->get_option( 'checkout_page_base' ) ) ) ) {


				// If elementor page preview, return post link as it is.
				if ( isset( $_REQUEST['elementor-preview'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					return $post_link;
				}

				$structure = get_option( 'permalink_structure' );

				if ( in_array( $structure, self::get_supported_permalink_structures_to_normalize(), true ) ) {

					$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
					$post_link = str_replace( '/' . self::get_url_rewrite_slug() . '/', '/', $post_link );

				}

			}

			return $post_link;
		}

		public static function get_supported_permalink_structures_to_normalize() {
			return array( '/%postname%/' );
		}

		/**
		 * Have WordPress match postname to any of our public post types.
		 * All of our public post types can have /post-name/ as the slug, so they need to be unique across all posts.
		 * By default, WordPress only accounts for posts and pages where the slug is /post-name/.
		 *
		 * @param WP_Query $query query statement.
		 */
		public static function add_cpt_post_names_to_main_query( $query ) {

			// Bail if this is not the main query.
			if ( ! $query->is_main_query() ) {
				return;
			}


			// Bail if this query doesn't match our very specific rewrite rule.
			if ( ! isset( $query->query['page'] ) ) {
				return;
			}

			// Bail if we're not querying based on the post name.
			if ( empty( $query->query['name'] ) ) {
				return;
			}
			// If query does not match (not exactly 2 parameters or 3 with 'lang'), return early.
			if ( ! ( count( $query->query ) === 2 || ( count( $query->query ) === 3 && isset( $query->query['lang'] ) ) ) ) {
				return;
			}
			// Add landing page step post type to existing post type array.
			if ( isset( $query->query_vars['post_type'] ) && is_array( $query->query_vars['post_type'] ) ) {

				$post_types = $query->query_vars['post_type'];

				$post_types[] = self::get_post_type_slug();

				$query->set( 'post_type', $post_types );

			} else {

				// Add CPT to the list of post types WP will include when it queries based on the post name.
				$query->set( 'post_type', array( 'post', 'page', self::get_post_type_slug() ) );
			}
		}

		/**
		 * Find cart key using product item key
		 *
		 * @param $product_key
		 *
		 * @return array|null
		 */
		public static function get_cart_item_key( $product_key, $from_removed_cart = false ) {
			$cart = WC()->cart->get_cart_contents();
			if ( count( $cart ) > 0 ) {

				foreach ( $cart as $item_key => $item_data ) {
					if ( isset( $item_data['_wfacp_product_key'] ) && $product_key == $item_data['_wfacp_product_key'] ) {

						return [ $item_key, $item_data ];
					}
				}
			}
			$cart = WC()->cart->removed_cart_contents;
			if ( count( $cart ) > 0 && $from_removed_cart ) {
				foreach ( $cart as $item_key => $item_data ) {
					if ( isset( $item_data['_wfacp_product_key'] ) && $product_key == $item_data['_wfacp_product_key'] ) {

						return [ $item_key, $item_data ];
					}
				}
			}

			return null;
		}

		final public static function unset_gateways( $gateways ) {
			if ( WFACP_Common::is_theme_builder() ) {
				foreach ( $gateways as $key => $gateway ) {
					if ( 'WC_Gateway_COD' != $gateway ) {
						unset( $gateways[ $key ] );
					}
				}
			}

			return $gateways;
		}

		/**
		 * @return array
		 */
		final public static function ajax_extra_frontend_data() {
			if ( is_null( WC()->cart ) ) {
				return [];
			}
			$data                    = [];
			$data['cart_is_empty']   = WC()->cart->is_empty();
			$data['cart_total']      = WC()->cart->get_total( 'edit' );
			$data['cart_is_virtual'] = WFACP_Common::is_cart_is_virtual();
			if ( class_exists( 'WC_Subscriptions_Cart' ) && method_exists( 'WC_Subscriptions_Cart', 'cart_contains_subscription' ) ) {
				$data['cart_contains_subscription'] = WC_Subscriptions_Cart::cart_contains_subscription();
			}

			return $data;
		}

		final public static function copy_meta( $old_post_id, $new_post_id ) {


			$exclude_data = [
				'_wfacp_selected_products',
				'_wfacp_selected_products_settings',
				'_wfacp_selected_design',
				'_wfacp_page_layout',
				'_wfacp_page_settings',
				'_wfacp_page_custom_field',
				'_wfacp_fieldsets_data',
				'_wfacp_checkout_fields',
				'_wfacp_product_switcher_setting',
				'_wfacp_save_address_order',
				'_post_description',
				'_wp_page_template',
			];


			$exclude_meta_keys_to_copy = apply_filters( 'wfacp_do_not_duplicate_meta', $exclude_data );

			global $wpdb;
			$post_meta_all = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$old_post_id" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

			if ( ! empty( $post_meta_all ) ) {
				$sql_query_selects = [];

				foreach ( $post_meta_all as $meta_info ) {

					$meta_key = $meta_info->meta_key;

					if ( in_array( $meta_key, $exclude_meta_keys_to_copy, true ) ) {
						continue;
					}

					$meta_key            = esc_sql( $meta_key );
					$meta_value          = esc_sql( $meta_info->meta_value );
					$sql_query_selects[] = "( '$new_post_id', '$meta_key', '$meta_value')"; //db call ok; no-cache ok; WPCS: unprepared SQL ok.

				}

				$sql_query_meta_val = implode( ',', $sql_query_selects );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO %1$s (post_id, meta_key, meta_value) VALUES ' . $sql_query_meta_val, $wpdb->postmeta ) );//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,WordPress.DB.PreparedSQL.NotPrepared

			}
		}

		/**
		 *
		 * @return bool
		 */
		public static function is_frontend_request() {
			return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && function_exists( 'WC' ) && ! WC()->is_rest_api_request();
		}

		/**
		 * this function only run when Order created via Google Pay or Apple Pay button
		 *
		 * @param $order_id
		 * @param $posted_data
		 * @param $order WC_Order
		 *
		 * @return void
		 */
		public static function update_aero_field( $order_id, $posted_data, $order ) {

			if ( ! $order instanceof WC_Order ) {
				return;
			}
			$wfacp_id             = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
			$payment_request_type = filter_input( INPUT_POST, 'payment_request_type', FILTER_UNSAFE_RAW );

			if ( ! is_null( $wfacp_id ) && ! is_null( $payment_request_type ) && ( 'payment_request_api' == $payment_request_type || 'apple_pay' == $payment_request_type || 'google_pay' == $payment_request_type ) ) {
				$order->update_meta_data( '_wfacp_post_id', $wfacp_id );

				$override = filter_input( INPUT_GET, 'wfacp_is_checkout_override', FILTER_UNSAFE_RAW );
				if ( ! is_null( $override ) ) {
					if ( 'yes' == $override ) {
						$link = wc_get_checkout_url();
					} else {
						$link = get_the_permalink( $wfacp_id );
					}
					if ( ! empty( $link ) ) {
						$order->update_meta_data( '_wfacp_source', $link );
					}
				}
				$order->save_meta_data();
			}
		}


		public static function wfacp_order_total( $atts ) {

			$atts = shortcode_atts( array(
				'with_html' => 'no',
			), $atts );

			if ( is_null( WC()->cart ) ) {
				return '';
			}


			if ( 'yes' == $atts['with_html'] ) {
				$cart_total = WC()->cart->get_total();
			} else {
				$cart_total = strip_tags( WC()->cart->get_total() );
			}


			return $cart_total;
		}

		/**
		 * Create facebook advanced matching data
		 * @return mixed|null
		 */
		public static function pixel_advanced_matching_data() {
			$args = array();

			if ( ! class_exists( 'BWF_Admin_General_Settings' ) ) {
				return $args;
			}

			$advanced_tracking = BWF_Admin_General_Settings::get_instance()->get_option( 'is_fb_advanced_event' );

			if ( ! is_array( $advanced_tracking ) || count( $advanced_tracking ) === 0 || 'yes' !== $advanced_tracking[0] ) {
				return $args;
			}

			$params = self::advanced_matching_data();

			if ( ! is_array( $params ) || 0 === count( $params ) ) {
				return $args;
			}

			foreach ( $params as $key => &$value ) {
				if ( ! empty( $value ) ) {
					$params[ $key ] = WFACP_Common::sanitize_advanced_matching_param( $value, $key );
				}
			}

			return $params;
		}

		/**
		 * Create tiktok advanced matching data
		 * @return mixed|null
		 */
		public static function tiktok_advanced_matching_data() {
			$args = array();

			$params = self::advanced_matching_data();

			if ( ! is_array( $params ) || 0 === count( $params ) ) {
				return $args;
			}

			if ( isset( $params["em"] ) && $params["em"] != "" ) {
				$args['sha256_email'] = hash( 'sha256', $params["em"] );
			}
			if ( isset( $params["ph"] ) && $params["ph"] != "" ) {
				$args['sha256_phone_number'] = hash( 'sha256', $params['ph'] );
			}

			return $args;
		}

		public static function advanced_matching_data() {
			$params = array();

			$user = wp_get_current_user();

			if ( ! empty( $user ) && $user->ID !== 0 ) {
				// get user regular data
				$params['fn']          = $user->get( 'user_firstname' );
				$params['ln']          = $user->get( 'user_lastname' );
				$params['em']          = $user->get( 'user_email' );
				$params['ph']          = get_user_meta( $user->ID, 'user_phone', true );
				$params['external_id'] = $user->ID;
			}

			/**
			 * Add common WooCommerce Advanced Matching params
			 */

			if ( class_exists( 'woocommerce' ) ) {

				if ( ! empty( $user ) && $user->ID !== 0 ) {
					// if first name is not set in regular wp user meta
					if ( empty( $params['fn'] ) ) {
						$params['fn'] = $user->get( 'billing_first_name' );
					}

					// if last name is not set in regular wp user meta
					if ( empty( $params['ln'] ) ) {
						$params['ln'] = $user->get( 'billing_last_name' );
					}

					$params['ph'] = $user->get( 'billing_phone' );
					$params['ct'] = $user->get( 'billing_city' );
					$params['st'] = $user->get( 'billing_state' );

					$params['country'] = $user->get( 'billing_country' );
				}

			}

			if ( empty( $params['external_id'] ) && ! empty( $_COOKIE['wffn_flt'] ) ) {
				$params['external_id'] = bwf_clean( $_COOKIE['wffn_flt'] );
			}
			$params = apply_filters( 'wfacp_advanced_matching_data', $params );

			if ( ! is_array( $params ) || count( $params ) === 0 ) {
				return array();
			}

			return $params;
		}

		public static function sanitize_advanced_matching_param( $value, $key ) {
			$value = strtolower( $value );
			if ( $key == 'ph' ) {
				$value = preg_replace( '/\D/', '', $value );
			} elseif ( $key == 'em' ) {
				$value = preg_replace( '/[^a-z0-9._+-@]+/i', '', $value );
			} else {
				// only letters with unicode support
				$value = preg_replace( '/[^\w\p{L}]/u', '', $value );
			}

			return $value;

		}

		public static function generate_transient_key() {
			return md5( bwf_generate_random_bytes( 32 ) );
		}

		public static function do_wc_ajax() {
			global $wp_query;
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( ! empty( $_GET['wc-ajax'] ) && ! empty( $_GET['wfacp_id'] ) ) {
				$wp_query->set( 'wc-ajax', sanitize_text_field( wp_unslash( $_GET['wc-ajax'] ) ) );
				$wp_query->set( 'wfacp_id', sanitize_text_field( wp_unslash( $_GET['wfacp_id'] ) ) );
			}

			$action   = $wp_query->get( 'wc-ajax' );
			$wfacp_id = $wp_query->get( 'wfacp_id' );
			if ( $action == 'update_order_review' || $action == 'get_refreshed_fragments' ) {
				return;
			}
			if ( $action && absint( $wfacp_id ) > 0 ) {
				self::initTemplateLoader( $wfacp_id );
			}
		}

		public static function wfacp_round( $value, $precision = 2 ) {
			if ( ! is_numeric( $value ) ) {
				$value = floatval( $value );
			}

			return round( $value, $precision );
		}

		public static function get_aero_registered_checkout_fields() {
			$fields = [
				'billing_email',
				'billing_first_name',
				'billing_last_name',
				'billing_company',
				'billing_address_1',
				'billing_address_2',
				'billing_city',
				'billing_postcode',
				'billing_country',
				'billing_state',
				'billing_phone',
				'billing_same_as_shipping',
				'shipping_email',
				'shipping_first_name',
				'shipping_last_name',
				'shipping_company',
				'shipping_address_1',
				'shipping_address_2',
				'shipping_city',
				'shipping_postcode',
				'shipping_country',
				'shipping_state',
				'shipping_phone',
				'shipping_same_as_billing',
				'shipping_calculator',
				'order_comments',
				'order_summary',
				'order_coupon',
				'order_total',
				'product_switching',
				'billing_wc_custom_field',
				'shipping_wc_custom_field',
				'account_username',
				'account_password',
				'wc_advanced_order_field',
				'shipping_method',
			];

			return apply_filters( 'wfacp_aero_registered_checkout_fields', $fields );

		}

		/**
		 * @return void
		 *  Register AeroCheckout custom Fields for billing
		 */
		public static function setup_fields_billing() {


			new WFACP_Add_Address_Field( 'wc_custom_field', array(
				'type'        => 'wfacp_html',
				'label'       => 'Extra Billing Fields',
				'placeholder' => 'Extra Billing Fields',
				'cssready'    => [ 'wfacp-col-left-third' ],
				'class'       => array( 'form-row-third first', 'wfacp-col-full' ),
				'required'    => false,
				'priority'    => 60,
			) );
			new WFACP_Add_Address_Field( 'wc_custom_field', array(
				'type'        => 'wfacp_html',
				'label'       => 'Extra Shipping Fields',
				'placeholder' => 'Extra Shipping Fields',
				'cssready'    => [ 'wfacp-col-left-third' ],
				'class'       => array( 'form-row-third first', 'wfacp-col-full' ),
				'required'    => false,
				'priority'    => 60,
			), 'shipping' );


		}

		public static function include_third_party_field( $wfacp_id ) {

			$fields = WFACP_Common::get_checkout_fields( $wfacp_id );
			if ( isset( $fields['advanced']['wc_advanced_order_field'] ) || isset( $fields['billing']['billing_wc_custom_field'] ) || isset( $fields['shipping']['shipping_wc_custom_field'] ) ) {
				include_once WFACP_PLUGIN_DIR . '/includes/class-register-third-party-fields.php';
				WFACP_Class_Register_Third_Party_Fields::get_instance( $fields );
			}


		}

		/**
		 * Insert Third Party field in checkout form during the template import.
		 *
		 * @param $data
		 *
		 * @return array
		 */
		public static function add_third_party_fields_to_checkout_form( $data ) {
			$steps = [ 'third_step', 'two_step', 'single_step' ];
			foreach ( $steps as $step ) {
				if ( isset( $data['page_layout']['fieldsets'][ $step ] ) ) {
					$data['page_layout']['fieldsets'][ $step ] = self::map_third_party_field_into_last_section_form( $data['page_layout']['fieldsets'][ $step ] );
					break;
				}
			}

			return $data;
		}

		public static function map_third_party_field_into_last_section_form( $sections ) {
			$last_section = end( $sections );
			if ( false === $last_section ) {
				return $sections;
			}
			$adavanced_fields = self::get_advanced_fields();
			if ( ! isset( $adavanced_fields['wc_advanced_order_field'] ) ) {
				return $sections;
			}
			$last_section_index                          = array_key_last( $sections );
			$sections[ $last_section_index ]['fields'][] = $adavanced_fields['wc_advanced_order_field'];

			return $sections;
		}

		public static function oxy_get_meta_prefix( $key ) {
			if ( function_exists( 'oxy_get_meta_prefix' ) ) {
				$key = oxy_get_meta_prefix( $key );
			}

			return $key;
		}

		public static function display_save_price( $price_message ) {
			// Early return if cart is not available
			if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
				return;
			}

			try {
				$cart_contents = WC()->cart->get_cart_contents();
				if ( empty( $cart_contents ) ) {
					return;
				}

				$regular_price = 0;

				foreach ( $cart_contents as $content ) {
					// Validate cart item data
					if ( ! isset( $content['data'] ) || ! isset( $content['quantity'] ) ) {
						continue;
					}

					$product = $content['data'];
					if ( ! ( $product instanceof WC_Product ) ) {
						continue;
					}

					$quantity   = absint( $content['quantity'] );
					$product_id = $product->get_id();

					// Get fresh instance of the product
					$product = wc_get_product( $product_id );
					if ( ! $product ) {
						continue;
					}

					$item_regular_price = $product->get_regular_price();
					if ( '' === $item_regular_price || ! is_numeric( $item_regular_price ) ) {
						continue;
					}

					$regular_price += $quantity * floatval( $item_regular_price );
				}

				// Prevent division by zero
				if ( $regular_price <= 0 ) {
					return;
				}

				$total = WC()->cart->get_cart_contents_total();

				// Only proceed if there's actually a saving
				if ( $regular_price <= $total ) {
					return;
				}

				$saving_price      = $regular_price - $total;
				$saving_percentage = ( $saving_price * 100 ) / $regular_price;

				// Format the saving price
				$formatted_saving_price = str_replace( '-', '', wc_price( $saving_price ) );

				// Replace placeholders in message
				$total_message = str_replace( '{{saving_amount}}', $formatted_saving_price, $price_message );

				if ( $saving_percentage > 0 ) {
					$total_message = str_replace( '{{saving_percentage}}', number_format( $saving_percentage, 0 ) . '%', $total_message );
				}

				if ( empty( $total_message ) ) {
					return;
				}

				// Output the message
				?>
                <tr class="wfacp-saving-amount">
                    <td colspan="2">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M7.77778 8.2669C8.08461 8.2669 8.33334 8.01817 8.33334 7.71135C8.33334 7.40452 8.08461 7.15579 7.77778 7.15579C7.47096 7.15579 7.22223 7.40452 7.22223 7.71135C7.22223 8.01817 7.47096 8.2669 7.77778 8.2669Z" fill="currentColor"/>
                        <path d="M12.2222 12.7113C12.5291 12.7113 12.7778 12.4626 12.7778 12.1558C12.7778 11.849 12.5291 11.6002 12.2222 11.6002C11.9154 11.6002 11.6667 11.849 11.6667 12.1558C11.6667 12.4626 11.9154 12.7113 12.2222 12.7113Z" fill="currentColor"/>
                        <path d="M17.3889 11.6175C17.8889 11.2257 18.2222 10.6101 18.3333 9.99451C18.2778 9.32293 17.8889 8.76328 17.3889 8.37152C17.1667 8.20363 17 7.9238 16.8333 7.69994C16.7778 7.36415 16.8333 7.02836 16.8889 6.74853C17.0556 6.13291 17 5.46133 16.7222 4.90168C16.2778 4.45396 15.6667 4.17413 15.0556 4.17413C14.7778 4.17413 14.5 4.0622 14.2222 3.95027C14 3.72641 13.8889 3.44659 13.7778 3.16676C13.5 2.60711 13.2222 1.93553 12.6111 1.71167C12 1.59974 11.3333 1.76763 10.8889 2.15939C10.6111 2.32728 10.3333 2.43921 10.0556 2.49518C9.77778 2.43921 9.5 2.32728 9.22223 2.15939C8.72223 1.87956 8.11111 1.54377 7.5 1.71167C6.88889 1.87956 6.5 2.55114 6.22223 3.1108C6.11112 3.39062 5.94445 3.67045 5.77778 3.89431C5.5 4.00624 5.22223 4.11817 4.94445 4.11817C4.33334 4.11817 3.72223 4.39799 3.27778 4.84572C3 5.40537 2.94445 6.07695 3.11112 6.69257C3.16667 7.02836 3.22223 7.30818 3.16667 7.64397C3 7.9238 2.83334 8.14766 2.61112 8.31556C2.11112 8.76328 1.72223 9.32293 1.66667 9.99451C1.72223 10.6661 2.11112 11.2257 2.61112 11.6175C2.83334 11.7854 3 12.0652 3.16667 12.2891C3.22223 12.6249 3.16667 12.9607 3.11112 13.2405C2.94445 13.8561 3 14.5277 3.27778 15.0873C3.72223 15.5351 4.33334 15.8149 4.94445 15.8149C5.22223 15.8149 5.5 15.9268 5.77778 16.0387C6 16.2626 6.11112 16.5424 6.22223 16.8223C6.5 17.3819 6.77778 18.0535 7.38889 18.2774C7.5 18.3333 7.66667 18.3333 7.77778 18.3333C8.27778 18.2774 8.72223 18.1095 9.11111 17.8296C9.38889 17.6617 9.66667 17.5498 9.94445 17.4938C10.2222 17.5498 10.5 17.6617 10.7778 17.8296C11.2778 18.2214 11.8889 18.3893 12.5 18.2774C13.1111 18.0535 13.4444 17.4379 13.6667 16.8223C13.7778 16.5424 13.9444 16.2626 14.1111 16.0387C14.3889 15.9268 14.6667 15.8149 14.9444 15.8149C15.5556 15.8149 16.1667 15.5351 16.6111 15.0873C16.8889 14.5277 16.9444 13.8561 16.7778 13.2405C16.7222 12.9047 16.6667 12.6249 16.7222 12.2891C17 12.0652 17.1667 11.8414 17.3889 11.6175ZM10.5556 12.2331C10.5556 11.2817 11.2778 10.5542 12.2222 10.5542C13.1667 10.5542 13.8889 11.2817 13.8889 12.2331C13.8889 13.1845 13.1667 13.9121 12.2222 13.9121C11.2778 13.9121 10.5556 13.1845 10.5556 12.2331ZM13.7222 7.02836L7.05556 13.7442C6.94445 13.8561 6.83334 13.9121 6.66667 13.9121C6.5 13.9121 6.38889 13.8561 6.27778 13.7442C6.05556 13.5203 6.05556 13.1845 6.27778 12.9607L12.9444 6.24484C13.1667 6.02098 13.5556 6.07695 13.7222 6.30081C13.8889 6.52467 13.8889 6.8045 13.7222 7.02836ZM9.44445 7.7559C9.44445 8.70731 8.72223 9.43486 7.77778 9.43486C6.83334 9.43486 6.11112 8.70731 6.11112 7.7559C6.11112 6.8045 6.83334 6.07695 7.77778 6.07695C8.72223 6.07695 9.44445 6.8045 9.44445 7.7559Z" fill="currentColor"/>
                    </svg>
                    <?php echo wp_kses_post( $total_message ); ?>
                </span>
                    </td>
                </tr>
				<?php
			} catch ( Exception $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					error_log( 'Error in display_save_price: ' . $e->getMessage() );
				}

				return;
			}

		}

		/*
		 * Clear side wide add to cart data on checkout after fire event
		 * Handle case for themes not use wc ajax fro add to cart
		 * @return void
		 */
		public static function clear_pending_events_data_from_session() {
			try {
				if ( function_exists( 'WC' ) && ! is_null( WC()->session ) && WC()->session->has_session() ) {
					$events = WC()->session->get( 'wffn_pending_data' );
					if ( ! is_null( $events ) && is_array( $events ) && count( $events ) > 0 ) {
						WC()->session->set( 'wffn_pending_data', '' );
					}
				}
			} catch ( Exception|Error $e ) {

			}
		}


		/**
		 * Translates strings based on the current language setting
		 * Optimized for both direct string lookups and content with multiple strings
		 *
		 * @param mixed $content The content to translate (string)
		 *
		 * @return string Translated content
		 */
		public static function translation_string_to_check( $content ) {
			try {
				// Static cache to avoid processing the same string multiple times
				static $cache = [];
				if ( isset( $cache[ $content ] ) ) {
					return $cache[ $content ];
				}

				// Early return if content is empty
				if ( empty( $content ) ) {
					return $content;
				}

				// Make sure content is a string
				if ( ! is_string( $content ) ) {
					// Convert to string if possible
					if ( is_array( $content ) || is_object( $content ) ) {
						$content = json_encode( $content );
					} else {
						$content = (string) $content;
					}
				}

				if ( ! function_exists( 'wfacp_get_translation' ) ) {
					return $content;
				}

				// Get translations for current locale - use static to only retrieve once
				static $translations = null;
				static $current_locale = null;
				static $sorted_keys = null;

				if ( $translations === null ) {
					$translations   = wfacp_get_translation();
					$current_locale = get_locale();

					if ( ! empty( $translations[ $current_locale ] ) && is_array( $translations[ $current_locale ] ) ) {
						$sorted_keys = array_keys( $translations[ $current_locale ] );
						usort( $sorted_keys, function ( $a, $b ) {
							return strlen( $b ) - strlen( $a ); // Longer strings first
						} );
					}
				}

				// Return original content if no translations exist for current locale
				if ( empty( $translations[ $current_locale ] ) || ! is_array( $translations[ $current_locale ] ) ) {
					$cache[ $content ] = $content;

					return $content;
				}

				// Fast path: Direct lookup for single strings
				if ( isset( $translations[ $current_locale ][ $content ] ) ) {
					$cache[ $content ] = $translations[ $current_locale ][ $content ];

					return $translations[ $current_locale ][ $content ];
				}

				// For content with multiple strings, replace each matching string
				$result = $content;
				foreach ( $sorted_keys as $original ) {
					// Skip if original string is not contained in content
					if ( stripos( $result, $original ) === false ) {
						continue;
					}

					$translated = $translations[ $current_locale ][ $original ];

					// Handle different string types
					if ( preg_match( '/[.!?,;:]$/', $original ) || strlen( $original ) > 40 ) {
						// For strings ending with punctuation or very long strings
						$pattern = '/' . preg_quote( $original, '/' ) . '/ui'; // Added u flag for Unicode support
					} else {
						// For normal strings, use word boundaries
						$pattern = '/\b' . preg_quote( $original, '/' ) . '\b/ui';
					}

					// Debug output (remove in production)
					// echo $pattern . " " . $translated . " => $original <br>";

					$result = preg_replace( $pattern, $translated, $result );
				}

				$cache[ $content ] = $result;

				return $result;

			} catch ( Exception $e ) {
				// Log the error if you have logging enabled
				// error_log('Translation error: ' . $e->getMessage());
				return $content;
			}
		}

		public static function get_notice_html_in_editor( $builder = 'elementor' ) {

			$temp_style = [
				'background-color: #F9F9FF',
				'padding: 16px 12px 16px 12px',
				'border-left: 4px solid #82838E',
				'line-height: 1.5',
				'margin-left: 0',
				'margin-right:0'

			];


			$style = implode( ';', $temp_style );

			$slug      = 'funnel-checkout';
			$id        = WFACP_Common::get_id();
			$funnel_id = get_post_meta( $id, '_bwf_in_funnel', true );
			$step_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
				'page'      => 'bwf',
				'path'      => "/" . $slug . "/" . $id . "/design",
				'funnel_id' => $funnel_id,
			], admin_url( 'admin.php' ) ) );

			$notice_html = '<div style="' . $style . '">
        <strong style="font-weight: 500;font-size: 13px;line-height: 20px;display: inline-block;margin-bottom: 8px;color:#353030;">
            <svg style="vertical-align: middle;margin-right: 4px;" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.77708 1.35938C12.4595 1.35938 15.4448 4.34461 15.4448 8.02708C15.4448 11.7095 12.4595 14.6948 8.77708 14.6948C5.09461 14.6948 2.10938 11.7095 2.10938 8.02708C2.10938 4.34461 5.09461 1.35938 8.77708 1.35938ZM8.77708 2.35938C5.64689 2.35938 3.10938 4.89689 3.10938 8.02708C3.10938 11.1573 5.64689 13.6948 8.77708 13.6948C11.9073 13.6948 14.4448 11.1573 14.4448 8.02708C14.4448 4.89689 11.9073 2.35938 8.77708 2.35938ZM8.77465 7.02643C9.02778 7.02626 9.2371 7.21423 9.27036 7.45826L9.27497 7.5261L9.27737 11.1938C9.27755 11.47 9.05384 11.694 8.7777 11.6942C8.52457 11.6943 8.31525 11.5064 8.28198 11.2623L8.27737 11.1945L8.27497 7.52676C8.27479 7.25061 8.4985 7.02661 8.77465 7.02643ZM8.77737 4.69459C9.14507 4.69459 9.44315 4.99267 9.44315 5.36036C9.44315 5.72806 9.14507 6.02614 8.77737 6.02614C8.40968 6.02614 8.1116 5.72806 8.1116 5.36036C8.1116 4.99267 8.40968 4.69459 8.77737 4.69459Z" fill="#353030"/>
            </svg>
            Checkout Field Editor
        </strong>
        <p style="margin: 0 0 12px;font-size: 13px;line-height: 20px;display: inline-block;color:#353030;font-weight: 400;">Add new fields, edit existing fields or reorder the fields on this checkout.</p>
        <a href="' . $step_link . '" target="_blank" style="text-decoration:none;padding: 5px 12px 5px 12px;color: #0073AA;border-radius: 8px;border: 1px solid #0073AA;display: inline-block;font-weight: 500;font-size: 11px;line-height: 18px;">
            <svg style="vertical-align: middle;margin-right: 4px;" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_25839_113016)">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.11068 4.52344C3.88966 4.52344 3.6777 4.61123 3.52142 4.76752C3.36514 4.9238 3.27734 5.13576 3.27734 5.35677V12.6901C3.27734 12.9111 3.36514 13.1231 3.52142 13.2794C3.6777 13.4356 3.88966 13.5234 4.11068 13.5234H11.444C11.665 13.5234 11.877 13.4356 12.0333 13.2794C12.1895 13.1231 12.2773 12.9111 12.2773 12.6901V8.6901C12.2773 8.41396 12.5012 8.1901 12.7773 8.1901C13.0535 8.1901 13.2773 8.41396 13.2773 8.6901V12.6901C13.2773 13.1763 13.0842 13.6427 12.7404 13.9865C12.3966 14.3303 11.9302 14.5234 11.444 14.5234H4.11068C3.62445 14.5234 3.15813 14.3303 2.81431 13.9865C2.4705 13.6427 2.27734 13.1763 2.27734 12.6901V5.35677C2.27734 4.87054 2.4705 4.40423 2.81431 4.06041C3.15813 3.71659 3.62445 3.52344 4.11068 3.52344H8.11068C8.38682 3.52344 8.61068 3.7473 8.61068 4.02344C8.61068 4.29958 8.38682 4.52344 8.11068 4.52344H4.11068Z" fill="#0073AA"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2773 2.02344C10.2773 1.7473 10.5012 1.52344 10.7773 1.52344H14.7773C15.0535 1.52344 15.2773 1.7473 15.2773 2.02344V6.02344C15.2773 6.29958 15.0535 6.52344 14.7773 6.52344C14.5012 6.52344 14.2773 6.29958 14.2773 6.02344V2.52344H10.7773C10.5012 2.52344 10.2773 2.29958 10.2773 2.02344Z" fill="#0073AA"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.1322 1.66988C15.3275 1.86515 15.3275 2.18173 15.1322 2.37699L7.79887 9.71032C7.6036 9.90559 7.28702 9.90559 7.09176 9.71032C6.8965 9.51506 6.8965 9.19848 7.09176 9.00322L14.4251 1.66988C14.6204 1.47462 14.9369 1.47462 15.1322 1.66988Z" fill="#0073AA"/>
                </g>
                <defs>
                    <clipPath id="clip0_25839_113016">
                        <rect width="16" height="16" fill="white" transform="translate(0.777344 0.0234375)"/>
                    </clipPath>
                </defs>
            </svg>
            Open Field Editor
        </a>
    </div>';

			return $notice_html;

		}

	}
}