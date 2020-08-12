<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/
 
 
	$correct_btn_text = get_option('mcq_txt_correct_answer', 'Correct Answer');
	
	echo '<div class="single_question_button">';
	
	echo apply_filters('MCQ_FILTER_QUESTION_BUTTON_CORRECT_HTML', sprintf( __('<div class="button btn_view_correct">%s</div>', MCQ_TEXTDOMAIN ), $correct_btn_text ) );
	
	echo '</div>';