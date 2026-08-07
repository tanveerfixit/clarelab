# ClareLab EPOS Page Inventory

Here is the inventory of active pages and routes currently available in the ClareLab POS application.

| # | Route | Controller / Component | Description |
|---|---|---|---|
| 1 | `/` | `App\Livewire\Auth\Login` (Guests) <br> `App\Livewire\HomeDashboard` (Users) | **Central Login / Branch Dashboard**: Serves the unified login form for guests, and automatically forwards authenticated users to their branch home dashboard. |
| 2 | `/register` | `App\Livewire\Pos\CashRegister` | **Cash Register / POS**: Core terminal screen to scan barcodes, add items to the cart, register cash drawers, and process checkout sales. |
| 3 | `/repairs` | `App\Livewire\Repairs\RepairBooking` | **Repair Bookings List**: Board index displaying active and completed device repair tickets. |
| 4 | `/repairs/{ticket}` | `App\Livewire\Repairs\RepairShow` | **Repair Ticket Detail**: Shows individual repair diagnostics, updates technician assignments, logs notes, and manages job statuses. |
| 5 | `/products` | `App\Livewire\Products\ProductIndex` | **Manage Products Catalog**: Table index listing all products in the catalog with filtering by type, category, and manufacturer. |
| 6 | `/products/create` | `App\Livewire\Products\ProductFormView` | **Create Product**: Catalog creation form for registering standard, variable, serialized, or service products. |
| 7 | `/products/{product}` | `App\Livewire\Products\ProductShow` | **Product Information**: Detailed specification screen showing branch-scoped stock levels, cost history, and individual in-stock serial numbers (IMEIs). |
| 8 | `/products/{product}/edit` | `App\Livewire\Products\ProductFormView` | **Edit Product**: Update page to modify product attributes, prices, or conditions. |
| 9 | `/getting-started` | `App\Livewire\Settings\GettingStarted` | **Getting Started / Settings**: Business setup assistant and initial account/printer configuration module. |
| 10 | `/customers` | `App\Livewire\Customers\CustomerIndex` | **Manage Customers**: Full-page CRM dashboard to browse customer contacts, filter walk-ins/regular accounts, and add new customers. |
| 11 | `/customers/{customer}` | `App\Livewire\Customers\CustomerShow` | **Customer Profile Details**: Displays individual client specifications, including contact details, active repairing logs, and purchase invoices. |

