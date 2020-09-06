<?php
/**
 * Question Meta box
 */

defined( 'ABSPATH' ) || exit;


?>
<div class="mcq-question-fields">
    <div class="question-field">
        <div class="mcq-button-admin"><?php esc_html_e( 'New Option', 'mcq-test' ); ?></div>
        <div class="question-options">
			<?php foreach ( mcq_test()->get_meta( '_question_options', false, array() ) as $option_index => $option ) :
				render_single_option_field( $option_index, $option );
			endforeach; ?>
        </div>
    </div>
</div>
