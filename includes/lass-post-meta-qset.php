<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mcq_post_meta_question_set{
	
	public function __construct(){
		add_action('add_meta_boxes', array($this, 'meta_boxes_mcq_question_set'));
		add_action('save_post', array($this, 'meta_boxes_mcq_question_set_save'));
	}
		
	public function mcq_meta_options_question_set($options = array()){
		
		$options['Options'] = array(
		
			'question_set_option_1'=>array(
				'css_class'=>'question_set_option_1',
				'title'=>'Option 1',
				'input_type'=>'text',
				'placeholder'=>'Option 1'
			),
			
			'question_set_option_2'=>array(
				'css_class'=>'question_set_option_2',
				'title'=>'Option 2',
				'input_type'=>'text',
				'placeholder'=>'Option 2'
			),
			
			'question_set_option_3'=>array(
				'css_class'=>'question_set_option_3',
				'title'=>'Option 3',
				'input_type'=>'text',
				'placeholder'=>'Option 3'
			),
			
			'question_set_option_4'=>array(
				'css_class'=>'question_set_option_4',
				'title'=>'Options 4',
				'input_type'=>'text',
				'placeholder'=>'Option 4'
			),
			
			
		);
		
		$options['Answer'] = array(
		
			'question_set_correct_ans'=>array(
				'css_class'=>'question_set_correct_ans',
				'title'=>'Correct Answer',
				'option_details'=>'Select the right option number. It may be multiple.',
				'input_type'=>'checkbox',
				'input_args'=> array(
					'question_set_option_1'	=> 'Option 1',
					'question_set_option_2'	=> 'Option 2',
					'question_set_option_3'	=> 'Option 3',
					'question_set_option_4'	=> 'Option 4',
				),
				
			),
		);
		
		$options['Settings'] = array(
		
			'question_set_level'=>array(
				'css_class'=>'question_set_level',
				'title'=>'Set question_set Difficulties Level ',
				'option_details'=>'Set Difficulties Level. 1 means easy level and 10 means high level.',
				'input_type'=>'radio',
				'input_args'=> array(
					'1'	=> 'Level 1',
					'2'	=> 'Level 2',
					'3'	=> 'Level 3',
					'4'	=> 'Level 4',
					'5'	=> 'Level 5',
					'6'	=> 'Level 6',
					'7'	=> 'Level 7',
					'8'	=> 'Level 8',
					'9'	=> 'Level 9',
					'10'=> 'Level 10',
				),
				
			),
		);
		
		
		
		$options = apply_filters( 'mcq_filters_meta_options_question_set', $options );
		return $options;
	}
	
	
	public function mcq_meta_options_question_set_form(){
		global $post;
			
		$mcq_meta_options_question_set = $this->mcq_meta_options_question_set();
		$html = '';
			
		$html.= '<div class="back-settings wp-logo-slider-settings">';		
		
		$html_nav = '';
		$html_box = '';
		$custom_jquery = '';
				
		$i=1;
		foreach($mcq_meta_options_question_set as $key=>$options)
		{
			if ( $i == 1 ):
				$html_nav.= '<li nav="'.$i.'" class="nav'.$i.' active">'.$key.'</li>';	
				$html_box.= '<li style="display: block;" class="box'.$i.' tab-box active">';
			else:
				$html_nav.= '<li nav="'.$i.'" class="nav'.$i.'">'.$key.'</li>';
				$html_box.= '<li style="display: none;" class="box'.$i.' tab-box">';
			endif;
		
			foreach($options as $option_key=>$option_info)
			{
				$option_value =  get_post_meta( $post->ID, $option_key, true );
				
				if(!empty($option_info['input_values']))
					if(empty($option_value)) $option_value = $option_info['input_values'];

				$option_details	= isset($option_info['option_details']) ? $option_info['option_details'] : '';
				$status 		= isset($option_info['status']) ? $option_info['status'] : '';
				$placeholder 	= isset($option_info['placeholder']) ? $option_info['placeholder'] : '';
				$rows 			= isset($option_info['rows']) ? $option_info['rows'] : '';
				
				//================================================//
				
				$html_box.= '<div class="option-box '.$option_info['css_class'].'">';
				$html_box.= '<p class="option-title">'.$option_info['title'].'</p>';
				$html_box.= '<p class="option-info">'.$option_details.'</p>';

				
				if($option_info['input_type'] == 'text')
				{
					if( !empty( $option_info['is_disabled'] ) && $option_info['is_disabled'] == true ) 
						$html_box.= '<input id="'.$option_key.'" '.$status.' type="text" disabled placeholder="'.$placeholder.'" name="'.$option_key.'" value="'.$option_value.'" /> ';					
					else $html_box.= '<input id="'.$option_key.'" '.$status.' type="text" placeholder="'.$placeholder.'" name="'.$option_key.'" value="'.$option_value.'" /> ';					
				}	
				elseif($option_info['input_type'] == 'number')
					$html_box.= '<input id="'.$option_key.'" '.$status.' type="number" placeholder="'.$placeholder.'" name="'.$option_key.'" value="'.$option_value.'" /> ';					
				elseif($option_info['input_type'] == 'textarea')
					$html_box.= '<textarea rows="'.$rows.'" id="'.$option_key.'" placeholder="'.$placeholder.'" name="'.$option_key.'" >'.$option_value.'</textarea> ';
				elseif($option_info['input_type'] == 'radio')
				{
					$input_args = $option_info['input_args'];
					
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if($input_args_key == $option_value) $checked = 'checked';
						else $checked = '';
							
						$html_box.= '<label class="radio_input"><input class="'.$option_key.'" type="radio" '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'"   >'.$input_args_values.'</label><br>';
					}
				}
				elseif($option_info['input_type'] == 'select')
				{
					$input_args = $option_info['input_args'];
					$html_box.= '<select id="'.$option_key.'" name="'.$option_key.'" >';
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if($input_args_key == $option_value) $selected = 'selected';
						else $selected = '';

						$html_box.= '<option '.$selected.' value="'.$input_args_key.'">'.$input_args_values.'</option>';
					}
					$html_box.= '</select>';
				}	
				
				elseif($option_info['input_type'] == 'checkbox')
				{
					$input_args = $option_info['input_args'];
					$html_box.= '<div id="'.$option_key.'">';
					
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if ( empty($input_args_key) ){
							$html_box.= '<label class="checkbox_input"> '.__('No '.$option_info['title'].' Found. Add items in Settings.','wp_re').'</label>';
							continue;
						}
							
						if ( is_array($option_value) && in_array( $input_args_key, $option_value) )	$checked = 'checked';
						else $checked = '';

						$html_box.= '<label class="checkbox_input"> <input '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'[]"  type="checkbox" > '.$input_args_values.'</label><br>';
					}
					$html_box.= '</div>';
				}	
				
				elseif($option_info['input_type'] == 'file')
				{
					$html_box.= '<input type="text" id="file_'.$option_key.'" name="'.$option_key.'" value="'.$option_value.'" />';
					$html_box.= '<input id="upload_button_'.$option_key.'" class="upload_button_'.$option_key.' button" type="button" value="Upload File" />';					
					$html_box.= '<div style="" class="logo-preview"><img width="100%" src="'.$option_value.'" /></div>';
					$html_box.= '
					<script>
						jQuery(document).ready(function($){
							var custom_uploader; 
							jQuery("#upload_button_'.$option_key.'").click(function(e) {
								e.preventDefault();
							 	if (custom_uploader) {
									custom_uploader.open();
									return;
								}
								custom_uploader = wp.media.frames.file_frame = wp.media({
									title: "Choose File",
									button: {
										text: "Choose File"
									},
									multiple: false
								});
								custom_uploader.on("select", function() {
									attachment = custom_uploader.state().get("selection").first().toJSON();
									jQuery("#file_'.$option_key.'").val(attachment.url);
									jQuery(".logo-preview img").attr("src",attachment.url);											
								});
								custom_uploader.open();
							});
						})
					</script>';					
				}
				
				$html_box.= '</div>';

				$is_time = isset( $option_info['is_time'] ) ? $option_info['is_time'] : '';
				
				if ( $is_time == true )
				$custom_jquery .= '
				jQuery("#'.$option_key.'").timepicker({
					"timeFormat": "H:i:s"
				});';
				
			}
			$html_box.= '</li>';
			$i++;
		}
		$html.= '<ul class="tab-nav">';
		$html.= $html_nav;			
		$html.= '</ul>';
		$html.= '<ul class="box">';
		$html.= $html_box;
		$html.= '</ul>';		
		$html.= '</div>';	

		$html .= '
		<script>
		'.$custom_jquery.'
		</script>';
		
		return $html;
	}

	public function meta_boxes_mcq_question_set($post_type) {
			$post_types = array('question_set');
			if (in_array($post_type, $post_types)) 
			{
				add_meta_box('mcq_metabox',
					__('question_set Data Box','mcq-test'),
					array($this, 'mcq_meta_box_function'),
					$post_type,
					'normal',
					'high');
			}
		}
	public function mcq_meta_box_function($post) {
        wp_nonce_field('mcq_nonce_check', 'mcq_nonce_check_value_question_set');
		$mcq_meta_options_question_set = $this->mcq_meta_options_question_set();
		foreach($mcq_meta_options_question_set as $options_tab=>$options)
		{
			foreach($options as $option_key=>$option_data)
				${$option_key} = get_post_meta($post -> ID, $option_key, true);
		}
		?> <div class="job-bm-cp-meta"> <?php
		echo $this->mcq_meta_options_question_set_form(); 
		?></div><?php
   	}
	
	public function meta_boxes_mcq_question_set_save($post_id){
		if (!isset($_POST['mcq_nonce_check_value_question_set'])) return $post_id;

		$nonce = $_POST['mcq_nonce_check_value_question_set'];
		
		
	 	if (!wp_verify_nonce($nonce, 'mcq_nonce_check')) return $post_id;
	 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	 	if ('page' == $_POST['post_type']) 
		{
	 		if (!current_user_can('edit_page', $post_id)) return $post_id;
	 	} 
		else 
		{
	 		if (!current_user_can('edit_post', $post_id)) return $post_id;
		}


		$mcq_meta_options_question_set = $this->mcq_meta_options_question_set();
		
		foreach($mcq_meta_options_question_set as $options_tab=>$options)
		{
			foreach($options as $option_key=>$option_data)
			{
				${$option_key} = stripslashes_deep($_POST[$option_key]);
				update_post_meta($post_id, $option_key, ${$option_key});			
			}
		}
	}
	
	
} new class_mcq_post_meta_question_set();