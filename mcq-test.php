<?php
/*
	Plugin Name: MCQ Test
	Plugin URI: http://pluginbazar.ml/
	Description: This is a plug-in for Managing a MCQ webapps.
	Version: 1.2.1
	Author: pluginbazar
	Author URI: http://pluginbazar.ml/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class MCQ{
	
	public function __construct(){
	
	define('mcq_plugin_url', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	define('mcq_plugin_dir', plugin_dir_path( __FILE__ ) );
	define('mcq_plugin_name', 'MCQ Model Test' );
	define('mcq_plugin_version', '1.0.0' );
	define('MCQ_TEXTDOMAIN', 'mcq-test' );
	define('QUESTION_PER_QUIZ', 15 );
		
	require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
	
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-post-types.php');	
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-post-meta.php');	
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-post-meta-participant.php');	
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-shortcodes.php');	
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-settings.php');	
	require_once( plugin_dir_path( __FILE__ ) . 'includes/class-question-column.php');	
	
	//require_once( plugin_dir_path( __FILE__ ) . 'templates/single-question.php');
	
	$this->mcq_loading_actions();
	
	
	require_once( plugin_dir_path( __FILE__ ) . 'templates/single-question-template-functions.php');	
	require_once( plugin_dir_path( __FILE__ ) . 'templates/sidebar.php');	
	
	add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
	add_action( 'wp_enqueue_scripts', array( $this, 'mcq_front_scripts' ) );
	add_action( 'admin_enqueue_scripts', array( $this, 'mcq_admin_scripts' ) );
	
	}
	
	public function mcq_loading_actions() {
		
		require_once( plugin_dir_path( __FILE__ ) . 'includes/actions/action-single-question.php');	
	}
	
	
	public function mcq_front_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_style('ecom-fonts', mcq_plugin_url.'resources/both/css/font-awesome.css');
		wp_enqueue_script('ecom-js', plugins_url( '/resources/front/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
	
		wp_enqueue_style('ecom-style', mcq_plugin_url.'resources/front/css/style.css');
		wp_localize_script( 'ecom-js', 'mcq_ajax', array( 'mcq_ajaxurl' => admin_url( 'admin-ajax.php')));
		
		wp_enqueue_style('jquery-confirm.min', mcq_plugin_url.'resources/front/css/jquery-confirm.min.css');
		wp_enqueue_script('jquery-confirm.min', plugins_url( '/resources/front/js/jquery-confirm.min.js' , __FILE__ ) , array( 'jquery' ));
		
		wp_enqueue_script('jquery.steps', plugins_url( '/resources/front/js/jquery.steps.js' , __FILE__ ) , array( 'jquery' ));				
		
	}

	public function mcq_admin_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		
		wp_enqueue_script('mcq-back-js', plugins_url( 'resources/back/js/scripts.js' , __FILE__ ) , array( 'jquery' ));
		
		wp_enqueue_style('BackAdmin', mcq_plugin_url.'resources/back/css/BackAdmin.css');		
		wp_enqueue_script('BackAdmin', plugins_url( 'resources/back/js/BackAdmin.js' , __FILE__ ) , array( 'jquery' ));
		
		wp_enqueue_style('ecom-fonts', mcq_plugin_url.'resources/both/css/font-awesome.css');
		
		wp_enqueue_style('mcq_admin_style', mcq_plugin_url.'resources/back/css/style.css');
	}
} new MCQ();