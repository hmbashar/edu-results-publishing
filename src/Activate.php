<?php
/**
 * Plugin Activation Handler
 *
 * @package CBEDU
 * @since   1.3.0
 */

namespace CBEDU;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Activate
 *
 * Handles plugin activation
 */
class Activate
{
    /**
     * Run activation procedures
     *
     * @since 1.3.0
     *
     * @return void
     */
    public static function activate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Set default options
        self::set_default_options();

        // Add activation timestamp
        if (!get_option('cbedu_activated_time')) {
            update_option('cbedu_activated_time', time());
        }
    }

    /**
     * Set default plugin options
     *
     * @since 1.3.0
     *
     * @return void
     */
    private static function set_default_options()
    {
        // Set any default options here if needed
        if (!get_option('cbedu_version')) {
            update_option('cbedu_version', CBEDU_VERSION);
        }
    }
}
