<?php

/**
 * AdminManager.php
 *
 * This file contains the AdminManager class, which is responsible for handling the
 * initialization and configuration of the EDU Results Publishing Admin.
 * It ensures the proper setup of the required configurations and functionalities
 * for the EDU Results Publishing Admin.
 *
 * @package CBEDU\Admin
 * @since 1.2.0
 */

namespace CBEDU\Admin;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

use CBEDU\Admin\Assets\Assets;
use CBEDU\Admin\PostTypes\CustomPosts;
use CBEDU\Admin\Taxonomy\Taxonomy;
use CBEDU\Admin\Helpers\Helpers;
use CBEDU\Admin\Dashboard\Dashboard;
use CBEDU\Admin\CustomFields\CustomFields;
use CBEDU\Admin\CustomFields\ResultSubjectRF;
use CBEDU\Admin\Dashboard\Settings\Settings;


/**
 * Class AdminManager
 * Handles the initialization and configuration of the EDU Results Publishing Admin.
 * It ensures the proper setup of the required configurations and functionalities
 * for the EDU Results Publishing Admin.
 *
 * @package CBEDU\Admin
 * @since 1.2.0
 */
class AdminManager
{
    protected $customPosts;
    protected $taxonomy;
    protected $dashboard;
    protected $helpers;
    protected $customFields;
    protected $resultSubjectRF;
    protected $settings;
    protected $assets;


    /**
     * AdminManager constructor.
     *
     * Initializes the AdminManager by setting constants and initiating configurations
     * necessary for the EDU Results Publishing Admin setup.
     *
     * @param object $plugin_instance Main plugin instance
     * @since 1.2.0
     */
    public function __construct($plugin_instance = null)
    {
        $this->setConstants();
        $this->init();

        // Add plugin action links
        add_filter('plugin_action_links_' . plugin_basename(CBEDU_RESULT_PATH . 'edu-results-publishing.php'), [$this, 'add_plugin_settings_link']);
        add_filter('plugin_row_meta', [$this, 'plugin_row_meta'], 10, 2);
    }

    /**
     * Sets the constants for the EDU Results Publishing Admin.
     *
     * Defines the URL path for the EDU Results Publishing Admin assets directory.
     *
     * @since 1.2.0
     */
    public function setConstants()
    {
        if (!defined('CBEDU_ADMIN_ASSETS_URL')) {
            define('CBEDU_ADMIN_ASSETS_URL', CBEDU_RESULT_URL . 'Admin/Assets');
        }
    }

    /**
     * Initializes the classes used by the EDU Results Publishing Admin.
     *
     * This function instantiates all admin-related classes including custom post types,
     * taxonomies, custom fields, settings, and import/export functionality.
     *
     * @since 1.2.0
     */
    public function init()
    {


        // Initialize Assets
        if (class_exists('\CBEDU\Admin\Assets\Assets')) {
            $this->assets = new Assets();
        }
        // Initialize Custom Post Types
        if (class_exists('\CBEDU\Admin\PostTypes\CustomPosts')) {
            $this->customPosts = new CustomPosts();
        }
        // Initialize Dashboard
        if (class_exists('\CBEDU\Admin\Dashboard\Dashboard')) {
            $this->dashboard = new Dashboard();
        }
        // Initialize Taxonomies
        if (class_exists('\CBEDU\Admin\Taxonomy\Taxonomy')) {
            $this->taxonomy = new Taxonomy();
        }
        // Initialize Helpers
        if (class_exists('\CBEDU\Admin\Helpers\Helpers')) {
            $this->helpers = new Helpers();
        }
        // Initialize Custom Fields
        if (class_exists('\CBEDU\Admin\CustomFields\CustomFields')) {
            $this->customFields = new CustomFields();
        }
        // Initialize Result-Subject Repeater Field
        if (class_exists('\CBEDU\Admin\CustomFields\ResultSubjectRF')) {
            $this->resultSubjectRF = new ResultSubjectRF();
        }

        // Initialize Settings
        if (class_exists('\CBEDU\Admin\Dashboard\Settings\Settings')) {
            $this->settings = new Settings();
        }

    }

    /**
     * Add custom links to the plugin actions in the Plugins list.
     *
     * @param array $links Existing plugin action links.
     * @return array Modified plugin action links.
     */
    public function add_plugin_settings_link($links)
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url('admin.php?page=cbedu_results_settings')),
            esc_html__('Settings', 'edu-results')
        );

        $donate_link = sprintf(
            '<a href="%s" target="_blank" style="font-weight: bold; color: #ff4500;">%s</a>',
            esc_url('https://www.buymeacoffee.com/hmbashar'),
            esc_html__('Donate', 'edu-results')
        );

        // Prepend the settings link
        array_unshift($links, $settings_link);

        // Append the Donate link
        array_push($links, $donate_link);

        return $links;
    }

    /**
     * Add custom meta links to plugin row
     *
     * @param array $links Existing meta links.
     * @param string $file Plugin file path.
     * @return array Modified meta links.
     */
    public function plugin_row_meta($links, $file)
    {
        if (plugin_basename(CBEDU_RESULT_PATH . 'edu-results-publishing.php') === $file) {
            $row_meta = array(
                'support' => '<a href="https://wordpress.org/support/plugin/edu-results-publishing" target="_blank">' . esc_html__('Support', 'edu-results') . '</a>',
                'rate' => '<a href="https://wordpress.org/support/plugin/edu-results-publishing/reviews/#new-post" target="_blank">' . esc_html__('Rate Us', 'edu-results') . '</a>',
                'docs' => '<a href="https://facebook.com/hmbashar" target="_blank">' . esc_html__('Documentation', 'edu-results') . '</a>',
            );
            return array_merge($links, $row_meta);
        }
        return (array) $links;
    }
}
