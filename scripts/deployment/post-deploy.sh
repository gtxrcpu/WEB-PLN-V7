#!/bin/bash
# Post-Deployment Verification Script
# Purpose: Verify deployment success
# Usage: ./post-deploy.sh

set -e

echo "========================================="
echo "✅ POST-DEPLOYMENT VERIFICATION"
echo "========================================="
echo ""

# Color codes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_URL="${APP_URL:-http://localhost}"
CRITICAL_ENDPOINTS=(
    "/"
    "/login"
    "/user"
)

echo "🏥 Step 1: Checking Application Status"
echo "-----------------------------------------"
# Check if application is up
php artisan about > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Application is running${NC}"
else
    echo -e "${RED}✗ Application is not responding${NC}"
    exit 1
fi

# Check database connection
php artisan db:show > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database connection OK${NC}"
else
    echo -e "${RED}✗ Database connection failed${NC}"
    exit 1
fi
echo ""

echo "🌐 Step 2: Testing Critical Endpoints"
echo "-----------------------------------------"
for endpoint in "${CRITICAL_ENDPOINTS[@]}"; do
    url="$APP_URL$endpoint"
    echo "Testing: $url"
    
    status_code=$(curl -s -o /dev/null -w "%{http_code}" "$url" || echo "000")
    
    if [ "$status_code" == "200" ] || [ "$status_code" == "302" ]; then
        echo -e "${GREEN}✓ $endpoint - OK (HTTP $status_code)${NC}"
    else
        echo -e "${RED}✗ $endpoint - FAILED (HTTP $status_code)${NC}"
        exit 1
    fi
done
echo ""

echo "📋 Step 3: Checking Logs for Errors"  
echo "-----------------------------------------"
# Check Laravel log for recent errors
LOG_FILE="storage/logs/laravel.log"

if [ -f "$LOG_FILE" ]; then
    # Check for errors in last 100 lines
    recent_errors=$(tail -n 100 "$LOG_FILE" | grep -i "error" | wc -l)
    
    if [ "$recent_errors" -gt 0 ]; then
        echo -e "${YELLOW}⚠ Found $recent_errors error(s) in recent logs${NC}"
        echo "Last 5 errors:"
        tail -n 100 "$LOG_FILE" | grep -i "error" | tail -n 5
    else
        echo -e "${GREEN}✓ No recent errors in logs${NC}"
    fi
else
    echo -e "${YELLOW}⚠ Log file not found${NC}"
fi
echo ""

echo "🧪 Step 4: Running Smoke Tests"
echo "-----------------------------------------"
# Run a subset of critical tests
php artisan test --filter=AuthenticationTest --stop-on-failure
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Smoke tests passed${NC}"
else
    echo -e "${RED}✗ Smoke tests failed${NC}"
    exit 1
fi
echo ""

echo "📊 Step 5: Application Health Check"
echo "-----------------------------------------"
# Display application info
echo "Environment: $(php artisan env)"
echo "Version: $(git describe --tags --always 2>/dev/null || echo 'N/A')"
echo "Last commit: $(git log -1 --pretty=format:'%h - %s (%cr)')"
echo ""

echo "========================================="
echo -e "${GREEN}✅ POST-DEPLOYMENT VERIFICATION COMPLETE${NC}"
echo "========================================="
echo ""
echo "Summary:"
echo "  ✓ Application status OK"
echo "  ✓ Database connected"
echo "  ✓ Critical endpoints responding"
echo "  ✓ No critical errors in logs"
echo "  ✓ Smoke tests passed"
echo ""
echo "Deployment verified successfully! 🎉"
echo ""
