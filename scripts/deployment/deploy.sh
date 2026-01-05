#!/bin/bash
# Deployment Script
# Purpose: Deploy application to production
# Usage: ./deploy.sh

set -e  # Exit on any error

echo "========================================="
echo "🚀 APPLICATION DEPLOYMENT"
echo "========================================="
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_DIR="/var/www/plnweb"  # Adjust to your production path
GIT_BRANCH="${GIT_BRANCH:-main}"

echo "📥 Step 1: Pulling Latest Code"
echo "-----------------------------------------"
cd "$APP_DIR" || exit 1

# Stash any local changes
git stash

# Pull latest code
git fetch origin
git checkout "$GIT_BRANCH"
git pull origin "$GIT_BRANCH"

echo -e "${GREEN}✓ Code updated to latest version${NC}"
echo ""

echo "📦 Step 2: Installing Dependencies (Production Mode)"
echo "-----------------------------------------"
# Install Composer dependencies (production, optimized)
composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${GREEN}✓ Composer dependencies installed${NC}"

# Install NPM dependencies if needed
if [ -f package.json ]; then
    npm install --production
    npm run build || npm run production
    echo -e "${GREEN}✓ Node dependencies installed and built${NC}"
fi
echo ""

echo "🗄️  Step 3: Running Database Migrations"
echo "-----------------------------------------"
# Run migrations (with backup safety)
php artisan migrate --force

echo -e "${GREEN}✓ Database migrations completed${NC}"
echo ""

echo "🧹 Step 4: Clearing and Rebuilding Caches"
echo "-----------------------------------------"
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild optimized caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate optimized autoload files
composer dump-autoload --optimize

echo -e "${GREEN}✓ Caches cleared and rebuilt${NC}"
echo ""

echo "🔄 Step 5: Restarting Services"
echo "-----------------------------------------"
# Restart PHP-FPM (adjust service name based on your system)
sudo systemctl restart php8.2-fpm || echo -e "${YELLOW}⚠ Could not restart PHP-FPM${NC}"

# Restart queue workers if running
php artisan queue:restart || echo -e "${YELLOW}⚠ No queue workers to restart${NC}"

# Restart web server (nginx/apache)
sudo systemctl restart nginx || sudo systemctl restart apache2 || echo -e "${YELLOW}⚠ Could not restart web server${NC}"

echo -e "${GREEN}✓ Services restarted${NC}"
echo ""

echo "🔒 Step 6: Setting Permissions"
echo "-----------------------------------------"
# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo -e "${GREEN}✓ Permissions set${NC}"
echo ""

echo "========================================="
echo -e "${GREEN}✅ DEPLOYMENT COMPLETE${NC}"
echo "========================================="
echo ""
echo "Deployed: $(git log -1 --pretty=format:'%h - %s (%cr)')"
echo ""
echo "Next: Run post-deployment verification"
echo "  ./post-deploy.sh"
echo ""
