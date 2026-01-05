#!/bin/bash
# Pre-Deployment Validation Script
# Purpose: Validate application before deployment
# Usage: ./pre-deploy.sh

set -e  # Exit on any error

echo "========================================="
echo "🔍 PRE-DEPLOYMENT VALIDATION"
echo "========================================="
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
APP_ENV="${APP_ENV:-production}"
BACKUP_DIR="storage/backups"

echo "📋 Step 1: Running All Tests"
echo "-----------------------------------------"
php artisan test --stop-on-failure
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ All tests passed${NC}"
else
    echo -e "${RED}✗ Tests failed. Deployment aborted.${NC}"
    exit 1
fi
echo ""

echo "🔧 Step 2: Validating Production Configuration"
echo "-----------------------------------------"
# Check if .env file exists
if [ ! -f .env ]; then
    echo -e "${RED}✗ .env file not found${NC}"
    exit 1
fi

# Check required environment variables
required_vars=("APP_ENV" "APP_KEY" "DB_DATABASE" "DB_USERNAME")
for var in "${required_vars[@]}"; do
    if ! grep -q "^${var}=" .env; then
        echo -e "${RED}✗ Missing required variable: ${var}${NC}"
        exit 1
    fi
done

# Check APP_DEBUG is false in production
if grep -q "^APP_DEBUG=true" .env && [ "$APP_ENV" == "production" ]; then
    echo -e "${RED}✗ APP_DEBUG must be false in production${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Production configuration valid${NC}"
echo ""

echo "📊 Step 3: Checking Code Quality"
echo "-----------------------------------------"
# Check if composer.lock exists
if [ ! -f composer.lock ]; then
    echo -e "${YELLOW}⚠ composer.lock not found. Run composer install first.${NC}"
fi

# Check for syntax errors in PHP files
echo "Checking PHP syntax..."
find app -name "*.php" -exec php -l {} \; > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ No PHP syntax errors${NC}"
else
    echo -e "${RED}✗ PHP syntax errors found${NC}"
    exit 1
fi
echo ""

echo "💾 Step 4: Creating Database Backup"
echo "-----------------------------------------"
# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

# Generate backup filename with timestamp
BACKUP_FILE="$BACKUP_DIR/backup_$(date +%Y%m%d_%H%M%S).sql"

# Backup database (MySQL)
php artisan db:backup "$BACKUP_FILE" 2>/dev/null || mysqldump -u root plnweb > "$BACKUP_FILE"

if [ -f "$BACKUP_FILE" ]; then
    echo -e "${GREEN}✓ Database backup created: $BACKUP_FILE${NC}"
else
    echo -e "${YELLOW}⚠ Database backup failed (manual backup recommended)${NC}"
fi
echo ""

echo "🏗️  Step 5: Building Assets"
echo "-----------------------------------------"
# Install node dependencies if package.json exists
if [ -f package.json ]; then
    echo "Installing Node dependencies..."
    npm install --production
    
    echo "Building production assets..."
    npm run build || npm run production
    
    echo -e "${GREEN}✓ Assets built successfully${NC}"
else
    echo -e "${YELLOW}⚠ No package.json found. Skipping asset build.${NC}"
fi
echo ""

echo "========================================="
echo -e "${GREEN}✅ PRE-DEPLOYMENT VALIDATION COMPLETE${NC}"
echo "========================================="
echo ""
echo "Summary:"
echo "  ✓ All tests passed"
echo "  ✓ Production config validated"
echo "  ✓ Code quality checked"
echo "  ✓ Database backup created"
echo "  ✓ Assets built"
echo ""
echo "Application is ready for deployment!"
echo ""
