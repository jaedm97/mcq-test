<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/
 
if ( ! defined('ABSPATH')) exit;  // if direct access 



add_filter('single_template', 'my_single_template');

function my_single_template($single) {
	
	global $wp_query, $post;

	foreach((array)get_the_category() as $cat) :

	if( file_exists( mcq_plugin_dir. 'templates/single-question_cat-' . $cat->slug . '.php') )
	return mcq_plugin_dir. 'templates/single-question_cat-' . $cat->slug . '.php';

	elseif(file_exists( mcq_plugin_dir. 'templates/single-question_cat-' . $cat->term_id . '.php'))
	return mcq_plugin_dir. 'templates/single-question_cat-' . $cat->term_id . '.php';

	endforeach;

}




	function mcq_get_categories( $taxonamy =  '') {
		$args = array(
			'show_option_none' => __( 'Select category' ),
			'hide_empty'          => 0,
			'hierarchical'        => true,
			'order'               => 'ASC',
			'orderby'             => 'name',
			'taxonomy'            => 'question_cat',
			
		);
		$cat = get_terms( $args );
		$cat_arr = array();
		foreach( $cat as $cat_details ) {
			if ( $cat_details->parent == 0 ) {
				$cat_arr[$cat_details->term_id]['name'] = $cat_details->name;
			} else {
				$cat_arr[$cat_details->parent][$cat_details->term_id] = $cat_details->name;
			}			
		}
		
		return $cat_arr;
	}
	
	function mcq_ajax_start_test() {
		$p_name 	= $_POST['p_name'];
		$p_email 	= $_POST['p_email'];
		
		$html 		= '';

		$wp_query = new WP_Query( array (
			'post_type' => 'participant',	
			'meta_query' => array(
				array(
					'key'     => 'participant_email',
					'value'   => $p_email,
					'compare' => '=',
				),
			),
		));
			
		$p_id = '';
		if ( $wp_query->have_posts() ):while ( $wp_query->have_posts() ) : $wp_query->the_post();
			$p_id .= get_the_ID();
		endwhile; wp_reset_query(); endif;
		
		if ( empty($p_id) ) {
			
			$new_participant_post = array(
				'post_type'   	=> 'participant',
				'post_title'	=> $p_name,
				'post_status'   => 'publish',
			);
			$p_id = wp_insert_post($new_participant_post, true);
			update_post_meta($p_id,'participant_email',$p_email);
		
		}
		setcookie( 'participant_id', $p_id, time() + (86400 * 3), "/");
		
		$wp_query_question = new WP_Query( array (
			'post_type' => 'question',
			'post_status' => 'publish',
			'orderby' => 'random',
			'posts_per_page' => QUESTION_PER_QUIZ,
		) );
	
		$step_number = 0;
		
		$html .= '<div class="mcq_question_container">';
		
		if ( $wp_query_question->have_posts() ) : 
		while ( $wp_query_question->have_posts() ) : $wp_query_question->the_post();	
		
		$step_number++;
		
		$html .= '<div class="mcq_single_question">';
		
		$html .= '<div class="steps-title">'.$step_number.'</div>';
		
		
		$html .= '<div class="steps-body">'.get_the_title().'</div>';

		
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
	add_action('wp_ajax_mcq_ajax_start_test', 'mcq_ajax_start_test');
	add_action('wp_ajax_nopriv_mcq_ajax_start_test', 'mcq_ajax_start_test');
	
	function mcq_ajax_get_result(){
		$arr_options 	= $_POST['arr_options'];
		$total_mark 	= 0;
		$href_link		= '';
		
		foreach( $arr_options as $qs_id => $given_option ) {
			
			$given_opt_number 	= explode( "_" , $given_option );
			$correct_opt_number	= get_post_meta( $qs_id, 'question_correct_ans', true );
	
			if( $correct_opt_number[0] == $given_opt_number[2] ) {
				$total_mark += 1;
			} else {
				$total_mark -= 0.25;
			}
			
			$href_link .= $qs_id.'~'.$given_option.',';
		}
		
		if( empty($total_mark) ) $total_mark = 0;
		$performance_rate = ceil(( $total_mark / QUESTION_PER_QUIZ ) * 100);
		
		if( $performance_rate < 0 ) $performance_rate = 0;
		
		if( $performance_rate < 25 ) $performance_color = '#DE5044';
		elseif( $performance_rate >= 25 && $performance_rate < 50 ) $performance_color = '#FEA800';
		elseif( $performance_rate >= 25 && $performance_rate < 75 ) $performance_color = '#1DA361';
		elseif( $performance_rate >= 75 ) $performance_color = '#1DA361';
		
		//echo '<pre>';print_r($href_link); echo '</pre>';
		
		
		$html = '
		<div class="mcq_result">
			<i class="fa fa-trophy center green s_56" aria-hidden="true"></i> <br>
			
			
			<div class="mcq_result_details">
				<span class="mcq_congratulation center s_18">Congratulations</span>
				
				<div class="mcq_performance s_16" style="background-color:'.$performance_color.';">'.$performance_rate.'%</div>
				
				<div class="mcq_mark s_18">You got '.$total_mark.' out of '.QUESTION_PER_QUIZ.'</div>
			
			</div>
			
			<div class="mcq_correct_answer s_16 w_180"><a href="?answer='.$href_link.'"> <i class="fa fa-check-square" aria-hidden="true"></i> Correct Answers </a></div>
			<div class="mcq_retest s_16 w_180" onclick="location.reload();"><i class="fa fa-repeat" aria-hidden="true"></i> Again</div>
		</div>';
		
		
		echo $html;
		die();
	}
	add_action('wp_ajax_mcq_ajax_get_result', 'mcq_ajax_get_result');
	add_action('wp_ajax_nopriv_mcq_ajax_get_result', 'mcq_ajax_get_result');
	
	function mcq_toast_message() {
		echo '<div id="toast"></div>';
	}
	add_action( 'wp_footer', 'mcq_toast_message' );
	
	
	
	function mcq_single_question_template($single_template) {
		global $post;

		if ($post->post_type == 'question') {
			$single_template = mcq_plugin_dir. 'templates/single-question.php';
		}
		return $single_template;
	}
	add_filter( 'single_template', 'mcq_single_question_template' );
	
	
	
	
	add_action('admin_menu', 'mcq_menu_pages', 10);
	function mcq_menu_pages(){
		
	}

	function mcq_test_output() {
		return '';
	}