#!/bin/bash

# SEO Master Pro - Setup Script
# This script sets up the application for local development

set -e

echo "🚀 Setting up SEO Master Pro..."
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo "${YELLOW}📝 Creating .env file from .env.example...${NC}"
    cp .env.example .env
    echo "${GREEN}✓ .env file created${NC}"
else
    echo "${GREEN}✓ .env file already exists${NC}"
fi

# Install Composer dependencies
echo ""
echo "${YELLOW}📦 Installing Composer dependencies...${NC}"
if command -v composer &> /dev/null; then
    composer install
    echo "${GREEN}✓ Composer dependencies installed${NC}"
else
    echo "${RED}✗ Composer not found. Please install Composer first.${NC}"
    exit 1
fi

# Install NPM dependencies
echo ""
echo "${YELLOW}📦 Installing NPM dependencies...${NC}"
if command -v npm &> /dev/null; then
    npm install
    echo "${GREEN}✓ NPM dependencies installed${NC}"
else
    echo "${RED}✗ NPM not found. Please install Node.js first.${NC}"
    exit 1
fi

# Generate application key
echo ""
echo "${YELLOW}🔑 Generating application key...${NC}"
php artisan key:generate
echo "${GREEN}✓ Application key generated${NC}"

# Create storage symlink
echo ""
echo "${YELLOW}🔗 Creating storage symlink...${NC}"
php artisan storage:link
echo "${GREEN}✓ Storage symlink created${NC}"

# Check if database is configured
echo ""
echo "${YELLOW}🗄️  Checking database configuration...${NC}"
DB_CONNECTION=$(grep DB_CONNECTION .env | cut -d '=' -f2)
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)

if [ -z "$DB_DATABASE" ]; then
    echo "${RED}✗ Database not configured in .env file${NC}"
    echo "${YELLOW}Please configure your database settings in .env and run this script again.${NC}"
    exit 1
fi

echo "${GREEN}✓ Database configured: $DB_CONNECTION - $DB_DATABASE${NC}"

# Ask to run migrations
echo ""
read -p "Do you want to run database migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "${YELLOW}🔄 Running migrations...${NC}"
    php artisan migrate
    echo "${GREEN}✓ Migrations completed${NC}"

    # Ask to seed database
    echo ""
    read -p "Do you want to seed the database with sample data? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "${YELLOW}🌱 Seeding database...${NC}"
        php artisan db:seed
        echo "${GREEN}✓ Database seeded${NC}"
    fi
fi

# Build assets
echo ""
echo "${YELLOW}🎨 Building frontend assets...${NC}"
npm run build
echo "${GREEN}✓ Assets built${NC}"

# Clear caches
echo ""
echo "${YELLOW}🧹 Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "${GREEN}✓ Caches cleared${NC}"

# Set permissions
echo ""
echo "${YELLOW}🔐 Setting permissions...${NC}"
chmod -R 775 storage bootstrap/cache
echo "${GREEN}✓ Permissions set${NC}"

echo ""
echo "${GREEN}========================================${NC}"
echo "${GREEN}✓ Setup completed successfully!${NC}"
echo "${GREEN}========================================${NC}"
echo ""
echo "You can now run the application with:"
echo "  ${YELLOW}php artisan serve${NC}"
echo ""
echo "For queue workers, run:"
echo "  ${YELLOW}php artisan horizon${NC}"
echo ""
echo "For development with hot reload:"
echo "  ${YELLOW}npm run dev${NC}"
echo ""
