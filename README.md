# TaskFlow - Project Management and Time Control Platform

TaskFlow is a comprehensive project management platform built with Laravel that provides project tracking, time control, client approvals, and advanced reporting capabilities for freelancers and small teams managing multiple projects and clients.

## 🚀 Getting Started

### Prerequisites

Make sure you have the following installed on your system:

- **Docker Desktop** - [Download here](https://www.docker.com/products/docker-desktop) (Required)
- **Composer** - [Install guide](https://getcomposer.org/download/) (Required)  
- **Git** - [Download here](https://git-scm.com/downloads) (Required)
- **Node.js 18+** - [Download here](https://nodejs.org/) (Optional - for frontend development)

### Quick Setup (< 15 minutes)

1. **Clone the repository**
   ```bash
   git clone https://github.com/rickgomes43/taskflow.git
   cd taskflow
   ```

2. **Copy environment configuration**
   ```bash
   cp .env.example .env
   ```

3. **Install PHP dependencies**
   ```bash
   composer install
   ```

4. **Generate application key**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

5. **Start Docker containers**
   ```bash
   ./vendor/bin/sail up -d
   ```
   
   Wait for all containers to be ready (usually 30-60 seconds).

6. **Run database migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

7. **Access the application**
   - Open your browser and navigate to: **http://localhost**
   - You should see the Laravel welcome page
   - API endpoints are available at: **http://localhost/api**

### ✅ Verification & Health Check

Run these commands to verify your setup is working correctly:

```bash
# Check all containers are running
./vendor/bin/sail ps

# Test database connection and migrations
./vendor/bin/sail artisan migrate:status

# Test Redis connection (cache/sessions)
./vendor/bin/sail artisan tinker --execute="echo 'Redis connection: ' . \Illuminate\Support\Facades\Redis::ping();"

# Run basic tests
./vendor/bin/sail artisan test

# Check application configuration
./vendor/bin/sail artisan config:show app

# View container logs (if issues occur)
./vendor/bin/sail logs
```

**Expected Results:**
- `sail ps` should show 4 containers running (app, mysql, redis, mailpit)
- Database should show migrations table exists
- Redis should return "PONG"
- Tests should pass without errors

## 🏗️ Architecture

### Services

| Service | Port | Description |
|---------|------|-------------|
| **Laravel App** | 80 | Main application server |
| **MySQL** | 3306 | Primary database |
| **Redis** | 6379 | Cache and session store |
| **Vite** | 5173 | Development asset server |

### Application Structure

TaskFlow follows standard Laravel 11 conventions for maximum maintainability and developer familiarity:

```
app/
├── Http/
│   ├── Controllers/     # HTTP request handlers
│   ├── Middleware/      # Custom middleware classes
│   └── Requests/        # Form request validation
├── Models/              # Eloquent models
├── Services/            # Business logic services
├── Repositories/        # Data access layer (if needed)
├── Enums/              # PHP 8.1+ enums for constants
├── Traits/             # Reusable code traits
├── Events/             # Application events
├── Listeners/          # Event handlers
├── Jobs/               # Background jobs
├── Mail/               # Mail classes
└── Observers/          # Model observers
```

### Tech Stack

- **Backend**: Laravel 11, PHP 8.4
- **Database**: MySQL 8.0
- **Cache/Sessions**: Redis Alpine
- **Frontend**: (To be configured - Tailwind CSS + ShadCN UI)
- **Container**: Docker + Laravel Sail
- **Testing**: PHPUnit
- **Architecture**: Standard Laravel MVC with Service Layer

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

## 🔧 Development Workflow

### Daily Development Commands

```bash
# Start your development session
./vendor/bin/sail up -d

# View real-time logs (optional)
./vendor/bin/sail logs -f

# Access the application container shell
./vendor/bin/sail shell

# Stop containers at end of session
./vendor/bin/sail down
```

### Database Operations

```bash
# Run new migrations
./vendor/bin/sail artisan migrate

# Reset database (⚠️ destroys all data)
./vendor/bin/sail artisan migrate:fresh

# Seed database with sample data
./vendor/bin/sail artisan db:seed

# Reset and seed (for testing)
./vendor/bin/sail artisan migrate:fresh --seed

# Check migration status
./vendor/bin/sail artisan migrate:status
```

### Testing & Code Quality

```bash
# Run all tests
./vendor/bin/sail test

# Run specific test file
./vendor/bin/sail test tests/Feature/ExampleTest.php

# Run tests with coverage
./vendor/bin/sail test --coverage

# Code formatting (when configured)
./vendor/bin/sail composer format
```

### Asset Management (Frontend)

```bash
# Install frontend dependencies
./vendor/bin/sail npm install

# Build assets for development
./vendor/bin/sail npm run dev

# Watch assets for changes
./vendor/bin/sail npm run dev -- --watch

# Build assets for production
./vendor/bin/sail npm run build
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

## 🚨 Troubleshooting Guide

### Common Setup Issues

#### Port Conflicts
If ports 80, 3306, or 6379 are already in use:

```bash
# Check what's using the ports
lsof -i :80
lsof -i :3306  
lsof -i :6379

# Option 1: Stop conflicting services
sudo service apache2 stop  # Ubuntu/Debian
sudo service mysql stop

# Option 2: Use alternative ports in .env
APP_PORT=8080
FORWARD_DB_PORT=3307
FORWARD_REDIS_PORT=6380
```

#### Permission Issues (Linux/macOS)
```bash
# Fix storage permissions
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

# Fix Sail permissions
chmod +x vendor/bin/sail
```

#### Container Problems
```bash
# View detailed logs
./vendor/bin/sail logs app
./vendor/bin/sail logs mysql
./vendor/bin/sail logs redis

# Rebuild containers from scratch
./vendor/bin/sail down -v
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d

# Nuclear option: Reset Docker environment
./vendor/bin/sail down -v
docker system prune -a -f --volumes
composer install
./vendor/bin/sail up -d
```

#### Database Issues
```bash
# Check MySQL container health
./vendor/bin/sail exec mysql mysql -u sail -p -e "SELECT 1;"

# Reset database completely
./vendor/bin/sail artisan migrate:fresh

# Check database configuration
./vendor/bin/sail artisan config:show database

# Manual database connection test
./vendor/bin/sail exec mysql mysql -u sail -ptaskflow taskflow
```

### Environment-Specific Issues

#### macOS Silicon (M1/M2)
```bash
# If containers fail to build
export DOCKER_DEFAULT_PLATFORM=linux/amd64
./vendor/bin/sail build --no-cache
```

#### Windows (WSL2)
```bash
# Ensure you're in WSL2 filesystem
cd /home/username/projects/taskflow
# NOT: /mnt/c/projects/taskflow

# Fix line ending issues
git config --global core.autocrlf false
git config --global core.eol lf
```

### Performance Optimization

#### Slow Docker Performance
```bash
# Allocate more resources to Docker Desktop:
# - Memory: 4GB+ recommended
# - CPU: 2+ cores recommended
# - Disk: SSD recommended

# Enable BuildKit for faster builds
export DOCKER_BUILDKIT=1
```

## 📚 Documentation & Resources

### TaskFlow Documentation
- **[Detailed Setup Guide](./docs/setup.md)** - Complete developer setup instructions
- **[Architecture Overview](./docs/architecture.md)** - System design and technical decisions
- **[Sprint Planning](./docs/sprint-planning.md)** - Development roadmap and user stories
- **[Project Requirements](./docs/prd.md)** - Product requirements and specifications

### Laravel Resources
- [Laravel 11 Documentation](https://laravel.com/docs/11.x)
- [Laravel Sail Documentation](https://laravel.com/docs/sail)
- [Laravel Testing Guide](https://laravel.com/docs/testing)

### Development Tools
- [GitHub Repository](https://github.com/rickgomes43/taskflow)
- [GitHub Projects Board](https://github.com/rickgomes43/taskflow/projects)
- [Issue Tracker](https://github.com/rickgomes43/taskflow/issues)

## 🤝 Contributing

### For New Developers

1. **Read the documentation**: Start with [`docs/setup.md`](./docs/setup.md)
2. **Set up your environment**: Follow this README's setup steps
3. **Check the project board**: View current tasks in [GitHub Projects](https://github.com/rickgomes43/taskflow/projects)
4. **Run tests**: Ensure `./vendor/bin/sail test` passes
5. **Make your first commit**: Follow the conventional commit format

### Development Guidelines

```bash
# Before starting work
git checkout main
git pull origin main
git checkout -b feature/your-feature-name

# During development
./vendor/bin/sail test  # Always run tests
./vendor/bin/sail artisan migrate  # Apply new migrations

# Before committing
./vendor/bin/sail test
git add .
git commit -m "feat: add your feature description"
```

## 📊 Project Status & Health

### Sprint 0 - ✅ COMPLETED
- [x] Laravel 11 with Docker Sail configured
- [x] MySQL + Redis + Mailpit services working
- [x] Environment configuration documented
- [x] Basic project structure established
- [x] Documentation foundation created
- [x] GitHub repository and project board setup

### Current Sprint - Sprint 1 (In Progress)
- [x] Base structure and comprehensive documentation (US003) - **COMPLETED** ✅
- [x] Standard Laravel folder structure implementation
- [x] Complete developer onboarding guide
- [x] Production-ready configuration templates

### Health Metrics
- **Setup Time**: ⏱️ < 15 minutes on fresh machine
- **Test Coverage**: 🎯 Target: 80%+ (Current: Basic tests passing)
- **Documentation**: 📖 Comprehensive setup + architecture docs
- **Developer Experience**: 🚀 One-command environment setup

---

## 📝 Project Information

**Project**: TaskFlow - Project Management Platform  
**Version**: v0.1.0 (Sprint 0 Complete)  
**Stack**: Laravel 11 + Docker + MySQL + Redis  
**Repository**: https://github.com/rickgomes43/taskflow  
**Created**: August 7, 2025  
**Status**: ✅ Ready for Active Development  

**Next Milestone**: Complete base structure and documentation (Sprint 1)

---
*⚡ Powered by Laravel Sail + Docker | 🏗️ Built for Scalability | 📱 Mobile-First Design*
