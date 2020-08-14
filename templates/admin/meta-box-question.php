<?php
/**
 * Question Meta box
 */

defined( 'ABSPATH' ) || exit;

$question_options = mcq_test()->get_meta( '_question_options', false, array() );
$question_answers = mcq_test()->get_meta( '_question_answers', false, array() );

?>
<div class="mcq-question-fields">
    <div class="question-field">
        <div class="mcq-button-admin"><?php esc_html_e( 'New Option', 'mcq-test' ); ?></div>
        <div class="question-options">
			<?php foreach ( $question_options as $option ) :
				render_single_option_field( $option, $question_answers );
			endforeach; ?>
        </div>
    </div>
</div>
