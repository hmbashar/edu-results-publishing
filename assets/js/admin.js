jQuery(document).ready(function($) {
    $('#cbedu_result_registration_number').on('change', function() {
        var registrationNumber = $(this).val();

        $.ajax({
            url: cbedu_ajax_object.ajax_url, // Passed from wp_localize_script
            type: 'POST',
            data: {
                action: 'get_student_details_by_registration',
                registration_number: registrationNumber,
                security: cbedu_ajax_object.nonce // Passed from wp_localize_script
            },
            success: function(response) {
                // Extracting student's name and father's name from the response
                $('#cbedu_result_std_name').val(response.studentName);
                $('#cbedu_result_std_fathers_name').val(response.fathersName);
            }
        });
    });
});
