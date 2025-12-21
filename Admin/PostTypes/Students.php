<?php

namespace CBEDU\Admin\PostTypes;

class Students
{
    public function __construct()
    {
        add_action( 'init', [ $this, 'register_custom_post_type' ] );
    }

    public function register_custom_post_type()
    {
        $this->students_post_type();
    }

        private function students_post_type()
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

        register_post_type( CBEDU_PREFIX . 'students', $args);
    }
}