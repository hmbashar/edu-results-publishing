jQuery(document).ready(function($) {
    $('#cbedu_result_registration_number').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: cbedu_ajax_autocomplete_object.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'add_search_registration_numbers',
                    AutoNonce: cbedu_ajax_autocomplete_object.auto_complete_nonce,  // Sending nonce for verification
                    term: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.label,
                            value: item.value
                        };
                    }));
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Autocomplete error: ' + textStatus + ' ' + errorThrown);
                }
            });
        },
        minLength: 2
    });
});