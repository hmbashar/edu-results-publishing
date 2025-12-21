<?php
namespace cbedu\inc\admin\settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CBEDUResultSettings
{

    private $prefix;

    public function __construct($eduResultPublishing)
    {

        $this->prefix = $eduResultPublishing->getPrefix();

        add_action('admin_menu', array($this, 'addSubmenuPage'));
        add_action('admin_init', array($this, 'registerSettings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueSettingsStyles'));
    }

    public function enqueueSettingsStyles($hook)
    {
        // Only load on our settings page
        if ($hook !== 'cbedu_results_page_cbedu_results_settings') {
            return;
        }
        
        wp_enqueue_style(
            'cbedu-admin-settings',
            plugins_url('../../assets/css/admin-settings.css', __FILE__),
            array(),
            '1.0.0'
        );
    }

    public function addSubmenuPage()
    {
        $parentSlug = 'edit.php?post_type=' . $this->prefix . 'results';
        $pageTitle = __('Edu Result Settings', 'edu-results');
        $menuTitle = __('Settings', 'edu-results');
        $capability = 'edit_posts';
        $menuSlug = 'cbedu_results_settings';
        $function = array($this, 'renderSettingsPage');

        add_submenu_page($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $function);
    }
    public function renderSettingsPage()
    {
?>
        <div class="wrap cbedu-settings-wrap">
            <div class="cbedu-settings-header">
                <h1><?php echo esc_html__('🎓 Edu Results Settings', 'edu-results'); ?></h1>
                <p class="cbedu-settings-subtitle"><?php echo esc_html__('Configure your educational institution settings', 'edu-results'); ?></p>
            </div>
            
            <?php settings_errors(); ?>
            
            <div class="cbedu-settings-container">
                <form method="post" action="options.php" enctype="multipart/form-data" class="cbedu-settings-form">
                    <?php settings_fields('cbedu_results_settings_group'); ?>
                    <?php wp_nonce_field('cbedu_results_settings_nonce', 'cbedu_results_settings_nonce_field'); ?>
                    
                    <div class="cbedu-settings-card">
                        <div class="cbedu-card-header">
                            <h2>🏛️ Institution Information</h2>
                            <p class="description">Basic information about your educational institution</p>
                        </div>
                        <div class="cbedu-card-body">
                            <?php do_settings_sections('cbedu_results_settings'); ?>
                        </div>
                    </div>
                    
                    <div class="cbedu-settings-footer">
                        <?php submit_button(__('Save Settings', 'edu-results'), 'primary cbedu-save-btn', 'submit', false); ?>
                    </div>
                </form>
            </div>
        </div>
    <?php
    }

    public function registerSettings()
    {
        // Add a new option for the logo
        register_setting(
            'cbedu_results_settings_group',
            'cbedu_results_logo',
            array(
                'type' => 'string',
                // Store the attachment URL as a string
                'sanitize_callback' => 'esc_url_raw',
                // Sanitize the URL
                'show_in_rest' => true, // Show the field in the REST API
            )
        );
        // Add a new option for Collage Name
        register_setting('cbedu_results_settings_group', 'cbedu_results_collage_name', 'sanitize_text_field');

        // Add a new option for Collage Registration Number
        register_setting('cbedu_results_settings_group', 'cbedu_results_collage_registration_number', 'sanitize_text_field');

        // Add a new option for Collage Since Year
        register_setting('cbedu_results_settings_group', 'cbedu_results_collage_since_year', 'sanitize_text_field');

        // Add a new option for Collage Address
        register_setting('cbedu_results_settings_group', 'cbedu_results_collage_address', 'sanitize_textarea_field');

        // Add a new option for Collage Phone Number
        register_setting('cbedu_results_settings_group', 'cbedu_results_collage_phone_number', 'sanitize_text_field');

        // Add a new option for Collage Email Address
        register_setting('cbedu_results_settings_group', 'cbedu_results_collage_email_address', 'sanitize_email');
        
        // Register a new option for College Website URL
        register_setting('cbedu_results_settings_group', 'cbedu_results_collage_website_url', 'esc_url_raw');
        
        // Register a new option for Result Page Banner Heading
        register_setting('cbedu_results_settings_group', 'cbedu_results_banner_heading', 'sanitize_text_field');


        add_settings_field(
            'cbedu_results_logo',
            'Logo',
            array($this, 'logoFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        add_settings_section(
            'cbedu_results_settings_section',
            'General Settings',
            array($this, 'settingsSectionCallback'),
            'cbedu_results_settings'
        );


        // Add new settings fields for the new options
        add_settings_field(
            'cbedu_results_collage_name',
            'Collage Name',
            array($this, 'collageNameFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        add_settings_field(
            'cbedu_results_collage_registration_number',
            'Collage Registration Number',
            array($this, 'collageRegistrationNumberFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        add_settings_field(
            'cbedu_results_collage_since_year',
            'Collage Since Year',
            array($this, 'collageSinceYearFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        add_settings_field(
            'cbedu_results_collage_phone_number',
            'Collage Phone Number',
            array($this, 'collagePhoneNumberFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        add_settings_field(
            'cbedu_results_collage_email_address',
            'Collage Email Address',
            array($this, 'collageEmailAddressFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );
        //field for College Website URL
        add_settings_field(
            'cbedu_results_collage_website_url',
            'College Website URL',
            array($this, 'collageWebsiteUrlFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        // Add new settings field for Result Page Banner Heading
        add_settings_field(
            'cbedu_results_banner_heading',
            'Result Page Banner Heading',
            array($this, 'resultPageBannerHeadingCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        add_settings_field(
            'cbedu_results_collage_address',
            'Collage Address',
            array($this, 'collageAddressFieldCallback'),
            'cbedu_results_settings',
            'cbedu_results_settings_section'
        );

        // Add nonce validation
        add_action('admin_init', array($this, 'validateNonce'));
    }

    public function validateNonce()
    {
        if (isset($_POST['cbedu_results_settings_nonce_field'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST['cbedu_results_settings_nonce_field']));
            if (!wp_verify_nonce($nonce, 'cbedu_results_settings_nonce')) {
                die('Security check failed.');
            }
        }
    }

    public function logoFieldCallback()
    {
        $logoURL = get_option('cbedu_results_logo');
    ?>
        <div class="cbedu-field-wrapper">
            <input type="text" name="cbedu_results_logo" value="<?php echo esc_url($logoURL); ?>" id="cbedu_results_logo" placeholder="https://example.com/logo.png">
            <input type="button" id="upload_logo_button" class="button" value="📁 Upload Logo">
            <p class="description">Upload or enter the URL of your institution's logo</p>
            <?php if ($logoURL): ?>
                <img src="<?php echo esc_url($logoURL); ?>" alt="Logo Preview" id="logo_preview">
            <?php endif; ?>
        </div>
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
                        $('#cbedu_results_logo').val(attachment.url);
                        $('#logo_preview').attr('src', attachment.url).show();
                    });

                    customUploader.open();
                });
            });
        </script>
<?php
    }


    public function settingsSectionCallback()
    {
        echo '<p class="cbedu-section-description">Configure the basic information about your educational institution. This information will be displayed on result pages.</p>';
    }



    public function collageNameFieldCallback()
    {
        $collageName = get_option('cbedu_results_collage_name');
        echo '<div class="cbedu-field-wrapper">';
        echo '<input type="text" name="cbedu_results_collage_name" value="' . esc_attr($collageName) . '" placeholder="e.g., Springfield High School" />';
        echo '<p class="description">Full name of your educational institution</p>';
        echo '</div>';
    }

    public function collageRegistrationNumberFieldCallback()
    {
        $collageRegNumber = get_option('cbedu_results_collage_registration_number');
        echo '<div class="cbedu-field-wrapper">';
        echo '<input type="text" name="cbedu_results_collage_registration_number" value="' . esc_attr($collageRegNumber) . '" placeholder="e.g., REG-2024-001" />';
        echo '<p class="description">Official registration or EIIN number</p>';
        echo '</div>';
    }

    public function collageSinceYearFieldCallback()
    {
        $collageSinceYear = get_option('cbedu_results_collage_since_year');
        echo '<div class="cbedu-field-wrapper">';
        echo '<input type="text" name="cbedu_results_collage_since_year" value="' . esc_attr($collageSinceYear) . '" placeholder="e.g., 1995" />';
        echo '<p class="description">Year when the institution was established</p>';
        echo '</div>';
    }

    public function collageAddressFieldCallback()
    {
        $collageAddress = get_option('cbedu_results_collage_address');
        echo '<div class="cbedu-field-wrapper">';
        echo '<textarea name="cbedu_results_collage_address" rows="5" cols="50" placeholder="Enter complete address with city, state, and postal code">' . esc_textarea($collageAddress) . '</textarea>';
        echo '<p class="description">Complete postal address of the institution</p>';
        echo '</div>';
    }
    

    public function collagePhoneNumberFieldCallback()
    {
        $collagePhoneNumber = get_option('cbedu_results_collage_phone_number');
        echo '<div class="cbedu-field-wrapper">';
        echo '<input type="text" name="cbedu_results_collage_phone_number" value="' . esc_attr($collagePhoneNumber) . '" placeholder="e.g., +880-123-456-7890" />';
        echo '<p class="description">Primary contact phone number</p>';
        echo '</div>';
    }

    public function collageEmailAddressFieldCallback()
    {
        $collageEmailAddress = get_option('cbedu_results_collage_email_address');
        echo '<div class="cbedu-field-wrapper">';
        echo '<input type="email" name="cbedu_results_collage_email_address" value="' . esc_attr($collageEmailAddress) . '" placeholder="e.g., info@institution.edu" />';
        echo '<p class="description">Official email address for contact</p>';
        echo '</div>';
    }


    public function collageWebsiteUrlFieldCallback()
    {
        $collageWebsiteUrl = get_option('cbedu_results_collage_website_url');
        echo '<div class="cbedu-field-wrapper">';
        echo '<input type="url" name="cbedu_results_collage_website_url" value="' . esc_url($collageWebsiteUrl) . '" placeholder="https://www.institution.edu" />';
        echo '<p class="description">Official website URL</p>';
        echo '</div>';
    }

    public function resultPageBannerHeadingCallback()
    {
        $bannerHeading = get_option('cbedu_results_banner_heading');
        echo '<div class="cbedu-field-wrapper">';
        echo '<input type="text" name="cbedu_results_banner_heading" value="' . esc_attr($bannerHeading) . '" placeholder="Enter custom heading text for result banners" />';
        echo '<p class="description">Custom heading text that appears on result pages</p>';
        echo '</div>';
    }
}
