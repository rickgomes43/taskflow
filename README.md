# TaskFlow - Project Management and Time Control Platform

TaskFlow is a comprehensive project management platform built with Laravel that provides project tracking, time control, client approvals, and advanced reporting capabilities.

## 🚀 Quick Start

### Prerequisites

- **Docker Desktop** - [Download here](https://www.docker.com/products/docker-desktop)
- **Composer** - [Install guide](https://getcomposer.org/download/)
- **Git** - [Download here](https://git-scm.com/downloads)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd taskflow
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Start the Docker containers**
   ```bash
   ./vendor/bin/sail up -d
   ```

4. **Run database migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

5. **Access the application**
   - Open your browser and go to: http://localhost
   - The Laravel welcome page should be displayed

### 🧪 Verification Commands

Verify your setup is working correctly:

```bash
# Check container status
./vendor/bin/sail ps

# Test database connection
./vendor/bin/sail artisan migrate:status

# Test Redis connection
./vendor/bin/sail artisan tinker --execute="echo 'Redis test: ' . \Illuminate\Support\Facades\Redis::ping();"

# Stop containers (when needed)
./vendor/bin/sail down

# View logs
./vendor/bin/sail logs
```

## 🏗️ Architecture

### Services

| Service | Port | Description |
|---------|------|-------------|
| **Laravel App** | 80 | Main application server |
| **MySQL** | 3306 | Primary database |
| **Redis** | 6379 | Cache and session store |
| **Vite** | 5173 | Development asset server |

### Tech Stack

- **Backend**: Laravel 11, PHP 8.4
- **Database**: MySQL 8.0
- **Cache/Sessions**: Redis Alpine
- **Frontend**: (To be configured - Tailwind CSS + ShadCN UI)
- **Container**: Docker + Laravel Sail
- **Testing**: PHPUnit

## 📊 Environment Configuration

Key environment variables (already configured in `.env`):

```env
# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=taskflow
DB_USERNAME=sail
DB_PASSWORD=password

# Cache & Sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
```

## 🔧 Development Commands

```bash
# Start development environment
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Fresh migration (reset database)
./vendor/bin/sail artisan migrate:fresh

# Generate application key
./vendor/bin/sail artisan key:generate

# Access container shell
./vendor/bin/sail shell

# Run Artisan commands
./vendor/bin/sail artisan <command>

# Install NPM packages (when frontend is configured)
./vendor/bin/sail npm install

# Run tests (when configured)
./vendor/bin/sail test
```

## 📋 Project Status

### Sprint 0 - Completed ✅

- [x] Laravel 11 with Sail configured
- [x] Docker Compose with Laravel + MySQL + Redis
- [x] Database migrations running successfully
- [x] Redis configured for cache and sessions
- [x] Port mappings configured (80, 3306, 6379)
- [x] Data persistence across container restarts
- [x] Application accessible at http://localhost

### Next Steps - Sprint 1

- [ ] Configure Tailwind CSS + ShadCN UI
- [ ] Set up automated testing (PHPUnit)
- [ ] Implement authentication system
- [ ] GitHub Actions CI/CD pipeline

## 🚨 Troubleshooting

### Port conflicts
If ports 80, 3306, or 6379 are already in use:
```bash
# Check what's using the ports
lsof -i :80
lsof -i :3306
lsof -i :6379

# Stop conflicting services or modify .env:
APP_PORT=8080
FORWARD_DB_PORT=3307
FORWARD_REDIS_PORT=6380
```

### Container issues
```bash
# View logs
./vendor/bin/sail logs

# Rebuild containers
./vendor/bin/sail build --no-cache

# Reset everything
./vendor/bin/sail down -v
docker system prune -f
./vendor/bin/sail up -d
```

### Database connection issues
```bash
# Verify MySQL is running and healthy
./vendor/bin/sail ps

# Check database configuration
./vendor/bin/sail artisan config:show database

# Reset database
./vendor/bin/sail artisan migrate:fresh
```

## 📚 Documentation

- [Laravel Sail Documentation](https://laravel.com/docs/sail)
- [Project Documentation](./docs/)
- [Sprint Planning](./docs/sprint-planning.md)
- [Architecture Overview](./docs/architecture.md)

---

**Setup Time**: ⏱️ ~15 minutes on a fresh machine
**Status**: ✅ Sprint 0 Complete - Environment Ready for Development

*Created: August 7, 2025*
*Sprint 0 - Setup & Foundation*
