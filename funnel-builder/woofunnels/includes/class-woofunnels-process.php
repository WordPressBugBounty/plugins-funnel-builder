<?php
if ( ! class_exists( 'WooFunnels_Process' ) ) {
	/**
	 * Basic process class that detect request and pass to respective class
	 *
	 * @author woofunnels
	 * @package WooFunnels
	 */
	#[AllowDynamicProperties]
	class WooFunnels_Process {

		private static $ins = null;
		public $in_update_messages = array();

		/**
		 * Initiate hooks
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'parse_request_and_process' ), 14 );
			add_filter( 'admin_notices', array( $this, 'maybe_show_advanced_update_notification' ), 999 );

			add_action( 'admin_head', array( $this, 'register_in_update_plugin_message' ) );


			add_action( 'fk_fb_every_day', array( 'WooFunnels_License_Controller', 'license_check' ) );
			add_action( 'wp_ajax_nopriv_fk_init_license_request', array( 'WooFunnels_License_Controller', 'license_check_api_call_init' ) );
			add_action( 'funnelkit_license_update', array( $this, 'maybe_clear_plugin_update_transients' ) );
			add_action( 'funnelkit_delete_transients', array( $this, 'maybe_clear_plugin_update_transients' ) );
			add_action( 'woocommerce_thankyou', array( $this, 'fire_thankyou_ajax' ), 10 );
			add_action( 'wp_ajax_bwf_thankyou_ajax', array( $this, 'handle_thankyou_ajax' ), 10 );
			add_action( 'wp_ajax_nopriv_bwf_thankyou_ajax', array( $this, 'handle_thankyou_ajax' ), 10 );
			add_action( 'admin_init', array( $this, 'maybe_set_options_auto_loading_false' ) );

			add_action( 'admin_head', array( $this, 'maybe_swap_order_to_make_it_correct' ), - 2 );
			add_action( 'admin_head', array( $this, 'maybe_correct_submenu_order' ), - 1 );

			add_action( 'admin_head', array( $this, 'correct_sub_menu_order' ), 999 );
			add_action( 'admin_head', array( $this, 'correct_sub_menu_order_legacy' ), 999 );

			add_action( 'admin_init', array( $this, 'hide_plugins_update_notices' ) );
			add_action( 'admin_footer', array( $this, 'print_css' ), 9999 );
		}

		public static function get_instance() {
			if ( self::$ins === null ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function parse_request_and_process() {
			//Initiating the license instance to handle submissions  (submission can redirect page two that can cause "header already sent" issue to be arised)
			// Initiating this to over come that issue
			if ( 'woofunnels' === filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW ) && 'licenses' === filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW ) ) {
				WooFunnels_licenses::get_instance();
			}

			//Handling Optin
			if ( isset( $_GET['woofunnels-optin-choice'] ) && isset( $_GET['_woofunnels_optin_nonce'] ) ) {
				if ( ! wp_verify_nonce( sanitize_text_field( $_GET['_woofunnels_optin_nonce'] ), 'woofunnels_optin_nonce' ) ) {
					wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'woofunnels' ) ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
				}

				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( esc_html__( 'Cheating huh?', 'woofunnels' ) ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
				}

				$optin_choice = sanitize_text_field( $_GET['woofunnels-optin-choice'] );
				if ( $optin_choice === 'yes' ) {
					WooFunnels_optIn_Manager::Allow_optin();
					if ( isset( $_GET['ref'] ) ) {
						WooFunnels_optIn_Manager::update_optIn_referer( filter_input( INPUT_GET, 'ref', FILTER_UNSAFE_RAW ) );
					}
				} else {
					WooFunnels_optIn_Manager::block_optin();
				}

				do_action( 'woofunnels_after_optin_choice', $optin_choice );
			}

			//Initiating the license instance to handle submissions  (submission can redirect page two that can cause "header already sent" issue to be arised)
			// Initiating this to over come that issue
			if ( isset( $_GET['page'] ) && 'woofunnels' === sanitize_text_field( $_GET['page'] ) && isset( $_GET['tab'] ) && 'support' === sanitize_text_field( $_GET['tab'] ) && isset( $_POST['woofunnels_submit_support'] ) ) {
				$instance_support = WooFunnels_Support::get_instance();

				if ( filter_input( INPUT_POST, 'choose_addon', true ) === '' || filter_input( INPUT_POST, 'comments', true ) === '' ) {
					$instance_support->validation = false;
				}
			}
		}

		public function maybe_show_advanced_update_notification() {
			$screen            = get_current_screen();
			$plugins_installed = WooFunnels_Addons::get_installed_plugins();
			$hide_notice       = get_option( 'woofunnel_hide_update_notice', 'no' );

			if ( 'yes' !== $hide_notice && is_object( $screen ) && 'index.php' === $screen->parent_file ) {
				$plugins = get_site_transient( 'update_plugins' );
				if ( isset( $plugins->response ) && is_array( $plugins->response ) ) {
					$plugins      = array_keys( $plugins->response );
					$plugin_names = [];
					foreach ( $plugins_installed as $basename => $installed ) {
						if ( is_array( $plugins ) && count( $plugins ) > 0 && in_array( $basename, $plugins, true ) ) {
							$plugin_names[] = $installed['Name'];
						}
					}

					if ( count( $plugin_names ) > 0 ) {
						?>
                        <div class="woofunnel-notice-message notice notice-warning">
                            <a class="woofunnel-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'woofunnel-update-notice', 'hide' ), 'woofunnel_update_notice_nonce', '_woofunnel_update_notice_nonce' ) ); ?>">
								<?php esc_html_e( 'Dismiss', 'woofunnels' );  // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch ?>
                            </a>
                            <p>
								<?php
								_e( sprintf( 'Attention: There is an update available of <strong>%s</strong> plugin. &nbsp;<a href="%s" class="">Go to updates</a>', implode( ', ', $plugin_names ), admin_url( 'plugins.php?s=funnelkit&plugin_status=all' ) ), 'woofunnels' ); // phpcs:ignore WordPress.Security.EscapeOutput, WordPress.WP.I18n.TextDomainMismatch, WordPress.WP.I18n.MissingTranslatorsComment, WordPress.WP.I18n.NonSingularStringLiteralText
								?>
                            </p>
                        </div>
                        <style>
                            div.woofunnel-notice-message {
                                overflow: hidden;
                                position: relative;
                            }

                            .woofunnel-notice-message a.woofunnel-message-close {
                                position: static;
                                float: right;
                                padding: 0px 15px 5px 28px;
                                margin-top: -10px;
                                line-height: 14px;
                                text-decoration: none;
                            }

                            .woofunnel-notice-message a.woofunnel-message-close:before {
                                position: relative;
                                top: 18px;
                                left: -20px;
                                transition: all .1s ease-in-out;
                            }
                        </style>
						<?php
					}
				}
			}
		}

		/**
		 * Set option for hide woofunnel plugin update notice
		 */
		public static function hide_plugins_update_notices() {

			if ( isset( $_GET['woofunnel-update-notice'] ) && isset( $_GET['_woofunnel_update_notice_nonce'] ) ) {
				if ( ! wp_verify_nonce( sanitize_text_field( $_GET['_woofunnel_update_notice_nonce'] ), 'woofunnel_update_notice_nonce' ) ) {
					wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'woofunnels' ) ); // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
				}
				update_option( 'woofunnel_hide_update_notice', 'yes' );
				wp_safe_redirect( admin_url( 'index.php' ) );
				exit;
			}
		}

		public function register_in_update_plugin_message() {
			$get_in_update_message_support = apply_filters( 'woofunnels_in_update_message_support', array() );

			if ( empty( $get_in_update_message_support ) ) {
				return;
			}
			$this->in_update_messages = $get_in_update_message_support;
			$get_basenames            = array_keys( $get_in_update_message_support );
			foreach ( $get_basenames as $basename ) {
				add_action( 'in_plugin_update_message-' . $basename, array( $this, 'in_plugin_update_message' ), 10, 2 );

			}
		}

		/**
		 * Show plugin changes on the plugins screen. Code adapted from W3 Total Cache.
		 *
		 * @param array $args Unused parameter.
		 * @param stdClass $response Plugin update response.
		 */
		public function in_plugin_update_message( $args, $response ) {
			$changelog_path  = $this->in_update_messages[ $args['plugin'] ];
			$current_version = $args['Version'];
			$upgrade_notice  = $this->get_upgrade_notice( $response->new_version, $changelog_path, $current_version );

			echo apply_filters( 'woofunnels_in_plugin_update_message', $upgrade_notice ? '</br>' . wp_kses_post( $upgrade_notice ) : '', $args['plugin'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			echo '<style>span.woofunnels_plugin_upgrade_notice::before {
    content: ' . '"\f463";
    margin-right: 6px;
    vertical-align: bottom;
    color: #f56e28;
    display: inline-block;
    font: 400 20px/1 dashicons;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    vertical-align: top;
}</style>';
		}

		/**
		 * Get the upgrade notice from WordPress.org.
		 *
		 * @param string $version WooCommerce new version.
		 *
		 * @return string
		 */
		protected function get_upgrade_notice( $version, $path, $current_version ) {
			$transient_name = 'woofunnels_upgrade_notice_' . $version . md5( $path );
			$upgrade_notice = get_transient( $transient_name );

			if ( false === $upgrade_notice ) {
				$response = wp_safe_remote_get( $path );
				if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
					$upgrade_notice = $this->parse_update_notice( $response['body'], $version, $current_version );
					set_transient( $transient_name, $upgrade_notice, DAY_IN_SECONDS );
				}
			}

			return $upgrade_notice;
		}

		/**
		 * Parse update notice from readme file.
		 *
		 * @param string $content WooCommerce readme file content.
		 * @param string $new_version WooCommerce new version.
		 *
		 * @return string
		 */
		private function parse_update_notice( $content, $new_version, $current_version ) {
			$version_parts     = explode( '.', $new_version );
			$check_for_notices = array(
				$version_parts[0] . '.0', // Major.
				$version_parts[0] . '.0.0', // Major.
				$version_parts[0] . '.' . $version_parts[1], // Minor.
			);

			$notice_regexp  = '~==\s*Upgrade Notice\s*==\s*=\s*(.*)\s*=(.*)(=\s*' . preg_quote( $new_version ) . '\s*=|$)~Uis';
			$upgrade_notice = '';

			foreach ( $check_for_notices as $check_version ) {
				if ( version_compare( $current_version, $check_version, '>' ) ) {
					continue;
				}

				$matches = null;
				if ( preg_match( $notice_regexp, $content, $matches ) ) {

					$notices = (array) preg_split( '~[\r\n]+~', trim( $matches[2] ) );

					if ( version_compare( trim( $matches[1] ), $check_version, '=' ) ) {
						$upgrade_notice .= '<span class="woofunnels_plugin_upgrade_notice">';

						foreach ( $notices as $line ) {
							$upgrade_notice .= preg_replace( '~\[([^\]]*)\]\(([^\)]*)\)~', '<a href="${2}">${1}</a>', $line );
						}

						$upgrade_notice .= '</span>';
					}
					break;
				}
			}

			return wp_kses_post( $upgrade_notice );
		}


		public function fire_thankyou_ajax( $order_id ) {
			$action             = 'bwf_thankyou_ajax';
			$nonce              = wp_create_nonce( 'bwf_thankyou_ajax' );
			$ajaxurl            = admin_url( 'admin-ajax.php' );
			$bwfUrlAjaxThankYou = $ajaxurl . '?action=' . $action . '&nonce=' . $nonce . '&order_id=' . $order_id;
			?>
            <script>
                document.addEventListener("DOMContentLoaded", function (event) {
                    var xhr = new XMLHttpRequest();
                    var bwfUrlAjaxThankYou = '<?php echo $bwfUrlAjaxThankYou; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
                    xhr.open("POST", bwfUrlAjaxThankYou, true);
                    //Send the proper header information along with the request
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send();
                });

            </script>
			<?php
		}

		public function handle_thankyou_ajax() {
			check_ajax_referer( 'bwf_thankyou_ajax', 'nonce' );

			$get_order_id = filter_input( INPUT_GET, 'order_id', FILTER_SANITIZE_NUMBER_INT );
			if ( empty( $get_order_id ) ) {
				return;
			}

			/**
			 * Fires a generic WC thankyou hook
			 */
			do_action( 'woofunnels_woocommerce_thankyou', $get_order_id );
			wp_send_json( array( 'success' => true ) );
		}

		/**
		 * @hooked over 'admin_init'
		 * Mark the customizer data to not autoload on WP load as its only needed on specific pages.
		 */
		public function maybe_set_options_auto_loading_false() {

			$should_run_query = get_option( '_bwf_upgrade_1_9_13', 'no' );

			if ( 'yes' === $should_run_query ) {
				return;
			}

			global $wpdb;
			/**
			 * Update session table with the data
			 */
			$query = $wpdb->prepare( "UPDATE `" . $wpdb->prefix . "options` SET `autoload` = %s WHERE (`option_name` LIKE '%wfocu_c_%' OR `option_name` LIKE '%wfacp_c_%') AND `autoload` LIKE 'yes' AND `option_name` NOT LIKE 'wfacp_css_migrated'", 'no' );
			$wpdb->query( $query );  //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			update_option( '_bwf_upgrade_1_9_13', 'yes' );
		}

		public function maybe_swap_order_to_make_it_correct() {
			global $menu, $submenu;
			if ( ! isset( $submenu['woofunnels'] ) ) {
				return;
			}

			$get_parent_position   = $this->get_parent_position( $menu );
			$get_autonami_position = $this->get_autonami_position( $menu );

			if ( false === $get_parent_position ) {
				return;
			}

			if ( ! isset( $menu[ $get_autonami_position ] ) || ! is_array( $menu[ $get_autonami_position ] ) || 'autonami' !== $menu[ $get_autonami_position ][2] ) {
				return;
			}
			$this->array_swap( $menu, $get_parent_position, $get_autonami_position );
		}

		/**
		 * @hooked over admin_head::-1
		 * Handles menu order for woofunnels submenu as it gets registered by the different plugin on different priorities
		 */
		public function maybe_correct_submenu_order() {
			global $submenu, $menu, $woofunnels_menu_slug;
			if ( ! isset( $submenu['woofunnels'] ) ) {
				return;
			}
			$woofunnels_menu_slug = 'woofunnels';

			$get_all_submenu = $submenu['woofunnels'];

			/**
			 * get the top slug/submenu to play as parent menu
			 */
			$get_slug = $this->get_top_slug( $get_all_submenu );

			if ( empty( $get_slug ) ) {
				return;
			}
			/**
			 * get 'woofunnels' parent menu position so that we can alter it
			 */
			$get_parent_position = $this->get_parent_position( $menu );
			if ( false === $get_parent_position ) {
				return;
			}

			/**
			 * Assign found submenu in place of woofunnels to be shown as first menu in the series
			 */
			$menu[ $get_parent_position ][2] = $get_slug;


			/**
			 * Unset woofunnels we do not need it
			 */
			unset( $submenu['woofunnels'] );

			/**
			 * get the menu order sorted by placing license at the bottom
			 */
			$get_all_submenu = $this->get_current_order( $get_all_submenu );

			/**
			 * Manage menu URL
			 */
			$get_all_submenu = array_map( function ( $val ) {
				$val[2] = 'admin.php?page=' . $val[2];

				return $val;
			}, $get_all_submenu );

			/**
			 * Place correct submenu in the global
			 */
			$submenu[ $get_slug ] = $get_all_submenu;
			$woofunnels_menu_slug = $get_slug;

			/**
			 * manage highlighting the menus
			 */ global $parent_file, $plugin_page, $submenu_file, $current_page;
			if ( true === $this->is_our_submenu( $plugin_page, $get_all_submenu ) ) :
				$parent_file  = $get_slug;//phpcs:ignore
				$submenu_file = 'admin.php?page=' . $plugin_page;//phpcs:ignore
			endif;

		}

		public function get_top_slug( $submenu ) {
			if ( isset( $submenu[1][2] ) ) {
				return $submenu[1][2];
			}

			return '';
		}

		function array_swap( &$array, $swap_a, $swap_b ) {
			list( $array[ $swap_a ], $array[ $swap_b ] ) = array( $array[ $swap_b ], $array[ $swap_a ] );
		}

		public function get_parent_position( $menus ) {
			$found = false;

			foreach ( $menus as $key => $menu ) {
				if ( 'woofunnels' === $menu[2] ) {
					$found = $key;
					break;
				}
			}

			return $found;

		}

		public function get_autonami_position( $menus ) {
			$found = false;

			foreach ( $menus as $key => $menu ) {
				if ( 'autonami' === $menu[2] ) {
					$found = $key;
					break;
				}
			}

			return $found;

		}

		public function get_current_order( $get_all_submenu ) {
			$get_license_config = $get_all_submenu[0];

			array_shift( $get_all_submenu );
			$get_all_submenu[ count( $get_all_submenu ) ] = $get_license_config;

			return $get_all_submenu;
		}

		public function is_our_submenu( $plugin_page, $get_all_submenu ) {
			$found = false;
			foreach ( $get_all_submenu as $menu ) {
				if ( 'admin.php?page=' . $plugin_page === $menu[2] ) {
					$found = true;
					break;
				}
			}

			return $found;
		}

		public function correct_sub_menu_order() {
			global $submenu, $menu;


			/**
			 * change the title of the woofunnels to the new menu
			 */
			foreach ( $menu as &$men ) {
				if ( isset( $men[5] ) && $men[5] === 'toplevel_page_woofunnels' ) {

					$men[0] = 'FunnelKit';
					$men[3] = 'FunnelKit';
				}
			}


			if ( ! isset( $submenu['bwf'] ) ) {
				return;
			}

			$new_sub_menu = [];

			$any_external     = false;
			$max_count        = 90;
			$additional_break = false;

			foreach ( $submenu['bwf'] as $key => $sub_item ) {
				if ( ! current_user_can( $sub_item[1] ) ) {
					continue;
				}
				if ( "admin.php?page=woofunnels" === $sub_item[2] ) {

					continue;
				}

				switch ( $sub_item[2] ) {

					case "admin.php?page=bwf":
						$sub_item[4]     = '';
						$new_sub_menu[0] = $sub_item;
						break;
					case "admin.php?page=bwf&path=/funnels":
						$new_sub_menu[10] = $sub_item;
						break;
					case "admin.php?page=bwf&path=/store-checkout":
						$new_sub_menu[11]    = $sub_item;
						$new_sub_menu[11][4] = 'bwf_store_checkout';

						break;
					case "admin.php?page=bwf&path=/analytics":
						$new_sub_menu[12] = $sub_item;
						break;
					case "admin.php?page=bwf&path=/templates":
						$new_sub_menu[13] = $sub_item;
						break;
					case "admin.php?page=bwf_ab_tests":
						$new_sub_menu[20] = $sub_item;
						$any_external     = true;
						break;
					case "admin.php?page=bwfcrm-contacts":
						$sub_item[4]      = 'bwf_admin_menu_b_top';
						$new_sub_menu[70] = $sub_item;
						$any_external     = true;
						break;
					case "admin.php?page=autonami":
						$new_sub_menu[80] = $sub_item;
						$any_external     = true;
						break;
					case "admin.php?page=bwf-campaigns":
						$new_sub_menu[90] = $sub_item;
						$any_external     = true;
						break;

					case "admin.php?page=wfacp":
						$any_external     = true;
						$new_sub_menu[40] = $sub_item;
						break;
					case "admin.php?page=wfch":
						$any_external     = true;
						$new_sub_menu[45] = $sub_item;
						break;
					case "admin.php?page=wfob":
						$any_external     = true;
						$new_sub_menu[50] = $sub_item;
						break;
					case "admin.php?page=upstroke":
						$any_external     = true;
						$new_sub_menu[55] = $sub_item;
						break;
					default:
						if ( false === $additional_break ) {
							$additional_break = $max_count + 1;

						}
						$new_sub_menu[ $max_count + 1 ] = $sub_item;
						$max_count ++;
						break;

				}
			}
			if ( ! empty( $new_sub_menu ) && count( $new_sub_menu ) > 0 ) {
				/** Assigning class above native plugins */
				if ( isset( $new_sub_menu[40] ) ) {
					$new_sub_menu[40][4] = 'bwf_admin_menu_b_top';
				} elseif ( isset( $new_sub_menu[45] ) ) {
					$new_sub_menu[45][4] = 'bwf_admin_menu_b_top';
				} elseif ( isset( $new_sub_menu[50] ) ) {
					$new_sub_menu[50][4] = 'bwf_admin_menu_b_top';
				} elseif ( isset( $new_sub_menu[55] ) ) {
					$new_sub_menu[55][4] = 'bwf_admin_menu_b_top';
				}


				ksort( $new_sub_menu );
				$submenu['bwf'] = $new_sub_menu;

				ob_start();
				?>
                <style>
                    #adminmenu li.bwf_admin_menu_b_top {
                        border-top: 1px dashed #65686b;
                        padding-top: 5px;
                        margin-top: 5px
                    }

                    #adminmenu li.bwf_admin_menu_b_bottom {
                        border-bottom: 1px dashed #65686b;
                        padding-bottom: 5px;
                        margin-bottom: 5px
                    }
                </style>
				<?php
				echo ob_get_clean(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		public function correct_sub_menu_order_legacy() {
			global $submenu;
			if ( ! isset( $submenu['bwf_dashboard'] ) ) {
				return;
			}

			$new_sub_menu = [];

			$section       = filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW );
			$aero_settings = filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW );
			$aero_page     = filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW );

			foreach ( $submenu['bwf_dashboard'] as $key => $sub_item ) {
				if ( ! current_user_can( $sub_item[1] ) ) {
					continue;
				}
				switch ( $sub_item[2] ) {
					case "admin.php?page=bwf_dashboard":
						$sub_item[4]     = 'bwf_admin_menu_b_bottom';
						$new_sub_menu[0] = $sub_item;
						break;
					case "admin.php?page=bwf_funnels":
						$new_sub_menu[10] = $sub_item;
						break;
					case "admin.php?page=bwf_ab_tests":
						$new_sub_menu[20] = $sub_item;
						break;
					case "admin.php?page=bwfcrm-contacts":
						$sub_item[4]      = 'bwf_admin_menu_b_top';
						$new_sub_menu[70] = $sub_item;
						break;
					case "admin.php?page=autonami":
						$new_sub_menu[80] = $sub_item;
						break;
					case "admin.php?page=bwf-campaigns":
						$new_sub_menu[90] = $sub_item;
						break;
					case "admin.php?page=woofunnels_settings":
						$sub_item[4] = 'bwf_admin_menu_b_top';
						if ( in_array( $section, [ 'bwf_settings', 'lp-settings', 'op-settings', 'ty-settings' ] ) ) {
							$sub_item[4] .= ' current';
						} elseif ( 'settings' === $aero_settings && 'wfacp' === $aero_page ) {
							$sub_item[4] .= ' current';
						}
						$new_sub_menu[140] = $sub_item;
						break;
					case "admin.php?page=woofunnels":
						$new_sub_menu[150] = $sub_item;
						break;
					case "admin.php?page=wfacp":
						$new_sub_menu[40] = $sub_item;
						break;
					case "admin.php?page=wfch":
						$new_sub_menu[45] = $sub_item;
						break;
					case "admin.php?page=wfob":
						$new_sub_menu[50] = $sub_item;
						break;
					case "admin.php?page=upstroke":
						$new_sub_menu[55] = $sub_item;
						break;

				}
			}

			if ( ! empty( $new_sub_menu ) && count( $new_sub_menu ) > 0 ) {
				/** Assigning class above native plugins */
				if ( isset( $new_sub_menu[40] ) ) {
					$new_sub_menu[40][4] = 'bwf_admin_menu_b_top';
				} elseif ( isset( $new_sub_menu[45] ) ) {
					$new_sub_menu[45][4] = 'bwf_admin_menu_b_top';
				} elseif ( isset( $new_sub_menu[50] ) ) {
					$new_sub_menu[50][4] = 'bwf_admin_menu_b_top';
				}

				ksort( $new_sub_menu );
				$submenu['bwf_dashboard'] = $new_sub_menu;

				ob_start();
				?>
                <style>
                    #adminmenu li.bwf_admin_menu_b_top {
                        border-top: 1px dashed #65686b;
                        padding-top: 5px;
                        margin-top: 5px
                    }

                    #adminmenu li.bwf_admin_menu_b_bottom {
                        border-bottom: 1px dashed #65686b;
                        padding-bottom: 5px;
                        margin-bottom: 5px
                    }
                </style>
				<?php
				echo ob_get_clean(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		public function maybe_clear_plugin_update_transients() {
			delete_transient( 'update_plugins' );
			delete_site_transient( 'update_plugins' );
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s", '%_bwf_version_cache_%' ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		}

		public function print_css() {
			?>
            <style>
                .wp-admin #adminmenu .toplevel_page_woofunnels .wp-menu-image:before {
                    content: none;
                }

                .wp-admin #adminmenu .toplevel_page_woofunnels .wp-not-current-submenu .wp-menu-image {
                    background-image: url("<?php echo esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/bwf-icon-grey.svg'); ?>") !important;
                }

                .wp-admin #adminmenu .toplevel_page_woofunnels .wp-has-current-submenu .wp-menu-image {
                    background-image: url("<?php echo esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/bwf-icon-white.svg'); ?>") !important;
                }

                .wp-admin #adminmenu .toplevel_page_woofunnels .wp-menu-image {
                    background-repeat: no-repeat;
                    position: relative;
                    top: 5px;
                    background-position: 50% 25%;
                    background-size: 60%;
                }
            </style> <?php
		}
	}
}