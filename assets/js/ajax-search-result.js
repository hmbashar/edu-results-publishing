jQuery(document).ready(function($) {
    $('#cbedu-results-form').submit(function(event) {
        event.preventDefault();
        var registrationNumber = $('#registration_number').val().trim();
        
        if (registrationNumber === '') {
            alert('Please enter a registration number.');
            return;
        }

        var formData = $(this).serialize() + '&nonce=' + cbedu_ajax_results_object.nonce;        
        $.ajax({
            type: "POST",
            url: cbedu_ajax_results_object.ajaxurl,
            data: formData + '&action=cbedu_handle_form_submission',
            success: function(response) {
                $('#cbedu-results-display').html(response);
            }
        });
    });
});
