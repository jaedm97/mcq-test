jQuery(document).ready(function($)
	{	
	
	
	
		$(function() {
			$( ".mcq_meta_options_container" ).sortable({ handle: ".mcq_single_sorter" });
		});
	
		$('.mcq_add_new_option').on('click', function() {
			
			$('.mcq_meta_options_container').css( 'opacity','0.4' );
			
			var data = '<li class="mcq_single_options" id="">' + 
			'<input type="text" name="mcq_question_options[options]['+$.now()+']" size="30" placeholder="Option"/>' +
				'<div class="mcq_delete_single_options"><i class="fa fa-times-circle-o"></i></div>' +
				'<div class="mcq_single_sorter"><i class="fa fa-sort"></i></div></li>';
			
			$('.mcq_meta_options_container').append( data );
				
			$('.mcq_meta_options_container').css( 'opacity','1' );
			
		});
		
		
		
		
		
		
		
		
		$(document).on('click', '#mcq_btn_start', function()
		{
			var mcq_btn_start = $('#mcq_btn_start').val();
			
			alert(mcq_btn_start);
			
			return;
			
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:mcq_ajax.mcq_ajaxurl,
			data: {
				"action": "mcq_ajax_validate_set", 
				"set_number":set_number,
			},
			success: function(data) {
				
				
				
			}
				});
			
		})

	});
	
	
	jQuery(document).on('click','.mcq_delete_single_options', function(){
		jQuery(this).closest('li').remove();
	});
	
	