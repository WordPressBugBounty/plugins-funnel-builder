<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class WFTY_Shortcodes
 * @package WFTY
 * @author XlPlugins
 */
if ( ! class_exists( 'WFTY_Shortcodes' ) ) {
	#[AllowDynamicProperties]

  class WFTY_Shortcodes {

		public static function init() {
			$data = WFFN_Core()->thank_you_pages->data;

			/**
			 * WordPress prints whatever a shortcode returns, so the escaping has to
			 * happen here. Wrapping at registration keeps the data methods usable
			 * elsewhere -- templates and the wfty_* helper functions still get the
			 * raw value.
			 */
			$plain_text = array(
				'wfty_order_number'          => 'get_order_id',
				'wfty_customer_first_name'   => 'get_customer_first_name',
				'wfty_customer_last_name'    => 'get_customer_last_name',
				'wfty_customer_email'        => 'get_customer_email',
				'wfty_customer_phone_number' => 'get_customer_phone',
				'wfty_order_meta'            => 'wfty_order_meta',
			);

			foreach ( $plain_text as $tag => $method ) {
				add_shortcode(
					$tag,
					static function ( $atts = array() ) use ( $data, $method ) {
						return esc_html( (string) call_user_func( array( $data, $method ), $atts ) );
					}
				);
			}

			/** These build markup -- a details table, an address block, a formatted price. */
			$markup = array(
				'wfty_customer_details' => 'get_customer_info',
				'wfty_order_details'    => 'get_order_details',
				'wfty_order_total'      => 'get_order_total',
			);

			foreach ( $markup as $tag => $method ) {
				add_shortcode(
					$tag,
					static function ( $atts = array() ) use ( $data, $method ) {
						return wp_kses_post( (string) call_user_func( array( $data, $method ), $atts ) );
					}
				);
			}
		}
	}
}


function wfty_customer_first_name() {
	return WFFN_Core()->thank_you_pages->data->get_customer_first_name();
}

function wfty_customer_last_name() {
	return WFFN_Core()->thank_you_pages->data->get_customer_last_name();
}

function wfty_customer_email() {
	return WFFN_Core()->thank_you_pages->data->get_customer_email();
}

function wfty_customer_phone_number() {
	return WFFN_Core()->thank_you_pages->data->get_customer_phone();
}

function wfty_order_number() {
	return WFFN_Core()->thank_you_pages->data->get_order_id();
}

function wfty_order_total( $args ) {
	return WFFN_Core()->thank_you_pages->data->get_order_total( $args );
}

function wfty_order_meta( $args ) {
	return WFFN_Core()->thank_you_pages->data->wfty_order_meta( $args );
}