<?php

namespace cbedu\inc\custom_fields;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
class CBEDUCustomFields
{

    /**
     * Constructs a new instance of the class.
     *
     * Initializes the class by calling the register_student_fields(), register_subject_fields(),
     * and register_result_fields() methods.
     *
     * @throws Some_Exception_Class description of exception
     */
    public function __construct()
    {

        // Call the register_student_fields() method during class initialization
        $this->register_student_fields();

        $this->register_subject_fields();

        $this->register_result_fields(); // Call the register_result_fields() method

        add_action('admin_notices', array($this, 'cbedu_admin_notices'));

        add_action('wp_ajax_add_search_registration_numbers', array($this, 'add_search_registration_numbers'));
        //  add_action('wp_ajax_nopriv_add_search_registration_numbers', array($this, 'add_search_registration_numbers'));
        
        // Hook to handle auto-draft deletion
        add_action('admin_init', array($this, 'handle_auto_draft_deletion'));
    }
    
    /**
     * Handle auto-draft deletion via admin actions
     */
    public function handle_auto_draft_deletion()
    {
        // Check if we're trying to delete an auto-draft
        if (isset($_GET['action']) && $_GET['action'] === 'trash' && isset($_GET['post'])) {
            $post_id = intval($_GET['post']);
            $post = get_post($post_id);
            
            if ($post && $post->post_status === 'auto-draft' && 
                in_array($post->post_type, array('cbedu_students', 'cbedu_subjects', 'cbedu_results'))) {
                
                // Force delete auto-draft instead of trashing
                wp_delete_post($post_id, true);
                
                // Redirect back to the list
                wp_redirect(admin_url('edit.php?post_type=' . $post->post_type . '&deleted=1'));
                exit;
            }
        }
        
        // Handle bulk deletion
        if (isset($_GET['action']) && $_GET['action'] === 'trash' && isset($_GET['post']) && is_array($_GET['post'])) {
            foreach ($_GET['post'] as $post_id) {
                $post_id = intval($post_id);
                $post = get_post($post_id);
                
                if ($post && $post->post_status === 'auto-draft' && 
                    in_array($post->post_type, array('cbedu_students', 'cbedu_subjects', 'cbedu_results'))) {
                    
                    // Force delete auto-draft instead of trashing
                    wp_delete_post($post_id, true);
                }
            }
        }
    }

    /**
     * Registers the student fields.
     *
     * @return void
     */
    public function register_student_fields()
    {
        add_action('add_meta_boxes', array($this, 'add_student_fields_meta_box'));

        add_action('save_post', array($this, 'save_student_fields'));
        add_action('save_post', array($this, 'cbedu_check_unique_registration_number'));        
        
        // Clean up auto-draft posts
        add_action('wp_scheduled_delete', array($this, 'cleanup_auto_drafts'));
        
        // Allow deletion of auto-draft posts
        add_filter('user_has_cap', array($this, 'allow_auto_draft_deletion'), 10, 4);
        
        // Disable autosave for our custom post types
        add_action('admin_print_scripts', array($this, 'disable_autosave'));
    }
    
    /**
     * Disable autosave for custom post types
     */
    public function disable_autosave()
    {
        global $post;
        
        if ($post && in_array($post->post_type, array('cbedu_students', 'cbedu_subjects', 'cbedu_results'))) {
            wp_dequeue_script('autosave');
        }
    }
    
    /**
     * Allow users to delete auto-draft posts for our custom post types
     */
    public function allow_auto_draft_deletion($allcaps, $caps, $args, $user)
    {
        // Check if this is a delete operation
        if (isset($args[0]) && in_array($args[0], array('delete_post', 'delete_posts'))) {
            // Check if we have a post ID
            if (isset($args[2])) {
                $post_id = $args[2];
                $post = get_post($post_id);
                
                // If it's an auto-draft of our custom post types, allow deletion
                if ($post && in_array($post->post_type, array('cbedu_students', 'cbedu_subjects', 'cbedu_results'))) {
                    if ($post->post_status === 'auto-draft') {
                        $allcaps['delete_posts'] = true;
                        $allcaps['delete_published_posts'] = true;
                        $allcaps['delete_others_posts'] = true;
                    }
                }
            }
        }
        
        return $allcaps;
    }
    
    /**
     * Clean up old auto-draft posts for cbedu custom post types
     */
    public function cleanup_auto_drafts()
    {
        global $wpdb;
        
        // Delete auto-drafts older than 7 days for our custom post types
        $post_types = array('cbedu_students', 'cbedu_subjects', 'cbedu_results');
        
        foreach ($post_types as $post_type) {
            $wpdb->query($wpdb->prepare(
                "DELETE FROM {$wpdb->posts} 
                WHERE post_type = %s 
                AND post_status = 'auto-draft' 
                AND DATE_SUB(NOW(), INTERVAL 7 DAY) > post_date",
                $post_type
            ));
        }
    }

    /**
     * Adds the student fields meta box.
     *
     * @return void
     */
    public function add_student_fields_meta_box()
    {
        add_meta_box(
            'student_fields',
            'Student Fields',
            array($this, 'render_student_fields_meta_box'),
            'cbedu_students',
            //  custom post type slug
            'normal',
            'default'
        );
    }

    /**
     * Renders the student fields meta box.
     *
     * @param object $post The post object.
     * @throws None.
     * @return None.
     */
    public function render_student_fields_meta_box($post)
    {
        wp_nonce_field('cbedu_save_student_nonce_action', 'cbedu_save_student_nonce');

        // Get all meta values
        $id_number = get_post_meta($post->ID, 'cbedu_result_std_id', true);        
        $std_registration_number = get_post_meta($post->ID, 'cbedu_result_std_registration_number', true);
        $father_name = get_post_meta($post->ID, 'cbedu_result_std_father_name', true);
        $mother_name = get_post_meta($post->ID, 'cbedu_result_std_mother_name', true);
        $dob = get_post_meta($post->ID, 'cbedu_result_std_dob', true);
        $gender = get_post_meta($post->ID, 'cbedu_result_std_gender', true);
        $phone = get_post_meta($post->ID, 'cbedu_result_std_phone', true);
        $email = get_post_meta($post->ID, 'cbedu_result_std_email', true);
        $blood_group = get_post_meta($post->ID, 'cbedu_result_std_blood_group', true);
        $address = get_post_meta($post->ID, 'cbedu_result_std_address', true);
        $guardian_phone = get_post_meta($post->ID, 'cbedu_result_std_guardian_phone', true);
        $fathers_qualification = get_post_meta($post->ID, 'cbedu_result_std_fathers_qualification', true);
        $fathers_occupation = get_post_meta($post->ID, 'cbedu_result_std_fathers_occupation', true);
        $mothers_occupation = get_post_meta($post->ID, 'cbedu_result_std_mothers_occupation', true);
        $mothers_qualification = get_post_meta($post->ID, 'cbedu_result_std_mothers_qualification', true);
        $birth_registration_number = get_post_meta($post->ID, 'cbedu_result_std_birth_registration_number', true);
        $nid_number = get_post_meta($post->ID, 'cbedu_result_std_nid_number', true);
?>
        <div class="cbedu-meta-box-wrap">
            <!-- Basic Information Section -->
            <div class="cbedu-field-section">
                <h3 class="cbedu-section-header"><?php esc_html_e('Basic Information', 'edu-results'); ?></h3>
                <div class="cbedu-section-content">
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_id">
                                <?php esc_html_e('ID Number', 'edu-results'); ?>
                                <span class="required">*</span>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_id" name="cbedu_result_std_id" value="<?php echo esc_attr($id_number); ?>" placeholder="<?php esc_attr_e('Enter student ID', 'edu-results'); ?>" />
                        </div>
                        
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_registration_number">
                                <?php esc_html_e('Registration Number', 'edu-results'); ?>
                                <span class="required">*</span>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_registration_number" name="cbedu_result_std_registration_number" value="<?php echo esc_attr($std_registration_number); ?>" placeholder="<?php esc_attr_e('Enter registration number', 'edu-results'); ?>" />
                        </div>
                    </div>

                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_dob">
                                <?php esc_html_e('Date of Birth', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="date" id="cbedu_result_std_dob" name="cbedu_result_std_dob" value="<?php echo esc_attr($dob); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_gender">
                                <?php esc_html_e('Gender', 'edu-results'); ?>
                            </label>
                            <select class="cbedu-field-select" id="cbedu_result_std_gender" name="cbedu_result_std_gender">
                                <option value=""><?php esc_html_e('Select Gender', 'edu-results'); ?></option>
                                <option value="Male" <?php selected($gender, 'Male'); ?>><?php esc_html_e('Male', 'edu-results'); ?></option>
                                <option value="Female" <?php selected($gender, 'Female'); ?>><?php esc_html_e('Female', 'edu-results'); ?></option>
                                <option value="Other" <?php selected($gender, 'Other'); ?>><?php esc_html_e('Other', 'edu-results'); ?></option>
                            </select>
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_blood_group">
                                <?php esc_html_e('Blood Group', 'edu-results'); ?>
                            </label>
                            <select class="cbedu-field-select" id="cbedu_result_std_blood_group" name="cbedu_result_std_blood_group">
                                <option value=""><?php esc_html_e('Select Blood Group', 'edu-results'); ?></option>
                                <option value="A+" <?php selected($blood_group, 'A+'); ?>>A+</option>
                                <option value="A-" <?php selected($blood_group, 'A-'); ?>>A-</option>
                                <option value="B+" <?php selected($blood_group, 'B+'); ?>>B+</option>
                                <option value="B-" <?php selected($blood_group, 'B-'); ?>>B-</option>
                                <option value="AB+" <?php selected($blood_group, 'AB+'); ?>>AB+</option>
                                <option value="AB-" <?php selected($blood_group, 'AB-'); ?>>AB-</option>
                                <option value="O+" <?php selected($blood_group, 'O+'); ?>>O+</option>
                                <option value="O-" <?php selected($blood_group, 'O-'); ?>>O-</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="cbedu-field-section">
                <h3 class="cbedu-section-header"><?php esc_html_e('Contact Information', 'edu-results'); ?></h3>
                <div class="cbedu-section-content">
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_phone">
                                <?php esc_html_e('Phone Number', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_phone" name="cbedu_result_std_phone" value="<?php echo esc_attr($phone); ?>" placeholder="<?php esc_attr_e('+880...', 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_email">
                                <?php esc_html_e('Email Address', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="email" id="cbedu_result_std_email" name="cbedu_result_std_email" value="<?php echo esc_attr($email); ?>" placeholder="<?php esc_attr_e('student@example.com', 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_guardian_phone">
                                <?php esc_html_e('Guardian Phone', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_guardian_phone" name="cbedu_result_std_guardian_phone" value="<?php echo esc_attr($guardian_phone); ?>" placeholder="<?php esc_attr_e('+880...', 'edu-results'); ?>" />
                        </div>
                    </div>

                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group cbedu-field-full">
                            <label class="cbedu-field-label" for="cbedu_result_std_address">
                                <?php esc_html_e('Address', 'edu-results'); ?>
                            </label>
                            <textarea class="cbedu-field-textarea" id="cbedu_result_std_address" name="cbedu_result_std_address" rows="3" placeholder="<?php esc_attr_e('Enter full address...', 'edu-results'); ?>"><?php echo esc_textarea($address); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Information Section -->
            <div class="cbedu-field-section">
                <h3 class="cbedu-section-header"><?php esc_html_e('Family Information', 'edu-results'); ?></h3>
                <div class="cbedu-section-content">
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_father_name">
                                <?php esc_html_e("Father's Name", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_father_name" name="cbedu_result_std_father_name" value="<?php echo esc_attr($father_name); ?>" placeholder="<?php esc_attr_e("Enter father's name", 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_fathers_occupation">
                                <?php esc_html_e("Father's Occupation", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_fathers_occupation" name="cbedu_result_std_fathers_occupation" value="<?php echo esc_attr($fathers_occupation); ?>" placeholder="<?php esc_attr_e('Enter occupation', 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_fathers_qualification">
                                <?php esc_html_e("Father's Qualification", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_fathers_qualification" name="cbedu_result_std_fathers_qualification" value="<?php echo esc_attr($fathers_qualification); ?>" placeholder="<?php esc_attr_e('Enter qualification', 'edu-results'); ?>" />
                        </div>
                    </div>

                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_mother_name">
                                <?php esc_html_e("Mother's Name", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_mother_name" name="cbedu_result_std_mother_name" value="<?php echo esc_attr($mother_name); ?>" placeholder="<?php esc_attr_e("Enter mother's name", 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_mothers_occupation">
                                <?php esc_html_e("Mother's Occupation", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_mothers_occupation" name="cbedu_result_std_mothers_occupation" value="<?php echo esc_attr($mothers_occupation); ?>" placeholder="<?php esc_attr_e('Enter occupation', 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_mothers_qualification">
                                <?php esc_html_e("Mother's Qualification", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_mothers_qualification" name="cbedu_result_std_mothers_qualification" value="<?php echo esc_attr($mothers_qualification); ?>" placeholder="<?php esc_attr_e('Enter qualification', 'edu-results'); ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Government Documents Section -->
            <div class="cbedu-field-section">
                <h3 class="cbedu-section-header"><?php esc_html_e('Government Documents', 'edu-results'); ?></h3>
                <div class="cbedu-section-content">
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_birth_registration_number">
                                <?php esc_html_e('Birth Registration Number', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_birth_registration_number" name="cbedu_result_std_birth_registration_number" value="<?php echo esc_attr($birth_registration_number); ?>" placeholder="<?php esc_attr_e('Enter birth registration number', 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_nid_number">
                                <?php esc_html_e('NID Number', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_nid_number" name="cbedu_result_std_nid_number" value="<?php echo esc_attr($nid_number); ?>" placeholder="<?php esc_attr_e('Enter NID number', 'edu-results'); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Save the custom field values when the post is saved.
     *
     * @param int $post_id The post ID.
     */
    public function save_student_fields($post_id)
    {
        // Save the custom field values when the post is saved
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Skip auto-draft, trash, and inherit posts
        $post_status = get_post_status($post_id);
        if (in_array($post_status, array('auto-draft', 'trash', 'inherit'))) {
            return;
        }

        // Check post type
        if (get_post_type($post_id) !== 'cbedu_students') {
            return;
        }

        // Update ID number
        if (isset($_POST['cbedu_result_std_id'])) {
            update_post_meta($post_id, 'cbedu_result_std_id', sanitize_text_field($_POST['cbedu_result_std_id']));
        }

        // Update Registration Number
        if (isset($_POST['cbedu_result_std_registration_number'])) {
            update_post_meta($post_id, 'cbedu_result_std_registration_number', sanitize_text_field($_POST['cbedu_result_std_registration_number']));
        }

        // Update Father's Name
        if (isset($_POST['cbedu_result_std_father_name'])) {
            update_post_meta($post_id, 'cbedu_result_std_father_name', sanitize_text_field($_POST['cbedu_result_std_father_name']));
        }

        // Update Mother's Name
        if (isset($_POST['cbedu_result_std_mother_name'])) {
            update_post_meta($post_id, 'cbedu_result_std_mother_name', sanitize_text_field($_POST['cbedu_result_std_mother_name']));
        }

        // Update Date of Birth (DOB)
        if (isset($_POST['cbedu_result_std_dob'])) {
            update_post_meta($post_id, 'cbedu_result_std_dob', sanitize_text_field($_POST['cbedu_result_std_dob']));
        }
        // Update Gender
        if (isset($_POST['cbedu_result_std_gender'])) {
            update_post_meta($post_id, 'cbedu_result_std_gender', sanitize_text_field($_POST['cbedu_result_std_gender']));
        }

        // Update Phone Number
        if (isset($_POST['cbedu_result_std_phone'])) {
            update_post_meta($post_id, 'cbedu_result_std_phone', sanitize_text_field($_POST['cbedu_result_std_phone']));
        }

        // Update Email Address
        if (isset($_POST['cbedu_result_std_email'])) {
            update_post_meta($post_id, 'cbedu_result_std_email', sanitize_email($_POST['cbedu_result_std_email']));
        }

        // Update Blood Group
        if (isset($_POST['cbedu_result_std_blood_group'])) {
            update_post_meta($post_id, 'cbedu_result_std_blood_group', sanitize_text_field($_POST['cbedu_result_std_blood_group']));
        }     

        // Update Guardian Phone Number
        if (isset($_POST['cbedu_result_std_guardian_phone'])) {
            update_post_meta($post_id, 'cbedu_result_std_guardian_phone', sanitize_text_field($_POST['cbedu_result_std_guardian_phone']));
        }

        // Update Father's Qualification
        if (isset($_POST['cbedu_result_std_fathers_qualification'])) {
            update_post_meta($post_id, 'cbedu_result_std_fathers_qualification', sanitize_text_field($_POST['cbedu_result_std_fathers_qualification']));
        }

        // Update Father's Occupation
        if (isset($_POST['cbedu_result_std_fathers_occupation'])) {
            update_post_meta($post_id, 'cbedu_result_std_fathers_occupation', sanitize_text_field($_POST['cbedu_result_std_fathers_occupation']));
        }

        // Update Mother's Occupation
        if (isset($_POST['cbedu_result_std_mothers_occupation'])) {
            update_post_meta($post_id, 'cbedu_result_std_mothers_occupation', sanitize_text_field($_POST['cbedu_result_std_mothers_occupation']));
        }

        // Update Mother's Qualification
        if (isset($_POST['cbedu_result_std_mothers_qualification'])) {
            update_post_meta($post_id, 'cbedu_result_std_mothers_qualification', sanitize_text_field($_POST['cbedu_result_std_mothers_qualification']));
        }

        // Update Birth Registration Number
        if (isset($_POST['cbedu_result_std_birth_registration_number'])) {
            update_post_meta($post_id, 'cbedu_result_std_birth_registration_number', sanitize_text_field($_POST['cbedu_result_std_birth_registration_number']));
        }

        // Update NID Number
        if (isset($_POST['cbedu_result_std_nid_number'])) {
            update_post_meta($post_id, 'cbedu_result_std_nid_number', sanitize_text_field($_POST['cbedu_result_std_nid_number']));
        }

        // Update Address
        if (isset($_POST['cbedu_result_std_address'])) {
            update_post_meta($post_id, 'cbedu_result_std_address', sanitize_textarea_field($_POST['cbedu_result_std_address']));
        }   
    }

    // New methods for subject fields
    public function register_subject_fields()
    {
        add_action('add_meta_boxes', array($this, 'add_subject_fields_meta_box'));
        add_action('save_post', array($this, 'save_subject_fields'));
    }

    /**
     * Adds a meta box for subject fields.
     *
     * @return void
     */
    public function add_subject_fields_meta_box()
    {
        add_meta_box(
            'subject_fields',
            'Subject Fields',
            array($this, 'render_subject_fields_meta_box'),
            'cbedu_subjects', // Assuming 'cbedu_subjects' is the custom post type slug for Subjects
            'normal',
            'default'
        );
    }

    /**
     * Renders the subject fields meta box for a given post.
     *
     * @param object $post The post object.
     * @throws None
     * @return void
     */
    public function render_subject_fields_meta_box($post)
    {
        // Retrieve existing value for subject code
        $subject_code = get_post_meta($post->ID, 'cbedu_subject_code', true);

        // Output HTML input for subject code
    ?>
        <div class="cbedu-meta-box-wrap">
            <div class="cbedu-field-section">
                <h3 class="cbedu-section-header"><?php esc_html_e('Subject Information', 'edu-results'); ?></h3>
                <div class="cbedu-section-content">
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_subject_code">
                                <?php esc_html_e('Subject Code', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_subject_code" name="cbedu_subject_code" value="<?php echo esc_attr($subject_code); ?>" placeholder="<?php esc_attr_e('e.g., MATH101', 'edu-results'); ?>" />
                            <p class="cbedu-field-description"><?php esc_html_e('Enter a unique code for this subject', 'edu-results'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Save the subject fields for a given post.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save_subject_fields($post_id)
    {
        // Save the custom field value when the post is saved
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Skip auto-draft, trash, and inherit posts
        $post_status = get_post_status($post_id);
        if (in_array($post_status, array('auto-draft', 'trash', 'inherit'))) {
            return;
        }

        // Check post type
        if (get_post_type($post_id) !== 'cbedu_subjects') {
            return;
        }

        // Check if Subject Code is set and sanitize it
        if (isset($_POST['cbedu_subject_code'])) {
            $sanitized_subject_code = sanitize_text_field($_POST['cbedu_subject_code']);
            update_post_meta($post_id, 'cbedu_subject_code', $sanitized_subject_code);
        }
    }


    /**
     * Registers the result fields.
     *
     * This function adds action hooks to register the result fields meta box and
     * save the result fields. It also adds a save action to update the title
     * when the post is saved.
     *
     * @throws Some_Exception_Class description of exception
     */
    public function register_result_fields()
    {
        add_action('add_meta_boxes', array($this, 'add_result_fields_meta_box'));

        add_action('save_post', array($this, 'save_result_fields'));
        // Add a save action to update the title when the post is saved
        add_action('save_post', array($this, 'update_cbedu_results_title_on_save'));
    }

    /**
     * Generates the meta box for adding result fields.
     *
     * @throws Some_Exception_Class description of exception
     * @return void
     */
    public function add_result_fields_meta_box()
    {
        add_meta_box(
            'result_fields',
            'Result Fields',
            array($this, 'render_result_fields_meta_box'),
            'cbedu_results', // custom post type slug for Results
            'normal',
            'default'
        );
    }

    /**
     * Render the result fields meta box.
     *
     * @param $post The current post object.
     * @return void
     */
    public function render_result_fields_meta_box($post)
    {

        // Generate a nonce
        $nonce = wp_create_nonce('cbedu_register_number_nonce');
        // Get current value of the selected registration number
        $current_reg_number = get_post_meta($post->ID, 'cbedu_result_std_registration_number', true);
        $roll = get_post_meta($post->ID, 'cbedu_result_std_roll', true);
        // Fetch student's details based on the current registration number
        $student_details = $this->get_student_details_by_registration_number($current_reg_number);
        $student_name = $student_details['studentName'];
        $fathers_name = $student_details['fathersName'];
        $mothers_name = $student_details['mothersName'];
        $student_type = get_post_meta($post->ID, 'cbedu_result_std_student_type', true);
        $result_status = get_post_meta($post->ID, 'cbedu_result_std_result_status', true);
        $gpa = get_post_meta($post->ID, 'cbedu_result_std_gpa', true);
        $was_gpa = get_post_meta($post->ID, 'cbedu_result_std_was_gpa', true);

    ?>
        <div class="cbedu-meta-box-wrap">
            <!-- Student Selection Section -->
            <div class="cbedu-field-section">
                <h3 class="cbedu-section-header"><?php esc_html_e('Student Selection', 'edu-results'); ?></h3>
                <div class="cbedu-section-content">
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_registration_number">
                                <?php esc_html_e('Registration Number', 'edu-results'); ?>
                                <span class="required">*</span>
                            </label>
                            <input class="cbedu-field-input cbedu-registration-number-autocomplete" type="text" id="cbedu_result_std_registration_number" name="cbedu_result_std_registration_number" value="<?php echo esc_attr($current_reg_number); ?>" placeholder="<?php esc_attr_e('Start typing...', 'edu-results'); ?>" />
                            <p class="cbedu-field-description"><?php esc_html_e('Type to search and select student registration number', 'edu-results'); ?></p>
                        </div>
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_roll">
                                <?php esc_html_e('Roll Number', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_roll" name="cbedu_result_std_roll" value="<?php echo esc_attr($roll); ?>" placeholder="<?php esc_attr_e('Enter roll number', 'edu-results'); ?>" />
                        </div>
                    </div>
                    
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_name">
                                <?php esc_html_e('Student Name', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_name" name="cbedu_result_std_name" value="<?php echo esc_attr($student_name); ?>" readonly style="background-color: #f0f0f1; cursor: not-allowed;" />
                        </div>
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_fathers_name">
                                <?php esc_html_e("Father's Name", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_fathers_name" name="cbedu_result_std_fathers_name" value="<?php echo esc_attr($fathers_name); ?>" readonly style="background-color: #f0f0f1; cursor: not-allowed;" />
                        </div>
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_mothers_name">
                                <?php esc_html_e("Mother's Name", 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_mothers_name" name="cbedu_result_std_mothers_name" value="<?php echo esc_attr($mothers_name); ?>" readonly style="background-color: #f0f0f1; cursor: not-allowed;" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Details Section -->
            <div class="cbedu-field-section">
                <h3 class="cbedu-section-header"><?php esc_html_e('Result Details', 'edu-results'); ?></h3>
                <div class="cbedu-section-content">
                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_student_type">
                                <?php esc_html_e('Student Type', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_student_type" name="cbedu_result_std_student_type" value="<?php echo esc_attr($student_type); ?>" placeholder="<?php esc_attr_e('e.g., Regular, Private', 'edu-results'); ?>" />
                        </div>
                        
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label">
                                <?php esc_html_e('Result Status', 'edu-results'); ?>
                            </label>
                            <div style="display: flex; gap: 20px; align-items: center; margin-top: 8px;">
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-weight: normal;">
                                    <input type="radio" id="cbedu_result_std_result_status_passed" name="cbedu_result_std_result_status" value="Passed" <?php checked($result_status, 'Passed'); ?> />
                                    <?php esc_html_e('Passed', 'edu-results'); ?>
                                </label>
                                <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-weight: normal;">
                                    <input type="radio" id="cbedu_result_std_result_status_failed" name="cbedu_result_std_result_status" value="Failed" <?php checked($result_status, 'Failed'); ?> />
                                    <?php esc_html_e('Failed', 'edu-results'); ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="cbedu-field-row">
                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_gpa">
                                <?php esc_html_e('GPA', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_gpa" name="cbedu_result_std_gpa" value="<?php echo esc_attr($gpa); ?>" placeholder="<?php esc_attr_e('e.g., 4.50', 'edu-results'); ?>" />
                        </div>

                        <div class="cbedu-field-group">
                            <label class="cbedu-field-label" for="cbedu_result_std_was_gpa">
                                <?php esc_html_e('GPA (WAS)', 'edu-results'); ?>
                            </label>
                            <input class="cbedu-field-input" type="text" id="cbedu_result_std_was_gpa" name="cbedu_result_std_was_gpa" value="<?php echo esc_attr($was_gpa); ?>" placeholder="<?php esc_attr_e('e.g., 4.50', 'edu-results'); ?>" />
                            <p class="cbedu-field-description"><?php esc_html_e('GPA Without Additional Subject', 'edu-results'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Renders the registration number dropdown for a given post.
     *
     * @param object $post The post object.
     * @throws Some_Exception_Class If there is an error retrieving the registration number or the students.
    /**
     * Saves the result fields for a given post ID.
     *
     * @param int $post_id The ID of the post.
     */
    public function save_result_fields($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Skip auto-draft, trash, and inherit posts
        $post_status = get_post_status($post_id);
        if (in_array($post_status, array('auto-draft', 'trash', 'inherit'))) {
            return;
        }

        // Check post type
        if (get_post_type($post_id) !== 'cbedu_results') {
            return;
        }

        // Update Roll
        if (isset($_POST['cbedu_result_std_roll'])) {
            update_post_meta($post_id, 'cbedu_result_std_roll', sanitize_text_field($_POST['cbedu_result_std_roll']));
        }

        // Update Student Type
        if (isset($_POST['cbedu_result_std_student_type'])) {
            update_post_meta($post_id, 'cbedu_result_std_student_type', sanitize_text_field($_POST['cbedu_result_std_student_type']));
        }

        // Update Result Status

        if (isset($_POST['cbedu_result_std_result_status'])) {
            $sanitized_result_status = sanitize_text_field($_POST['cbedu_result_std_result_status']);
            update_post_meta($post_id, 'cbedu_result_std_result_status', $sanitized_result_status);
        }

        // Update GPA
        if (isset($_POST['cbedu_result_std_gpa'])) {
            update_post_meta($post_id, 'cbedu_result_std_gpa', sanitize_text_field($_POST['cbedu_result_std_gpa']));
        }

        // Update Was GPA
        if (isset($_POST['cbedu_result_std_was_gpa'])) {
            update_post_meta($post_id, 'cbedu_result_std_was_gpa', sanitize_text_field($_POST['cbedu_result_std_was_gpa']));
        }

        // Save Registration Number from dropdown
        if (isset($_POST['cbedu_result_std_registration_number'])) {
            update_post_meta($post_id, 'cbedu_result_std_registration_number', sanitize_text_field($_POST['cbedu_result_std_registration_number']));
        }
    }


    /**
     * Retrieves the details of a student based on their registration number.
     *
     * @param string $registration_number The registration number of the student.
     * @return array An array containing the student's name and father's name.
     */
    private function get_student_details_by_registration_number($registration_number)
    {
        if (empty($registration_number)) {
            return array('studentName' => 'Not Found!', 'fathersName' => 'Not Found!', 'mothersName' => 'Not Found!');
        }

        $args = array(
            'post_type' => 'cbedu_students',
            'meta_key' => 'cbedu_result_std_registration_number',
            'meta_value' => $registration_number,
            'posts_per_page' => 1
        );

        $students = get_posts($args);
        if (!empty($students)) {
            $student_name = !empty($students[0]->post_title) ? $students[0]->post_title : 'Not Found!';
            $father_name = get_post_meta($students[0]->ID, 'cbedu_result_std_father_name', true);
            $father_name = !empty($father_name) ? $father_name : 'Not Found!';

            // Fetch the mother's name
            $mother_name = get_post_meta($students[0]->ID, 'cbedu_result_std_mother_name', true);
            $mother_name = !empty($mother_name) ? $mother_name : 'Not Found!';

            return array(
                'studentName' => $student_name,
                'fathersName' => $father_name,
                'mothersName' => $mother_name // Added mother's name
            );
        }

        return array('studentName' => 'Not Found!', 'fathersName' => 'Not Found!', 'mothersName' => 'Not Found!');
    }


    /**
     * Updates the title of the 'cbedu_results' post based on the registration number.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function update_cbedu_results_title_on_save($post_id)
    {

        // Check for autosave, permissions, and post type
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (get_post_type($post_id) !== 'cbedu_results') return;

        // Skip auto-draft, trash, and inherit posts
        $post_status = get_post_status($post_id);
        if (in_array($post_status, array('auto-draft', 'trash', 'inherit'))) return;

        // Get the registration number from the 'cbedu_results' post meta
        $registration_number = get_post_meta($post_id, 'cbedu_result_std_registration_number', true);

        // Skip if no registration number is set
        if (empty($registration_number)) return;

        // Find the 'cbedu_students' post with this registration number
        $student_posts = get_posts(array(
            'post_type' => 'cbedu_students',
            'meta_key' => 'cbedu_result_std_registration_number', // Adjust if needed
            'meta_value' => $registration_number,
            'posts_per_page' => 1
        ));


        if (!empty($student_posts)) {
            $student_post_title = $student_posts[0]->post_title;


            // Check if the title is different from the current title of 'cbedu_results' post
            if (get_the_title($post_id) !== $student_post_title) {
                // Unhook to prevent infinite loop
                remove_action('save_post', array($this, 'update_cbedu_results_title_on_save'));
                
                // Update the title of 'cbedu_results' post                
                wp_update_post(array(
                    'ID'         => $post_id,
                    'post_title' => $student_post_title
                ));
                
                // Re-hook the action
                add_action('save_post', array($this, 'update_cbedu_results_title_on_save'));
            }
        }
    }

    /**
     * Checks if the registration number of a student is unique before saving the post.
     *
     * @param int $post_id The ID of the post being saved.
     *
     * @return void
     */
    public function cbedu_check_unique_registration_number($post_id)
    {
        // Check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        // Skip auto-draft, trash, and inherit posts
        $post_status = get_post_status($post_id);
        if (in_array($post_status, array('auto-draft', 'trash', 'inherit'))) return;

        // Check the post type
        if (get_post_type($post_id) !== 'cbedu_students') return;

        // Verify the nonce before proceeding.
        if (
            !isset($_POST['cbedu_save_student_nonce']) ||
            !wp_verify_nonce($_POST['cbedu_save_student_nonce'], 'cbedu_save_student_nonce_action')
        ) {
            return;
        }

        // Get the registration number from the submitted form
        if (isset($_POST['cbedu_result_std_registration_number'])) {
            $registration_number = sanitize_text_field($_POST['cbedu_result_std_registration_number']);

            // Search for posts with the same registration number
            $query = new \WP_Query(array(
                'post_type' => 'cbedu_students',
                'meta_key' => 'cbedu_result_std_registration_number',
                'meta_value' => $registration_number,
                'post__not_in' => array($post_id), // Exclude the current post
                'posts_per_page' => 1
            ));

            if ($query->found_posts > 0) {
                // Set a custom error flag in the post meta.
                update_post_meta($post_id, '_cbedu_registration_error', '1');

                // Unhook this function to prevent infinite loop
                remove_action('save_post', array($this, 'cbedu_check_unique_registration_number'));

                // Update the post to revert the title change
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_status' => 'draft' // Optional: Change the post status
                ));

                // Re-hook the function
                add_action('save_post', array($this, 'cbedu_check_unique_registration_number'));

                // Show error message
                add_filter('redirect_post_location', array($this, 'cbedu_modify_redirect_location'), 10, 2);
            }
        }
    }

    /**
     * Displays an error notice if the 'cbedu_registration_error' query parameter is set.
     *
     * @return void
     */
    public function cbedu_admin_notices()
    {
        if (!isset($_GET['cbedu_registration_error'])) return;
    ?>
        <div class="notice notice-error">
            <p><?php _e('Error: Registration number must be unique.', 'edu-results'); ?></p>
        </div>
<?php
    }

    /**
     * Modifies the redirect location.
     *
     * @param mixed $location The original redirect location.
     * @param int $post_id The ID of the post.
     * @return mixed The modified redirect location.
     */
    public function cbedu_modify_redirect_location($location, $post_id)
    {
        // Check if our custom query var is set to show the error message.
        if (get_post_meta($post_id, '_cbedu_registration_error', true)) {
            // Remove the default message query arg if your error is set.
            $location = remove_query_arg('message', $location);
            // Add your custom error message.
            $location = add_query_arg('cbedu_registration_error', '1', $location);
            // Delete the error meta to prevent the error from persisting on refresh.
            delete_post_meta($post_id, '_cbedu_registration_error');
        }
        return $location;
    }


    public function add_search_registration_numbers()
    {
        // Verify the nonce
        check_ajax_referer('cbedu_auto_complete_nonce', 'AutoNonce');

        $term = sanitize_text_field($_POST['term']);

        $args = array(
            'post_type' => 'cbedu_students',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'cbedu_result_std_registration_number',
                    'value' => $term,
                    'compare' => 'LIKE'
                )
            )
        );

        $query = new \WP_Query($args);
        $results = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $registration_number = get_post_meta(get_the_ID(), 'cbedu_result_std_registration_number', true);
                $results[] = array('label' => get_the_title(), 'value' => $registration_number);
            }
        }

        wp_reset_postdata();
        wp_send_json($results);
        wp_die();
    }
}
