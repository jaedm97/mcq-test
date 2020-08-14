<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! function_exists( 'mcq_test' ) ) {
	function mcq_test() {

		global $mcq_test;

		if ( empty( $mcq_test ) ) {
			$mcq_test = new MCQ_Functions();
		}

		return $mcq_test;
	}
}


if ( ! function_exists( 'mcq_get_template_part' ) ) {
	/**
	 * Get Template Part
	 *
	 * @param $slug
	 * @param string $name
	 * @param array $args
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 */
	function mcq_get_template_part( $slug, $name = '', $args = array(), $main_template = false ) {

		$template   = '';
		$plugin_dir = MCQ_PLUGIN_DIR;

		/**
		 * Locate template
		 */
		if ( $name ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				"wpp/{$slug}-{$name}.php"
			) );
		}

		/**
		 * Check directory for templates from Addons
		 */
		$backtrace      = debug_backtrace( 2, true );
		$backtrace      = empty( $backtrace ) ? array() : $backtrace;
		$backtrace      = reset( $backtrace );
		$backtrace_file = isset( $backtrace['file'] ) ? $backtrace['file'] : '';

		// Search in Poll Pro
		if ( strpos( $backtrace_file, 'wp-poll-pro' ) !== false && defined( 'WPPP_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? MCQ_PLUGIN_DIR : WPPP_PLUGIN_DIR;
		}

		// Search in Survey
		if ( strpos( $backtrace_file, 'wp-poll-survey' ) !== false && defined( 'WPPS_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? MCQ_PLUGIN_DIR : WPPS_PLUGIN_DIR;
		}


		/**
		 * Search for Template in Plugin
		 *
		 * @in Plugin
		 */
		if ( ! $template && $name && file_exists( untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php" ) ) {
			$template = untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php";
		}


		/**
		 * Search for Template in Theme
		 *
		 * @in Theme
		 */
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", "wpp/{$slug}.php" ) );
		}


		/**
		 * Allow 3rd party plugins to filter template file from their plugin.
		 *
		 * @filter mcq_filters_get_template_part
		 */
		$template = apply_filters( 'mcq_filters_get_template_part', $template, $slug, $name );


		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'mcq_get_template' ) ) {
	/**
	 * Get Template
	 *
	 * @param $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return WP_Error
	 */
	function mcq_get_template( $template_name, $args = array(), $template_path = '', $default_path = '', $main_template = false ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		/**
		 * Check directory for templates from Addons
		 */
		$backtrace      = debug_backtrace( 2, true );
		$backtrace      = empty( $backtrace ) ? array() : $backtrace;
		$backtrace      = reset( $backtrace );
		$backtrace_file = isset( $backtrace['file'] ) ? $backtrace['file'] : '';

		$located = mcq_locate_template( $template_name, $template_path, $default_path, $backtrace_file, $main_template );


		if ( ! file_exists( $located ) ) {
			return new WP_Error( 'invalid_data', __( '%s does not exist.', 'wp-poll' ), '<code>' . $located . '</code>' );
		}

		$located = apply_filters( 'mcq_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'mcq_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'mcq_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'mcq_locate_template' ) ) {
	/**
	 *  Locate template
	 *
	 * @param $template_name
	 * @param string $template_path
	 * @param string $default_path
	 * @param string $backtrace_file
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return mixed|void
	 */
	function mcq_locate_template( $template_name, $template_path = '', $default_path = '', $backtrace_file = '', $main_template = false ) {

		$plugin_dir = MCQ_PLUGIN_DIR;

		/**
		 * Template path in Theme
		 */
		if ( ! $template_path ) {
			$template_path = 'wpp/';
		}

		// Check for Poll Pro
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-pro' ) !== false && defined( 'WPPP_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? MCQ_PLUGIN_DIR : WPPP_PLUGIN_DIR;
		}

		// Check for survey
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-survey' ) !== false && defined( 'WPPS_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? MCQ_PLUGIN_DIR : WPPS_PLUGIN_DIR;
		}

		// Check for MCQ
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-quiz' ) !== false && defined( 'WPPQUIZ_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? MCQ_PLUGIN_DIR : WPPQUIZ_PLUGIN_DIR;
		}


		/**
		 * Template default path from Plugin
		 */
		if ( ! $default_path ) {
			$default_path = untrailingslashit( $plugin_dir ) . '/templates/';
		}

		/**
		 * Look within passed path within the theme - this is priority.
		 */
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		/**
		 * Get default template
		 */
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		/**
		 * Return what we found with allowing 3rd party to override
		 *
		 * @filter mcq_filters_locate_template
		 */
		return apply_filters( 'mcq_filters_locate_template', $template, $template_name, $template_path );
	}
}


if ( ! function_exists( 'render_single_option_field' ) ) {
	/**
	 * Render single option field
	 *
	 * @param string $option
	 * @param array $question_answers
	 */
	function render_single_option_field( $option = '', $question_answers = array() ) {

		ob_start();

		printf( '<input type="text" name="_question_options[]" placeholder="Write Option..." value="%s"/>', $option );
		printf( '<span class="dashicons dashicons-no-alt mcq-option-remove"></span>' );
		printf( '<label class="dashicons dashicons-saved mcq-option-correct %s"><input type="checkbox" name="_question_answers[]" value="%s"></label>',
			is_array( $question_answers ) && in_array( $option, $question_answers ) ? 'correct' : '', $option
		);
		printf( '<div class="question-option">%s</div>', ob_get_clean() );
	}
}


add_filter( 'single_template', 'my_single_template' );

function my_single_template( $single ) {

	global $wp_query, $post;

	foreach ( (array) get_the_category() as $cat ) :

		if ( file_exists( MCQ_PLUGIN_DIR . 'templates/single-question_cat-' . $cat->slug . '.php' ) ) {
			return MCQ_PLUGIN_DIR . 'templates/single-question_cat-' . $cat->slug . '.php';
		} elseif ( file_exists( MCQ_PLUGIN_DIR . 'templates/single-question_cat-' . $cat->term_id . '.php' ) ) {
			return MCQ_PLUGIN_DIR . 'templates/single-question_cat-' . $cat->term_id . '.php';
		}

	endforeach;

}


function mcq_get_categories( $taxonamy = '' ) {
	$args    = array(
		'show_option_none' => __( 'Select category' ),
		'hide_empty'       => 0,
		'hierarchical'     => true,
		'order'            => 'ASC',
		'orderby'          => 'name',
		'taxonomy'         => 'question_cat',

	);
	$cat     = get_terms( $args );
	$cat_arr = array();
	foreach ( $cat as $cat_details ) {
		if ( $cat_details->parent == 0 ) {
			$cat_arr[ $cat_details->term_id ]['name'] = $cat_details->name;
		} else {
			$cat_arr[ $cat_details->parent ][ $cat_details->term_id ] = $cat_details->name;
		}
	}

	return $cat_arr;
}

function mcq_ajax_start_test() {
	$p_name  = $_POST['p_name'];
	$p_email = $_POST['p_email'];

	$html = '';

	$wp_query = new WP_Query( array(
		'post_type'  => 'participant',
		'meta_query' => array(
			array(
				'key'     => 'participant_email',
				'value'   => $p_email,
				'compare' => '=',
			),
		),
	) );

	$p_id = '';
	if ( $wp_query->have_posts() ):while ( $wp_query->have_posts() ) : $wp_query->the_post();
		$p_id .= get_the_ID();
	endwhile;
		wp_reset_query(); endif;

	if ( empty( $p_id ) ) {

		$new_participant_post = array(
			'post_type'   => 'participant',
			'post_title'  => $p_name,
			'post_status' => 'publish',
		);
		$p_id                 = wp_insert_post( $new_participant_post, true );
		update_post_meta( $p_id, 'participant_email', $p_email );

	}
	setcookie( 'participant_id', $p_id, time() + ( 86400 * 3 ), "/" );

	$wp_query_question = new WP_Query( array(
		'post_type'      => 'question',
		'post_status'    => 'publish',
		'orderby'        => 'random',
		'posts_per_page' => QUESTION_PER_QUIZ,
	) );

	$step_number = 0;

	$html .= '<div class="mcq_question_container">';

	if ( $wp_query_question->have_posts() ) :
		while ( $wp_query_question->have_posts() ) : $wp_query_question->the_post();

			$step_number ++;

			$html .= '<div class="mcq_single_question">';

			$html .= '<div class="steps-title">' . $step_number . '</div>';


			$html .= '<div class="steps-body">' . get_the_title() . '</div>';


			$html .= '</div>'; //mcq_single_question


		endwhile;
	endif;


	$html .= '</div>'; // mcq_question_container


	$html .= '
		<script>
			jQuery(".mcq_question_container").steps({
				headerTag: ".steps-title",
				bodyTag: ".steps-body",
				transitionEffect: "slide",
				onFinished: function (event, currentIndex){
					alert("ok");
				}
			});
        </script>';

	echo $html;
	die();
}

add_action( 'wp_ajax_mcq_ajax_start_test', 'mcq_ajax_start_test' );
add_action( 'wp_ajax_nopriv_mcq_ajax_start_test', 'mcq_ajax_start_test' );

function mcq_ajax_get_result() {
	$arr_options = $_POST['arr_options'];
	$total_mark  = 0;
	$href_link   = '';

	foreach ( $arr_options as $qs_id => $given_option ) {

		$given_opt_number   = explode( "_", $given_option );
		$correct_opt_number = get_post_meta( $qs_id, 'question_correct_ans', true );

		if ( $correct_opt_number[0] == $given_opt_number[2] ) {
			$total_mark += 1;
		} else {
			$total_mark -= 0.25;
		}

		$href_link .= $qs_id . '~' . $given_option . ',';
	}

	if ( empty( $total_mark ) ) {
		$total_mark = 0;
	}
	$performance_rate = ceil( ( $total_mark / QUESTION_PER_QUIZ ) * 100 );

	if ( $performance_rate < 0 ) {
		$performance_rate = 0;
	}

	if ( $performance_rate < 25 ) {
		$performance_color = '#DE5044';
	} elseif ( $performance_rate >= 25 && $performance_rate < 50 ) {
		$performance_color = '#FEA800';
	} elseif ( $performance_rate >= 25 && $performance_rate < 75 ) {
		$performance_color = '#1DA361';
	} elseif ( $performance_rate >= 75 ) {
		$performance_color = '#1DA361';
	}

	//echo '<pre>';print_r($href_link); echo '</pre>';


	$html = '
		<div class="mcq_result">
			<i class="fa fa-trophy center green s_56" aria-hidden="true"></i> <br>
			
			
			<div class="mcq_result_details">
				<span class="mcq_congratulation center s_18">Congratulations</span>
				
				<div class="mcq_performance s_16" style="background-color:' . $performance_color . ';">' . $performance_rate . '%</div>
				
				<div class="mcq_mark s_18">You got ' . $total_mark . ' out of ' . QUESTION_PER_QUIZ . '</div>
			
			</div>
			
			<div class="mcq_correct_answer s_16 w_180"><a href="?answer=' . $href_link . '"> <i class="fa fa-check-square" aria-hidden="true"></i> Correct Answers </a></div>
			<div class="mcq_retest s_16 w_180" onclick="location.reload();"><i class="fa fa-repeat" aria-hidden="true"></i> Again</div>
		</div>';


	echo $html;
	die();
}

add_action( 'wp_ajax_mcq_ajax_get_result', 'mcq_ajax_get_result' );
add_action( 'wp_ajax_nopriv_mcq_ajax_get_result', 'mcq_ajax_get_result' );

function mcq_toast_message() {
	echo '<div id="toast"></div>';
}

add_action( 'wp_footer', 'mcq_toast_message' );


function mcq_single_question_template( $single_template ) {
	global $post;

	if ( $post->post_type == 'question' ) {
		$single_template = MCQ_PLUGIN_DIR . 'templates/single-question.php';
	}

	return $single_template;
}

add_filter( 'single_template', 'mcq_single_question_template' );


add_action( 'admin_menu', 'mcq_menu_pages', 10 );
function mcq_menu_pages() {

}

function mcq_test_output() {
	return '';
}