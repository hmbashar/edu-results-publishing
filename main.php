<?php
/**
 * Plugin Name: EDU Results Publishing
 * Author: MD Abul Bashar
 * Author URI: https://facebook.com/hmbashar
 * Description: This plugin is for student exam results publishing.
 * Tags: Result, WP Result Plugin, EDU Results
 * Text Domain: edu-results
 */

// Define the prefix
define('EDU_RESULTS_PREFIX', 'edu_');

//Define textdomain
define('EDU_RESULTS_TEXTDOMAIN', 'edu-results');

/**
 * Register custom post type for exam results.
 */
function edu_results_register_post_type() {
    $labels = array(
        'name'               => __('Results', EDU_RESULTS_TEXTDOMAIN),
        'singular_name'      => __('Result', EDU_RESULTS_TEXTDOMAIN),
        'menu_name'          => __('EDU Results', EDU_RESULTS_TEXTDOMAIN),
        'add_new'            => __('Add New', EDU_RESULTS_TEXTDOMAIN),
        'add_new_item'       => __('Add New Result', EDU_RESULTS_TEXTDOMAIN),
        'edit_item'          => __('Edit Result', EDU_RESULTS_TEXTDOMAIN),
        'new_item'           => __('New Result', EDU_RESULTS_TEXTDOMAIN),
        'view_item'          => __('View Result', EDU_RESULTS_TEXTDOMAIN),
        'search_items'       => __('Search Results', EDU_RESULTS_TEXTDOMAIN),
        'not_found'          => __('No Results found', EDU_RESULTS_TEXTDOMAIN),
        'not_found_in_trash' => __('No Results found in Trash', EDU_RESULTS_TEXTDOMAIN),
        'parent_item_colon'  => __('Parent Result:', EDU_RESULTS_TEXTDOMAIN),
        'all_items'          => __('All Results', EDU_RESULTS_TEXTDOMAIN),
        'archives'           => __('Result Archives', EDU_RESULTS_TEXTDOMAIN),
        'insert_into_item'   => __('Insert into Result', EDU_RESULTS_TEXTDOMAIN),
        'uploaded_to_this_item' => __('Uploaded to this Result', EDU_RESULTS_TEXTDOMAIN),
        'featured_image'        => __('Student Image', EDU_RESULTS_TEXTDOMAIN), // Use the defined constant
        'set_featured_image'    => __('Set Student Picture', EDU_RESULTS_TEXTDOMAIN), // Change the featured image label here
        'remove_featured_image' => __('Remove Student Picture', EDU_RESULTS_TEXTDOMAIN), // Change the featured image removal label here
        'use_featured_image'    => __('Use as Student Picture', EDU_RESULTS_TEXTDOMAIN), // Change the featured image use label here
        'menu_icon'             => 'dashicons-book', // You can change the icon
        'public'                => true,
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'edu-results'),
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
    );
    

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-book',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    );

    register_post_type(EDU_RESULTS_PREFIX . 'results', $args);
}

add_action('init', 'edu_results_register_post_type');

/**
 * Add a "Donate" link to the plugin action links.
 */
function edu_results_plugin_action_links($links) {
    $donate_link = '<a href="https://www.buymeacoffee.com/hmbashar" target="_blank">' . __('Donate', EDU_RESULTS_TEXTDOMAIN) . '</a>';
    array_unshift($links, $donate_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'edu_results_plugin_action_links');





 /**
 * Add a submenu page under the 'Edu Result' custom post type.
 */
function edu_results_add_submenu_page() {
    $parent_slug = 'edit.php?post_type=' . EDU_RESULTS_PREFIX . 'results'; // Parent menu slug (the custom post type)
    $page_title = __('Edu Result Settings', EDU_RESULTS_TEXTDOMAIN);
    $menu_title = __('Settings', EDU_RESULTS_TEXTDOMAIN);
    $capability = 'edit_posts'; // Adjust the capability as needed
    $menu_slug = 'edu_results_settings'; // Page slug
    $function = 'edu_results_settings_page'; // Callback function to render the submenu page content

    add_submenu_page(
        $parent_slug,
        $page_title,
        $menu_title,
        $capability,
        $menu_slug,
        $function
    );
}


add_action('admin_menu', 'edu_results_add_submenu_page');

/**
 * Render the content of the settings page.
 */
function edu_results_settings_page() {
    ?>
    <div class="wrap">
        <h2>Edu Results Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('edu_results_settings_group'); ?>
            <?php do_settings_sections('edu_results_settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Register plugin settings and fields.
 */
function edu_results_register_settings() {
    // Register a setting
    register_setting('edu_results_settings_group', 'edu_results_setting_name');

    // Add a section to the settings page
    add_settings_section(
        'edu_results_settings_section',
        'General Settings',
        'edu_results_settings_section_callback',
        'edu_results_settings'
    );

    // Add a field to the section
    add_settings_field(
        'edu_results_setting_name',
        'Setting Name',
        'edu_results_setting_callback',
        'edu_results_settings',
        'edu_results_settings_section'
    );
}

/**
 * Callback function for the settings section.
 */
function edu_results_settings_section_callback() {
    echo 'These are the general settings for the Edu Results plugin.';
}

/**
 * Callback function for the settings field.
 */
function edu_results_setting_callback() {
    $setting_value = get_option('edu_results_setting_name');
    echo '<input type="text" name="edu_results_setting_name" value="' . esc_attr($setting_value) . '" />';
}

add_action('admin_init', 'edu_results_register_settings');


/**
 * Change the placeholder label for the title input field to "Student Name".
 *
 * @param string $title The default placeholder label.
 * @return string The modified placeholder label.
 */
function edu_results_change_title_placeholder($title) {
    $screen = get_current_screen();
    if ($screen->post_type == EDU_RESULTS_PREFIX . 'results') {
        $title = 'Student Name';
    }
    return $title;
}

add_filter('enter_title_here', 'edu_results_change_title_placeholder');

