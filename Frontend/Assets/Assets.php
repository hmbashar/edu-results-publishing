<?php

namespace CBEDU\Frontend\Assets;

class Assets
{

    public function __construct()
    {
        // Add plugin assets
        add_action('wp_enqueue_scripts', array($this, 'cbedu_results_assets_enqueue'));
    }

    public function cbedu_results_assets_enqueue()
    {
        wp_enqueue_style('cbedu-results-style', CBEDU_FRONTEND_ASSETS_URL . '/css/style.css', array(), CBEDU_VERSION);

        //for ajax search
        wp_enqueue_script('cbedu-ajax-search-result', CBEDU_FRONTEND_ASSETS_URL . '/js/ajax-search-result.js', array('jquery'), CBEDU_VERSION, true);
        wp_localize_script('cbedu-ajax-search-result', 'cbedu_ajax_results_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_ajax_search_result_nonce') // Create a nonce for security
        ));

        //script for print
        wp_enqueue_script('cbedu-print-js', CBEDU_FRONTEND_ASSETS_URL . '/js/print.js', array('jquery'), CBEDU_VERSION, true);
    }
}
