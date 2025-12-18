<?php
/**
 * Manager Class
 *
 * Initializes and manages all plugin components
 *
 * @package CBEDU
 * @since   1.3.0
 */

namespace CBEDU;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

use CBEDU\Admin\AdminManager;
use CBEDU\Core\Loader;
use CBEDU\Frontend\PublicManager;

/**
 * Class Manager
 *
 * Central manager for initializing all plugin components
 */
class Manager
{
    /**
     * Core loader instance
     *
     * @var Loader
     */
    protected $loader;

    /**
     * Admin manager instance
     *
     * @var AdminManager
     */
    protected $admin_manager;

    /**
     * Public/Frontend manager instance
     *
     * @var PublicManager
     */
    protected $public_manager;

    /**
     * Class constructor
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize the manager by setting up hooks and classes
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function init()
    {
        $this->init_classes();
        $this->init_hooks();
    }

    /**
     * Initialize core classes
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function init_classes()
    {
        // Initialize core loader
        $this->loader = new Loader(
            CBEDU_PREFIX,
            CBEDU_VERSION,
            CBEDU_URL,
            CBEDU_PATH
        );
        $this->loader->init();

        // Initialize admin components
        if (is_admin()) {
            $this->admin_manager = new AdminManager();
        }

        // Initialize public/frontend components
        if (!is_admin()) {
            $this->public_manager = new PublicManager();
        }
    }

    /**
     * Initialize WordPress hooks
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function init_hooks()
    {
        add_action('init', array($this, 'load_textdomain'));
    }

    /**
     * Load plugin text domain for translations
     *
     * @since 1.3.0
     *
     * @return void
     */
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'edu-results',
            false,
            dirname(plugin_basename(CBEDU_FILE)) . '/languages/'
        );
    }
}
