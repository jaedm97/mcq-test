<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

?> <div class="question-meta"  itemprop="QuestionMeta" itemscope itemtype="http://schema.org/QuestionMeta"> <?php 
	
	
	$category = get_the_terms(get_the_ID(), 'question_cat');
	if( !empty($category[0]->name) )
	echo apply_filters('MCQ_FILTER_QUESTION_META_CATEGORY_HTML', sprintf( __('<a class="meta button question_category" href="%s">%s %s</a>', 'mcq-test' ), get_term_link($category[0]->term_id), '<i class="fa fa-folder-open-o"></i>', $category[0]->name ) );
	
	echo apply_filters('MCQ_FILTER_QUESTION_META_DATE_HTML', sprintf( __('<a class="meta button question_date" href="" itemprop="datePublished" content="%s">%s %s</a>', 'mcq-test' ), get_the_date(), '<i class="fa fa-calendar-check-o"></i>', get_the_date() ) );
	
	
?> </div>
