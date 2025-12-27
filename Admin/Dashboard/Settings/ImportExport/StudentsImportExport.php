<?php
namespace CBEDU\Admin\Dashboard\Settings\ImportExport;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class StudentsImportExport
{
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
        $this->register_ajax_handlers();
    }

    /**
     * Register AJAX handlers
     */
    public function register_ajax_handlers()
    {
        add_action('wp_ajax_cbedu_export_students', array($this, 'ajax_export_students'));
        add_action('wp_ajax_cbedu_import_students', array($this, 'ajax_import_students'));
    }

    /**
     * Handle AJAX CSV export
     */
    public function ajax_export_students()
    {
        // Security checks
        check_ajax_referer('cbedu_export_students_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to export students.', 'edu-results')));
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
            wp_send_json_error(array('message' => __('No students found to export.', 'edu-results')));
        }

        // Prepare CSV headers
        $headers = array_merge(
            ['student_title'],
            $this->meta_fields,
            $this->taxonomies
        );

        // Create CSV content
        $csv_data = array();
        $csv_data[] = $headers;

        // Add data rows
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
            
            $csv_data[] = $row;
        }

        // Convert to CSV string
        $output = fopen('php://temp', 'r+');
        foreach ($csv_data as $row) {
            fputcsv($output, $row);
        }
        rewind($output);
        $csv_string = stream_get_contents($output);
        fclose($output);

        // Send success response with CSV data
        wp_send_json_success(array(
            'csv' => $csv_string,
            'filename' => 'students-export-' . date('Y-m-d-His') . '.csv',
            'count' => count($students)
        ));
    }

    /**
     * Handle AJAX CSV import
     */
    public function ajax_import_students()
    {
        // Security checks
        check_ajax_referer('cbedu_import_students_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to import students.', 'edu-results')));
        }

        // Check if file was uploaded
        if (!isset($_FILES['cbedu_import_file']) || $_FILES['cbedu_import_file']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error(array('message' => __('Please select a CSV file to import.', 'edu-results')));
        }

        $file = $_FILES['cbedu_import_file'];

        // Validate file extension
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_ext !== 'csv') {
            wp_send_json_error(array('message' => __('Invalid file type. Please upload a CSV file.', 'edu-results')));
        }

        // Validate MIME type for additional security
        $allowed_mime_types = array(
            'text/csv',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel'
        );
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime_type, $allowed_mime_types)) {
            wp_send_json_error(array('message' => __('Invalid file format. Please upload a valid CSV file.', 'edu-results')));
        }

        // Check file size (max 10MB)
        $max_file_size = 10 * 1024 * 1024; // 10MB in bytes
        if ($file['size'] > $max_file_size) {
            wp_send_json_error(array('message' => __('File size exceeds 10MB limit.', 'edu-results')));
        }

        // Read CSV file
        $handle = fopen($file['tmp_name'], 'r');
        if ($handle === false) {
            wp_send_json_error(array('message' => __('Unable to read the CSV file.', 'edu-results')));
        }

        // Get headers
        $headers = fgetcsv($handle);
        
        if (empty($headers)) {
            fclose($handle);
            wp_send_json_error(array('message' => __('CSV file is empty or has no headers.', 'edu-results')));
        }

        // Clean headers (remove BOM, trim whitespace)
        $headers = array_map(function($header) {
            // Remove BOM if present
            $header = str_replace("\xEF\xBB\xBF", '', $header);
            return trim($header);
        }, $headers);

        // Validate required headers
        $expected_headers = array_merge(
            ['student_title'],
            $this->meta_fields,
            $this->taxonomies
        );

        // Check if all required headers are present (order doesn't matter)
        $missing_headers = array_diff($expected_headers, $headers);
        if (!empty($missing_headers)) {
            fclose($handle);
            $error_msg = sprintf(
                __('CSV file has invalid or missing headers. Missing: %s', 'edu-results'),
                implode(', ', $missing_headers)
            );
            wp_send_json_error(array('message' => $error_msg));
        }

        // Process rows
        $imported = 0;
        $updated = 0;
        $errors = 0;
        $error_messages = array();
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
                $error_messages[] = sprintf(__('Row %d: Missing required fields (title or registration number)', 'edu-results'), $row_number);
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
                    $error_messages[] = sprintf(__('Row %d: Failed to create student', 'edu-results'), $row_number);
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

        // Send success response
        wp_send_json_success(array(
            'imported' => $imported,
            'updated' => $updated,
            'errors' => $errors,
            'error_messages' => $error_messages,
            'total' => $imported + $updated
        ));
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
     * Render Students section UI
     */
    public function render_section()
    {
        ?>
        <!-- Export Card -->
        <div class="cbedu-ie-card cbedu-ie-card-export">
            <div class="cbedu-ie-card-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15L12 3M12 15L8 11M12 15L16 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            
            <div class="cbedu-ie-card-header">
                <h3 class="cbedu-ie-card-title"><?php esc_html_e('Export Students', 'edu-results'); ?></h3>
                <p class="cbedu-ie-card-description"><?php esc_html_e('Download all student data as a CSV file', 'edu-results'); ?></p>
            </div>
            
            <div class="cbedu-ie-card-body">
                <div class="cbedu-ie-features">
                    <div class="cbedu-ie-feature-item">
                        <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?php esc_html_e('All published students', 'edu-results'); ?></span>
                    </div>
                    <div class="cbedu-ie-feature-item">
                        <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?php esc_html_e('17 custom meta fields', 'edu-results'); ?></span>
                    </div>
                    <div class="cbedu-ie-feature-item">
                        <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?php esc_html_e('All taxonomy terms', 'edu-results'); ?></span>
                    </div>
                    <div class="cbedu-ie-feature-item">
                        <svg class="cbedu-ie-feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span><?php esc_html_e('UTF-8 encoded CSV', 'edu-results'); ?></span>
                    </div>
                </div>
                
                <form method="post" action="" class="cbedu-ie-form cbedu-students-export-form">
                    <?php wp_nonce_field('cbedu_export_students_action', 'cbedu_export_students_nonce'); ?>
                    <button type="submit" name="cbedu_export_students" class="cbedu-ie-btn cbedu-ie-btn-export">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 15L12 3M12 15L8 11M12 15L16 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span><?php esc_html_e('Export to CSV', 'edu-results'); ?></span>
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
                <h3 class="cbedu-ie-card-title"><?php esc_html_e('Import Students', 'edu-results'); ?></h3>
                <p class="cbedu-ie-card-description"><?php esc_html_e('Upload CSV file to create or update students', 'edu-results'); ?></p>
            </div>
            
            <div class="cbedu-ie-card-body">
                <form method="post" action="" enctype="multipart/form-data" class="cbedu-ie-form cbedu-students-import-form">
                    <?php wp_nonce_field('cbedu_import_students_action', 'cbedu_import_students_nonce'); ?>
                    
                    <div class="cbedu-ie-upload-area cbedu-students-upload-area">
                        <input type="file" name="cbedu_import_file" id="cbedu_students_import_file" accept=".csv" required class="cbedu-ie-file-input" />
                        <label for="cbedu_students_import_file" class="cbedu-ie-upload-label">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                <path d="M7 18C4.23858 18 2 15.7614 2 13C2 10.2386 4.23858 8 7 8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8C19.7614 8 22 10.2386 22 13C22 15.7614 19.7614 18 17 18M12 15V21M12 15L9 18M12 15L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="cbedu-ie-upload-text"><?php esc_html_e('Click to browse or drag CSV file here', 'edu-results'); ?></span>
                            <span class="cbedu-ie-upload-hint"><?php esc_html_e('Maximum file size: 10MB', 'edu-results'); ?></span>
                        </label>
                    </div>
                    
                    <div class="cbedu-ie-info-box">
                        <div class="cbedu-ie-info-header">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <strong><?php esc_html_e('Important Notes', 'edu-results'); ?></strong>
                        </div>
                        <ul class="cbedu-ie-info-list">
                            <li><?php esc_html_e('Students with matching registration numbers will be', 'edu-results'); ?> <strong><?php esc_html_e('updated', 'edu-results'); ?></strong></li>
                            <li><?php esc_html_e('New students will be', 'edu-results'); ?> <strong><?php esc_html_e('created', 'edu-results'); ?></strong> <?php esc_html_e('automatically', 'edu-results'); ?></li>
                            <li><?php esc_html_e('Use pipe', 'edu-results'); ?> (<code>|</code>) <?php esc_html_e('to separate multiple taxonomy terms', 'edu-results'); ?></li>
                            <li><?php esc_html_e('Required fields:', 'edu-results'); ?> <code>student_title</code>, <code>cbedu_result_std_registration_number</code></li>
                        </ul>
                    </div>
                    
                    <button type="submit" name="cbedu_import_students" class="cbedu-ie-btn cbedu-ie-btn-import">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 3L12 15M12 3L16 7M12 3L8 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L2 19C2 20.1046 2.89543 21 4 21L20 21C21.1046 21 22 20.1046 22 19L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span><?php esc_html_e('Import from CSV', 'edu-results'); ?></span>
                    </button>
                </form>
            </div>
        </div>
        <?php
    }
}
