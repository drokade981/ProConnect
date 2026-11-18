# UrbanApp - Laravel 13 with Authentication (Docker Setup)

This is a fresh **Laravel 13** application pre-configured with **Laravel Breeze (Blade)** authentication and a **Docker** environment.

---

## 🚀 Quick Start with Docker

### 1. Build and Start the Containers
Run Docker Compose in detached mode:
```bash
docker-compose up -d --build
```

This starts:
- **`urban_app_php`**: PHP 8.3-FPM with all Laravel required extensions and Composer.
- **`urban_app_nginx`**: Nginx web server configured for Laravel routing, exposed on **port 8000** (`http://localhost:8000`).
- **`urban_app_mysql`**: MySQL 8.0 database server, exposed on **port 3306** (`db: urban_app`, `user: urban_user`, `password: secretpassword`).

---

### 2. Install Composer Dependencies (inside Docker container)
```bash
docker-compose exec app composer install
```

---

### 3. Generate Application Key & Run Migrations
```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

---

### 4. Compile Frontend Assets (Vite & Tailwind)
Run using the optional `node` profile or your local Node:
```bash
docker-compose run --rm node npm install
docker-compose run --rm node npm run build
```

---

### 5. Access the Application
Open your browser at:
👉 **[http://localhost:8000](http://localhost:8000)**

You can now:
- **Register a new account** at `/register`
- **Log in** at `/login`
- **Customer Dashboard** at `/dashboard`
- **Admin Control Panel** at `/admin` *(Admin account required)*

---

## 🛡️ Admin Panel Access & Guide

The application includes a dedicated **Executive Admin Console** designed with a vibrant dark theme, glassmorphic cards, and gradient interactive buttons.

### 🔑 Pre-Configured Admin Credentials

The database seeder automatically creates the following accounts:

| Role | Email | Password | Access Level |
|---|---|---|---|
| **Platform Administrator** | `admin@urbanapp.com` | `password` | **Full Admin Console Access** (`/admin`) |
| **Approved Provider** | `vikram.pro@example.com` | `password` | Electrician (Marketplace Provider) |
| **Pending KYC Provider** | `dr.ananya@example.com` | `password` | Doctor (Awaiting KYC Review) |
| **Customer** | `rohit.sharma@example.com` | `password` | Customer (Access to `/dashboard`) |

---

### 🌐 Admin URLs & Modules

Once logged in with `admin@urbanapp.com`, navigate to:

| Module | URL | Description |
|---|---|---|
| **Executive Dashboard** | [`http://localhost:8000/admin`](http://localhost:8000/admin) | Live KPI metrics (Revenue, Total Bookings, Active Providers), Pending KYC quick alerts, and recent bookings. |
| **Providers & KYC Directory** | [`http://localhost:8000/admin/providers`](http://localhost:8000/admin/providers) | Filterable directory of service professionals by status (`All`, `Pending`, `Approved`, `Rejected`). |
| **KYC Audit & Approval** | `http://localhost:8000/admin/providers/{id}` | Inspect Aadhaar, PAN, and trade licenses with **1-Click Approve** or **Reject** gradient action buttons. |
| **Services & Catalog** | [`http://localhost:8000/admin/services`](http://localhost:8000/admin/services) | Manage categories, add new services, set pricing models, and toggle service availability. |
| **Live Dispatch & Bookings** | [`http://localhost:8000/admin/bookings`](http://localhost:8000/admin/bookings) | Monitor booking state machine (`requested`, `assigned`, `in_progress`, `completed`), assignments, and addresses. |
| **Support & Disputes** | [`http://localhost:8000/admin/tickets`](http://localhost:8000/admin/tickets) | Resolve customer/provider disputes, track priority badges, and update ticket states. |

---

### 🔒 Access Control & Security

- Access to any `/admin/*` route is strictly protected by **`AdminMiddleware`** (`app/Http/Middleware/AdminMiddleware.php`).
- Unauthenticated users attempting to access `/admin` are automatically redirected to `/login`.
- Authenticated non-admin users (such as customers or providers) will receive an HTTP `403 Forbidden` response.

---

### ⚡ Managing Admin Users

#### 1. Promote Any User to Admin via Tinker
Run inside Docker container:
```bash
docker exec urban_app_php php artisan tinker --execute="App\Models\User::where('email', 'your_user@example.com')->update(['role' => 'admin']);"
```

#### 2. Re-seed Demo Admin & Sample Data
If you refresh or reset your database:
```bash
docker exec urban_app_php php artisan db:seed
```

#### 3. Run Automated Admin Feature Tests
To verify all permissions and view rendering:
```bash
docker exec urban_app_php php artisan test --filter=AdminTest
```

---

## 🛠 Useful Docker Commands

| Action | Command |
|---|---|
| **Start Containers** | `docker-compose up -d` |
| **Stop Containers** | `docker-compose down` |
| **View Logs** | `docker-compose logs -f` |
| **Artisan CLI** | `docker-compose exec app php artisan <command>` |
| **Composer CLI** | `docker-compose exec app composer <command>` |
| **Run Migrations** | `docker-compose exec app php artisan migrate` |
| **Access PHP Shell** | `docker-compose exec app bash` |

