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
        $atts = shortcode_atts(
            array(
                'id' => 0,
            ),
            $atts,
            'cbedu_result_details'
        );

        $result_id = absint($atts['id']);

        // Allow deep-linking: /result-details/?result_id=123
        if (!$result_id && isset($_GET['result_id'])) {
            $result_id = absint(wp_unslash($_GET['result_id']));
        }

        // Optional lookup by roll + registration number.
        if (!$result_id && !empty($_GET['roll']) && !empty($_GET['registration_number'])) {
            $result_id = $this->find_result_id_by_credentials(
                sanitize_text_field(wp_unslash($_GET['roll'])),
                sanitize_text_field(wp_unslash($_GET['registration_number']))
            );
        }

        ob_start();
        ?>
        <div class="cbedu-results-shortcode-area">
            <?php
            include CBEDU_FRONTEND_PATH . 'Shortcodes/result-view/result-view.php';
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Find a published result post ID by roll and registration number.
     *
     * @param string $roll
     * @param string $registration_number
     * @return int
     */
    private function find_result_id_by_credentials($roll, $registration_number)
    {
        $query = new \WP_Query(
            array(
                'post_type'              => 'cbedu_results',
                'post_status'            => 'publish',
                'posts_per_page'         => 1,
                'fields'                 => 'ids',
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'meta_query'             => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'cbedu_result_std_roll',
                        'value'   => $roll,
                        'compare' => '=',
                    ),
                    array(
                        'key'     => 'cbedu_result_std_registration_number',
                        'value'   => $registration_number,
                        'compare' => '=',
                    ),
                ),
            )
        );

        if (empty($query->posts)) {
            return 0;
        }

        return absint($query->posts[0]);
    }
}
