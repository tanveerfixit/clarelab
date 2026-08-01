# Full Multi-Tenancy Implementation Plan: ClareLab

This plan details the step-by-step implementation for multi-tenant subdomain isolation on **clarelab.com**, explicitly configured and verified with 3 distinct business tenants:

1. **PhoneLab**: `https://phonelab.clarelab.com`
2. **iStore**: `https://istore.clarelab.com`
3. **FIXD**: `https://fixd.clarelab.com`

---

## 1. System Architecture & Tenant Mapping

```text
                                [ Internet User ]
                                       │
            ┌──────────────────────────┼──────────────────────────┐
            ▼                          ▼                          ▼
[ phonelab.clarelab.com ]     [ istore.clarelab.com ]     [ fixd.clarelab.com ]
            │                          │                          │
            └──────────────────────────┼──────────────────────────┘
                                       ▼
                       [ Subdomain Tenant Middleware ]
                                       │
      ┌────────────────────────────────┼────────────────────────────────┐
      ▼                                ▼                                ▼
[ DB: clarelab_tenant_phonelab ] [ DB: clarelab_tenant_istore ]   [ DB: clarelab_tenant_fixd ]
├── Business: PhoneLab           ├── Business: iStore             ├── Business: FIXD
├── Users: tech@phonelab.com     ├── Users: sales@istore.com      ├── Users: admin@fixd.com
├── Products: Screen Repair Kit  ├── Products: iPhone 15 Pro Max  ├── Products: Battery Replacement
└── Invoices: PL-2026-0001       └── Invoices: IS-2026-0001       └── Invoices: FX-2026-0001
```

---

## 2. Central Database Schema (`clarelab_central`)

The central database tracks tenant subscriptions and subdomain mappings:

### `tenants` Table
| id | tenant_id | business_name | owner_email | db_name | status |
|---|---|---|---|---|---|
| `1` | `phonelab` | **PhoneLab** | `admin@phonelab.com` | `clarelab_tenant_phonelab` | `active` |
| `2` | `istore` | **iStore** | `admin@istore.com` | `clarelab_tenant_istore` | `active` |
| `3` | `fixd` | **FIXD** | `admin@fixd.com` | `clarelab_tenant_fixd` | `active` |

### `domains` Table
| id | tenant_id | domain | full_url |
|---|---|---|---|
| `1` | `phonelab` | `phonelab.clarelab.com` | `https://phonelab.clarelab.com` |
| `2` | `istore` | `istore.clarelab.com` | `https://istore.clarelab.com` |
| `3` | `fixd` | `fixd.clarelab.com` | `https://fixd.clarelab.com` |

---

## 3. Step-by-Step Execution Plan

### Step 1: Package Installation & Configuration
- Install `stancl/tenancy` via Composer:
  ```bash
  composer require stancl/tenancy
  php artisan tenancy:install
  ```
- Configure `config/tenancy.php` for `clarelab.com` and local domain resolution (`localhost`).
- **Verification**: Check published configuration files `config/tenancy.php` and `routes/tenant.php`.

---

### Step 2: Database Architecture & Migration Separation
- Configure database connection drivers in `config/database.php` for central management.
- Create central migrations (`tenants`, `domains`).
- Move tenant-specific migrations (users, products, repairs, invoices) to `database/migrations/tenant/`.
- **Verification**: Run `php artisan migrate` for central tables and confirm `tenants` and `domains` tables are created in central DB.

---

### Step 3: Subdomain Tenant Routing & Middleware Setup
- Configure `app/Models/Tenant.php` and `app/Models/Domain.php`.
- Define central routes on `clarelab.com` (Registration & Landing Page).
- Define tenant routes on `{tenant}.clarelab.com` (POS Register, Repairs, Products, Inventory) protected by `InitializeTenancyBySubdomain` middleware.
- **Verification**: Verify central routes load on main domain while tenant subdomains route through tenancy middleware.

---

### Step 4: Automated Business Provisioning & Seeder System
- Build tenant seeders to initialize sample business profile, admin user, and demo products per tenant.
- Build Business Registration Livewire component (`app/Livewire/Central/RegisterBusiness.php`).
- Registration Logic:
  1. Input: Business Name (`PhoneLab`, `iStore`, `FIXD`), Subdomain (`phonelab`, `istore`, `fixd`), Owner Email, Password.
  2. Create Tenant record & Domain record in central DB.
  3. Automatically provision tenant database (e.g. `clarelab_tenant_phonelab`).
  4. Automatically run tenant migrations and seed admin credentials.
- **Verification**: Provision all 3 business databases (`phonelab`, `istore`, `fixd`) automatically via CLI/Seeder and web registration.

---

### Step 5: End-to-End Multi-Tenant Isolation Testing
Validate 100% data and authentication isolation across the 3 target businesses:

#### A. PhoneLab (`phonelab.clarelab.com`)
- Login: `admin@phonelab.com`
- Create Product: **"PhoneLab Screen Repair Service"**
- Generate Invoice: **`PL-2026-0001`**

#### B. iStore (`istore.clarelab.com`)
- Login: `admin@istore.com`
- Create Product: **"iPhone 15 Pro Max 256GB"**
- Generate Invoice: **`IS-2026-0001`**

#### C. FIXD (`fixd.clarelab.com`)
- Login: `admin@fixd.com`
- Create Product: **"FIXD Battery Replacement"**
- Generate Invoice: **`FX-2026-0001`**

#### Isolation Validation Checks:
- [ ] Confirm `admin@phonelab.com` cannot log in to `istore.clarelab.com` or `fixd.clarelab.com`.
- [ ] Confirm `istore.clarelab.com` database contains 0 PhoneLab or FIXD products.
- [ ] Confirm each business invoice counter operates independently.

---

### Step 6: Code Audit & GitHub Deployment
- Run full test suite: `php artisan test`.
- Stage, commit, and push clean codebase to GitHub repository:
  `git@github.com:tanveerfixit/clarelab.git`

---

## User Review & Approval Required

> [!IMPORTANT]
> Please review this complete plan featuring **phonelab.clarelab.com**, **istore.clarelab.com**, and **fixd.clarelab.com**. Click **Proceed** to begin execution with **Step 1**!
