<?php

namespace CBEDU\Frontend\Shortcodes;

use CBEDU\Frontend\Helpers\Icons;

class SearchForm
{

    public function __construct()
    {
        add_shortcode('cbedu_search_form', array($this, 'render_result_search_form_shortcode'));
    }

    /**
     * Generates a search form for result details.
     *
     * @param array $atts An array of attributes for the shortcode.
     * @return string The HTML output of the search form.
     */
    public function render_result_search_form_shortcode($atts)
    {
        $attributes = shortcode_atts(array(
            'placeholder' => __('Enter Registration Number', 'edu-results'),
            'button_text' => __('Search Results', 'edu-results'),
        ), $atts);
        extract($attributes);
        ob_start(); ?>
        <div class="cbedu-results-search-form-area">
            <div class="cbedu-search-form-header">
                <h2>
                    <?php echo Icons::get('search', array('class' => 'cbedu-icon cbedu-icon--title', 'size' => 28)); ?>
                    <span><?php esc_html_e('Student Result Search', 'edu-results'); ?></span>
                </h2>
                <p><?php esc_html_e('Enter your details below to view your examination results', 'edu-results'); ?></p>
            </div>

            <form action="javascript:void(0)" method="post" id="cbedu-results-form" class="cbedu-modern-form" novalidate>
                <div class="cbedu-form-grid">
                    <div class="cbedu-form-field">
                        <label for="examination">
                            <span class="label-icon"><?php echo Icons::get('exam', array('size' => 18)); ?></span>
                            <?php esc_html_e('Examination', 'edu-results'); ?>
                            <span class="required" aria-hidden="true">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="examination" id="examination" required aria-required="true">
                                <option value=""><?php esc_html_e('Select Examination', 'edu-results'); ?></option>
                                <?php
                                $examinations = get_terms('cbedu_examinations', array('hide_empty' => false));
                                if (!is_wp_error($examinations)) {
                                    foreach ($examinations as $examination) {
                                        echo '<option value="' . esc_attr($examination->slug) . '">' . esc_html($examination->name) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-examination-error" role="alert"></div>
                    </div>

                    <div class="cbedu-form-field">
                        <label for="year">
                            <span class="label-icon"><?php echo Icons::get('calendar', array('size' => 18)); ?></span>
                            <?php esc_html_e('Year', 'edu-results'); ?>
                            <span class="required" aria-hidden="true">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="year" id="year" required aria-required="true">
                                <option value=""><?php esc_html_e('Select Year', 'edu-results'); ?></option>
                                <?php
                                $years = get_terms('cbedu_session_years', array('hide_empty' => false));
                                if (!is_wp_error($years)) {
                                    foreach ($years as $year) {
                                        echo '<option value="' . esc_attr($year->slug) . '">' . esc_html($year->name) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-year-error" role="alert"></div>
                    </div>

                    <div class="cbedu-form-field">
                        <label for="board">
                            <span class="label-icon"><?php echo Icons::get('board', array('size' => 18)); ?></span>
                            <?php esc_html_e('Board', 'edu-results'); ?>
                            <span class="required" aria-hidden="true">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="board" id="board" required aria-required="true">
                                <option value=""><?php esc_html_e('Select Board', 'edu-results'); ?></option>
                                <?php
                                $boards = get_terms('cbedu_boards', array('hide_empty' => false));
                                if (!is_wp_error($boards)) {
                                    foreach ($boards as $board) {
                                        echo '<option value="' . esc_attr($board->slug) . '">' . esc_html($board->name) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-board-error" role="alert"></div>
                    </div>

                    <div class="cbedu-form-field">
                        <label for="department_group">
                            <span class="label-icon"><?php echo Icons::get('group', array('size' => 18)); ?></span>
                            <?php esc_html_e('Department/Group', 'edu-results'); ?>
                            <span class="required" aria-hidden="true">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="department_group" id="department_group" required aria-required="true">
                                <option value=""><?php esc_html_e('Select Department/Group', 'edu-results'); ?></option>
                                <?php
                                $groups = get_terms('cbedu_department_group', array('hide_empty' => false));
                                if (!is_wp_error($groups)) {
                                    foreach ($groups as $group) {
                                        echo '<option value="' . esc_attr($group->slug) . '">' . esc_html($group->name) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-department-group-error" role="alert"></div>
                    </div>

                    <div class="cbedu-form-field">
                        <label for="registration_number">
                            <span class="label-icon"><?php echo Icons::get('ticket', array('size' => 18)); ?></span>
                            <?php esc_html_e('Registration Number', 'edu-results'); ?>
                            <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input type="text" name="registration_number" id="registration_number"
                            placeholder="<?php echo esc_attr($placeholder); ?>" required aria-required="true" autocomplete="off">
                        <div class="cbedu-error-message" id="cbedu-registration-number-error" role="alert"></div>
                    </div>

                    <div class="cbedu-form-field">
                        <label for="roll">
                            <span class="label-icon"><?php echo Icons::get('hash', array('size' => 18)); ?></span>
                            <?php esc_html_e('Roll', 'edu-results'); ?>
                            <span class="required" aria-hidden="true">*</span>
                        </label>
                        <input type="text" name="roll" id="roll" placeholder="<?php esc_attr_e('Enter Roll', 'edu-results'); ?>" required aria-required="true" autocomplete="off">
                        <div class="cbedu-error-message" id="cbedu-roll-error" role="alert"></div>
                    </div>
                </div>

                <div class="cbedu-form-submit-wrapper">
                    <button type="submit" class="cbedu-submit-btn">
                        <span class="btn-icon"><?php echo Icons::get('search', array('size' => 18)); ?></span>
                        <span class="btn-text"><?php echo esc_html($button_text); ?></span>
                        <span class="btn-arrow"><?php echo Icons::get('arrow', array('size' => 18)); ?></span>
                    </button>
                </div>
            </form>

            <div id="cbedu-ajax-result-preloader" class="cbedu-preloader-wrapper" style="display: none;" aria-live="polite">
                <div class="cbedu-preloader-content">
                    <div class="cbedu-ajax-preloader-ellipsis" aria-hidden="true">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <p class="cbedu-preloader-text"><?php esc_html_e('Loading your results...', 'edu-results'); ?></p>
                </div>
            </div>

            <div id="cbedu-results-display" aria-live="polite"></div>
        </div>
<?php
        return ob_get_clean();
    }
}
