<?php

namespace CBEDU\Admin\PostTypes;

class Subjects {

    
    public function __construct() {
        add_action( 'init', [ $this, 'registerHooks' ] );        
    }
    
    public function registerHooks() {            
        $this->register_subjects_post_type();
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
        register_post_type(CBEDU_PREFIX . 'subjects', $args);
    }

}