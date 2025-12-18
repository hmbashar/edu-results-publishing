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
        
        <style>
            .cbedu-settings-wrap {
                margin: 20px 20px 20px 0;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            }
            
            .cbedu-settings-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #fff;
                padding: 30px 40px;
                border-radius: 12px 12px 0 0;
                margin: 0 0 30px 0;
                box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
            }
            
            .cbedu-settings-header h1 {
                color: #fff;
                margin: 0 0 8px 0;
                font-size: 32px;
                font-weight: 700;
                letter-spacing: -0.5px;
            }
            
            .cbedu-settings-subtitle {
                color: rgba(255, 255, 255, 0.95);
                margin: 0;
                font-size: 16px;
                font-weight: 400;
            }
            
            .cbedu-settings-container {
                max-width: 900px;
            }
            
            .cbedu-settings-card {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
                margin-bottom: 30px;
                overflow: hidden;
            }
            
            .cbedu-card-header {
                background: linear-gradient(to bottom, #f8f9fa, #e9ecef);
                padding: 25px 30px;
                border-bottom: 2px solid #dee2e6;
            }
            
            .cbedu-card-header h2 {
                margin: 0 0 8px 0;
                font-size: 20px;
                font-weight: 600;
                color: #2d3748;
            }
            
            .cbedu-card-header .description {
                margin: 0;
                color: #718096;
                font-size: 14px;
            }
            
            .cbedu-card-body {
                padding: 30px;
            }
            
            .cbedu-card-body .form-table {
                margin: 0;
                border: none;
            }
            
            .cbedu-card-body .form-table th {
                padding: 16px 0;
                font-weight: 600;
                color: #2d3748;
                font-size: 14px;
                width: 220px;
            }
            
            .cbedu-card-body .form-table td {
                padding: 16px 0 16px 20px;
            }
            
            .cbedu-card-body .form-table tr {
                border-bottom: 1px solid #f0f0f1;
            }
            
            .cbedu-card-body .form-table tr:last-child {
                border-bottom: none;
            }
            
            .cbedu-card-body input[type="text"],
            .cbedu-card-body input[type="email"],
            .cbedu-card-body input[type="url"],
            .cbedu-card-body textarea {
                width: 100%;
                max-width: 500px;
                padding: 10px 14px;
                border: 1.5px solid #d1d5db;
                border-radius: 8px;
                font-size: 14px;
                line-height: 1.4;
                transition: all 0.2s ease;
                background: #fff;
                font-family: inherit;
            }
            
            .cbedu-card-body input[type="text"]:focus,
            .cbedu-card-body input[type="email"]:focus,
            .cbedu-card-body input[type="url"]:focus,
            .cbedu-card-body textarea:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                background: #fafbff;
            }
            
            .cbedu-card-body textarea {
                min-height: 100px;
                resize: vertical;
            }
            
            .cbedu-card-body .description {
                margin: 8px 0 0 0;
                color: #718096;
                font-size: 13px;
                font-style: italic;
            }
            
            .cbedu-card-body .button {
                padding: 8px 18px;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 600;
                border: 1.5px solid #667eea;
                background: #fff;
                color: #667eea;
                transition: all 0.2s ease;
                cursor: pointer;
                margin-top: 8px;
            }
            
            .cbedu-card-body .button:hover {
                background: #667eea;
                color: #fff;
                border-color: #5a67d8;
                transform: translateY(-1px);
            }
            
            .cbedu-field-wrapper {
                display: block;
            }
            
            .cbedu-field-wrapper input[type="text"],
            .cbedu-field-wrapper input[type="email"],
            .cbedu-field-wrapper input[type="url"],
            .cbedu-field-wrapper textarea {
                display: block;
                margin-bottom: 8px;
            }
            
            .cbedu-section-description {
                margin: 0 0 20px 0;
                color: #4a5568;
                font-size: 14px;
                line-height: 1.6;
                padding: 15px;
                background: #f7fafc;
                border-left: 4px solid #667eea;
                border-radius: 4px;
            }
            
            #logo_preview {
                margin-top: 15px;
                border-radius: 8px;
                border: 2px solid #e2e8f0;
                padding: 10px;
                background: #f8f9fa;
                display: block;
                max-width: 250px;
                height: auto;
            }
            
            .cbedu-settings-footer {
                margin-top: 30px;
                padding: 0;
            }
            
            .cbedu-save-btn {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                border: none !important;
                color: #fff !important;
                padding: 14px 40px !important;
                font-size: 15px !important;
                font-weight: 600 !important;
                border-radius: 8px !important;
                cursor: pointer;
                transition: all 0.3s ease !important;
                box-shadow: 0 4px 14px rgba(102, 126, 234, 0.35) !important;
                text-transform: uppercase;
                letter-spacing: 0.6px;
                height: auto !important;
                line-height: 1 !important;
            }
            
            .cbedu-save-btn:hover {
                background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 6px 20px rgba(102, 126, 234, 0.45) !important;
            }
            
            .cbedu-save-btn:active {
                transform: translateY(0) !important;
            }
            
            /* Responsive */
            @media (max-width: 782px) {
                .cbedu-settings-header {
                    padding: 25px 20px;
                }
                
                .cbedu-settings-header h1 {
                    font-size: 24px;
                }
                
                .cbedu-card-header {
                    padding: 20px;
                }
                
                .cbedu-card-body {
                    padding: 20px;
                }
                
                .cbedu-card-body .form-table th,
                .cbedu-card-body .form-table td {
                    display: block;
                    width: 100%;
                    padding: 10px 0;
                }
                
                .cbedu-card-body input[type="text"],
                .cbedu-card-body input[type="email"],
                .cbedu-card-body input[type="url"],
                .cbedu-card-body textarea {
                    max-width: 100%;
                }
            }
        </style>
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
