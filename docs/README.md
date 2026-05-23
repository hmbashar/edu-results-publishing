# EDU Results Publishing Documentation

This folder contains a full user and developer manual for the **EDU Results Publishing** WordPress plugin.

Plugin version reviewed: **1.2.0**  
Main plugin file: `edu-results-publishing.php`  
Text domain: `edu-results`  
Prefix: `cbedu_`

## Documentation Map

| File | Audience | Purpose |
| --- | --- | --- |
| [`user-manual.md`](user-manual.md) | Site admins, school/college staff, result publishers | How to install, configure, enter students/subjects/results, and publish searchable result sheets. |
| [`developer-guide.md`](developer-guide.md) | WordPress/PHP developers | How the plugin is structured, loaded, extended, and maintained. |
| [`data-model.md`](data-model.md) | Developers and technical admins | Custom post types, taxonomies, options, post meta keys, and AJAX data flow. |
| [`shortcodes-and-frontend.md`](shortcodes-and-frontend.md) | Site builders and developers | Shortcodes, front-end result search, AJAX behavior, print behavior, and template notes. |
| [`build-and-release.md`](build-and-release.md) | Developers and maintainers | Composer, development tooling, translation files, linting, packaging, and release checklist. |
| [`troubleshooting.md`](troubleshooting.md) | Admins and developers | Common problems, causes, fixes, and diagnostic steps. |

## Plugin Summary

EDU Results Publishing helps educational institutions publish examination results in WordPress. It provides:

- Student records with personal and guardian information.
- Subject records with subject codes.
- Result records linked to students by registration number and roll number.
- Session year, examination, board, and department/group taxonomies.
- Institution settings such as name, logo, contact information, website, and result banner heading.
- Front-end result search form using the `[cbedu_search_form]` shortcode.
- AJAX result lookup by registration number, roll, examination, year, board, and department/group.
- Printable result sheet layout.
- Bangla translation files.

## Recommended Reading Order

1. Start with [`user-manual.md`](user-manual.md) if you are installing or operating the plugin.
2. Read [`data-model.md`](data-model.md) before importing/migrating data or extending fields.
3. Read [`developer-guide.md`](developer-guide.md) before changing PHP, hooks, AJAX, assets, or architecture.
4. Use [`troubleshooting.md`](troubleshooting.md) when results do not show or admin fields do not behave as expected.

## Important Notes

- The primary front-end shortcode is `[cbedu_search_form]`.
- The plugin also registers `[cbedu_result_details]`, but the current included render file uses a hardcoded result post ID (`157`), so it should be reviewed before production use.
- Results are found only when all required search inputs match: examination, year/session, board, department/group, registration number, and roll number.
- Students should be created before results so result entries can auto-fill student information from registration number.
