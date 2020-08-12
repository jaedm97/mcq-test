<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

	
	$html = '<style type="text/css">';			
	
	$html .= '
	
	.question-meta {
		display: inline-block;
	}
	
	.meta {
		float: left;
		text-align: center;
		margin-right: 10px;
		padding: 5px 0;
		border-radius: 3px;
		cursor: pointer;
	}

	.meta a {
		color: #fff !important;
		text-decoration: none;
		outline: none !important;
		padding: 5px 35px;
	}
	
	.meta a i{
		margin-right: 5px;
	}
	
	
	.question_category {
		background-color: #377FBD;
	}

	.question_date {
		background-color: #34A853;
	}
	
	
	';
	
	$html .= '</style>';	
	
	
	echo $html;
