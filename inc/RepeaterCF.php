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
            'post_type' => 'cbedu_subjects', // Replace with your actual custom post type slug
            'posts_per_page' => -1 // Fetch all posts
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

        <table id="repeatable-fieldset-one" width="100%">
            <tbody>
                <?php
                if ($eduResultsGroup) :
                    foreach ($eduResultsGroup as $field) :
                ?>
                        <tr>
                            <td width="70%">
                                <select style="width:80%;padding:10px;" name="cbedu_results_subject_name[]">
                                    <option value=""><?php esc_attr_e('Select Subject', 'edu-results'); ?></option>
                                    <?php foreach ($subjects as $subject) : 
                                        $subject_code = get_post_meta($subject->ID, 'cbedu_subject_code', true); 
                                    ?>
                                    <option value="<?php echo esc_attr($subject->post_title); ?>" <?php selected($field['subject_name'], $subject->post_title); ?>>
                                        <?php echo esc_html($subject_code . ' - ' . $subject->post_title); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td width="70%">
                                <input style="width:80%;padding:10px;" type="text" placeholder="<?php esc_attr_e('Enter subject value', 'edu-results'); ?>" name="cbedu_results_subject_value[]" value="<?php echo isset($field['subject_value']) ? esc_attr($field['subject_value']) : ''; ?>" />
                            </td>
                            <td width="15%"><a class="button remove-row" href="#1"><?php esc_html_e('Remove', 'edu-results'); ?></a></td>
                        </tr>
                    <?php
                    endforeach;
                else :
                    // Show a blank row
                    ?>
                    <tr>
                        <td width="70%">
                            <select style="width:80%;padding:10px;" name="cbedu_results_subject_name[]">
                                <option value=""><?php esc_attr_e('Select Subject', 'edu-results'); ?></option>
                                <?php foreach ($subjects as $subject) : ?>
                                    <option value="<?php echo esc_attr($subject->post_title); ?>">
                                        <?php echo esc_html($subject->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="70%">
                            <input style="width:80%;padding:10px;" type="text" placeholder="<?php esc_attr_e('Enter subject value', 'edu-results'); ?>" name="cbedu_results_subject_value[]" />
                        </td>
                        <td width="15%"><a class="button remove-row" href="#1"><?php esc_html_e('Remove', 'edu-results'); ?></a></td>
                    </tr>
                <?php endif; ?>

                <!-- Empty hidden row for jQuery -->
                <tr class="empty-row screen-reader-text">
                    <td width="70%">
                        <select style="width:80%;padding:10px;" name="cbedu_results_subject_name[]">
                            <option value=""><?php esc_attr_e('Select Subject', 'edu-results'); ?></option>
                            <?php foreach ($subjects as $subject) : ?>
                                <option value="<?php echo esc_attr($subject->post_title); ?>">
                                    <?php echo esc_html($subject->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td width="70%">
                        <input style="width:80%;padding:10px;" type="text" placeholder="<?php esc_attr_e('Enter subject value', 'edu-results'); ?>" name="cbedu_results_subject_value[]" />
                    </td>
                    <td width="15%"><a class="button remove-row" href="#1"><?php esc_html_e('Remove', 'edu-results'); ?></a></td>
                </tr>
            </tbody>
        </table>

        <p><a id="edu-add-subject-row" class="button" href="#"><?php esc_html_e('Add Another', 'edu-results'); ?></a></p>
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

        if (!current_user_can('edit_post', $postID))
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
