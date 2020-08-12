jQuery(document).ready(function($)
	{	
		$(document).on('click', '.mcq_start_now', function()
		{
			$(this).fadeOut();
			$('.mcq_question_container').fadeIn();
		})
		
		$(document).on('click', '.mcq_checkbox_level', function()
		{
			var id 	= $(this).attr("for");
			var mcq_id = $('#'+id).attr("name");

			if( $( "." + mcq_id ).is(':checked') ) {
				var toast_message = $('.mcq_alredy_selected').html();
				$('#toast').html(toast_message);
				$('#toast').addClass('show');
				setTimeout( function(){ $('#toast').removeClass('show');}, 3000 );
			}
			
			$(".single-mcq-"+mcq_id+" input").change(function(){
				$(this).siblings().attr("disabled", $(this).is(":checked"));  
			});

		})
		
		
		
		$(document).on('click', '#mcq_btn_start', function()
		{
			var pre_html = $('.mcq_test_container').html();
			
			var p_name 	= $('#mcq_name').val();
			var p_email	= $('#mcq_email').val();
			
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:mcq_ajax.mcq_ajaxurl,
			data: {
				"action": "mcq_ajax_start_test", 
				"p_name":p_name,
				"p_email":p_email,
			},
			success: function(data) {
				//location.reload();
				
				$('.mcq_test_container').html(data);
			}
				});
		})
		
		
		
		$(document).on('click', '.mcq_get_result', function()
		{
			$(this).fadeOut();
			var arr_options = {};
			$('#mcq_form_test :input').each(function() {
				
				if ( $(this).is(":checked") ) {
					arr_options[this.name] = $(this).val();
				}
			});

			var _HTML = $('#mcq_form_test').html();
			$('#mcq_form_test').addClass('center');
			$('#mcq_form_test').html('<i class="fa fa-spin green s_56 fa-cog" aria-hidden="true"></i>');
			//$(this).fadeOut();
			
			$.ajax(
				{
			type: 'POST',
			context: this,
			url:mcq_ajax.mcq_ajaxurl,
			data: {
				"action": "mcq_ajax_get_result", 
				"arr_options":arr_options,
			},
			success: function(data) {
				$('#mcq_form_test').html(data);
			}
				});
	
	
		})
		
		
		$(document).on('click', '.btn_view_correct', function()
		{
			$('.list-single-question p').each(function() {
				
				if ( $(this).hasClass("correct_answer") ) {
					$(this).addClass('green').hide().delay(500).fadeIn();
				}
				
			});
		})
		
		$(document).on('click', '.btn_report_question', function()
		{
			
		})
		
		

	});

	
	/*var mcq_btn_start = $('#mcq_btn_start').val();
			
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
				
				
				
				*/