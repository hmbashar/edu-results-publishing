<?php
namespace CBEDU\Admin\Dashboard\Settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ImportExport
{
    private $prefix;
    private $post_type = 'cbedu_students';
    
    // All meta fields for students
    private $meta_fields = [
        'cbedu_result_std_id',
        'cbedu_result_std_registration_number',
        'cbedu_result_std_father_name',
        'cbedu_result_std_mother_name',
        'cbedu_result_std_dob',
        'cbedu_result_std_gender',
        'cbedu_result_std_phone',
        'cbedu_result_std_email',
        'cbedu_result_std_blood_group',
        'cbedu_result_std_address',
        'cbedu_result_std_guardian_phone',
        'cbedu_result_std_fathers_qualification',
        'cbedu_result_std_fathers_occupation',
        'cbedu_result_std_mothers_occupation',
        'cbedu_result_std_mothers_qualification',
        'cbedu_result_std_birth_registration_number',
        'cbedu_result_std_nid_number',
    ];
    
    // Taxonomies for students
    private $taxonomies = [
        'cbedu_session_years',
        'cbedu_examinations',
        'cbedu_boards',
        'cbedu_department_group',
    ];

    public function __construct()
    {
        $this->prefix = CBEDU_PREFIX;
        
        // Handle export
        add_action('admin_init', array($this, 'handle_export'));
        
        // Handle import
        add_action('admin_init', array($this, 'handle_import'));
    }

    /**
     * Handle CSV export
     */
    public function handle_export()
    {
        if (!isset($_POST['cbedu_export_students'])) {
            return;
        }

        // Security checks
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to export students.', 'edu-results'));
        }

        if (!isset($_POST['cbedu_export_nonce']) || !wp_verify_nonce($_POST['cbedu_export_nonce'], 'cbedu_export_students_action')) {
            wp_die(__('Security check failed.', 'edu-results'));
        }

        // Get all students
        $args = array(
            'post_type' => $this->post_type,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        );

        $students = get_posts($args);

        if (empty($students)) {
            wp_redirect(add_query_arg('export_error', 'no_students', wp_get_referer()));
            exit;
        }

        // Prepare CSV headers
        $headers = array_merge(
            ['student_title'],
            $this->meta_fields,
            $this->taxonomies
        );

        // Set headers for download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=students-export-' . date('Y-m-d-His') . '.csv');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Write headers
        fputcsv($output, $headers);

        // Write data
        foreach ($students as $student) {
            $row = array();
            
            // Add title
            $row[] = $student->post_title;
            
            // Add meta fields
            foreach ($this->meta_fields as $meta_key) {
                $row[] = get_post_meta($student->ID, $meta_key, true);
            }
            
            // Add taxonomies
            foreach ($this->taxonomies as $taxonomy) {
                $terms = wp_get_post_terms($student->ID, $taxonomy, array('fields' => 'names'));
                $row[] = is_array($terms) ? implode('|', $terms) : '';
            }
            
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    /**
     * Handle CSV import
     */
    public function handle_import()
    {
        if (!isset($_POST['cbedu_import_students'])) {
            return;
        }

        // Security checks
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to import students.', 'edu-results'));
        }

        if (!isset($_POST['cbedu_import_nonce']) || !wp_verify_nonce($_POST['cbedu_import_nonce'], 'cbedu_import_students_action')) {
            wp_die(__('Security check failed.', 'edu-results'));
        }

        // Check if file was uploaded
        if (!isset($_FILES['cbedu_import_file']) || $_FILES['cbedu_import_file']['error'] !== UPLOAD_ERR_OK) {
            wp_redirect(add_query_arg('import_error', 'no_file', wp_get_referer()));
            exit;
        }

        $file = $_FILES['cbedu_import_file'];

        // Validate file type
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_ext !== 'csv') {
            wp_redirect(add_query_arg('import_error', 'invalid_file', wp_get_referer()));
            exit;
        }

        // Read CSV file
        $handle = fopen($file['tmp_name'], 'r');
        if ($handle === false) {
            wp_redirect(add_query_arg('import_error', 'read_error', wp_get_referer()));
            exit;
        }

        // Get headers
        $headers = fgetcsv($handle);
        
        if (empty($headers)) {
            fclose($handle);
            wp_redirect(add_query_arg('import_error', 'no_headers', wp_get_referer()));
            exit;
        }

        // Validate required headers
        $expected_headers = array_merge(
            ['student_title'],
            $this->meta_fields,
            $this->taxonomies
        );

        // Check if all required headers are present
        $missing_headers = array_diff($expected_headers, $headers);
        if (!empty($missing_headers)) {
            fclose($handle);
            wp_redirect(add_query_arg('import_error', 'invalid_headers', wp_get_referer()));
            exit;
        }

        // Process rows
        $imported = 0;
        $updated = 0;
        $errors = 0;
        $row_number = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row_number++;
            
            // Skip empty rows
            if (empty(array_filter($data))) {
                continue;
            }

            // Combine headers with data
            $row_data = array_combine($headers, $data);

            // Validate required fields
            if (empty($row_data['student_title']) || empty($row_data['cbedu_result_std_registration_number'])) {
                $errors++;
                continue;
            }

            // Check if student exists by registration number
            $existing_student = $this->get_student_by_registration_number($row_data['cbedu_result_std_registration_number']);

            if ($existing_student) {
                // Update existing student
                $post_id = $existing_student->ID;
                
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => sanitize_text_field($row_data['student_title']),
                ));
                
                $updated++;
            } else {
                // Create new student
                $post_id = wp_insert_post(array(
                    'post_title' => sanitize_text_field($row_data['student_title']),
                    'post_type' => $this->post_type,
                    'post_status' => 'publish',
                ));

                if (is_wp_error($post_id)) {
                    $errors++;
                    continue;
                }
                
                $imported++;
            }

            // Update meta fields
            foreach ($this->meta_fields as $meta_key) {
                if (isset($row_data[$meta_key])) {
                    $value = $row_data[$meta_key];
                    
                    // Sanitize based on field type
                    if ($meta_key === 'cbedu_result_std_email') {
                        $value = sanitize_email($value);
                    } elseif ($meta_key === 'cbedu_result_std_address') {
                        $value = sanitize_textarea_field($value);
                    } else {
                        $value = sanitize_text_field($value);
                    }
                    
                    update_post_meta($post_id, $meta_key, $value);
                }
            }

            // Update taxonomies
            foreach ($this->taxonomies as $taxonomy) {
                if (isset($row_data[$taxonomy]) && !empty($row_data[$taxonomy])) {
                    $terms = explode('|', $row_data[$taxonomy]);
                    $terms = array_map('trim', $terms);
                    $terms = array_filter($terms);
                    
                    if (!empty($terms)) {
                        wp_set_object_terms($post_id, $terms, $taxonomy);
                    }
                }
            }
        }

        fclose($handle);

        // Redirect with success message
        $redirect_args = array(
            'import_success' => 1,
            'imported' => $imported,
            'updated' => $updated,
            'errors' => $errors,
        );

        wp_redirect(add_query_arg($redirect_args, wp_get_referer()));
        exit;
    }

    /**
     * Get student by registration number
     */
    private function get_student_by_registration_number($registration_number)
    {
        $args = array(
            'post_type' => $this->post_type,
            'posts_per_page' => 1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'cbedu_result_std_registration_number',
                    'value' => $registration_number,
                    'compare' => '=',
                ),
            ),
        );

        $students = get_posts($args);
        return !empty($students) ? $students[0] : null;
    }

    /**
     * Render import/export tab content
     */
    public function render_import_export_tab()
    {
        ?>
        <div class="cbedu-import-export-wrapper">
            
            <!-- Hero Section -->
            <div class="cbedu-ie-hero">
                <div class="cbedu-ie-hero-content">
                    <h2 class="cbedu-ie-hero-title">📊 Student Data Management</h2>
                    <p class="cbedu-ie-hero-subtitle">Seamlessly import and export student records with all custom fields and taxonomies</p>
                </div>
            </div>

            <div class="cbedu-ie-grid">
                
                <!-- Export Card -->
                <div class="cbedu-ie-card cbedu-ie-card-export">
                    <div class="cbedu-ie-card-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 15L12 3M12 15L8 11M12 15L16 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    
                    <div class="cbedu-ie-card-header">
                        <h3 class="cbedu-ie-card-title">Export Students</h3>
                        <p class="cbedu-ie-card-description">Download all student data as a CSV file</p>
                    </div>
                    
                    <div class="cbedu-ie-card-body">
                        <div class="cbedu-ie-features">
                            <div class="cbedu-ie-feature-item">
                                <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>All published students</span>
                            </div>
                            <div class="cbedu-ie-feature-item">
                                <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>17 custom meta fields</span>
                            </div>
                            <div class="cbedu-ie-feature-item">
                                <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>All taxonomy terms</span>
                            </div>
                            <div class="cbedu-ie-feature-item">
                                <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span>UTF-8 encoded CSV</span>
                            </div>
                        </div>
                        
                        <form method="post" action="" class="cbedu-ie-form">
                            <?php wp_nonce_field('cbedu_export_students_action', 'cbedu_export_nonce'); ?>
                            <button type="submit" name="cbedu_export_students" class="cbedu-ie-btn cbedu-ie-btn-export">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 15L12 3M12 15L8 11M12 15L16 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span>Export to CSV</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Import Card -->
                <div class="cbedu-ie-card cbedu-ie-card-import">
                    <div class="cbedu-ie-card-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 3L12 15M12 3L16 7M12 3L8 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    
                    <div class="cbedu-ie-card-header">
                        <h3 class="cbedu-ie-card-title">Import Students</h3>
                        <p class="cbedu-ie-card-description">Upload CSV file to create or update students</p>
                    </div>
                    
                    <div class="cbedu-ie-card-body">
                        <form method="post" action="" enctype="multipart/form-data" class="cbedu-ie-form">
                            <?php wp_nonce_field('cbedu_import_students_action', 'cbedu_import_nonce'); ?>
                            
                            <div class="cbedu-ie-upload-area">
                                <input type="file" name="cbedu_import_file" id="cbedu_import_file" accept=".csv" required class="cbedu-ie-file-input" />
                                <label for="cbedu_import_file" class="cbedu-ie-upload-label">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                        <path d="M7 18C4.23858 18 2 15.7614 2 13C2 10.2386 4.23858 8 7 8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8C19.7614 8 22 10.2386 22 13C22 15.7614 19.7614 18 17 18M12 15V21M12 15L9 18M12 15L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span class="cbedu-ie-upload-text">Click to browse or drag CSV file here</span>
                                    <span class="cbedu-ie-upload-hint">Maximum file size: 10MB</span>
                                </label>
                            </div>
                            
                            <div class="cbedu-ie-info-box">
                                <div class="cbedu-ie-info-header">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <strong>Important Notes</strong>
                                </div>
                                <ul class="cbedu-ie-info-list">
                                    <li>Students with matching registration numbers will be <strong>updated</strong></li>
                                    <li>New students will be <strong>created</strong> automatically</li>
                                    <li>Use pipe (<code>|</code>) to separate multiple taxonomy terms</li>
                                    <li>Required fields: <code>student_title</code>, <code>cbedu_result_std_registration_number</code></li>
                                </ul>
                            </div>
                            
                            <button type="submit" name="cbedu_import_students" class="cbedu-ie-btn cbedu-ie-btn-import">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 3L12 15M12 3L16 7M12 3L8 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span>Import from CSV</span>
                            </button>
                        </form>
                    </div>
                </div>
                
            </div>
            
            <!-- Help Section -->
            <div class="cbedu-ie-help">
                <div class="cbedu-ie-help-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13M12 17H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="cbedu-ie-help-content">
                    <h4>Need Help?</h4>
                    <p>Export a sample CSV first to see the correct format, then modify it with your data and import it back.</p>
                </div>
            </div>
            
        </div>
        <?php
    }

    /**
     * Display admin notices for import/export
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
