<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

	if ( ! defined('ABSPATH')) exit;  // if direct access 

	add_action( 'mcq_action_single_question_title', 'mcq_action_single_question_title_function', 10 );
	add_action( 'mcq_action_single_question_meta', 'mcq_action_single_question_meta_function', 10 );
	add_action( 'mcq_action_single_question_optins', 'mcq_action_single_question_optins_function', 10 );
	add_action( 'mcq_action_single_question_buttons', 'mcq_action_single_question_buttons_function', 10 );
	add_action( 'mcq_action_single_question_related', 'mcq_action_single_question_related_function', 10 );
	
	if ( ! function_exists( 'mcq_action_single_question_title_function' ) ) {
		function mcq_action_single_question_title_function() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question/title.php');
		}
	}
	
	if ( ! function_exists( 'mcq_action_single_question_meta_function' ) ) {
		function mcq_action_single_question_meta_function() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question/meta.php');
		}
	}
	
	if ( ! function_exists( 'mcq_action_single_question_optins_function' ) ) {
		function mcq_action_single_question_optins_function() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question/options.php');
		}
	}
	
	if ( ! function_exists( 'mcq_action_single_question_buttons_function' ) ) {
		function mcq_action_single_question_buttons_function() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question/buttons.php');
		}
	}
	
	if ( ! function_exists( 'mcq_action_single_question_related_function' ) ) {
		function mcq_action_single_question_related_function() {
			require_once( MCQ_PLUGIN_DIR. 'templates/single-question/related.php');
		}
	}
	
	
	