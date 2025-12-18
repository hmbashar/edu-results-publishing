<?php
/**
 * Public Manager
 *
 * Manages all frontend/public-facing functionality
 *
 * @package    CBEDU
 * @subpackage Frontend
 * @since      1.3.0
 */

namespace CBEDU\Frontend;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PublicManager
 *
 * Handles all frontend functionality
 */
class PublicManager
{
    /**
     * Constructor
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->init_hooks();
        $this->load_public_components();
    }

    /**
     * Initialize frontend hooks
     *
     * @since 1.3.0
     *
     * @return void
     */
    private function init_hooks()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
    }

    /**
     * Load public components (legacy compatibility)
     *
     * @since 1.3.0
     *
     * @return void
     */
    private function load_public_components()
    {
        // Load legacy frontend files
        if (file_exists(CBEDU_PATH . 'inc/lib/shortcode.php')) {
            require_once CBEDU_PATH . 'inc/lib/shortcode.php';
            if (class_exists('\cbedu\inc\lib\CBEDUResultsShortcode')) {
                new \cbedu\inc\lib\CBEDUResultsShortcode();
            }
        }
    }

    /**
     * Enqueue frontend assets
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function enqueue_public_assets()
    {
        wp_enqueue_style(
            'cbedu-results-style',
            CBEDU_URL . 'assets/public/css/style.css',
            array(),
            CBEDU_VERSION
        );

        wp_enqueue_style(
            'cbedu-autocomplete-style',
            CBEDU_URL . 'assets/public/css/autocomplete.css',
            array(),
            CBEDU_VERSION
        );

        wp_enqueue_script(
            'cbedu-ajax-search',
            CBEDU_URL . 'assets/public/js/ajax-search-result.js',
            array('jquery'),
            CBEDU_VERSION,
            true
        );

        wp_enqueue_script(
            'cbedu-autocomplete',
            CBEDU_URL . 'assets/public/js/autocomplete.js',
            array('jquery'),
            CBEDU_VERSION,
            true
        );

        wp_enqueue_script(
            'cbedu-print',
            CBEDU_URL . 'assets/public/js/print.js',
            array('jquery'),
            CBEDU_VERSION,
            true
        );

        wp_localize_script('cbedu-ajax-search', 'cbedu_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_search_nonce')
        ));
    }
}
