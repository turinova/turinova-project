# Select2 UI Implementation Guide

## Table of Contents
1. [Overview](#overview)
2. [Setup & Configuration](#setup--configuration)
3. [Implementation Patterns](#implementation-patterns)
4. [Modal Integration](#modal-integration)
5. [Common Use Cases](#common-use-cases)
6. [Troubleshooting](#troubleshooting)
7. [Best Practices](#best-practices)
8. [Examples](#examples)

---

## Overview

Select2 is a jQuery-based replacement for select boxes that provides enhanced functionality including search, keyboard navigation, and better styling. In the ERP system, Select2 is used to improve user experience for dropdown selections, particularly in forms and modals.

### Key Features
- ✅ **Searchable dropdowns** - Type to filter options
- ✅ **Keyboard navigation** - Arrow keys and Enter to select
- ✅ **Enhanced styling** - Modern, professional appearance
- ✅ **Modal compatibility** - Proper rendering within Bootstrap modals
- ✅ **Responsive design** - Works on all screen sizes
- ✅ **Accessibility** - Screen reader and keyboard support

---

## Setup & Configuration

### 1. CSS & JS Files
The Select2 files are already included in the base layout (`erp-app/app/views/layout/base.php`):

```html
<!-- CSS -->
<link rel="stylesheet" href="/Turinova_project/erp-app/public/assets/assets/vendor/libs/select2/select2.css" />

<!-- JavaScript -->
<script src="/Turinova_project/erp-app/public/assets/assets/vendor/libs/select2/select2.js"></script>
```

### 2. jQuery Dependency
Select2 requires jQuery, which is loaded before Select2 in the base layout:

```html
<script src="/Turinova_project/erp-app/public/assets/assets/vendor/libs/jquery/jquery.js"></script>
<script src="/Turinova_project/erp-app/public/assets/assets/vendor/libs/select2/select2.js"></script>
```

### 3. Basic HTML Structure
```html
<select class="form-select select2" id="exampleSelect" name="example">
    <option value="">Válassz opciót...</option>
    <option value="1">Opció 1</option>
    <option value="2">Opció 2</option>
</select>
```

---

## Implementation Patterns

### 1. Basic Select2 Initialization
```javascript
// Simple initialization
$('#exampleSelect').select2({
    placeholder: 'Válassz opciót...',
    allowClear: true
});
```

### 2. Modal Integration Pattern
```javascript
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('exampleModal');
    if (modal) {
        // Initialize when modal opens
        modal.addEventListener('show.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#exampleSelect').select2({
                    placeholder: 'Válassz opciót...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#exampleModal')
                });
            }
        });

        // Clean up when modal closes
        modal.addEventListener('hidden.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#exampleSelect').select2('destroy');
            }
        });
    }
});
```

### 3. Dynamic Data Loading
```javascript
// Initialize with AJAX data
$('#dynamicSelect').select2({
    placeholder: 'Válassz opciót...',
    allowClear: true,
    ajax: {
        url: '/api/data',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                search: params.term,
                page: params.page
            };
        },
        processResults: function(data, params) {
            return {
                results: data.items,
                pagination: {
                    more: data.more
                }
            };
        }
    }
});
```

---

## Modal Integration

### Problem
Select2 dropdowns don't render properly inside Bootstrap modals due to z-index and positioning issues.

### Solution
Use the `dropdownParent` option to specify the modal container:

```javascript
$('#exampleSelect').select2({
    dropdownParent: $('#exampleModal')
});
```

### Complete Modal Pattern
```html
<!-- Modal -->
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <select class="form-select select2" id="exampleSelect">
                    <option value="">Válassz...</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('exampleModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#exampleSelect').select2({
                    placeholder: 'Válassz opciót...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#exampleModal')
                });
            }
        });

        modal.addEventListener('hidden.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#exampleSelect').select2('destroy');
            }
        });
    }
});
</script>
```

---

## Common Use Cases

### 1. Product Categories - Parent Selection
**File**: `erp-app/app/views/product-categories/index.php`

```html
<select class="form-select select2" id="parentCategory" name="parent_id" required>
    <option value="">Válassz főkategóriát...</option>
    <?php foreach ($mainCategoriesForDropdown as $mainCat): ?>
        <option value="<?= $mainCat['id'] ?>"><?= htmlspecialchars($mainCat['name']) ?></option>
    <?php endforeach; ?>
</select>
```

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const addSubCategoryModal = document.getElementById('addSubCategoryModal');
    if (addSubCategoryModal) {
        addSubCategoryModal.addEventListener('show.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#parentCategory').select2({
                    placeholder: 'Válassz főkategóriát...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#addSubCategoryModal')
                });
            }
        });

        addSubCategoryModal.addEventListener('hidden.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#parentCategory').select2('destroy');
            }
        });
    }
});
```

### 2. Fee Types - VAT Selection
**File**: `erp-app/app/views/fee-types/index.php`

```html
<select class="form-select select2" id="vat_id" name="vat_id" required>
    <option value="">Válassz ÁFA kulcsot...</option>
    <?php foreach ($vatOptions as $vat): ?>
        <option value="<?= $vat['id'] ?>"><?= htmlspecialchars($vat['name']) ?></option>
    <?php endforeach; ?>
</select>
```

```javascript
$('#addFeeTypeModal').on('show.bs.modal', function() {
    if ($.fn.select2) {
        $('#vat_id').select2({
            placeholder: 'Válassz ÁFA kulcsot...',
            allowClear: true,
            dropdownParent: $('#addFeeTypeModal')
        });
    }
});
```

### 3. Fee Types - Type Selection
```html
<select class="form-select select2" id="type" name="type" required>
    <option value="">Válassz típust...</option>
    <option value="fixed">Fix összeg</option>
    <option value="percentage">Százalék</option>
</select>
```

```javascript
$('#addFeeTypeModal').on('show.bs.modal', function() {
    if ($.fn.select2) {
        $('#type').select2({
            placeholder: 'Válassz típust...',
            allowClear: true,
            dropdownParent: $('#addFeeTypeModal')
        });
    }
});
```

---

## Troubleshooting

### 1. "$ is not defined" Error
**Problem**: jQuery not available when script runs
**Solution**: Use vanilla JavaScript with safe jQuery access

```javascript
// ❌ Wrong - jQuery might not be available
$('#exampleSelect').select2();

// ✅ Correct - Safe jQuery access
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && window.jQuery.fn.select2) {
        window.jQuery('#exampleSelect').select2();
    }
});
```

### 2. Dropdown Not Visible in Modal
**Problem**: Z-index or positioning issues
**Solution**: Use `dropdownParent` option

```javascript
$('#exampleSelect').select2({
    dropdownParent: $('#exampleModal')
});
```

### 3. Select2 Not Initializing
**Problem**: Timing issues with modal content
**Solution**: Initialize on modal show event

```javascript
$('#exampleModal').on('show.bs.modal', function() {
    $('#exampleSelect').select2();
});
```

### 4. Memory Leaks
**Problem**: Select2 instances not cleaned up
**Solution**: Destroy on modal hide

```javascript
$('#exampleModal').on('hidden.bs.modal', function() {
    $('#exampleSelect').select2('destroy');
});
```

### 5. Console Errors
**Common Issues**:
- jQuery not loaded
- Select2 not loaded
- DOM element not found
- Z-index conflicts

**Debug Steps**:
```javascript
// Check if jQuery is available
console.log('jQuery:', typeof window.jQuery);

// Check if Select2 is available
console.log('Select2:', typeof window.jQuery?.fn?.select2);

// Check if element exists
console.log('Element:', document.getElementById('exampleSelect'));
```

---

## Best Practices

### 1. Safe Initialization
```javascript
// Always check for dependencies
if (window.jQuery && window.jQuery.fn.select2) {
    window.jQuery('#exampleSelect').select2();
}
```

### 2. Modal Integration
```javascript
// Initialize on modal show
modal.addEventListener('show.bs.modal', function() {
    // Initialize Select2
});

// Clean up on modal hide
modal.addEventListener('hidden.bs.modal', function() {
    // Destroy Select2
});
```

### 3. Proper HTML Structure
```html
<!-- Always include placeholder -->
<select class="form-select select2" id="exampleSelect">
    <option value="">Válassz opciót...</option>
    <!-- Other options -->
</select>
```

### 4. Accessibility
```javascript
// Include proper labels and ARIA attributes
$('#exampleSelect').select2({
    placeholder: 'Válassz opciót...',
    allowClear: true,
    dropdownParent: $('#exampleModal')
});
```

### 5. Performance
```javascript
// Destroy instances to prevent memory leaks
$('#exampleSelect').select2('destroy');

// Use event delegation for dynamic content
$(document).on('show.bs.modal', '#exampleModal', function() {
    // Initialize Select2
});
```

---

## Configuration Options

### Common Options
```javascript
$('#exampleSelect').select2({
    placeholder: 'Válassz opciót...',        // Placeholder text
    allowClear: true,                        // Show clear button
    dropdownParent: $('#exampleModal'),      // Parent container
    width: '100%',                          // Width
    minimumResultsForSearch: 0,              // Always show search
    language: 'hu',                         // Hungarian language
    theme: 'bootstrap-5'                    // Bootstrap theme
});
```

### Advanced Options
```javascript
$('#exampleSelect').select2({
    // AJAX loading
    ajax: {
        url: '/api/data',
        dataType: 'json',
        delay: 250
    },
    
    // Custom templates
    templateResult: function(data) {
        return data.text;
    },
    
    // Custom selection
    templateSelection: function(data) {
        return data.text;
    },
    
    // Events
    select: function(e) {
        console.log('Selected:', e.params.data);
    }
});
```

---

## Examples

### 1. Simple Dropdown
```html
<select class="form-select select2" id="simpleSelect">
    <option value="">Válassz...</option>
    <option value="1">Opció 1</option>
    <option value="2">Opció 2</option>
</select>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && window.jQuery.fn.select2) {
        window.jQuery('#simpleSelect').select2({
            placeholder: 'Válassz opciót...',
            allowClear: true
        });
    }
});
</script>
```

### 2. Modal Dropdown
```html
<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <select class="form-select select2" id="modalSelect">
                    <option value="">Válassz...</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('exampleModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#modalSelect').select2({
                    placeholder: 'Válassz opciót...',
                    allowClear: true,
                    dropdownParent: window.jQuery('#exampleModal')
                });
            }
        });

        modal.addEventListener('hidden.bs.modal', function() {
            if (window.jQuery && window.jQuery.fn.select2) {
                window.jQuery('#modalSelect').select2('destroy');
            }
        });
    }
});
</script>
```

### 3. AJAX Dropdown
```html
<select class="form-select select2" id="ajaxSelect">
    <option value="">Válassz...</option>
</select>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && window.jQuery.fn.select2) {
        window.jQuery('#ajaxSelect').select2({
            placeholder: 'Válassz opciót...',
            allowClear: true,
            ajax: {
                url: '/api/data',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });
    }
});
</script>
```

---

## File Structure

### Core Files
```
erp-app/
├── app/
│   └── views/
│       └── layout/
│           └── base.php              # Select2 CSS/JS includes
├── public/
│   └── assets/
│       └── assets/
│           └── vendor/
│               └── libs/
│                   └── select2/      # Select2 library files
```

### Implementation Files
```
erp-app/
├── app/
│   └── views/
│       ├── product-categories/
│       │   └── index.php            # Parent category dropdown
│       ├── fee-types/
│       │   └── index.php            # VAT and type dropdowns
│       └── layout/
│           └── base.php              # Base layout with Select2
```

---

## Conclusion

Select2 provides a significant improvement to user experience in the ERP system by offering:

- **Enhanced Functionality**: Search, keyboard navigation, better styling
- **Modal Compatibility**: Proper integration with Bootstrap modals
- **Accessibility**: Screen reader and keyboard support
- **Performance**: Efficient rendering and memory management

The implementation follows best practices for:
- **Safe Initialization**: Checking for dependencies
- **Modal Integration**: Proper event handling
- **Memory Management**: Cleaning up instances
- **Error Prevention**: Comprehensive error handling

This guide serves as a reference for implementing Select2 in new features and maintaining existing implementations throughout the ERP system. 