@echo off
echo ========================================
echo   Optimizing K3 PLN Application
echo ========================================
echo.

echo [1/6] Clearing application cache...
php artisan cache:clear

echo [2/6] Clearing config cache...
php artisan config:clear

echo [3/6] Clearing route cache...
php artisan route:clear

echo [4/6] Clearing view cache...
php artisan view:clear

echo [5/6] Caching config...
php artisan config:cache

echo [6/6] Caching routes...
php artisan route:cache

echo.
echo ========================================
echo   Optimization Complete!
echo ========================================
echo.
pause
