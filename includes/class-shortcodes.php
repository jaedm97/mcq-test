<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_ecommerce_shortcodes{
	
    public function __construct(){
		add_shortcode( 'import_questions', array( $this, 'import_questions' ) );
		add_shortcode( 'mcq_test', array( $this, 'mcq_test' ) );
	}
	
	public function mcq_test($atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				
		), $atts);
		
		
		//$participant_id = isset($_COOKIE['participant_id']) ? $_COOKIE['participant_id'] : '';
		//echo '<pre>';print_r($participant_id); echo '</pre>';
		
		//$html .= '<div class="mcq_test_container"> <div class="mcq_input_1 w_320 center_div center">';
		
		/*$html .= '<select class="w_320 s_14 pl_8">';
		$html .= '<option value="">Select a Category</option>';
		
		foreach( $cat_arr as $cat_id => $cat_info ) {
			foreach( $cat_info as $key => $value ) {
			
				if( $key == 'name' ) 
					$html .= '<option value="'.$key.'" class="bold"> '.$value.'</option>';
				else 
					$html .= '<option value="'.$key.'">  - '.$value.'</option>';
			}
		}
		$html .= '</select><br>';	*/ // hide the select	
			
		/*$html .= '<input id="mcq_name" type="text" placeholder="Your Name" autofocus value="Jaed Mosharraf" /> <br>';
		$html .= '<input id="mcq_email" type="text" placeholder="Phone or Email" value="jaedm97@gmail.com" /> <br>';
		
		$html .= '</div>';
		
	
		$html .= '<div id="mcq_btn_start" class="mcq_btn mcq_btn_primary w_220 center_div">Start MCQ Test</div>';
		
		$html .= '<div class="mcq_pre_loading nodisplay">
		<div class="center">
			<i class="pre_loading_icon fa fa-spin fa-cog red s_56" aria-hidden="true"></i><br><br>
			
			<span class="collecting_info s_16 green">Collecting your Information </span>
			<span class="gathering_questions nodisplay s_16 blue">Gathering Questions</span> 
			
			<br>
		</div>
		</div>';
		
		
		$html .= '</div>'; //mcq_test_container
		*/
		
		
		$answer = explode( ",", isset($_GET['answer']) ? $_GET['answer'] : '' );
		array_pop($answer);
		if( $_GET ) {
			
			if( count($answer) <= 0 ) 
				return '<span class="s_22">Sorry! You seems to be Spam</span>';
			
		
		//echo '<pre>';print_r($answer); echo '</pre>';
		
		$html = '';
		$count = 0;
		$html .= '<div class="question-container">';	
		
		foreach( $answer as $serial => $single_question ) {
			
			$arr_single = explode( "~", $single_question );
			
			$html .= '<div class="list-single-question">
			<div class="mcq_question_title s_16"> '.++$count.'. '.get_the_title( $arr_single[0] ).'</div>'; 
			
			$arr_option = array();
			$arr_option[] = array( 'key' => 'question_option_1', 'value' => get_post_meta( $arr_single[0], 'question_option_1', true ) );
			$arr_option[] = array( 'key' => 'question_option_2', 'value' => get_post_meta( $arr_single[0], 'question_option_2', true ) );
			$arr_option[] = array( 'key' => 'question_option_3', 'value' => get_post_meta( $arr_single[0], 'question_option_3', true ) );
			$arr_option[] = array( 'key' => 'question_option_4', 'value' => get_post_meta( $arr_single[0], 'question_option_4', true ) );
			shuffle($arr_option);
			
			$correct_opt_number	= get_post_meta( $arr_single[0], 'question_correct_ans', true );
			$correct_opt_number	= 'question_option_'.$correct_opt_number[0];
			
			foreach( $arr_option as $sl => $option_value ) {
				
			if( $option_value['key'] == $arr_single[1] ) {
				
				if( $correct_opt_number == $arr_single[1] ) {
					$html .= '
					<span class="list-mcq-option s_16 green"><i class="fa fa-check-square" aria-hidden="true"></i> '.$option_value['value'].'</span><br>';
				}
				else {
					$html .= '
					<span class="list-mcq-option s_16 red"><i class="fa fa-check-square" aria-hidden="true"></i> '.$option_value['value'].'</span><br>';
				}
			} else {
				
				if( $option_value['key'] == $correct_opt_number )
				$html .= '
				<span class="list-mcq-option s_16 green"><i class="fa fa-square-o" aria-hidden="true"></i> '.$option_value['value'].'</span><br>';
				else	
				$html .= '
				<span class="list-mcq-option s_16"><i class="fa fa-square-o" aria-hidden="true"></i> '.$option_value['value'].'</span><br>';
				
				
			}
			}
			
			$html .= '</div>'; // single_question
				
		}
		
		$html .= '</div>'; // question_container
			
			
			
			
			return $html;
		}
		else {
			
			ob_start();
			include( MCQ_PLUGIN_DIR . 'templates/mcq-test.php');
			return ob_get_clean();
			
		}
				
		
			
	}
	
	public function import_questions($atts, $content = null ) {
		$atts = shortcode_atts(
			array(
		), $atts);
		
		// include MCQ_PLUGIN_DIR .'import/qs_1.php';
		
		
		include MCQ_PLUGIN_DIR .'templates/import_questions.php';
		
		
		
		// echo '<pre>';print_r($mcq_content);echo '</pre>';
		
		
		/* $arr = array();
		foreach( explode(",", $mcq_content) as $serial => $qs_single ) {
			
			$arr_qs 	= explode('~',$qs_single);
			$correct 	= isset( $arr_qs[1] ) ? $arr_qs[1] : '';
			$option_1 	= isset( $arr_qs[2] ) ? $arr_qs[2] : '';
			$option_2 	= isset( $arr_qs[3] ) ? $arr_qs[3] : '';
			$option_3 	= isset( $arr_qs[4] ) ? $arr_qs[4] : '';
			$option_4 	= isset( $arr_qs[5] ) ? $arr_qs[5] : '';
				
			if( 
				!empty( $arr_qs[0] ) &&
				!empty( $correct ) &&
				!empty( $option_1 ) &&
				!empty( $option_2 ) &&
				!empty( $option_3 ) &&
				!empty( $option_4 ) )
			{
				$new_question_post = array(
					'post_type'   => 'question',
					'post_title'    => $arr_qs[0],
					'post_status'   => 'pending',
				  
				);
				$post_id = wp_insert_post($new_question_post, true);
				
				update_post_meta($post_id,'question_option_1',$option_1);
				update_post_meta($post_id,'question_option_2',$option_2);
				update_post_meta($post_id,'question_option_3',$option_3);
				update_post_meta($post_id,'question_option_4',$option_4);
				update_post_meta($post_id,'question_correct_ans',$correct);
				update_post_meta($post_id,'question_level',rand(1,6));
				
			}
		} */
	}
	
	
	
}new class_ecommerce_shortcodes();