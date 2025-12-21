<?php

namespace cbedu\inc\RepeaterCF;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
class CBEDURepeaterCustomFields
{

    public function __construct()
    {

        add_action('add_meta_boxes', array($this, 'addMetaBox'));
        add_action('save_post', array($this, 'saveMetaBoxData'));
    }

    public function addMetaBox()
    {
        add_meta_box('cbedu_results_repeater', __('Subjects Information', 'edu-results'), array($this, 'renderMetaBox'), 'cbedu_results', 'normal', 'default');
    }

    public function renderMetaBox($post) {
        // Fetch subjects from 'subjects' custom post type
        $subjects = get_posts(array(
            'post_type' => 'cbedu_subjects',
            'posts_per_page' => -1
        ));

        $eduResultsGroup = get_post_meta($post->ID, 'cbedu_subjects_results', true);
        wp_nonce_field('cbedu_results_repeatable_meta_box_nonce', 'cbedu_results_repeatable_meta_box_nonce');
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#edu-add-subject-row').on('click', function() {
                    var row = $('.empty-row.screen-reader-text').clone(true);
                    row.removeClass('empty-row screen-reader-text');
                    row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
                    return false;
                });

                $('.remove-row').on('click', function() {
                    $(this).parents('tr').remove();
                    return false;
                });
            });
        </script>

        <div class="cbedu-subjects-repeater-wrapper">
            <div class="cbedu-repeater-header">
                <h3><?php esc_html_e('Add Subject Marks', 'edu-results'); ?></h3>
                <p class="description"><?php esc_html_e('Select subjects and enter marks for each subject', 'edu-results'); ?></p>
            </div>

            <table id="repeatable-fieldset-one" class="cbedu-repeater-table">
                <thead>
                    <tr>
                        <th class="cbedu-repeater-th-subject"><?php esc_html_e('Subject', 'edu-results'); ?></th>
                        <th class="cbedu-repeater-th-marks"><?php esc_html_e('Marks / Grade', 'edu-results'); ?></th>
                        <th class="cbedu-repeater-th-action"><?php esc_html_e('Action', 'edu-results'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($eduResultsGroup) :
                        foreach ($eduResultsGroup as $field) :
                    ?>
                            <tr class="cbedu-repeater-row">
                                <td class="cbedu-repeater-td-subject">
                                    <select class="cbedu-repeater-select" name="cbedu_results_subject_name[]">
                                        <option value=""><?php esc_attr_e('Select Subject', 'edu-results'); ?></option>
                                        <?php foreach ($subjects as $subject) : 
                                            $subject_code = get_post_meta($subject->ID, 'cbedu_subject_code', true); 
                                        ?>
                                        <option value="<?php echo esc_attr($subject->post_title); ?>" <?php selected($field['subject_name'], $subject->post_title); ?>>
                                            <?php echo esc_html($subject_code ? $subject_code . ' - ' : '') . esc_html($subject->post_title); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="cbedu-repeater-td-input">
                                    <input class="cbedu-repeater-input" type="text" placeholder="<?php esc_attr_e('Enter marks or grade', 'edu-results'); ?>" name="cbedu_results_subject_value[]" value="<?php echo isset($field['subject_value']) ? esc_attr($field['subject_value']) : ''; ?>" />
                                </td>
                                <td class="cbedu-repeater-td-action">
                                    <button type="button" class="button remove-row cbedu-remove-btn">
                                        <span class="dashicons dashicons-trash"></span>
                                        <?php esc_html_e('Remove', 'edu-results'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else :
                        ?>
                        <tr class="cbedu-repeater-row">
                            <td class="cbedu-repeater-td-subject">
                                <select class="cbedu-repeater-select" name="cbedu_results_subject_name[]">
                                    <option value=""><?php esc_attr_e('Select Subject', 'edu-results'); ?></option>
                                    <?php foreach ($subjects as $subject) : 
                                        $subject_code = get_post_meta($subject->ID, 'cbedu_subject_code', true);
                                    ?>
                                        <option value="<?php echo esc_attr($subject->post_title); ?>">
                                            <?php echo esc_html($subject_code ? $subject_code . ' - ' : '') . esc_html($subject->post_title); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="cbedu-repeater-td-input">
                                <input class="cbedu-repeater-input" type="text" placeholder="<?php esc_attr_e('Enter marks or grade', 'edu-results'); ?>" name="cbedu_results_subject_value[]" />
                            </td>
                            <td class="cbedu-repeater-td-action">
                                <button type="button" class="button remove-row cbedu-remove-btn">
                                    <span class="dashicons dashicons-trash"></span>
                                    <?php esc_html_e('Remove', 'edu-results'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <!-- Empty hidden row for jQuery -->
                    <tr class="empty-row screen-reader-text cbedu-repeater-row">
                        <td class="cbedu-repeater-td-subject">
                            <select class="cbedu-repeater-select" name="cbedu_results_subject_name[]">
                                <option value=""><?php esc_attr_e('Select Subject', 'edu-results'); ?></option>
                                <?php foreach ($subjects as $subject) : 
                                    $subject_code = get_post_meta($subject->ID, 'cbedu_subject_code', true);
                                ?>
                                    <option value="<?php echo esc_attr($subject->post_title); ?>">
                                        <?php echo esc_html($subject_code ? $subject_code . ' - ' : '') . esc_html($subject->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="cbedu-repeater-td-input">
                            <input class="cbedu-repeater-input" type="text" placeholder="<?php esc_attr_e('Enter marks or grade', 'edu-results'); ?>" name="cbedu_results_subject_value[]" />
                        </td>
                        <td class="cbedu-repeater-td-action">
                            <button type="button" class="button remove-row cbedu-remove-btn">
                                <span class="dashicons dashicons-trash"></span>
                                <?php esc_html_e('Remove', 'edu-results'); ?>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="cbedu-repeater-footer">
                <button type="button" id="edu-add-subject-row" class="button button-primary cbedu-add-subject-btn">
                    <span class="dashicons dashicons-plus-alt"></span>
                    <?php esc_html_e('Add Another Subject', 'edu-results'); ?>
                </button>
            </div>
        </div>
    <?php
    }

    public function saveMetaBoxData($postID)
    {
        if (
            !isset($_POST['cbedu_results_repeatable_meta_box_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cbedu_results_repeatable_meta_box_nonce'])), 'cbedu_results_repeatable_meta_box_nonce')
        )
            return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        // Skip auto-draft, trash, and inherit posts
        $post_status = get_post_status($postID);
        if (in_array($post_status, array('auto-draft', 'trash', 'inherit')))
            return;

        if (!current_user_can('edit_post', $postID))
            return;

        // Check post type
        if (get_post_type($postID) !== 'cbedu_results')
            return;

        $old = get_post_meta($postID, 'cbedu_subjects_results', true);
        $new = array();
        $subjectNames = isset($_POST['cbedu_results_subject_name']) ? $_POST['cbedu_results_subject_name'] : array();
        $subjectValues = isset($_POST['cbedu_results_subject_value']) ? $_POST['cbedu_results_subject_value'] : array();

        $esc_subjectNames = array_map('sanitize_text_field', wp_unslash($subjectNames));
        $esc_subjectValues = array_map('sanitize_text_field', wp_unslash($subjectValues));

        $count = count($esc_subjectNames);

        for ($i = 0; $i < $count; $i++) {
            if ($esc_subjectNames[$i] != '') :
                $new[$i]['subject_name'] = stripslashes(strip_tags($esc_subjectNames[$i]));
                $new[$i]['subject_value'] = stripslashes(strip_tags($esc_subjectValues[$i]));
            endif;
        }

        if (!empty($new) && $new != $old)
            update_post_meta($postID, 'cbedu_subjects_results', $new);
        elseif (empty($new) && $old)
            delete_post_meta($postID, 'cbedu_subjects_results', $old);
    }
}
