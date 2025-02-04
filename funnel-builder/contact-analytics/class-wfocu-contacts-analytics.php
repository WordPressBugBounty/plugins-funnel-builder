<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOCU_Contacts_Analytics
 */
if ( ! class_exists( 'WFOCU_Contacts_Analytics' ) ) {

	class WFOCU_Contacts_Analytics {

		/**
		 * instance of class
		 * @var null
		 */
		private static $ins = null;

		/**
		 * WFOCU_Contacts_Analytics constructor.
		 */
		public function __construct() {
		}

		/**
		 * @return WFOCU_Contacts_Analytics|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		/**
		 * @param $funnel_id
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records( $funnel_id, $cid ) {
			global $wpdb;
			$item_data = [];

			$query = "SELECT session.order_id as order_id, event.object_id, event.action_type_id, event.value as 'total_revenue', event_meta.meta_value as 'item_ids', DATE_FORMAT(event.timestamp, '%Y-%m-%dT%TZ') as 'date', p.post_title as 'object_name', 'upsell'as 'type' FROM " . $wpdb->prefix . "wfocu_event as event 
			LEFT JOIN " . $wpdb->prefix . "wfocu_session as session ON event.sess_id=session.id 
			LEFT JOIN " . $wpdb->prefix . "posts as p ON event.object_id=p.id 
			LEFT JOIN " . $wpdb->prefix . "wfocu_event_meta as event_meta ON event.id=event_meta.event_id 
			WHERE(event.action_type_id=4 OR event.action_type_id=6 OR event.action_type_id=7 OR event.action_type_id=9) AND session.fid=" . $funnel_id . " AND session.cid=" . $cid . " AND event_meta.meta_key = '_items_added' order by session.timestamp asc";

			$order_data = $wpdb->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error   = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( ! is_array( $order_data ) || count( $order_data ) === 0 ) {
				return $item_data;
			}

			$all_item_ids = [];

			/** merge all items ids in one array */
			if ( is_array( $order_data ) && count( $order_data ) > 0 ) {
				foreach ( $order_data as &$i_array ) {
					$i_array->item_ids = ( isset( $i_array->item_ids ) && '' != $i_array->item_ids ) ? json_decode( $i_array->item_ids ) : [];
					if ( is_array( $i_array->item_ids ) && count( $i_array->item_ids ) > 0 ) {
						$all_item_ids = array_merge( $all_item_ids, $i_array->item_ids );
					}
				}
			}

			if ( is_array( $all_item_ids ) && count( $all_item_ids ) > 0 ) {
				/**
				 * get order item product name and quantity by items ids
				 */
				$item_query = "SELECT oi.order_item_id as 'item_id', oi.order_item_name as 'product_name', oim.meta_value as 'qty' FROM " . $wpdb->prefix . "woocommerce_order_items as oi LEFT JOIN " . $wpdb->prefix . "woocommerce_order_itemmeta as oim ON oi.order_item_id = oim.order_item_id WHERE oi.order_item_id IN (" . implode( ',', $all_item_ids ) . ") AND oi.order_item_type = 'line_item' AND oim.meta_key = '_qty' GROUP BY oi.order_item_id";
				$item_data  = $wpdb->get_results( $item_query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$db_error   = WFFN_Common::maybe_wpdb_error( $wpdb );
				if ( true === $db_error['db_error'] ) {
					return $db_error;
				}
			}

			foreach ( $order_data as &$order ) {
				$product_titles = [];
				$qty            = 0;
				if ( is_array( $order->item_ids ) && count( $order->item_ids ) > 0 && is_array( $item_data ) && count( $item_data ) > 0 ) {
					foreach ( $order->item_ids as $item_id ) {
						$search = array_search( intval( $item_id ), array_map( 'intval', wp_list_pluck( $item_data, 'item_id' ) ), true );
						if ( false !== $search && isset( $item_data[ $search ] ) ) {
							$product_titles[] = $item_data[ $search ]->product_name;
							$qty              += absint( $item_data[ $search ]->qty );
						}
					}

				}
				unset( $order->item_ids );
				$order->product_name = implode( ', ', $product_titles );
				$order->product_qty  = $qty;
			}

			return $order_data;

		}

		public function get_contacts_revenue_records( $cid, $order_ids ) {
			global $wpdb;
			$query = "SELECT session.fid as fid, session.order_id as order_id, event.object_id,event.action_type_id,event.value,DATE_FORMAT(event.timestamp, '%Y-%m-%d %T') as 'date',p.post_title as 'object_name','upsell' as 'type' FROM " . $wpdb->prefix . 'wfocu_event' . " as event LEFT JOIN " . $wpdb->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $wpdb->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.order_id IN ( $order_ids ) AND session.cid=$cid order by session.timestamp asc";

			$data     = $wpdb->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contact_record_by_cid( $cid ) {
			global $wpdb;
			$query = "SELECT session.order_id as order_id, event.object_id,event.action_type_id,event.value,DATE_FORMAT(event.timestamp, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name','upsell' as 'type' FROM " . $wpdb->prefix . 'wfocu_event' . " as event LEFT JOIN " . $wpdb->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $wpdb->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.cid=$cid order by session.timestamp asc";

			$data     = $wpdb->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( ! empty( $data[0]->order_id ) ) {
				$order_products = ! empty( wc_get_order( $data[0]->order_id ) ) ? wffn_rest_funnel_modules()->get_first_item( $data[0]->order_id ) : [];
				if ( ! empty( $order_products ) ) {
					$data[0]->product_name = $order_products['title'];
					$data[0]->product_qty  = $order_products['more'];
				}
			} else if ( ! empty( $data[0] ) ) {
				$data[0]->product_name = '';
				$data[0]->product_qty  = '';
			}

			return $data;

		}

		/**
		 * @param $order_id
		 *
		 * @return array|false|object|stdClass[]|null
		 */
		public function export_upsell_offer_by_order_id( $order_id ) {
			global $wpdb;
			$query = "SELECT event.object_id as 'id', session.order_id as 'order_id', p.post_title as 'offer_name', (CASE WHEN action_type_id = 4 THEN 'Yes' WHEN action_type_id = 6 THEN 'No' ELSE '' END) AS `offer_converted`, event.value as 'offer_total' FROM " . $wpdb->prefix . 'wfocu_event' . " as event LEFT JOIN " . $wpdb->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $wpdb->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.order_id='{$order_id}'  order by session.timestamp asc";

			$data     = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return false;
			}

			return $data;
		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 * @param $is_interval
		 * @param $int_request
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_total_revenue( $funnel_id, $start_date, $end_date, $is_interval = '', $int_request = '' ) {
			global $wpdb;
			$funnel_id = ( $funnel_id !== '' ) ? " AND fid = " . $funnel_id . " " : ' AND sess.fid != 0 ';
			$date      = ( '' !== $start_date && '' !== $end_date ) ? " AND ev.timestamp >= '" . $start_date . "' AND ev.timestamp < '" . $end_date . "' " : '';

			$interval_query = '';
			$group_by       = '';
			if ( class_exists( 'WFFN_REST_Controller' ) ) {
				$rest_con = new WFFN_REST_Controller();

				if ( 'interval' === $is_interval ) {
					$get_interval   = $rest_con->get_interval_format_query( $int_request, 'ev.timestamp' );
					$interval_query = $get_interval['interval_query'];
					$interval_group = $get_interval['interval_group'];
					$group_by       = " GROUP BY " . $interval_group;

				}
			}

			$query    = "SELECT SUM(ev.value) as sum_upsells " . $interval_query . " FROM `" . $wpdb->prefix . "wfocu_event` as ev LEFT JOIN `" . $wpdb->prefix . "wfocu_session` as sess on sess.id = ev.sess_id WHERE ev.action_type_id = 4 " . $funnel_id . " AND sess.total > 0  " . $date . $group_by . " ORDER BY sess.id DESC";
			$data     = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $limit
		 * @param string $order
		 * @param string $order_by
		 *
		 * @return string
		 */
		public function get_timeline_data_query( $limit, $order = 'DESC', $order_by = 'date' ) {
			global $wpdb;
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			return "SELECT stats.object_id as id, sess.fid as 'fid', sess.cid as 'cid', sess.order_id as 'order_id', CONVERT( stats.value USING utf8) as 'total_revenue', 'upsell' as 'type', posts.post_title as 'post_title', stats.timestamp as date FROM " . $wpdb->prefix . "wfocu_event AS stats LEFT JOIN " . $wpdb->prefix . "wfocu_session AS sess ON stats.sess_id=sess.id LEFT JOIN " . $wpdb->prefix . "posts AS posts ON stats.object_id=posts.ID where ( stats.action_type_id = 4) AND sess.cid IS NOT NULL ORDER BY " . $order_by . " " . $order . " " . $limit;

		}

		/**
		 * @param $limit
		 * @param $date_query
		 *
		 * @return array|false[]|object|stdClass[]|null
		 */
		public function get_top_funnels( $limit = '', $date_query = '' ) {
			global $wpdb;
			$limit      = ( $limit !== '' ) ? " LIMIT " . $limit : '';
			$date_query = str_replace( '{{COLUMN}}', 'sess.timestamp', $date_query );
			$query      = "SELECT funnel.id as fid, funnel.title as title, stats.total as total FROM " . $wpdb->prefix . "bwf_funnels AS funnel 
			JOIN ( SELECT sess.fid as fid, SUM(ev.value) as total FROM " . $wpdb->prefix . "wfocu_event as ev 
			LEFT JOIN " . $wpdb->prefix . "wfocu_session as sess on sess.id = ev.sess_id 
			WHERE ev.action_type_id = 4 AND sess.fid != 0 AND sess.total > 0 AND " . $date_query . " GROUP BY fid ) as stats ON funnel.id = stats.fid WHERE 1=1  GROUP BY funnel.id ORDER BY total DESC  " . $limit;

			$data     = $wpdb->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $cids
		 * @param $funnel_id
		 *
		 * @return array|false[]|true
		 */
		public function delete_contact( $cids, $funnel_id = 0 ) {
			global $wpdb;
			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );
			$funnel_query             = ( absint( $funnel_id ) > 0 ) ? " AND fid = " . $funnel_id . " " : '';

			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return true;
			}

			$query    = "SELECT id  FROM " . $wpdb->prefix . "wfocu_session WHERE cid IN (" . $placeholdersForFavFruits . ") " . $funnel_query;
			$sess_ids = $wpdb->get_results( $wpdb->prepare( $query, $cids ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( is_array( $sess_ids ) && count( $sess_ids ) > 0 ) {
				foreach ( $sess_ids as $sess_id ) {
					WFOCU_Core()->session_db->delete( $sess_id['id'] );
				}
			}

			return true;
		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {
			global $wpdb;
			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return;
			}
			$query    = "SELECT id  FROM " . $wpdb->prefix . "wfocu_session WHERE fid =" . $funnel_id;
			$sess_ids = $wpdb->get_results( $query, ARRAY_A );
			if ( is_array( $sess_ids ) && count( $sess_ids ) > 0 ) {
				foreach ( $sess_ids as $sess_id ) {
					WFOCU_Core()->session_db->delete( $sess_id['id'] );
				}
			}
			$query = "DELETE FROM " . $wpdb->prefix . "wfocu_session WHERE fid=" . $funnel_id;
			$wpdb->query( $query );

			$all_upsell_funnel_ids = [];
			$funnel                = new WFFN_Funnel( $funnel_id );
			$rest_API              = WFFN_REST_API_EndPoint::get_instance();
			if ( $funnel instanceof WFFN_Funnel && 0 < $funnel->get_id() ) {
				$get_steps = $funnel->get_steps();
				$get_steps = $rest_API::get_instance()->maybe_add_ab_variants( $get_steps );
				foreach ( $get_steps as $step ) {
					if ( $step['type'] === 'wc_upsells' ) {
						array_push( $all_upsell_funnel_ids, $step['id'] );
					}
				}
			}


			if ( count( $all_upsell_funnel_ids ) > 0 ) {
				$wpdb->get_results( "DELETE eventss FROM " . $wpdb->prefix . "wfocu_event as eventss
INNER JOIN " . $wpdb->prefix . "wfocu_event_meta AS events_meta__funnel_id ON ( eventss.ID = events_meta__funnel_id.event_id ) 
 

			                        AND
( ( events_meta__funnel_id.meta_key   = '_funnel_id' AND events_meta__funnel_id.meta_value IN (" . implode( ',', $all_upsell_funnel_ids ) . ") ))" );

			}


		}
	}
}