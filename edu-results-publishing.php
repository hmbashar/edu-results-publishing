<?php

/**
 * Plugin Name: EDU Results Publishing
 * Author: MD Abul Bashar
 * Author URI: https://facebook.com/hmbashar
 * Description: This plugin is for student exam results publishing.
 * Tags: Result, WP Result Plugin, EDU Results
 * Text Domain: edu-results
 * Version: 1.0
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Prefix: cbedu_ 
 */


/* @package edu-results
 * @since 1.0
 * @version 1.0
 * @author MD Abul Bashar
 * @link https://facebook.com/hmbashar
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly


//define URL
define('CBEDU_RESULT_URL', plugin_dir_url(__FILE__));
define('CBEDU_RESULT_DIR', plugin_dir_path(__FILE__));
define('CBEDU_PREFIX', 'cbedu_');

class CBEDUResultPublishing
{
    // Plugin prefix
    private $prefix;

    public function __construct()
    {
        $this->prefix = CBEDU_PREFIX;

        // Register plugin action links
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'addPluginActionLinks'));
        // Change placeholder
        add_filter('enter_title_here', array($this, 'changeTitlePlaceholder'));
        // Add admin assets
        add_action('admin_enqueue_scripts', array($this, 'cbedu_result_assets_enque_admin'));
        // Add plugin assets
        add_action('wp_enqueue_scripts', array($this, 'cbedu_results_assets_enqueue'));

        // Register text domain for translation
        add_action('plugins_loaded', array($this, 'loadTextDomain'));

        // Add description after the title field for Subjects post type
        add_action('edit_form_after_title', array($this, 'addDescriptionAfterTitle'));

        // Initialize the plugin
        $this->initialize();
    }

    public function getTextDomain()
    {
        return 'edu-results';
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    private function initialize()
    {
        // Register custom post types
        $custom_post_type = new \cbedu\inc\lib\CBEDU_CUSTOM_POSTS($this->prefix);

        // Instantiate other classes
        $repeaterCustomFields = new \cbedu\inc\RepeaterCF\CBEDURepeaterCustomFields($this);
        $adminSettingsFields = new \cbedu\inc\admin\settings\CBEDUResultSettings($this);
        $customFields = new \cbedu\inc\custom_fields\CBEDUCustomFields();
        $resultsShortcode = new \cbedu\inc\lib\CBEDUResultsShortcode();
    }

    public function addPluginActionLinks($links)
    {
        $donateLink = '<a href="https://www.buymeacoffee.com/hmbashar" target="_blank">' . __('Donate', 'edu-results') . '</a>';
        array_unshift($links, $donateLink);
        return $links;
    }


    public function changeTitlePlaceholder($title)
    {
        $screen = get_current_screen();
        if ($screen->post_type == $this->prefix . 'results') {
            $title = 'Enter Student Name';
        } elseif ($screen->post_type == $this->prefix . 'subjects') {
            $title = 'Enter Code and Subject Name'; // Placeholder for Subjects post type
        }
        return $title;
    }
    /**
     * Adds a description after the title of a post.
     *
     * @param object $post The post object.
     * @return void
     */
    public function addDescriptionAfterTitle($post)
    {
        if ($post->post_type == $this->prefix . 'subjects') {
            echo '<div style="margin-top:10px;">';
            echo '<p class="description">Please enter your subject code and subject name here, ex: CS101 - Computer Science</p>';
            echo '</div>';
        }
    }

    public function cbedu_result_assets_enque_admin()
    {
        wp_enqueue_media();
    }

    public function loadTextDomain()
    {
        load_plugin_textdomain($this->getTextDomain(), false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function cbedu_results_assets_enqueue()
    {
        wp_enqueue_style('cbedu-results-style', plugins_url('/assets/css/style.css', __FILE__));
    }
}


require_once CBEDU_RESULT_DIR . 'inc/custom-fields.php';

require_once CBEDU_RESULT_DIR . 'inc/admin/settings.php';

require_once CBEDU_RESULT_DIR . 'inc/RepeaterCF.php';

require_once CBEDU_RESULT_DIR . 'inc/lib/shortcode.php';

require_once CBEDU_RESULT_DIR . 'inc/lib/custom-posts.php';

//init the main class
$CBEDUResultPublishing = new CBEDUResultPublishing();