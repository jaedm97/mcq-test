<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

	//echo '<pre>';print_r($_POST); echo '</pre>';

	
	$mcq_txt_start = get_option('mcq_txt_start');
	if( empty( $mcq_txt_start) ) $mcq_txt_start = 'Start Now';
	
	$mcq_txt_next_question = get_option('mcq_txt_next_question');
	if( empty( $mcq_txt_next_question) ) $mcq_txt_next_question = 'Next Question';
	
	$mcq_txt_previous_question = get_option('mcq_txt_previous_question');
	if( empty( $mcq_txt_previous_question) ) $mcq_txt_previous_question = 'Previous Question';
	
	$mcq_txt_finish_test = get_option('mcq_txt_finish_test');
	if( empty( $mcq_txt_finish_test) ) $mcq_txt_finish_test = 'Finish MCQ Test';
	
	$mcq_require_login_totest = get_option('mcq_require_login_totest');
	if( empty( $mcq_require_login_totest) ) $mcq_require_login_totest = 'no';
	
	
	if( $mcq_require_login_totest == 'yes' && !is_user_logged_in() ) {
		
		echo '<span class="s_18"><i class="fa fa-lock" aria-hidden="true"></i> You need to <a class="s_18" href="'.wp_login_url($_SERVER['REQUEST_URI']).'">login</a> give test';
		
	} else {
	
?>



<div class="mcq_start_now"> <?php echo $mcq_txt_start; ?></div>
		
<form enctype="multipart/form-data" id="mcq_form_test">
		
	<div class="mcq_question_container">
	<?php 
		$wp_query_question = new WP_Query( array (
			'post_type' => 'question',
			'post_status' => 'publish',
			'orderby' => 'rand',
			'posts_per_page' => QUESTION_PER_QUIZ,
		) );
		$step_number = 0;
	
		if ( $wp_query_question->have_posts() ) : 
		while ( $wp_query_question->have_posts() ) : $wp_query_question->the_post();	
		
		$step_number++;
		
		$arr_option = array();
		$arr_option[] = array( 'key' => 'question_option_1', 'value' => get_post_meta( get_the_ID(), 'question_option_1', true ) );
		$arr_option[] = array( 'key' => 'question_option_2', 'value' => get_post_meta( get_the_ID(), 'question_option_2', true ) );
		$arr_option[] = array( 'key' => 'question_option_3', 'value' => get_post_meta( get_the_ID(), 'question_option_3', true ) );
		$arr_option[] = array( 'key' => 'question_option_4', 'value' => get_post_meta( get_the_ID(), 'question_option_4', true ) );
		shuffle($arr_option);
		
		?>
		
		<div class="steps-title"><?php echo $step_number; ?></div>
		
		<div class="steps-body single-mcq-<?php echo get_the_ID(); ?>">
		
			<span class="mcq_question_title s_16"><a href="<?php echo get_permalink(); ?>"><?php echo $step_number .'. '.get_the_title(); ?></a></span>
			<br></br> <?php 

			foreach( $arr_option as $sl => $option_value ) { echo 
				'<input type="checkbox" name="'.get_the_ID().'" class="'.get_the_ID().'" id="'.$option_value['key'].'_'.get_the_ID().'" value="'.$option_value['key'].'" />
				<label class="mcq_checkbox_level s_16" for="'.$option_value['key'].'_'.get_the_ID().'">'.$option_value['value'].'</label><br>';
			}
			unset($foo); ?>
			
		</div>

	<?php endwhile; endif; ?>
	</div> <!--  mcq_question_container -->

</form> <!-- end of form -->


<div class="mcq_alredy_selected nodisplay"><i class="fa fa-exclamation-triangle red" aria-hidden="true"></i> You already Selected an Option</div>
	
<div class="mcq_get_result">Get Result</div>
		
	<script>
		jQuery(".mcq_question_container").steps({
			headerTag: ".steps-title",
			bodyTag: ".steps-body",
			transitionEffect: "slide",
			transitionEffectSpeed: 600,
			onFinished: function (event, currentIndex){
				jQuery(".mcq_question_container").fadeOut();
				jQuery(".mcq_get_result").fadeIn();
			},
			labels: {
				cancel: "Cancel",
				current: "current step:",
				pagination: "Pagination",
				finish: "<?php echo $mcq_txt_finish_test; ?>",
				next: "<?php echo $mcq_txt_next_question; ?>",
				previous: "<?php echo $mcq_txt_previous_question; ?>",
				loading: "Loading ..."
			}
		});
    </script>
		

<?php } ?>