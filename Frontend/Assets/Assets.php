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
        $style_ver  = $this->asset_version('css/style.css');
        $search_ver = $this->asset_version('js/ajax-search-result.js');
        $print_ver  = $this->asset_version('js/print.js');

        wp_enqueue_style('cbedu-results-style', CBEDU_FRONTEND_ASSETS_URL . '/css/style.css', array(), $style_ver);

        //for ajax search
        wp_enqueue_script('cbedu-ajax-search-result', CBEDU_FRONTEND_ASSETS_URL . '/js/ajax-search-result.js', array('jquery'), $search_ver, true);
        wp_localize_script('cbedu-ajax-search-result', 'cbedu_ajax_results_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_ajax_search_result_nonce') // Create a nonce for security
        ));

        //script for print / PDF sheet
        $print_css_ver = $this->asset_version('css/print-sheet.css');
        wp_enqueue_script('cbedu-print-js', CBEDU_FRONTEND_ASSETS_URL . '/js/print.js', array('jquery'), $print_ver, true);
        wp_localize_script('cbedu-print-js', 'cbedu_print_object', array(
            'printCss'       => add_query_arg('ver', $print_css_ver, CBEDU_FRONTEND_ASSETS_URL . '/css/print-sheet.css'),
            'collegeName'    => (string) get_option('cbedu_results_collage_name', ''),
            'collegeReg'     => (string) get_option('cbedu_results_collage_registration_number', ''),
            'collegePhone'   => (string) get_option('cbedu_results_collage_phone_number', ''),
            'collegeEmail'   => (string) get_option('cbedu_results_collage_email_address', ''),
            'collegeLogo'    => (string) get_option('cbedu_results_logo', ''),
            'sheetTitle'     => __('Academic Result Sheet', 'edu-results'),
            'regLabel'       => __('College Registration', 'edu-results'),
            'printedLabel'   => __('Printed on', 'edu-results'),
            'signatureLeft'  => __('Prepared by', 'edu-results'),
            'signatureMid'   => __('Checked by', 'edu-results'),
            'signatureRight' => __('Controller of Examinations', 'edu-results'),
            'footerNote'     => __('This is a computer-generated result sheet. Verify details with the institute if needed.', 'edu-results'),
        ));
    }

    /**
     * Use filemtime so browsers pick up asset changes without bumping plugin version.
     *
     * @param string $relative_path Path under Frontend/Assets.
     * @return string|false
     */
    private function asset_version($relative_path)
    {
        $file = CBEDU_FRONTEND_PATH . 'Assets/' . ltrim($relative_path, '/');
        return file_exists($file) ? (string) filemtime($file) : CBEDU_VERSION;
    }
}
