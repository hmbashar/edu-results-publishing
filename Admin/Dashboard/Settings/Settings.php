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
                    <form method="post" action="options.php" enctype="multipart/form-data" class="cbedu-general-settings-form">
                        <?php settings_fields('cbedu_results_settings_group'); ?>
                        <?php wp_nonce_field('cbedu_results_settings_nonce', 'cbedu_results_settings_nonce_field'); ?>
                        
                        <!-- Branding Section -->
                        <div class="cbedu-modern-card">
                            <div class="cbedu-modern-card-header">
                                <div class="cbedu-modern-card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                                        <path d="M21 15L16 10L5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3><?php esc_html_e('Branding & Identity', 'edu-results'); ?></h3>
                                    <p><?php esc_html_e('Logo and visual identity settings', 'edu-results'); ?></p>
                                </div>
                            </div>
                            <div class="cbedu-modern-card-body">
                                <div class="cbedu-modern-field">
                                    <label><?php esc_html_e('Institution Logo', 'edu-results'); ?></label>
                                    <span class="cbedu-field-hint"><?php esc_html_e('Recommended size: 200x200px', 'edu-results'); ?></span>
                                    <?php $this->logoFieldCallback(); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information Section -->
                        <div class="cbedu-modern-card">
                            <div class="cbedu-modern-card-header">
                                <div class="cbedu-modern-card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3><?php esc_html_e('Basic Information', 'edu-results'); ?></h3>
                                    <p><?php esc_html_e('Essential details about your institution', 'edu-results'); ?></p>
                                </div>
                            </div>
                            <div class="cbedu-modern-card-body">
                                <div class="cbedu-modern-grid">
                                    <div class="cbedu-modern-field">
                                        <label for="cbedu_results_collage_name"><?php esc_html_e('Institution Name', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Official name of your institution', 'edu-results'); ?></span>
                                        <?php $this->collageNameFieldCallback(); ?>
                                    </div>
                                    <div class="cbedu-modern-field">
                                        <label for="cbedu_results_collage_registration_number"><?php esc_html_e('Registration Number', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Official registration or EIIN number', 'edu-results'); ?></span>
                                        <?php $this->collageRegistrationNumberFieldCallback(); ?>
                                    </div>
                                    <div class="cbedu-modern-field">
                                        <label for="cbedu_results_collage_since_year"><?php esc_html_e('Established Year', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Year when institution was founded', 'edu-results'); ?></span>
                                        <?php $this->collageSinceYearFieldCallback(); ?>
                                    </div>
                                    <div class="cbedu-modern-field cbedu-field-full">
                                        <label for="cbedu_results_banner_heading"><?php esc_html_e('Result Page Banner Heading', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Custom heading for result pages', 'edu-results'); ?></span>
                                        <?php $this->resultPageBannerHeadingCallback(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="cbedu-modern-card">
                            <div class="cbedu-modern-card-header">
                                <div class="cbedu-modern-card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3><?php esc_html_e('Contact Information', 'edu-results'); ?></h3>
                                    <p><?php esc_html_e('How people can reach your institution', 'edu-results'); ?></p>
                                </div>
                            </div>
                            <div class="cbedu-modern-card-body">
                                <div class="cbedu-modern-grid">
                                    <div class="cbedu-modern-field">
                                        <label for="cbedu_results_collage_email_address"><?php esc_html_e('Email Address', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Primary contact email', 'edu-results'); ?></span>
                                        <?php $this->collageEmailAddressFieldCallback(); ?>
                                    </div>
                                    <div class="cbedu-modern-field">
                                        <label for="cbedu_results_collage_phone_number"><?php esc_html_e('Phone Number', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Primary contact number', 'edu-results'); ?></span>
                                        <?php $this->collagePhoneNumberFieldCallback(); ?>
                                    </div>
                                    <div class="cbedu-modern-field">
                                        <label for="cbedu_results_collage_website_url"><?php esc_html_e('Website URL', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Official website address', 'edu-results'); ?></span>
                                        <?php $this->collageWebsiteUrlFieldCallback(); ?>
                                    </div>
                                    <div class="cbedu-modern-field cbedu-field-full">
                                        <label for="cbedu_results_collage_address"><?php esc_html_e('Physical Address', 'edu-results'); ?></label>
                                        <span class="cbedu-field-hint"><?php esc_html_e('Complete postal address', 'edu-results'); ?></span>
                                        <?php $this->collageAddressFieldCallback(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="cbedu-modern-save-section">
                            <?php submit_button(esc_html__('💾 Save All Settings', 'edu-results'), 'primary cbedu-modern-save-btn', 'submit', false); ?>
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
