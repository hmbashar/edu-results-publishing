<?php
namespace cbedu\inc\lib;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CBEDUResultsShortcode
{
    /**
     * Constructor method for the class.
     * @return void
     */
    public function __construct()
    {

        $this->register_shortcode();
    }
    /**
     * Renders a shortcode and returns the output.
     *
     * @param array $atts An array of attributes for the shortcode.
     * @param string|null $content The content within the shortcode.
     * @return string The generated output of the shortcode.
     */
    public function render_shortcode($atts, $content = null)
    {
        ob_start();
        ?>
        <div class="edu-results-shortcode-area"> <h2>this is test</h2>

        <?php 
        // Include the 'render-result-view.php' file
        include CBEDU_RESULT_DIR . 'inc/front-end/render-result-view.php';
        ?>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * Registers a shortcode for displaying educational results.
     *
     * This function adds a shortcode 'edu_results' and associates it with the
     * 'render_shortcode' method of the current class. This allows users to
     * easily display educational results on their website by using the
     * [edu_results] shortcode.     *
     */

    public function register_shortcode()
    {
        add_shortcode('cbedu_results', array($this, 'render_shortcode'));
    }
}