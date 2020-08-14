/**
 * Admin Scripts
 */

(function ($, window, document, pluginObject) {
    "use strict";

    $(function () {
        $(".question-options").sortable({revert: true});
    });


    $(document).on('click', '.mcq-button-admin', function () {

        let thisButton = $(this),
            questionsWrap = thisButton.parent().find('.question-options');

        $.ajax({
            type: 'POST',
            context: this,
            url: pluginObject.ajaxUrl,
            data: {
                'action': 'get_option_field',
            },
            success: function (response) {
                if (response.success) {
                    $(response.data).hide().appendTo(questionsWrap).slideDown();
                }
            }
        });
    });

    $(document).on('click', '.question-option .mcq-option-remove', function () {
        if (confirm(pluginObject.confirmText)) {
            $(this).parent().slideUp().remove();
        }
    });


    $(document).on('change', '.question-option .mcq-option-correct input[type="checkbox"]', function () {
        $(this).parent().toggleClass('correct');
    });

})(jQuery, window, document, mcq_object);







