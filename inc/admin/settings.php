<?php 

namespace inc\admin\settings;

class EDUResultSettings {

    private $prefix;
    private $textdomain;
    
    public function __construct($eduResultPublishing) {
        $this->textdomain = $eduResultPublishing->getTextDomain();
        $this->prefix = $eduResultPublishing->getPrefix();

        add_action('admin_menu', array($this, 'addSubmenuPage'));
        add_action('admin_init', array($this, 'registerSettings'));
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
}