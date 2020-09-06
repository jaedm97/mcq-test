<?php
/**
 * Post list columns
 */


if ( ! class_exists( 'MCQ_Columns' ) ) {
	/**
	 * Class MCQ_Columns
	 */
	class MCQ_Columns {
		/**
		 * MCQ_Columns constructor.
		 */
		function __construct() {
			add_action( 'manage_question_posts_columns', array( $this, 'add_columns' ), 16, 1 );
			add_action( 'manage_question_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );
		}


		/**
		 * Display column content in post list page
		 *
		 * @param $column
		 * @param $post_id
		 */
		function columns_content( $column, $post_id ) {

		}


		/**
		 * Add columns to post list page
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		function add_columns( $columns ) {

			return array(
				'cb'    => mcq_test()->get_args_option( 'cb', '', $columns ),
				'title' => esc_html__( 'Questions Title', 'mcq-test' ),
			);
		}
	}

	new MCQ_Columns();
}