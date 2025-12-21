<?php

namespace CBEDU\Admin\PostTypes;

class Results {
    
    protected $prefix;
    
    public function __construct() {
        $this->prefix = CBEDU_PREFIX;
        add_action( 'init', [ $this, 'register_results_post_type' ] );
    }
    
    public function register_results_post_type()
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
}