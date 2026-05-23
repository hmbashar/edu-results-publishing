# Troubleshooting Guide

This guide covers common problems in EDU Results Publishing and how to fix them.

## 1. Plugin Does Not Activate

### Possible causes

- PHP version is lower than 7.2.
- Plugin files are incomplete.
- `vendor/autoload.php` is missing if required by the current package.
- A fatal PHP error exists in a modified file.

### Fixes

1. Check PHP version in hosting control panel or WordPress Site Health.
2. Re-upload the full plugin folder.
3. Confirm the main file exists:

```text
edu-results-publishing/edu-results-publishing.php
```

4. Enable WordPress debug logging temporarily and inspect `wp-content/debug.log`.

## 2. EDU Results Menu Is Missing

### Possible causes

- Plugin is not active.
- Current user does not have enough permissions.
- Admin menu conflict with another plugin.

### Fixes

1. Go to **Plugins** and confirm the plugin is active.
2. Log in as an administrator.
3. Check direct URL for results post type:

```text
/wp-admin/edit.php?post_type=cbedu_results
```

## 3. Settings Page Does Not Save

### Possible causes

- Security nonce failed.
- User does not have enough permissions.
- Server/security plugin blocks the request.

### Fixes

1. Refresh the settings page and try again.
2. Confirm you are logged in as an administrator.
3. Temporarily disable aggressive security/firewall rules and test again.
4. Check whether `admin-post.php` or `options.php` is blocked by server security.

## 4. Logo Upload Button Does Not Work

### Possible causes

- WordPress media scripts did not load.
- JavaScript error on settings page.
- Browser extension conflict.

### Fixes

1. Open browser developer tools and check the Console tab.
2. Confirm `wp_enqueue_media()` is called on admin pages.
3. Disable conflicting admin UI plugins temporarily.
4. Try a different browser.

## 5. Registration Number Autocomplete Does Not Work

### Possible causes

- No student records exist.
- Typed value is fewer than two characters.
- Student registration meta is missing.
- AJAX nonce failed.
- JavaScript error on result edit screen.

### Fixes

1. Create a student with a registration number.
2. Edit a Result record.
3. Type at least two characters in the registration number field.
4. Check browser Console and Network tabs.
5. Confirm the AJAX action `add_search_registration_numbers` returns data.

## 6. Student Name Does Not Auto-Fill in Result Editor

### Possible causes

- Registration number does not exactly match any student.
- Student record is not published.
- JavaScript did not load.
- AJAX nonce failed.

### Fixes

1. Copy the registration number from the student record exactly.
2. Paste it into the result registration number field.
3. Wait at least 500ms after typing because the script uses debounce.
4. Check that `assets/js/admin.js` is loaded.
5. Check the AJAX response for `get_student_details_by_registration`.

## 7. Result Search Page Shows Empty Dropdowns

### Possible causes

- No taxonomy terms have been created.
- Terms were created under the wrong taxonomy.
- Theme or plugin conflict affects shortcode output.

### Fixes

Create terms for:

- Session Years
- Examinations
- Boards
- Departments/Groups

Then reload the page containing:

```text
[cbedu_search_form]
```

## 8. Front-End Result Search Says Result Not Found

### Most common cause

The search requires all fields to match the result record. One mismatch is enough to return no result.

### Check these values

| Search Field | Must Match |
| --- | --- |
| Examination | Result's `cbedu_examinations` term slug. |
| Year | Result's `cbedu_session_years` term slug. |
| Board | Result's `cbedu_boards` term slug. |
| Department/Group | Result's `cbedu_department_group` term slug. |
| Registration Number | Result meta `cbedu_result_std_registration_number`. |
| Roll | Result meta `cbedu_result_std_roll`. |

### Fixes

1. Open the result record in admin.
2. Confirm the result is published.
3. Confirm all four taxonomy terms are selected.
4. Confirm registration and roll values.
5. Test again using exact values.

## 9. Result Saves but Subject Rows Disappear

### Possible causes

- Nonce failed.
- Current user cannot edit the result post.
- Empty subject names were submitted.
- JavaScript conflict removed input names.

### Fixes

1. Ensure each row has a selected subject.
2. Save as a user with edit permission.
3. Check browser Console for JavaScript errors.
4. Confirm inputs are named:

```text
cbedu_results_subject_name[]
cbedu_results_subject_value[]
```

## 10. Duplicate Student Registration Number Error

### Meaning

The plugin checks that student registration numbers should be unique.

### Fixes

1. Search Students for the registration number.
2. Edit the existing student instead of creating another.
3. If duplication was accidental, change or remove the duplicate registration number.

## 11. Result Title Is Not Expected

### Explanation

The result post title may be automatically updated based on the student registration number after saving.

### Fixes

1. Confirm the registration number belongs to the intended student.
2. Update the student title if the student name is wrong.
3. Resave the result.

## 12. Print Output Looks Wrong

### Possible causes

- Theme styles are not included in print window.
- The selected print element ID is wrong.
- Browser popup blocker prevents print window.

### Fixes

1. Allow popups for the site.
2. Confirm the printed element exists.
3. Extend `assets/js/print.js` print CSS if needed.

## 13. Bangla Translation Does Not Show

### Possible causes

- Site language is not Bangla/Bangladesh.
- Translation file names do not match WordPress loading rules.
- Text domain mismatch in some strings.

### Fixes

1. Set WordPress language to Bangla/Bangladesh if desired.
2. Confirm files exist in `languages/`.
3. Confirm plugin text domain is `edu-results`.
4. Regenerate `.pot` and update `.po/.mo` files if strings changed.

## 14. Developer Debug Checklist

Enable debugging in `wp-config.php` temporarily:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Then check:

```text
wp-content/debug.log
```

Also inspect:

- Browser Console tab
- Browser Network tab for admin-ajax.php requests
- WordPress post meta values
- Taxonomy term slugs
- Plugin asset URLs

## 15. Known Issues / Review Items

These are areas developers should review before major production deployment:

- `inc/front-end/render-result-view.php` uses a hardcoded `$post_id = 157`.
- Namespace casing is inconsistent between Composer config and class files.
- Some AJAX handlers are available to non-logged-in users even when used only in admin workflows.
- Result subject rows store subject names, not subject IDs, which can cause historical result display issues if subject names change later.
- Some labels say `Collage` instead of `College` or `Institution`.
