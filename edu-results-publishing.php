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

    public function cbedu_result_assets_enque_admin()
    {
        wp_enqueue_media();

        wp_enqueue_script('cbedu-custom-fields', CBEDU_RESULT_URL . 'assets/js/admin.js', array('jquery'), '1.0.0', true);

        wp_localize_script('cbedu-custom-fields', 'cbedu_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_register_number_nonce')
        ));
    }

    public function loadTextDomain()
    {
        load_plugin_textdomain($this->getTextDomain(), false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function cbedu_results_assets_enqueue()
    {
        wp_enqueue_style('cbedu-results-style', plugins_url('/assets/css/style.css', __FILE__));
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
