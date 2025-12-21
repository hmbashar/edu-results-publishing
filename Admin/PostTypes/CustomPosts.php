<?php

namespace CBEDU\Admin\PostTypes;

use CBEDU\Admin\PostTypes\Results;
use CBEDU\Admin\PostTypes\Subjects;
use CBEDU\Admin\PostTypes\Students;
class CustomPosts {
    
    protected $results;
    protected $subjects;
    protected $students;


    public function __construct() {
        $this->register_custom_post_types();
    }
    
    public function register_custom_post_types()
    {
        $this->results = new Results();
        $this->subjects = new Subjects();
        $this->students = new Students();
    }
 
    
  
}