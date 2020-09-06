<?php
/**
 * MCQ Meta data
 */


if ( ! class_exists( 'MCQ_Meta_data' ) ) {
	/**
	 * Class MCQ_Meta_data
	 */
	class MCQ_Meta_data {
		/**
		 * MCQ_Meta_data constructor.
		 */
		function __construct() {

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_meta_data' ) );
		}


		/**
		 * Save meta data
		 *
		 * @param $post_id
		 */
		function save_meta_data( $post_id ) {

			$posted_data = wp_unslash( $_POST );

			if ( wp_verify_nonce( mcq_test()->get_args_option( 'question_nonce_val', '', $posted_data ), 'question_nonce' ) ) {
				update_post_meta( $post_id, '_question_options', mcq_test()->get_args_option( '_question_options', array(), $posted_data ) );
				update_post_meta( $post_id, '_question_answers', mcq_test()->get_args_option( '_question_answers', array(), $posted_data ) );
			}
		}


		/**
		 * Meta box output
		 *
		 * @param $post
		 */
		public function question_meta( $post ) {

			wp_nonce_field( 'question_nonce', 'question_nonce_val' );
			mcq_get_template( 'admin/meta-box-question.php' );
		}


		/**
		 * Add meta boxes
		 *
		 * @param $post_type
		 */
		public function add_meta_boxes( $post_type ) {

			if ( in_array( $post_type, array( 'question' ) ) ) {
				add_meta_box( 'question_meta', esc_html__( 'Question Data', 'mcq-test' ), array( $this, 'question_meta' ), $post_type, 'normal', 'high' );
			}
		}
	}

	new MCQ_Meta_data();
}