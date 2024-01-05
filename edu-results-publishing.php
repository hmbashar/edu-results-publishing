<?php

/**
 * Plugin Name: EDU Results Publishing
 * Author: MD Abul Bashar
 * Author URI: https://facebook.com/hmbashar
 * Description: This plugin is for student exam results publishing.
 * Tags: Result, WP Result Plugin, EDU Results
 * Text Domain: edu-results
 * Version: 1.0
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Prefix: cbedu_ 
 */


/* @package edu-results
 * @since 1.0
 * @version 1.0
 * @author MD Abul Bashar
 * @link https://facebook.com/hmbashar
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly


//define URL
define('CBEDU_RESULT_URL', plugin_dir_url(__FILE__));
define('CBEDU_RESULT_DIR', plugin_dir_path(__FILE__));
define('CBEDU_PREFIX', 'cbedu_');

class CBEDUResultPublishing
{
    // Plugin prefix
    private $prefix;

    public function __construct()
    {
        $this->prefix = CBEDU_PREFIX;

        // Register plugin action links
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'addPluginActionLinks'));
        // Change placeholder
        add_filter('enter_title_here', array($this, 'changeTitlePlaceholder'));
        // Add admin assets
        add_action('admin_enqueue_scripts', array($this, 'cbedu_result_assets_enque_admin'));
        // Add plugin assets
        add_action('wp_enqueue_scripts', array($this, 'cbedu_results_assets_enqueue'));

        add_filter('post_updated_messages', array($this, 'cbedu_custom_post_publish_message'));

        // Register text domain for translation
        add_action('plugins_loaded', array($this, 'loadTextDomain'));

        // Initialize the plugin
        $this->initialize();

        $this->register_ajax_handlers();
    }

    public function getTextDomain()
    {
        return 'edu-results';
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    private function initialize()
    {
        // Register custom post types
        $custom_post_type = new \cbedu\inc\lib\CBEDU_CUSTOM_POSTS($this->prefix);
        // Register custom taxonomies
        $custom_post_type = new \cbedu\inc\lib\CBEDU_CUSTOM_TAXONOMY($this->prefix);
        // Instantiate other classes
        $repeaterCustomFields = new \cbedu\inc\RepeaterCF\CBEDURepeaterCustomFields($this);

        $adminSettingsFields = new \cbedu\inc\admin\settings\CBEDUResultSettings($this);
        $customFields = new \cbedu\inc\custom_fields\CBEDUCustomFields();
        $resultsShortcode = new \cbedu\inc\lib\CBEDUResultsShortcode();
    }

    public function addPluginActionLinks($links)
    {
        $donateLink = '<a href="https://www.buymeacoffee.com/hmbashar" target="_blank">' . __('Donate', 'edu-results') . '</a>';
        array_unshift($links, $donateLink);
        return $links;
    }

    public static function convert_marks_to_grade($marks)
    {
        if ($marks >= 80) {
            return array('A+', 5.00);
        } elseif ($marks >= 70) {
            return array('A', 4.00);
        } elseif ($marks >= 60) {
            return array('A-', 3.50);
        } elseif ($marks >= 50) {
            return array('B', 3.00);
        } elseif ($marks >= 40) {
            return array('C', 2.00);
        } elseif ($marks >= 33) {
            return array('D', 1.00);
        } else {
            return array('F', 0.00);
        }
    }

    public function changeTitlePlaceholder($title)
    {
        $screen = get_current_screen();
        if ($screen->post_type == $this->prefix . 'results' || $screen->post_type == $this->prefix . 'students') {
            $title = 'Enter Student Name';
        } elseif ($screen->post_type == $this->prefix . 'subjects') {
            $title = 'Enter Subject Name'; // Placeholder for Subjects post type
        }
        return $title;
    }

    public function cbedu_result_assets_enque_admin($hook_suffix)
    {
        global $post;
        wp_enqueue_media();

        wp_enqueue_script('cbedu-custom-fields', CBEDU_RESULT_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', true);

        wp_localize_script('cbedu-custom-fields', 'cbedu_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_register_number_nonce')
        ));

        //for autocomplete jquery in results post type with registration number
        if ($hook_suffix === 'post-new.php' || $hook_suffix === 'post.php') {
            if (get_post_type($post) === 'cbedu_results') {

                wp_enqueue_style('cbedu-autocomplete-ui-css', plugin_dir_url(__FILE__) . 'assets/css/autocomplete.css');
                wp_enqueue_script('cbedu-autocomplete-js', plugin_dir_url(__FILE__) . 'assets/js/autocomplete.js', array('jquery', 'jquery-ui-autocomplete'), '1.0.0', true);
                wp_localize_script('cbedu-autocomplete-js', 'cbedu_ajax_autocomplete_object', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    // Pass the nonce here
                    'auto_complete_nonce' => wp_create_nonce('cbedu_auto_complete_nonce')
                ));
            }
        }
    }


    public function loadTextDomain()
    {
        load_plugin_textdomain($this->getTextDomain(), false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function cbedu_results_assets_enqueue()
    {
        wp_enqueue_style('cbedu-results-style', plugins_url('/assets/css/style.css', __FILE__));

        //for ajax search
        wp_enqueue_script('cbedu-ajax-search-result', plugins_url('/assets/js/ajax-search-result.js', __FILE__), array('jquery'), '1.0.0', true);
        wp_localize_script('cbedu-ajax-search-result', 'cbedu_ajax_results_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_ajax_search_result_nonce') // Create a nonce for security
        ));

        //script for print
        wp_enqueue_script('cbedu-print-js', plugins_url('/assets/js/print.js', __FILE__), array('jquery'), '1.0.0', true);
    }

    public function cbedu_custom_post_publish_message($messages)
    {
        global $post, $post_ID;

        $post_type = get_post_type($post_ID);

        // Check if the post type is 'subjects'
        if ($post_type == 'cbedu_subjects') {
            $permalink = get_permalink($post_ID);

            // Customizing the 'Post published' message for 'subjects' post type
            $messages[$post_type] = array_fill(0, 11, ''); // reset array
            $messages[$post_type][1] = 'Subject published. <a href="' . esc_url($permalink) . '">View subject</a>';
            $messages[$post_type][6] = 'Subject published. <a href="' . esc_url($permalink) . '">View subject</a>';
        }
        // Check if the post type is 'results'
        if ($post_type == 'cbedu_results') {
            $permalink = get_permalink($post_ID);

            // Customizing the 'Post published' message for 'subjects' post type
            $messages[$post_type] = array_fill(0, 11, ''); // reset array
            $messages[$post_type][1] = 'Results published. <a href="' . esc_url($permalink) . '">View Results</a>';
            $messages[$post_type][6] = 'Results published. <a href="' . esc_url($permalink) . '">View Results</a>';
        }

        return $messages;
    }

    public function register_ajax_handlers()
    {
        add_action('wp_ajax_get_student_details_by_registration', array($this, 'get_student_details_by_registration_callback'));
        add_action('wp_ajax_nopriv_get_student_details_by_registration', array($this, 'get_student_details_by_registration_callback'));

        add_action('wp_ajax_cbedu_handle_form_submission', array($this, 'cbedu_handle_form_submission')); // ajax search result
        add_action('wp_ajax_nopriv_cbedu_handle_form_submission', array($this, 'cbedu_handle_form_submission')); //ajax search result
    }


    public function get_student_details_by_registration_callback()
    {
        // Check nonce for security
        check_ajax_referer('cbedu_register_number_nonce', 'security');

        $registration_number = sanitize_text_field($_POST['registration_number']);

        $args = array(
            'post_type' => 'cbedu_students',
            'meta_key' => 'cbedu_result_std_registration_number',
            'meta_value' => $registration_number,
            'posts_per_page' => 1
        );

        $students = get_posts($args);

        if (!empty($students)) {
            // Get the student's name, check if it's not empty
            $student_name = !empty($students[0]->post_title) ? $students[0]->post_title : 'Not Found!';

            // Get the father's name, check if it's not empty
            $father_name = get_post_meta($students[0]->ID, 'cbedu_result_std_father_name', true);
            $father_name = !empty($father_name) ? $father_name : 'Not Found!';

            // Get the father's name, check if it's not empty
            $mother_name = get_post_meta($students[0]->ID, 'cbedu_result_std_mother_name', true);
            $mother_name = !empty($mother_name) ? $mother_name : 'Not Found!';

            // Output both names as JSON
            wp_send_json([
                'studentName' => esc_html($student_name),
                'fathersName' => esc_html($father_name),
                'mothersName' => esc_html($mother_name)
            ]);
        } else {
            wp_send_json(['studentName' => 'Not Found!', 'fathersName' => 'Not Found!', 'mothersName' => 'Not Found!']);
        }

        wp_die(); // This is required to terminate immediately and return a proper response
    }

    /**
     * Handles form submission for the cbedu_handle_form_submission function.
     * result form ajax callback function
     *
     */
    function cbedu_handle_form_submission() {
        // Check nonce for security
        check_ajax_referer('cbedu_ajax_search_result_nonce', 'nonce');
    
        // Server-side validation for required fields
        $required_fields = array('registration_number', 'roll', 'examination', 'year', 'board', 'department_group');
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo '<p>Error: Field "' . esc_html($field) . '" is required.</p>';
                wp_die();
            }
        }
    
        // Sanitize and assign input values
        $registration_number = sanitize_text_field($_POST['registration_number']);
        $roll_number = sanitize_text_field($_POST['roll']);
        $cbedu_examination = sanitize_text_field($_POST['examination']);
        $cbedu_year = sanitize_text_field($_POST['year']);
        $cbedu_board = sanitize_text_field($_POST['board']);
        $cbedu_department_group = sanitize_text_field($_POST['department_group']);
    
        // Prepare the query arguments for 'cbedu_results'
        $args = array(
            'post_type' => 'cbedu_results',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'cbedu_examinations',
                    'field' => 'slug',
                    'terms' => $cbedu_examination,
                ),
                array(
                    'taxonomy' => 'cbedu_session_years',
                    'field' => 'slug',
                    'terms' => $cbedu_year,
                ),
                array(
                    'taxonomy' => 'cbedu_boards',
                    'field' => 'slug',
                    'terms' => $cbedu_board,
                ),
                array(
                    'taxonomy' => 'cbedu_department_group',
                    'field' => 'slug',
                    'terms' => $cbedu_department_group,
                ),
            ),
            'meta_query' => array(
                array(
                    'key' => 'cbedu_result_std_registration_number',
                    'value' => $registration_number,
                    'compare' => '='
                ),
                array(
                    'key' => 'cbedu_result_std_roll',
                    'value' => $roll_number,
                    'compare' => '='
                ),
            ),
        );
    
        // Execute the query for 'cbedu_results'
        $query = new WP_Query($args);
    
        // Output the results
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $collageName = get_option('cbedu_results_collage_name');

                $rs_std_roll = get_post_meta(get_the_ID(), 'cbedu_result_std_roll', true);
                $rs_std_reg_number = get_post_meta(get_the_ID(), 'cbedu_result_std_registration_number', true);
                $rs_std_type = get_post_meta(get_the_ID(), 'cbedu_result_std_student_type', true);
                $rs_std_result_status = get_post_meta(get_the_ID(), 'cbedu_result_std_result_status', true);
                $rs_std_gpa = get_post_meta(get_the_ID(), 'cbedu_result_std_gpa', true);
    

                 // Fetch taxonomy term names
                $session_year_terms = wp_get_post_terms(get_the_ID(), 'cbedu_session_years', array('fields' => 'names'));
                $examination_terms = wp_get_post_terms(get_the_ID(), 'cbedu_examinations', array('fields' => 'names'));
                $board_terms = wp_get_post_terms(get_the_ID(), 'cbedu_boards', array('fields' => 'names'));
                $department_group_terms = wp_get_post_terms(get_the_ID(), 'cbedu_department_group', array('fields' => 'names'));

                // Convert term arrays to strings
                $session_year = !empty($session_year_terms) ? implode(', ', $session_year_terms) : '';
                $examination = !empty($examination_terms) ? implode(', ', $examination_terms) : '';
                $board = !empty($board_terms) ? implode(', ', $board_terms) : '';
                $department_group = !empty($department_group_terms) ? implode(', ', $department_group_terms) : '';


                // New Query: Find corresponding student in 'cbedu_students'
                $student_args = array(
                    'post_type' => 'cbedu_students',
                    'posts_per_page' => 1,
                    'meta_query' => array(
                        array(
                            'key' => 'cbedu_result_std_registration_number',
                            'value' => $registration_number,
                            'compare' => '='
                        )
                    )
                );
    
                $student_query = new WP_Query($student_args);
    
                if ($student_query->have_posts()) {
                    while ($student_query->have_posts()) {
                        $student_query->the_post();
    
                        // Fetch all student details
                        $st_father_name = get_post_meta(get_the_ID(), 'cbedu_result_std_father_name', true);
                        $st_mother_name = get_post_meta(get_the_ID(), 'cbedu_result_std_mother_name', true);
                        $st_std_id = get_post_meta(get_the_ID(), 'cbedu_result_std_id', true);                        
                        $st_std_dob = get_post_meta(get_the_ID(), 'cbedu_result_std_dob', true);
                        $st_std_gender = get_post_meta(get_the_ID(), 'cbedu_result_std_gender', true);
                        
    
                        // Display the results in a table
                        ?>
                        <div class="cbedu-ajax-result-area" id="cbedu-result-table">
                            <div class="cbedu-ajax-result">
                                <!--Student Information-->
                                <div class="cbedu-result-student-information-area">
                                    <div class="cbedu-result-student-information-heading">
                                        <h4>Student Information</h4>
                                    </div>
                                    <table>
                                        <tr>
                                            <th>Roll:</th>
                                            <td><?php echo esc_html($rs_std_roll); ?></td>
                                            <th>Registration Number:</th>
                                            <td><?php echo esc_html($rs_std_reg_number); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td><?php the_title(); ?></td>
                                            <th>ID:</th>
                                            <td><?php echo esc_html($st_std_id); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Father's Name:</th>
                                            <td><?php echo esc_html($st_father_name); ?></td>
                                            <th>Mother's Name:</th>
                                            <td><?php echo esc_html($st_mother_name); ?></td>                                            
                                        </tr> 
                                        <tr>
                                            <th>Board:</th>
                                            <td><?php echo esc_html($board); ?></td>
                                            <th>Group:</th>
                                            <td><?php echo esc_html($department_group); ?></td>                                            
                                        </tr>
                                        <tr>
                                            <th>Session:</th>
                                            <td><?php echo esc_html($session_year); ?></td>
                                            <th>Result:</th>
                                            <td><?php echo esc_html($rs_std_result_status); ?></td>                                            
                                        </tr>
                                        <tr>
                                            <th>Gender:</th>
                                            <td><?php echo esc_html($st_std_gender); ?></td>
                                            <th>DOB:</th>
                                            <td><?php echo esc_html($st_std_dob); ?></td>                                            
                                        </tr> 
                                        <tr>
                                            <th>Student Type:</th>
                                            <td><?php echo esc_html($rs_std_type); ?></td>
                                            <th>Institute:</th>
                                            <td><?php echo esc_html($collageName); ?></td>                                            
                                        </tr> 
                                        <tr>
                                            <th>Examinations:</th>
                                            <td><?php echo esc_html($examination); ?></td>
                                            <th>GPA:</th>
                                            <td><?php echo esc_html($rs_std_gpa); ?></td>                                            
                                        </tr>
                                    </table>
                                </div><!--/ Student Information-->
                            </div>                                                   
                        </div>
                        <div class="cbedu-print-button-container">
                            <button onclick="cbeduPrintResult('cbedu-result-table')">Print</button>
                        </div>  
                        <?php
                    }
                }
    
                wp_reset_postdata(); // Reset student query
            }
        } else {
            echo '<p>No results found for the selected examination.</p>';
        }
    
        wp_reset_postdata(); // Reset main query
    
        wp_die(); // Terminate the script and return a proper response
    }    

    
}

function convert_marks_to_grade($marks)
{
    if ($marks >= 80) {
        return array('A+', 5.00);
    } elseif ($marks >= 70) {
        return array('A', 4.00);
    } elseif ($marks >= 60) {
        return array('A-', 3.50);
    } elseif ($marks >= 50) {
        return array('B', 3.00);
    } elseif ($marks >= 40) {
        return array('C', 2.00);
    } elseif ($marks >= 33) {
        return array('D', 1.00);
    } else {
        return array('F', 0.00);
    }
}



require_once CBEDU_RESULT_DIR . 'inc/custom-fields.php';

require_once CBEDU_RESULT_DIR . 'inc/admin/settings.php';

require_once CBEDU_RESULT_DIR . 'inc/RepeaterCF.php';

require_once CBEDU_RESULT_DIR . 'inc/lib/shortcode.php';

require_once CBEDU_RESULT_DIR . 'inc/lib/custom-posts.php';

require_once CBEDU_RESULT_DIR . 'inc/lib/custom-taxonomy.php';

//init the main class
$CBEDUResultPublishing = new CBEDUResultPublishing();
