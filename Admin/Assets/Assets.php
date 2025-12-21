<?php

namespace CBEDU\Admin\Assets;

class Assets
{

    /**
     * Constructor
     */
    public function __construct()
    {

        // Add admin assets
        add_action('admin_enqueue_scripts', array($this, 'cbedu_result_assets_enque_admin'));
    }


    public function cbedu_result_assets_enque_admin($hook_suffix)
    {
        global $post;
        wp_enqueue_media();

        wp_enqueue_script('cbedu-custom-fields', CBEDU_ADMIN_ASSETS_URL . '/js/admin.js', array('jquery'), '1.0.0', true);

        wp_localize_script('cbedu-custom-fields', 'cbedu_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_register_number_nonce')
        ));

        // Enqueue admin meta fields CSS for custom post types
        if ($hook_suffix === 'post-new.php' || $hook_suffix === 'post.php') {
            $post_type = get_post_type($post);

            // Load admin CSS for students, subjects, and results post types
            if (in_array($post_type, array('cbedu_students', 'cbedu_subjects', 'cbedu_results'))) {
                wp_enqueue_style('cbedu-admin-meta-fields', CBEDU_ADMIN_ASSETS_URL . '/css/admin-meta-fields.css', array(), CBEDU_VERSION);
            }

            // For autocomplete jquery in results post type with registration number
            if ($post_type === 'cbedu_results') {
                wp_enqueue_style('cbedu-autocomplete-ui-css', CBEDU_ADMIN_ASSETS_URL . '/css/autocomplete.css');
                wp_enqueue_script('cbedu-autocomplete-js', CBEDU_ADMIN_ASSETS_URL . '/js/autocomplete.js', array('jquery', 'jquery-ui-autocomplete'), '1.0.0', true);
                wp_localize_script('cbedu-autocomplete-js', 'cbedu_ajax_autocomplete_object', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    // Pass the nonce here
                    'auto_complete_nonce' => wp_create_nonce('cbedu_auto_complete_nonce')
                ));
            }
        }
    }

    public function enqueueSettingsStyles($hook)
    {
        // Only load on our settings page
        if ($hook !== 'cbedu_results_page_cbedu_results_settings') {
            return;
        }

        wp_enqueue_style(
            'cbedu-admin-settings',
            CBEDU_RESULT_URL . 'assets/css/admin-settings.css',
            array(),
            CBEDU_VERSION
        );
    }
}
