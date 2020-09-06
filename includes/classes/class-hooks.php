<?php
/**
 * Class Hooks
 */


if ( ! class_exists( 'MCQ_Hooks' ) ) {
	/**
	 * Class MCQ_Hooks
	 */
	class MCQ_Hooks {


		/**
		 * MCQ_Hooks constructor.
		 */
		function __construct() {

			if ( ! is_admin() ) {
				add_action( 'init', array( $this, 'ob_start' ) );
				add_action( 'wp_footer', array( $this, 'ob_end' ) );
			}

			add_action( 'init', array( $this, 'register_everything' ) );
			add_action( 'wp_ajax_get_option_field', array( $this, 'get_option_field' ) );
			add_filter( 'the_content', array( $this, 'render_question_content' ) );

			add_action( 'mcq-importer', array( $this, 'render_importer' ) );
		}

		/**
		 * @param $content
		 *
		 * @return false|string
		 */
		function render_question_content( $content ) {

			if ( is_singular( 'question' ) ) {
				ob_start();
				mcq_get_template( 'single-question.php' );

				return ob_get_clean();
			}

			return $content;
		}


		/**
		 * Render importer in backend
		 */
		function render_importer() {
			mcq_get_template( 'importer-main.php' );
		}


		/**
		 * Return content for single option field through ajax request
		 */
		function get_option_field() {
			ob_start();
			render_single_option_field();
			wp_send_json_success( ob_get_clean() );
		}


		/**
		 * Render All questions
		 *
		 * @return false|string
		 */
		function render_questions() {

			ob_start();
			mcq_get_template( 'all-questions.php' );

			return ob_get_clean();
		}

		/**
		 * Register Post Types and Settings
		 */
		function register_everything() {

			/**
			 * Register Post Types
			 */
			mcq_test()->PB_Settings()->register_post_type( 'question', array(
				'singular'  => esc_html__( 'Question', 'mcq-test' ),
				'plural'    => esc_html__( 'Questions', 'mcq-test' ),
				'labels'    => array(
					'menu_name' => esc_html__( 'MCQ Panel', 'mcq-test' ),
				),
				'menu_icon' => 'dashicons-awards',
				'supports'  => array( 'title' ),
			) );

			mcq_test()->PB_Settings()->register_taxonomy( 'question_cat', 'question', array(
				'singular' => esc_html__( 'Category', 'mcq-test' ),
				'plural'   => esc_html__( 'Categories', 'mcq-test' ),
			) );


			mcq_test()->PB_Settings( array(
				'add_in_menu'     => true,
				'menu_type'       => 'submenu',
				'menu_title'      => esc_html__( 'Importer', 'mcq-test' ),
				'page_title'      => esc_html__( 'Importer', 'mcq-test' ),
				'menu_page_title' => esc_html__( 'Importer Panel', 'mcq-test' ),
				'capability'      => 'manage_options',
				'menu_slug'       => 'mcq-importer',
				'parent_slug'     => "edit.php?post_type=question",
				'pages'           => array(
					'mcq-importer' => array(
						'page_nav'    => esc_html__( 'Import Questions', 'mcq-test' ),
						'show_submit' => false,
					),
				),
			) );

			mcq_test()->PB_Settings()->register_shortcode( 'mcq-questions', array( $this, 'render_questions' ) );
		}


		/**
		 * Return Buffered Content
		 *
		 * @param $buffer
		 *
		 * @return mixed
		 */
		function ob_callback( $buffer ) {
			return $buffer;
		}


		/**
		 * Start of Output Buffer
		 */
		function ob_start() {
			ob_start( array( $this, 'ob_callback' ) );
		}


		/**
		 * End of Output Buffer
		 */
		function ob_end() {
			if ( ob_get_length() ) {
				ob_end_flush();
			}
		}
	}

	new MCQ_Hooks();
}