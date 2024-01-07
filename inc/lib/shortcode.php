<?php

namespace cbedu\inc\lib;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class CBEDUResultsShortcode
{
    /**
     * Constructor method for the class.
     * @return void
     */
    public function __construct()
    {

        $this->register_shortcode();
    }
    /**
     * Renders a shortcode and returns the output.
     *
     * @param array $atts An array of attributes for the shortcode.
     * @param string|null $content The content within the shortcode.
     * @return string The generated output of the shortcode.
     */
    public function render_result_details_shortcode($atts, $content = null)
    {
        ob_start();
?>
        <div class="cbedu-results-shortcode-area">

            <?php
            // Include the 'render-result-view.php' file
            include CBEDU_RESULT_DIR . 'inc/front-end/render-result-view.php';
            ?>
        </div>

    <?php
        return ob_get_clean();
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
            <form action="javascript:void(0)" method="post" id="cbedu-results-form">
                <!-- Examination Dropdown -->
                <div class="cbedu-results-search-form-single-element-area">
                    <div class="cbedu-results-search-form-single-element">
                        <label for="examination"><?php _e('Examination:', 'edu-results'); ?></label>
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
                </div><!--/ Examination Dropdown -->


                <!-- Year Dropdown -->
                <div class="cbedu-results-search-form-single-element-area">
                    <div class="cbedu-results-search-form-single-element">
                        <label for="year"><?php _e('Year:', 'edu-results'); ?></label>
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
                </div><!--/ Year Dropdown -->

                <!-- Board Dropdown -->
                <div class="cbedu-results-search-form-single-element-area">
                    <div class="cbedu-results-search-form-single-element">
                        <label for="board"><?php _e('Board:', 'edu-results'); ?></label>
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
                </div><!--/ Board Dropdown -->

                <!-- Department/Group Dropdown -->
                <div class="cbedu-results-search-form-single-element-area">
                    <div class="cbedu-results-search-form-single-element">
                        <label for="department_group"><?php _e('Department/Group:', 'edu-results'); ?></label>
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
                </div><!--/ Department/Group Dropdown -->

                <!-- Registration Number Input -->
                <div class="cbedu-results-search-form-single-element-area">
                    <div class="cbedu-results-search-form-single-element">
                        <label for="registration_number"><?php _e('Registration Number:', 'edu-results'); ?></label>
                        <input type="text" name="registration_number" id="registration_number" placeholder="<?php echo esc_attr($placeholder); ?>" required>                    
                    </div>
                    <div class="cbedu-error-message" id="cbedu-registration-number-error"></div>
                </div><!--/ Registration Number Input -->

                <!-- Roll Input -->
                <div class="cbedu-results-search-form-single-element-area">
                    <div class="cbedu-results-search-form-single-element">
                        <label for="roll"><?php _e('Roll:', 'edu-results'); ?></label>
                        <input type="text" name="roll" id="roll" placeholder="Enter Roll" required>                    
                    </div>
                    <div class="cbedu-error-message" id="cbedu-roll-error"></div>
                </div><!--/ Roll Input -->

                
                <!-- Submit Button -->
                <div class="cbedu-results-search-form-single-element cbedu-results-search-form-submit">
                    <input type="submit" value="<?php echo esc_attr($button_text); ?>">
                </div>
                
            </form>       

            <div id="cbedu-ajax-result-preloader" style="display: none;">
                <div class="cbedu-ajax-preloader-ellipsis"><div></div><div></div><div></div><div></div></div>
            </div>
            <!-- CBEDU Results Display -->
            <div id="cbedu-results-display"></div>
        </div>
<?php


        return ob_get_clean();
    }

    /**
     * Registers a shortcode for displaying educational results.
     *
     * This function adds a shortcode 'edu_results' and associates it with the
     * 'render_shortcode' method of the current class. This allows users to
     * easily display educational results on their website by using the
     * [edu_results] shortcode.     *
     */

    public function register_shortcode()
    {
        add_shortcode('cbedu_search_form', array($this, 'render_result_search_form_shortcode'));
        add_shortcode('cbedu_result_details', array($this, 'render_result_details_shortcode'));
    }
}
