<?php
if ( ! class_exists( 'WFOP_Divi_Extension' ) ) {
	#[AllowDynamicProperties]
	class WFOP_Divi_Extension extends DiviExtension {

		/**
		 * The gettext domain for the extension's translations.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $gettext_domain = 'wffn-woofunnels-op-divi';
		public static $field_color_type = 'color';

		/**
		 * The extension's WP Plugin name.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $name = 'woofunnels-op-divi';

		/**
		 * The extension's version
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $version = '1.0.0';

		private $module_path = '';

		public $modules_instance = [];
		private $builder_setup_done = false;

		/**
		 * WFOP_Divi_Extension constructor.
		 *
		 * @param string $name
		 * @param array $args
		 */
		public function __construct( $name = 'woofunnels-op-divi', $args = array() ) {
			$this->plugin_dir     = plugin_dir_path( __FILE__ );
			$this->module_path    = $this->plugin_dir . 'modules/';
			$this->plugin_dir_url = plugin_dir_url( __FILE__ );
			parent::__construct( $name, $args );
			add_filter( 'et_theme_builder_template_layouts', [ $this, 'disable_header_footer' ], 99 );

		}

		protected function _enqueue_bundles() {
			$this->enqueue_module_js();

		}

		public function wp_hook_enqueue_scripts() {
			parent::wp_hook_enqueue_scripts();
			wp_dequeue_style( 'woofunnels-op-divi-styles' );
		}

		private function enqueue_module_js() {
			// Frontend Bundle
			if ( ! WFOPP_Core()->optin_pages->is_wfop_page() ) {
				return;
			}
			wp_enqueue_script( 'flickity', WFFN_PLUGIN_URL . '/assets/flickity/flickity.pkgd.js', [], true );
			wp_enqueue_style( "{$this->name}-wfop-divi", "{$this->plugin_dir_url}css/divi.css", [], $this->version );
			if ( et_core_is_fb_enabled() ) {
				wp_enqueue_script( "{$this->name}-builder-bundle", "{$this->plugin_dir_url}scripts/loader.min.js", [ 'react-dom' ], $this->version, true );
			}
			do_action( 'wfop_divi_module_js' );

		}


		private function get_modules() {
			$modules = [
				'optin_form' => [
					'name' => __( 'WF Optin Form', 'funnel-builder' ),
					'path' => $this->module_path . 'optin-form.php',
				]
			];

			return apply_filters( 'wffn_op_divi_modules', $modules, $this );
		}

		// This function run upto divi builder 4.9.*
		public function hook_et_builder_modules_loaded() {
			$this->setup_builder_module();
		}

		/**
		 * THis function run From Divi 4.10.0
		 */
		public function hook_et_builder_ready() {
			$this->setup_builder_module();
		}

		public function setup_builder_module() {
			if ( true === $this->builder_setup_done ) {
				return;
			}
			$modules = $this->get_modules();

			$response = WFFN_Common::check_builder_status( 'divi' );
			if ( isset( $response['version'] ) && version_compare( $response['version'], '4.10.0', '>' ) ) {
				self::$field_color_type = 'color-alpha';
			}

			if ( ! empty( $modules ) ) {
				include_once __DIR__ . '/class-abstract-wfop-divi-fields.php';
				include_once __DIR__ . '/class-wfop-divi-html-block.php';
				foreach ( $modules as $key => $module ) {
					if ( ! file_exists( $module['path'] ) ) {
						continue;
					}
					$this->modules_instance[ $key ] = include_once $module['path'];
					$this->modules_instance[ $key ]->set_name( $module['name'] );

					remove_action( 'et_builder_modules_loaded', array( $this, 'hook_et_builder_modules_loaded' ) );
					remove_action( 'et_builder_ready', array( $this, 'hook_et_builder_ready' ), 9 );
					$this->builder_setup_done = true;
				}
			}

		}

		public function disable_header_footer( $layouts ) {
			global $post;
			if ( ! isset( $_GET['et_fb'] ) || ! defined( 'ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE' ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return $layouts;
			}


			if ( is_null( $post ) || $post->post_type !== WFOPP_Core()->optin_pages->get_post_type_slug() ) {
				return $layouts;
			}
			$my_template = get_post_meta( $post->ID, '_wp_page_template', true );

			if ( ( 'wfop-boxed.php' === $my_template || 'wfop-canvas.php' === $my_template ) && isset( $layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ] ) ) {
				$layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['id']       = 0;
				$layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['enabled']  = false;
				$layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['override'] = false;
				$layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['id']       = 0;
				$layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['enabled']  = false;
				$layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['override'] = false;
			}

			return $layouts;
		}

	}

	new WFOP_Divi_Extension;
}