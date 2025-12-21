<?php 
namespace cbedu\inc\lib;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CBEDU_CUSTOM_TAXONOMY {
    private $prefix;

    public function __construct($prefix) {
        $this->prefix = $prefix;
        add_action('init', array($this, 'register_taxonomies'));
    }

    public function register_taxonomies() {
        $this->register_session_years_taxonomy();
        $this->register_examination_taxonomy();
        $this->register_board_taxonomy();
        $this->register_department_group_taxonomy();
    }

    private function register_session_years_taxonomy() {
        $labels = array(
            'name'                       => _x('Session Years', 'edu-results'),
            'singular_name'              => _x('Session Year', 'edu-results'),
            'menu_name'                  => __('Session Years', 'edu-results'),
            'all_items'                  => __('All Session Years', 'edu-results'),
            'parent_item'                => __('Parent Session Year', 'edu-results'),
            'parent_item_colon'          => __('Parent Session Year:', 'edu-results'),
            'new_item_name'              => __('New Session Year Name', 'edu-results'),
            'add_new_item'               => __('Add New Session Year', 'edu-results'),
            'edit_item'                  => __('Edit Session Year', 'edu-results'),
            'update_item'                => __('Update Session Year', 'edu-results'),
            'view_item'                  => __('View Session Year', 'edu-results'),
            'separate_items_with_commas' => __('Separate session years with commas', 'edu-results'),
            'add_or_remove_items'        => __('Add or remove session years', 'edu-results'),
            'choose_from_most_used'      => __('Choose from the most used', 'edu-results'),
            'popular_items'              => __('Popular Session Years', 'edu-results'),
            'search_items'               => __('Search Session Years', 'edu-results'),
            'not_found'                  => __('Not Found', 'edu-results'),
            'no_terms'                   => __('No session years', 'edu-results'),
            'items_list'                 => __('Session Years list', 'edu-results'),
            'items_list_navigation'      => __('Session Years list navigation', 'edu-results'),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => array('slug' => 'session-years'),
        );
        register_taxonomy($this->prefix . 'session_years', [$this->prefix . 'students', $this->prefix . 'results'], $args);
    }

    private function register_examination_taxonomy() {
        $labels = array(
            'name'                       => _x('Examinations', 'edu-results'),
            'singular_name'              => _x('Examination', 'edu-results'),
            'menu_name'                  => __('Examinations', 'edu-results'),
            'all_items'                  => __('All Examinations', 'edu-results'),
            'parent_item'                => __('Parent Examination', 'edu-results'),
            'parent_item_colon'          => __('Parent Examination:', 'edu-results'),
            'new_item_name'              => __('New Examination Name', 'edu-results'),
            'add_new_item'               => __('Add New Examination', 'edu-results'),
            'edit_item'                  => __('Edit Examination', 'edu-results'),
            'update_item'                => __('Update Examination', 'edu-results'),
            'view_item'                  => __('View Examination', 'edu-results'),
            'separate_items_with_commas' => __('Separate examinations with commas', 'edu-results'),
            'add_or_remove_items'        => __('Add or remove examinations', 'edu-results'),
            'choose_from_most_used'      => __('Choose from the most used', 'edu-results'),
            'popular_items'              => __('Popular Examinations', 'edu-results'),
            'search_items'               => __('Search Examinations', 'edu-results'),
            'not_found'                  => __('Not Found', 'edu-results'),
            'no_terms'                   => __('No examinations', 'edu-results'),
            'items_list'                 => __('Examinations list', 'edu-results'),
            'items_list_navigation'      => __('Examinations list navigation', 'edu-results'),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => array('slug' => 'examinations'),
        );
        register_taxonomy($this->prefix . 'examinations', [$this->prefix . 'students', $this->prefix . 'results'], $args);
    }

    private function register_board_taxonomy() {
        $labels = array(
            'name'                       => _x('Boards', 'edu-results'),
            'singular_name'              => _x('Board', 'edu-results'),
            'menu_name'                  => __('Boards', 'edu-results'),
            'all_items'                  => __('All Boards', 'edu-results'),
            'parent_item'                => __('Parent Board', 'edu-results'),
            'parent_item_colon'          => __('Parent Board:', 'edu-results'),
            'new_item_name'              => __('New Board Name', 'edu-results'),
            'add_new_item'               => __('Add New Board', 'edu-results'),
            'edit_item'                  => __('Edit Board', 'edu-results'),
            'update_item'                => __('Update Board', 'edu-results'),
            'view_item'                  => __('View Board', 'edu-results'),
            'separate_items_with_commas' => __('Separate boards with commas', 'edu-results'),
            'add_or_remove_items'        => __('Add or remove boards', 'edu-results'),
            'choose_from_most_used'      => __('Choose from the most used', 'edu-results'),
            'popular_items'              => __('Popular Boards', 'edu-results'),
            'search_items'               => __('Search Boards', 'edu-results'),
            'not_found'                  => __('Not Found', 'edu-results'),
            'no_terms'                   => __('No boards', 'edu-results'),
            'items_list'                 => __('Boards list', 'edu-results'),
            'items_list_navigation'      => __('Boards list navigation', 'edu-results'),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => array('slug' => 'boards'),
        );
        register_taxonomy($this->prefix . 'boards', [$this->prefix . 'students', $this->prefix . 'results'], $args);
    }

    private function register_department_group_taxonomy() {
        $labels = array(
            'name'                       => _x('Departments/Groups', 'edu-results'),
            'singular_name'              => _x('Department/Group', 'edu-results'),
            'menu_name'                  => __('Departments/Groups', 'edu-results'),
            'all_items'                  => __('All Departments/Groups', 'edu-results'),
            'parent_item'                => __('Parent Department/Group', 'edu-results'),
            'parent_item_colon'          => __('Parent Department/Group:', 'edu-results'),
            'new_item_name'              => __('New Department/Group Name', 'edu-results'),
            'add_new_item'               => __('Add New Department/Group', 'edu-results'),
            'edit_item'                  => __('Edit Department/Group', 'edu-results'),
            'update_item'                => __('Update Department/Group', 'edu-results'),
            'view_item'                  => __('View Department/Group', 'edu-results'),
            'separate_items_with_commas' => __('Separate departments/groups with commas', 'edu-results'),
            'add_or_remove_items'        => __('Add or remove departments/groups', 'edu-results'),
            'choose_from_most_used'      => __('Choose from the most used', 'edu-results'),
            'popular_items'              => __('Popular Departments/Groups', 'edu-results'),
            'search_items'               => __('Search Departments/Groups', 'edu-results'),
            'not_found'                  => __('Not Found', 'edu-results'),
            'no_terms'                   => __('No departments/groups', 'edu-results'),
            'items_list'                 => __('Departments/Groups list', 'edu-results'),
            'items_list_navigation'      => __('Departments/Groups list navigation', 'edu-results'),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => array('slug' => 'departments-groups'),
        );
        register_taxonomy($this->prefix . 'department_group', [$this->prefix . 'students', $this->prefix . 'results'], $args);
    }
}