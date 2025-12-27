# Import/Export System Architecture

> **Modular, Secure, and Extensible CSV Import/Export for WordPress Custom Post Types**

## 🎯 Overview

The Import/Export system provides a scalable solution for managing Students and Results data via CSV files. Built with WordPress best practices, it features AJAX-based operations, comprehensive security, and a modular architecture that makes adding new post types straightforward.

### Key Features

- ✅ **Modular Design** - Separate classes for each post type
- ✅ **AJAX-Based** - Seamless UX without page reloads
- ✅ **Secure** - Multi-layer security (nonces, capabilities, file validation, sanitization)
- ✅ **Auto-Creation** - Automatically creates missing subjects during Results import
- ✅ **Extensible** - Add new post types in ~30 minutes
- ✅ **User-Friendly** - Drag-and-drop, SweetAlert2 notifications, detailed error reporting

---

## 📐 Architecture Principles

### 1. Separation of Concerns
Each post type has its own handler class, keeping code organized and maintainable.

### 2. Single Responsibility
Each class manages only one post type's import/export logic.

### 3. Coordinator Pattern
The main `ImportExport` class orchestrates all handlers without containing business logic.

### 4. Scalability
Adding a new post type requires minimal changes to existing code.

---

## 🏗️ System Structure

### Backend (PHP)

```
Admin/Dashboard/Settings/
├── ImportExport.php                    # Main coordinator
└── ImportExport/
    ├── StudentsImportExport.php        # Students handler
    └── ResultsImportExport.php         # Results handler
```

### Frontend (JavaScript)

```
Admin/Assets/js/
├── import-export.js                    # Main coordinator
└── import-export/
    ├── students-import-export.js       # Students AJAX handler
    └── results-import-export.js        # Results AJAX handler
```

### Assets

```
Admin/Assets/
├── css/
│   └── admin-import-export.css         # Import/Export styles
└── js/
    └── (SweetAlert2 via CDN)           # User notifications
```

---

## 🔄 Data Flow

### Export Flow

```
User Click → AJAX Request → Security Check → Fetch Data → Generate CSV → Download
```

**Detailed Steps:**
1. User clicks "Export to CSV"
2. JavaScript sends AJAX request with nonce
3. PHP verifies nonce and capability
4. Fetch posts with meta fields and taxonomies
5. Generate CSV string with proper encoding
6. Return JSON response with CSV data
7. JavaScript creates Blob and triggers download

### Import Flow

```
File Upload → Validation → Parse CSV → Process Rows → Update/Create → Feedback
```

**Detailed Steps:**
1. User selects/drops CSV file
2. JavaScript sends AJAX request with FormData
3. PHP validates file (extension, MIME, size)
4. Parse and validate CSV headers
5. Process each row:
   - Check if record exists (by unique identifier)
   - Update existing or create new post
   - Update meta fields and taxonomies
   - For Results: auto-create missing subjects
6. Return success/error statistics
7. JavaScript displays results via SweetAlert2

---

## 📦 Class Structure

### ImportExport (Coordinator)

**File:** `Admin/Dashboard/Settings/ImportExport.php`

**Purpose:** Orchestrates Students and Results handlers

```php
class ImportExport
{
    private $students_handler;
    private $results_handler;

    public function __construct()
    {
        $this->students_handler = new StudentsImportExport();
        $this->results_handler = new ResultsImportExport();
    }

    public function render_import_export_tab()
    {
        // Renders unified UI with both sections
    }
}
```

---

### StudentsImportExport

**File:** `Admin/Dashboard/Settings/ImportExport/StudentsImportExport.php`

**Data Structure:**
- **Post Type:** `cbedu_students`
- **Meta Fields:** 17 custom fields (ID, registration, names, contact info, etc.)
- **Taxonomies:** 4 (session years, examinations, boards, department/group)
- **Unique Identifier:** Registration Number

**Key Methods:**
```php
public function ajax_export_students()      // Handles export AJAX
public function ajax_import_students()      // Handles import AJAX
private function get_student_by_registration_number($reg)
public function render_section()            // Renders UI
```

**AJAX Actions:**
- `wp_ajax_cbedu_export_students`
- `wp_ajax_cbedu_import_students`

**Security:**
- Nonce: `cbedu_export_students_nonce`, `cbedu_import_students_nonce`
- Capability: `manage_options`

---

### ResultsImportExport

**File:** `Admin/Dashboard/Settings/ImportExport/ResultsImportExport.php`

**Data Structure:**
- **Post Type:** `cbedu_results`
- **Meta Fields:** 6 custom fields (registration, roll, student type, result status, GPA, was GPA)
- **Subjects:** Stored as serialized array in `cbedu_subjects_results` meta
- **Taxonomies:** 4 (same as Students)
- **Unique Identifier:** Registration Number + Roll Number

**Key Methods:**
```php
public function ajax_export_results()       // Handles export AJAX
public function ajax_import_results()       // Handles import AJAX
private function get_result_by_registration_and_roll($reg, $roll)
private function ensure_subject_exists($name) // Auto-creates subjects
public function render_section()            // Renders UI
```

**AJAX Actions:**
- `wp_ajax_cbedu_export_results`
- `wp_ajax_cbedu_import_results`

**Special Feature:** Automatically creates missing subjects during import

**Subjects Format:**
- **Storage:** Serialized array `[['subject_name' => 'Math', 'subject_value' => '80'], ...]`
- **CSV Format:** `Math:80|English:89|Bangla:98`

---

## 🔒 Security Architecture

### 5-Layer Security Model

#### Layer 1: Authentication (Nonces)
```php
check_ajax_referer('cbedu_export_students_nonce', 'nonce');
```

#### Layer 2: Authorization (Capabilities)
```php
if (!current_user_can('manage_options')) {
    wp_send_json_error(...);
}
```

#### Layer 3: File Validation
- **Extension:** `.csv` only
- **MIME Type:** 7 allowed types validated via `finfo_file()`
- **Size:** 10MB maximum

#### Layer 4: Data Sanitization
- Email: `sanitize_email()`
- Textarea: `sanitize_textarea_field()`
- Text: `sanitize_text_field()`

#### Layer 5: Input Validation
- Required fields check
- Header validation with BOM removal
- Empty row handling

**Security Score:** ✅ 16/16 checks passed (100%)

---

## 📝 CSV Format

### Students CSV

```csv
student_title,cbedu_result_std_id,cbedu_result_std_registration_number,...
John Doe,123,REG001,...
```

### Results CSV

```csv
result_title,cbedu_result_std_registration_number,cbedu_result_std_roll,...,subjects
Result 2024,REG001,001,...,Math:80|English:89|Bangla:98
```

**Format Rules:**
- **Taxonomies:** Use pipe `|` to separate multiple terms (`Session 2024|Session 2023`)
- **Subjects:** Use `SubjectName:Mark` pairs separated by pipes (`Math:80|English:89`)

---

## 🚀 Extension Guide

### Adding a New Post Type (Example: Subjects)

#### Step 1: Create Handler Class

**File:** `Admin/Dashboard/Settings/ImportExport/SubjectsImportExport.php`

```php
<?php
namespace CBEDU\Admin\Dashboard\Settings\ImportExport;

if (!defined('ABSPATH')) exit;

class SubjectsImportExport
{
    private $post_type = 'cbedu_subjects';
    private $meta_fields = ['cbedu_subject_code'];
    private $taxonomies = [];

    public function __construct()
    {
        $this->register_ajax_handlers();
    }

    public function register_ajax_handlers()
    {
        add_action('wp_ajax_cbedu_export_subjects', [$this, 'ajax_export_subjects']);
        add_action('wp_ajax_cbedu_import_subjects', [$this, 'ajax_import_subjects']);
    }

    public function ajax_export_subjects()
    {
        check_ajax_referer('cbedu_export_subjects_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied', 'edu-results')]);
        }
        // Export logic (follow StudentsImportExport pattern)
    }

    public function ajax_import_subjects()
    {
        check_ajax_referer('cbedu_import_subjects_nonce', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied', 'edu-results')]);
        }
        // Import logic (follow StudentsImportExport pattern)
    }

    public function render_section()
    {
        // UI rendering (follow StudentsImportExport pattern)
    }
}
```

#### Step 2: Update Coordinator

**File:** `Admin/Dashboard/Settings/ImportExport.php`

```php
use CBEDU\Admin\Dashboard\Settings\ImportExport\SubjectsImportExport;

class ImportExport
{
    private $subjects_handler;

    public function __construct()
    {
        // Existing handlers...
        $this->subjects_handler = new SubjectsImportExport();
    }

    public function render_import_export_tab()
    {
        // After Results section, add:
        ?>
        <div class="cbedu-ie-separator">
            <div class="cbedu-ie-separator-line"></div>
        </div>
        
        <div class="cbedu-ie-section">
            <div class="cbedu-ie-section-header">
                <h2>📚 Subjects Data Management</h2>
                <p>Import and export subject records</p>
            </div>
            <div class="cbedu-ie-grid">
                <?php $this->subjects_handler->render_section(); ?>
            </div>
        </div>
        <?php
    }
}
```

#### Step 3: Create JavaScript Module

**File:** `Admin/Assets/js/import-export/subjects-import-export.js`

```javascript
function initSubjectsImportExport() {
    (function($) {
        'use strict';

        const exportForm = $('.cbedu-subjects-export-form');
        const importForm = $('.cbedu-subjects-import-form');

        exportForm.on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: cbeduImportExport.ajaxurl,
                type: 'POST',
                data: {
                    action: 'cbedu_export_subjects',
                    nonce: cbeduImportExport.exportSubjectsNonce
                },
                // Handle response...
            });
        });

        // Import handler (follow students-import-export.js pattern)
    })(jQuery);
}
```

#### Step 4: Update Assets

**File:** `Admin/Assets/Assets.php`

```php
// Enqueue Subjects module
wp_enqueue_script(
    'cbedu-subjects-import-export',
    CBEDU_ADMIN_ASSETS_URL . '/js/import-export/subjects-import-export.js',
    array('jquery', 'sweetalert2'),
    CBEDU_VERSION,
    true
);

// Update coordinator dependencies
wp_enqueue_script(
    'cbedu-import-export',
    CBEDU_ADMIN_ASSETS_URL . '/js/import-export.js',
    array('jquery', 'cbedu-students-import-export', 'cbedu-results-import-export', 'cbedu-subjects-import-export'),
    CBEDU_VERSION,
    true
);

// Add nonces
wp_localize_script('cbedu-import-export', 'cbeduImportExport', array(
    // Existing nonces...
    'exportSubjectsNonce' => wp_create_nonce('cbedu_export_subjects_nonce'),
    'importSubjectsNonce' => wp_create_nonce('cbedu_import_subjects_nonce'),
));
```

#### Step 5: Update Main Coordinator

**File:** `Admin/Assets/js/import-export.js`

```javascript
$(document).ready(function() {
    if (typeof initStudentsImportExport === 'function') {
        initStudentsImportExport();
    }
    if (typeof initResultsImportExport === 'function') {
        initResultsImportExport();
    }
    if (typeof initSubjectsImportExport === 'function') {
        initSubjectsImportExport(); // Add this
    }
});
```

**Total Time:** ~30 minutes for experienced developers

---

## 💡 Best Practices

### 1. Naming Conventions
- **Classes:** `{PostType}ImportExport` (e.g., `StudentsImportExport`)
- **AJAX Actions:** `cbedu_export_{posttype}`, `cbedu_import_{posttype}`
- **Nonces:** `cbedu_export_{posttype}_nonce`, `cbedu_import_{posttype}_nonce`

### 2. Unique Identifiers
Define clear unique identifiers for each post type:
- **Students:** Registration Number
- **Results:** Registration Number + Roll
- **Subjects:** Subject Code

### 3. Error Messages
Provide detailed, actionable error messages:
```php
$error_messages[] = sprintf(
    __('Row %d: Missing required field "%s"', 'edu-results'),
    $row_number,
    $field_name
);
```

### 4. Consistent Response Format
```php
// Success
wp_send_json_success([
    'imported' => $imported,
    'updated' => $updated,
    'errors' => $errors,
    'error_messages' => $error_messages
]);

// Error
wp_send_json_error([
    'message' => __('Error description', 'edu-results')
]);
```

---

## ⚡ Performance Considerations

### Current Implementation
- **File Size Limit:** 10MB
- **Processing:** Row-by-row (no batching)
- **Memory:** Uses `php://temp` for CSV generation

### Optimization Opportunities
1. **Batch Inserts:** For large imports (1000+ rows)
2. **Background Processing:** For very large files
3. **Progress Tracking:** Real-time progress updates

### Server Requirements
- `upload_max_filesize`: ≥ 10MB
- `post_max_size`: ≥ 10MB
- `max_execution_time`: ≥ 60 seconds (for large imports)
- `memory_limit`: ≥ 128MB

---

## 🧪 Testing Checklist

### Security Tests
- [x] Nonce verification
- [x] Capability checks
- [x] File extension validation
- [x] MIME type validation
- [x] File size validation
- [x] Data sanitization

### Functional Tests
- [x] Export with no records
- [x] Export with records
- [x] Import valid CSV
- [x] Import with missing headers
- [x] Import with invalid file
- [x] Update existing records
- [x] Create new records
- [x] Auto-create subjects (Results)

### UI/UX Tests
- [x] Drag-and-drop
- [x] SweetAlert2 notifications
- [x] Form reset after import
- [x] CSV download
- [x] Error display

---

## 🐛 Troubleshooting

### Common Issues

**Import not working**
- Check nonce names match between PHP and JavaScript
- Verify AJAX action names are registered correctly

**File upload fails**
- Check `php.ini`: `upload_max_filesize`, `post_max_size`
- Verify file permissions on upload directory

**Memory errors**
- Increase `memory_limit` in `php.ini`
- Consider processing smaller batches

**Timeout errors**
- Increase `max_execution_time` in `php.ini`
- Consider background processing for large files

---

## 📚 References

- [WordPress AJAX](https://developer.wordpress.org/plugins/javascript/ajax/)
- [WordPress Nonces](https://developer.wordpress.org/apis/security/nonces/)
- [Data Validation](https://developer.wordpress.org/apis/security/data-validation/)
- [Sanitizing Data](https://developer.wordpress.org/apis/security/sanitizing-data/)

---

## 📄 License

This project is part of the Edu Results Publishing plugin.

---

**Maintained By:** Development Team  
**Last Updated:** 2025-12-27  
**Version:** 1.0.0
