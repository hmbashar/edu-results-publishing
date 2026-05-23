# Developer Guide: EDU Results Publishing

This guide explains how the EDU Results Publishing plugin is built and how developers can maintain or extend it.

## 1. Technology Stack

- Platform: WordPress plugin
- Language: PHP
- Front-end/admin scripting: jQuery
- Styling: CSS
- Package manager: Composer
- Autoloading: Composer PSR-4
- Text domain: `edu-results`
- Main namespace: `CBEDU`
- Internal namespaces: lowercase `cbedu\inc\...`

## 2. Main Entry File

Main file:

```text
edu-results-publishing.php
```

The file declares the WordPress plugin header, defines constants, includes dependencies, and instantiates the final plugin class.

Important constants:

| Constant | Value / Meaning |
| --- | --- |
| `CBEDU_RESULT_URL` | Plugin URL from `plugin_dir_url(__FILE__)`. |
| `CBEDU_RESULT_DIR` | Plugin filesystem path from `plugin_dir_path(__FILE__)`. |
| `CBEDU_PREFIX` | `cbedu_`. Used for post types/taxonomies/meta naming. |
| `CBEDU_VERSION` | `1.2.0`. |

## 3. Main Class

Class:

```php
CBEDU\CBEDUResultPublishing
```

Primary responsibilities:

- Store the plugin prefix.
- Register plugin action links.
- Change title placeholders for plugin post types.
- Enqueue admin and front-end assets.
- Load translations.
- Register post types and taxonomies.
- Initialize custom fields, settings, repeater fields, shortcodes, and utility functions.
- Register AJAX handlers.
- Convert marks into grades.

## 4. Bootstrap Flow

At load time, the plugin follows this flow:

1. Prevent direct access if `ABSPATH` is not defined.
2. Define constants.
3. Load Composer autoload if available.
4. Require PHP class files from `inc/`.
5. Instantiate `CBEDUResultPublishing`.
6. Constructor registers global hooks.
7. Constructor calls `initialize()`.
8. `initialize()` calls:
   - `initializePostTypesAndTaxonomies()`
   - `initializeComponents()`
9. AJAX handlers are registered.

## 5. File Structure

```text
edu-results-publishing/
├── edu-results-publishing.php
├── composer.json
├── readme.txt
├── IMPROVEMENTS.md
├── assets/
│   ├── css/
│   │   ├── admin-meta-fields.css
│   │   ├── admin-settings.css
│   │   ├── autocomplete.css
│   │   └── style.css
│   ├── img/
│   │   └── student.webp
│   └── js/
│       ├── admin.js
│       ├── ajax-search-result.js
│       ├── autocomplete.js
│       └── print.js
├── inc/
│   ├── RepeaterCF.php
│   ├── custom-fields.php
│   ├── admin/
│   │   └── settings.php
│   ├── front-end/
│   │   └── render-result-view.php
│   └── lib/
│       ├── custom-functions.php
│       ├── custom-posts.php
│       ├── custom-taxonomy.php
│       └── shortcode.php
├── languages/
│   ├── bn_BD.mo
│   ├── bn_BD.po
│   ├── bn_BD.pot
│   └── edu-results-publishing.pot
└── vendor/
```

## 6. Main PHP Components

### `inc/lib/custom-posts.php`

Class:

```php
cbedu\inc\lib\CBEDU_CUSTOM_POSTS
```

Registers:

- `cbedu_results`
- `cbedu_subjects`
- `cbedu_students`

It also adds submenu entries for post types under the EDU Results admin area.

### `inc/lib/custom-taxonomy.php`

Class:

```php
cbedu\inc\lib\CBEDU_CUSTOM_TAXONOMY
```

Registers taxonomies:

- `cbedu_session_years`
- `cbedu_examinations`
- `cbedu_boards`
- `cbedu_department_group`

These taxonomies are attached to both students and results.

### `inc/custom-fields.php`

Class:

```php
cbedu\inc\custom_fields\CBEDUCustomFields
```

Registers and saves meta boxes for:

- Student fields
- Subject fields
- Result fields

Also handles:

- registration number uniqueness checking
- auto-draft deletion behavior
- admin notices
- registration number autocomplete AJAX data source
- result title update on save

### `inc/RepeaterCF.php`

Class:

```php
cbedu\inc\RepeaterCF\CBEDURepeaterCustomFields
```

Adds the **Subjects Information** repeater meta box to result posts and saves `cbedu_subjects_results` as an array of subject rows.

### `inc/admin/settings.php`

Class:

```php
cbedu\inc\admin\settings\CBEDUResultSettings
```

Adds the settings page under Results and registers institution options.

### `inc/lib/shortcode.php`

Class:

```php
cbedu\inc\lib\CBEDUResultsShortcode
```

Registers shortcodes:

- `[cbedu_search_form]`
- `[cbedu_result_details]`

### `inc/lib/custom-functions.php`

Class:

```php
cbedu\inc\lib\CBEDUCustomFunctions\CBEDUCustomFunctions
```

Adds taxonomy requirement validation during save for result records.

## 7. Hook Overview

Main hooks used by the plugin:

| Hook | Purpose |
| --- | --- |
| `init` | Register post types and taxonomies. |
| `admin_menu` | Add plugin submenu pages. |
| `admin_init` | Register settings and perform admin-side handling. |
| `add_meta_boxes` | Add custom meta boxes. |
| `save_post` | Save meta fields and validate taxonomy requirements. |
| `admin_enqueue_scripts` | Load admin CSS/JS. |
| `wp_enqueue_scripts` | Load front-end CSS/JS. |
| `plugins_loaded` | Load text domain. |
| `edit_form_after_title` | Display helper text under result title. |
| `post_updated_messages` | Customize post publish/update notices. |
| `wp_ajax_*` | Handle authenticated AJAX requests. |
| `wp_ajax_nopriv_*` | Handle public AJAX requests where needed. |

## 8. AJAX Endpoints

### `get_student_details_by_registration`

Used in the admin result editor to auto-fill student name, father name, and mother name from a registration number.

Registered for:

- logged-in users
- non-logged-in users

Security nonce:

```text
cbedu_register_number_nonce
```

Request data:

| Field | Meaning |
| --- | --- |
| `registration_number` | Student registration number. |
| `security` | Nonce value. |

Response JSON:

```json
{
  "studentName": "Student Name",
  "fathersName": "Father Name",
  "mothersName": "Mother Name"
}
```

### `cbedu_handle_form_submission`

Used by the public search form.

Registered for:

- logged-in users
- non-logged-in visitors

Security nonce:

```text
cbedu_ajax_search_result_nonce
```

Required fields:

- `registration_number`
- `roll`
- `examination`
- `year`
- `board`
- `department_group`

The handler returns HTML, not JSON.

### `add_search_registration_numbers`

Used by jQuery UI autocomplete on the admin result editor.

Security nonce:

```text
cbedu_auto_complete_nonce
```

The handler searches student records by registration number and returns autocomplete labels/values.

## 9. Asset Loading

### Admin Assets

Loaded through `admin_enqueue_scripts`.

Global admin asset:

- `assets/js/admin.js`

Loaded on student, subject, and result post edit screens:

- `assets/css/admin-meta-fields.css`

Loaded on result edit screen:

- `assets/css/autocomplete.css`
- `assets/js/autocomplete.js`
- WordPress `jquery-ui-autocomplete`

Settings page asset:

- `assets/css/admin-settings.css`

### Front-End Assets

Loaded through `wp_enqueue_scripts`.

- `assets/css/style.css`
- `assets/js/ajax-search-result.js`
- `assets/js/print.js`

## 10. Grade Conversion Logic

Static method:

```php
CBEDUResultPublishing::convert_marks_to_grade($marks)
```

Returns an array:

```php
array($letter_grade, $gpa)
```

Rules:

| Marks | Return |
| --- | --- |
| `>= 80` | `A+`, `5.00` |
| `>= 70` | `A`, `4.00` |
| `>= 60` | `A-`, `3.50` |
| `>= 50` | `B`, `3.00` |
| `>= 40` | `C`, `2.00` |
| `>= 33` | `D`, `1.00` |
| `< 33` | `F`, `0.00` |

## 11. Extension Guidelines

### Add a new student field

1. Add the input in `render_student_fields_meta_box()`.
2. Add nonce/capability-safe save logic in `save_student_fields()`.
3. Sanitize the value before saving.
4. Add the meta key to `data-model.md` if documentation is maintained.
5. If the field should appear on the result sheet, update the AJAX result rendering.

### Add a new result search filter

1. Register or add the data field.
2. Add the field to the shortcode form.
3. Add front-end validation in `assets/js/ajax-search-result.js`.
4. Add server-side required field validation in `cbedu_handle_form_submission()`.
5. Add query logic to the `WP_Query` tax/meta query.
6. Update user documentation.

### Change the result sheet layout

The active AJAX output is generated inside `cbedu_handle_form_submission()` in the main plugin file. Front-end styling is in `assets/css/style.css`.

The file `inc/front-end/render-result-view.php` also contains a standalone result rendering template, but it uses a hardcoded `$post_id = 157`, so treat it as a legacy or reference template unless refactored.

## 12. Security Practices Already Used

The plugin uses several WordPress security practices:

- Direct access checks with `ABSPATH`.
- Nonces for settings, meta boxes, and AJAX requests.
- `sanitize_text_field()`, `sanitize_email()`, `sanitize_textarea_field()`, and `esc_url_raw()` for input.
- Output escaping with functions such as `esc_html()`, `esc_attr()`, and `esc_url()` in many places.
- Capability checks before saving meta box data.

## 13. Security Recommendations

Recommended future improvements:

- Do not register admin-only student lookup AJAX for unauthenticated visitors unless required.
- Add stricter capability checks in admin AJAX handlers.
- Standardize all namespace casing to match Composer PSR-4 autoloading.
- Avoid `die()` in settings nonce validation; use `wp_die()` with a translatable message.
- Ensure every `$_POST` access is guarded with `isset()` before sanitization.
- Consider returning JSON from all AJAX handlers and rendering templates client-side or through a dedicated PHP partial.

## 14. Known Technical Notes

- Composer maps `CBEDU\Inc\` to `inc/`, but many classes use lowercase `cbedu\inc\...` namespaces. This works when files are manually required, but can be confusing for strict autoloading.
- The plugin uses both manual `require_once` loading and Composer autoload.
- `inc/front-end/render-result-view.php` contains a hardcoded result post ID and should not be treated as a dynamic production template without refactoring.
- Some labels use `Collage` where `College` or `Institution` may be intended.
