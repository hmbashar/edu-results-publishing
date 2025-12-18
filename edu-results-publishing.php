<?php
/**
 * Plugin Name: EDU Results Publishing
 * Plugin URI:  https://github.com/hmbashar/edu-results-publishing
 * Description: A professional WordPress plugin for publishing educational exam results with modern UI and PSR-4 architecture
 * Version:     1.3.0
 * Author:      MD Abul Bashar
 * Author URI:  https://facebook.com/hmbashar
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: edu-results
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 *
 * @package CBEDU
 */

namespace CBEDU;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

final class CBEDUResultsPublishing
{
    /**
     * Singleton instance.
     *
     * @var CBEDUResultsPublishing|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return CBEDUResultsPublishing
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor is private to enforce singleton.
     */
    private function __construct()
    {
        $this->define_constants();
        $this->include_files();
        $this->init_hooks();
    }

    /**
     * Define plugin constants.
     */
    private function define_constants()
    {
        define('CBEDU_VERSION', '1.3.0');
        define('CBEDU_PATH', plugin_dir_path(__FILE__));
        define('CBEDU_URL', plugin_dir_url(__FILE__));
        define('CBEDU_FILE', __FILE__);
        define('CBEDU_BASENAME', plugin_basename(__FILE__));
        define('CBEDU_NAME', 'EDU Results Publishing');
        define('CBEDU_PREFIX', 'cbedu_');
    }

    /**
     * Include necessary files.
     */
    private function include_files()
    {
        if (file_exists(CBEDU_PATH . 'vendor/autoload.php')) {
            require_once CBEDU_PATH . 'vendor/autoload.php';
        }
    }

    /**
     * Register plugin hooks.
     */
    private function init_hooks()
    {
        add_action('plugins_loaded', array($this, 'plugin_loaded'));
        register_activation_hook(CBEDU_FILE, array($this, 'activate'));
        register_deactivation_hook(CBEDU_FILE, array($this, 'deactivate'));
    }

    /**
     * Actions after plugins_loaded.
     */
    public function plugin_loaded()
    {
        if (class_exists('\CBEDU\Manager')) {
            new \CBEDU\Manager();
        }
    }

    /**
     * Plugin activation logic.
     */
    public function activate()
    {
        if (class_exists('\CBEDU\Activate')) {
            \CBEDU\Activate::activate();
        }
    }

    /**
     * Plugin deactivation logic.
     */
    public function deactivate()
    {
        if (class_exists('\CBEDU\Deactivate')) {
            \CBEDU\Deactivate::deactivate();
        }
    }
}

// Initialize the plugin.
if (!function_exists('cbedu_initialize')) {
    function cbedu_initialize()
    {
        return \CBEDU\CBEDUResultsPublishing::get_instance();
    }

    cbedu_initialize();
}
