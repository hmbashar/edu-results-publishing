<?php
/**
 * Plugin Deactivation Handler
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
 * Class Deactivate
 *
 * Handles plugin deactivation
 */
class Deactivate
{
    /**
     * Run deactivation procedures
     *
     * @since 1.3.0
     *
     * @return void
     */
    public static function deactivate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Clean up any temporary data if needed
        // Note: Don't delete user data on deactivation, only on uninstall
    }
}
