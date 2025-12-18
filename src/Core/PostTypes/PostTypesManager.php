<?php
/**
 * Post Types Registration
 *
 * Registers all custom post types for the plugin
 *
 * @package    CBEDU
 * @subpackage Core/PostTypes
 * @since      1.3.0
 */

namespace CBEDU\Core\PostTypes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class PostTypesManager
 *
 * Handles registration of all custom post types
 */
class PostTypesManager
{
    /**
     * Plugin prefix
     *
     * @var string
     */
    private $prefix;

    /**
     * Constructor
     *
     * @param string $prefix Plugin prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
        add_action('init', array($this, 'registerPostTypes'));
        add_action('admin_menu', array($this, 'addSubMenuPages'));
    }

    /**
     * Register all custom post types
     *
     * @return void
     */
    public function registerPostTypes()
    {
        $this->registerResultsPostType();
        $this->registerSubjectsPostType();
        $this->registerStudentsPostType();
    }

    /**
     * Add custom post types as submenus
     *
     * @return void
     */
    public function addSubMenuPages()
    {
        add_submenu_page(
            'edit.php?post_type=' . $this->prefix . 'results',
            __('Subjects', 'edu-results'),
            __('Subjects', 'edu-results'),
            'manage_options',
            'edit.php?post_type=' . $this->prefix . 'subjects'
        );

        add_submenu_page(
            'edit.php?post_type=' . $this->prefix . 'results',
            __('Students', 'edu-results'),
            __('Students', 'edu-results'),
            'manage_options',
            'edit.php?post_type=' . $this->prefix . 'students'
        );
    }

    /**
     * Register Results post type
     *
     * @return void
     */
    private function registerResultsPostType()
    {
        $labels = array(
            'name'                     => __('Results', 'edu-results'),
            'singular_name'            => __('Result', 'edu-results'),
            'menu_name'                => __('EDU Results', 'edu-results'),
            'add_new'                  => __('Add New Result', 'edu-results'),
            'add_new_item'             => __('Add New Result', 'edu-results'),
            'edit_item'                => __('Edit Result', 'edu-results'),
            'new_item'                 => __('New Result', 'edu-results'),
            'view_item'                => __('View Result', 'edu-results'),
            'item_published'           => __('Result published.', 'edu-results'),
            'item_published_privately' => __('Result published privately.', 'edu-results'),
            'item_reverted_to_draft'   => __('Result reverted to draft.', 'edu-results'),
            'item_scheduled'           => __('Result scheduled.', 'edu-results'),
            'item_updated'             => __('Result updated.', 'edu-results'),
            'search_items'             => __('Search Results', 'edu-results'),
            'not_found'                => __('No Results found', 'edu-results'),
            'not_found_in_trash'       => __('No Results found in Trash', 'edu-results'),
            'parent_item_colon'        => __('Parent Result:', 'edu-results'),
            'all_items'                => __('All Results', 'edu-results'),
            'archives'                 => __('Result Archives', 'edu-results'),
            'insert_into_item'         => __('Insert into Result', 'edu-results'),
            'uploaded_to_this_item'    => __('Uploaded to this Result', 'edu-results'),
            'featured_image'           => __('Student Image', 'edu-results'),
            'set_featured_image'       => __('Set Student Picture', 'edu-results'),
            'remove_featured_image'    => __('Remove Student Picture', 'edu-results'),
            'use_featured_image'       => __('Use as Student Picture', 'edu-results'),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'edu-results'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'menu_icon'           => 'dashicons-book',
            'supports'            => array('title', 'thumbnail'),
        );

        register_post_type($this->prefix . 'results', $args);
    }

    /**
     * Register Subjects post type
     *
     * @return void
     */
    private function registerSubjectsPostType()
    {
        $labels = array(
            'name'                     => __('Subjects', 'edu-results'),
            'singular_name'            => __('Subject', 'edu-results'),
            'menu_name'                => __('EDU Subjects', 'edu-results'),
            'add_new'                  => __('Add New', 'edu-results'),
            'add_new_item'             => __('Add New Subject', 'edu-results'),
            'edit_item'                => __('Edit Subject', 'edu-results'),
            'new_item'                 => __('New Subject', 'edu-results'),
            'view_item'                => __('View Subject', 'edu-results'),
            'item_published'           => __('Subject published.', 'edu-results'),
            'item_published_privately' => __('Subject published privately.', 'edu-results'),
            'item_reverted_to_draft'   => __('Subject reverted to draft.', 'edu-results'),
            'item_scheduled'           => __('Subject scheduled.', 'edu-results'),
            'item_updated'             => __('Subject updated.', 'edu-results'),
            'search_items'             => __('Search Subjects', 'edu-results'),
            'not_found'                => __('No Subjects found', 'edu-results'),
            'not_found_in_trash'       => __('No Subjects found in Trash', 'edu-results'),
            'parent_item_colon'        => __('Parent Subject:', 'edu-results'),
            'all_items'                => __('All Subjects', 'edu-results'),
            'archives'                 => __('Subject Archives', 'edu-results'),
            'insert_into_item'         => __('Insert into Subject', 'edu-results'),
            'uploaded_to_this_item'    => __('Uploaded to this Subject', 'edu-results'),
            'featured_image'           => __('Subject Image', 'edu-results'),
            'set_featured_image'       => __('Set Subject Picture', 'edu-results'),
            'remove_featured_image'    => __('Remove Subject Picture', 'edu-results'),
            'use_featured_image'       => __('Use as Subject Picture', 'edu-results'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'edu-subjects'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-book-alt',
            'supports'           => array('title'),
        );

        register_post_type($this->prefix . 'subjects', $args);
    }

    /**
     * Register Students post type
     *
     * @return void
     */
    private function registerStudentsPostType()
    {
        $labels = array(
            'name'                     => __('Students', 'edu-results'),
            'singular_name'            => __('Student', 'edu-results'),
            'menu_name'                => __('EDU Students', 'edu-results'),
            'item_published'           => __('Student published.', 'edu-results'),
            'item_published_privately' => __('Student private.', 'edu-results'),
            'item_reverted_to_draft'   => __('Student profile reverted to draft.', 'edu-results'),
            'item_scheduled'           => __('Student profile scheduled.', 'edu-results'),
            'item_updated'             => __('Student profile updated.', 'edu-results'),
            'add_new'                  => __('Add New', 'edu-results'),
            'add_new_item'             => __('Add New Student', 'edu-results'),
            'edit_item'                => __('Edit Student', 'edu-results'),
            'new_item'                 => __('New Student', 'edu-results'),
            'view_item'                => __('View Student', 'edu-results'),
            'search_items'             => __('Search Students', 'edu-results'),
            'not_found'                => __('No Students found', 'edu-results'),
            'not_found_in_trash'       => __('No Students found in Trash', 'edu-results'),
            'parent_item_colon'        => __('Parent Student:', 'edu-results'),
            'all_items'                => __('All Students', 'edu-results'),
            'archives'                 => __('Student Archives', 'edu-results'),
            'insert_into_item'         => __('Insert into Student', 'edu-results'),
            'uploaded_to_this_item'    => __('Uploaded to this Student', 'edu-results'),
            'featured_image'           => __('Student Image', 'edu-results'),
            'set_featured_image'       => __('Set Student Picture', 'edu-results'),
            'remove_featured_image'    => __('Remove Student Picture', 'edu-results'),
            'use_featured_image'       => __('Use as Student Picture', 'edu-results'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'edu-students'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-welcome-learn-more',
            'supports'           => array('title', 'thumbnail'),
        );

        register_post_type($this->prefix . 'students', $args);
    }
}
