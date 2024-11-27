<?php

#[AllowDynamicProperties]
class WFFN_Email_Notification {
	/**
	 * The single instance of the class.
	 *
	 * @var WFFN_Email_Notification
	 */
	protected static $instance = null;

	/**
	 * Global settings.
	 *
	 * @var array
	 */
	protected $global_settings = array();
	/**
	 * Last executed notification.
	 *
	 * @var array
	 */
	protected $executed_last = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		/** global settings */
		$this->global_settings = $this->load_settings();

		/** Fetch the saved notifications data */
		$this->executed_last = get_option( 'wffn_email_notification_updated', array(
			'weekly'  => '',
			'monthly' => '',
		) );

		$this->init();
	}

	/**
	 * Initialize the class.
	 */
	public function init() {
		/** Email notification callback */
		add_action( 'wffn_performance_notification', array( $this, 'run_notifications' ) );

		/** Testing */
		add_action( 'admin_init', array( $this, 'test_notification_admin' ) );

		add_action( 'wp_ajax_wffn_send_test_email_notification', array( $this, 'send_test_email_notification' ) );

		add_action( 'bwf_global_save_settings_funnelkit_notifications', array( $this, 'save_settings' ), 10, 1 );

		add_action( 'bwf_general_settings_updated', array( $this, 'wffn_set_as_ct_worker' ) );
	}

	/**
	 * Load notification settings.
	 *
	 * @return array
	 */
	public static function load_settings() {
		BWF_Admin_General_Settings::get_instance()->setup_options();
		$bwf_enable_notification = BWF_Admin_General_Settings::get_instance()->get_option( 'bwf_enable_notification' );
		$frequency               = BWF_Admin_General_Settings::get_instance()->get_option( 'bwf_notification_frequency' );
		$users                   = BWF_Admin_General_Settings::get_instance()->get_option( 'bwf_notification_user_selector' );
		$external_users          = BWF_Admin_General_Settings::get_instance()->get_option( 'bwf_external_user' );
		$notification_time       = BWF_Admin_General_Settings::get_instance()->get_option( 'bwf_notification_time' );

		return [
			'bwf_enable_notification'        => $bwf_enable_notification,
			'bwf_notification_frequency'     => $frequency,
			'bwf_notification_user_selector' => $users,
			'bwf_external_user'              => $external_users,
			'bwf_notification_time'          => $notification_time,
		];
	}

	/**
	 * Get the instance of the class.
	 *
	 * @return WFFN_Email_Notification
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Retrieves the HTML content of a template.
	 *
	 * This method includes the specified template file and allows passing arguments to it.
	 *
	 * @param string $template The name of the template file to include.
	 * @param array $args Optional. An array of arguments to pass to the template file. Default is an empty array.
	 *
	 * @return void
	 */
	public static function get_template_html( $template, $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		ob_start();
		include WFFN_PLUGIN_DIR . '/' . $template;

		return ob_get_clean();// phpcs:ignore WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Create a timestamp from an array of time values.
	 *
	 * @param array $time_array An array of time values.
	 *
	 * @return int|bool The timestamp or false if required keys are missing.
	 */
	public function create_timestamp_from_array( $time_array ) {
		// Check if required keys exist in the array
		if ( isset( $time_array['hours'], $time_array['minutes'], $time_array['ampm'] ) ) {
			$hours   = intval( $time_array['hours'] );
			$minutes = intval( $time_array['minutes'] );
			$ampm    = strtolower( $time_array['ampm'] );

			if ( $ampm === 'am' && 12 === $hours ) {
				$hours = 0;
			} elseif ( $ampm === 'pm' && $hours < 12 ) {
				// Convert 12-hour format to 24-hour format
				$hours += 12;
			}

			return WFFN_Common::get_store_time( $hours, $minutes, 0 );
		}

		return WFFN_Common::get_store_time( 10 );
	}

	/**
	 * @return void
	 */
	public function run_notifications() {
		if ( false === $this->is_notification_active() ) {
			return;
		}

		$frequencies = $this->get_frequencies();

		$frequencies = $this->filter_frequencies( $frequencies );

		$frequencies = self::prepare_frequencies( $frequencies );
		if ( empty( $frequencies ) ) {
			return;
		}

		foreach ( $frequencies as $frequency => $dates ) {
			$this->send_email( $frequency, $dates );
		}
	}

	/**
	 * Check if email notification is active.
	 *
	 * @return bool
	 */
	protected function is_notification_active() {
		return isset( $this->global_settings['bwf_enable_notification'] ) && $this->global_settings['bwf_enable_notification'];
	}

	/**
	 * Get the frequencies for email notifications.
	 *
	 * @return array
	 */
	protected function get_frequencies() {
		if ( isset( $this->global_settings['bwf_notification_frequency'] ) && is_array( $this->global_settings['bwf_notification_frequency'] ) ) {
			return $this->global_settings['bwf_notification_frequency'];
		}

		return array();
	}

	/**
	 * Filter the frequencies based on the last saved option key.
	 *
	 * @param array $frequencies The frequencies to filter.
	 *
	 * @return array The filtered frequencies.
	 */
	protected function filter_frequencies( $frequencies = array() ) {
		if ( empty( $frequencies ) ) {
			return array();
		}

		/** Filter out the frequencies if an email was already sent */
		return array_filter( $frequencies, function ( $frequency ) {
			return ! $this->mail_sent( $frequency );
		} );
	}

	/**
	 * Prepare frequencies
	 *
	 * @param $frequencies
	 *
	 * @return array
	 * @throws DateMalformedStringException
	 */
	public static function prepare_frequencies( $frequencies = [] ) {
		$final = array();

		if ( array_search( 'weekly', $frequencies ) !== false ) { // @codingStandardsIgnoreLine
			$final['weekly'] = WFFN_Common::get_notification_week_range();
		}


		if ( array_search( 'monthly', $frequencies ) !== false ) { // @codingStandardsIgnoreLine
			$final['monthly'] = WFFN_Common::get_notification_month_range();
		}

		return $final;
	}

	/**
	 * Check if the email was sent for the given frequency.
	 *
	 * @param string $frequency The frequency to check.
	 *
	 * @return bool True if the email was sent, false otherwise.
	 */
	public function mail_sent( $frequency ) {
		$today = new DateTime();

		/** Case: weekly. Not Monday */
		if ( 'weekly' === $frequency && 1 !== intval( $today->format( 'N' ) ) ) {
			/** 1 means Monday */
			return true;
		}


		/** Case: monthly. Not 1st */
		if ( 'monthly' === $frequency && 1 !== intval( $today->format( 'd' ) ) ) {
			return true;
		}

		/** Check if the last execution time for the given frequency is not set */
		if ( ! isset( $this->executed_last[ $frequency ] ) || empty( $this->executed_last[ $frequency ] ) ) {
			return false;
		}

		try {
			$last_sent = new DateTime( $this->executed_last[ $frequency ] );
		} catch ( Exception $e ) {
			WFFN_Core()->logger->log( "Frequency {$frequency} and value {$this->executed_last[$frequency]}", 'notification-error', true );
			WFFN_Core()->logger->log( "Exception {$e->getMessage()}", 'notification-error', true );

			return false;
		} catch ( Error $e ) {
			WFFN_Core()->logger->log( "Frequency {$frequency} and value {$this->executed_last[$frequency]}", 'notification-error', true );
			WFFN_Core()->logger->log( "Error {$e->getMessage()}", 'notification-error', true );

			return false;
		}

		switch ( $frequency ) {
			case 'weekly':
				return ! ( intval( $last_sent->format( 'W' ) ) < intval( $today->format( 'W' ) ) );

			case 'monthly':
				return ! ( intval( $last_sent->format( 'Ym' ) ) < intval( $today->format( 'Ym' ) ) );
			default:
				return false;
		}
	}

	/**
	 * Send email notification.
	 *
	 * @param $frequency
	 * @param $dates
	 *
	 * @return bool|mixed|void
	 */
	public function send_email( $frequency, $dates ) {
		/** Prepare metrics */
		$metrics_controller = new WFFN_Notification_Metrics_Controller( $dates, $frequency );
		$metrics_controller->prepare_data();

		/** Check if email has data */
		if ( ! $metrics_controller->is_valid() ) {
			return;
		}

		$data             = $metrics_controller->get_data();
		$email_controller = new WFFN_Notification_Email_Controller( $frequency, $data, $dates );

		$to      = $this->get_recipients();
		$subject = $this->get_email_subject( $frequency, $dates );
		$body    = $email_controller->get_content_html();
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		if ( is_array( $to ) && count( $to ) > 0 ) {
			foreach ( $to as $recipient ) {
				$sent = wp_mail( $recipient, $subject, $body, $headers ); // @codingStandardsIgnoreLine
			}
		}

		// Update the last execution time if the email was sent.
		if ( $sent ) {
			$this->executed_last[ $frequency ] = date( 'c' ); // @codingStandardsIgnoreLine
			update_option( 'wffn_email_notification_updated', $this->executed_last );
		}
	}

	/**
	 * Get the recipients for the email.
	 *
	 * @return array The recipients for the email.
	 */
	private function get_recipients() {
		$recipients = array( get_option( 'admin_email' ) );

		if ( isset( $this->global_settings['bwf_notification_user_selector'] ) && is_array( $this->global_settings['bwf_notification_user_selector'] ) ) {
			foreach ( $this->global_settings['bwf_notification_user_selector'] as $user ) {
				if ( isset( $user['id'] ) && ! empty( $user['id'] ) ) {
					$user_data = get_userdata( $user['id'] );
					if ( $user_data ) {
						$recipients[] = $user_data->user_email;
					}
				}
			}
		}

		if ( isset( $this->global_settings['bwf_external_user'] ) && is_array( $this->global_settings['bwf_external_user'] ) ) {
			foreach ( $this->global_settings['bwf_external_user'] as $user ) {
				if ( isset( $user['mail'] ) && ! empty( $user['mail'] ) ) {
					$recipients[] = $user['mail'];
				}
			}
		}

		return $recipients;
	}

	/**
	 * Get the email subject.
	 *
	 * @param string $frequency The frequency of the email.
	 * @param array $dates The dates to use in the email subject.
	 *
	 * @return string The email subject.
	 */
	public static function get_email_subject( $frequency, $dates ) {
		$date_string = self::get_date_string( $dates, $frequency );
		switch ( strtolower( $frequency ) ) {
			case 'weekly':
				return sprintf( __( 'Weekly Report for %s', 'FunnelKit' ), $date_string );

			case 'monthly':
				return sprintf( __( 'Monthly Report for %s', 'FunnelKit' ), $date_string );

			default:
				return __( 'Report', 'FunnelKit' );
		}
	}

	/**
	 * Get the date string for the email subject.
	 *
	 * @param array $dates The dates to use in the date string.
	 *
	 * @return string The date string.
	 */
	public static function get_date_string( $dates = array(), $frequency = 'weekly' ) {
		if ( 'daily' === $frequency && isset( $dates['from_date'] ) ) {
			return sprintf( __( '%1$s', 'Funnelkit' ), self::format_date( $dates['from_date'] ) );
		}

		if ( isset( $dates['from_date'] ) && isset( $dates['to_date'] ) ) {
			return sprintf( __( '%1$s - %2$s', 'Funnelkit' ), self::format_date( $dates['from_date'] ), self::format_date( $dates['to_date'] ) );
		}

		return '';
	}

	/**
	 * Formats a date string to the desired format.
	 *
	 * @param string $date_string The date string to format.
	 *
	 * @return string The formatted date string.
	 */
	public static function format_date( $date_string ) {
		// Convert date string to a DateTime object
		$date = new DateTime( $date_string );

		return $date->format( 'F j, Y' );
	}

	/**
	 * Testing email notification.
	 */
	public function test_notification_admin() {


		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}
		if ( ! isset( $_GET['wffn_email_preview'] ) ) { // @codingStandardsIgnoreLine
			return;
		}
		$mode = filter_input( INPUT_GET, 'wffn_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$mode = empty( $mode ) ? 'weekly' : $mode;

		switch ( $mode ) {
			case 'monthly':
				$range = WFFN_Common::get_notification_month_range();
				break;
			default:
				$range = WFFN_Common::get_notification_week_range();
				$mode  = 'weekly';
				break;
		}

		$dates = array(
			'from_date'          => $range['from_date'],
			'to_date'            => $range['to_date'],
			'from_date_previous' => $range['from_date_previous'],
			'to_date_previous'   => $range['to_date_previous'],
		);

		// Prepare metrics.
		$metrics_controller = new WFFN_Notification_Metrics_Controller( $dates, $mode );
		$metrics_controller->prepare_data();

		$data             = $metrics_controller->get_data();
		$email_controller = new WFFN_Notification_Email_Controller( $mode, $data, $dates );

		echo $email_controller->get_content_html(); // @codingStandardsIgnoreLine
		exit;
	}

	/**
	 * Send test email notification.
	 */
	public function send_test_email_notification() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action.', 'Funnelkit' ) );
		}

		$mode = filter_input( INPUT_GET, 'wffn_mode', FILTER_SANITIZE_STRING );
		$mode = empty( $mode ) ? 'weekly' : $mode;

		switch ( $mode ) {
			case 'monthly':
				$range = WFFN_Common::get_notification_month_range();
				break;
			default:
				$range = WFFN_Common::get_notification_week_range();
				$mode  = 'weekly';
				break;
		}

		$dates = array(
			'from_date'          => $range['from_date'],
			'to_date'            => $range['to_date'],
			'from_date_previous' => $range['from_date_previous'],
			'to_date_previous'   => $range['to_date_previous'],
		);

		// Prepare metrics.
		$metrics_controller = new WFFN_Notification_Metrics_Controller( $dates, $mode );
		$metrics_controller->prepare_data();

		$data             = $metrics_controller->get_data();
		$email_controller = new WFFN_Notification_Email_Controller( $mode, $data, $dates );

		$to      = get_option( 'admin_email' );
		$subject = self::get_email_subject( $mode, $dates );
		$body    = $email_controller->get_content_html();
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		$sent = wp_mail( $to, $subject, $body, $headers ); // @codingStandardsIgnoreLine

		if ( $sent ) {
			wp_send_json_success( __( 'Email sent successfully.', 'Funnelkit' ) );
		} else {
			wp_send_json_error( __( 'Failed to send email.', 'Funnelkit' ) );
		}
	}

	public function save_settings( $options ) {
		$resp       = [];
		$get_config = get_option( 'bwf_gen_config', [] );
		foreach ( $options as $key => $value ) {

			$get_config[ $key ] = $value;
		}
		$general_settings = BWF_Admin_General_Settings::get_instance();
		$general_settings->update_global_settings_fields( $get_config );
		$resp['status'] = true;

		return $resp;

	}

	function wffn_set_as_ct_worker() {

		$email_notification = WFFN_Email_Notification::get_instance();
		$settings           = $email_notification->load_settings();
		if ( empty( $settings['bwf_enable_notification'] ) ) {
			return;
		}

		$notification_time = $settings['bwf_notification_time'];
		$desired_timestamp = $email_notification->create_timestamp_from_array( $notification_time );
		$next_scheduled = wp_next_scheduled( 'wffn_performance_notification' );

		if ( ! $next_scheduled || $next_scheduled !== $desired_timestamp ) {
			if ( $next_scheduled ) {
				wp_clear_scheduled_hook( 'wffn_performance_notification' );
			}
			wp_schedule_event( $desired_timestamp, 'daily', 'wffn_performance_notification' );
		}

	}

}

if ( class_exists( 'WFFN_Core' ) ) {
	WFFN_Core::register( 'notification_email', 'WFFN_Email_Notification' );
}
