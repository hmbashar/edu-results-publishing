jQuery(document).ready(function($) {
    $('#cbedu_result_registration_number').on('change', function() {
        var registrationNumber = $(this).val();

        $.ajax({
            url: cbedu_ajax_object.ajax_url, // Passed from wp_localize_script
            type: 'POST',
            data: {
                action: 'get_student_name_by_registration',
                registration_number: registrationNumber,
                security: cbedu_ajax_object.nonce // Passed from wp_localize_script
            },
            success: function(response) {
                $('#cbedu_result_std_name').val(response);
            }
        });
    });
});