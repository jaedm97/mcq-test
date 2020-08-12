<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	add_action( 'mcq_action_single_question', 'mcq_template_single_question_title', 10 );
	add_action( 'mcq_action_single_question', 'mcq_template_single_question_meta', 10 );
	add_action( 'mcq_action_single_question', 'mcq_template_single_question_content', 20 );	
	add_action( 'mcq_action_single_question', 'mcq_template_single_question_pagination', 20 );	
	add_action( 'mcq_action_single_question', 'mcq_template_single_question_css', 20 );	


	if ( ! function_exists( 'mcq_template_single_question_title' ) ) {
		function mcq_template_single_question_title() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question-title.php');
		}
	}
	
	if ( ! function_exists( 'mcq_template_single_question_meta' ) ) {
		function mcq_template_single_question_meta() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question-meta.php');
		}
	}

	if ( ! function_exists( 'mcq_template_single_question_content' ) ) {
		function mcq_template_single_question_content() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question-content.php');
		}
	}
	
	if ( ! function_exists( 'mcq_template_single_question_pagination' ) ) {
		function mcq_template_single_question_pagination() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question-pagination.php');
		}
	}
	
	if ( ! function_exists( 'mcq_template_single_question_css' ) ) {
		function mcq_template_single_question_css() {
			require_once( MCQ_PLUGIN_DIR. 'templates/style.php');
		}
	}
	

