# SEO Master Pro - Deployment Guide

This guide covers deploying SEO Master Pro to production environments.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Server Requirements](#server-requirements)
- [Deployment Methods](#deployment-methods)
  - [Option 1: Traditional VPS](#option-1-traditional-vps-digitalocean-linode-ovh)
  - [Option 2: Docker](#option-2-docker)
  - [Option 3: Laravel Forge](#option-3-laravel-forge)
- [Post-Deployment](#post-deployment)
- [Performance Optimization](#performance-optimization)
- [Security Hardening](#security-hardening)
- [Monitoring](#monitoring)
- [Troubleshooting](#troubleshooting)

## Prerequisites

Before deploying, ensure you have:

- [ ] Domain name configured with DNS
- [ ] SSL certificate (Let's Encrypt recommended)
- [ ] Server with SSH access
- [ ] Git repository access
- [ ] Production database credentials
- [ ] All API keys (Google, Stripe, etc.)
- [ ] Email service configured (Mailgun, SendGrid, etc.)

## Server Requirements

### Minimum Specifications

**For up to 100 projects:**
- 4 CPU cores
- 8 GB RAM
- 100 GB SSD storage
- Ubuntu 22.04 LTS or Debian 12

**For up to 500 projects:**
- 8 CPU cores
- 16 GB RAM
- 250 GB SSD storage

**For enterprise (1000+ projects):**
- 16+ CPU cores
- 32+ GB RAM
- 500+ GB SSD storage
- Consider separate database and Redis servers

### Required Software

- PHP 8.3 or higher
- Composer 2.x
- Node.js 20.x or higher
- MySQL 8.0+ or PostgreSQL 16+
- Redis 7.x
- Nginx or Apache
- Supervisor (for queue workers)
- Certbot (for SSL)

## Deployment Methods

### Option 1: Traditional VPS (DigitalOcean, Linode, OVH)

#### Step 1: Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.3 and extensions
sudo apt install -y php8.3 php8.3-fpm php8.3-mysql php8.3-pgsql \
    php8.3-redis php8.3-mbstring php8.3-xml php8.3-bcmath \
    php8.3-curl php8.3-zip php8.3-gd php8.3-intl php8.3-imagick

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL 8.0
sudo apt install -y mysql-server
sudo mysql_secure_installation

# Install Redis
sudo apt install -y redis-server
sudo systemctl enable redis-server

# Install Nginx
sudo apt install -y nginx
sudo systemctl enable nginx

# Install Supervisor
sudo apt install -y supervisor
sudo systemctl enable supervisor
```

#### Step 2: Create Database

```bash
sudo mysql -u root -p
```

```sql
CREATE DATABASE seo_master_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'seomaster'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON seo_master_pro.* TO 'seomaster'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Step 3: Clone Repository

```bash
# Create application directory
sudo mkdir -p /var/www/seo-master-pro
sudo chown -R $USER:$USER /var/www/seo-master-pro

# Clone repository
cd /var/www
git clone https://github.com/haythemsaa/seo.git seo-master-pro
cd seo-master-pro

# Set permissions
sudo chown -R www-data:www-data /var/www/seo-master-pro
sudo chmod -R 755 /var/www/seo-master-pro/storage
sudo chmod -R 755 /var/www/seo-master-pro/bootstrap/cache
```

#### Step 4: Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install JavaScript dependencies
npm ci

# Build assets
npm run build
```

#### Step 5: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with production values
nano .env
```

**Critical .env values for production:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=seo_master_pro
DB_USERNAME=seomaster
DB_PASSWORD=your_strong_password

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=your_redis_password

# Add all your API keys
STRIPE_KEY=pk_live_xxx
STRIPE_SECRET=sk_live_xxx
GOOGLE_CLIENT_ID=xxx
# ... etc
```

#### Step 6: Run Migrations

```bash
php artisan migrate --force

# Optional: Seed sample data
php artisan db:seed
```

#### Step 7: Configure Nginx

```bash
sudo nano /etc/nginx/sites-available/seo-master-pro
```

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;

    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;

    server_name your-domain.com www.your-domain.com;
    root /var/www/seo-master-pro/public;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    # Increase upload size for report uploads
    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/seo-master-pro /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

#### Step 8: Install SSL Certificate

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Auto-renewal is automatic with systemd timer
sudo systemctl status certbot.timer
```

#### Step 9: Configure Supervisor for Queue Workers

```bash
sudo nano /etc/supervisor/conf.d/seo-master-pro.conf
```

```ini
[program:seo-master-pro-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/seo-master-pro/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/seo-master-pro/storage/logs/worker.log
stopwaitsecs=3600

[program:seo-master-pro-horizon]
process_name=%(program_name)s
command=php /var/www/seo-master-pro/artisan horizon
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/seo-master-pro/storage/logs/horizon.log
stopwaitsecs=3600
```

```bash
# Update Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

#### Step 10: Configure Cron Jobs

```bash
sudo crontab -e -u www-data
```

```cron
# Laravel Scheduler
* * * * * cd /var/www/seo-master-pro && php artisan schedule:run >> /dev/null 2>&1

# Backup database daily at 2 AM
0 2 * * * cd /var/www/seo-master-pro && php artisan backup:run
```

#### Step 11: Optimize Laravel

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Option 2: Docker

#### Docker Compose Configuration

Create `docker-compose.yml`:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: seo-master-pro-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - seo-network
    depends_on:
      - db
      - redis

  nginx:
    image: nginx:alpine
    container_name: seo-master-pro-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
      - ./docker/nginx/ssl:/etc/nginx/ssl
    networks:
      - seo-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: seo-master-pro-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_USER: ${DB_USERNAME}
    volumes:
      - db-data:/var/lib/mysql
    networks:
      - seo-network

  redis:
    image: redis:7-alpine
    container_name: seo-master-pro-redis
    restart: unless-stopped
    command: redis-server --appendonly yes
    volumes:
      - redis-data:/data
    networks:
      - seo-network

  horizon:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: seo-master-pro-horizon
    restart: unless-stopped
    working_dir: /var/www
    command: php artisan horizon
    volumes:
      - ./:/var/www
    networks:
      - seo-network
    depends_on:
      - db
      - redis

volumes:
  db-data:
  redis-data:

networks:
  seo-network:
    driver: bridge
```

Create `Dockerfile`:

```dockerfile
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

**Deploy with Docker:**

```bash
# Build and start containers
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate --force

# Cache config
docker-compose exec app php artisan config:cache
```

### Option 3: Laravel Forge

1. **Create Server** on [forge.laravel.com](https://forge.laravel.com)
2. **Connect Repository**: Link your GitHub/GitLab repository
3. **Configure Site**: Set domain and web directory to `/public`
4. **Environment**: Add all `.env` variables via Forge UI
5. **Deploy Script**: Forge provides default deploy script
6. **SSL**: Enable with one click (uses Let's Encrypt)
7. **Queue**: Enable queue worker via Forge UI
8. **Scheduler**: Enabled automatically

**Custom Deploy Script:**

```bash
cd /home/forge/your-domain.com
git pull origin main
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
php artisan horizon:terminate
```

## Post-Deployment

### Verify Installation

```bash
# Check application status
php artisan about

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Test Redis connection
>>> Redis::ping();

# Check queue workers
sudo supervisorctl status

# Check logs
tail -f storage/logs/laravel.log
```

### Create Admin User

```bash
php artisan tinker
```

```php
use App\Models\User;

User::create([
    'name' => 'Admin',
    'email' => 'admin@your-domain.com',
    'password' => bcrypt('your_secure_password'),
    'role' => 'admin',
]);
```

## Performance Optimization

### PHP-FPM Tuning

Edit `/etc/php/8.3/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

### MySQL Optimization

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
[mysqld]
innodb_buffer_pool_size = 4G
innodb_log_file_size = 512M
max_connections = 200
query_cache_size = 0
query_cache_type = 0
```

### Redis Optimization

Edit `/etc/redis/redis.conf`:

```ini
maxmemory 2gb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

### Enable OPcache

Edit `/etc/php/8.3/fpm/php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

## Security Hardening

### Firewall Configuration

```bash
# Install UFW
sudo apt install -y ufw

# Allow SSH, HTTP, HTTPS
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable
```

### Fail2Ban

```bash
# Install Fail2Ban
sudo apt install -y fail2ban

# Configure
sudo nano /etc/fail2ban/jail.local
```

```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5

[sshd]
enabled = true

[nginx-http-auth]
enabled = true
```

```bash
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### Database Security

```sql
-- Remove anonymous users
DELETE FROM mysql.user WHERE User='';

-- Disallow root login remotely
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

-- Remove test database
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';

FLUSH PRIVILEGES;
```

## Monitoring

### Laravel Telescope (Development Only)

```bash
# Install Telescope
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

**Disable in production:**

```php
// app/Providers/TelescopeServiceProvider.php
public function register()
{
    if ($this->app->environment('local')) {
        $this->app->register(TelescopeServiceProvider::class);
    }
}
```

### Error Tracking with Sentry

```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=your-dsn-here
```

### Application Monitoring

**Install monitoring tools:**

- New Relic
- Datadog
- Prometheus + Grafana

### Log Management

```bash
# Install Logrotate
sudo apt install -y logrotate

# Configure log rotation
sudo nano /etc/logrotate.d/seo-master-pro
```

```logrotate
/var/www/seo-master-pro/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

## Troubleshooting

### Common Issues

**500 Internal Server Error:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check Nginx error logs
sudo tail -f /var/log/nginx/error.log

# Check PHP-FPM logs
sudo tail -f /var/log/php8.3-fpm.log
```

**Permission Issues:**
```bash
sudo chown -R www-data:www-data /var/www/seo-master-pro
sudo chmod -R 755 /var/www/seo-master-pro/storage
sudo chmod -R 755 /var/www/seo-master-pro/bootstrap/cache
```

**Queue Not Processing:**
```bash
# Check Supervisor status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart all

# Check Horizon
php artisan horizon:status
```

**Database Connection Failed:**
```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check credentials in .env
cat .env | grep DB_
```

## Backup Strategy

### Automated Backups

Install Laravel Backup:

```bash
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

Configure in `config/backup.php` and add to scheduler.

### Manual Backup

```bash
# Database backup
mysqldump -u seomaster -p seo_master_pro > backup-$(date +%Y%m%d).sql

# Full application backup
tar -czf seo-master-pro-backup-$(date +%Y%m%d).tar.gz /var/www/seo-master-pro
```

## Scaling

### Horizontal Scaling

For high-traffic scenarios:

1. **Load Balancer**: Nginx or HAProxy
2. **Multiple App Servers**: Clone application to multiple servers
3. **Centralized Database**: MySQL replication or RDS
4. **Centralized Redis**: Redis cluster or ElastiCache
5. **Shared Storage**: NFS or S3 for uploads

### Vertical Scaling

- Upgrade server resources (CPU, RAM)
- Optimize database queries
- Implement caching strategies
- Use CDN for static assets

---

**Deployment completed!** Your SEO Master Pro application should now be live and running.

For support, consult the [main README](README.md) or [Frontend Documentation](FRONTEND_README.md).
