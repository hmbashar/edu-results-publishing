<?php

namespace inc\custom_fields;

class EDUCustomFields
{
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
            'edu_results',
            //  custom post type slug
            'normal',
            'default'
        );
    }

    public function render_student_fields_meta_box($post)
    {
        // Render the custom fields HTML inputs here
        // Example: ID number, name, roll
        $id_number = get_post_meta($post->ID, 'edu_results_student_id', true);
        $name = get_post_meta($post->ID, 'edu_results_student_name', true);
        $roll = get_post_meta($post->ID, 'edu_results_student_roll', true);
        // Subject Scores
        $subject_scores = get_post_meta($post->ID, 'edu_results_subject_scores', true);
        $total_score = get_post_meta($post->ID, 'edu_results_total_score', true);

        // Output HTML inputs for each field
        ?>
        <label for="edu_results_student_id">ID Number:</label>
        <input type="text" id="edu_results_student_id" name="edu_results_student_id"
            value="<?php echo esc_attr($id_number); ?>" /><br>

        <label for="edu_results_student_name">Name:</label>
        <input type="text" id="edu_results_student_name" name="edu_results_student_name"
            value="<?php echo esc_attr($name); ?>" /><br>

        <label for="edu_results_student_roll">Roll:</label>
        <input type="text" id="edu_results_student_roll" name="edu_results_student_roll"
            value="<?php echo esc_attr($roll); ?>" /><br>

        <!-- Subject Scores -->
        <label for="edu_results_subject_scores">Subject Scores:</label>
        <input type="text" id="edu_results_subject_scores" name="edu_results_subject_scores"
            value="<?php echo esc_attr($subject_scores); ?>" /><br>

        <!-- Total Score -->
        <label for="edu_results_total_score">Total Score:</label>
        <input type="text" id="edu_results_total_score" name="edu_results_total_score"
            value="<?php echo esc_attr($total_score); ?>" /><br>

        <?php
    }

    public function save_student_fields($post_id)
    {
        // Save the custom field values when the post is saved
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Update ID number
        if (isset($_POST['edu_results_student_id'])) {
            update_post_meta($post_id, 'edu_results_student_id', sanitize_text_field($_POST['edu_results_student_id']));
        }

        // Update name
        if (isset($_POST['edu_results_student_name'])) {
            update_post_meta($post_id, 'edu_results_student_name', sanitize_text_field($_POST['edu_results_student_name']));
        }

        // Update roll
        if (isset($_POST['edu_results_student_roll'])) {
            update_post_meta($post_id, 'edu_results_student_roll', sanitize_text_field($_POST['edu_results_student_roll']));
        }

        // Update Subject Scores
        if (isset($_POST['edu_results_subject_scores'])) {
            update_post_meta($post_id, 'edu_results_subject_scores', sanitize_text_field($_POST['edu_results_subject_scores']));
        }

        // Update Total Score
        if (isset($_POST['edu_results_total_score'])) {
            update_post_meta($post_id, 'edu_results_total_score', sanitize_text_field($_POST['edu_results_total_score']));
        }
    }
}

$custom_fields = new \inc\custom_fields\EDUCustomFields();
$custom_fields->register_student_fields();