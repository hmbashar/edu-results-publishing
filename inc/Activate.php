<?php
namespace CBEDU\Inc;

/**
 * Don't allow direct access
 */
if (!defined('ABSPATH')) {
	die;
}

/**
 * Plugin activation class
 *
 * Contains methods that are triggered when the plugin is activated.
 * This class handles the initialization tasks such as creating necessary
 * database tables, setting default options, and flushing rewrite rules.
 *
 * @package CBEDU
 * @since 1.2.0
 */
class Activate
{

	/**
	 * Perform actions on plugin activation.
	 *
	 * This function is intended to be run when the plugin is activated.
	 * It ensures that WordPress rewrite rules are flushed to account for any changes
	 * in custom post types or taxonomies that the plugin introduces. It also sets
	 * default plugin options if they don't already exist.
	 *
	 * @since 1.0.0
	 */
	public static function activate()
	{
		// Set default options
		self::set_default_options();

		// Flush rewrite rules for custom post types and taxonomies
		flush_rewrite_rules();
	}

	/**
	 * Set default plugin options
	 *
	 * This method creates default settings for the plugin on activation
	 * if they don't already exist in the database.
	 *
	 * @since 1.2.0
	 */
	private static function set_default_options()
	{
		// Check if options already exist
		$existing_options = get_option('cbedu_settings');

		if (false === $existing_options) {
			// Set default options
			$default_options = array(
				'version' => CBEDU_VERSION,
				'activated_time' => current_time('timestamp'),
			);

			update_option('cbedu_settings', $default_options);
		}
	}
}
