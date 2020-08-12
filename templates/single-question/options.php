<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/
 
 
	$html = '';
	
	$html .= '<div class="list-single-question">'; 
	
	$mcq_question_options = get_post_meta( get_the_ID(), 'mcq_question_options', true );
	
	$options_correct 	= isset( $mcq_question_options['correct'] ) ? $mcq_question_options['correct'] : '';
	$options_array 		= isset( $mcq_question_options['options'] ) ? $mcq_question_options['options'] : array();
	shuffle( $options_array );
	
	foreach( $options_array as $key => $option_value ) {
				
		if( $mcq_question_options['options'][$options_correct] == $option_value )
			$html .= '<p class="list-mcq-option correct_answer"><i class="fa fa-square-o" aria-hidden="true"></i> '.$option_value.'</p>';
		else $html .= '<p class="list-mcq-option"><i class="fa fa-square-o" aria-hidden="true"></i> '.$option_value.'</p>';
			
	}
			
	$html .= '</div>';
			
	echo $html;
	