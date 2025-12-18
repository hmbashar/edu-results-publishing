jQuery(document).ready(function($) {
    $('#cbedu-results-form').submit(function(event) {
        event.preventDefault();
        let isValid = true;
        $('.cbedu-error-message').text(''); // Clear previous error messages

        // Define fields to validate
        let fieldsToValidate = [
            { id: '#examination', errorId: '#cbedu-examination-error', errorMessage: 'Please select an examination.' },
            { id: '#year', errorId: '#cbedu-year-error', errorMessage: 'Please select a year.' },
            { id: '#board', errorId: '#cbedu-board-error', errorMessage: 'Please select a board.' },
            { id: '#department_group', errorId: '#cbedu-department-group-error', errorMessage: 'Please select a department/group.' },
            { id: '#registration_number', errorId: '#cbedu-registration-number-error', errorMessage: 'Please enter a registration number.' },
            { id: '#roll', errorId: '#cbedu-roll-error', errorMessage: 'Please enter a roll number.' }
        ];

        // Iterate and validate each field
        $.each(fieldsToValidate, function(index, field) {
            let fieldValue = $(field.id).val().trim();
            if (fieldValue === '') {
                $(field.errorId).text(field.errorMessage);
                isValid = false;
            }
        });

        // Proceed with AJAX if all fields are valid
        if (isValid) {
            $('#cbedu-ajax-result-preloader').show(); // Show the preloader
            let formData = $(this).serialize() + '&nonce=' + cbedu_ajax_results_object.nonce;
            $.ajax({
                type: "POST",
                url: cbedu_ajax_results_object.ajaxurl,
                data: formData + '&action=cbedu_handle_form_submission',
                success: function(response) {
                    $('#cbedu-results-display').html(response);
                },
                complete: function() {
                    $('#cbedu-ajax-result-preloader').hide(); // Hide the preloader after the AJAX request is complete
                }
            });
        }
    });
});