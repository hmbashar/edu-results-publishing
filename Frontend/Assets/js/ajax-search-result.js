jQuery(document).ready(function($) {
    $('#cbedu-results-form').on('submit', function(event) {
        event.preventDefault();
        let isValid = true;
        $('.cbedu-error-message').text('').removeClass('is-visible');
        $('.cbedu-form-field').removeClass('has-error');

        let fieldsToValidate = [
            { id: '#examination', errorId: '#cbedu-examination-error', errorMessage: 'Please select an examination.' },
            { id: '#year', errorId: '#cbedu-year-error', errorMessage: 'Please select a year.' },
            { id: '#board', errorId: '#cbedu-board-error', errorMessage: 'Please select a board.' },
            { id: '#department_group', errorId: '#cbedu-department-group-error', errorMessage: 'Please select a department/group.' },
            { id: '#registration_number', errorId: '#cbedu-registration-number-error', errorMessage: 'Please enter a registration number.' },
            { id: '#roll', errorId: '#cbedu-roll-error', errorMessage: 'Please enter a roll number.' }
        ];

        $.each(fieldsToValidate, function(index, field) {
            let fieldValue = ($(field.id).val() || '').trim();
            if (fieldValue === '') {
                $(field.errorId).text(field.errorMessage).addClass('is-visible');
                $(field.id).closest('.cbedu-form-field').addClass('has-error');
                isValid = false;
            }
        });

        if (!isValid) {
            var $firstError = $('.cbedu-form-field.has-error').first();
            if ($firstError.length) {
                $('html, body').animate({ scrollTop: $firstError.offset().top - 80 }, 280);
                $firstError.find('input, select').first().trigger('focus');
            }
            return;
        }

        var $form = $(this);
        var $submit = $form.find('.cbedu-submit-btn');
        $('#cbedu-ajax-result-preloader').show();
        $('#cbedu-results-display').empty();
        $submit.prop('disabled', true).addClass('is-loading');

        let formData = $form.serialize() + '&nonce=' + cbedu_ajax_results_object.nonce;

        $.ajax({
            type: 'POST',
            url: cbedu_ajax_results_object.ajaxurl,
            data: formData + '&action=cbedu_handle_form_submission',
            success: function(response) {
                $('#cbedu-results-display').html(response);
                var $target = $('#cbedu-results-display');
                if ($target.length) {
                    $('html, body').animate({ scrollTop: $target.offset().top - 40 }, 400);
                }
            },
            error: function() {
                $('#cbedu-results-display').html(
                    '<div class="cbedu-empty-state cbedu-empty-state--error">' +
                        '<p class="cbedu-empty-state__title">Unable to load results</p>' +
                        '<p class="cbedu-empty-state__text">Please check your connection and try again.</p>' +
                    '</div>'
                );
            },
            complete: function() {
                $('#cbedu-ajax-result-preloader').hide();
                $submit.prop('disabled', false).removeClass('is-loading');
            }
        });
    });

    $('#cbedu-results-form').on('change input', 'input, select', function() {
        var $field = $(this).closest('.cbedu-form-field');
        $field.removeClass('has-error');
        $field.find('.cbedu-error-message').text('').removeClass('is-visible');
    });
});
