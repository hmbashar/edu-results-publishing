<?php
namespace CBEDU\Inc;

/**
 * Disable direct access
 */
if (!defined('ABSPATH')) {
	die;
}

/**
 * Class Deactivate
 *
 * Handles the deactivation of the plugin by performing cleanup tasks
 * such as flushing rewrite rules and optionally clearing caches.
 *
 * @package CBEDU
 * @since 1.0.0
 */
class Deactivate
{

	/**
	 * Perform cleanup on plugin deactivation.
	 *
	 * Clears the rewrite rules so that WordPress can generate new ones.
	 * This ensures that custom post types and taxonomies are properly
	 * removed from the rewrite rules when the plugin is deactivated.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate()
	{
		// Perform cleanup on plugin deactivation, like removing options or clearing caches.
		flush_rewrite_rules();
	}
}
