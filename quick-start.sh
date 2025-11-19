#!/bin/bash

################################################################################
# SEO MASTER PRO - QUICK START INSTALLATION SCRIPT
################################################################################
# This script automates the entire installation process
# Compatible with: Ubuntu 20.04+, Debian 11+, CentOS 8+, macOS
################################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_NAME="SEO Master Pro"
PHP_VERSION="8.3"
NODE_VERSION="20"
MYSQL_VERSION="8.0"
REDIS_VERSION="7.0"

################################################################################
# Helper Functions
################################################################################

print_header() {
    echo -e "${BLUE}"
    echo "================================================================================"
    echo "  $1"
    echo "================================================================================"
    echo -e "${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

check_command() {
    if command -v $1 &> /dev/null; then
        print_success "$1 is installed"
        return 0
    else
        print_warning "$1 is not installed"
        return 1
    fi
}

################################################################################
# Welcome Message
################################################################################

clear
print_header "Welcome to $APP_NAME Installation"
echo ""
print_info "This script will install and configure:"
echo "  • PHP $PHP_VERSION with required extensions"
echo "  • Composer (latest)"
echo "  • Node.js $NODE_VERSION & npm"
echo "  • MySQL $MYSQL_VERSION"
echo "  • Redis $REDIS_VERSION"
echo "  • Laravel dependencies"
echo "  • Frontend assets"
echo ""
read -p "Do you want to continue? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    print_error "Installation cancelled"
    exit 1
fi

################################################################################
# System Detection
################################################################################

print_header "Step 1: System Detection"

if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        OS=$NAME
        VER=$VERSION_ID
        print_info "Detected OS: $OS $VER"
    fi
elif [[ "$OSTYPE" == "darwin"* ]]; then
    OS="macOS"
    print_info "Detected OS: macOS"
else
    print_error "Unsupported operating system: $OSTYPE"
    exit 1
fi

################################################################################
# Check Existing Installations
################################################################################

print_header "Step 2: Checking Existing Software"

check_command php && PHP_INSTALLED=true || PHP_INSTALLED=false
check_command composer && COMPOSER_INSTALLED=true || COMPOSER_INSTALLED=false
check_command node && NODE_INSTALLED=true || NODE_INSTALLED=false
check_command mysql && MYSQL_INSTALLED=true || MYSQL_INSTALLED=false
check_command redis-server && REDIS_INSTALLED=true || REDIS_INSTALLED=false

################################################################################
# Installation Mode Selection
################################################################################

print_header "Step 3: Installation Mode"

echo "Select installation mode:"
echo "1) Full Installation (Install all missing dependencies)"
echo "2) Application Only (Skip system dependencies)"
echo "3) Development Mode (Install dev tools + all dependencies)"
echo ""
read -p "Enter your choice (1-3): " INSTALL_MODE

################################################################################
# Install System Dependencies
################################################################################

if [[ "$INSTALL_MODE" == "1" ]] || [[ "$INSTALL_MODE" == "3" ]]; then
    print_header "Step 4: Installing System Dependencies"

    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        print_info "Updating package lists..."
        sudo apt-get update -qq

        if [ "$PHP_INSTALLED" = false ]; then
            print_info "Installing PHP $PHP_VERSION..."
            sudo apt-get install -y software-properties-common
            sudo add-apt-repository -y ppa:ondrej/php
            sudo apt-get update -qq
            sudo apt-get install -y \
                php${PHP_VERSION} \
                php${PHP_VERSION}-cli \
                php${PHP_VERSION}-fpm \
                php${PHP_VERSION}-mysql \
                php${PHP_VERSION}-redis \
                php${PHP_VERSION}-mbstring \
                php${PHP_VERSION}-xml \
                php${PHP_VERSION}-bcmath \
                php${PHP_VERSION}-curl \
                php${PHP_VERSION}-gd \
                php${PHP_VERSION}-zip \
                php${PHP_VERSION}-intl
            print_success "PHP $PHP_VERSION installed"
        fi

        if [ "$MYSQL_INSTALLED" = false ]; then
            print_info "Installing MySQL..."
            sudo apt-get install -y mysql-server
            sudo systemctl start mysql
            sudo systemctl enable mysql
            print_success "MySQL installed"
        fi

        if [ "$REDIS_INSTALLED" = false ]; then
            print_info "Installing Redis..."
            sudo apt-get install -y redis-server
            sudo systemctl start redis-server
            sudo systemctl enable redis-server
            print_success "Redis installed"
        fi

    elif [[ "$OS" == "macOS" ]]; then
        if ! command -v brew &> /dev/null; then
            print_error "Homebrew is required for macOS installation"
            print_info "Install Homebrew from: https://brew.sh"
            exit 1
        fi

        if [ "$PHP_INSTALLED" = false ]; then
            print_info "Installing PHP..."
            brew install php@${PHP_VERSION}
            print_success "PHP installed"
        fi

        if [ "$MYSQL_INSTALLED" = false ]; then
            print_info "Installing MySQL..."
            brew install mysql@${MYSQL_VERSION}
            brew services start mysql@${MYSQL_VERSION}
            print_success "MySQL installed"
        fi

        if [ "$REDIS_INSTALLED" = false ]; then
            print_info "Installing Redis..."
            brew install redis
            brew services start redis
            print_success "Redis installed"
        fi
    fi
fi

################################################################################
# Install Composer
################################################################################

print_header "Step 5: Installing Composer"

if [ "$COMPOSER_INSTALLED" = false ]; then
    print_info "Downloading Composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --quiet
    php -r "unlink('composer-setup.php');"
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    print_success "Composer installed"
else
    print_info "Updating Composer..."
    sudo composer self-update
    print_success "Composer updated"
fi

################################################################################
# Install Node.js & npm
################################################################################

print_header "Step 6: Installing Node.js"

if [ "$NODE_INSTALLED" = false ]; then
    print_info "Installing Node.js $NODE_VERSION..."

    if [[ "$OS" == *"Ubuntu"* ]] || [[ "$OS" == *"Debian"* ]]; then
        curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | sudo -E bash -
        sudo apt-get install -y nodejs
    elif [[ "$OS" == "macOS" ]]; then
        brew install node@${NODE_VERSION}
    fi

    print_success "Node.js installed"
else
    print_info "Node.js is already installed: $(node --version)"
fi

################################################################################
# Configure Application
################################################################################

print_header "Step 7: Configuring Application"

# Check if .env exists
if [ ! -f .env ]; then
    print_info "Creating .env file from .env.example..."
    cp .env.example .env
    print_success ".env file created"

    # Generate application key
    print_info "Generating application key..."
    php artisan key:generate
    print_success "Application key generated"
else
    print_warning ".env file already exists, skipping..."
fi

################################################################################
# Database Configuration
################################################################################

print_header "Step 8: Database Configuration"

read -p "Configure database? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    read -p "Database name [seo_master_pro]: " DB_NAME
    DB_NAME=${DB_NAME:-seo_master_pro}

    read -p "Database username [root]: " DB_USER
    DB_USER=${DB_USER:-root}

    read -sp "Database password: " DB_PASS
    echo

    # Update .env file
    sed -i.bak "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
    sed -i.bak "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
    sed -i.bak "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

    # Create database
    print_info "Creating database..."
    mysql -u$DB_USER -p$DB_PASS -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || {
        print_warning "Could not create database automatically. Please create it manually."
    }

    print_success "Database configured"
fi

################################################################################
# Install Dependencies
################################################################################

print_header "Step 9: Installing Dependencies"

print_info "Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

if [[ "$INSTALL_MODE" == "3" ]]; then
    composer install --dev
fi

print_success "PHP dependencies installed"

print_info "Installing Node.js dependencies..."
npm install

print_success "Node.js dependencies installed"

################################################################################
# Build Assets
################################################################################

print_header "Step 10: Building Frontend Assets"

print_info "Building assets..."
if [[ "$INSTALL_MODE" == "3" ]]; then
    npm run dev
else
    npm run build
fi

print_success "Assets built successfully"

################################################################################
# Database Migration
################################################################################

print_header "Step 11: Database Migration"

read -p "Run database migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Running migrations..."
    php artisan migrate --force
    print_success "Migrations completed"

    read -p "Seed database with demo data? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        print_info "Seeding database..."
        php artisan db:seed
        print_success "Database seeded"
    fi
fi

################################################################################
# Storage & Cache
################################################################################

print_header "Step 12: Configuring Storage & Cache"

print_info "Creating storage symlink..."
php artisan storage:link

print_info "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_success "Optimization completed"

################################################################################
# File Permissions
################################################################################

print_header "Step 13: Setting File Permissions"

print_info "Setting correct permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 755 scripts

if [[ "$OS" != "macOS" ]]; then
    # Set ownership for Linux
    WEBSERVER_USER="www-data"
    if [ -d "/etc/nginx" ]; then
        WEBSERVER_USER="www-data"
    elif [ -d "/etc/httpd" ]; then
        WEBSERVER_USER="apache"
    fi

    sudo chown -R $USER:$WEBSERVER_USER storage bootstrap/cache
fi

print_success "Permissions set"

################################################################################
# Queue & Scheduler Setup
################################################################################

print_header "Step 14: Queue & Scheduler Setup"

read -p "Set up queue worker? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_info "Queue worker setup instructions:"
    echo ""
    echo "For Supervisor (recommended for production):"
    echo "  sudo nano /etc/supervisor/conf.d/seo-master-pro.conf"
    echo ""
    echo "For development, run:"
    echo "  php artisan queue:work --daemon"
    echo ""

    print_info "Laravel Scheduler setup:"
    echo ""
    echo "Add to crontab (run: crontab -e):"
    echo "  * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
    echo ""
fi

################################################################################
# Installation Complete
################################################################################

print_header "Installation Complete! 🎉"

echo ""
print_success "SEO Master Pro has been installed successfully!"
echo ""
print_info "Next Steps:"
echo ""
echo "1. Start the development server:"
echo "   ${GREEN}php artisan serve${NC}"
echo ""
echo "2. Access the application:"
echo "   ${GREEN}http://localhost:8000${NC}"
echo ""
echo "3. Default admin credentials (if seeded):"
echo "   Email: ${GREEN}admin@seo-master-pro.com${NC}"
echo "   Password: ${GREEN}password${NC}"
echo ""
print_info "Additional Commands:"
echo ""
echo "• Run queue worker:     ${BLUE}php artisan queue:work${NC}"
echo "• Run Horizon:          ${BLUE}php artisan horizon${NC}"
echo "• Run tests:            ${BLUE}php artisan test${NC}"
echo "• Check rankings:       ${BLUE}php artisan seo:check-rankings${NC}"
echo "• Generate reports:     ${BLUE}php artisan seo:generate-reports${NC}"
echo ""
print_info "Documentation:"
echo "• README.md             - General documentation"
echo "• DEPLOYMENT.md         - Deployment guide"
echo "• docs/API.md           - API documentation"
echo "• Postman Collection    - docs/SEO_Master_Pro_API.postman_collection.json"
echo ""
print_info "Support:"
echo "• GitHub: https://github.com/your-repo/seo-master-pro"
echo "• Documentation: https://docs.seo-master-pro.com"
echo ""
echo "================================================================================"
echo ""

# Save installation log
INSTALL_LOG="installation-$(date +%Y%m%d-%H%M%S).log"
{
    echo "Installation completed at: $(date)"
    echo "OS: $OS"
    echo "PHP Version: $(php --version | head -n 1)"
    echo "Composer Version: $(composer --version)"
    echo "Node Version: $(node --version)"
    echo "npm Version: $(npm --version)"
} > "$INSTALL_LOG"

print_success "Installation log saved to: $INSTALL_LOG"
echo ""
