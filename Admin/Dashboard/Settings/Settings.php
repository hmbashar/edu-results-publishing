<?php
namespace CBEDU\Admin\Dashboard\Settings;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Settings
{

    private $prefix;

    private $import_export;

    public function __construct()
    {

        $this->prefix = CBEDU_PREFIX;

        add_action('admin_menu', array($this, 'addSubmenuPage'));
        add_action('admin_init', array($this, 'registerSettings'));
        
        // Initialize ImportExport
        $this->import_export = new ImportExport();
        add_action('admin_notices', array($this->import_export, 'display_notices'));
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
        // Get current tab
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
    ?>
        <div class="wrap cbedu-settings-wrap">
            <div class="cbedu-settings-header">
                <h1><?php echo esc_html__('🎓 Edu Results Settings', 'edu-results'); ?></h1>
                <p class="cbedu-settings-subtitle"><?php echo esc_html__('Configure your educational institution settings', 'edu-results'); ?></p>
            </div>
            
            <?php settings_errors(); ?>
            
            <!-- Tab Navigation -->
            <h2 class="nav-tab-wrapper">
                <a href="?post_type=<?php echo esc_attr($this->prefix . 'results'); ?>&page=cbedu_results_settings&tab=general" 
                   class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('General', 'edu-results'); ?>
                </a>
                <a href="?post_type=<?php echo esc_attr($this->prefix . 'results'); ?>&page=cbedu_results_settings&tab=import-export" 
                   class="nav-tab <?php echo $active_tab === 'import-export' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Import / Export', 'edu-results'); ?>
                </a>
            </h2>
            
            <div class="cbedu-settings-container">
                <?php if ($active_tab === 'general') : ?>
                    <!-- General Settings Tab -->
                    
                    <!-- Hero Section -->
                    <div class="cbedu-general-hero">
                        <div class="cbedu-general-hero-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                                <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 22V12H15V22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h2 class="cbedu-general-hero-title"><?php esc_html_e('Institution Information', 'edu-results'); ?></h2>
                        <p class="cbedu-general-hero-subtitle"><?php esc_html_e('Configure your educational institution details and branding', 'edu-results'); ?></p>
                    </div>

                    <form method="post" action="options.php" enctype="multipart/form-data" class="cbedu-general-form">
                        <?php settings_fields('cbedu_results_settings_group'); ?>
                        <?php wp_nonce_field('cbedu_results_settings_nonce', 'cbedu_results_settings_nonce_field'); ?>
                        
                        <div class="cbedu-general-grid">
                            
                            <!-- Institution Logo -->
                            <div class="cbedu-field-group cbedu-field-logo">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                                        <path d="M21 15L16 10L5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label"><?php esc_html_e('Institution Logo', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Upload your institution logo (recommended size: 200x200px)', 'edu-results'); ?></p>
                                    <?php $this->logoFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Institution Name -->
                            <div class="cbedu-field-group">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_collage_name"><?php esc_html_e('Institution Name', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Official name of your educational institution', 'edu-results'); ?></p>
                                    <?php $this->collageNameFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Registration Number -->
                            <div class="cbedu-field-group">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_collage_registration_number"><?php esc_html_e('Registration Number', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Official registration or identification number', 'edu-results'); ?></p>
                                    <?php $this->collageRegistrationNumberFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Since Year -->
                            <div class="cbedu-field-group">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_collage_since_year"><?php esc_html_e('Established Year', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Year when the institution was established', 'edu-results'); ?></p>
                                    <?php $this->collageSinceYearFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="cbedu-field-group">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_collage_email_address"><?php esc_html_e('Email Address', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Official contact email address', 'edu-results'); ?></p>
                                    <?php $this->collageEmailAddressFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="cbedu-field-group">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M22 16.92V19.92C22 20.4696 21.5523 20.9174 21.0027 20.9202C18.3631 20.9476 15.7738 20.1768 13.5244 18.6832C11.4285 17.3024 9.69736 15.5713 8.31662 13.4754C6.81856 11.2194 6.04738 8.62117 6.07762 5.97361C6.08037 5.42402 6.52786 4.97656 7.07746 4.97656H10.0775C10.6297 4.97656 11.0849 5.40713 11.0849 5.95928C11.0849 6.49784 11.1849 7.02656 11.3849 7.52656C11.5849 8.02656 11.5849 8.52656 11.3849 9.02656L10.3849 10.0266C11.3849 12.0266 13.3849 14.0266 15.3849 15.0266L16.3849 14.0266C16.8849 13.5266 17.3849 13.5266 17.8849 13.7266C18.3849 13.9266 18.9849 14.0266 19.5849 14.0266C20.1371 14.0266 20.5849 14.4818 20.5849 15.034V16.92H22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_collage_phone_number"><?php esc_html_e('Phone Number', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Primary contact phone number', 'edu-results'); ?></p>
                                    <?php $this->collagePhoneNumberFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Website URL -->
                            <div class="cbedu-field-group">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                        <path d="M2 12H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M12 2C14.5 4.5 16 8 16 12C16 16 14.5 19.5 12 22C9.5 19.5 8 16 8 12C8 8 9.5 4.5 12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_collage_website_url"><?php esc_html_e('Website URL', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Official website address', 'edu-results'); ?></p>
                                    <?php $this->collageWebsiteUrlFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="cbedu-field-group cbedu-field-full">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M21 10C21 17 12 23 12 23C12 23 3 17 3 10C3 7.61305 3.94821 5.32387 5.63604 3.63604C7.32387 1.94821 9.61305 1 12 1C14.3869 1 16.6761 1.94821 18.364 3.63604C20.0518 5.32387 21 7.61305 21 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_collage_address"><?php esc_html_e('Address', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Complete physical address of the institution', 'edu-results'); ?></p>
                                    <?php $this->collageAddressFieldCallback(); ?>
                                </div>
                            </div>

                            <!-- Banner Heading -->
                            <div class="cbedu-field-group cbedu-field-full">
                                <div class="cbedu-field-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 7H20M4 12H20M4 17H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="cbedu-field-content">
                                    <label class="cbedu-field-label" for="cbedu_results_banner_heading"><?php esc_html_e('Result Page Banner Heading', 'edu-results'); ?></label>
                                    <p class="cbedu-field-description"><?php esc_html_e('Heading text displayed on the result page banner', 'edu-results'); ?></p>
                                    <?php $this->resultPageBannerHeadingCallback(); ?>
                                </div>
                            </div>

                        </div>

                        <!-- Save Button -->
                        <div class="cbedu-general-footer">
                            <?php submit_button(esc_html__('Save Settings', 'edu-results'), 'primary cbedu-general-save-btn', 'submit', false); ?>
                        </div>
                    </form>
                    
                <?php elseif ($active_tab === 'import-export') : ?>
                    <!-- Import/Export Tab -->
                    <?php $this->import_export->render_import_export_tab(); ?>
                    
                <?php endif; ?>
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
