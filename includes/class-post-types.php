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

		$singular  = __( 'Participant', 'mcq-test' );
		$plural    = __( 'Participants', 'mcq-test' );

		register_post_type( "participant",
			apply_filters( "register_post_type_participant", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, 'mcq-test' ),
					'all_items'             => sprintf( __( 'All %s', 'mcq-test' ), $plural ),
					'add_new' 				=> __( 'Add New', 'mcq-test' ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'mcq-test' ), $singular ),
					'edit' 					=> __( 'Edit', 'mcq-test' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'mcq-test' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'mcq-test' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'mcq-test' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'mcq-test' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mcq-test' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'mcq-test' ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', 'mcq-test' ), $plural ),
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

		$singular  = __( 'Question', 'mcq-test' );
		$plural    = __( 'Questions', 'mcq-test' );

		register_post_type( "question",
			apply_filters( "register_post_type_question", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, 'mcq-test' ),
					'all_items'             => sprintf( __( 'All %s', 'mcq-test' ), $plural ),
					'add_new' 				=> __( 'Add New', 'mcq-test' ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'mcq-test' ), $singular ),
					'edit' 					=> __( 'Edit', 'mcq-test' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'mcq-test' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'mcq-test' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'mcq-test' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'mcq-test' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mcq-test' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'mcq-test' ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', 'mcq-test' ), $plural ),
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
		
		$singular  = __( 'Question Category', 'mcq-test' );
		$plural    = __( 'Question Categories', 'mcq-test' );
		
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
						'search_items'      => sprintf( __( 'Search %s', 'mcq-test' ), $plural ),
						'all_items'         => sprintf( __( 'All %s', 'mcq-test' ), $plural ),
						'parent_item'       => sprintf( __( 'Parent %s', 'mcq-test' ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', 'mcq-test' ), $singular ),
						'edit_item'         => sprintf( __( 'Edit %s', 'mcq-test' ), $singular ),
						'update_item'       => sprintf( __( 'Update %s', 'mcq-test' ), $singular ),
						'add_new_item'      => sprintf( __( 'Add New %s', 'mcq-test' ), $singular ),
						'new_item_name'     => sprintf( __( 'New %s Name', 'mcq-test' ),  $singular )
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

		$singular  = __( 'Q Set', 'mcq-test' );
		$plural    = __( 'Q Sets', 'mcq-test' );

		register_post_type( "question_set",
			apply_filters( "register_post_type_question_set", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, 'mcq-test' ),
					'all_items'             => sprintf( __( 'All %s', 'mcq-test' ), $plural ),
					'add_new' 				=> __( 'Add New', 'mcq-test' ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'mcq-test' ), $singular ),
					'edit' 					=> __( 'Edit', 'mcq-test' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'mcq-test' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'mcq-test' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'mcq-test' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'mcq-test' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mcq-test' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'mcq-test' ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', 'mcq-test' ), $plural ),
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

		$singular  = __( 'Given Test', 'mcq-test' );
		$plural    = __( 'Given Tests', 'mcq-test' );

		register_post_type( "given_test",
			apply_filters( "register_post_type_given_test", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => __( $singular, 'mcq-test' ),
					'all_items'             => sprintf( __( 'All %s', 'mcq-test' ), $plural ),
					'add_new' 				=> __( 'Add New', 'mcq-test' ),
					'add_new_item' 			=> sprintf( __( 'Add %s', 'mcq-test' ), $singular ),
					'edit' 					=> __( 'Edit', 'mcq-test' ),
					'edit_item' 			=> sprintf( __( 'Edit %s', 'mcq-test' ), $singular ),
					'new_item' 				=> sprintf( __( 'New %s', 'mcq-test' ), $singular ),
					'view' 					=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'view_item' 			=> sprintf( __( 'View %s', 'mcq-test' ), $singular ),
					'search_items' 			=> sprintf( __( 'Search %s', 'mcq-test' ), $plural ),
					'not_found' 			=> sprintf( __( 'No %s found', 'mcq-test' ), $plural ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'mcq-test' ), $plural ),
					'parent' 				=> sprintf( __( 'Parent %s', 'mcq-test' ), $singular )
				),
				'description' => sprintf( __( 'This is where you can create and manage %s.', 'mcq-test' ), $plural ),
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