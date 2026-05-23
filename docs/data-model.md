# Data Model Reference

This document describes the post types, taxonomies, options, and post meta keys used by EDU Results Publishing.

## 1. Prefix

The plugin prefix is:

```text
cbedu_
```

Most post types, taxonomies, and meta keys use this prefix.

## 2. Custom Post Types

### `cbedu_results`

Purpose: Stores published result records.

Admin label: **Results / EDU Results**

Supports:

- Title
- Featured image / thumbnail

Public settings:

- `public`: true
- `publicly_queryable`: false
- `has_archive`: true
- Menu icon: `dashicons-book`

Notes:

- Result records are the primary records searched by the front-end result search form.
- The title may be automatically updated based on the selected student registration number.
- The featured image is used as the student image on result output; if missing, `assets/img/student.webp` is used as default.

### `cbedu_subjects`

Purpose: Stores subjects that can be selected in the result subject repeater.

Admin label: **Subjects / EDU Subjects**

Supports:

- Title

Public settings:

- `public`: true
- `publicly_queryable`: false
- `has_archive`: true
- `show_in_menu`: false, but it is added as a submenu under EDU Results.

### `cbedu_students`

Purpose: Stores student profiles.

Admin label: **Students**

Supports:

- Title
- Featured image / thumbnail

Public settings:

- `public`: true
- `publicly_queryable`: true
- `has_archive`: true
- `show_in_menu`: false, but it is added as a submenu under EDU Results.

Notes:

- Student registration number is used to connect student details with result records.
- The plugin attempts to enforce unique student registration numbers.

## 3. Taxonomies

All taxonomies below are hierarchical and attached to both `cbedu_students` and `cbedu_results`.

| Taxonomy | Admin Label | Rewrite Slug | Purpose |
| --- | --- | --- | --- |
| `cbedu_session_years` | Session Years | `session-years` | Academic year/session selector. |
| `cbedu_examinations` | Examinations | `examinations` | Exam type/name selector. |
| `cbedu_boards` | Boards | `boards` | Education board selector. |
| `cbedu_department_group` | Departments/Groups | `departments-groups` | Academic department/group selector. |

## 4. Options / Settings

The settings page stores WordPress options using `register_setting()`.

| Option Key | Sanitization | Meaning |
| --- | --- | --- |
| `cbedu_results_logo` | `esc_url_raw` | Institution logo URL. |
| `cbedu_results_collage_name` | `sanitize_text_field` | Institution/college name. |
| `cbedu_results_collage_registration_number` | `sanitize_text_field` | Institution registration number. |
| `cbedu_results_collage_since_year` | `sanitize_text_field` | Institution since/founding year. |
| `cbedu_results_collage_address` | `sanitize_textarea_field` | Institution address. |
| `cbedu_results_collage_phone_number` | `sanitize_text_field` | Institution phone number. |
| `cbedu_results_collage_email_address` | `sanitize_email` | Institution email. |
| `cbedu_results_collage_website_url` | `esc_url_raw` | Institution website URL. |
| `cbedu_results_banner_heading` | `sanitize_text_field` | Heading on result page/result sheet. |

## 5. Student Post Meta

Stored on `cbedu_students` posts.

| Meta Key | Field | Notes |
| --- | --- | --- |
| `cbedu_result_std_id` | ID Number | Student ID. |
| `cbedu_result_std_registration_number` | Registration Number | Important unique lookup key. |
| `cbedu_result_std_dob` | Date of Birth | Student DOB. |
| `cbedu_result_std_gender` | Gender | Student gender. |
| `cbedu_result_std_blood_group` | Blood Group | Student blood group. |
| `cbedu_result_std_phone` | Phone Number | Student phone. |
| `cbedu_result_std_email` | Email Address | Student email. |
| `cbedu_result_std_guardian_phone` | Guardian Phone | Guardian contact. |
| `cbedu_result_std_address` | Address | Student address. |
| `cbedu_result_std_father_name` | Father's Name | Used for result auto-fill and result output. |
| `cbedu_result_std_fathers_occupation` | Father's Occupation | Optional. |
| `cbedu_result_std_fathers_qualification` | Father's Qualification | Optional. |
| `cbedu_result_std_mother_name` | Mother's Name | Used for result auto-fill and result output. |
| `cbedu_result_std_mothers_occupation` | Mother's Occupation | Optional. |
| `cbedu_result_std_mothers_qualification` | Mother's Qualification | Optional. |
| `cbedu_result_std_birth_registration_number` | Birth Registration Number | Optional government document field. |
| `cbedu_result_std_nid_number` | NID Number | Optional national ID field. |

## 6. Subject Post Meta

Stored on `cbedu_subjects` posts.

| Meta Key | Field | Notes |
| --- | --- | --- |
| `cbedu_subject_code` | Subject Code | Displayed in the result repeater subject dropdown. |

## 7. Result Post Meta

Stored on `cbedu_results` posts.

| Meta Key | Field | Notes |
| --- | --- | --- |
| `cbedu_result_std_registration_number` | Registration Number | Used by search and student lookup. |
| `cbedu_result_std_roll` | Roll Number | Required by front-end search. |
| `cbedu_result_std_name` | Student Name | Auto-filled display field. |
| `cbedu_result_std_fathers_name` | Father's Name | Auto-filled display field. |
| `cbedu_result_std_mothers_name` | Mother's Name | Auto-filled display field. |
| `cbedu_result_std_student_type` | Student Type | Student category/classification. |
| `cbedu_result_std_result_status` | Result Status | Passed/failed status. |
| `cbedu_result_std_gpa` | GPA | Final GPA. |
| `cbedu_result_std_was_gpa` | GPA Without Additional Subject | Shown as GPA (WAS). |
| `cbedu_subjects_results` | Subject results array | Repeater data for subjects and marks/grades. |

## 8. Subject Repeater Data Shape

`cbedu_subjects_results` is saved as an array of rows.

Conceptual structure:

```php
array(
    array(
        'subject_name'  => 'Mathematics',
        'subject_value' => '85',
    ),
    array(
        'subject_name'  => 'English',
        'subject_value' => 'A',
    ),
)
```

The input names used in the editor are:

```text
cbedu_results_subject_name[]
cbedu_results_subject_value[]
```

## 9. Front-End Search Query Model

The public AJAX result search queries `cbedu_results` using a combined taxonomy query and meta query.

### Required taxonomy matches

| Search Field | Taxonomy |
| --- | --- |
| Examination | `cbedu_examinations` |
| Year | `cbedu_session_years` |
| Board | `cbedu_boards` |
| Department/Group | `cbedu_department_group` |

### Required meta matches

| Search Field | Meta Key |
| --- | --- |
| Registration Number | `cbedu_result_std_registration_number` |
| Roll | `cbedu_result_std_roll` |

A result is returned only when all required taxonomy and meta conditions match.

## 10. AJAX Localized Objects

The plugin localizes data to JavaScript objects.

| Object | Script | Purpose |
| --- | --- | --- |
| `cbedu_ajax_object` | `assets/js/admin.js` | Admin registration lookup URL and nonce. |
| `cbedu_ajax_autocomplete_object` | `assets/js/autocomplete.js` | Admin autocomplete URL and nonce. |
| `cbedu_ajax_results_object` | `assets/js/ajax-search-result.js` | Public result search URL and nonce. |

## 11. Nonces

| Nonce Action | Used By |
| --- | --- |
| `cbedu_register_number_nonce` | Admin student details lookup by registration number. |
| `cbedu_auto_complete_nonce` | Admin registration number autocomplete. |
| `cbedu_ajax_search_result_nonce` | Public result search form AJAX. |
| `cbedu_save_student_nonce_action` | Student meta box save. |
| `cbedu_results_repeatable_meta_box_nonce` | Result subject repeater save. |
| `cbedu_results_settings_nonce` | Settings page save check. |

## 12. Data Integrity Rules

Recommended rules for stable operation:

- Registration numbers should be unique in `cbedu_students`.
- Result records should use registration numbers that already exist in student records.
- Each result should have all four required taxonomy terms selected.
- Result records should have both registration number and roll number.
- Subject rows should use published subject names selected from the dropdown.
- Avoid changing subject titles after results are published, because stored repeater rows use subject names rather than subject post IDs.
