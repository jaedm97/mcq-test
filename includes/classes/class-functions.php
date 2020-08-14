<?php
/**
 * Class Functions
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'MCQ_Functions' ) ) {
	class MCQ_Functions {

		public function mcq_fn_get_area() {
			$mcq_area = array(
				''  => 'Select Customer Area',
				101 => 'Jahaj Company Moor',
				201 => 'Shapla',
				202 => 'Lalbag',
				203 => 'Modern',
				204 => 'Park Moor',
				205 => 'Modern',
				206 => 'Doshona',
			);

			return $mcq_area;
		}

		public function mcq_fn_get_customer_type() {
			return array(
				''         => 'Select Customer Type',
				'7-days'   => '7 Days',
				'15-days'  => '15 Days',
				'30-days'  => '30 Days',
				'one-time' => 'One Time',
			);
		}

		public function mcq_fn_get_customer_status() {
			return array(
				'' => 'Select Customer Status',
				9  => 'Hold',
				1  => 'Active',
				7  => 'Suspend',

			);
		}

		public function mcq_delivery_time() {
			return array(
				''   => 'Select Delivery Time',
				't1' => array(
					't_name'  => 'Schedule 1',
					't_start' => 9,
					't_end'   => 11,
				),
				't2' => array(
					't_name'  => 'Schedule 2',
					't_start' => 13,
					't_end'   => 15,
				),
				't3' => array(
					't_name'  => 'Schedule 3',
					't_start' => 18,
					't_end'   => 20,
				),
			);
		}


		/**
		 * PB_Settings Class
		 *
		 * @param array $args
		 *
		 * @return PB_Settings
		 */
		function PB_Settings( $args = array() ) {

			return new PB_Settings( $args );
		}


		/**
		 * Print notice to the admin bar
		 *
		 * @param string $message
		 * @param bool $is_success
		 * @param bool $is_dismissible
		 */
		function print_notice( $message = '', $is_success = true, $is_dismissible = true ) {

			if ( empty ( $message ) ) {
				return;
			}

			if ( is_bool( $is_success ) ) {
				$is_success = $is_success ? 'success' : 'error';
			}

			printf( '<div class="notice notice-%s %s"><p>%s</p></div>', $is_success, $is_dismissible ? 'is-dismissible' : '', $message );
		}


		/**
		 * Return Post Meta Value
		 *
		 * @param bool $meta_key
		 * @param bool $post_id
		 * @param string $default
		 *
		 * @return mixed|string|void
		 */
		function get_meta( $meta_key = false, $post_id = false, $default = '' ) {

			if ( ! $meta_key ) {
				return '';
			}

			$post_id    = ! $post_id ? get_the_ID() : $post_id;
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			$meta_value = empty( $meta_value ) ? $default : $meta_value;

			return apply_filters( 'woc_filters_get_meta', $meta_value, $meta_key, $post_id, $default );
		}

		/**
		 * Return option value
		 *
		 * @param string $option_key
		 * @param string $default_val
		 *
		 * @return mixed|string|void
		 */
		function get_option( $option_key = '', $default_val = '' ) {

			if ( empty( $option_key ) ) {
				return '';
			}

			$option_val = get_option( $option_key, $default_val );
			$option_val = empty( $option_val ) ? $default_val : $option_val;

			return apply_filters( 'woc_filters_option_' . $option_key, $option_val );
		}


		/**
		 * Return Arguments Value
		 *
		 * @param string $key
		 * @param string $default
		 * @param array $args
		 *
		 * @return mixed|string
		 */
		function get_args_option( $key = '', $default = '', $args = array() ) {

			global $mcq_test_args;

			$args    = empty( $args ) ? $mcq_test_args : $args;
			$default = empty( $default ) && ! is_array($default) ? '' : $default;
			$key     = empty( $key ) ? '' : $key;

			if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
				return $args[ $key ];
			}

			return $default;
		}
	}

	global $mcq_test;
	$mcq_test = new MCQ_Functions();
}