<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

	$mcq_input_data = isset( $_POST['mcq_input_data'] ) ? $_POST['mcq_input_data'] : '';
	$mcq_arr_option = explode( ",", $mcq_input_data );
	
	unset ($mcq_arr_option[count($mcq_arr_option)-1]);

	if( !empty($mcq_arr_option) )
	foreach( $mcq_arr_option as $single_question ) {
	
		$mcq_question_options = array();

		$question = explode( "~", $single_question );
		foreach( $question as $key => $value )	{
			
			if( !empty( $value ) && $key == 0 ) {

				$new_question_ID = wp_insert_post( array(
					'post_type'   => 'question',
					'post_title'    => $value,
					'post_status'   => 'pending',
				) );
			}			
			
			if( !empty( $value ) && $key == 1 ) {
				$mcq_question_options['correct'] = $value;
			}
			
			if( $key > 1 ) {
				$mcq_question_options['options'][$key-1] = $question[$key];
			}
		}
		
		if( !empty( $new_question_ID ) )
		update_post_meta( $new_question_ID, 'mcq_question_options', $mcq_question_options );
		
	}
	
	
?>
		
<form enctype="multipart/form-data" action="" method="POST">
	
	<label for="mcq_input_data">Add your HTML</label><br><br>
	<textarea name="mcq_input_data" id="mcq_input_data" rows="5" col="50" style="width:100%;"></textarea>
	
	<br><br><input type="submit" class="button" value="Import questions" />
</form>

