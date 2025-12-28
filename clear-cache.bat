@echo off
echo Clearing Laravel Cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo.
echo Cache cleared successfully!
echo Please refresh your browser with Ctrl+Shift+R (hard refresh)
pause
