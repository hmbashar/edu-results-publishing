<?php

namespace cbedu\inc\custom_fields;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
class CBEDUCustomFields
{

    public function __construct()
    {

        // Call the register_student_fields() method during class initialization
        $this->register_student_fields();

        $this->register_subject_fields();

        $this->register_result_fields(); // Call the register_result_fields() method
    }

    public function register_student_fields()
    {
        add_action('add_meta_boxes', array($this, 'add_student_fields_meta_box'));
        add_action('save_post', array($this, 'save_student_fields'));
    }

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

    public function render_student_fields_meta_box($post)
    {
        // Retrieve existing values for custom fields
        $id_number = get_post_meta($post->ID, 'cbedu_result_std_id', true);
        $roll = get_post_meta($post->ID, 'cbedu_result_std_roll', true);
        $std_registration_number = get_post_meta($post->ID, 'cbedu_result_std_registration_number', true);
        $father_name = get_post_meta($post->ID, 'cbedu_result_std_father_name', true);
        $mother_name = get_post_meta($post->ID, 'cbedu_result_std_mother_name', true);
        $dob = get_post_meta($post->ID, 'cbedu_result_std_dob', true);

        // Output HTML inputs for each field
?>
        <table>
            <tr>
                <td>
                    <label for="cbedu_result_std_id">ID Number:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_id" name="cbedu_result_std_id" value="<?php echo esc_attr($id_number); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_roll">Roll:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_roll" name="cbedu_result_std_roll" value="<?php echo esc_attr($roll); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_registration_number">Registration Number:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_registration_number" name="cbedu_result_std_registration_number" value="<?php echo esc_attr($std_registration_number); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_father_name">Father's Name:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_father_name" name="cbedu_result_std_father_name" value="<?php echo esc_attr($father_name); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_mother_name">Mother's Name:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_mother_name" name="cbedu_result_std_mother_name" value="<?php echo esc_attr($mother_name); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_dob">Date of Birth:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="date" id="cbedu_result_std_dob" name="cbedu_result_std_dob" value="<?php echo esc_attr($dob); ?>" />
                </td>
            </tr>
        </table>
    <?php
    }

    public function save_student_fields($post_id)
    {
        // Save the custom field values when the post is saved
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Update ID number
        if (isset($_POST['cbedu_result_std_id'])) {
            update_post_meta($post_id, 'cbedu_result_std_id', sanitize_text_field($_POST['cbedu_result_std_id']));
        }

        // Update roll
        if (isset($_POST['cbedu_result_std_roll'])) {
            update_post_meta($post_id, 'cbedu_result_std_roll', sanitize_text_field($_POST['cbedu_result_std_roll']));
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
    }

    // New methods for subject fields
    public function register_subject_fields()
    {
        add_action('add_meta_boxes', array($this, 'add_subject_fields_meta_box'));
        add_action('save_post', array($this, 'save_subject_fields'));
    }

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

    public function render_subject_fields_meta_box($post)
    {
        // Retrieve existing value for subject code
        $subject_code = get_post_meta($post->ID, 'cbedu_subject_code', true);

        // Output HTML input for subject code
    ?>
        <table>
            <tr>
                <td>
                    <label for="cbedu_subject_code">Subject Code:</label>
                </td>
                <td>
                    <input class="regular-text" type="text" id="cbedu_subject_code" name="cbedu_subject_code" value="<?php echo esc_attr($subject_code); ?>" />
                </td>
            </tr>
        </table>
    <?php
    }

    public function save_subject_fields($post_id)
    {
        // Save the custom field value when the post is saved
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check if Subject Code is set and sanitize it
        if (isset($_POST['cbedu_subject_code'])) {
            $sanitized_subject_code = sanitize_text_field($_POST['cbedu_subject_code']);
            update_post_meta($post_id, 'cbedu_subject_code', $sanitized_subject_code);
        }
    }


    public function register_result_fields()
    {
        add_action('add_meta_boxes', array($this, 'add_result_fields_meta_box'));
        add_action('save_post', array($this, 'save_result_fields'));
    }

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

    public function render_result_fields_meta_box($post)
    {

        // Generate a nonce
        $nonce = wp_create_nonce('cbedu_register_number_nonce');
        // Get current value of the selected registration number
        $current_reg_number = get_post_meta($post->ID, 'cbedu_result_registration_number', true);
        // Fetch student's name based on the current registration number
        // Fetch student's details based on the current registration number
        $student_details = $this->get_student_details_by_registration_number($current_reg_number);
        $student_name = $student_details['studentName'];
        $fathers_name = $student_details['fathersName'];
        $student_type = get_post_meta($post->ID, 'cbedu_result_std_student_type', true);
        $result_status = get_post_meta($post->ID, 'cbedu_result_std_result_status', true);
        $gpa = get_post_meta($post->ID, 'cbedu_result_std_gpa', true);
        $was_gpa = get_post_meta($post->ID, 'cbedu_result_std_was_gpa', true);

    ?>
        <table>
            <?php


            $this->render_registration_number_dropdown($post);

            echo '<tr><td><label for="cbedu_result_std_name">Student Name:</label></td>';
            echo '<td><input type="text" id="cbedu_result_std_name" name="cbedu_result_std_name" value="' . esc_attr($student_name) . '" readonly /></td></tr>';

            echo '<tr><td><label for="cbedu_result_std_fathers_name">Father\'s Name:</label></td>';
            echo '<td><input type="text" id="cbedu_result_std_fathers_name" name="cbedu_result_std_fathers_name" value="' . esc_attr($fathers_name) . '" readonly /></td></tr>';

            ?>
            <tr>
                <td>
                    <label for="cbedu_result_std_student_type">Student Type:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_student_type" name="cbedu_result_std_student_type" value="<?php echo esc_attr($student_type); ?>" />
                </td>
            </tr>
            <tr style="margin-top: 10px;margin-bottom:10px;">
                <td>
                    <label>Result Status:</label>
                </td>
                <td>
                    <input type="radio" id="cbedu_result_std_result_status_passed" name="cbedu_result_std_result_status" value="Passed" <?php checked($result_status, 'Passed'); ?> />
                    <label for="cbedu_result_std_result_status_passed" style="margin-right: 10px;">Passed</label>

                    <input type="radio" id="cbedu_result_std_result_status_failed" name="cbedu_result_std_result_status" value="Failed" <?php checked($result_status, 'Failed'); ?> />
                    <label for="cbedu_result_std_result_status_failed">Failed</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_gpa">GPA:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_gpa" name="cbedu_result_std_gpa" value="<?php echo esc_attr($gpa); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_was_gpa">GPA <abbr title="Without additional subject">(WAS)</abbr>:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_was_gpa" name="cbedu_result_std_was_gpa" value="<?php echo esc_attr($was_gpa); ?>" />
                </td>
            </tr>
        </table>
    <?php
    }

    private function render_registration_number_dropdown($post)
    {
        // Get current value
        $current_value = get_post_meta($post->ID, 'cbedu_result_registration_number', true);

        // Query all students to get their registration numbers
        $args = array(
            'post_type' => 'cbedu_students',
            'posts_per_page' => -1
        );
        $students = get_posts($args);

        echo '<tr><td><label for="cbedu_result_registration_number">Registration Number:</label></td>';
        echo '<td><select id="cbedu_result_registration_number" name="cbedu_result_registration_number">';
        echo '<option value="">Select a Registration Number</option>';

        foreach ($students as $student) {
            $registration_number = get_post_meta($student->ID, 'cbedu_result_std_registration_number', true);
            $selected = $current_value == $registration_number ? 'selected' : '';
            echo '<option value="' . esc_attr($registration_number) . '" ' . $selected . '>' . esc_html($registration_number) . '</option>';
        }

        echo '</select></td></tr>';
    ?>
<?php
    }

    public function save_result_fields($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
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
        if (isset($_POST['cbedu_result_registration_number'])) {
            update_post_meta($post_id, 'cbedu_result_registration_number', sanitize_text_field($_POST['cbedu_result_registration_number']));
        }


        // Check if the post type is 'cbedu_results' then update the title based on the registration number
        if (get_post_type($post_id) == 'cbedu_results') {
            // Get the registration number from the 'cbedu_results' post meta
            $registration_number = get_post_meta($post_id, 'cbedu_result_registration_number', true);

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
                    // Update the title of 'cbedu_results' post                
                    wp_update_post(array(
                        'ID'         => $post_id,
                        'post_title' => $student_post_title
                    ));
                }
            }
        }
    }


    private function get_student_details_by_registration_number($registration_number)
    {
        if (empty($registration_number)) {
            return array('studentName' => 'Not Found!', 'fathersName' => 'Not Found!');
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

            return array(
                'studentName' => $student_name,
                'fathersName' => $father_name
            );
        }

        return array('studentName' => 'Not Found!', 'fathersName' => 'Not Found!');
    }
}
