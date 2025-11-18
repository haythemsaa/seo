#!/bin/bash

# SEO Master Pro - Deployment Script
# This script deploys the application to production

set -e

echo "🚀 Deploying SEO Master Pro..."
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running in production
if [ "$APP_ENV" != "production" ]; then
    echo "${RED}⚠️  Warning: APP_ENV is not set to production${NC}"
    read -p "Continue anyway? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Enable maintenance mode
echo "${YELLOW}🔒 Enabling maintenance mode...${NC}"
php artisan down --retry=60
echo "${GREEN}✓ Maintenance mode enabled${NC}"

# Pull latest changes
echo ""
echo "${YELLOW}📥 Pulling latest changes from git...${NC}"
git pull origin main
echo "${GREEN}✓ Changes pulled${NC}"

# Install/Update Composer dependencies (production)
echo ""
echo "${YELLOW}📦 Installing Composer dependencies (production)...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
echo "${GREEN}✓ Composer dependencies installed${NC}"

# Install/Update NPM dependencies
echo ""
echo "${YELLOW}📦 Installing NPM dependencies...${NC}"
npm ci
echo "${GREEN}✓ NPM dependencies installed${NC}"

# Build assets
echo ""
echo "${YELLOW}🎨 Building frontend assets for production...${NC}"
npm run build
echo "${GREEN}✓ Assets built${NC}"

# Run database migrations
echo ""
echo "${YELLOW}🗄️  Running database migrations...${NC}"
php artisan migrate --force
echo "${GREEN}✓ Migrations completed${NC}"

# Clear and cache config
echo ""
echo "${YELLOW}⚡ Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo "${GREEN}✓ Application optimized${NC}"

# Restart queue workers
echo ""
echo "${YELLOW}🔄 Restarting queue workers...${NC}"
php artisan queue:restart
echo "${GREEN}✓ Queue workers restarted${NC}"

# Restart Horizon
echo ""
echo "${YELLOW}🔄 Restarting Horizon...${NC}"
php artisan horizon:terminate
echo "${GREEN}✓ Horizon terminated (supervisor will restart it)${NC}"

# Clear OPcache (if OPcache is enabled)
echo ""
echo "${YELLOW}🧹 Clearing OPcache...${NC}"
php artisan opcache:clear 2>/dev/null || echo "OPcache clear not available"

# Set permissions
echo ""
echo "${YELLOW}🔐 Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache
echo "${GREEN}✓ Permissions set${NC}"

# Disable maintenance mode
echo ""
echo "${YELLOW}🔓 Disabling maintenance mode...${NC}"
php artisan up
echo "${GREEN}✓ Maintenance mode disabled${NC}"

# Run health check
echo ""
echo "${YELLOW}🏥 Running health check...${NC}"
if curl -f -s -o /dev/null http://localhost; then
    echo "${GREEN}✓ Application is responding${NC}"
else
    echo "${RED}✗ Application is not responding${NC}"
    echo "${RED}Please check the logs${NC}"
fi

echo ""
echo "${GREEN}========================================${NC}"
echo "${GREEN}✓ Deployment completed successfully!${NC}"
echo "${GREEN}========================================${NC}"
echo ""
echo "Next steps:"
echo "  - Check application logs: ${YELLOW}tail -f storage/logs/laravel.log${NC}"
echo "  - Monitor queue workers: ${YELLOW}php artisan horizon:status${NC}"
echo "  - Check supervisor status: ${YELLOW}supervisorctl status${NC}"
echo ""
