<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

$cbedu_result = new WP_Query(
    array(
        'post_type' => 'cbedu_results',
        'posts_per_page' => 1,
    )
);
$collageName = get_option('cbedu_results_collage_name');
?>

<div class="cbedu-results-render-wrapping-area" id="cbedu-results-render-wrapping-area">
    <div class="cbedu-result-render-area">
        <!--Banner Area-->
        <div class="cbedu-result-ver-banner-area">
            <div class="cbedu-result-ver-name">
                <h2><?php echo esc_html($collageName); ?></h2>
            </div>
            <div class="cbedu-result-sheet-header-info">
                <div class="cbedu-result-sheet-collage-logo">
                    <img src="<?php echo esc_url(get_option('cbedu_results_logo')); ?>" alt="Collage Logo">
                </div>
                <div class="cbedu-result-collage-information">
                    <div class="cbedu-result-collage-sub-heading">
                        <h4>Collage Registration: 256569874598</h4>
                    </div>
                    <div class="cbedu-result-collage-info">
                        <p>Phone: 0123456789</p>
                        <p>Email: admin@example.com</p>
                    </div>
                    <div class="cbedu-result-collage-info">
                        <p>Address: 123 Main Street, New York, United States</p>
                    </div>
                    <div class="cbedu-result-collage-info">
                        <p>Website: www.example.com</p>
                    </div>
                </div>
                <div class="cbedu-result-collage-std-image">
                    <img src="<?php echo CBEDU_RESULT_URL ?>/assets/img/student.webp" alt="Student Image">
                </div>
            </div>
            <div class="cbedu-result-banner-heading">
                <h3>SSC/Dakil/Equivalent Result 2023</h3>
            </div>
        </div><!--/ Banner Area-->

        <?php
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
                <!--Student Information-->
                <div class="cbedu-result-student-information-area">
                    <div class="cbedu-result-student-information-heading">
                        <h4>Student Information</h4>
                    </div>
                    <table>
                        <tr>
                            <th>Roll:</th>
                            <td>
                                <?php echo esc_html($cbedu_std_roll); ?>
                            </td>
                            <th>Registration Number:</th>
                            <td>
                                <?php echo esc_html($cbedu_std_reg_number); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Name:
                            </th>
                            <td>
                                <?php the_title(); ?>
                            </td>
                            <td>
                                Father:
                            </td>
                            <td>
                                <?php echo esc_html($cbedu_std_f_name); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Board:</td>
                            <td>
                                <?php echo esc_html($cbedu_std_board); ?>
                            </td>
                            <td>Group:</td>
                            <td>
                                <?php echo esc_html($cbedu_std_group); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>DOB:</td>
                            <td>
                                <?php echo esc_html($cbedu_std_dob); ?>
                            </td>
                            <th>Institute</th>
                            <td>
                                <?php echo esc_html($collageName); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Monther Name:
                            </td>
                            <td>
                                <?php echo esc_html($cbedu_std_m_name); ?>
                            </td>
                            <th>GPA</th>
                            <td>
                                <?php echo esc_html($cbedu_std_gpa); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>ID:</th>
                            <td>
                                <?php echo esc_html($cbedu_std_id); ?>
                            </td>
                            <th>Result</th>
                            <td>
                                <?php echo esc_html($cbedu_std_re_status); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Student Type: </th>
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
                            <h4>Result Sheet</h4>
                        </div>
                        <table>
                            <tr>
                                <th>Code</th>
                                <th>Name of Subjects</th>
                                <th>Letter Mark</th>
                                <th>Letter Grade</th>
                                <th class="cbedu-table-gpa">GPA <abbr title="Without additional subject">(WAS)</abbr></th>
                                <th>GPA</th>
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

        <?php

            endwhile;
        endif;
        wp_reset_query();
        ?>
    </div>
</div>