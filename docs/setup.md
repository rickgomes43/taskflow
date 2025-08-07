# TaskFlow - Complete Developer Setup Guide

This guide provides comprehensive instructions for setting up the TaskFlow development environment on any system. Follow these steps to have a fully functional development environment running in less than 30 minutes.

## Table of Contents
- [System Requirements](#system-requirements)
- [Pre-Installation Setup](#pre-installation-setup) 
- [Step-by-Step Installation](#step-by-step-installation)
- [Environment Configuration](#environment-configuration)
- [Verification & Testing](#verification--testing)
- [IDE Configuration](#ide-configuration)
- [Troubleshooting](#troubleshooting)
- [Advanced Configuration](#advanced-configuration)

---

## System Requirements

### Minimum Hardware Requirements
- **RAM**: 4GB+ (8GB recommended for optimal performance)
- **Storage**: 5GB free disk space
- **CPU**: Any modern processor (Intel/AMD/Apple Silicon supported)
- **Network**: Stable internet connection for initial setup

### Supported Operating Systems
- **macOS**: 10.15+ (Catalina and later)
- **Windows**: Windows 10/11 with WSL2
- **Linux**: Ubuntu 20.04+, Debian 10+, CentOS 8+, or equivalent

---

## Pre-Installation Setup

### 1. Install Docker Desktop

**macOS:**
```bash
# Download from official site
open https://docs.docker.com/desktop/install/mac-install/

# Or install via Homebrew
brew install --cask docker
```

**Windows:**
```bash
# Download and install Docker Desktop for Windows
# Ensure WSL2 backend is enabled
```

**Linux (Ubuntu/Debian):**
```bash
# Install Docker Engine
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Add user to docker group
sudo usermod -aG docker $USER
newgrp docker

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### 2. Install Composer

**macOS:**
```bash
# Using Homebrew (recommended)
brew install composer

# Or download directly
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
```

**Windows:**
```bash
# Download and install from: https://getcomposer.org/Composer-Setup.exe
```

**Linux:**
```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### 3. Install Git

**macOS:**
```bash
# Install via Xcode Command Line Tools
xcode-select --install

# Or via Homebrew
brew install git
```

**Windows:**
```bash
# Download from: https://git-scm.com/download/win
```

**Linux:**
```bash
# Ubuntu/Debian
sudo apt update && sudo apt install git

# CentOS/RHEL
sudo yum install git
```

### 4. Install Node.js (Optional - for frontend development)

```bash
# macOS (via Homebrew)
brew install node@18

# Linux/Windows - Use Node Version Manager (NVM)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
nvm install 18
nvm use 18
```

---

## Step-by-Step Installation

### Step 1: Clone the Repository

```bash
# Clone from GitHub
git clone https://github.com/rickgomes43/taskflow.git
cd taskflow

# Verify you're in the correct directory
pwd
ls -la
```

### Step 2: Copy Environment File

```bash
# Copy the environment template
cp .env.example .env

# Verify the file was created
ls -la .env*
```

### Step 3: Install PHP Dependencies

```bash
# Install all Composer dependencies
composer install --optimize-autoloader

# Verify vendor directory was created
ls -la vendor/
```

### Step 4: Generate Application Key

```bash
# Generate Laravel application key
./vendor/bin/sail artisan key:generate

# Verify the key was generated in .env file
grep APP_KEY .env
```

### Step 5: Start Docker Containers

```bash
# Start all containers in detached mode
./vendor/bin/sail up -d

# Wait for containers to be healthy (30-60 seconds)
./vendor/bin/sail ps

# Expected output: 4 containers running
# - taskflow_laravel.test-1
# - taskflow_mysql-1  
# - taskflow_redis-1
# - taskflow_mailpit-1
```

### Step 6: Initialize Database

```bash
# Run database migrations
./vendor/bin/sail artisan migrate

# Expected output: Migration table created successfully
# Verify migration status
./vendor/bin/sail artisan migrate:status
```

### Step 7: Access Application

Open your browser and navigate to:
- **Main Application**: http://localhost
- **Database**: http://localhost:3306 (via MySQL client)
- **Redis**: http://localhost:6379 (via Redis client)
- **Mail Testing**: http://localhost:8025 (Mailpit interface)

---

## Environment Configuration

### Understanding the .env File

The `.env` file contains all environment-specific configuration. Key sections include:

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=mysql          # Container name for Docker
DB_PORT=3306
DB_DATABASE=taskflow
DB_USERNAME=sail
DB_PASSWORD=password
```

#### Cache and Sessions (Redis)
```env
CACHE_STORE=redis
SESSION_DRIVER=redis
REDIS_HOST=redis       # Container name for Docker
REDIS_PORT=6379
```

#### TaskFlow-Specific Settings
```env
# Time tracking
TASKFLOW_MAX_DAILY_HOURS=12
TASKFLOW_BREAK_REMINDER_MINUTES=60

# File uploads
TASKFLOW_MAX_FILE_SIZE=10240
TASKFLOW_ALLOWED_FILE_TYPES="jpg,jpeg,png,gif,pdf,doc,docx"

# Notifications
TASKFLOW_SEND_EMAIL_NOTIFICATIONS=true
```

### Port Customization

If default ports conflict with existing services:

```env
# Custom ports
APP_PORT=8080
FORWARD_DB_PORT=3307
FORWARD_REDIS_PORT=6380
VITE_PORT=5174
```

---

## Verification & Testing

### System Health Check

Run these commands to ensure everything is working:

```bash
# 1. Check container status
./vendor/bin/sail ps
# Should show 4 containers running

# 2. Test database connection
./vendor/bin/sail artisan migrate:status
# Should show migration table exists

# 3. Test Redis connection  
./vendor/bin/sail artisan tinker --execute="echo 'Redis: ' . \Illuminate\Support\Facades\Redis::ping();"
# Should return "PONG"

# 4. Test basic application
curl -I http://localhost
# Should return HTTP 200

# 5. Run test suite
./vendor/bin/sail test
# All tests should pass
```

### Database Verification

```bash
# Connect to MySQL and verify database
./vendor/bin/sail exec mysql mysql -u sail -p
# Password: password

# Inside MySQL:
SHOW DATABASES;
USE taskflow;
SHOW TABLES;
```

### Laravel Application Check

```bash
# Check Laravel configuration
./vendor/bin/sail artisan config:show app
./vendor/bin/sail artisan config:show database

# Generate sample data (optional)
./vendor/bin/sail artisan db:seed

# Clear and rebuild cache
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
```

---

## IDE Configuration

### Visual Studio Code

Install recommended extensions:

```json
{
  "recommendations": [
    "ms-vscode-remote.remote-containers",
    "bmewburn.vscode-intelephense-client",
    "onecentlin.laravel-blade",
    "ryannaddy.laravel-artisan",
    "codingyu.laravel-goto-view",
    "ms-azuretools.vscode-docker"
  ]
}
```

### PHPStorm Configuration

1. **Configure PHP Interpreter**:
   - File → Settings → PHP
   - Add Docker-based Remote Interpreter
   - Point to `./vendor/bin/sail exec app php`

2. **Database Connection**:
   - Host: `localhost`
   - Port: `3306` (or custom FORWARD_DB_PORT)
   - User: `sail`
   - Password: `password`
   - Database: `taskflow`

### Development Container Support

For VSCode with Remote-Containers:

```json
// .devcontainer/devcontainer.json
{
  "name": "TaskFlow Laravel Sail",
  "dockerComposeFile": "../docker-compose.yml",
  "service": "laravel.test",
  "workspaceFolder": "/var/www/html",
  "forwardPorts": [80, 3306, 6379, 5173, 8025],
  "extensions": [
    "bmewburn.vscode-intelephense-client",
    "onecentlin.laravel-blade"
  ]
}
```

---

## Troubleshooting

### Common Issues and Solutions

#### Issue: Containers Won't Start
```bash
# Check for port conflicts
lsof -i :80 :3306 :6379

# Stop conflicting services
sudo service apache2 stop
sudo service mysql stop

# Or use custom ports in .env
```

#### Issue: Permission Denied (Linux/macOS)
```bash
# Fix storage permissions
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

# Make Sail executable
chmod +x vendor/bin/sail
```

#### Issue: Database Connection Failed
```bash
# Verify MySQL container is running
./vendor/bin/sail exec mysql mysql -u sail -p

# Reset database if corrupted
./vendor/bin/sail artisan migrate:fresh
```

#### Issue: Redis Connection Failed
```bash
# Test Redis container
./vendor/bin/sail exec redis redis-cli ping

# Check Redis configuration
./vendor/bin/sail artisan config:show cache
```

### Platform-Specific Issues

#### macOS Silicon (M1/M2)
```bash
# If containers fail to build
export DOCKER_DEFAULT_PLATFORM=linux/amd64
./vendor/bin/sail build --no-cache
```

#### Windows WSL2
```bash
# Ensure project is in WSL2 filesystem
cd /home/username/projects/taskflow
# NOT: /mnt/c/projects/taskflow

# Fix line endings
git config --global core.autocrlf false
```

#### Linux Memory Issues
```bash
# Increase Docker memory limit
# Edit /etc/docker/daemon.json:
{
  "default-shm-size": "2G",
  "default-ulimits": {
    "memlock": {"name": "memlock", "soft": -1, "hard": -1}
  }
}
```

### Getting Help

If you encounter issues not covered here:

1. **Check the logs**: `./vendor/bin/sail logs`
2. **Search issues**: [GitHub Issues](https://github.com/rickgomes43/taskflow/issues)
3. **Laravel Sail docs**: [Official Documentation](https://laravel.com/docs/sail)
4. **Create an issue**: Include system info, error messages, and steps to reproduce

---

## Advanced Configuration

### Performance Optimization

#### Docker Performance Tuning
```bash
# Allocate more resources in Docker Desktop:
# - Memory: 6GB+
# - CPU cores: 4+
# - Enable "Use gRPC FUSE for file sharing"

# Enable BuildKit
export DOCKER_BUILDKIT=1
```

#### Laravel Optimization
```bash
# Production-like optimizations for development
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache

# Clear all cache when needed
./vendor/bin/sail artisan optimize:clear
```

### Multiple Environment Setup

For running multiple environments (staging, testing):

```bash
# Copy and customize environment files
cp .env.example .env.staging
cp .env.example .env.testing

# Use custom compose files
docker-compose -f docker-compose.staging.yml up -d
```

### Custom Services

Add additional services to `docker-compose.yml`:

```yaml
services:
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch:8.8.0
    environment:
      - discovery.type=single-node
      - xpack.security.enabled=false
    ports:
      - "9200:9200"
    
  minio:
    image: minio/minio:latest
    command: server /data --console-address ":9001"
    ports:
      - "9000:9000"
      - "9001:9001"
```

---

## Understanding the Laravel Structure

TaskFlow follows standard Laravel 11 conventions for maximum developer familiarity:

```
app/
├── Http/
│   ├── Controllers/     # Handle HTTP requests and route logic
│   ├── Middleware/      # Request/response filtering and processing
│   └── Requests/        # Form validation rules and authorization
├── Models/              # Database models using Eloquent ORM
├── Services/            # Business logic services and complex operations
├── Repositories/        # Data access patterns (optional, for complex queries)
├── Enums/              # PHP 8.1+ enumerations for constants
├── Traits/             # Reusable code patterns and mixins
├── Events/             # Application events for decoupled architecture
├── Listeners/          # Event handlers and observers
├── Jobs/               # Background/queue jobs for async processing
├── Mail/               # Email classes and templates
└── Observers/          # Model lifecycle hooks and automation
```

## Next Steps

Once your environment is set up successfully:

1. **Explore the codebase**: Review `app/` structure following Laravel conventions
2. **Read the documentation**: Check [`docs/architecture.md`](./architecture.md)  
3. **Run the test suite**: Ensure all tests pass with `./vendor/bin/sail test`
4. **Check the project board**: View tasks at [GitHub Projects](https://github.com/rickgomes43/taskflow/projects)
5. **Start development**: Pick up a task and start coding!

### Development Workflow

```bash
# Daily development workflow
./vendor/bin/sail up -d     # Start containers
./vendor/bin/sail logs -f   # Monitor logs (optional)

# Your development work here...

./vendor/bin/sail test      # Run tests before committing
./vendor/bin/sail down      # Stop containers when done
```

---

**Setup complete!** 🎉

You now have a fully functional TaskFlow development environment. The setup should take approximately **15-30 minutes** depending on your system and internet speed.

For questions or issues, please check the [troubleshooting section](#troubleshooting) or create an issue on GitHub.

*Last updated: August 7, 2025*