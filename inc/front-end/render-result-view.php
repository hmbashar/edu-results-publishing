<?php
$cbedu_result = new WP_Query(
    array(
        'post_type' => 'cbedu_results',
        'posts_per_page' => 1,
    )
);
$collageName = get_option('cbedu_results_collage_name');
?>

<div class="edu-results-render-wrapping-area">
    <div class="edu-result-render-area">
        <!--Banner Area-->
        <div class="edu-result-ver-banner-area">
            <div class="edu-result-ver-name">
                <h2><?php echo esc_html($collageName); ?></h2>
            </div>
            <div class="edu-result-banner-heading">
                <h3>SSC/Dakil/Equivalent Result 2023</h3>
            </div>
        </div><!--/ Banner Area-->
        <?php
        if ($cbedu_result->have_posts()):
            while ($cbedu_result->have_posts()):
                $cbedu_result->the_post();

                //student information metabox 
                $cbedu_std_roll = get_post_meta(get_the_ID(), 'cbedu_result_std_roll', true);
                $cbedu_std_reg_number = get_post_meta(get_the_ID(), 'cbedu_result_std_registration_number', true);
                $cbedu_std_board = get_post_meta(get_the_ID(), 'cbedu_result_std_board', true);
                $cbedu_std_f_name = get_post_meta(get_the_ID(), 'cbedu_result_std_father_name', true);
                $cbedu_std_m_name = get_post_meta(get_the_ID(), 'cbedu_result_std_mother_name', true);
                $cbedu_std_gpa = get_post_meta(get_the_ID(), 'cbedu_result_std_gpa', true);
                $cbedu_std_group = get_post_meta(get_the_ID(), 'cbedu_result_std_group', true);
                $cbedu_std_id = get_post_meta(get_the_ID(), 'cbedu_result_std_id', true);
                $cbedu_std_re_status = get_post_meta(get_the_ID(), 'cbedu_result_std_result_status', true);
                $cbedu_std_type = get_post_meta(get_the_ID(), 'cbedu_result_std_student_type', true);
                $cbedu_std_dob = get_post_meta(get_the_ID(), 'cbedu_result_std_dob', true);

                //Student subjects result
        
                $cbedu_std_all_subjects_result = get_post_meta(get_the_ID(), 'cbedu_subjects_results', true);
                ?>
                <!--Student Information-->
                <div class="edu-result-student-information-area">
                    <div class="edu-result-student-information-heading">
                        <h4>Student Information</h4>
                    </div>
                    <table border="1">
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
                <div class="edu-result-student-subject-area">
                    <div class="edu-result-student-subject">
                        <div class="edu-result-student-subject-heading">
                            <h4>Result Sheet</h4>
                        </div>

                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Subject Name</th>
                                    <th>Subject Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                // Check if there are subject results
                                if (!empty($cbedu_std_all_subjects_result) && is_array($cbedu_std_all_subjects_result)) {

                                    foreach ($cbedu_std_all_subjects_result as $subject_result) {
                                        if (isset($subject_result['subject_name']) && isset($subject_result['subject_value'])) {
                                            $subject_name = esc_html($subject_result['subject_name']);
                                            $subject_value = esc_html($subject_result['subject_value']);
                                            ?>
                                            <tr>
                                                <td><?php echo esc_html($subject_name); ?></td>
                                                <td><?php echo esc_html($subject_value); ?></td>
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
            
                endwhile; endif; 
                wp_reset_query();
            ?>
    </div>
</div>