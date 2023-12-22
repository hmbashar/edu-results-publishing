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
            'placeholder' => 'Enter Registration Number',
            'button_text' => 'Search Results',
        ), $atts);
        extract($attributes); // Extract the attributes into variables
        ob_start(); ?>
        <div class="cbedu-results-search-form-area">
            <form action="<?php echo esc_url(get_permalink()); ?>" method="post" id="cbedu-results-form">
                <!-- Examination Dropdown -->
                <div class="cbedu-results-search-form-single-element">
                    <label for="examination">Examination:</label>
                    <select name="examination" id="examination" required>
                        <option value="">Select Examination</option>
                        <?php
                        $examinations = get_terms('cbedu_examinations', array('hide_empty' => false));
                        foreach ($examinations as $examination) {
                            echo '<option value="' . esc_attr($examination->slug) . '">' . esc_html($examination->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>


                <!-- Year Dropdown -->
                <div class="cbedu-results-search-form-single-element">
                    <label for="year">Year:</label>
                    <select name="year" id="year" required>
                        <option value="">Select Year</option>
                        <?php
                        $years = get_terms('cbedu_session_years', array('hide_empty' => false));
                        foreach ($years as $year) {
                            echo '<option value="' . esc_attr($year->slug) . '">' . esc_html($year->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Board Dropdown -->
                <div class="cbedu-results-search-form-single-element">
                    <label for="board">Board:</label>
                    <select name="board" id="board" required>
                        <option value="">Select Board</option>
                        <?php
                        $boards = get_terms('cbedu_boards', array('hide_empty' => false));
                        foreach ($boards as $board) {
                            echo '<option value="' . esc_attr($board->slug) . '">' . esc_html($board->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
  
                <!-- Department/Group Dropdown -->
                <div class="cbedu-results-search-form-single-element">
                    <label for="department_group">Department/Group:</label>
                    <select name="department_group" id="department_group" required>
                        <option value="">Select Department/Group</option>
                        <?php
                        $groups = get_terms('cbedu_department_group', array('hide_empty' => false));
                        foreach ($groups as $group) {
                            echo '<option value="' . esc_attr($group->slug) . '">' . esc_html($group->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

            <!-- Registration Number Input -->
            <div class="cbedu-results-search-form-single-element">
                <label for="registration_number">Registration Number:</label>
                <input type="text" name="registration_number" id="registration_number" placeholder="<?php echo esc_attr($placeholder); ?>" required>
            </div>

            <!-- Roll Input -->
            <div class="cbedu-results-search-form-single-element">
                <label for="roll">Roll:</label>
                <input type="text" name="roll" id="roll" placeholder="Enter Roll" required>
            </div>



                <!-- Submit Button -->
                <div class="cbedu-results-search-form-single-element cbedu-results-search-form-submit">
                    <input type="submit" value="<?php echo esc_attr($button_text); ?>">
                </div>
            </form>

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
