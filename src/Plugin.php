<?php
/**
 * Main Plugin Class
 *
 * Core plugin functionality and initialization
 *
 * @package    CBEDU
 * @since      1.3.0
 */

namespace CBEDU;

use CBEDU\Core\Loader;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Plugin
 *
 * Main plugin controller class
 */
class Plugin
{
    /**
     * Plugin instance
     *
     * @var Plugin
     */
    private static $instance = null;

    /**
     * Plugin prefix
     *
     * @var string
     */
    private $prefix;

    /**
     * Plugin version
     *
     * @var string
     */
    private $version;

    /**
     * Plugin URL
     *
     * @var string
     */
    private $pluginUrl;

    /**
     * Plugin directory path
     *
     * @var string
     */
    private $pluginPath;

    /**
     * Text domain
     *
     * @var string
     */
    private $textDomain = 'edu-results';

    /**
     * Core loader instance
     *
     * @var Loader
     */
    private $loader;

    /**
     * Get singleton instance
     *
     * @param string $prefix     Plugin prefix
     * @param string $version    Plugin version
     * @param string $pluginUrl  Plugin URL
     * @param string $pluginPath Plugin directory path
     * @return Plugin
     */
    public static function getInstance($prefix, $version, $pluginUrl, $pluginPath)
    {
        if (self::$instance === null) {
            self::$instance = new self($prefix, $version, $pluginUrl, $pluginPath);
        }
        
        return self::$instance;
    }

    /**
     * Constructor
     *
     * @param string $prefix     Plugin prefix
     * @param string $version    Plugin version
     * @param string $pluginUrl  Plugin URL
     * @param string $pluginPath Plugin directory path
     */
    private function __construct($prefix, $version, $pluginUrl, $pluginPath)
    {
        $this->prefix = $prefix;
        $this->version = $version;
        $this->pluginUrl = $pluginUrl;
        $this->pluginPath = $pluginPath;

        $this->registerHooks();
        $this->loadDependencies();
    }

    /**
     * Register WordPress hooks
     *
     * @return void
     */
    private function registerHooks()
    {
        add_action('plugins_loaded', array($this, 'loadTextDomain'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminAssets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueueFrontendAssets'));
        add_filter('plugin_action_links_' . plugin_basename($this->pluginPath . 'edu-results-publishing.php'), array($this, 'addPluginActionLinks'));
        add_filter('enter_title_here', array($this, 'changeTitlePlaceholder'));
        add_filter('post_updated_messages', array($this, 'customPostPublishMessage'));
        add_action('edit_form_after_title', array($this, 'addCustomDescriptionAfterTitle'));
    }

    /**
     * Load plugin dependencies
     *
     * @return void
     */
    private function loadDependencies()
    {
        // Initialize the core loader
        $this->loader = new Loader($this->prefix, $this->version, $this->pluginUrl, $this->pluginPath);
        $this->loader->init();
    }

    /**
     * Load plugin text domain for translations
     *
     * @return void
     */
    public function loadTextDomain()
    {
        load_plugin_textdomain(
            $this->textDomain,
            false,
            dirname(plugin_basename($this->pluginPath . 'edu-results-publishing.php')) . '/languages/'
        );
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueueAdminAssets()
    {
        wp_enqueue_media();
        
        wp_enqueue_style(
            'cbedu-admin-meta-fields',
            $this->pluginUrl . 'assets/admin/css/admin-meta-fields.css',
            array(),
            $this->version
        );
        
        wp_enqueue_script(
            'cbedu-admin-js',
            $this->pluginUrl . 'assets/admin/js/admin.js',
            array('jquery'),
            $this->version,
            true
        );
    }

    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueueFrontendAssets()
    {
        wp_enqueue_style(
            'cbedu-results-style',
            $this->pluginUrl . 'assets/public/css/style.css',
            array(),
            $this->version
        );
        
        wp_enqueue_style(
            'cbedu-autocomplete-style',
            $this->pluginUrl . 'assets/public/css/autocomplete.css',
            array(),
            $this->version
        );
        
        wp_enqueue_script(
            'cbedu-ajax-search',
            $this->pluginUrl . 'assets/public/js/ajax-search-result.js',
            array('jquery'),
            $this->version,
            true
        );
        
        wp_enqueue_script(
            'cbedu-autocomplete',
            $this->pluginUrl . 'assets/public/js/autocomplete.js',
            array('jquery'),
            $this->version,
            true
        );
        
        wp_enqueue_script(
            'cbedu-print',
            $this->pluginUrl . 'assets/public/js/print.js',
            array('jquery'),
            $this->version,
            true
        );
        
        wp_localize_script('cbedu-ajax-search', 'cbedu_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbedu_search_nonce')
        ));
    }

    /**
     * Add plugin action links
     *
     * @param array $links Existing links
     * @return array Modified links
     */
    public function addPluginActionLinks($links)
    {
        $settingsLink = '<a href="' . admin_url('edit.php?post_type=' . $this->prefix . 'results&page=cbedu_results_settings') . '">' . __('Settings', 'edu-results') . '</a>';
        array_unshift($links, $settingsLink);
        
        return $links;
    }

    /**
     * Change title placeholder
     *
     * @param string $title Current placeholder
     * @return string Modified placeholder
     */
    public function changeTitlePlaceholder($title)
    {
        $screen = get_current_screen();
        
        if ($screen->post_type === $this->prefix . 'results') {
            $title = __('Enter Result ID / Title', 'edu-results');
        } elseif ($screen->post_type === $this->prefix . 'subjects') {
            $title = __('Enter Subject Name', 'edu-results');
        } elseif ($screen->post_type === $this->prefix . 'students') {
            $title = __('Enter Student Name', 'edu-results');
        }
        
        return $title;
    }

    /**
     * Custom post publish messages
     *
     * @param array $messages Existing messages
     * @return array Modified messages
     */
    public function customPostPublishMessage($messages)
    {
        $messages[$this->prefix . 'results'] = array(
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
     * @param \WP_Post $post Current post object
     * @return void
     */
    public function addCustomDescriptionAfterTitle($post)
    {
        if ($post->post_type === $this->prefix . 'results') {
            echo '<p style="margin-top: 10px; font-style: italic; color: #666;">' 
                 . __('Note: First, add all subjects, session years, examinations, boards, and departments. Then add students (these fields are optional for students but required for results). Finally, create results.', 'edu-results') 
                 . '</p>';
        }
    }

    /**
     * Get plugin prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get plugin version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get text domain
     *
     * @return string
     */
    public function getTextDomain()
    {
        return $this->textDomain;
    }
}
