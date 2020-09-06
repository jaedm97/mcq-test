<?php
/**
 * Importer Main
 */


$posted_data = wp_unslash( $_POST );

if ( wp_verify_nonce( mcq_test()->get_args_option( 'mcq_importer_nonce', '', $posted_data ), 'mcq_importer' ) ) {
	$question_cat   = mcq_test()->get_args_option( 'q_cat', '', $posted_data );
	$html_content   = mcq_test()->get_args_option( 'html_content', '', $posted_data );
//	$questions      = empty( $html_content ) ? array() : mcq_parse_questions_from_html( $html_content );
	$questions      = empty( $html_content ) ? array() : mcq_parse_questions_from_mcqstudybd( $html_content );
	$question_count = 0;

	foreach ( $questions as $question ) {

		$title   = mcq_test()->get_args_option( 'title', '', $question );
		$options = mcq_test()->get_args_option( 'options', array(), $question );

		if ( empty( $title ) || empty( $options ) ) {
			continue;
		}

		$question_id = wp_insert_post( array(
			'post_type'   => 'question',
			'post_status' => 'publish',
			'post_title'  => $title,
			'tax_input'   => array(
				'question_cat' => $question_cat,
			),
			'meta_input'  => array(
				'_question_options' => $options,
			),
		) );

		if ( ! is_wp_error( $question_id ) ) {
			$question_count ++;
		}
	}

	mcq_test()->print_notice( sprintf( esc_html__( '%s Questions are added successfully!', 'mcq-test' ), $question_count ) );
}


?>
<br>
<form action="<?php the_permalink(); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <select name="q_cat">
        <option value="">Select Category</option>
		<?php foreach ( get_terms( array( 'taxonomy' => 'question_cat', 'hide_empty' => false ) ) as $term ) : ?>
            <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
		<?php endforeach; ?>
    </select>
    <br> <br>
    <textarea name="html_content" cols="30" rows="10"></textarea>
    <br><br>
	<?php wp_nonce_field( 'mcq_importer', 'mcq_importer_nonce' ); ?>
    <button class="button button-primary" type="submit">Import Questions</button>
</form>
