# CLAUDE.md — Code Generator Instructions for Laravel 11 TALL Stack EPOS

This document serves as the authoritative architectural blueprint and coding guidelines for generating, maintaining, and extending the ClareLab EPOS system. All code generated for this project MUST strictly follow these rules and patterns.

> Architecture modelled after authentic POS systems: CellSmart POS, RepairDesk, Lightspeed POS, Square POS, RepairQ, Vend.

---

## 1. Project Overview & Environment

- **Framework**: Laravel 11 (PHP 8.3+)
- **Stack**: TALL Stack (Tailwind CSS v4, Alpine.js v3, Laravel 11, Livewire v3)
- **Multi-Tenancy Package**: `stancl/tenancy` (database-per-tenant)
- **Primary OS**: Windows / Cross-platform
- **Database (Local Dev)**: File-based SQLite (`database/database.sqlite`)
- **Database (Production)**: MySQL 8.4 — one database per business tenant
- **Project Directory**: `C:\epos-main\laravel-epos\`

---

## 2. Core Architectural & Engineering Directives

### A. Multi-Tenancy: 3-Tier Hierarchy

Every authentic POS system uses this structure:

```
Company / Business (Tenant)          ← Separate MySQL database per business
  └── Outlets / Branches / Stores    ← branch_id within the tenant DB
        └── Registers / Terminals    ← register_id within the branch
```

**ClareLab Live Business — PhoneLab:**
```
clarelab_central (central DB)
├── tenants, domains, shared_product_templates

tenant_phonelab (PhoneLab's isolated DB)
├── Business: PhoneLab
│
├── Branch 1: Phone Lab       → phonelab.clarelab.com
├── Branch 2: iPear            → ipear.clarelab.com
├── Branch 3: iPear Tesco      → ipeartesco.clarelab.com
├── Branch 4: FIXD             → fixd.clarelab.com
├── Branch 5: Phone Shop       → phoneshop.clarelab.com
└── Branch 6: Gadgets          → gadgets.clarelab.com
```

**Each branch looks and feels like a completely separate business to the customer:**
- Own subdomain (e.g. `ipear.clarelab.com` vs `fixd.clarelab.com`)
- Own brand name, logo, and colour scheme
- Own invoice design and invoice number prefix (e.g. `IPEAR-2026-0001`, `FIXD-2026-0001`)
- Own receipt header/footer text
- Own contact details, address, phone number
- Own opening hours and local settings

**But behind the scenes they share ONE product catalog and ONE customer database** because they are all branches of the same PhoneLab business.

### B. Database-Per-Business Strict Isolation

1. Each business tenant operates on its own dedicated MySQL database (`clarelab_tenant_{tenant_id}`).
2. **Zero Cross-Database Queries**: Sales, invoices, transactions, repair tickets, customer data, stock quantities, activity logs, and financial statistics are NEVER shared between business databases.
3. Central system operations (tenant routing, subdomain mapping, shared product templates) are managed in a separate `clarelab_central` database.

### C. Data Isolation Matrix — Within the SAME Business

Within a single business (e.g. PhoneLab), data is either shared across all branches or isolated per branch:

| Data | Scope | Rule |
|:---|:---|:---|
| **Product Catalog** (name, SKU, barcode, manufacturer, category) | ✅ SHARED (business-wide) | Single master catalog. NO `branch_id` on `products` table. HQ creates a product, all branches see it instantly. |
| **Customer Database** | ✅ SHARED (business-wide) | Unified CRM. Customer buys at Ennis, history visible at Limerick. |
| **Suppliers / Vendors** | ✅ SHARED (business-wide) | Central vendor list for purchase orders. |
| **Staff / Users** | ✅ SHARED (business-wide) | Staff created at business level, assigned to specific branch(es). |
| **Roles & Permissions** | ✅ SHARED (business-wide) | Admin, Manager, Cashier, Technician roles defined once. |
| **Tax Rules** | ✅ SHARED (business-wide) | VAT rates, tax classes defined at business level. |
| **Inventory / Stock Quantities** | 🔒 ISOLATED (per branch) | `branch_stocks` table: `(product_id, branch_id) → quantity`. Ennis has 5 units, Limerick has 3 — tracked separately. |
| **Sales / Transactions** | 🔒 ISOLATED (per branch) | Each sale records `branch_id` + `register_id`. |
| **Cash Drawer / Till** | 🔒 ISOLATED (per branch) | Opening float, Z-reports, cash counts per register per branch. |
| **Invoices** | 🔒 ISOLATED (per branch) | Invoice numbering per-branch: `PL-ENNIS-0001`, `PL-LMK-0001`. |
| **Repair Tickets** | 🔒 ISOLATED (per branch) | Ticket created at receiving branch. Can be transferred to hub. |
| **Activity Logs** | 🔒 ISOLATED (per branch) | Who did what, when, at which branch. |
| **Product Serials / IMEI** | 🔒 ISOLATED (per branch) | `product_serials.branch_id` tracks which branch holds the physical unit. |
| **Reports** | 🔄 BOTH | Branch-level reports (daily Z-report) AND business-wide consolidated reports (total revenue). |
| **Pricing** | 🔄 OPTIONAL | Default price from catalog. Branches can override with local price books if enabled. |

### D. Cross-Business Product Sharing Protocol

Between different businesses (PhoneLab ↔ iStore ↔ FIXD), **only product templates** can be shared. Pricing, inventory, serials, and sales are NEVER shared.

1. **Central Shared Repository**: `clarelab_central.shared_product_templates` stores product templates (name, manufacturer, category, model, storage, color, description, `shared_by_tenant_id`).
2. **Publishing**: A business can publish a product template to the central repository.
3. **Importing**: Any business can browse the repository and import/clone a template into its own isolated database with its own local cost price, selling price, and stock levels.
4. **No Ongoing Sync**: Importing is a one-time clone. Changes to the source product do not propagate.

```
PhoneLab creates locally:              iStore imports & customises:
"Samsung Galaxy A55 128GB"        →    "Samsung Galaxy A55 128GB"
  Cost: £300  ← PhoneLab's cost         Cost: £280  ← iStore's cost
  Price: £400 ← PhoneLab's price        Price: £420 ← iStore's price
  Stock: 5    ← PhoneLab's stock         Stock: 0    ← iStore's stock
  IMEIs: [...]← PhoneLab's serials       IMEIs: []   ← iStore's serials
```

### E. Branch-Level Branding & Settings (White-Label Branches)

Each branch within a business has its own identity. The `branches` table stores per-branch customisation:

| Setting | Example (iPear) | Example (FIXD) |
|:---|:---|:---|
| **Branch Name** | iPear | FIXD |
| **Subdomain** | ipear.clarelab.com | fixd.clarelab.com |
| **Logo** | `/storage/branches/ipear/logo.png` | `/storage/branches/fixd/logo.png` |
| **Colour Scheme** | `#1DB954` (green) | `#FF6B35` (orange) |
| **Invoice Prefix** | `IPEAR` | `FIXD` |
| **Invoice Number** | `IPEAR-2026-0001` | `FIXD-2026-0001` |
| **Receipt Header** | "iPear — Premium Apple Repairs" | "FIXD — We Fix Everything" |
| **Receipt Footer** | "Thank you for choosing iPear!" | "Thank you for choosing FIXD!" |
| **Address** | 12 Main St, Ennis | 5 O'Connell St, Limerick |
| **Phone** | 065 123 4567 | 061 987 6543 |
| **Email** | info@ipear.ie | hello@fixd.ie |
| **Opening Hours** | Mon-Sat 9am-6pm | Mon-Fri 10am-7pm |
| **Tax Settings** | VAT 23% | VAT 23% |

When rendering the POS UI, invoices, or thermal receipts, ALWAYS load branding from the active branch context (`session('active_branch_id')` or `auth()->user()->branch_id`). Never hardcode a single business name or logo.

### F. SQLite vs MySQL Compatibility

- Use Laravel Query Builder / Eloquent abstractions exclusively.
- Do NOT write raw MySQL/SQLite specific SQL syntax.
- Use the `HandlesConcurrency` trait for SQLite-safe `lockForUpdate()` calls inside `DB::transaction()` blocks.

---

## 3. Key Schema Patterns

### Products Table — Business-Wide Shared Catalog (NO branch_id)
```sql
products
├── id
├── business_id              -- auto-set by tenant context
├── name                     -- "Samsung Galaxy A55 128GB" (visible to ALL branches)
├── manufacturer             -- "Samsung"
├── category_name            -- "Smartphones"
├── sku, barcode             -- uniform across all branches
├── cost_price, selling_price -- default business-wide prices
├── type                     -- standard, serialized, variable, service
├── has_serial, is_taxable
├── stock_quantity            -- aggregate (sum of all branch_stocks)
└── timestamps
```

### Branches — Per-Branch Identity & Settings
```sql
branches
├── id
├── business_id
├── name                     -- "iPear", "FIXD", "Phone Lab", "Gadgets"
├── slug                     -- "ipear", "fixd", "phonelab", "gadgets"
├── subdomain                -- "ipear.clarelab.ie"
├── logo_path                -- "/storage/branches/ipear/logo.png"
├── color_primary            -- "#1DB954"
├── color_secondary          -- "#FFFFFF"
├── invoice_prefix           -- "IPEAR"
├── invoice_next_number      -- 1 (auto-increments per branch)
├── receipt_header           -- "iPear — Premium Apple Repairs"
├── receipt_footer           -- "Thank you for choosing iPear!"
├── address, phone, email    -- branch contact details
├── opening_hours            -- JSON or text
├── status                   -- active, inactive
└── timestamps
```

### Branch Stocks — Per-Branch Inventory (HAS branch_id)
```sql
branch_stocks
├── id
├── business_id
├── branch_id                -- Phone Lab=1, iPear=2, iPear Tesco=3, FIXD=4, Phone Shop=5, Gadgets=6
├── product_id               -- FK → products
├── quantity                 -- stock on hand AT THIS BRANCH ONLY
├── reorder_level
└── timestamps
    UNIQUE(branch_id, product_id)
```

### Sales / Transactions — Per-Branch (HAS branch_id + register_id)
```sql
sales
├── id
├── business_id
├── branch_id                -- which branch made this sale
├── register_id              -- which terminal/register
├── user_id                  -- which staff member
├── customer_id
├── invoice_number           -- "IPEAR-2026-0001" or "FIXD-2026-0042" (uses branch.invoice_prefix)
├── subtotal, tax_amount, grand_total
├── payment_method
└── timestamps
```

### Product Serials / IMEI — Per-Branch (HAS branch_id)
```sql
product_serials
├── id
├── product_id
├── product_variant_id
├── serial_number            -- IMEI number
├── status                   -- available, sold, in_repair, returned, transferred
├── branch_id                -- which branch physically holds this unit
├── transaction_id
└── timestamps
```

### Central Shared Product Templates (`clarelab_central` DB)
```sql
shared_product_templates
├── id
├── name                     -- "Samsung Galaxy A55 128GB"
├── manufacturer             -- "Samsung"
├── category                 -- "Smartphones"
├── model                    -- "A55"
├── storage                  -- "128GB"
├── color                    -- "Black"
├── description
├── shared_by_tenant_id      -- "phonelab"
└── timestamps
```

---

## 4. Directory Structure & Domain Organization

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
├── migrations/              -- central migrations
└── migrations/tenant/       -- tenant-specific migrations (products, sales, repairs, etc.)
```

---

## 5. Key Code Templates & Patterns

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

## 6. TALL Stack Conventions

1. **Livewire 3**:
   - Component responsibilities: Orchestration ONLY (validate inputs, call service classes, handle exceptions, assign public properties).
   - Use **Form Objects** (`Livewire\Form`) for complex forms (e.g., checkout data payloads, product variant forms).
   - Use **`#[Computed]`** properties for cart calculations, tax breakdowns, and product list filtering.
   - Enable **`wire:navigate`** on all primary navigation links for SPA-like transitions.

2. **Alpine.js**:
   - Client-only transient UI state: Modals, dropdowns, thermal receipt preview toggles.
   - **Keyboard Shortcuts**: Global listener for `F2` (focus product search) and `F4` (complete checkout).
   - **Hardware Barcode Scanner Listener**: See Section 5B.

3. **Cart State Management (Hybrid Persistence)**:
   - Client cart state is cached in `localStorage` via Alpine.js to survive browser reloads or network hiccups.
   - Alpine.js syncs cart items to Livewire for checkout execution, stock verification, and receipt rendering.

---

## 7. Instructions for the Code Generator

When generating code for this repository, ALWAYS:

1. **Database-Per-Tenant Isolation**: Maintain strict isolation (`clarelab_tenant_{tenant_id}`). NEVER perform cross-database queries or leak sales, activity logs, financial stats, or customer data between tenant databases.
2. **Product Catalog is Business-Wide**: The `products` table has NO `branch_id`. Products are shared across all branches of the same business. Do NOT add `branch_id` to the `products` table.
3. **Inventory is Per-Branch**: Stock quantities are tracked in `branch_stocks` with `(product_id, branch_id) → quantity`. Always filter by `branch_id` when querying stock levels.
4. **Sales are Per-Branch**: All sales, transactions, invoices, and activity logs MUST include `branch_id` and `register_id` to identify which branch and terminal processed the sale.
5. **Serials/IMEI are Per-Branch**: `product_serials.branch_id` tracks which branch physically holds each unit. Update `branch_id` on stock transfers.
6. **Cross-Business Product Sharing**: Use `clarelab_central.shared_product_templates` ONLY for cross-business product template import/export. Never share pricing, inventory, or serials.
7. **Transactions**: Use `DB::transaction()` for all stock adjustments, sale processing, and order updates.
8. **Domain Exceptions**: Throw typed domain exceptions (e.g. `InsufficientStockException`) rather than returning boolean `false`.
9. **Computed Properties**: Implement Livewire computed properties (`#[Computed]`) for total, tax, discount calculations, and filtered inventory search.
10. **SQLite Comments**: Provide clear comments when SQLite behavior diverges from MySQL.
11. **SEO-Friendly URLs**: Keep routes, URLs, and model routing parameters clean, intuitive, and SEO-friendly.
