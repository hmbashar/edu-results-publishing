<?php
namespace inc\RepeaterCF;

class EDURepeaterCustomFields
{
    private $textdomain;

    public function __construct($eduResultPublishing)
    {
        $this->textdomain = $eduResultPublishing->getTextDomain();

        add_action('add_meta_boxes', array($this, 'addMetaBox'));
        add_action('save_post', array($this, 'saveMetaBoxData'));
    }

    public function addMetaBox()
    {
        add_meta_box('edu_results_repeater', __('Subjects Information', $this->textdomain), array($this, 'renderMetaBox'), 'edu_results', 'normal', 'default');
    }

    public function renderMetaBox($post)
    {
        $eduResultsGroup = get_post_meta($post->ID, 'edu_subjects_results', true);

        wp_nonce_field('edu_results_repeatable_meta_box_nonce', 'edu_results_repeatable_meta_box_nonce');
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#edu-add-subject-row').on('click', function () {
                    var row = $('.empty-row.screen-reader-text').clone(true);
                    row.removeClass('empty-row screen-reader-text');
                    row.insertBefore('#repeatable-fieldset-one tbody>tr:last');
                    return false;
                });

                $('.remove-row').on('click', function () {
                    $(this).parents('tr').remove();
                    return false;
                });
            });
        </script>
        <table id="repeatable-fieldset-one" width="100%">
            <tbody>
                <?php
                if ($eduResultsGroup):
                    foreach ($eduResultsGroup as $field) {
                        ?>
                        <tr>
                            <td width="70%">
                                <input style="width:80%;padding:10px;" type="text"
                                    placeholder="<?php esc_attr_e('Enter subject name', $this->textdomain); ?>"
                                    name="edu_results_subject_name[]"
                                    value="<?php if ($field['subject_name'] != '')
                                        echo esc_attr($field['subject_name']); ?>" />
                            </td>
                            <td width="70%">
                                <input style="width:80%;padding:10px;" type="text"
                                    placeholder="<?php esc_attr_e('Enter subject value', $this->textdomain); ?>"
                                    name="edu_results_subject_value[]"
                                    value="<?php echo isset($field['subject_value']) ? esc_attr($field['subject_value']) : ''; ?>" />
                            </td>

                            <td width="15%"><a class="button remove-row" href="#1">
                                    <?php esc_html_e('Remove', $this->textdomain); ?>
                                </a></td>
                        </tr>
                        <?php
                    }
                else:
                    // Show a blank row
                    ?>
                    <tr>
                        <td>
                            <input style="width:80%;padding:10px;" type="text"
                                placeholder="<?php esc_attr_e('Enter subject name', $this->textdomain); ?>"
                                name="edu_results_subject_name[]" />
                        </td>
                        <td>
                            <input style="width:80%;padding:10px;" type="text"
                                placeholder="<?php esc_attr_e('Enter subject value', $this->textdomain); ?>"
                                name="edu_results_subject_value[]" />
                        </td>
                        <td><a class="button  cmb-remove-row-button button-disabled" href="#">
                                <?php esc_html_e('Remove', $this->textdomain); ?>
                            </a></td>
                    </tr>
                <?php endif; ?>

                <!-- Empty hidden row for jQuery -->
                <tr class="empty-row screen-reader-text">
                    <td>
                        <input style="width:80%;padding:10px;" type="text"
                            placeholder="<?php esc_attr_e('Enter subject name', $this->textdomain); ?>"
                            name="edu_results_subject_name[]" />
                    </td>
                    <td>
                        <input style="width:80%;padding:10px;" type="text"
                            placeholder="<?php esc_attr_e('Enter subject value', $this->textdomain); ?>"
                            name="edu_results_subject_value[]" />
                    </td>
                    <td><a class="button remove-row" href="#">
                            <?php esc_html_e('Remove', $this->textdomain); ?>
                        </a></td>
                </tr>
            </tbody>
        </table>
        <p><a id="edu-add-subject-row" class="button" href="#">
                <?php esc_html_e('Add Another', $this->textdomain); ?>
            </a></p>
        <?php
    }

    public function saveMetaBoxData($postID)
    {
        if (
            !isset($_POST['edu_results_repeatable_meta_box_nonce']) ||
            !wp_verify_nonce($_POST['edu_results_repeatable_meta_box_nonce'], 'edu_results_repeatable_meta_box_nonce')
        )
            return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (!current_user_can('edit_post', $postID))
            return;

        $old = get_post_meta($postID, 'edu_subjects_results', true);
        $new = array();
        $subjectNames = $_POST['edu_results_subject_name'];
        $subjectValues = $_POST['edu_results_subject_value'];
        $count = count($subjectNames);
        for ($i = 0; $i < $count; $i++) {
            if ($subjectNames[$i] != ''):
                $new[$i]['subject_name'] = stripslashes(strip_tags($subjectNames[$i]));
                $new[$i]['subject_value'] = stripslashes(strip_tags($subjectValues[$i]));
            endif;
        }
        if (!empty($new) && $new != $old)
            update_post_meta($postID, 'edu_subjects_results', $new);
        elseif (empty($new) && $old)
            delete_post_meta($postID, 'edu_subjects_results', $old);
    }
}