<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 




$next_question 		= get_next_post();
$previous_question	= get_previous_post();
			
			
			
?>
<div class="question-pagination"  itemprop="QuestionPagination" itemscope itemtype="http://schema.org/QuestionPagination">

	
	<?php if ( !empty( $previous_question ) ) { ?>
		
		<div class="pagination-previous-question">
			<a href="<?php echo get_permalink( $previous_question->ID ); ?>"><?php echo $previous_question->post_title; ?></a> 
		</div>
		
	<?php } ?>
	
	
	<?php if ( !empty( $next_question ) ) { ?>
		
		<div class="pagination-next-question">
			<a href="<?php echo get_permalink( $next_question->ID ); ?>"><?php echo $next_question->post_title; ?></a> 
		</div>
		
	<?php } ?>
	
</div>
