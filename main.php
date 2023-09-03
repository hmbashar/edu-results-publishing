<?php
/**
 * Plugin Name: EDU Results Publishing
 * Author: MD Abul Bashar
 * Author URI: https://facebook.com/hmbashar
 * Description: This plugin is for student exam results publishing.
 * Tags: Result, WP Result Plugin, EDU Results
 * Text Domain: edu-results
 */

class EDUResultPublishing {
    private $prefix;
    private $textdomain;

    public function __construct() {
        $this->prefix = 'edu_';
        $this->textdomain = 'edu-results';

        add_action('init', array($this, 'registerPostType'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'addPluginActionLinks'));
        add_action('admin_menu', array($this, 'addSubmenuPage'));
        add_action('admin_init', array($this, 'registerSettings'));
        add_filter('enter_title_here', array($this, 'changeTitlePlaceholder'));
    }

    public function registerPostType() {
        $labels = array(
            'name'               => __('Results', $this->textdomain),
            'singular_name'      => __('Result', $this->textdomain),
            'menu_name'          => __('EDU Results', $this->textdomain),
            'add_new'            => __('Add New', $this->textdomain),
            'add_new_item'       => __('Add New Result', $this->textdomain),
            'edit_item'          => __('Edit Result', $this->textdomain),
            'new_item'           => __('New Result', $this->textdomain),
            'view_item'          => __('View Result', $this->textdomain),
            'search_items'       => __('Search Results', $this->textdomain),
            'not_found'          => __('No Results found', $this->textdomain),
            'not_found_in_trash' => __('No Results found in Trash', $this->textdomain),
            'parent_item_colon'  => __('Parent Result:', $this->textdomain),
            'all_items'          => __('All Results', $this->textdomain),
            'archives'           => __('Result Archives', $this->textdomain),
            'insert_into_item'   => __('Insert into Result', $this->textdomain),
            'uploaded_to_this_item' => __('Uploaded to this Result', $this->textdomain),
            'featured_image'        => __('Student Image', $this->textdomain),
            'set_featured_image'    => __('Set Student Picture', $this->textdomain),
            'remove_featured_image' => __('Remove Student Picture', $this->textdomain),
            'use_featured_image'    => __('Use as Student Picture', $this->textdomain),
            'menu_icon'             => 'dashicons-book',
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
    
        register_post_type($this->prefix . 'results', $args);
    }
    

    public function addPluginActionLinks($links) {
        $donateLink = '<a href="https://www.buymeacoffee.com/hmbashar" target="_blank">' . __('Donate', $this->textdomain) . '</a>';
        array_unshift($links, $donateLink);
        return $links;
    }

    public function addSubmenuPage() {
        $parentSlug = 'edit.php?post_type=' . $this->prefix . 'results';
        $pageTitle = __('Edu Result Settings', $this->textdomain);
        $menuTitle = __('Settings', $this->textdomain);
        $capability = 'edit_posts';
        $menuSlug = 'edu_results_settings';
        $function = array($this, 'renderSettingsPage');

        add_submenu_page($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $function);
    }
    public function renderSettingsPage() {
        ?>
        <div class="wrap">
            <h2>Edu Results Settings</h2>
            <form method="post" action="options.php" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
                <?php settings_fields('edu_results_settings_group'); ?>
                <?php do_settings_sections('edu_results_settings'); ?>
    
             
    
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    public function registerSettings() {
        register_setting('edu_results_settings_group', 'edu_results_setting_name');
        // Add a new option for the logo
        register_setting('edu_results_settings_group', 'edu_results_logo', array(
            'type' => 'string', // Store the attachment URL as a string
            'sanitize_callback' => 'esc_url_raw', // Sanitize the URL
            'show_in_rest' => true, // Show the field in the REST API
        ));
    
        add_settings_section(
            'edu_results_settings_section',
            'General Settings',
            array($this, 'settingsSectionCallback'),
            'edu_results_settings'
        );
    
        add_settings_field(
            'edu_results_setting_name',
            'Setting Name',
            array($this, 'settingsFieldCallback'),
            'edu_results_settings',
            'edu_results_settings_section'
        );
    
        // Add a new settings field for the logo
        add_settings_field(
            'edu_results_logo',
            'Logo',
            array($this, 'logoFieldCallback'),
            'edu_results_settings',
            'edu_results_settings_section'
        );
    }
    

    public function logoFieldCallback() {
        $logoURL = get_option('edu_results_logo');
        ?>
        <input type="text" name="edu_results_logo" value="<?php echo esc_url($logoURL); ?>" id="edu_results_logo">
        <input type="button" id="upload_logo_button" class="button" value="Upload Logo">
        <br>
        <img src="<?php echo esc_url($logoURL); ?>" alt="Logo Preview" id="logo_preview" style="max-width: 200px;">
        <script>
        jQuery(document).ready(function($) {
            $('#upload_logo_button').click(function(e) {
                e.preventDefault();
    
                var customUploader = wp.media({
                    title: 'Upload Logo',
                    button: {
                        text: 'Use this Logo'
                    },
                    library: {
                        type: 'image'
                    },
                    multiple: false
                });
    
                customUploader.on('select', function() {
                    var attachment = customUploader.state().get('selection').first().toJSON();
                    $('#edu_results_logo').val(attachment.url);
                    $('#logo_preview').attr('src', attachment.url);
                });
    
                customUploader.open();
            });
        });
        </script>
        <p class="description">Upload your logo here.</p>
        <?php
    }
    

    public function settingsSectionCallback() {
        echo 'These are the general settings for the Edu Results plugin.';
    }

    public function settingsFieldCallback() {
        $settingValue = get_option('edu_results_setting_name');
        echo '<input type="text" name="edu_results_setting_name" value="' . esc_attr($settingValue) . '" />';
    }

    public function changeTitlePlaceholder($title) {
        $screen = get_current_screen();
        if ($screen->post_type == $this->prefix . 'results') {
            $title = 'Student Name';
        }
        return $title;
    }
}

$eduResultPublishing = new EDUResultPublishing();

function enqueue_wp_media() {
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enqueue_wp_media');
