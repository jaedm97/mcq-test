<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mcq_post_meta_question{
	
	public function __construct(){
		add_action('add_meta_boxes', array($this, 'meta_boxes_mcq_question'));
		add_action('save_post', array($this, 'meta_boxes_mcq_question_save'));
	}	
	
	public function meta_boxes_mcq_question($post_type) {
		$post_types = array('question');
		if (in_array($post_type, $post_types)) {
			add_meta_box('mcq_metabox',__('Question Option Box',MCQ_TEXTDOMAIN),array($this, 'mcq_meta_box_function'),$post_type,'normal','high');
		}
	}
	
	public function mcq_meta_box_function($post) {
        wp_nonce_field('mcq_nonce_check', 'mcq_nonce_check_value_question');

		
		$mcq_question_options = get_post_meta( $post->ID, 'mcq_question_options', true );
	
		// echo '<pre>'; print_r( $mcq_question_options); echo '</pre>';
		
		
	?>
	
	<div class="back-settings wp-poll-meta-container">
		
	<ul class="tab-nav"> </ul>
	<ul class="box">
		
	<li style="display: block;" class="box1 tab-box active">
			
		<div class="option-box">
			<p class="option-info">You can add more options</p>
			
			<ul class="mcq_meta_options_container">
			<div class="mcq_add_new_option"><i class="fa fa-plus-circle"></i> Add new Options</div>
				
			<?php if( empty( $mcq_question_options['options'] ) ) { ?>
				
			<li class="mcq_single_options"> 
				<input type="text" name="mcq_question_options[options][<?php echo time(); ?>]" size="30" placeholder="Option"/> 
				<div class="mcq_delete_single_options"><i class="fa fa-times-circle-o"></i></div>
				<div class="mcq_single_sorter"><i class="fa fa-sort"></i></div>
				<div class="mcq_single_correct">
					<input type="checkbox" name="mcq_question_options[correct]" value="" />
				</div>
			</li>
			
			<?php 
			
			} else { 
			
			
			foreach( $mcq_question_options['options'] as $option_key => $option_value ) {
			
				
				$correct_option = isset( $mcq_question_options['correct'] ) ? $mcq_question_options['correct'] : '';
				
				$checked = '';
				if( !empty( $correct_option ) ) {
					
					if( !is_array( $correct_option ) ) $correct_option = array( $correct_option );
					$checked = in_array( $option_key, $correct_option ) ? 'checked' : '';
				}
				
			?>
				
			<li class="mcq_single_options"> 
				
				<input type="text" name="mcq_question_options[options][<?php echo $option_key; ?>]" size="30" value="<?php echo $option_value;  ?>" placeholder="Option"/> 
				<div class="mcq_delete_single_options"><i class="fa fa-times-circle-o"></i></div>
				<div class="mcq_single_sorter"><i class="fa fa-sort"></i></div>
				
				<div class="mcq_single_correct">
					<input type="checkbox" <?php echo $checked; ?> name="mcq_question_options[correct][]" value="<?php echo $option_key; ?>" />
				</div>
				
			</li>
			
			<?php } } ?>
				
			</ul>	
		</div>

	</li>
		
		
		
		
		
	</ul>
	</div><?php
		
		
	
		
		
   	}
	
	public function meta_boxes_mcq_question_save($post_id){
		if (!isset($_POST['mcq_nonce_check_value_question'])) return $post_id;

		$nonce = $_POST['mcq_nonce_check_value_question'];
		
	 	if (!wp_verify_nonce($nonce, 'mcq_nonce_check')) return $post_id;
	 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 	if ('page' == $_POST['post_type'])  {
	 		if (!current_user_can('edit_page', $post_id)) return $post_id;
	 	} else {
	 		if (!current_user_can('edit_post', $post_id)) return $post_id;
		}


		$mcq_question_options = stripslashes_deep( $_POST['mcq_question_options'] );
		update_post_meta($post_id, 'mcq_question_options', $mcq_question_options);			
	}
	
	
} new class_mcq_post_meta_question();