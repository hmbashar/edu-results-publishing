<?php
/**
 * Admin Manager
 *
 * Manages all admin-related functionality
 *
 * @package    CBEDU
 * @subpackage Admin
 * @since      1.3.0
 */

namespace CBEDU\Admin;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AdminManager
 *
 * Handles all WordPress admin functionality
 */
class AdminManager
{
    /**
     * Constructor
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        $this->init_hooks();
        $this->load_admin_components();
    }

    /**
     * Initialize admin hooks
     *
     * @since 1.3.0
     *
     * @return void
     */
    private function init_hooks()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_filter('plugin_action_links_' . plugin_basename(CBEDU_FILE), array($this, 'add_plugin_action_links'));
        add_filter('enter_title_here', array($this, 'change_title_placeholder'));
        add_filter('post_updated_messages', array($this, 'custom_post_publish_message'));
        add_action('edit_form_after_title', array($this, 'add_custom_description_after_title'));
    }

    /**
     * Load admin components (legacy compatibility)
     *
     * @since 1.3.0
     *
     * @return void
     */
    private function load_admin_components()
    {
        // Load legacy admin files
        if (file_exists(CBEDU_PATH . 'inc/custom-fields.php')) {
            require_once CBEDU_PATH . 'inc/custom-fields.php';
            if (class_exists('\cbedu\inc\custom_fields\CBEDUCustomFields')) {
                new \cbedu\inc\custom_fields\CBEDUCustomFields();
            }
        }

        if (file_exists(CBEDU_PATH . 'inc/RepeaterCF.php')) {
            require_once CBEDU_PATH . 'inc/RepeaterCF.php';
            if (class_exists('\cbedu\inc\RepeaterCF\CBEDURepeaterCustomFields')) {
                new \cbedu\inc\RepeaterCF\CBEDURepeaterCustomFields((object)['prefix' => CBEDU_PREFIX]);
            }
        }

        if (file_exists(CBEDU_PATH . 'inc/admin/settings.php')) {
            require_once CBEDU_PATH . 'inc/admin/settings.php';
            if (class_exists('\cbedu\inc\admin\settings\CBEDUResultSettings')) {
                new \cbedu\inc\admin\settings\CBEDUResultSettings((object)['prefix' => CBEDU_PREFIX]);
            }
        }
    }

    /**
     * Enqueue admin assets
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function enqueue_admin_assets()
    {
        wp_enqueue_media();

        wp_enqueue_style(
            'cbedu-admin-meta-fields',
            CBEDU_URL . 'assets/admin/css/admin-meta-fields.css',
            array(),
            CBEDU_VERSION
        );

        wp_enqueue_script(
            'cbedu-admin-js',
            CBEDU_URL . 'assets/admin/js/admin.js',
            array('jquery'),
            CBEDU_VERSION,
            true
        );

        wp_localize_script('cbedu-admin-js', 'cbedu_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_register_number_nonce')
        ));
    }

    /**
     * Add plugin action links
     *
     * @since 1.3.0
     *
     * @param array $links Existing links
     * @return array Modified links
     */
    public function add_plugin_action_links($links)
    {
        $settings_link = '<a href="' . admin_url('edit.php?post_type=' . CBEDU_PREFIX . 'results&page=cbedu_results_settings') . '">' . __('Settings', 'edu-results') . '</a>';
        array_unshift($links, $settings_link);

        return $links;
    }

    /**
     * Change title placeholder
     *
     * @since 1.3.0
     *
     * @param string $title Current placeholder
     * @return string Modified placeholder
     */
    public function change_title_placeholder($title)
    {
        $screen = get_current_screen();

        if ($screen->post_type === CBEDU_PREFIX . 'results') {
            $title = __('Enter Result ID / Title', 'edu-results');
        } elseif ($screen->post_type === CBEDU_PREFIX . 'subjects') {
            $title = __('Enter Subject Name', 'edu-results');
        } elseif ($screen->post_type === CBEDU_PREFIX . 'students') {
            $title = __('Enter Student Name', 'edu-results');
        }

        return $title;
    }

    /**
     * Custom post publish messages
     *
     * @since 1.3.0
     *
     * @param array $messages Existing messages
     * @return array Modified messages
     */
    public function custom_post_publish_message($messages)
    {
        $messages[CBEDU_PREFIX . 'results'] = array(
            0  => '',
            1  => __('Result updated.', 'edu-results'),
            2  => __('Custom field updated.', 'edu-results'),
            3  => __('Custom field deleted.', 'edu-results'),
            4  => __('Result updated.', 'edu-results'),
            5  => isset($_GET['revision']) ? sprintf(__('Result restored to revision from %s', 'edu-results'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6  => __('Result published.', 'edu-results'),
            7  => __('Result saved.', 'edu-results'),
            8  => __('Result submitted.', 'edu-results'),
            9  => sprintf(__('Result scheduled for: <strong>%1$s</strong>.', 'edu-results'), date_i18n(__('M j, Y @ G:i', 'edu-results'), strtotime(get_post()->post_date))),
            10 => __('Result draft updated.', 'edu-results')
        );

        return $messages;
    }

    /**
     * Add custom description after title
     *
     * @since 1.3.0
     *
     * @param \WP_Post $post Current post object
     * @return void
     */
    public function add_custom_description_after_title($post)
    {
        if ($post->post_type === CBEDU_PREFIX . 'results') {
            echo '<p style="margin-top: 10px; font-style: italic; color: #666;">'
                 . __('Note: First, add all subjects, session years, examinations, boards, and departments. Then add students (these fields are optional for students but required for results). Finally, create results.', 'edu-results')
                 . '</p>';
        }
    }
}
