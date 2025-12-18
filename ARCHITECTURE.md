# EDU Results Publishing - PSR-4 Architecture

## Version 1.3.0 - Professional PSR-4 Structure (CBEDU Namespace)

### Overview
This plugin follows professional PSR-4 autoloading standards with the **CBEDU** namespace. All classes are autoloaded via Composer - **no require/include statements** for PSR-4 classes.

---

## Directory Structure

```
edu-results-publishing/
├── src/                          # PSR-4 Autoloaded (CBEDU namespace)
│   ├── Admin/
│   │   └── AdminManager.php      # ✅ All admin functionality
│   ├── Core/
│   │   ├── PostTypes/
│   │   │   └── PostTypesManager.php
│   │   ├── Taxonomies/
│   │   │   └── TaxonomiesManager.php
│   │   └── Loader.php
│   ├── Frontend/
│   │   └── PublicManager.php     # ✅ All frontend functionality
│   ├── Manager.php                # ✅ Central component manager
│   ├── Activate.php               # ✅ Activation handler
│   └── Deactivate.php             # ✅ Deactivation handler
│
├── assets/
│   ├── admin/                     # Admin-only assets
│   │   ├── css/
│   │   └── js/
│   └── public/                    # Frontend assets
│       ├── css/
│       ├── js/
│       └── img/
│
├── inc/                           # Legacy (backward compatibility)
├── vendor/                        # Composer (autoloader)
├── composer.json                  # PSR-4: "CBEDU\\": "src/"
└── edu-results-publishing.php     # Main file (singleton pattern)
```

---

## PSR-4 Namespace Structure

```php
CBEDU\                            // Root namespace (CBEDU_ prefix)
├── CBEDUResultsPublishing        // Main singleton class
├── Manager                       // Component initializer
├── Activate                      // Activation handler
├── Deactivate                    // Deactivation handler
├── Admin\
│   └── AdminManager              // All admin functionality
├── Core\
│   ├── Loader                    // Core loader
│   ├── PostTypes\
│   │   └── PostTypesManager
│   └── Taxonomies\
│       └── TaxonomiesManager
└── Frontend\
    └── PublicManager             // All frontend functionality
```

---

## Main Plugin Flow

### 1. Main File (edu-results-publishing.php)
```php
namespace CBEDU;

final class CBEDUResultsPublishing {
    // Singleton pattern
    public static function get_instance() { ... }
    
    // Define constants
    private function define_constants() {
        define('CBEDU_VERSION', '1.3.0');
        define('CBEDU_PATH', ...);
        define('CBEDU_URL', ...);
        define('CBEDU_PREFIX', 'cbedu_');
        // etc.
    }
    
    // Load composer autoloader
    private function include_files() {
        require_once CBEDU_PATH . 'vendor/autoload.php';
    }
    
    // Initialize on plugins_loaded
    public function plugin_loaded() {
        new \CBEDU\Manager();
    }
}

cbedu_initialize(); // Start plugin
```

### 2. Manager Class
```php
namespace CBEDU;

use CBEDU\Admin\AdminManager;
use CBEDU\Core\Loader;
use CBEDU\Frontend\PublicManager;

class Manager {
    public function init() {
        // Core loader (post types, taxonomies)
        $this->loader = new Loader(...);
        
        // Admin components (if admin)
        if (is_admin()) {
            $this->admin_manager = new AdminManager();
        }
        
        // Frontend components (if frontend)
        if (!is_admin()) {
            $this->public_manager = new PublicManager();
        }
    }
}
```

### 3. Component Managers

#### AdminManager
```php
namespace CBEDU\Admin;

class AdminManager {
    // - Enqueue admin assets
    // - Plugin action links
    // - Title placeholders
    // - Post publish messages
    // - Load legacy admin files
}
```

#### PublicManager
```php
namespace CBEDU\Frontend;

class PublicManager {
    // - Enqueue frontend assets
    // - Load legacy frontend files (shortcodes)
}
```

---

## Constants

```php
CBEDU_VERSION       // '1.3.0'
CBEDU_PATH          // Plugin directory path
CBEDU_URL           // Plugin URL
CBEDU_FILE          // Main plugin file
CBEDU_BASENAME      // plugin_basename()
CBEDU_NAME          // 'EDU Results Publishing'
CBEDU_PREFIX        // 'cbedu_' (database prefix)
```

---

## PSR-4 Autoloading

### Composer Configuration
```json
{
    "autoload": {
        "psr-4": {
            "CBEDU\\": "src/"
        }
    }
}
```

### How It Works
```php
// Old way (manual loading)
require_once 'inc/some-file.php';
new SomeClass();

// New way (PSR-4 autoloaded)
use CBEDU\Admin\AdminManager;
new AdminManager(); // Automatically loaded!
```

### After Adding New Classes
```bash
composer dump-autoload -o
```

---

## Key Features

### ✅ No Manual Loading
- All `src/` classes loaded automatically
- No `require_once` or `include` for PSR-4 classes
- Follows PHP-FIG standards

### ✅ Singleton Pattern
```php
$plugin = \CBEDU\CBEDUResultsPublishing::get_instance();
```

### ✅ Separation of Concerns
- **Core**: Post types, taxonomies
- **Admin**: Admin-only functionality
- **Frontend**: Public-facing functionality

### ✅ Backward Compatibility
- Legacy files still work
- Old constants maintained
- No breaking changes

---

## Adding New Components

### Example: Add New Admin Component
```php
// File: src/Admin/MetaBoxes/StudentMetaBox.php
namespace CBEDU\Admin\MetaBoxes;

class StudentMetaBox {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'register']);
    }
    
    public function register() {
        // Register meta box
    }
}
```

```php
// In AdminManager.php
use CBEDU\Admin\MetaBoxes\StudentMetaBox;

private function load_admin_components() {
    new StudentMetaBox(); // Auto-loaded!
}
```

### No Need For:
- ❌ `require_once`
- ❌ `include`
- ❌ Manual file paths

Just use the class - **PSR-4 handles it**!

---

## Migration from EduResults

### What Changed
| Old | New |
|-----|-----|
| `EduResults\` namespace | `CBEDU\` namespace |
| `EDU_RESULTS_*` constants | `CBEDU_*` constants |
| `EduResults\Plugin` | `CBEDU\CBEDUResultsPublishing` |
| Manual require statements | PSR-4 autoloading |

### Backward Compatibility
All existing functionality maintained:
- ✅ Custom post types
- ✅ Taxonomies  
- ✅ Meta boxes
- ✅ AJAX
- ✅ Shortcodes
- ✅ Settings
- ✅ Assets

---

## File Loading Strategy

### PSR-4 Classes (src/)
```php
// Automatically loaded by composer
use CBEDU\Admin\AdminManager;
new AdminManager();
```

### Legacy Classes (inc/)
```php
// Loaded manually in component managers
require_once CBEDU_PATH . 'inc/custom-fields.php';
new \cbedu\inc\custom_fields\CBEDUCustomFields();
```

---

## Benefits

### 🚀 Performance
- Optimized autoloader (`composer dump-autoload -o`)
- Classes loaded only when needed
- No unnecessary file parsing

### 📦 Maintainability  
- Clear structure
- Easy to find classes
- Standard conventions

### 🔌 Extensibility
- Add new classes without modifying core
- Follow namespacing conventions
- Drop files in `src/` and they work

### 🛡️ Standards Compliance
- PSR-4 (autoloading)
- WordPress Coding Standards
- PHP-FIG recommendations

---

## Testing Checklist

- [x] Plugin activates
- [x] Post types registered
- [x] Taxonomies registered
- [x] Admin assets load
- [x] Frontend assets load
- [x] Settings page works
- [x] Meta boxes display
- [x] AJAX functions work
- [x] Shortcodes render
- [x] Composer autoloader optimized

---

## Version History

### Version 1.3.0 (Current)
- ✅ Full CBEDU namespace
- ✅ CBEDU_ prefix (all caps)
- ✅ Complete PSR-4 autoloading
- ✅ No require/include for PSR-4 classes
- ✅ REVIX-pattern architecture
- ✅ Singleton pattern
- ✅ Separate Admin/Frontend managers
- ✅ Activation/Deactivation handlers
- ✅ Optimized composer autoloader

---

## Support

- **GitHub**: https://github.com/hmbashar/edu-results-publishing
- **Author**: MD Abul Bashar

---

## License

GPLv2 or later

---

## New Directory Structure

```
edu-results-publishing/
├── src/                          # PSR-4 Autoloaded Classes
│   ├── Admin/                    # Admin-specific functionality
│   │   ├── MetaBoxes/           # Custom meta boxes (future)
│   │   ├── Settings/            # Plugin settings (future)
│   │   └── Ajax/                # Admin AJAX handlers (future)
│   ├── Core/                     # Core plugin functionality
│   │   ├── PostTypes/
│   │   │   └── PostTypesManager.php     # ✅ NEW: Manages all custom post types
│   │   ├── Taxonomies/
│   │   │   └── TaxonomiesManager.php    # ✅ NEW: Manages all taxonomies
│   │   └── Loader.php                    # ✅ NEW: Core component loader
│   ├── Frontend/                 # Frontend-specific functionality
│   │   ├── Shortcodes/          # Shortcode handlers (future)
│   │   └── Templates/           # Template files (future)
│   ├── Includes/                 # Shared/utility classes
│   │   └── Helpers/             # Helper functions (future)
│   └── Plugin.php                # ✅ NEW: Main plugin class
│
├── assets/                       # Asset files (reorganized)
│   ├── admin/                    # ✅ NEW: Admin-only assets
│   │   ├── css/
│   │   │   ├── admin-meta-fields.css
│   │   │   └── admin-settings.css
│   │   └── js/
│   │       └── admin.js
│   ├── public/                   # ✅ NEW: Frontend assets
│   │   ├── css/
│   │   │   ├── style.css
│   │   │   └── autocomplete.css
│   │   ├── js/
│   │   │   ├── ajax-search-result.js
│   │   │   ├── autocomplete.js
│   │   │   └── print.js
│   │   └── img/                 # Images
│   └── css/                      # Legacy (for backward compatibility)
│       └── js/
│
├── inc/                          # Legacy classes (maintained for compatibility)
│   ├── admin/
│   │   └── settings.php         # Settings page
│   ├── front-end/
│   │   └── render-result-view.php
│   ├── lib/
│   │   ├── custom-functions.php
│   │   ├── custom-posts.php     # ⚠️ Will be deprecated
│   │   ├── custom-taxonomy.php  # ⚠️ Will be deprecated
│   │   └── shortcode.php
│   ├── custom-fields.php
│   └── RepeaterCF.php
│
├── languages/                    # Translation files
├── vendor/                       # Composer dependencies
├── composer.json                 # ✅ UPDATED: PSR-4 autoloading
├── edu-results-publishing.php    # ✅ UPDATED: Clean main file
└── README.md                     # This file
```

---

## Namespace Structure

### New PSR-4 Namespaces

```php
EduResults\                       // Root namespace
├── Plugin                        // Main plugin class
├── Core\
│   ├── Loader                    // Component loader
│   ├── PostTypes\
│   │   └── PostTypesManager      // Post types registration
│   └── Taxonomies\
│       └── TaxonomiesManager     // Taxonomies registration
├── Admin\                        // Admin components (future)
├── Frontend\                     // Frontend components (future)
└── Includes\                     // Shared utilities (future)
```

### Legacy Namespaces (Backward Compatibility)

```php
cbedu\inc\lib\
├── CBEDU_CUSTOM_POSTS           // ⚠️ Deprecated - Use EduResults\Core\PostTypes\PostTypesManager
├── CBEDU_CUSTOM_TAXONOMY        // ⚠️ Deprecated - Use EduResults\Core\Taxonomies\TaxonomiesManager
├── CBEDUResultsShortcode
└── CBEDUCustomFunctions\

cbedu\inc\custom_fields\
└── CBEDUCustomFields

cbedu\inc\admin\settings\
└── CBEDUResultSettings

cbedu\inc\RepeaterCF\
└── CBEDURepeaterCustomFields
```

---

## Key Improvements

### 1. **PSR-4 Autoloading**
- Composer autoloader handles all new classes automatically
- No more manual `require_once` statements for new classes
- Follows PHP-FIG standards

### 2. **Separation of Concerns**
```php
// Old way (all in one file)
class CBEDUResultPublishing {
    // 600+ lines mixing everything
}

// New way (clean separation)
class Plugin {
    // Initialization only
    private $loader;
}

class Loader {
    // Component loading logic
}

class PostTypesManager {
    // Only post type registration
}

class TaxonomiesManager {
    // Only taxonomy registration
}
```

### 3. **Asset Organization**
```
Old: assets/css/admin-meta-fields.css
New: assets/admin/css/admin-meta-fields.css

Old: assets/css/style.css
New: assets/public/css/style.css
```

### 4. **Singleton Pattern**
```php
// Ensures only one instance of the plugin
$plugin = \EduResults\Plugin::getInstance(
    EDU_RESULTS_PREFIX,
    EDU_RESULTS_VERSION,
    EDU_RESULTS_URL,
    EDU_RESULTS_DIR
);
```

### 5. **Proper Documentation**
- PHPDoc blocks for all classes and methods
- Type hints where applicable
- Clear method/property visibility

---

## Migration Guide

### For Developers

#### Old Class Instantiation
```php
// Old way
require_once 'inc/lib/custom-posts.php';
new \cbedu\inc\lib\CBEDU_CUSTOM_POSTS($prefix);
```

#### New Class Instantiation
```php
// New way - no require needed
use EduResults\Core\PostTypes\PostTypesManager;
new PostTypesManager($prefix);
```

### For Extensibility

#### Adding New Post Types
```php
// File: src/Core/PostTypes/PostTypesManager.php
private function registerMyNewPostType() {
    // Register post type
}

// Add to registerPostTypes():
public function registerPostTypes() {
    $this->registerResultsPostType();
    $this->registerSubjectsPostType();
    $this->registerStudentsPostType();
    $this->registerMyNewPostType(); // Add here
}
```

---

## Constants

### New Constants (v1.3.0+)
```php
EDU_RESULTS_VERSION   // Plugin version
EDU_RESULTS_PREFIX    // Plugin prefix (cbedu_)
EDU_RESULTS_URL       // Plugin URL
EDU_RESULTS_DIR       // Plugin directory path
EDU_RESULTS_FILE      // Main plugin file
```

### Legacy Constants (Maintained for compatibility)
```php
CBEDU_VERSION         // Alias for EDU_RESULTS_VERSION
CBEDU_PREFIX          // Alias for EDU_RESULTS_PREFIX
CBEDU_RESULT_URL      // Alias for EDU_RESULTS_URL
CBEDU_RESULT_DIR      // Alias for EDU_RESULTS_DIR
```

---

## Composer Configuration

```json
{
    "autoload": {
        "psr-4": {
            "EduResults\\": "src/"
        }
    }
}
```

After adding new classes, run:
```bash
composer dump-autoload
```

---

## Class Responsibilities

| Class | Responsibility | Status |
|-------|---------------|--------|
| `Plugin` | Main plugin initialization & hooks | ✅ NEW |
| `Core\Loader` | Load all plugin components | ✅ NEW |
| `Core\PostTypes\PostTypesManager` | Register post types | ✅ NEW |
| `Core\Taxonomies\TaxonomiesManager` | Register taxonomies | ✅ NEW |
| Legacy classes | Backward compatibility | ⚠️ Maintained |

---

## Backward Compatibility

All existing functionality is maintained:
- ✅ Custom post types (Results, Students, Subjects)
- ✅ Taxonomies (Sessions, Examinations, Boards, Departments)
- ✅ Meta boxes and custom fields
- ✅ AJAX search functionality
- ✅ Shortcodes ([cbedu_search_form])
- ✅ Admin settings page
- ✅ Frontend result display
- ✅ All CSS/JS assets

---

## Next Steps (Future Roadmap)

### Phase 2: Admin Components
- [ ] Move `inc/admin/settings.php` → `src/Admin/Settings/SettingsManager.php`
- [ ] Move `inc/custom-fields.php` → `src/Admin/MetaBoxes/`
- [ ] Move `inc/RepeaterCF.php` → `src/Admin/MetaBoxes/RepeaterFields.php`

### Phase 3: Frontend Components
- [ ] Move `inc/lib/shortcode.php` → `src/Frontend/Shortcodes/SearchFormShortcode.php`
- [ ] Move `inc/front-end/render-result-view.php` → `src/Frontend/Templates/`

### Phase 4: Utilities
- [ ] Move `inc/lib/custom-functions.php` → `src/Includes/Helpers/Functions.php`

### Phase 5: Remove Legacy
- [ ] Deprecate old namespaces completely
- [ ] Remove `inc/lib/custom-posts.php` (replaced by PostTypesManager)
- [ ] Remove `inc/lib/custom-taxonomy.php` (replaced by TaxonomiesManager)

---

## Testing Checklist

After restructure, verify:
- [x] Plugin activates without errors
- [x] Post types registered correctly
- [x] Taxonomies registered correctly  
- [ ] Meta boxes display correctly
- [ ] AJAX search works
- [ ] Shortcode renders
- [ ] Admin settings save
- [ ] Frontend CSS/JS loads
- [ ] Admin CSS/JS loads

---

## Version History

### Version 1.3.0 (Current)
- ✅ PSR-4 autoloading structure
- ✅ New namespace: `EduResults\`
- ✅ Reorganized assets (admin/public separation)
- ✅ Professional class architecture
- ✅ Core\PostTypes\PostTypesManager
- ✅ Core\Taxonomies\TaxonomiesManager
- ✅ Core\Loader
- ✅ Main Plugin class
- ✅ Composer autoloader integration
- ✅ Backward compatibility maintained

### Version 1.2.0 (Previous)
- Custom fields UI improvements
- AJAX functionality
- Modern CSS design
- Settings page

---

## Support

- GitHub: https://github.com/hmbashar/edu-results-publishing
- Author: MD Abul Bashar
- Email: Contact via GitHub

---

## License

GPLv2 or later
