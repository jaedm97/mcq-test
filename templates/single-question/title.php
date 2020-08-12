<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 


	echo apply_filters('MCQ_FILTER_QUESTION_TITLE_HTML', sprintf( __('<h1 itemprop="QuestionName" class="title" content="%s">%s</h1>', 'mcq-test' ), get_the_title(), get_the_title() ) );
	