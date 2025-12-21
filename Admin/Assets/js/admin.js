jQuery(document).ready(function($) {
    let debounceTimer;
    $('#cbedu_result_std_registration_number').on('keyup', function() {
        let registrationNumber = $(this).val();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            $.ajax({
                url: cbedu_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_student_details_by_registration',
                    registration_number: registrationNumber,
                    security: cbedu_ajax_object.nonce
                },
                success: function(response) {
                    $('#cbedu_result_std_name').val(response.studentName);
                    $('#cbedu_result_std_fathers_name').val(response.fathersName);
                    $('#cbedu_result_std_mothers_name').val(response.mothersName);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching student details: ' + textStatus + ' ' + errorThrown);
                }
            });
        }, 500); // Delay for 500 ms
    });
});
