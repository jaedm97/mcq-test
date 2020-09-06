/**
 * Admin Scripts
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(document).on('click', '.mcq-single-question .mcq-correct', function () {
        $(this).parent().find('.correct').toggleClass('correct-visible');
    });

})(jQuery, window, document, mcq_object);







