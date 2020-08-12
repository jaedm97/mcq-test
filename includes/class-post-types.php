<?php

/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_mcq_post_types{
	
	public function __construct(){
		add_action( 'init', array( $this, 'mcq_posttype_question' ), 0 );
		add_action( 'init', array( $this, 'mcq_posttype_participant' ), 0 );
		add_action( 'init', array( $this, 'mcq_posttype_given_test' ), 0 );
		//add_action( 'init', array( $this, 'mcq_posttype_question_set' ), 0 );
	}
	
	public function mcq_posttype_participant(){
		if ( post_type_exists( "participant" ) ) return;

		$singular  = __( 'Participant', MCQ_TEXTDOMAIN );
		$plural    = __( 'Participants', MCQ_TEXTDOMAIN );

		register_post_type( "participant",
			apply_filters( "register_post_type_participant", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, MCQ_TEXTDOMAIN ),
					'all_items'             => sprintf( __( 'All %s', MCQ_TEXTDOMAIN ), $plural ),
					'add_new' 				=> __( 'Add New', MCQ_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', MCQ_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', MCQ_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', MCQ_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', MCQ_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', MCQ_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', MCQ_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', MCQ_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', MCQ_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', MCQ_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title','thumbnail','revisions'),
				'show_in_nav_menus' 	=> true,
				'menu_icon' => 'dashicons-businessman',
			) )
		);
	}
	
	public function mcq_posttype_question(){
		if ( post_type_exists( "question" ) ) return;

		$singular  = __( 'Question', MCQ_TEXTDOMAIN );
		$plural    = __( 'Questions', MCQ_TEXTDOMAIN );

		register_post_type( "question",
			apply_filters( "register_post_type_question", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, MCQ_TEXTDOMAIN ),
					'all_items'             => sprintf( __( 'All %s', MCQ_TEXTDOMAIN ), $plural ),
					'add_new' 				=> __( 'Add New', MCQ_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', MCQ_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', MCQ_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', MCQ_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', MCQ_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', MCQ_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', MCQ_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', MCQ_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', MCQ_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', MCQ_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title'),
				'show_in_nav_menus' 	=> true,
				'menu_icon' => 'dashicons-nametag',
			) )
		);
		
		$singular  = __( 'Question Category', MCQ_TEXTDOMAIN );
		$plural    = __( 'Question Categories', MCQ_TEXTDOMAIN );
		
			register_taxonomy( "question_cat",
				apply_filters( 'register_taxonomy_product_group_object_type', array( 'question' ) ),
	       	 	apply_filters( 'register_taxonomy_product_group_args', array(
		            'hierarchical' 			=> true,
		            'show_admin_column' 	=> true,					
		            'update_count_callback' => '_update_post_term_count',
		            'label' 				=> $plural,
		            'labels' => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => ucwords( $plural ),
						'search_items'      => sprintf( __( 'Search %s', MCQ_TEXTDOMAIN ), $plural ),
						'all_items'         => sprintf( __( 'All %s', MCQ_TEXTDOMAIN ), $plural ),
						'parent_item'       => sprintf( __( 'Parent %s', MCQ_TEXTDOMAIN ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', MCQ_TEXTDOMAIN ), $singular ),
						'edit_item'         => sprintf( __( 'Edit %s', MCQ_TEXTDOMAIN ), $singular ),
						'update_item'       => sprintf( __( 'Update %s', MCQ_TEXTDOMAIN ), $singular ),
						'add_new_item'      => sprintf( __( 'Add New %s', MCQ_TEXTDOMAIN ), $singular ),
						'new_item_name'     => sprintf( __( 'New %s Name', MCQ_TEXTDOMAIN ),  $singular )
	            	),
		            'show_ui' 				=> true,
		            'public' 	     		=> true,
				    'rewrite' => array(
						'slug' => 'question_cat',
						'with_front' => false,
						'hierarchical' => true
				),
		        ) )
		    );
			
			
	}
	
	public function mcq_posttype_question_set(){
		if ( post_type_exists( "question_set" ) ) return;

		$singular  = __( 'Q Set', MCQ_TEXTDOMAIN );
		$plural    = __( 'Q Sets', MCQ_TEXTDOMAIN );

		register_post_type( "question_set",
			apply_filters( "register_post_type_question_set", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, MCQ_TEXTDOMAIN ),
					'all_items'             => sprintf( __( 'All %s', MCQ_TEXTDOMAIN ), $plural ),
					'add_new' 				=> __( 'Add New', MCQ_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', MCQ_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', MCQ_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', MCQ_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', MCQ_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', MCQ_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', MCQ_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', MCQ_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', MCQ_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', MCQ_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('title','thumbnail','revisions'),
				'show_in_nav_menus' 	=> true,
				'menu_icon' => 'dashicons-admin-multisite',
			) )
		);
	}
	
	public function mcq_posttype_given_test(){
		if ( post_type_exists( "given_test" ) ) return;

		$singular  = __( 'Given Test', MCQ_TEXTDOMAIN );
		$plural    = __( 'Given Tests', MCQ_TEXTDOMAIN );

		register_post_type( "given_test",
			apply_filters( "register_post_type_given_test", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, MCQ_TEXTDOMAIN ),
					'all_items'             => sprintf( __( 'All %s', MCQ_TEXTDOMAIN ), $plural ),
					'add_new' 				=> __( 'Add New', MCQ_TEXTDOMAIN ),
					'add_new_item' 			=> sprintf( __( 'Add %s', MCQ_TEXTDOMAIN ), $singular ),
					'edit' 					=> __( 'Edit', MCQ_TEXTDOMAIN ),
					'edit_item' 			=> sprintf( __( 'Edit %s', MCQ_TEXTDOMAIN ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', MCQ_TEXTDOMAIN ), $singular ),
					'view' 					=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', MCQ_TEXTDOMAIN ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', MCQ_TEXTDOMAIN ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', MCQ_TEXTDOMAIN ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', MCQ_TEXTDOMAIN ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', MCQ_TEXTDOMAIN ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', MCQ_TEXTDOMAIN ), $plural ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'show_in_menu'			=> 'given_test',
				'query_var' 			=> true,
				'supports' 				=> array('title','thumbnail','revisions'),
				'show_in_nav_menus' 	=> true,
				'menu_icon' => 'dashicons-businessman',
			) )
		);
	}
	
	
} new class_mcq_post_types();