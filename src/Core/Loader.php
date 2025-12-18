<?php
/**
 * Core Loader
 *
 * Loads all core components of the plugin
 *
 * @package    EduResults
 * @subpackage Core
 * @since      1.3.0
 */

namespace EduResults\Core;

use EduResults\Core\PostTypes\PostTypesManager;
use EduResults\Core\Taxonomies\TaxonomiesManager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Loader
 *
 * Central loader for all plugin components
 */
class Loader
{
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
     * Constructor
     *
     * @param string $prefix      Plugin prefix
     * @param string $version     Plugin version
     * @param string $pluginUrl   Plugin URL
     * @param string $pluginPath  Plugin directory path
     */
    public function __construct($prefix, $version, $pluginUrl, $pluginPath)
    {
        $this->prefix = $prefix;
        $this->version = $version;
        $this->pluginUrl = $pluginUrl;
        $this->pluginPath = $pluginPath;
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init()
    {
        // Load core components
        $this->loadPostTypes();
        $this->loadTaxonomies();
        
        // Load admin components
        if (is_admin()) {
            $this->loadAdminComponents();
        }
        
        // Load frontend components
        if (!is_admin()) {
            $this->loadFrontendComponents();
        }
        
        // Load components needed in both admin and frontend
        $this->loadSharedComponents();
    }

    /**
     * Load post types
     *
     * @return void
     */
    private function loadPostTypes()
    {
        new PostTypesManager($this->prefix);
    }

    /**
     * Load taxonomies
     *
     * @return void
     */
    private function loadTaxonomies()
    {
        new TaxonomiesManager($this->prefix);
    }

    /**
     * Load admin components
     *
     * @return void
     */
    private function loadAdminComponents()
    {
        // Admin components will be loaded here
        // This keeps the old functionality for now until we migrate everything
        if (file_exists($this->pluginPath . 'inc/custom-fields.php')) {
            require_once $this->pluginPath . 'inc/custom-fields.php';
            if (class_exists('\cbedu\inc\custom_fields\CBEDUCustomFields')) {
                new \cbedu\inc\custom_fields\CBEDUCustomFields();
            }
        }
        
        if (file_exists($this->pluginPath . 'inc/RepeaterCF.php')) {
            require_once $this->pluginPath . 'inc/RepeaterCF.php';
            if (class_exists('\cbedu\inc\RepeaterCF\CBEDURepeaterCustomFields')) {
                new \cbedu\inc\RepeaterCF\CBEDURepeaterCustomFields();
            }
        }
        
        if (file_exists($this->pluginPath . 'inc/admin/settings.php')) {
            require_once $this->pluginPath . 'inc/admin/settings.php';
            if (class_exists('\cbedu\inc\admin\settings\CBEDUResultSettings')) {
                new \cbedu\inc\admin\settings\CBEDUResultSettings((object)['prefix' => $this->prefix]);
            }
        }
    }

    /**
     * Load frontend components
     *
     * @return void
     */
    private function loadFrontendComponents()
    {
        // Frontend components will be loaded here
        if (file_exists($this->pluginPath . 'inc/lib/shortcode.php')) {
            require_once $this->pluginPath . 'inc/lib/shortcode.php';
            if (class_exists('\cbedu\inc\lib\CBEDUResultsShortcode')) {
                new \cbedu\inc\lib\CBEDUResultsShortcode();
            }
        }
    }

    /**
     * Load components needed in both admin and frontend
     *
     * @return void
     */
    private function loadSharedComponents()
    {
        if (file_exists($this->pluginPath . 'inc/lib/custom-functions.php')) {
            require_once $this->pluginPath . 'inc/lib/custom-functions.php';
            if (class_exists('\cbedu\inc\lib\CBEDUCustomFunctions\CBEDUCustomFunctions')) {
                new \cbedu\inc\lib\CBEDUCustomFunctions\CBEDUCustomFunctions($this->prefix);
            }
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
     * Get plugin URL
     *
     * @return string
     */
    public function getPluginUrl()
    {
        return $this->pluginUrl;
    }

    /**
     * Get plugin path
     *
     * @return string
     */
    public function getPluginPath()
    {
        return $this->pluginPath;
    }
}
