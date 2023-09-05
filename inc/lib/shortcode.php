<?php

class EDUResultsShortcode
{
    public function __construct()
    {
        // Register the shortcode
        add_shortcode('edu_results', array($this, 'render_shortcode'));


    }

    public function render_shortcode($atts, $content = null)
    {

        // Shortcode logic goes here
        $output = '<div class="edu-results-shortcode-area">';

        // Include the 'render-result-view.php' file
        include EDU_RESULT_DIR . 'inc/front-end/render-result-view.php';

        $output .= '</div>';

        return $output;
    }
}

// Instantiate the class to register the shortcode
new EDUResultsShortcode();