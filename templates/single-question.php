<?php
/**
 * Single question content
 */

defined( 'ABSPATH' ) || exit;

$question = mcq_get_question();

?>

<div class="mcq-single-question">
    <ul class="mcq-options"><?php echo implode( '', $question->get_options_list() ); ?></ul>
</div>

<h2><?php esc_html_e( 'Related Questions', 'mcq-test' ); ?></h2>
<p><?php esc_html_e( 'Please browse question to see the correct answer.', 'mcq-test' ); ?></p>

<div class="mcq-related-questions">
	<?php foreach ( $question->get_related_questions( array( 'numberposts' => 3 ) ) as $question_id ) {
		if ( $this_question = mcq_get_question( $question_id ) ) {
			printf( '<p><h4><a href="%s?correct=show">%s</a></h4><ul>%s</ul></p>',
				$this_question->get_permalink(), $this_question->get_name(), implode( '', $this_question->get_options_list() )
			);
		}
	} ?>
</div>

