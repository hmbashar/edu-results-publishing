<?php 
namespace cbedu\inc\lib;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CBEDU_CUSTOM_POSTS{
    private $prefix;

    public function __construct($prefix) {
        $this->prefix = $prefix;

        add_action('init', array($this, 'register_custom_post_types'));
        
        // Add Subjects as a submenu
        add_action('admin_menu', array($this, 'addSubjectsSubMenu'));
    }

    public function addSubjectsSubMenu()
    {
        add_submenu_page(
            'edit.php?post_type=' . $this->prefix . 'results',
            'Subjects',
            'Subjects',
            'manage_options',
            'edit.php?post_type=' . $this->prefix . 'subjects'
        );
    }
    public function register_custom_post_types() {
        $this->register_results_post_type();
        $this->register_subjects_post_type();
    }


    private function register_results_post_type()
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
    private function register_subjects_post_type()
    {
        $labels = array(
            'name' => __('Subjects', 'edu-subjects'),
            'singular_name' => __('Subject', 'edu-subjects'),
            'menu_name' => __('EDU Subjects', 'edu-subjects'),
            'add_new' => __('Add New', 'edu-subjects'),
            'add_new_item' => __('Add New Subject', 'edu-subjects'),
            'edit_item' => __('Edit Subject', 'edu-subjects'),
            'new_item' => __('New Subject', 'edu-subjects'),
            'view_item' => __('View Subject', 'edu-subjects'),
            'search_items' => __('Search Subjects', 'edu-subjects'),
            'not_found' => __('No Subjects found', 'edu-subjects'),
            'not_found_in_trash' => __('No Subjects found in Trash', 'edu-subjects'),
            'parent_item_colon' => __('Parent Subject:', 'edu-subjects'),
            'all_items' => __('All Subjects', 'edu-subjects'),
            'archives' => __('Subject Archives', 'edu-subjects'),
            'insert_into_item' => __('Insert into Subject', 'edu-subjects'),
            'uploaded_to_this_item' => __('Uploaded to this Subject', 'edu-subjects'),
            'featured_image' => __('Subject Image', 'edu-subjects'),
            'set_featured_image' => __('Set Subject Picture', 'edu-subjects'),
            'remove_featured_image' => __('Remove Subject Picture', 'edu-subjects'),
            'use_featured_image' => __('Use as Subject Picture', 'edu-subjects'),
            'menu_icon' => 'dashicons-book-alt',
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'edu-subjects'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-book-alt',
            'supports' => array('title'),
        );
        register_post_type($this->prefix . 'subjects', $args);
    }
}
