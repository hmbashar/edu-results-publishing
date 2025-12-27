/**
 * Results Import/Export Module
 */
function initResultsImportExport() {
    (function($) {
        'use strict';

        const uploadArea = $('.cbedu-results-upload-area');
        const fileInput = $('#cbedu_results_import_file');
        const uploadLabel = uploadArea.find('.cbedu-ie-upload-label');
        const exportForm = $('.cbedu-results-export-form');
        const importForm = $('.cbedu-results-import-form');

        // Initialize drag and drop
        if (uploadArea.length > 0 && fileInput.length > 0) {
            initResultsDragDrop();
        }

        // Export AJAX handler
        if (exportForm.length > 0) {
            exportForm.on('submit', handleResultsExport);
        }

        // Import AJAX handler
        if (importForm.length > 0) {
            importForm.on('submit', handleResultsImport);
        }

        /**
         * Initialize drag and drop for results
         */
        function initResultsDragDrop() {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea[0].addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea[0].addEventListener(eventName, () => uploadLabel.addClass('cbedu-ie-drag-active'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea[0].addEventListener(eventName, () => uploadLabel.removeClass('cbedu-ie-drag-active'), false);
            });

            uploadArea[0].addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    const file = files[0];
                    if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
                        fileInput[0].files = files;
                        updateFileName(file.name);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: 'Please upload a CSV file only.',
                            confirmButtonColor: '#f5576c'
                        });
                    }
                }
            }

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
        }

        /**
         * Handle Results Export
         */
        function handleResultsExport(e) {
            e.preventDefault();

            // Show loading
            Swal.fire({
                title: 'Exporting Results...',
                html: 'Please wait while we prepare your CSV file.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: cbeduImportExport.ajaxurl,
                type: 'POST',
                data: {
                    action: 'cbedu_export_results',
                    nonce: cbeduImportExport.exportResultsNonce
                },
                success: function(response) {
                    if (response.success) {
                        // Create and download CSV file
                        const csv = response.data.csv;
                        const filename = response.data.filename;
                        const count = response.data.count;

                        // Create blob and download
                        const blob = new Blob(["\ufeff" + csv], { type: 'text/csv;charset=utf-8;' });
                        const link = document.createElement('a');
                        const url = URL.createObjectURL(blob);
                        
                        link.setAttribute('href', url);
                        link.setAttribute('download', filename);
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Export Successful!',
                            html: `<strong>${count}</strong> results exported successfully.`,
                            confirmButtonColor: '#667eea',
                            timer: 3000
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Export Failed',
                            text: response.data.message || 'An error occurred during export.',
                            confirmButtonColor: '#f5576c'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Export Failed',
                        text: 'An unexpected error occurred. Please try again.',
                        confirmButtonColor: '#f5576c'
                    });
                }
            });
        }

        /**
         * Handle Results Import
         */
        function handleResultsImport(e) {
            e.preventDefault();

            // Check if file is selected
            if (!fileInput[0].files || fileInput[0].files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No File Selected',
                    text: 'Please select a CSV file to import.',
                    confirmButtonColor: '#f5576c'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Importing Results...',
                html: 'Please wait while we process your CSV file.<br><small>This may take a moment for large files.</small>',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Prepare form data
            const formData = new FormData();
            formData.append('action', 'cbedu_import_results');
            formData.append('nonce', cbeduImportExport.importResultsNonce);
            formData.append('cbedu_results_import_file', fileInput[0].files[0]);

            $.ajax({
                url: cbeduImportExport.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        let html = '<div style="text-align: left;">';
                        html += `<p><strong>✓ Created:</strong> ${data.imported} results</p>`;
                        html += `<p><strong>✓ Updated:</strong> ${data.updated} results</p>`;
                        
                        if (data.errors > 0) {
                            html += `<p style="color: #f59e0b;"><strong>⚠ Errors:</strong> ${data.errors} rows</p>`;
                            
                            if (data.error_messages && data.error_messages.length > 0) {
                                html += '<details style="margin-top: 10px;"><summary style="cursor: pointer; color: #667eea;">View Error Details</summary>';
                                html += '<ul style="margin-top: 10px; font-size: 13px;">';
                                data.error_messages.forEach(msg => {
                                    html += `<li>${msg}</li>`;
                                });
                                html += '</ul></details>';
                            }
                        }
                        
                        html += '</div>';

                        Swal.fire({
                            icon: data.errors > 0 ? 'warning' : 'success',
                            title: 'Import Completed!',
                            html: html,
                            confirmButtonColor: '#667eea',
                            width: '600px'
                        });

                        // Reset form
                        importForm[0].reset();
                        uploadLabel.removeClass('cbedu-ie-file-selected cbedu-ie-drag-active');
                        uploadLabel.find('.cbedu-ie-upload-text').text('Click to browse or drag CSV file here');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Import Failed',
                            text: response.data.message || 'An error occurred during import.',
                            confirmButtonColor: '#f5576c'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Import Failed',
                        text: 'An unexpected error occurred. Please try again.',
                        confirmButtonColor: '#f5576c'
                    });
                }
            });
        }

    })(jQuery);
}
