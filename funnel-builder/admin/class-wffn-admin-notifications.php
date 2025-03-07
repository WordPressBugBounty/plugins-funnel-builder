<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Admin_Notifications
 * Handles All the methods about admin notifications
 */
if ( ! class_exists( 'WFFN_Admin_Notifications' ) ) {
	#[AllowDynamicProperties]
	class WFFN_Admin_Notifications {

		/**
		 * @var WFFN_Admin_Notifications|null
		 */

		private static $ins = null;
		public $notifs = [];

		public function __construct() {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this, 'register_notices' ) );

			}
		}

		/**
		 * @return WFFN_Admin_Notifications|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function get_notifications() {
			$this->prepare_notifications();

			return $this->notifs;
		}

		public function get_black_friday_day_data( $day = 'bf' ) {
			// Get the current year
			$year = gmdate( 'Y' );
			// Create a DateTime object for November 30 of the current year
			$blackFriday = new DateTimeImmutable( "{$year}-11-30 00:00:00" );

			// Find the last Friday of November
			while ( $blackFriday->format( 'N' ) != 5 ) { //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				$blackFriday = $blackFriday->modify( '-1 day' );
			}

			// Initialize data variable to store the resulting date
			$data = '';

			switch ( $day ) {
				case 'pre':
					// Pre-Black Friday: 5 days before
					$data = $blackFriday->modify( '-5 days' )->format( 'M d' );
					break;
				case 'sbs':
					// Small Business Saturday: 1 day after
					$data = $blackFriday->modify( '+1 day' )->format( 'M d' );
					break;
				case 'bfext':
					// Black Friday Extended: 2 days after
					$data = $blackFriday->modify( '+2 days' )->format( 'M d' );
					break;
				case 'cm':
					// Cyber Monday: 3 days after
					$data = $blackFriday->modify( '+3 days' )->format( 'M d' );
					break;
				case 'cmext':
					// Cyber Monday Extended: 7 days after
					$data = $blackFriday->modify( '+7 days' )->format( 'M d' );
					break;
				default:
					// Black Friday itself
					$data = $blackFriday->format( 'M d' );
					break;
			}

			return $data;
		}

		public function show_pre_black_friday_header_notification() {
			// Get the difference in minutes between today and Black Friday
			$blackFridayDifference = $this->get_black_friday_day_diff();
			// Check if the difference falls within the range for showing the notification
			// (-11 days in minutes to -4 days in minutes)
			if ( $blackFridayDifference >= - ( 11 * 1440 ) && $blackFridayDifference < - ( 4 * 1440 ) ) {
				return true;
			} else {
				return false;
			}
		}

		public function show_black_friday_header_notification() {
			// Get the difference in minutes between today and Black Friday
			$blackFridayDifference = $this->get_black_friday_day_diff();

			// Check if the difference falls within the range for showing the notification
			// (-4 days in minutes to the day after Black Friday)
			if ( $blackFridayDifference >= - ( 4 * 1440 ) && $blackFridayDifference < 1440 ) {
				return true;
			} else {
				return false;
			}
		}

		public function show_small_business_saturday_header_notification() {
			// Get the difference in minutes between today and Black Friday
			$blackFridayDifference = $this->get_black_friday_day_diff();

			// Check if the difference falls within the range for showing the notification
			// (1 day to 2 days after Black Friday)
			if ( $blackFridayDifference >= 1440 && $blackFridayDifference < 2880 ) {
				return true;
			} else {
				return false;
			}
		}


		public function show_black_friday_extended_header_notification() {
			// Get the difference in minutes between today and Black Friday
			$blackFridayDifference = $this->get_black_friday_day_diff();

			// Check if the difference falls within the range for showing the notification
			// (2 days to 3 days after Black Friday)
			if ( $blackFridayDifference >= 2880 && $blackFridayDifference < 4320 ) {
				return true;
			} else {
				return false;
			}
		}

		public function show_cyber_monday_header_notification() {
			// Get the difference in minutes between today and Black Friday
			$blackFridayDifference = $this->get_black_friday_day_diff();

			// Check if the difference falls within the range for showing the notification
			// (3 days to 4 days after Black Friday)
			if ( $blackFridayDifference >= 4320 && $blackFridayDifference < 5760 ) {
				return true;
			} else {
				return false;
			}
		}

		public function show_extended_cyber_monday_header_notification() {
			// Get the difference in minutes between today and Black Friday
			$blackFridayDifference = $this->get_black_friday_day_diff();

			// Check if the difference falls within the range for showing the notification
			// (4 days to 8 days after Black Friday)
			if ( $blackFridayDifference >= 5760 && $blackFridayDifference < 11520 ) {
				return true;
			} else {
				return false;
			}
		}

		public function show_green_monday_header_notification() {
			// Get the difference in minutes between today and the second Monday of December
			$secondDecMondayDayDiff = $this->get_second_dec_monday_day_diff();

			// Check if the difference falls within the range for showing the notification
			// (0 to 1 day after the second Monday of December)
			if ( $secondDecMondayDayDiff >= 0 && $secondDecMondayDayDiff < 1440 ) {
				return true;
			} else {
				return false;
			}
		}


		public function get_black_friday_day_diff() {
			// Set the timezone to 'America/New_York'
			$timezone = new DateTimeZone( 'America/New_York' );
			// Create DateTime object for today's date and time in the specified timezone
			$today = new DateTime( 'now', $timezone );

			// Get the current year
			$year = $today->format( 'Y' );
			// Start from November 30 at midnight UTC and calculate Black Friday
			$blackFriday = new DateTime( "{$year}-11-30 00:00:00", new DateTimeZone( 'UTC' ) );

			// Find the last Friday of November
			while ( $blackFriday->format( 'N' ) != 5 ) { //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				$blackFriday = $blackFriday->modify( '-1 day' );
			}

			// Convert Black Friday date to 'America/New_York' timezone for accurate diff
			$blackFriday = $blackFriday->setTimezone( $timezone );

			// Calculate the difference in minutes between today and Black Friday
			$differenceInMinutes = $today->getTimestamp() - $blackFriday->getTimestamp();
			$differenceInMinutes = round( $differenceInMinutes / 60 );

			return $differenceInMinutes;
		}

		public function get_second_dec_monday_day_diff( $diff = true ) {
			// Set the timezone to 'America/New_York'
			$timezone = new DateTimeZone( 'America/New_York' );
			// Get today's date and time in the specified timezone
			$today = new DateTime( 'now', $timezone );

			// Get the current year
			$year = $today->format( 'Y' );
			// Create a DateTime object for November 30 at midnight UTC
			$lastNovDay = new DateTime( "{$year}-11-30 00:00:00", new DateTimeZone( 'UTC' ) );

			// Move to December 1
			$decFirstDay = $lastNovDay->modify( '+1 day' );
			// Get the day of the week (0 = Sunday, 1 = Monday, etc.)
			$dayOfWeek = $decFirstDay->format( 'w' );
			// Calculate days to add to reach the first Monday of December
			$daysToAdd = ( $dayOfWeek == 0 ) ? 1 : 8 - $dayOfWeek; //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			// Move to the first Monday of December
			$firstDecMonday = $decFirstDay->modify( "+{$daysToAdd} days" );
			// Move to the second Monday of December
			$secondDecMonday = $firstDecMonday->modify( '+7 days' );

			if ( $diff ) {
				// Calculate the difference in minutes between today and the second Monday of December
				$differenceInMinutes = round( ( $today->getTimestamp() - $secondDecMonday->getTimestamp() ) / 60 );

				return $differenceInMinutes;
			} else {
				// Return the formatted date of the second Monday of December
				return $secondDecMonday->format( 'M d' );
			}
		}

		private function get_notification_buttons( $campaign ) {
			return [
				[
					'label'     => __( "Get FunnelKit PRO", "funnel-builder" ),
					'href'      => add_query_arg( [
						'utm_source'   => 'WordPress',
						'utm_medium'   => 'Notice+FKFB',
						'utm_campaign' => $campaign
					], "https://funnelkit.com/exclusive-offer/" ),
					'className' => 'is-primary',
					'target'    => '__blank',
				],
				[
					'label'     => __( "Learn More", "funnel-builder" ),
					'href'      => add_query_arg( [
						'utm_source'   => 'WordPress',
						'utm_medium'   => 'Notice+FKFB',
						'utm_campaign' => $campaign
					], "https://funnelkit.com/wordpress-funnel-builder/" ),
					'className' => 'is-secondary',
					'target'    => '__blank',
				],
				[
					'label'  => __( "Dismiss", "funnel-builder" ),
					'action' => 'close_notice',
				]
			];
		}

		private function add_notification( $key, $content ) {
			$this->notifs[] = [
				'key'           => $key,
				'content'       => $content,
				'className'     => 'bwf-notif-bwfcm',
				'customButtons' => $this->get_notification_buttons( 'BFCM' . gmdate( 'Y' ) )
			];
		}

		public function prepare_notifications() {


			if ( ! defined( 'WFFN_PRO_VERSION' ) ) {
				$yearKey = 'promo_bf_' . gmdate( 'Y' );

				if ( $this->show_pre_black_friday_header_notification() ) {
					$this->add_notification( $yearKey, $this->promo_pre_bfcm() );
				} elseif ( $this->show_black_friday_header_notification() ) {
					$this->add_notification( $yearKey, $this->promo_bfcm() );
				} elseif ( $this->show_small_business_saturday_header_notification() ) {
					$this->add_notification( $yearKey, $this->promo_small_business_saturday() );
				} elseif ( $this->show_black_friday_extended_header_notification() ) {
					$this->add_notification( $yearKey, $this->promo_ext_bfcm() );
				} elseif ( $this->show_cyber_monday_header_notification() ) {
					$this->add_notification( $yearKey, $this->promo_cmonly() );
				} elseif ( $this->show_extended_cyber_monday_header_notification() ) {
					$this->add_notification( $yearKey, $this->promo_ext_cmonly() );
				}

				// Show Green Monday notification independently
				if ( $this->show_green_monday_header_notification() ) {
					$this->add_notification( $yearKey, $this->promo_gm() );
				}
			}


			if ( WFFN_Core()->admin->is_update_available() ) {
				$version        = WFFN_Core()->admin->is_update_available();
				$this->notifs[] = array(
					'key'           => 'fb_update_' . str_replace( '.', '_', $version ),
					'content'       => $this->update_available( $version ),
					'customButtons' => [
						[
							'label'     => __( 'Update Now', "funnel-builder" ),
							'href'      => admin_url( 'plugins.php?s=FunnelKit+Funnel+Builder' ),
							'className' => 'is-primary',
							'target'    => '__blank',
						],
						[
							'label'  => __( 'Learn more', "funnel-builder" ),
							'href'   => "https://funnelkit.com/whats-new/?utm_source=WordPress&utm_campaign=fb+lite+plugin&utm_medium=Update+Notice+Bar",
							'target' => '__blank',
						]
					]
				);
			}


			$global_funnel_id = WFFN_Common::get_store_checkout_id();
			if ( absint( $global_funnel_id ) > 0 ) {
				$get_funnel = new WFFN_Funnel( $global_funnel_id );
				if ( $get_funnel instanceof WFFN_Funnel && 0 !== $get_funnel->get_id() ) {
					$get_version = WFFN_Core()->get_dB()->get_meta( $global_funnel_id, '_version' );

					if ( empty( $get_version ) ) {
						$this->notifs[] = array(
							'key'           => 'mig_store_checkout',
							'content'       => $this->store_checkout_migrated(),
							'customButtons' => [
								[
									'label'     => __( "Go to Store Checkout", "funnel-builder" ),
									'href'      => admin_url( 'admin.php?page=bwf&path=/store-checkout' ),
									'action'    => 'close_notice',
									'className' => 'is-primary',
								],
								[
									'label'  => __( "Learn more", "funnel-builder" ),
									'href'   => "https://funnelkit.com/woofunnels-is-now-funnelkit/?utm_source=WordPress&utm_medium=Store+Checkout+Migrate&utm_campaign=fb+lite+plugin",
									'target' => '__blank',
								]
							]
						);
					}


				}
			}

			/** Check if brand change notice is required */
			$first_v = get_option( 'wffn_first_v', false );
			if ( empty( $first_v ) || version_compare( $first_v, '2.4.1', '<' ) ) {
				$this->notifs[] = array(
					'key'     => 'brandchange',
					'content' => $this->brandchange(),

					'customButtons' => [
						[
							'label'     => __( "Learn more", "funnel-builder" ),
							'href'      => 'https://funnelkit.com/woofunnels-is-now-funnelkit/?utm_source=WordPress&utm_medium=Brand+Name+Change&utm_campaign=fb+lite+plugin',
							'className' => 'is-primary',
							'target'    => '__blank',
						],

					],
				);
			}

			if ( class_exists( 'WFFN_Pro_Modules' ) ) {
				$is_upsell_exists   = WFFN_Pro_Modules::get_module( 'one-click-upsells/woofunnels-upstroke-one-click-upsell.php' )::is_module_exists();
				$is_bump_exists     = WFFN_Pro_Modules::get_module( 'order-bumps/woofunnels-order-bump.php' )::is_module_exists();
				$is_checkout_exists = WFFN_Pro_Modules::get_module( 'checkout/woofunnels-aero-checkout.php' )::is_module_exists();
				$is_ab_exists       = WFFN_Pro_Modules::get_module( 'woofunnels-ab-tests/woofunnels-ab-tests.php' )::is_module_exists();

				$array_of_strings = [];
				if ( $is_upsell_exists ) {
					$array_of_strings[] = 'Upsells';
				}
				if ( $is_checkout_exists ) {
					$array_of_strings[] = 'Checkout';
				}
				if ( $is_bump_exists ) {
					$array_of_strings[] = 'Order Bump';
				}
				if ( $is_ab_exists ) {
					$array_of_strings[] = 'A/B testing';
				}

				if ( count( $array_of_strings ) > 0 ) {
					$this->notifs[] = array(
						'key'           => 'indi_plugins',
						'content'       => $this->individual_plugins_found( WFFN_Common::natural_language_join( $array_of_strings ), count( $array_of_strings ) ),
						'customButtons' => [
							[
								'label'     => __( "Deactivate Plugins", "funnel-builder" ),
								'href'      => admin_url( 'plugins.php?s=funnelkit' ),
								'className' => 'is-primary',
								'target'    => '__blank',
							],

							[
								'label'  => __( "Dismiss", "funnel-builder" ),
								'action' => 'close_notice',

							]
						]
					);
				}
			}

			if ( defined( 'WFFN_PRO_FILE' ) && function_exists( 'wfacp_is_woocommerce_active' ) && wfacp_is_woocommerce_active() && class_exists( '\Automattic\WooCommerce\Blocks\BlockTypes\ClassicShortcode' ) && $this->_should_display_block_incompatible_notice() && absint( $global_funnel_id ) === 0 ) {
				$this->notifs[] = array(
					'key'           => 'wc_block_incompat',
					'content'       => $this->block_incompat_notice(),
					'customButtons' => [
						[
							'label'     => __( "Switch to Classic Checkout", "funnel-builder" ),
							'action'    => 'api',
							'path'      => '/notifications/wc_block_incompat',
							'className' => 'is-primary',
						],

						[
							'label'  => __( "Dismiss", "funnel-builder" ),
							'action' => 'close_notice',

						]
					]
				);

			}

			$state_for_migration = $this->is_conversion_migration_required();
			if ( defined( 'WFFN_PRO_FILE' ) ) {
				if ( 1 === $state_for_migration ) {
					$this->notifs[] = array(
						'key'             => 'conversion_migration',
						'content'         => $this->conversion_migration_content( $state_for_migration ),
						'customButtons'   => [
							[
								'label'     => __( "Upgrade Database", "funnel-builder" ),
								'action'    => 'api',
								'path'      => '/migrate-conversion/',
								'className' => 'is-primary',
							],

						],
						'not_dismissible' => true
					);
				} elseif ( 2 === $state_for_migration ) {
					$this->notifs[] = array(
						'key'             => 'conversion_migration',
						'content'         => $this->conversion_migration_content( $state_for_migration ),
						'customButtons'   => [],
						'not_dismissible' => true
					);
				} elseif ( 3 === $state_for_migration ) {
					$this->notifs[] = array(
						'key'           => 'conversion_migration',
						'content'       => $this->conversion_migration_content( $state_for_migration ),
						'customButtons' => [
							[
								'label'     => __( "Dismiss", "funnel-builder" ),
								'action'    => 'close_notice',
								'className' => 'is-primary',
							]
						]
					);
				}
			}
		}

		private function _should_display_block_incompatible_notice() {
			$wc_cart_page     = get_post( wc_get_page_id( 'cart' ) );
			$wc_checkout_page = get_post( wc_get_page_id( 'checkout' ) );

			return has_block( 'woocommerce/checkout', $wc_checkout_page ) || has_block( 'woocommerce/cart', $wc_cart_page );
		}

		private function block_incompat_notice() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . __( "FunnelKit Checkout Pro", "funnel-builder" ) . '</h3>
					<p class="bwf-notifications-content">' . __( "We noticed that you're using the native Checkout experience in WooCommerce. Enhance the native checkout experience with conversion optimised templates that work out of box. Plus boost revenue by 15-20 percent by using order bumps and one click Upsells.", "funnel-builder" ) . '</p>
				</div>';
		}


		public function brandchange() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . __( "Alert! WooFunnels is now FunnelKit", "funnel-builder" ) . '</h3>
					<p class="bwf-notifications-content">' . __( "We are proud to announce that WooFunnels is now called FunnelKit. Only the name changes, everything else remains the same.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function store_checkout_migrated() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . __( "Global Checkout has been migrated to Store Checkout!", "funnel-builder" ) . '</h3>
					<p class="bwf-notifications-content">' . __( "To make your storefront's more accessible, we have migrated Global Checkout. All the steps of the checkout are available under Store Checkout.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function individual_plugins_found( $plugins_str, $count ) {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . sprintf( "Please deactivate individual plugin%s for %s.", ( 1 < $count ) ? 's' : '', $plugins_str ) . '</h3>
					<p class="bwf-notifications-content">' . __( "Funnel Builder Pro is active. It contains all the modules and you don't need separate plugins. Please deactivate them. Don't worry no data will be lost.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function pro_update_3_0() {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . __( "Update Funnel Builder Pro to version 3.0", "funnel-builder" ) . '</h3>
					<p class="bwf-notifications-content">' . __( "It seems that you are running an older version of Funnel Builder Pro. For a smoother experience, update Funnel Builder Pro to version 3.0.", "funnel-builder" ) . '</p>
				</div>';
		}


		public function promo_pre_bfcm( $html = true ) {
			$title   = __( "Pre Black Friday Sale is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" );
			$content = sprintf( __( "<strong>Get started using FunnelKit to grow your revenue today for up to %s OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing and much more! Expires Sunday, %s, at midnight ET.", "funnel-builder" ), '55%', $this->get_black_friday_day_data( 'pre' ) );

			if ( $html === false ) {
				return [
					'title'   => $title,
					'content' => $content,
					'date' => $this->get_black_friday_day_data( 'pre' )
				];
			}

			return '<div class="bwf-notifications-message current">
                <h3 class="bwf-notifications-title">
                    <img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . $title . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">
                </h3>
                <p class="bwf-notifications-content">' . $content . '</p>
            </div>';
		}

		public function promo_bfcm( $html = true ) {
			$title   = __( "Black Friday is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" );
			$content = sprintf( __( "<strong>Get started using FunnelKit to grow your revenue today for up to %s OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing and much more! Expires Friday, %s, at midnight ET.", "funnel-builder" ), '55%', $this->get_black_friday_day_data( 'bf' ) );

			if ( $html === false ) {
				return [
					'title'   => $title,
					'content' => $content,
					'date' => $this->get_black_friday_day_data( 'bf' )
				];
			}

			return '<div class="bwf-notifications-message current">
                <h3 class="bwf-notifications-title">
                    <img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . $title . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">
                </h3>
                <p class="bwf-notifications-content">' . $content . '</p>
            </div>';
		}

		public function promo_small_business_saturday( $html = true ) {
			$title   = __( "Small Business Saturday Sale is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" );
			$content = sprintf( __( "<strong>Get started using FunnelKit to grow your revenue today for up to %s OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing and much more! Expires Saturday, %s, at midnight ET.", "funnel-builder" ), '55%', $this->get_black_friday_day_data( 'sbs' ) );

			if ( $html === false ) {
				return [
					'title'   => $title,
					'content' => $content,
					'date' => $this->get_black_friday_day_data( 'sbs' )
				];
			}

			return '<div class="bwf-notifications-message current">
                <h3 class="bwf-notifications-title">
                    <img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . $title . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">
                </h3>
                <p class="bwf-notifications-content">' . $content . '</p>
            </div>';
		}

		public function promo_ext_bfcm( $html = true ) {
			$title   = __( "Black Friday is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" );
			$content = sprintf( __( "<strong>Get started using FunnelKit to grow your revenue today for up to %s OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing and much more! Expires Sunday, %s, at midnight ET.", "funnel-builder" ), '55%', $this->get_black_friday_day_data( 'bfext' ) );

			if ( $html === false ) {
				return [
					'title'   => $title,
					'content' => $content,
					'date' => $this->get_black_friday_day_data( 'bfext' )
				];
			}

			return '<div class="bwf-notifications-message current">
                <h3 class="bwf-notifications-title">
                    <img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . $title . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">
                </h3>
                <p class="bwf-notifications-content">' . $content . '</p>
            </div>';
		}

		public function promo_cmonly( $html = true ) {
			$title   = __( "Cyber Monday is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" );
			$content = sprintf( __( "<strong>Get started using FunnelKit to grow your revenue today for up to %s OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing and much more! Expires Monday, %s, at midnight ET.", "funnel-builder" ), '55%', $this->get_black_friday_day_data( 'cm' ) );

			if ( $html === false ) {
				return [
					'title'   => $title,
					'content' => $content,
					'date' => $this->get_black_friday_day_data( 'cm' )
				];
			}

			return '<div class="bwf-notifications-message current">
                <h3 class="bwf-notifications-title">
                    <img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . $title . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">
                </h3>
                <p class="bwf-notifications-content">' . $content . '</p>
            </div>';
		}

		public function promo_ext_cmonly( $html = true ) {
			$title   = __( "Cyber Monday Extended is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" );
			$content = sprintf( __( "<strong>Get started using FunnelKit to grow your revenue today for up to %s OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing and much more! Expires Friday, %s, at midnight ET.", "funnel-builder" ), '55%', $this->get_black_friday_day_data( 'cmext' ) );

			if ( $html === false ) {
				return [
					'title'   => $title,
					'content' => $content,
					'date' => $this->get_black_friday_day_data( 'cmext' )
				];
			}

			return '<div class="bwf-notifications-message current">
                <h3 class="bwf-notifications-title">
                    <img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . $title . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">
                </h3>
                <p class="bwf-notifications-content">' . $content . '</p>
            </div>';
		}

		public function promo_gm( $html = true ) {
			$title   = __( "Green Monday is HERE - Subscribe Now for Up To 55% Off ", "funnel-builder" );
			$content = sprintf( __( "<strong>Get started using FunnelKit to grow your revenue today for up to %s OFF!</strong> Get access to money-making solutions like Conversion Optimized Checkout, One Click Upsells Order Bumps, Analytics, A/B Testing and much more! Expires Monday, %s, at midnight ET.", "funnel-builder" ), '55%', $this->get_second_dec_monday_day_diff( false ) );

			if ( $html === false ) {
				return [
					'title'   => $title,
					'content' => $content,
					'date' => $this->get_second_dec_monday_day_diff( false )
				];
			}

			return '<div class="bwf-notifications-message current">
                <h3 class="bwf-notifications-title">
                    <img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">' . $title . '<img draggable="false" role="img" class="emoji" alt="💰" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f4b0.svg">
                </h3>
                <p class="bwf-notifications-content">' . $content . '</p>
            </div>';
		}


		public function update_available( $version = '0.0.0' ) {
			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . sprintf( "Alert! New version %s is available for update", $version ) . '</h3>
					<p class="bwf-notifications-content">' . __( "Don't miss out on the latest features, bug fixes & security enhancements! Upgrade to the latest version and do not let an outdated version hold you back.", "funnel-builder" ) . '</p>
				</div>';
		}

		public function conversion_migration_content( $state ) {

			if ( 1 === $state ) {
				$header = __( "Funnel Builder requires a Database upgrade", "funnel-builder" );
			} elseif ( 2 === $state ) {
				$header = __( "Funnel Builder Database upgrade started", "funnel-builder" );

				$identifier = 'bwf_conversion_1_migrator_cron';
				if ( function_exists( 'wp_next_scheduled' ) && function_exists( 'wffn_conversion_tracking_migrator' ) && ! wp_next_scheduled( $identifier ) ) {
					WFFN_Conversion_Tracking_Migrator::get_instance()->push_to_queue( 'wffn_run_conversion_migrator' );
					WFFN_Conversion_Tracking_Migrator::get_instance()->dispatch();
					WFFN_Conversion_Tracking_Migrator::get_instance()->save();
				}

			} else {
				$header = __( "Funnel Builder Database upgrade completed", "funnel-builder" );
			}

			return '<div class="bwf-notifications-message current">
					<h3 class="bwf-notifications-title">' . $header . '</h3>
					<p class="bwf-notifications-content">' . __( "To keep things running smoothly, we have to update the database to the newest version. The database upgrade runs in the background and may take a while depending upon the number of Orders, so please be patient. If you need any help <a target='_blank' href='http://funnelkit.com/support/'>contact support</a>.", "funnel-builder" ) . '</p>
				</div>';
		}


		public function filter_notifs( $all_registered_notifs, $id ) {
			$userdata = get_user_meta( $id, '_bwf_notifications_close', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
			if ( empty( $userdata ) ) {
				return $all_registered_notifs;
			}

			foreach ( $all_registered_notifs as $k => $notif ) {
				if ( ! in_array( $notif['key'], $userdata, true ) ) {
					continue;
				}
				unset( $all_registered_notifs[ $k ] );
			}


			return $all_registered_notifs;
		}

		public function user_has_notifications( $id ) {
			$all_registered_notifs = $this->get_notifications();

			$filter_notifs = $this->filter_notifs( $all_registered_notifs, $id );

			return count( $filter_notifs ) > 0 ? true : false;

		}

		public function is_user_dismissed( $id, $key ) {
			$userdata = get_user_meta( $id, '_bwf_notifications_close', true );
			$userdata = empty( $userdata ) && ! is_array( $userdata ) ? [] : $userdata;
			return in_array( $key, $userdata, true );
		}

		public function register_notices() {
			$user = WFFN_Role_Capability::get_instance()->user_access( 'menu', 'read' );
			if ( ! $user ) {
				return;
			}

			$global_funnel_id = WFFN_Common::get_store_checkout_id();
			global $fk_block_notice;

			if ( defined( 'WFFN_PRO_FILE' ) && empty( $fk_block_notice ) && function_exists( 'wfacp_is_woocommerce_active' ) && wfacp_is_woocommerce_active() && class_exists( '\Automattic\WooCommerce\Blocks\BlockTypes\ClassicShortcode' ) && $this->_should_display_block_incompatible_notice() && absint( $global_funnel_id ) === 0 && false === $this->is_user_dismissed( get_current_user_id(), 'wc_block_incompat' ) ) {
				$fk_block_notice   = true;
				$current_admin_url = basename( $_SERVER['REQUEST_URI'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
				$action_url        = admin_url( 'admin-ajax.php?action=wffn_blocks_incompatible_switch_to_classic&nonce=' . wp_create_nonce( 'wffn_blocks_incompatible_switch_to_classic' ) . '&redirect=' . $current_admin_url );
				$dismiss_url       = admin_url( 'admin-ajax.php?action=wffn_dismiss_notice&nkey=wc_block_incompat&nonce=' . wp_create_nonce( 'wp_wffn_dismiss_notice' ) . '&redirect=' . $current_admin_url );

				echo '<div class="notice notice-warning" >';

				echo wp_kses_post( $this->block_incompat_notice() );
				echo '<p><a class="button button-primary" href="' . esc_url( $action_url ) . '">' . __( ' Switch to Classic Checkout', 'funnel-builder' ) . '</a>     <a href="' . esc_url( $dismiss_url ) . '">' . __( 'Dismiss', 'funnel-builder' ) . '</a>  </p>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			}

			$this->show_setup_wizard();
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @return void
		 * @since 1.0.0
		 */

		public function show_setup_wizard() {

			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			$allowed_screens = array(
				'woofunnels_page_bwf_funnels',
				'dashboard',
				'plugins',
			);
			if ( ! in_array( $screen_id, $allowed_screens, true ) ) {
				return;
			}
			$current_admin_url = basename( wffn_clean( $_SERVER['REQUEST_URI'] ) ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
			$dismiss_url       = admin_url( 'admin-ajax.php?action=wffn_dismiss_notice&nkey=onboarding_wizard&nonce=' . wp_create_nonce( 'wp_wffn_dismiss_notice' ) . '&redirect=' . $current_admin_url );

			if ( WFFN_Core()->admin->is_wizard_available() ) { ?>


                <div class="notice notice-warning" style="position: relative;">

                    <a class="notice-dismiss" style="
                    position: absolute;
                    padding: 5px 15px 5px 35px;
                    font-size: 13px;
                    line-height: 1.2311961000;
                    text-decoration: none;
                    display: inline-flex;
                    top: 12px;
                    " href="<?php echo esc_url( $dismiss_url ) ?>"><?php esc_html_e( 'Dismiss' ); ?></a>
                    <h3 class="bwf-notifications-title"> <?php echo __( "Funnel Builder Quick Setup", "funnel-builder" ); ?></h3> <?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                    <p><?php esc_html_e( 'Thank you for activating Funnel Builder by FunnelKit. Go through a quick setup to ensure most optimal experience.', 'funnel-builder' ); ?></p>
                    <p>
                        <a href="<?php echo esc_url( WFFN_Core()->admin->wizard_url() ); ?>" class="button button-primary"> <?php esc_html_e( 'Start Wizard', 'funnel-builder' ); ?></a>

                    </p>
                </div>

				<?php
			}
		}


		/**
		 * Returns whether conversion migration is required or not
		 * @return integer
		 */
		public function is_conversion_migration_required() {


			/**
			 * if pro version is not installed, then no need to migrate
			 */
			if ( ! defined( 'WFFN_PRO_VERSION' ) || version_compare( WFFN_PRO_VERSION, '3.0.0', '<' ) ) {
				return 4;
			}
			$upgrade_state = WFFN_Conversion_Tracking_Migrator::get_instance()->get_upgrade_state();

			if ( 0 === $upgrade_state ) {
				if ( ! wffn_is_wc_active() || version_compare( get_option( 'wffn_first_v', '0.0.0' ), '3.0.0', '>=' ) ) {
					WFFN_Conversion_Tracking_Migrator::get_instance()->set_upgrade_state( 4 );
					$upgrade_state = 4;
				} else {
					global $wpdb;
					$count_wc_orders = $wpdb->get_var( "SELECT COUNT(`order_id`) FROM {$wpdb->prefix}wc_order_stats" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

					if ( empty( $count_wc_orders ) ) {
						WFFN_Conversion_Tracking_Migrator::get_instance()->set_upgrade_state( 4 );
						$upgrade_state = 4;
					} else {
						WFFN_Conversion_Tracking_Migrator::get_instance()->set_upgrade_state( 1 );
						$upgrade_state = 1;
					}
				}
			}

			return $upgrade_state;


		}

	}


}


if ( class_exists( 'WFFN_Core' ) ) {
	WFFN_Core::register( 'admin_notifications', 'WFFN_Admin_Notifications' );
}

