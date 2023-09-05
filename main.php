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
    private $textdomain;

    public function __construct()
    {
        $this->prefix = 'edu_';
        $this->textdomain = 'edu-results';

        add_action('init', array($this, 'registerPostType'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'addPluginActionLinks'));

        add_filter('enter_title_here', array($this, 'changeTitlePlaceholder'));

        add_action('admin_enqueue_scripts', array($this, 'edu_result_assets_enque_admin'));
    }

    public function getTextDomain()
    {
        return $this->textdomain;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function registerPostType()
    {
        $labels = array(
            'name' => __('Results', $this->textdomain),
            'singular_name' => __('Result', $this->textdomain),
            'menu_name' => __('EDU Results', $this->textdomain),
            'add_new' => __('Add New', $this->textdomain),
            'add_new_item' => __('Add New Result', $this->textdomain),
            'edit_item' => __('Edit Result', $this->textdomain),
            'new_item' => __('New Result', $this->textdomain),
            'view_item' => __('View Result', $this->textdomain),
            'search_items' => __('Search Results', $this->textdomain),
            'not_found' => __('No Results found', $this->textdomain),
            'not_found_in_trash' => __('No Results found in Trash', $this->textdomain),
            'parent_item_colon' => __('Parent Result:', $this->textdomain),
            'all_items' => __('All Results', $this->textdomain),
            'archives' => __('Result Archives', $this->textdomain),
            'insert_into_item' => __('Insert into Result', $this->textdomain),
            'uploaded_to_this_item' => __('Uploaded to this Result', $this->textdomain),
            'featured_image' => __('Student Image', $this->textdomain),
            'set_featured_image' => __('Set Student Picture', $this->textdomain),
            'remove_featured_image' => __('Remove Student Picture', $this->textdomain),
            'use_featured_image' => __('Use as Student Picture', $this->textdomain),
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
        $donateLink = '<a href="https://www.buymeacoffee.com/hmbashar" target="_blank">' . __('Donate', $this->textdomain) . '</a>';
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