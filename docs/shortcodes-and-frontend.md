# Shortcodes and Front-End Behavior

This document explains the public-facing functionality of EDU Results Publishing.

## 1. Registered Shortcodes

The plugin registers two shortcodes in `inc/lib/shortcode.php`.

| Shortcode | Purpose | Status |
| --- | --- | --- |
| `[cbedu_search_form]` | Displays the public result search form. | Primary/public shortcode. |
| `[cbedu_result_details]` | Displays a result detail wrapper using `inc/front-end/render-result-view.php`. | Should be reviewed before production because the render file contains a hardcoded post ID. |

## 2. `[cbedu_search_form]`

Use this shortcode on a WordPress page:

```text
[cbedu_search_form]
```

It renders a modern search form with these fields:

- Examination
- Year
- Board
- Department/Group
- Registration Number
- Roll

All fields are required.

## 3. Shortcode Attributes

The search form supports these shortcode attributes:

| Attribute | Default | Meaning |
| --- | --- | --- |
| `placeholder` | `Enter Registration Number` | Placeholder for the registration number input. |
| `button_text` | `Search Results` | Search button text. |

Example:

```text
[cbedu_search_form placeholder="Type registration no." button_text="View Result"]
```

## 4. Search Form Data Sources

Dropdowns are populated from taxonomy terms:

| Dropdown | Taxonomy |
| --- | --- |
| Examination | `cbedu_examinations` |
| Year | `cbedu_session_years` |
| Board | `cbedu_boards` |
| Department/Group | `cbedu_department_group` |

If a dropdown is empty, add terms in the WordPress admin first.

## 5. Front-End JavaScript

File:

```text
assets/js/ajax-search-result.js
```

Responsibilities:

1. Listen for submit on `#cbedu-results-form`.
2. Prevent normal page reload.
3. Clear old validation messages.
4. Validate all required fields.
5. Show `#cbedu-ajax-result-preloader` while searching.
6. Submit serialized form data to WordPress AJAX with action `cbedu_handle_form_submission`.
7. Insert returned HTML into `#cbedu-results-display`.
8. Hide the preloader when complete.

## 6. AJAX Endpoint

Action:

```text
cbedu_handle_form_submission
```

Registered hooks:

```php
wp_ajax_cbedu_handle_form_submission
wp_ajax_nopriv_cbedu_handle_form_submission
```

Nonce action:

```text
cbedu_ajax_search_result_nonce
```

Localized JavaScript object:

```text
cbedu_ajax_results_object
```

## 7. Required AJAX Request Fields

| Field | Type | Required | Meaning |
| --- | --- | --- | --- |
| `examination` | taxonomy slug | Yes | Selected examination. |
| `year` | taxonomy slug | Yes | Selected session/year. |
| `board` | taxonomy slug | Yes | Selected board. |
| `department_group` | taxonomy slug | Yes | Selected department/group. |
| `registration_number` | text | Yes | Student registration number. |
| `roll` | text | Yes | Student roll number. |
| `nonce` | text | Yes | AJAX security nonce. |

## 8. Result Output

The AJAX handler returns an HTML result sheet. It includes:

- Institution name/logo/contact details from plugin settings.
- Result banner heading.
- Student photo from result featured image or default `assets/img/student.webp`.
- Student roll and registration number.
- Student name.
- Father and mother names.
- Student ID, date of birth, gender, student type, result status.
- Session, examination, board, and department/group names.
- GPA and GPA without additional subject.
- Subject-wise result table.
- Print action support.

## 9. Result Matching Rules

A result is displayed only when the submitted search values match a published `cbedu_results` post.

Required matches:

- `cbedu_examinations` term slug equals submitted examination.
- `cbedu_session_years` term slug equals submitted year.
- `cbedu_boards` term slug equals submitted board.
- `cbedu_department_group` term slug equals submitted department/group.
- `cbedu_result_std_registration_number` equals submitted registration number.
- `cbedu_result_std_roll` equals submitted roll.

## 10. Print Behavior

File:

```text
assets/js/print.js
```

Global function:

```text
cbeduPrintResult(tableId)
```

Behavior:

1. Finds an element by ID.
2. Opens a new browser window.
3. Writes the selected element HTML into the window.
4. Adds basic print CSS.
5. Calls `print()`.

## 11. Styling

File:

```text
assets/css/style.css
```

Main style areas:

- Result search form layout.
- Form validation messages.
- AJAX preloader area.
- Result sheet layout.
- Institution banner/header area.
- Result tables.
- Student image and institution logo.
- Responsive behavior.

## 12. Page Template Recommendation

Use a full-width page template for the result search page if your theme supports it. The result sheet uses a wide tabular layout and may feel cramped inside narrow content columns.

## 13. `[cbedu_result_details]` Technical Note

The `[cbedu_result_details]` shortcode includes `inc/front-end/render-result-view.php`.

That file currently starts with:

```php
$post_id = 157;
```

This means it is not dynamically tied to the current result or search request unless refactored. For production use, prefer `[cbedu_search_form]` or update the render file to accept a dynamic result ID.

Recommended refactor:

- Pass a result ID as a shortcode attribute.
- Validate and cast it with `absint()`.
- Query the selected result post.
- Remove the hardcoded ID.

Example target shortcode design:

```text
[cbedu_result_details id="157"]
```
