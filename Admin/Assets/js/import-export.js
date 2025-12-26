/**
 * Import/Export Tab - Drag and Drop Functionality
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        const uploadArea = $('.cbedu-ie-upload-area');
        const fileInput = $('#cbedu_import_file');
        const uploadLabel = $('.cbedu-ie-upload-label');

        if (uploadArea.length === 0 || fileInput.length === 0) {
            return;
        }

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea[0].addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea[0].addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea[0].addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadLabel.addClass('cbedu-ie-drag-active');
        }

        function unhighlight(e) {
            uploadLabel.removeClass('cbedu-ie-drag-active');
        }

        // Handle dropped files
        uploadArea[0].addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                // Check if it's a CSV file
                const file = files[0];
                if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
                    fileInput[0].files = files;
                    updateFileName(file.name);
                } else {
                    alert('Please upload a CSV file only.');
                }
            }
        }

        // Update file name display when file is selected
        fileInput.on('change', function() {
            if (this.files.length > 0) {
                updateFileName(this.files[0].name);
            }
        });

        function updateFileName(fileName) {
            const uploadText = uploadLabel.find('.cbedu-ie-upload-text');
            uploadText.html('<strong>Selected:</strong> ' + fileName);
            uploadLabel.addClass('cbedu-ie-file-selected');
        }

        // Reset on form submit
        $('form').on('submit', function() {
            uploadLabel.removeClass('cbedu-ie-file-selected cbedu-ie-drag-active');
        });
    });

})(jQuery);
