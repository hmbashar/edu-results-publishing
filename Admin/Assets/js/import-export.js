/**
 * Import/Export Tab - Main Coordinator
 * Initializes Students and Results import/export modules
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize Students import/export
        if (typeof initStudentsImportExport === 'function') {
            initStudentsImportExport();
        }
        
        // Initialize Results import/export
        if (typeof initResultsImportExport === 'function') {
            initResultsImportExport();
        }
    });

})(jQuery);
