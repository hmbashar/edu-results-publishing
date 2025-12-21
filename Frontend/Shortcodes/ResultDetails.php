<?php

namespace CBEDU\Frontend\Shortcodes;

class ResultDetails {
    public function __construct() {
        add_shortcode('cbedu_result_details', array($this, 'render_result_details_shortcode'));
    }

       /**
     * Renders a shortcode and returns the output.
     *
     * @param array $atts An array of attributes for the shortcode.
     * @param string|null $content The content within the shortcode.
     * @return string The generated output of the shortcode.
     */
    public function render_result_details_shortcode($atts, $content = null)
    {
        ob_start();
?>
        <div class="cbedu-results-shortcode-area">
            <?php
            // Include the 'render-result-view.php' file
            include CBEDU_FRONTEND_PATH . 'Shortcodes/result-view/result-view.php';
            ?>
        </div>

    <?php
        return ob_get_clean();
    }
}