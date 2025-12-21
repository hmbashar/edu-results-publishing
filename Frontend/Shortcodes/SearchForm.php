<?php

namespace CBEDU\Frontend\Shortcodes;

class SearchForm
{

    public function __construct()
    {
        add_shortcode('cbedu_search_form', array($this, 'render_result_search_form_shortcode'));
    }

    /**
     * Generates a search form for result details.
     *
     * This function creates a form that allows users to search for result details
     * by entering a registration number.
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
        extract($attributes); // Extract the attributes into variables
        ob_start(); ?>
        <div class="cbedu-results-search-form-area">
            <div class="cbedu-search-form-header">
                <h2>🎓 Student Result Search</h2>
                <p>Enter your details below to view your examination results</p>
            </div>

            <form action="javascript:void(0)" method="post" id="cbedu-results-form" class="cbedu-modern-form">
                <div class="cbedu-form-grid">
                    <!-- Examination Dropdown -->
                    <div class="cbedu-form-field">
                        <label for="examination">
                            <span class="label-icon">📝</span>
                            <?php _e('Examination', 'edu-results'); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="examination" id="examination" required>
                                <option value=""><?php _e('Select Examination', 'edu-results'); ?></option>
                                <?php
                                $examinations = get_terms('cbedu_examinations', array('hide_empty' => false));
                                foreach ($examinations as $examination) {
                                    echo '<option value="' . esc_attr($examination->slug) . '">' . esc_html($examination->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-examination-error"></div>
                    </div>

                    <!-- Year Dropdown -->
                    <div class="cbedu-form-field">
                        <label for="year">
                            <span class="label-icon">📅</span>
                            <?php _e('Year', 'edu-results'); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="year" id="year" required>
                                <option value=""><?php _e('Select Year', 'edu-results'); ?></option>
                                <?php
                                $years = get_terms('cbedu_session_years', array('hide_empty' => false));
                                foreach ($years as $year) {
                                    echo '<option value="' . esc_attr($year->slug) . '">' . esc_html($year->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-year-error"></div>
                    </div>

                    <!-- Board Dropdown -->
                    <div class="cbedu-form-field">
                        <label for="board">
                            <span class="label-icon">🏛️</span>
                            <?php _e('Board', 'edu-results'); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="board" id="board" required>
                                <option value=""><?php _e('Select Board', 'edu-results'); ?></option>
                                <?php
                                $boards = get_terms('cbedu_boards', array('hide_empty' => false));
                                foreach ($boards as $board) {
                                    echo '<option value="' . esc_attr($board->slug) . '">' . esc_html($board->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-board-error"></div>
                    </div>

                    <!-- Department/Group Dropdown -->
                    <div class="cbedu-form-field">
                        <label for="department_group">
                            <span class="label-icon">📚</span>
                            <?php _e('Department/Group', 'edu-results'); ?>
                            <span class="required">*</span>
                        </label>
                        <div class="cbedu-select-wrapper">
                            <select name="department_group" id="department_group" required>
                                <option value=""><?php _e('Select Department/Group', 'edu-results'); ?></option>
                                <?php
                                $groups = get_terms('cbedu_department_group', array('hide_empty' => false));
                                foreach ($groups as $group) {
                                    echo '<option value="' . esc_attr($group->slug) . '">' . esc_html($group->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="cbedu-error-message" id="cbedu-department-group-error"></div>
                    </div>

                    <!-- Registration Number Input -->
                    <div class="cbedu-form-field">
                        <label for="registration_number">
                            <span class="label-icon">🎫</span>
                            <?php _e('Registration Number', 'edu-results'); ?>
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="registration_number" id="registration_number"
                            placeholder="<?php echo esc_attr($placeholder); ?>" required>
                        <div class="cbedu-error-message" id="cbedu-registration-number-error"></div>
                    </div>

                    <!-- Roll Input -->
                    <div class="cbedu-form-field">
                        <label for="roll">
                            <span class="label-icon">🔢</span>
                            <?php _e('Roll', 'edu-results'); ?>
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="roll" id="roll" placeholder="Enter Roll" required>
                        <div class="cbedu-error-message" id="cbedu-roll-error"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="cbedu-form-submit-wrapper">
                    <button type="submit" class="cbedu-submit-btn">
                        <span class="btn-icon">🔍</span>
                        <span class="btn-text"><?php echo esc_html($button_text); ?></span>
                        <span class="btn-arrow">→</span>
                    </button>
                </div>
            </form>

            <div id="cbedu-ajax-result-preloader" class="cbedu-preloader-wrapper" style="display: none;">
                <div class="cbedu-preloader-content">
                    <div class="cbedu-ajax-preloader-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <p class="cbedu-preloader-text">Loading your results...</p>
                </div>
            </div>

            <!-- CBEDU Results Display -->
            <div id="cbedu-results-display"></div>
        </div>
<?php


        return ob_get_clean();
    }
}
