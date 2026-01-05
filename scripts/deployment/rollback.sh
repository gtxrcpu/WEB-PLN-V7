#!/bin/bash
# Rollback Script
# Purpose: Rollback to previous version in case of deployment failure
# Usage: ./rollback.sh [commit_hash_or_tag]

set -e

echo "========================================="
echo "⏪ APPLICATION ROLLBACK"
echo "========================================="
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_DIR="/var/www/plnweb"
BACKUP_DIR="storage/backups"
ROLLBACK_TARGET="${1:-HEAD~1}"  # Default to previous commit

echo -e "${YELLOW}⚠ WARNING: This will rollback the application${NC}"
echo "Target: $ROLLBACK_TARGET"
echo ""
read -p "Continue? (yes/no): " confirm

if [ "$confirm" != "yes" ]; then
    echo "Rollback cancelled."
    exit 0
fi

cd "$APP_DIR" || exit 1

echo "🔙 Step 1: Reverting to Previous Version"
echo "-----------------------------------------"
# Stash any changes
git stash

# Checkout previous version
git checkout "$ROLLBACK_TARGET"

echo -e "${GREEN}✓ Code reverted to: $(git log -1 --pretty=format:'%h - %s')${NC}"
echo ""

echo "📦 Step 2: Reinstalling Dependencies"
echo "-----------------------------------------"
# Reinstall composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Rebuild assets if needed
if [ -f package.json ]; then
    npm install --production
    npm run build || npm run production
fi

echo -e "${GREEN}✓ Dependencies reinstalled${NC}"
echo ""

echo "🗄️  Step 3: Rolling Back Database Migrations"
echo "-----------------------------------------"
echo -e "${YELLOW}⚠ Rolling back last migration batch${NC}"

# Rollback last migration batch
php artisan migrate:rollback --force

echo -e "${GREEN}✓ Migrations rolled back${NC}"
echo ""

echo "💾 Step 4: Restoring Database Backup (Optional)"
echo "-----------------------------------------"
# List available backups
echo "Available backups:"
ls -lh "$BACKUP_DIR"/*.sql 2>/dev/null || echo "No backups found"

read -p "Restore from backup? (yes/no): " restore_confirm

if [ "$restore_confirm" == "yes" ]; then
    read -p "Enter backup filename: " backup_file
    
    if [ -f "$BACKUP_DIR/$backup_file" ]; then
        echo "Restoring database..."
        mysql -u root plnweb < "$BACKUP_DIR/$backup_file"
        echo -e "${GREEN}✓ Database restored from backup${NC}"
    else
        echo -e "${RED}✗ Backup file not found${NC}"
    fi
else
    echo "Skipping database restore"
fi
echo ""

echo "🧹 Step 5: Rebuilding Caches"
echo "-----------------------------------------"
# Clear and rebuild caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

composer dump-autoload --optimize

echo -e "${GREEN}✓ Caches rebuilt${NC}"
echo ""

echo "🔄 Step 6: Restarting Services"
echo "-----------------------------------------"
# Restart services
sudo systemctl restart php8.2-fpm || echo -e "${YELLOW}⚠ Could not restart PHP-FPM${NC}"
php artisan queue:restart || echo -e "${YELLOW}⚠ No queue workers${NC}"
sudo systemctl restart nginx || sudo systemctl restart apache2 || echo -e "${YELLOW}⚠ Could not restart web server${NC}"

echo -e "${GREEN}✓ Services restarted${NC}"
echo ""

echo "========================================="
echo -e "${GREEN}✅ ROLLBACK COMPLETE${NC}"
echo "========================================="
echo ""
echo "Current version: $(git log -1 --pretty=format:'%h - %s (%cr)')"
echo ""
echo "Next steps:"
echo "  1. Run post-deployment verification: ./post-deploy.sh"
echo "  2. Check application functionality manually"
echo "  3. Investigate and fix the issue before redeploying"
echo ""
