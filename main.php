<?php
/**
 * Plugin Name: EDU Results Publishing
 * Author: MD Abul Bashar
 * Author URI: https://facebook.com/hmbashar
 * Description: This plugin is for student exam results publishing.
 * Tags: Result, WP Result Plugin, EDU Results
 * Text Domain: edu-results
 */

 define('EDU_RESULT_DIR', plugin_dir_path(__FILE__));

class EDUResultPublishing
{
    private $prefix;

    public function __construct()
    {
        $this->prefix = 'edu_';

        add_action('init', array($this, 'registerPostType'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'addPluginActionLinks'));

        add_filter('enter_title_here', array($this, 'changeTitlePlaceholder'));

        add_action('admin_enqueue_scripts', array($this, 'edu_result_assets_enque_admin'));
    }

    public function getTextDomain()
    {
        return 'edu-results';
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function registerPostType()
    {
        $labels = array(
            'name' => __('Results', 'edu-results'),
            'singular_name' => __('Result', 'edu-results'),
            'menu_name' => __('EDU Results', 'edu-results'),
            'add_new' => __('Add New', 'edu-results'),
            'add_new_item' => __('Add New Result', 'edu-results'),
            'edit_item' => __('Edit Result', 'edu-results'),
            'new_item' => __('New Result', 'edu-results'),
            'view_item' => __('View Result', 'edu-results'),
            'search_items' => __('Search Results', 'edu-results'),
            'not_found' => __('No Results found', 'edu-results'),
            'not_found_in_trash' => __('No Results found in Trash', 'edu-results'),
            'parent_item_colon' => __('Parent Result:', 'edu-results'),
            'all_items' => __('All Results', 'edu-results'),
            'archives' => __('Result Archives', 'edu-results'),
            'insert_into_item' => __('Insert into Result', 'edu-results'),
            'uploaded_to_this_item' => __('Uploaded to this Result', 'edu-results'),
            'featured_image' => __('Student Image', 'edu-results'),
            'set_featured_image' => __('Set Student Picture', 'edu-results'),
            'remove_featured_image' => __('Remove Student Picture', 'edu-results'),
            'use_featured_image' => __('Use as Student Picture', 'edu-results'),
            'menu_icon' => 'dashicons-book',
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'edu-results'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-book',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        );

        register_post_type($this->prefix . 'results', $args);
    }


    public function addPluginActionLinks($links)
    {
        $donateLink = '<a href="https://www.buymeacoffee.com/hmbashar" target="_blank">' . __('Donate', 'edu-results') . '</a>';
        array_unshift($links, $donateLink);
        return $links;
    }


    public function changeTitlePlaceholder($title)
    {
        $screen = get_current_screen();
        if ($screen->post_type == $this->prefix . 'results') {
            $title = 'Student Name';
        }
        return $title;
    }

    public function edu_result_assets_enque_admin()
    {
        wp_enqueue_media();
    }
}





require_once EDU_RESULT_DIR . 'inc/custom-fields.php';

require_once EDU_RESULT_DIR . 'inc/admin/settings.php';

require_once EDU_RESULT_DIR . 'inc/RepeaterCF.php';

require_once EDU_RESULT_DIR . 'inc/lib/shortcode.php';

//init the main class
$eduResultPublishing = new EDUResultPublishing();
// Instantiate the EDURepeaterCustomFields class
$repeaterCustomFields = new \inc\RepeaterCF\EDURepeaterCustomFields($eduResultPublishing);
$repeaterCustomFields = new \inc\admin\settings\EDUResultSettings($eduResultPublishing);


$custom_fields = new \inc\custom_fields\EDUCustomFields();