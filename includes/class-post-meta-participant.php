<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mcq_post_meta_participant{
	
	public function __construct(){
		add_action('add_meta_boxes', array($this, 'meta_boxes_mcq_question'));
		add_action('save_post', array($this, 'meta_boxes_mcq_question_save'));
	}
		
	public function mcq_meta_options_question($options = array()){
		
		$options['Options'] = array(
		
			'participant_preferred_category'=>array(
				'css_class'=>'participant_preferred_category',
				'title'=>'Preferred Category',
				'input_type'=>'text',
				'placeholder'=>'Preferred Category'
			),
			
			'participant_preferred_keyword'=>array(
				'css_class'=>'participant_preferred_keyword',
				'title'=>'Preferred Keywords',
				'input_type'=>'text',
				'placeholder'=>'Preferred Keywords'
			),
			
			'participant_preferred_level'=>array(
				'css_class'=>'participant_preferred_level',
				'title'=>'Set Preferred Difficulties Level ',
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
		
		$options['Settings'] = array(
		
			'participant_notification'=>array(
				'css_class'=>'participant_notification',
				'title'=>'How you want to be Updated',
				'option_details'=>'Please let us know how you want tou to be updated regularly about latest things from here.',
				'input_type'=>'checkbox',
				'input_args'=> array(
					'notification_email'	=> 'By Regular Email',
					'notification_mobile'	=> 'By Regular Mobile SMS',
					'notification_social'	=> 'By Social Networks (Currently only Facebook)',
				),
			),
		);
		
		$options['Security'] = array(
		
			'participant_email'=>array(
				'css_class'=>'participant_email',
				'title'=>'Participant Email',
				'input_type'=>'text',
				'placeholder'=>'email@example.com'
			),
			
			'participant_mobile'=>array(
				'css_class'=>'participant_mobile',
				'title'=>'Participant Mobile',
				'input_type'=>'text',
				'placeholder'=>'01xxxxxxxxx'
			),
			
			'participant_security_code'=>array(
				'css_class'=>'participant_security_code',
				'title'=>'6 Digit Security Code',
				'option_details'=>'Set a 6 DIgit security code number to get access on your account.',
				'input_type'=>'number',
				'max_length'=>6,
				'placeholder'=> '123456',
			),
						
		);
		
		$options = apply_filters( 'mcq_filters_meta_participant', $options );
		return $options;
	}
	
	
	public function mcq_meta_options_question_form(){
		global $post;
			
		$mcq_meta_options_question = $this->mcq_meta_options_question();
		$html = '';
			
		$html.= '<div class="back-settings wp-logo-slider-settings">';		
		
		$html_nav = '';
		$html_box = '';
		$custom_jquery = '';
				
		$i=1;
		foreach($mcq_meta_options_question as $key=>$options)
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
				$max_length		= isset($option_info['max_length']) ? $option_info['max_length'] : '';
				
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
				elseif($option_info['input_type'] == 'number') {
					$html_box.= '<input id="'.$option_key.'" '.$status.' type="number" placeholder="'.$placeholder.'" name="'.$option_key.'" max="'.$max_length.'" value="'.$option_value.'" /> ';					
				}elseif($option_info['input_type'] == 'validation') 
				{
					$btn_text = $option_info['btn_text'];
					
					$html_box.= '<input id="'.$option_key.'" '.$status.' type="number" placeholder="'.$placeholder.'" name="'.$option_key.'" value="'.$option_value.'" /> ';					
				
					$html_box .= '<div class="validate_button" id="validation_'.$option_key.'">'.$btn_text.'</div>';
				}
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
						
						if( is_array($option_value) ) {
							if ( in_array( $input_args_key, $option_value) ) $checked = 'checked';
							else $checked = '';
						} else {
							if ( $input_args_key == $option_value ) $checked = 'checked';
							else $checked = '';
						}
						
						
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

	public function meta_boxes_mcq_question($post_type) {
			$post_types = array('participant');
			if (in_array($post_type, $post_types)) 
			{
				add_meta_box('mcq_metabox',
					__('Participant Data Box',MCQ_TEXTDOMAIN),
					array($this, 'mcq_meta_box_function'),
					$post_type,
					'normal',
					'high');
			}
		}
	public function mcq_meta_box_function($post) {
        wp_nonce_field('mcq_nonce_check', 'mcq_nonce_check_value_question');
		$mcq_meta_options_question = $this->mcq_meta_options_question();
		foreach($mcq_meta_options_question as $options_tab=>$options)
		{
			foreach($options as $option_key=>$option_data)
				${$option_key} = get_post_meta($post -> ID, $option_key, true);
		}
		?> <div class="job-bm-cp-meta"> <?php
		echo $this->mcq_meta_options_question_form(); 
		?></div><?php
   	}
	
	public function meta_boxes_mcq_question_save($post_id){
		if (!isset($_POST['mcq_nonce_check_value_question'])) return $post_id;

		$nonce = $_POST['mcq_nonce_check_value_question'];
		
		
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


		$mcq_meta_options_question = $this->mcq_meta_options_question();
		
		foreach($mcq_meta_options_question as $options_tab=>$options)
		{
			foreach($options as $option_key=>$option_data)
			{
				${$option_key} = stripslashes_deep($_POST[$option_key]);
				update_post_meta($post_id, $option_key, ${$option_key});			
			}
		}
	}
	
	
} new class_mcq_post_meta_participant();