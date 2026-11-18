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
- **View Dashboard** at `/dashboard`
- **Manage Profile & Password** at `/profile`

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

