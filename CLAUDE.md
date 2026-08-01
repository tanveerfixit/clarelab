# CLAUDE.md — Code Generator Instructions for Laravel 11 TALL Stack EPOS

This document serves as the authoritative architectural blueprint and coding guidelines for generating, maintaining, and extending the EPOS system. All code generated for this project MUST strictly follow these rules and patterns.

---

## 1. Project Overview & Environment

- **Framework**: Laravel 11 (PHP 8.3+)
- **Stack**: TALL Stack (Tailwind CSS v4, Alpine.js v3, Laravel 11, Livewire v3)
- **Primary OS**: Windows / Cross-platform
- **Database (Local Dev)**: File-based SQLite (`database/database.sqlite`)
- **Database Target (Production)**: MySQL 8.4
- **Project Directory**: `C:\epos-main\laravel-epos\`

---

## 2. Core Architectural & Engineering Directives

### A. Database & Multi-Tenancy Rules
1. **Multi-Tenancy Enforcement**:
   - Every domain table (except system-wide permissions) MUST include `business_id` and `branch_id`.
   - All domain models MUST use a `BelongsToBranch` trait that applies a Global Scope constraining queries to `auth()->user()->business_id` and `auth()->user()->branch_id`.
   - Never write loose queries without tenant scoping.

2. **SQLite vs MySQL Compatibility**:
   - Use Laravel Query Builder / Eloquent abstractions exclusively.
   - Do NOT write raw MySQL/SQLite specific SQL syntax.
   - Use the `HandlesConcurrency` trait for SQLite-safe `lockForUpdate()` calls inside `DB::transaction()` blocks.

### B. TALL Stack Conventions

1. **Livewire 3**:
   - Component responsibilities: Orchestration ONLY (validate inputs, call service classes, handle exceptions, assign public properties).
   - Use **Form Objects** (`Livewire\Form`) for complex forms (e.g., checkout data payloads, product variant forms).
   - Use **`#[Computed]`** properties for cart calculations, tax breakdowns, and product list filtering.
   - Enable **`wire:navigate`** on all primary navigation links for SPA-like transitions.

2. **Alpine.js**:
   - Client-only transient UI state: Modals, dropdowns, thermal receipt preview toggles.
   - **Keyboard Shortcuts**: Global listener for `F2` (focus product search) and `F4` (complete checkout).
   - **Hardware Barcode Scanner Listener**:
     - Implement a global keydown buffer.
     - Detect keypress intervals `< 40ms` to identify hardware scanner input vs. human typing.
     - Dispatch barcode string to Livewire upon `Enter` or stream completion.

3. **Cart State Management (Hybrid Persistence)**:
   - Client cart state is cached in `localStorage` via Alpine.js to survive browser reloads or network hiccups.
   - Alpine.js syncs cart items to Livewire for checkout execution, stock verification, and receipt rendering.

---

## 3. Directory Structure & Domain Organization

Code must be organized modularly by domain within `app/Modules/`:

```text
app/
├── Models/
│   ├── Business.php
│   ├── Branch.php
│   └── User.php
├── Modules/
│   ├── Core/
│   │   └── Traits/
│   │       ├── BelongsToBranch.php
│   │       └── HandlesConcurrency.php
│   ├── Inventory/
│   │   ├── Actions/
│   │   ├── Exceptions/
│   │   │   └── InsufficientStockException.php
│   │   ├── Models/
│   │   │   ├── Category.php
│   │   │   ├── Product.php
│   │   │   ├── ProductVariant.php
│   │   │   └── BranchStock.php
│   │   └── Services/
│   │       └── InventoryService.php
│   └── Pos/
│       ├── Forms/
│       │   └── CheckoutForm.php
│       ├── Http/
│       │   └── Livewire/
│       │       ├── CashRegister.php
│       │       └── ThermalReceiptModal.php
│       └── Services/
│           └── CheckoutService.php
database/
├── database.sqlite
└── migrations/
```

---

## 4. Key Code Templates & Patterns

### A. Concurrency Trait (`app/Modules/Core/Traits/HandlesConcurrency.php`)
```php
<?php

namespace App\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait HandlesConcurrency
{
    /**
     * Applies lockForUpdate() safely only on databases supporting row-level locks.
     */
    protected function safeLockForUpdate(Builder $query): Builder
    {
        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'pgsql', 'sqlsrv'], true)) {
            return $query->lockForUpdate();
        }

        return $query;
    }
}
```

### B. Global Hardware Barcode Listener (Alpine.js)
```html
<div x-data="barcodeScanner()" x-init="initScanner()">
    <!-- POS Interface -->
</div>

<script>
function barcodeScanner() {
    return {
        buffer: '',
        lastKeyTime: 0,
        initScanner() {
            window.addEventListener('keydown', (e) => {
                const currentTime = Date.now();
                const timeDiff = currentTime - this.lastKeyTime;
                this.lastKeyTime = currentTime;

                // Ignore target inputs to allow manual typing when focused
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
                    return;
                }

                if (e.key === 'Enter') {
                    if (this.buffer.length > 2) {
                        $wire.handleBarcodeScanned(this.buffer);
                        this.buffer = '';
                        e.preventDefault();
                    }
                } else if (e.key.length === 1) {
                    // If keys arrive in < 40ms intervals, treat as hardware scanner
                    if (timeDiff < 40 || this.buffer.length === 0) {
                        this.buffer += e.key;
                    } else {
                        this.buffer = e.key; // Reset if interval was too slow
                    }
                }
            });
        }
    }
}
</script>
```

### C. Thermal Receipt Print Styling Pattern
```html
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #thermal-receipt, #thermal-receipt * {
        visibility: visible;
    }
    #thermal-receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm; /* Standard thermal receipt width */
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
    }
}
</style>
```

---

## 5. Instructions for the Code Generator

When generating code for this repository, ALWAYS:
1. Include `business_id` and `branch_id` foreign keys in migrations and model relations.
2. Use `DB::transaction()` for all stock adjustments, sale processing, and order updates.
3. Throw typed domain exceptions (e.g. `InsufficientStockException`) rather than returning boolean `false`.
4. Implement Livewire computed properties (`#[Computed]`) for total, tax, discount calculations, and filtered inventory search.
5. Provide clear comments when SQLite behavior diverges from MySQL.
