# Build, Development, and Release Guide

This guide explains how to work on EDU Results Publishing as a developer or maintainer.

## 1. Development Requirements

Recommended local tools:

- PHP 7.2 or higher
- WordPress local development environment
- Composer
- WP-CLI
- Node is not required by the current plugin package

The plugin includes Composer configuration for development tooling.

## 2. Composer Configuration

File:

```text
composer.json
```

Package name:

```text
hmbashar/edu-results-publishing
```

License:

```text
GPL-2.0-or-later
```

Version:

```text
1.2.0
```

Autoload mapping:

```json
{
  "psr-4": {
    "CBEDU\\Inc\\": "inc/"
  }
}
```

Development dependencies declared:

- `wp-coding-standards/wpcs`
- `wp-cli/i18n-command`
- `phpcompatibility/phpcompatibility-wp`

## 3. Composer Scripts

| Script | Command | Purpose |
| --- | --- | --- |
| `lint:wpcs` | `@php ./vendor/squizlabs/php_codesniffer/bin/phpcs` | Run WordPress Coding Standards linting. |
| `lint:php` | `@php ./vendor/bin/parallel-lint --exclude .git --exclude vendor .` | Run PHP syntax linting. |
| `make-pot` | `wp i18n make-pot . languages/edu-results-publishing.pot` | Generate translation template. |
| `lint:autofix` | `vendor/bin/phpcbf` | Auto-fix coding standard issues where possible. |

## 4. Install Development Dependencies

From the plugin root:

```bash
composer install
```

For production packaging, avoid shipping unnecessary development dependencies unless intentionally bundled.

## 5. Run PHP Syntax Checks

```bash
composer run lint:php
```

If `parallel-lint` is not installed, install the missing dependency or run PHP lint manually:

```bash
find . -name '*.php' -not -path './vendor/*' -print0 | xargs -0 -n1 php -l
```

## 6. Run WordPress Coding Standards

```bash
composer run lint:wpcs
```

If WPCS is not configured globally, ensure the Composer dependencies are installed correctly.

## 7. Generate Translation Template

```bash
composer run make-pot
```

This updates:

```text
languages/edu-results-publishing.pot
```

The plugin also includes Bangla translation files:

```text
languages/bn_BD.po
languages/bn_BD.mo
languages/bn_BD.pot
```

## 8. Manual QA Checklist

Before release, test the following in WordPress admin:

- Plugin activates without fatal errors.
- EDU Results menu appears.
- Settings page loads and saves.
- Logo upload button opens media uploader.
- Session Years can be created.
- Examinations can be created.
- Boards can be created.
- Departments/Groups can be created.
- Subjects can be created with subject codes.
- Students can be created with registration numbers.
- Duplicate student registration numbers are handled correctly.
- Result editor loads registration autocomplete.
- Student name/father/mother fields auto-fill from registration number.
- Result subject repeater can add and remove rows.
- Result saves without losing subject rows.
- Result title updates correctly after saving.
- Front-end `[cbedu_search_form]` renders.
- Front-end form validation works.
- Matching result search returns the correct result sheet.
- Non-matching result search shows a useful message.
- Print button/function works.
- Translation strings still load.

## 9. Packaging Checklist

Before creating a release ZIP:

1. Update version in plugin header.
2. Update `CBEDU_VERSION` constant.
3. Update `composer.json` version.
4. Update `readme.txt` changelog.
5. Regenerate `.pot` file if strings changed.
6. Run linting.
7. Test activation and key workflows.
8. Remove development-only files if not intended for distribution.
9. Ensure `vendor/autoload.php` exists if the release expects bundled Composer autoloading.
10. Create the ZIP with the root folder name `edu-results-publishing`.

Recommended ZIP structure:

```text
edu-results-publishing.zip
└── edu-results-publishing/
    ├── edu-results-publishing.php
    ├── inc/
    ├── assets/
    ├── languages/
    ├── vendor/
    ├── readme.txt
    └── composer.json
```

## 10. Release Notes Template

```markdown
# EDU Results Publishing x.y.z

## Added
- ...

## Changed
- ...

## Fixed
- ...

## Developer Notes
- ...

## Upgrade Notes
- ...
```

## 11. Version Consistency

Keep these values aligned:

| Location | Current Value |
| --- | --- |
| Plugin header `Version` | `1.2.0` |
| `CBEDU_VERSION` | `1.2.0` |
| `composer.json` version | `1.2.0` |
| `readme.txt` changelog latest section | `1.2.0` |

## 12. Architecture Improvement Recommendations

For future releases:

- Standardize namespace casing between Composer and PHP class declarations.
- Move AJAX result HTML into a dedicated template partial.
- Remove or refactor hardcoded `$post_id = 157` in `inc/front-end/render-result-view.php`.
- Use result subject post IDs instead of subject names in repeater data.
- Add REST API endpoints only if needed and secured.
- Add automated tests for grade conversion, result query building, and meta save behavior.
- Add admin import/export if institutions need bulk data entry.
