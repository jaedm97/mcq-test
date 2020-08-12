<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/
 
	$wp_query_related = new WP_Query(array (
		'post_type' => 'question',
		'posts_per_page' => 3,
		'orderby' => 'rand',
		'post__not_in' => array( get_the_ID() ),
	) );
 
	?>
	<div class="mcq_related_question">
	
	<?php 
	
	if ( $wp_query_related->have_posts() ) :
	while ( $wp_query_related->have_posts() ) : $wp_query_related->the_post();	
		
		echo '<a class="mcq_related_single" href="'.get_the_permalink().'">'.get_the_title().'</a>';	
		
	endwhile;
	wp_reset_query();
	endif;
	
	
	?>
	</div>