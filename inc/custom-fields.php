<?php
namespace cbedu\inc\custom_fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class CBEDUCustomFields
{

    public function __construct()
    {

        // Call the register_student_fields() method during class initialization
        $this->register_student_fields();

        $this->register_subject_fields();
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
            'cbedu_results',
            //  custom post type slug
            'normal',
            'default'
        );
    }

    public function render_student_fields_meta_box($post)
    {
        // Retrieve existing values for custom fields
        $id_number = get_post_meta($post->ID, 'cbedu_result_std_id', true);
        $name = get_post_meta($post->ID, 'cbedu_result_std_name', true);
        $roll = get_post_meta($post->ID, 'cbedu_result_std_roll', true);
        $std_registration_number = get_post_meta($post->ID, 'cbedu_result_std_registration_number', true);
        $board = get_post_meta($post->ID, 'cbedu_result_std_board', true);
        $father_name = get_post_meta($post->ID, 'cbedu_result_std_father_name', true);
        $group = get_post_meta($post->ID, 'cbedu_result_std_group', true);
        $mother_name = get_post_meta($post->ID, 'cbedu_result_std_mother_name', true);
        $student_type = get_post_meta($post->ID, 'cbedu_result_std_student_type', true);
        $result_status = get_post_meta($post->ID, 'cbedu_result_std_result_status', true);
        $gpa = get_post_meta($post->ID, 'cbedu_result_std_gpa', true);
        $was_gpa = get_post_meta($post->ID, 'cbedu_result_std_was_gpa', true);
        $dob = get_post_meta($post->ID, 'cbedu_result_std_dob', true);

        // Output HTML inputs for each field
        ?>
        <table>
            <tr>
                <td>
                    <label for="cbedu_result_std_id">ID Number:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_id"
                        name="cbedu_result_std_id" value="<?php echo esc_attr($id_number); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_name">Name:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_name"
                        name="cbedu_result_std_name" value="<?php echo esc_attr($name); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_roll">Roll:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_roll"
                        name="cbedu_result_std_roll" value="<?php echo esc_attr($roll); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_registration_number">Registration Number:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_registration_number"
                        name="cbedu_result_std_registration_number" value="<?php echo esc_attr($std_registration_number); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_board">Board:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_board"
                        name="cbedu_result_std_board" value="<?php echo esc_attr($board); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_father_name">Father's Name:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_father_name"
                        name="cbedu_result_std_father_name" value="<?php echo esc_attr($father_name); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_group">Group:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_group"
                        name="cbedu_result_std_group" value="<?php echo esc_attr($group); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_mother_name">Mother's Name:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_mother_name"
                        name="cbedu_result_std_mother_name" value="<?php echo esc_attr($mother_name); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_student_type">Student Type:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_student_type"
                        name="cbedu_result_std_student_type" value="<?php echo esc_attr($student_type); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_result_status">Result Status:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_result_status"
                        name="cbedu_result_std_result_status" value="<?php echo esc_attr($result_status); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_gpa">GPA:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_gpa"
                        name="cbedu_result_std_gpa" value="<?php echo esc_attr($gpa); ?>" />
                </td>
            </tr>           
            <tr>
                <td>
                    <label for="cbedu_result_std_was_gpa">GPA <abbr title="Without additional subject">(WAS)</abbr>:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="text" id="cbedu_result_std_was_gpa"
                        name="cbedu_result_std_was_gpa" value="<?php echo esc_attr($was_gpa); ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cbedu_result_std_dob">Date of Birth:</label>
                </td>
                <td>
                    <input class="regular-text" style="padding: 7px 10px;" type="date" id="cbedu_result_std_dob"
                        name="cbedu_result_std_dob" value="<?php echo esc_attr($dob); ?>" />
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

        // Update name
        if (isset($_POST['cbedu_result_std_name'])) {
            update_post_meta($post_id, 'cbedu_result_std_name', sanitize_text_field($_POST['cbedu_result_std_name']));
        }

        // Update roll
        if (isset($_POST['cbedu_result_std_roll'])) {
            update_post_meta($post_id, 'cbedu_result_std_roll', sanitize_text_field($_POST['cbedu_result_std_roll']));
        }

        // Update Registration Number
        if (isset($_POST['cbedu_result_std_registration_number'])) {
            update_post_meta($post_id, 'cbedu_result_std_registration_number', sanitize_text_field($_POST['cbedu_result_std_registration_number']));
        }

        // Update Board
        if (isset($_POST['cbedu_result_std_board'])) {
            update_post_meta($post_id, 'cbedu_result_std_board', sanitize_text_field($_POST['cbedu_result_std_board']));
        }

        // Update Father's Name
        if (isset($_POST['cbedu_result_std_father_name'])) {
            update_post_meta($post_id, 'cbedu_result_std_father_name', sanitize_text_field($_POST['cbedu_result_std_father_name']));
        }

        // Update Group
        if (isset($_POST['cbedu_result_std_group'])) {
            update_post_meta($post_id, 'cbedu_result_std_group', sanitize_text_field($_POST['cbedu_result_std_group']));
        }

        // Update Mother's Name
        if (isset($_POST['cbedu_result_std_mother_name'])) {
            update_post_meta($post_id, 'cbedu_result_std_mother_name', sanitize_text_field($_POST['cbedu_result_std_mother_name']));
        }

        // Update Student Type
        if (isset($_POST['cbedu_result_std_student_type'])) {
            update_post_meta($post_id, 'cbedu_result_std_student_type', sanitize_text_field($_POST['cbedu_result_std_student_type']));
        }

        // Update Result Status
        if (isset($_POST['cbedu_result_std_result_status'])) {
            update_post_meta($post_id, 'cbedu_result_std_result_status', sanitize_text_field($_POST['cbedu_result_std_result_status']));
        }

        // Update GPA
        if (isset($_POST['cbedu_result_std_gpa'])) {
            update_post_meta($post_id, 'cbedu_result_std_gpa', sanitize_text_field($_POST['cbedu_result_std_gpa']));
        }

        // Update Was GPA
        if (isset($_POST['cbedu_result_std_was_gpa'])) {
            update_post_meta($post_id, 'cbedu_result_std_was_gpa', sanitize_text_field($_POST['cbedu_result_std_was_gpa']));
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
                    <input class="regular-text" type="text" id="cbedu_subject_code"
                        name="cbedu_subject_code" value="<?php echo esc_attr($subject_code); ?>" />
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
}