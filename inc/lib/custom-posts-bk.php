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
       // $this->register_subjects_post_type();
        //$this->register_students_post_type();
    }






}
