<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mcq_settings{
	
	public function __construct(){
		
		add_action('admin_menu', array( $this, 'mcq_menu_init' ), 10);
		
	}

	public function mcq_menu_settings(){
		include('menu/settings.php');	
	}

	public function mcq_menu_init() {
		add_menu_page('MCQ Test', 'MCQ Test', 'manage_options', 'given_test', 'mcq_test_output', 'dashicons-awards', 20 );
		add_submenu_page('given_test', __('Settings',MCQ_TEXTDOMAIN), __('Settings',MCQ_TEXTDOMAIN), 'manage_options', 'mcq_menu_settings', array( $this, 'mcq_menu_settings' ));	
	}
	
	
} new class_mcq_settings();