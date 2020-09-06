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

		/**
		 * load textdomain
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'mcq-test', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Include files
		 */
		function include_classes_functions() {

			require_once MCQ_PLUGIN_DIR . 'includes/classes/class-pb-settings-3.2.php';
			require_once MCQ_PLUGIN_DIR . 'includes/classes/class-hooks.php';
			require_once MCQ_PLUGIN_DIR . 'includes/classes/class-functions.php';
			require_once MCQ_PLUGIN_DIR . 'includes/classes/class-meta-boxes.php';
			require_once MCQ_PLUGIN_DIR . 'includes/classes/class-columns.php';
			require_once MCQ_PLUGIN_DIR . 'includes/classes/class-item-data.php';
			require_once MCQ_PLUGIN_DIR . 'includes/classes/class-item-question.php';

			require_once MCQ_PLUGIN_DIR . 'includes/functions.php';
		}

		/**
		 * Return data that will pass on pluginObject
		 *
		 * @return array
		 */
		function localize_scripts_data() {

			return array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'workingText' => esc_html__( 'Working...', 'mcq-test' ),
				'copyText'    => esc_html__( 'Copied !', 'mcq-test' ),
				'voteText'    => esc_html__( 'Vote(s)', 'mcq-test' ),
				'confirmText' => esc_html__( 'Do you really wanted to remove this option?', 'mcq-test' ),
			);
		}


		/**
		 * Admin scripts
		 */
		function admin_scripts() {

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_style( 'mcq-admin', MCQ_PLUGIN_URL . 'assets/admin/css/style.css', array(), date( 'H:s' ) );

			wp_enqueue_script( 'mcq-admin', plugins_url( 'assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ), date( 'H:s' ) );
			wp_localize_script( 'mcq-admin', 'mcq_object', $this->localize_scripts_data() );
		}


		/**
		 * Front scripts
		 */
		function front_scripts() {

			wp_enqueue_style( 'mcq-front', MCQ_PLUGIN_URL . 'assets/front/css/style.css', array(), date( 'g:s' ) );
			wp_enqueue_script( 'mcq-front', plugins_url( 'assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), date( 'H:s' ) );
			wp_localize_script( 'mcq-front', 'mcq_object', $this->localize_scripts_data() );
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