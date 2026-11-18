# 🛡️ Urban Marketplace - Admin Console Access & Administration Guide

This guide provides complete instructions on accessing, navigating, and managing the **Urban Services Marketplace Admin Console**.

---

## 🚀 Quick Access

1. Open your browser and go to:
   - **Login Page**: [http://localhost:8000/login](http://localhost:8000/login)
   - **Admin Console**: [http://localhost:8000/admin](http://localhost:8000/admin) *(Automatically redirects to login if not authenticated)*

2. Sign in with the seeded administrator credentials:
   - **Email**: `admin@urbanapp.com`
   - **Password**: `password`

---

## 👥 Seeded Accounts Reference

The demo database includes pre-configured accounts representing every role in the marketplace:

| Role | Email | Password | Allowed Access |
|---|---|---|---|
| **Platform Administrator** | `admin@urbanapp.com` | `password` | **Full Admin Console (`/admin`)** & Customer Dashboard |
| **Approved Provider (Electrician)** | `vikram.pro@example.com` | `password` | Provider Portal / Customer Views |
| **Pending KYC Provider (Doctor)** | `dr.ananya@example.com` | `password` | KYC verification reviewable by Admin |
| **Customer** | `rohit.sharma@example.com` | `password` | Customer Dashboard (`/dashboard`) |
| **Customer 2** | `priya.patel@example.com` | `password` | Customer Dashboard (`/dashboard`) |

> ⚠️ **Note**: If a customer or provider attempts to access `/admin`, the application will deny access with an **HTTP 403 Forbidden** screen.

---

## 🎨 Theme & Visual Design

The Admin Console uses a distinct layout from the customer-facing interface:
- **Canvas**: Dark-mode `#070913` with multi-color animated background gradient glow orbs.
- **Surface**: Translucent frosted-glass cards (`bg-slate-900/80 backdrop-blur-xl border-slate-800/80`).
- **Gradient Buttons**: Interactive multi-stop color transitions:
  - **Primary**: `bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600`
  - **Approve KYC**: `bg-gradient-to-r from-emerald-600 to-teal-500`
  - **Reject KYC**: `bg-gradient-to-r from-rose-600 to-red-600`
  - **Pending Alerts**: `bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500`
- **Dynamic Indicators**: Glowing status dots, pulse indicators, and live badges.

---

## 📑 Admin Modules & Functionality

### 1. Executive Dashboard (`/admin`)
- **Key Performance Indicators (KPIs)**:
  - Total Captured Revenue (₹)
  - Total Bookings count
  - Active & Online Service Professionals
  - Open Disputes & Support Tickets
- **Quick Action Bar**: One-click jump to pending KYC reviews and service dispatch.
- **Recent Bookings Feed**: Real-time snapshot of the newest marketplace orders.
- **KYC Review Queue**: Highlights providers awaiting document verification.

### 2. Providers & KYC Management (`/admin/providers`)
- **Filter Pills**: Switch between `All Professionals`, `Pending Review`, `Approved & Verified`, and `Rejected`.
- **Card Badges**: Displays average customer rating (⭐), completed jobs, experience in years, and online/offline status.
- **Verification CTA**: Click **Review KYC & Documents** to audit individual providers.

### 3. Provider Audit & KYC Approval (`/admin/providers/{id}`)
- **Professional Bio**: Experience, contact details, assigned service categories.
- **Document Verification Table**:
  - Aadhaar Card, PAN Card, Electrical Licenses, Medical Registrations.
  - Document status (`pending`, `approved`, `rejected`), issue/expiry dates.
- **Action Buttons**:
  - **Approve Professional** (Sets status to `approved`, approves documents, and enables booking dispatch).
  - **Reject Application** (Sets status to `rejected` with rejection notice).

### 4. Service Catalog & Pricing Matrix (`/admin/services`)
- **Category Hierarchy**: Add and manage root service categories with license requirements and sort order.
- **Service Creation Form**:
  - Service title, description, category assignment.
  - Pricing model (`fixed`, `hourly`, `quote_based`).
  - Service mode (`at_customer`, `at_provider`, `online`).
  - Estimated duration (minutes).
- **Service Status Toggle**: Instant activation/deactivation switch.

### 5. Live Booking & Dispatch Control (`/admin/bookings`)
- **State Machine Tracking**:
  - `draft` ➔ `requested` ➔ `assigned` ➔ `in_progress` ➔ `completed` / `cancelled`
- **Assignment Overview**: Assigned professional details, scheduled appointment time, and address.
- **Financial Breakdown**: Total amount, tax, discount, platform fee, and provider payout.

### 6. Support Tickets & Dispute Resolution (`/admin/tickets`)
- **Dispute Tracker**: View customer claims and provider complaints.
- **Urgency Levels**: `low`, `medium`, `high`, `urgent`.
- **Resolution Control**: Direct 1-click status update (`in_progress`, `resolved`, `closed`).

---

## 🛠 Command-Line Administration

### Make an Existing User an Admin
If you registered a new user via `/register` and want to promote them to Admin:

```bash
docker exec urban_app_php php artisan tinker --execute="App\Models\User::where('email', 'your_user@example.com')->update(['role' => 'admin']);"
```

### Re-seed Demo Data
To reset or reload initial demo categories, services, pricing, providers, and bookings:

```bash
docker exec urban_app_php php artisan db:seed
```

### Run Automated Admin Feature Tests
To run the automated test suite covering all admin views, access controls, and middleware:

```bash
docker exec urban_app_php php artisan test --filter=AdminTest
```

Expected output:
```text
PASS  Tests\Feature\AdminTest
✓ guest is redirected to login
✓ non admin gets forbidden status
✓ admin can access dashboard
✓ admin can access providers list
✓ admin can view provider detail and kyc
✓ admin can access services catalog
✓ admin can access bookings dispatch
✓ admin can access support tickets

Tests: 8 passed (18 assertions)
```

---

## 🔐 Architecture & Security Details

- **Admin Middleware**: Registered at `app/Http/Middleware/AdminMiddleware.php` with alias `'admin'` in `bootstrap/app.php`.
- **Route Group**: Defined in `routes/web.php` under prefix `/admin` and protected by `middleware(['auth', 'admin'])`.
- **Layout Component**: Located at `resources/views/layouts/admin.blade.php`.
- **View Components**: Blade views located in `resources/views/admin/`.

