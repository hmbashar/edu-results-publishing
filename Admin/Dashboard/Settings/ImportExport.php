<?php
namespace CBEDU\Admin\Dashboard\Settings;

use CBEDU\Admin\Dashboard\Settings\ImportExport\StudentsImportExport;
use CBEDU\Admin\Dashboard\Settings\ImportExport\ResultsImportExport;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ImportExport
{
    private $students_handler;
    private $results_handler;

    public function __construct()
    {
        // Initialize handlers
        $this->students_handler = new StudentsImportExport();
        $this->results_handler = new ResultsImportExport();
    }

    /**
     * Render import/export tab content
     */
    public function render_import_export_tab()
    {
        ?>
        <div class="cbedu-import-export-wrapper">
            
            <!-- Students Section -->
            <div class="cbedu-ie-hero">
                <div class="cbedu-ie-hero-content">
                    <h2 class="cbedu-ie-hero-title"><?php esc_html_e('📊 Student Data Management', 'edu-results'); ?></h2>
                    <p class="cbedu-ie-hero-subtitle"><?php esc_html_e('Seamlessly import and export student records with all custom fields and taxonomies', 'edu-results'); ?></p>
                </div>
            </div>

            <div class="cbedu-ie-grid">
                <?php $this->students_handler->render_section(); ?>
            </div>
            
            <!-- Separator -->
            <div class="cbedu-ie-separator">
                <div class="cbedu-ie-separator-line"></div>
            </div>
            
            <!-- Results Section -->
            <div class="cbedu-ie-hero">
                <div class="cbedu-ie-hero-content">
                    <h2 class="cbedu-ie-hero-title"><?php esc_html_e('📋 Results Data Management', 'edu-results'); ?></h2>
                    <p class="cbedu-ie-hero-subtitle"><?php esc_html_e('Seamlessly import and export result records with all custom fields and taxonomies', 'edu-results'); ?></p>
                </div>
            </div>

            <div class="cbedu-ie-grid">
                <?php $this->results_handler->render_section(); ?>
            </div>
            
            <!-- Help Section -->
            <div class="cbedu-ie-help">
                <div class="cbedu-ie-help-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13M12 17H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="cbedu-ie-help-content">
                    <h4><?php esc_html_e('Need Help?', 'edu-results'); ?></h4>
                    <p><?php esc_html_e('Export a sample CSV first to see the correct format, then modify it with your data and import it back.', 'edu-results'); ?></p>
                </div>
            </div>
            
        </div>
        <?php
    }

    /**
     * Display admin notices for import/export
     * (Kept for backward compatibility, but AJAX handles most notifications now)
     */
    public function display_notices()
    {
        // Export error
        if (isset($_GET['export_error'])) {
            $error = sanitize_text_field($_GET['export_error']);
            $message = '';
            
            switch ($error) {
                case 'no_students':
                    $message = __('No students found to export.', 'edu-results');
                    break;
                case 'no_results':
                    $message = __('No results found to export.', 'edu-results');
                    break;
                default:
                    $message = __('An error occurred during export.', 'edu-results');
            }
            
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }

        // Import success
        if (isset($_GET['import_success'])) {
            $imported = isset($_GET['imported']) ? intval($_GET['imported']) : 0;
            $updated = isset($_GET['updated']) ? intval($_GET['updated']) : 0;
            $errors = isset($_GET['errors']) ? intval($_GET['errors']) : 0;
            
            $message = sprintf(
                __('Import completed! Created: %d, Updated: %d, Errors: %d', 'edu-results'),
                $imported,
                $updated,
                $errors
            );
            
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }

        // Import error
        if (isset($_GET['import_error'])) {
            $error = sanitize_text_field($_GET['import_error']);
            $message = '';
            
            switch ($error) {
                case 'no_file':
                    $message = __('Please select a CSV file to import.', 'edu-results');
                    break;
                case 'invalid_file':
                    $message = __('Invalid file type. Please upload a CSV file.', 'edu-results');
                    break;
                case 'read_error':
                    $message = __('Unable to read the CSV file.', 'edu-results');
                    break;
                case 'no_headers':
                    $message = __('CSV file is empty or has no headers.', 'edu-results');
                    break;
                case 'invalid_headers':
                    $message = __('CSV file has invalid or missing headers. Please use the export format.', 'edu-results');
                    break;
                default:
                    $message = __('An error occurred during import.', 'edu-results');
            }
            
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }
    }
}
