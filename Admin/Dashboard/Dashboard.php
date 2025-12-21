<?php

namespace CBEDU\Admin\Dashboard;

class Dashboard {

    protected $prefix = CBEDU_PREFIX;
    
    public function __construct() {
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
    
}