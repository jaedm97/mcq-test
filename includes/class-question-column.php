<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mcq_question_column{
	
	public function __construct(){

		add_action( 'manage_question_posts_columns', array( $this, 'add_core_question_columns' ), 16, 1 );
		add_action( 'manage_question_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
		// add_filter( 'post_row_actions', array( $this, 'remove_quick_edit' ), 10, 2 );
		
		add_action( 'restrict_manage_posts', array( $this, 'category_filter_function' ), 9, 0 );
		add_filter( 'parse_query', array( $this, 'question_filter_by_category_function' ), 10, 1 );
		
	}
	
	public function category_filter_function() {

		global $typenow;
		if ( ('question' != $typenow ) ) return;
		
		$category	= isset( $_GET['question_category'] ) ? filter_input( INPUT_GET, 'question_category', FILTER_SANITIZE_STRING ) : '';
		
		// $all    	= ( '' 			=== $this_sort ) ? 'selected' : '';
		// $pending	= ( 'pending' 	=== $this_sort ) ? 'selected' : '';
		// $hold		= ( 'hold' 		=== $this_sort ) ? 'selected' : '';
		// $solved		= ( 'solved' 	=== $this_sort ) ? 'selected' : '';
		// $processing = ( ! isset( $_GET['qa_status'] ) || 'processing' === $this_sort ) ? 'selected' : '';
		
		// $dropdown        = '<select id="qa_status" name="qa_status">';
		// $dropdown        .= "<option value='' $all>" . __( 'Any Status', MCQ_TEXTDOMAIN ) . "</option>";
		// $dropdown        .= "<option value='pending' $pending>" . __( 'Pending', MCQ_TEXTDOMAIN ) . "</option>";
		// $dropdown        .= "<option value='processing' $processing>" . __( 'On Discussion', MCQ_TEXTDOMAIN ) . "</option>";
		// $dropdown        .= "<option value='hold' $hold>" . __( 'On Hold', MCQ_TEXTDOMAIN ) . "</option>";
		// $dropdown        .= "<option value='solved' $solved>" . __( 'Solved', MCQ_TEXTDOMAIN ) . "</option>";
		// $dropdown        .= '</select>';
		
		$dropdown = '<select name="question_category">';
		$dropdown .= '<option value="">'.__('Select category',MCQ_TEXTDOMAIN).'</option>';
		
		foreach( mcq_get_categories() as $cat_id => $cat_info ) { ksort($cat_info);
			foreach( $cat_info as $key => $value ) {
				
				$selected = ( $category == $cat_id ) ? 'selected' : '';
				
				if( $key == 0 )  $dropdown .= '<option value="'.$cat_id.'" '.$selected.'><b>'.$value.'</b></option>';
				else $dropdown .= '<option value="'.$key.'" '.$selected.'>  - '.$value.'</option>';
			}
		}
		$dropdown .= '</select>';
		
		
		
		echo $dropdown;

	}
	
	public function question_filter_by_category_function( $query ) {

		global $pagenow;
		if ( is_admin()
		     && 'edit.php' == $pagenow
		     && isset( $_GET['post_type'] )
		     && 'question' == $_GET['post_type']
		     && isset( $_GET['question_category'] )
		     && ! empty( $_GET['question_category'] )
		     && $query->is_main_query()
		) {
			
			$query->set( 'cat', $_GET['question_category'] );
			return;
				
				
				
				
			/* if( $_GET['question_category'] == 'pending' ) {
				$query->set( 'category__in', 'pending' );
				return;
			}
			
			if( $_GET['qa_status'] == 'processing' ) $query->set( 'post_status', 'publish' );
		
				$meta_query = $query->get( 'meta_query' );
				if ( ! is_array( $meta_query ) ) {
					$meta_query = array_filter( (array) $meta_query );
				}
				$meta_query[] = array(
						'key'     => 'qa_question_status',
						'value'   => sanitize_text_field( $_GET['qa_status'] ),
						'compare' => '=',
						'type'    => 'CHAR',
				);
				$query->set( 'meta_query', $meta_query ); */
		}

	}
	
	public function add_core_question_columns( $columns ) {

		$new = array();
		
		$count = 0;
		foreach ( $columns as $col_id => $col_label ) { $count++;

			if ( $count == 3 ) 
			$new['mcq-correct'] = '' . esc_html__( 'Options / Answer', MCQ_TEXTDOMAIN );
			
			if( 'title' === $col_id ) {
				$new[$col_id] = '<i class="fa fa-question-circle fs_18"></i> ' . esc_html__( 'Question Title', MCQ_TEXTDOMAIN );
			
			} elseif( 'taxonomy-question_cat' === $col_id ) {
				$new[$col_id] = '' . esc_html__( 'Questions Categories', MCQ_TEXTDOMAIN );
			
			} else {
				$new[ $col_id ] = $col_label;
			}
		}
		
		return $new;
	}
	
	public function custom_columns_content( $column, $post_id ) {
		switch ( $column ) {
		case 'mcq-correct':
			
			$html 		= '';
			$options 	= get_post_meta( get_the_ID(), 'mcq_question_options', true );
			
			foreach( $options['options'] as $key => $value ) {
				if( $options['correct'] == $key )
					$html .= '<span class="mcq_bk_correct"><b>'.$value.' </b></span> | ';
				else 
					$html .= '<span class="mcq_bk_option">'.$value.' </span> | ';
			}
			
			echo __( substr($html, 0, -2), MCQ_TEXTDOMAIN );
			break;

		case 'qa-activity':

			echo 'Jaed';
			break;

		}
	}	
} new class_mcq_question_column();