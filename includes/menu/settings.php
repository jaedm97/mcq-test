<?php	


/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 



class class_mcq_settings_page  {
	
	
    public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
    }
	
	
	public function mcq_settings_options($options = array()){
		
		$options['Settings'] = array(
			
			'mcq_require_login_totest'=>array(
				'css_class'=>'mcq_require_login_totest',					
				'title'=>'Require login to give Test',
				'input_type'=>'radio',
				'input_values'=>'no',
				'input_args'=>array('yes'=>'Yes','no'=>'No'),
			),
		);
		
		$options['Text Settings'] = array(
			
			'mcq_txt_start'=>array(
				'css_class'=>'mcq_txt_start',					
				'title'=>'Start Now Button Text',
				'input_type'=>'text',
			),
			'mcq_txt_next_question'=>array(
				'css_class'=>'mcq_txt_next_question',					
				'title'=>'Next Question Button',
				'input_type'=>'text',
			),
			'mcq_txt_previous_question'=>array(
				'css_class'=>'mcq_txt_previous_question',					
				'title'=>'Previous Question Button',
				'input_type'=>'text',
			),
			'mcq_txt_finish_test'=>array(
				'css_class'=>'mcq_txt_finish_test',					
				'title'=>'Finish MCQ Text',
				'input_type'=>'text',
			),
			'mcq_txt_correct_answer'=>array(
				'css_class'=>'mcq_txt_correct_answer',					
				'title'=>'Correct Answer Text',
				'input_type'=>'text',
			),
			
			
		);
		
		
		
		$options = apply_filters( 'mcq_settings_options', $options );
		return $options;
	}
	
	
	public function mcq_settings_options_form(){
		global $post;
			
		$mcq_settings_options = $this->mcq_settings_options();
		$html = '';
		$html.= '<div class="back-settings">';			
		$html_nav = '';
		$html_box = '';
		
		$i=1;
		foreach($mcq_settings_options as $key=>$options)
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
				$option_value =  get_option( $option_key );				
				
				if(!isset($option_info['placeholder'])) $placeholder = '';
				else $placeholder = $option_info['placeholder'];
				
				if(!isset($option_info['input_values'])) $option_info['input_values'] = '';
				if(!isset($option_info['status'])) $option_info['status'] = '';
				if(!isset($option_info['option_details'])) $option_info['option_details'] = '';
				
				
				
				if(empty($option_value)) $option_value = $option_info['input_values'];
				
				$html_box.= '<div class="option-box '.$option_info['css_class'].'">';
				$html_box.= '<p class="option-title">'.$option_info['title'].'</p>';
				$html_box.= '<p class="option-info">'.$option_info['option_details'].'</p>';
				
				
				
				
				if($option_info['input_type'] == 'text') 
					$html_box.= '<input type="text" '.$option_info['status'].' placeholder="'.$placeholder.'" name="'.$option_key.'" id="'.$option_key.'" value="'.$option_value.'" /> ';					
				elseif($option_info['input_type'] == 'text-multi')
				{
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if(empty($option_value[$input_args_key])) $option_value[$input_args_key] = $input_args[$input_args_key];
						$html_box.= '<label>'.$input_args_key.'<br/><input class="job-bm-color" type="text" placeholder="" name="'.$option_key.'['.$input_args_key.']" value="'.$option_value[$input_args_key].'" /></label><br/>';	
					}
				}					
				elseif($option_info['input_type'] == 'textarea') $html_box.= '<textarea placeholder="" name="'.$option_key.'" >'.$option_value.'</textarea> ';
				elseif($option_info['input_type'] == 'radio')
				{
					$input_args = $option_info['input_args'];
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if($input_args_key == $option_value) $checked = 'checked';
						else $checked = '';
						$html_box.= '<label><input class="'.$option_key.'" type="radio" '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'"   >'.$input_args_values.'</label><br/>';
					}
				}
				elseif($option_info['input_type'] == 'select')
				{
					$input_args = $option_info['input_args'];
					$html_box.= '<select name="'.$option_key.'" >';
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
					foreach($input_args as $input_args_key=>$input_args_values)
					{
						if(empty($option_value[$input_args_key])) $checked = '';
						else $checked = 'checked';
						$html_box.= '<label><input '.$checked.' value="'.$input_args_key.'" name="'.$option_key.'['.$input_args_key.']"  type="checkbox" >'.$input_args_values.'</label><br/>';
					}
				}
				elseif($option_info['input_type'] == 'file')
				{
					$html_box.= '<input type="text" id="file_'.$option_key.'" name="'.$option_key.'" value="'.$option_value.'" /><br />';
					$html_box.= '<input id="upload_button_'.$option_key.'" class="upload_button_'.$option_key.' button" type="button" value="Upload File" />';					
					$html_box.= '<br /><br /><div style="overflow:hidden;max-height:150px;max-width:150px;" class="logo-preview"><img width="100%" src="'.$option_value.'" /></div>';
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
		return $html;
	}
} new class_mcq_settings_page();

	if(empty($_POST['mcq_hidden'])):
		$class_mcq_settings_page = new class_mcq_settings_page();
		$mcq_settings_options = $class_mcq_settings_page->mcq_settings_options();
		foreach($mcq_settings_options as $options_tab=>$options)
		{
			foreach($options as $option_key=>$option_data) 
				${$option_key} = get_option( $option_key );
		}
	else:
		if($_POST['mcq_hidden'] == 'Y'):
			$class_mcq_settings_page = new class_mcq_settings_page();
			$mcq_settings_options = $class_mcq_settings_page->mcq_settings_options();
			foreach($mcq_settings_options as $options_tab=>$options)
			{
				foreach($options as $option_key=>$option_data)
				{
					if(!isset($_POST[$option_key])) $_POST[$option_key] = '';
					
					${$option_key} = stripslashes_deep($_POST[$option_key]);
					update_option($option_key, ${$option_key});
				}
			}
			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.', MCQ_TEXTDOMAIN ); ?></strong></p></div>
			<?php
		endif;
	endif;
?>





	<div class="wrap">
		<div id="icon-tools" class="icon32"><br></div>
		<?php echo "<h2>".__(mcq_plugin_name.' Settings', MCQ_TEXTDOMAIN)."</h2>";?>
		
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="mcq_hidden" value="Y" />
			<?php 
				settings_fields( 'mcq_plugin_options' );
				do_settings_sections( 'mcq_plugin_options' );
					
				$class_mcq_settings_page = new class_mcq_settings_page();
				echo $class_mcq_settings_page->mcq_settings_options_form(); 
			?>
			
			<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes',MCQ_TEXTDOMAIN ); ?>" />
			<!--<div class="button button-primary" id="mcq_reset_settings" > <?php //_e('Reset Settings',MCQ_TEXTDOMAIN ); ?> </div> -->
		</form>
		
	</div>
