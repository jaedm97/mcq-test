<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/
 
if ( ! defined('ABSPATH')) exit;  // if direct access 


	get_header();

	do_action('mcq_action_before_single_question');

	while ( have_posts() ) : the_post(); ?>
    
	<div itemscope itemtype="http://schema.org/Question" id="question-<?php the_ID(); ?>" <?php post_class('single-qs entry-content'); ?>>
        
    <?php do_action('mcq_action_single_question_title'); ?>
    <?php do_action('mcq_action_single_question_meta'); ?>
    <?php do_action('mcq_action_single_question_optins'); ?>
    <?php do_action('mcq_action_single_question_buttons'); ?>
    <?php do_action('mcq_action_single_question_related'); ?>
    
	<div class="clear"></div>
    </div>
	
	<?php
	endwhile;
	do_action('mcq_action_sidebar');
       
        
	do_action('mcq_action_after_single_question');
		
	get_footer();
		

	