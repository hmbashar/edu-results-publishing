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
 * @package EduResults
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Plugin constants
define('EDU_RESULTS_VERSION', '1.3.0');
define('EDU_RESULTS_PREFIX', 'cbedu_');
define('EDU_RESULTS_URL', plugin_dir_url(__FILE__));
define('EDU_RESULTS_DIR', plugin_dir_path(__FILE__));
define('EDU_RESULTS_FILE', __FILE__);

// For backward compatibility
define('CBEDU_VERSION', EDU_RESULTS_VERSION);
define('CBEDU_PREFIX', EDU_RESULTS_PREFIX);
define('CBEDU_RESULT_URL', EDU_RESULTS_URL);
define('CBEDU_RESULT_DIR', EDU_RESULTS_DIR);

// Load composer autoloader
if (file_exists(EDU_RESULTS_DIR . 'vendor/autoload.php')) {
    require_once EDU_RESULTS_DIR . 'vendor/autoload.php';
}

// Load legacy files for backward compatibility
require_once EDU_RESULTS_DIR . 'inc/custom-fields.php';
require_once EDU_RESULTS_DIR . 'inc/admin/settings.php';
require_once EDU_RESULTS_DIR . 'inc/RepeaterCF.php';
require_once EDU_RESULTS_DIR . 'inc/lib/shortcode.php';
require_once EDU_RESULTS_DIR . 'inc/lib/custom-functions.php';

/**
 * Initialize the plugin
 *
 * @return void
 */
function edu_results_init()
{
    // Initialize the main plugin class
    \EduResults\Plugin::getInstance(
        EDU_RESULTS_PREFIX,
        EDU_RESULTS_VERSION,
        EDU_RESULTS_URL,
        EDU_RESULTS_DIR
    );
}

add_action('plugins_loaded', 'edu_results_init', 5);

/**
 * Plugin activation hook
 *
 * @return void
 */
function edu_results_activate()
{
    // Flush rewrite rules on activation
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'edu_results_activate');

/**
 * Plugin deactivation hook
 *
 * @return void
 */
function edu_results_deactivate()
{
    // Flush rewrite rules on deactivation
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'edu_results_deactivate');
