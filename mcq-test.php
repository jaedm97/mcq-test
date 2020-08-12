<?php
/*
	Plugin Name: Multiple Choice Questions
	Plugin URI: https://pluginbazar.com/
	Description: Make online education system better within you WordPress website
	Version: 2.0.0
	Author: Pluginbazar
	Author URI: https://pluginbazar.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;
defined( 'MCQ_PLUGIN_URL' ) || define( 'MCQ_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
defined( 'MCQ_PLUGIN_DIR' ) || define( 'MCQ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'MCQ_PLUGIN_NAME' ) || define( 'MCQ_PLUGIN_NAME', 'MCQ Model Test' );
defined( 'MCQ_PLUGIN_VER' ) || define( 'MCQ_PLUGIN_VER', '1.0.0' );


if ( ! class_exists( 'MultipleChoiceQuestionsMain' ) ) {
	/**
	 * Class MultipleChoiceQuestionsMain
	 */
	class MultipleChoiceQuestionsMain {

		/**
		 * MultipleChoiceQuestionsMain constructor.
		 */
		function __construct() {

			$this->enqueue_scripts();
			$this->include_classes_functions();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}

		function load_textdomain() {
			load_plugin_textdomain( 'mcq-test', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Include files
		 */
		function include_classes_functions() {
			require_once MCQ_PLUGIN_URL . 'includes/functions.php';

			require_once MCQ_PLUGIN_URL . 'includes/class-post-types.php';
			require_once MCQ_PLUGIN_URL . 'includes/class-post-meta.php';
			require_once MCQ_PLUGIN_URL . 'includes/class-post-meta-participant.php';
			require_once MCQ_PLUGIN_URL . 'includes/class-shortcodes.php';
			require_once MCQ_PLUGIN_URL . 'includes/class-settings.php';
			require_once MCQ_PLUGIN_URL . 'includes/class-question-column.php';

			require_once MCQ_PLUGIN_URL . 'includes/actions/action-single-question.php';

			require_once MCQ_PLUGIN_URL . 'templates/single-question-template-functions.php';
			require_once MCQ_PLUGIN_URL . 'templates/sidebar.php';
		}

		/**
		 * Admin scripts
		 */
		function admin_scripts() {

			wp_enqueue_script( 'mcq-back-js', plugins_url( 'resources/back/js/scripts.js', __FILE__ ), array( 'jquery' ) );

			wp_enqueue_style( 'BackAdmin', MCQ_PLUGIN_URL . 'resources/back/css/BackAdmin.css' );
			wp_enqueue_script( 'BackAdmin', plugins_url( 'resources/back/js/BackAdmin.js', __FILE__ ), array( 'jquery' ) );

			wp_enqueue_style( 'ecom-fonts', MCQ_PLUGIN_URL . 'resources/both/css/font-awesome.css' );

			wp_enqueue_style( 'mcq_admin_style', MCQ_PLUGIN_URL . 'resources/back/css/style.css' );
		}

		/**
		 * Front scripts
		 */
		function front_scripts() {
			wp_enqueue_style( 'ecom-fonts', MCQ_PLUGIN_URL . 'resources/both/css/font-awesome.css' );
			wp_enqueue_script( 'ecom-js', plugins_url( '/resources/front/js/scripts.js', __FILE__ ), array( 'jquery' ) );

			wp_enqueue_style( 'ecom-style', MCQ_PLUGIN_URL . 'resources/front/css/style.css' );
			wp_localize_script( 'ecom-js', 'mcq_ajax', array( 'mcq_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

			wp_enqueue_style( 'jquery-confirm.min', MCQ_PLUGIN_URL . 'resources/front/css/jquery-confirm.min.css' );
			wp_enqueue_script( 'jquery-confirm.min', plugins_url( '/resources/front/js/jquery-confirm.min.js', __FILE__ ), array( 'jquery' ) );

			wp_enqueue_script( 'jquery.steps', plugins_url( '/resources/front/js/jquery.steps.js', __FILE__ ), array( 'jquery' ) );
		}

		/**
		 * Enqueue Scripts
		 */
		function enqueue_scripts() {
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}
	}

	new MultipleChoiceQuestionsMain();
}