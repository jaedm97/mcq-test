<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	//add_action( 'mcq_action_sidebar', 'mcq_action_sidebar_section', 10 );
	//add_action( 'mcq_action_single_question', 'mcq_template_single_question_meta', 10 );
	//add_action( 'mcq_action_single_question', 'mcq_template_single_question_content', 20 );	
	//add_action( 'mcq_action_single_question', 'mcq_template_single_question_css', 20 );	


	if ( ! function_exists( 'mcq_action_sidebar_section' ) ) {
		function mcq_action_sidebar_section() {
			//require_once( mcq_plugin_dir. 'templates/single-question-title.php');
			
			echo '<div class="sidebar">Jaed Mosharraf</div>';
		}
	}
	
	if ( ! function_exists( 'mcq_template_single_question_meta' ) ) {
		function mcq_template_single_question_meta() {
			require_once( mcq_plugin_dir. 'templates/single-question-meta.php');
		}
	}

	if ( ! function_exists( 'mcq_template_single_question_content' ) ) {
		function mcq_template_single_question_content() {
			require_once( mcq_plugin_dir. 'templates/single-question-content.php');
		}
	}
	
	if ( ! function_exists( 'mcq_template_single_question_css' ) ) {
		function mcq_template_single_question_css() {
			require_once( mcq_plugin_dir. 'templates/style.php');
		}
	}
	

