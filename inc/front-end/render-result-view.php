<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
$post_id = 157;
$cbedu_result = new WP_Query(
    array(
        'post_type' => 'cbedu_results',
        'p'         => $post_id,
    )
);
$collageName = get_option('cbedu_results_collage_name');
$collageRegistrationNumber = get_option('cbedu_results_collage_registration_number');
$collagePhone = get_option('cbedu_results_collage_phone_number');
$collageEmail = get_option('cbedu_results_collage_email_address');
$collageAddress = get_option('cbedu_results_collage_address');
$collageWebsite = get_option('cbedu_results_collage_website_url');
$collageBannerHeading = get_option('cbedu_results_banner_heading');
$collageLogo = get_option('cbedu_results_logo');



if ($cbedu_result->have_posts()) :
    while ($cbedu_result->have_posts()) :
        $cbedu_result->the_post();

        //student information metabox 
        $cbedu_std_roll = get_post_meta(get_the_ID(), 'cbedu_result_std_roll', true);
        $cbedu_std_reg_number = get_post_meta(get_the_ID(), 'cbedu_result_std_registration_number', true);
        $cbedu_std_board = get_post_meta(get_the_ID(), 'cbedu_result_std_board', true);
        $cbedu_std_f_name = get_post_meta(get_the_ID(), 'cbedu_result_std_father_name', true);
        $cbedu_std_m_name = get_post_meta(get_the_ID(), 'cbedu_result_std_mother_name', true);
        $cbedu_std_gpa = get_post_meta(get_the_ID(), 'cbedu_result_std_gpa', true);
        $cbedu_std_was_gpa = get_post_meta(get_the_ID(), 'cbedu_result_std_was_gpa', true);
        $cbedu_std_group = get_post_meta(get_the_ID(), 'cbedu_result_std_group', true);
        $cbedu_std_id = get_post_meta(get_the_ID(), 'cbedu_result_std_id', true);
        $cbedu_std_re_status = get_post_meta(get_the_ID(), 'cbedu_result_std_result_status', true);
        $cbedu_std_type = get_post_meta(get_the_ID(), 'cbedu_result_std_student_type', true);
        $cbedu_std_dob = get_post_meta(get_the_ID(), 'cbedu_result_std_dob', true);


        //Student subjects result
        $cbedu_std_all_subjects_result = get_post_meta(get_the_ID(), 'cbedu_subjects_results', true);
?>

        <div class="cbedu-results-render-wrapping-area" id="cbedu-results-render-wrapping-area">
            <div class="cbedu-result-render-area">
                <!--Banner Area-->
                <div class="cbedu-result-ver-banner-area">
                    <?php if(!empty($collageName)): ?>
                        <div class="cbedu-result-ver-name">
                            <h2><?php echo esc_html($collageName); ?></h2>
                        </div>
                    <?php endif; ?>
                    <div class="cbedu-result-sheet-header-info">
                        <?php if(!empty($collageLogo)) : ?>
                            <div class="cbedu-result-sheet-collage-logo">
                                <img src="<?php echo esc_url($collageLogo); ?>" alt="Collage Logo">
                            </div>
                        <?php endif; ?>
                        <div class="cbedu-result-collage-information">
                            <?php if(!empty($collageRegistrationNumber)) : ?>
                                <div class="cbedu-result-collage-sub-heading">
                                    <h4><?php _e('Collage Registration: ', 'edu-results'); echo esc_html($collageRegistrationNumber); ?></h4>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($collagePhone) && !empty($collageEmail)) : ?>
                                <div class="cbedu-result-collage-info">
                                    <?php if(!empty($collagePhone)) : ?>
                                        <p><?php _e('Phone: ', 'edu-results'); echo esc_html($collagePhone); ?></p>
                                    <?php endif; ?>
                                    <?php if(!empty($collageEmail)) : ?>
                                        <p><?php _e('Email: ', 'edu-results'); echo esc_html($collageEmail); ?></p>
                                    <?php endif; ?>                                    
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($collageAddress)) : ?>
                                <div class="cbedu-result-collage-info">
                                    <p><?php _e('Address: ', 'edu-results'); echo esc_html($collageAddress); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($collageWebsite)) : ?>
                                <div class="cbedu-result-collage-info">
                                    <p><?php _e('Website: ', 'edu-results'); echo esc_html($collageWebsite); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="cbedu-result-collage-std-image">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('thumbnail');
                            } else {
                                echo '<img src="' . CBEDU_RESULT_URL . '/assets/img/student.webp" alt="Student Image">';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="cbedu-result-banner-heading">
                        <h3><?php echo esc_html($collageBannerHeading); ?></h3>
                    </div>
                </div><!--/ Banner Area-->
                <!--Student Information-->
                <div class="cbedu-result-student-information-area">
                    <div class="cbedu-result-student-information-heading">
                        <h4><?php _e('Student Information', 'edu-results'); ?></h4>
                    </div>
                    <table>
                        <tr>
                            <th><?php _e('Roll:', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_roll); ?>
                            </td>
                            <th><?php _e('Registration Number:', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_reg_number); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Name:', 'edu-results'); ?>
                            </th>
                            <td>
                                <?php the_title(); ?>
                            </td>
                            <th>
                                <?php _e('Father:', 'edu-results'); ?>
                            </th>
                            <td>
                                <?php echo esc_html($cbedu_std_f_name); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Board:', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_board); ?>
                            </td>
                            <th><?php _e('Group:', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_group); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('DOB:', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_dob); ?>
                            </td>
                            <th><?php _e('Institute', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($collageName); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Monther Name:', 'edu-results'); ?>
                            </th>
                            <td>
                                <?php echo esc_html($cbedu_std_m_name); ?>
                            </td>
                            <th><?php _e('GPA', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_gpa); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('ID:', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_id); ?>
                            </td>
                            <th><?php _e('Result', 'edu-results'); ?></th>
                            <td>
                                <?php echo esc_html($cbedu_std_re_status); ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Student Type:', 'edu-results'); ?> </th>
                            <td>
                                <?php echo esc_html($cbedu_std_type); ?>
                            </td>
                        </tr>
                    </table>
                </div><!--/ Student Information-->

                <!--Student Subjects Information-->
                <div class="cbedu-result-student-subject-area">
                    <div class="cbedu-result-student-subject">
                        <div class="cbedu-result-student-subject-heading">
                            <h4><?php _e('Result Sheet', 'edu-results'); ?></h4>
                        </div>
                        <table>
                            <tr>
                                <th><?php _e('Subject', 'edu-results'); ?></th>
                                <th><?php _e('Name of Subjects', 'edu-results'); ?></th>
                                <th><?php _e('Marks', 'edu-results'); ?></th>
                                <th><?php _e('Letter Grade', 'edu-results'); ?></th>
                                <th class="cbedu-table-gpa"><?php _e('GPA', 'edu-results'); ?> <abbr title="Without additional subject"><?php _e('(WAS)', 'edu-results'); ?></abbr></th>
                                <th><?php _e('GPA', 'edu-results'); ?></th>
                            </tr>
                            <?php

                            // Check if there are subject results
                            if (!empty($cbedu_std_all_subjects_result) && is_array($cbedu_std_all_subjects_result)) {
                                // Calculate the total number of rows for the rowspan
                                $rowSpan = count($cbedu_std_all_subjects_result);
                                // Initialize a variable to track the first row
                                $isFirstRow = true;


                                foreach ($cbedu_std_all_subjects_result as $subject_result) {
                                    if (isset($subject_result['subject_name']) && isset($subject_result['subject_value'])) {
                                        $subject_name = esc_html($subject_result['subject_name']);
                                        $marks = intval(esc_html($subject_result['subject_value'])); // Assuming the marks are stored in 'subject_value'
                                        list($letter_grade, $grade_point) = CBEDUResultPublishing::convert_marks_to_grade($marks);

                                        // Fetch subject code based on subject name
                                        $subject_posts = get_posts(array(
                                            'post_type' => 'cbedu_subjects',
                                            'title'     => $subject_name,
                                            'posts_per_page' => 1
                                        ));

                                        $subject_code = '';
                                        if (!empty($subject_posts)) {
                                            $subject_code = get_post_meta($subject_posts[0]->ID, 'cbedu_subject_code', true);
                                        }

                            ?>
                                        <tr>
                                            <td><?php echo esc_html($subject_code); ?></td>
                                            <td><?php echo esc_html($subject_name); ?></td>
                                            <td><?php echo esc_html($marks); ?></td>
                                            <td><?php echo esc_html($letter_grade); ?></td>
                                            <?php if ($isFirstRow) { ?>
                                                <td rowspan="<?php echo $rowSpan; ?>" class="highlight"><?php echo esc_html($cbedu_std_was_gpa); ?></td>
                                                <td rowspan="<?php echo $rowSpan; ?>" class="highlight"><?php echo esc_html($cbedu_std_gpa); ?></td>
                                                <?php
                                                // Set to false so the rowspan is not repeated in subsequent rows
                                                $isFirstRow = false;
                                                ?>
                                            <?php } ?>
                                        </tr>

                            <?php

                                    }
                                }
                            }
                            ?>
                            </tbody>

                        </table>
                    </div>
                </div><!--/ Student Subjects Information-->
            </div>
        </div>
<?php
    endwhile;
endif;
wp_reset_query();
?>