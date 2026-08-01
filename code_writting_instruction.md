# Step-by-Step Code Writing Instructions (`code_writting_instruction.md`)

This document specifies the step-by-step roadmap for extracting the UI design system, color palette, and business logic from the original React EPOS codebase (`C:\epos-main\epos-main`) and implementing it into the new Laravel 11 TALL Stack codebase at (`C:\epos-main\laravel-epos`).

---

## SECTION 1: Core Design System & Color Extraction

### 1.1 Global Color Architecture (from `src/index.css`)
The new Laravel application's Tailwind CSS configuration (`resources/css/app.css`) must enforce the exact brand colors extracted from the original app:

| Variable | Color Hex | Functional Purpose |
| :--- | :--- | :--- |
| `--bg-app` | `#f4f7f9` | Global background behind layout cards |
| `--bg-sidebar` | `#2c3e50` | Dark slate navigation sidebar |
| `--bg-card` | `#ffffff` | Elevated interactive cards & panels |
| `--bg-hover` | `#f1f5f9` | Dynamic hover state for list items |
| `--bg-zebra` | `#f8f9fa` | Alternating row background for tables |
| `--text-main` | `#0f172a` | High-contrast body & header typography |
| `--text-muted` | `#64748b` | Sub-labels and meta information |
| `--brand-primary` | `#0a77c0` | Primary action buttons & active navigation |
| `--brand-primary-hover`| `#2980b9` | Hover state for primary buttons |
| `--brand-danger` | `#e74c3c` | Critical alerts, voids, and deletions |
| `--brand-success` | `#27ae60` | Checkout approvals and payment success |
| `--border-base` | `#e2e8f0` | Hairline list & grid borders |

### 1.2 Thermal Receipt Specifications
- **Width**: `80mm` fixed-width container (`302px` display simulation).
- **Typography**: `font-mono` / `Courier New`, 12px base size.
- **Dashed Dividers**: `border-b border-dashed border-black` for itemized breakdowns.

---

## SECTION 2: Business Logic Extraction

### 2.1 Cash Register & Cart Logic (from `CashRegister.tsx`)
1. **Local Storage Keys**:
   - Cart Items: `epos_cart`
   - Active Customer: `epos_customer`
   - Payments Applied: `epos_payments`
   - Activity Audit Log: `epos_activities`
2. **Cart Calculations**:
   - `Subtotal` = $\sum (\text{item.unit\_price} \times \text{item.quantity}) - \text{discount}$
   - `Tax Amount` = $\text{Subtotal} \times \left(\frac{\text{Tax Rate}}{100}\right)$
   - `Grand Total` = $\text{Subtotal} + \text{Tax Amount}$
   - `Balance Remaining` = $\text{Grand Total} - \sum (\text{Payment Entries})$
3. **Hardware Barcode Detection**:
   - Inter-key timing: $< 40\text{ms}$.
   - Auto-searches inventory by SKU or Barcode upon `Enter`.

---

## SECTION 3: Step-by-Step Implementation Roadmap

Execute the following steps in sequence. Do NOT proceed to a subsequent step until the current step is fully built and verified.

---

### Step 1: Framework Setup & Base Shell
- **Goal**: Initialize Laravel 11 app in `C:\epos-main\laravel-epos` with Livewire 3, Tailwind CSS v4, and Alpine.js.
- **Tasks**:
  1. Scaffold fresh Laravel 11 app in `C:\epos-main\laravel-epos`.
  2. Install Livewire 3.
  3. Configure `database/database.sqlite`.
  4. Import global CSS variables from Section 1.1 into `resources/css/app.css`.
  5. Create `MainLayout` Blade component with the slate sidebar (`#2c3e50`) and `#f4f7f9` background shell.

---

### Step 2: Core Domain Migrations & Models
- **Goal**: Port database tables from `src/db.ts` to Eloquent models.
- **Tasks**:
  1. Create `businesses` and `branches` migrations & models.
  2. Create `users` migration with `business_id` and `branch_id`.
  3. Create `categories`, `products`, `product_variants`, and `branch_stocks` migrations with unique composite indexes (`branch_id`, `product_variant_id`).
  4. Implement `BelongsToBranch` multi-tenancy trait in `app/Modules/Core/Traits/BelongsToBranch.php`.
  5. Seed default tax classes and categories.

---

### Step 3: Concurrency Engine & Inventory Domain
- **Goal**: Implement thread-safe stock reduction logic.
- **Tasks**:
  1. Create `HandlesConcurrency` trait for safe `lockForUpdate()` calls.
  2. Create `InsufficientStockException`.
  3. Build `InventoryService::decrementStock($branchId, $variantId, $quantity)`.
  4. Write Pest/PHPUnit tests running under SQLite verifying decrement & exception behavior.

---

### Step 4: POS Cash Register Component (UI + Cart State)
- **Goal**: Build the main 2-column POS interface (`CashRegister.php` & Blade template).
- **Tasks**:
  1. Create Livewire component `app/Modules/Pos/Http/Livewire/CashRegister.php`.
  2. Build left panel: Search bar, quick add button, product grid with live stock indicators.
  3. Build right panel: Cart item list, discount inputs, multi-payment split section (Cash/Card), subtotal/tax summary.
  4. Implement `#[Computed]` properties for `subtotal`, `taxTotal`, `grandTotal`, and `balanceDue`.
  5. Add Alpine.js `localStorage` synchronization for cart persistence.

---

### Step 5: Hardware Barcode Listener & Keyboard Shortcuts
- **Goal**: Seamless barcode scanning & fast POS hotkeys.
- **Tasks**:
  1. Add Alpine.js `barcodeScanner` component wrapping the Cash Register view.
  2. Implement `< 40ms` interval buffer to trigger `$wire.scanBarcode(code)`.
  3. Bind `F2` key to focus product search input.
  4. Bind `F4` key to trigger checkout modal.

---

### Step 6: Checkout Execution & Thermal Receipt Modal
- **Goal**: Finalize transactions, persist invoices, and print receipts.
- **Tasks**:
  1. Create `CheckoutService` to create invoices, payment records, and decrement inventory via `InventoryService` in a single transaction.
  2. Create `ThermalReceiptModal` Alpine/Livewire component simulating an `80mm` receipt.
  3. Implement `@media print` CSS rules for instant thermal receipt output.
  4. Clear local storage cart state upon successful transaction completion.

---

### Step 7: Final End-to-End Verification
- **Goal**: Verify full POS sales workflow.
- **Tasks**:
  1. Run automated test suite (`php artisan test`).
  2. Conduct manual barcode scan simulation and cart checkout flow.
  3. Validate multi-tenant data isolation across branches.
