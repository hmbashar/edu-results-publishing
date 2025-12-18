<?php 
namespace cbedu\inc\lib;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CBEDU_CUSTOM_POSTS{
    private $prefix;

    public function __construct($prefix) {
        $this->prefix = $prefix;

        add_action('init', array($this, 'register_custom_post_types'));
        
        // Add Subjects as a submenu
        add_action('admin_menu', array($this, 'addPostTypeSubMenu'));
    }

    public function addPostTypeSubMenu()
    {
        add_submenu_page(
            'edit.php?post_type=' . $this->prefix . 'results',
            'Subjects',
            'Subjects',
            'manage_options',
            'edit.php?post_type=' . $this->prefix . 'subjects'
        );
        add_submenu_page(
            'edit.php?post_type=' . $this->prefix . 'results', // Change this to the slug of your top-level menu
            __('Students', 'edu-students'),
            __('Students', 'edu-students'),
            'manage_options',
            'edit.php?post_type=' . $this->prefix . 'students'
        );
    }
    public function register_custom_post_types() {
        $this->register_results_post_type();
        $this->register_subjects_post_type();
        $this->register_students_post_type();
    }


    private function register_results_post_type()
    {
        $labels = array(
            'name' => __('Results', 'edu-results'),
            'singular_name' => __('Result', 'edu-results'),
            'menu_name' => __('EDU Results', 'edu-results'),
            'add_new' => __('Add New Result', 'edu-results'),
            'add_new_item' => __('Add New Result', 'edu-results'),
            'edit_item' => __('Edit Result', 'edu-results'),
            'new_item' => __('New Result', 'edu-results'),
            'view_item' => __('View Result', 'edu-results'),            
            'item_published'           => __( 'Result published.', 'edu-results' ), 
            'item_published_privately' => __( 'Result published privately.', 'edu-results' ), 
            'item_reverted_to_draft'   => __( 'Result reverted to draft.', 'edu-results' ), 
            'item_scheduled'           => __( 'Result scheduled.', 'edu-results' ), 
            'item_updated'             => __( 'Result updated.', 'edu-results' ), 
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
            'rewrite' => array('slug' => 'edu-results'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-book',
            'supports' => array('title', 'thumbnail'),
        );
        register_post_type($this->prefix . 'results', $args);
    }
    private function register_subjects_post_type()
    {
        $labels = array(
            'name' => __('Subjects', 'edu-subjects'),
            'singular_name' => __('Subject', 'edu-subjects'),
            'item_published'           => __( 'Subject published.', 'edu-results' ), 
            'item_published_privately' => __( 'Subject published privately.', 'edu-results' ), 
            'item_reverted_to_draft'   => __( 'Subject reverted to draft.', 'edu-results' ), 
            'item_scheduled'           => __( 'Subject scheduled.', 'edu-results' ), 
            'item_updated'             => __( 'Subject updated.', 'edu-results' ), 
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
            'supports' => array('title', 'thumbnail' ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
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

    private function register_students_post_type()
    {
        $labels = array(
            'name' => __('Students', 'edu-students'),
            'singular_name' => __('Student', 'edu-students'),
            'item_published' => __('Student published.', 'edu-students'),
            'item_published_privately' => __('Student private.', 'edu-students'),
            'item_reverted_to_draft' => __('Student profile reverted to draft.', 'edu-students'),
            'item_scheduled' => __('Student scheduled.', 'edu-students'),
            'item_updated' => __('Student profile updated.', 'edu-students'),
            'menu_name' => __('Students', 'edu-students'),
            'add_new' => __('Add New Students', 'edu-students'),
            'add_new_item' => __('Add New Student', 'edu-students'),
            'edit_item' => __('Edit Student', 'edu-students'),
            'new_item' => __('New Student', 'edu-students'),
            'view_item' => __('View Student', 'edu-students'),
            'search_items' => __('Search Students', 'edu-students'),
            'not_found' => __('No Students found', 'edu-students'),
            'not_found_in_trash' => __('No Students found in Trash', 'edu-students'),
            'parent_item_colon' => __('Parent Student:', 'edu-students'),
            'all_items' => __('All Students', 'edu-students'),
            'archives' => __('Student Archives', 'edu-students'),
            'insert_into_item' => __('Insert into Student', 'edu-students'),
            'uploaded_to_this_item' => __('Uploaded to this Student', 'edu-students'),
            'featured_image' => __('Student Image', 'edu-students'),
            'set_featured_image' => __('Set Student Picture', 'edu-students'),
            'remove_featured_image' => __('Remove Student Picture', 'edu-students'),
            'use_featured_image' => __('Use as Student Picture', 'edu-students'),
            'menu_icon' => 'dashicons-welcome-learn-more', // Change the icon if needed
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'edu-students'),
            'supports' => array('title', 'thumbnail', ),
        );        

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'query_var' => true,
            'rewrite' => array('slug' => 'edu-students'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'thumbnail'),            
        );

        register_post_type($this->prefix . 'students', $args);
    }
}
