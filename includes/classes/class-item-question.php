<?php
/**
 * Single Question class
 */


if ( ! class_exists( 'MCQ_Question' ) ) {
	/**
	 * Class MCQ_Question
	 */
	class MCQ_Question extends MCQ_Item_data {

		/**
		 * MCQ_Question constructor.
		 *
		 * @param false $question_id
		 */
		function __construct( $question_id = false ) {
			parent::__construct( $question_id );

			$this->set_taxonomy( 'question_cat' );
		}


		/**
		 * Return array of related questions
		 *
		 * @param array $args
		 *
		 * @return mixed|void
		 */
		function get_related_questions( $args = array() ) {

			$defaults    = array(
				'exclude'   => array( $this->get_id() ),
				'post_type' => $this->get_post()->post_type,
				'fields'    => 'ids',
				'tax_query' => array(
					array(
						'taxonomy' => $this->get_taxonomy(),
						'field'    => 'term_taxonomy_id',
						'terms'    => $this->get_terms( array( 'fields' => 'ids' ) ),
						'operator' => 'IN',
					)
				),
			);
			$parsed_args = wp_parse_args( $args, $defaults );

			return apply_filters( 'mcq_filters_get_related_questions', get_posts( $parsed_args ) );
		}


		/**
		 * Return question options list in html
		 *
		 * @param bool $shuffle
		 *
		 * @return mixed|void
		 */
		function get_options_list( $shuffle = true ) {

			$question_options = array_map( function ( $option ) {
				$value      = mcq_test()->get_args_option( 'value', '', $option );
				$is_correct = mcq_test()->get_args_option( 'is_correct', false, $option );

				return sprintf( '<li class="%s correct-visible">%s</li>', $is_correct ? 'correct' : '', $value );
			}, $this->get_options( $shuffle ) );

			return apply_filters( 'mcq_filters_question_options_list', $question_options );
		}


		/**
		 * Return question options array
		 *
		 * @param bool $shuffle
		 *
		 * @return mixed|void
		 */
		function get_options( $shuffle = true ) {

			$question_options = mcq_test()->get_meta( '_question_options', false, array() );

			if ( $shuffle ) {
				shuffle( $question_options );
			}

			return apply_filters( 'mcq_filters_question_options', $question_options );
		}

	}

	new MCQ_Question();
}