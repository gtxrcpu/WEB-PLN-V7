# ✅ Production Checklist - K3 PLN

## 🔒 Security

- [ ] Set `APP_DEBUG=false` di `.env`
- [ ] Set `APP_ENV=production` di `.env`
- [ ] Generate new `APP_KEY` untuk production
- [ ] Pastikan `.env` tidak ter-commit ke git
- [ ] Set proper file permissions (755 folders, 644 files)
- [ ] Disable directory listing
- [ ] Enable HTTPS/SSL
- [ ] Set secure session cookies

## ⚡ Performance

- [ ] Jalankan `php artisan optimize`
- [ ] Jalankan `php artisan config:cache`
- [ ] Jalankan `php artisan route:cache`
- [ ] Jalankan `php artisan view:cache`
- [ ] Enable opcache di PHP
- [ ] Set proper cache driver (redis/memcached untuk production besar)
- [ ] Optimize images (compress, webp format)
- [ ] Enable Gzip compression (sudah ada di .htaccess)
- [ ] Set browser caching headers (sudah ada di .htaccess)

## 🗄️ Database

- [ ] Backup database
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed data jika perlu: `php artisan db:seed`
- [ ] Optimize tables
- [ ] Set proper indexes
- [ ] Enable query caching

## 📁 Files & Folders

- [ ] Set storage permissions: `chmod -R 775 storage`
- [ ] Set bootstrap/cache permissions: `chmod -R 775 bootstrap/cache`
- [ ] Create symbolic link: `php artisan storage:link`
- [ ] Pastikan folder `storage/app/public` ada
- [ ] Pastikan folder `storage/logs` writable

## 🧪 Testing

- [ ] Test login admin
- [ ] Test login user
- [ ] Test create APAR
- [ ] Test create Kartu Kendali
- [ ] Test approval flow
- [ ] Test QR code generation
- [ ] Test export PDF
- [ ] Test di berbagai browser (Chrome, Firefox, Safari, Edge)
- [ ] Test di mobile devices
- [ ] Test slow connection (3G simulation)

## 📊 Monitoring

- [ ] Setup error logging
- [ ] Setup application monitoring
- [ ] Setup uptime monitoring
- [ ] Setup backup automation
- [ ] Setup log rotation
- [ ] Monitor disk space
- [ ] Monitor database size

## 🚀 Deployment

- [ ] Pull latest code: `git pull origin main`
- [ ] Install dependencies: `composer install --optimize-autoloader --no-dev`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear & cache: `php artisan optimize`
- [ ] Restart queue workers (jika ada)
- [ ] Test critical features
- [ ] Notify users (jika ada downtime)

## 🔄 Post-Deployment

- [ ] Check error logs: `tail -f storage/logs/laravel.log`
- [ ] Monitor server resources (CPU, RAM, Disk)
- [ ] Test all critical features
- [ ] Check response times
- [ ] Verify cron jobs running (jika ada)
- [ ] Backup database setelah deployment sukses

## 📝 Environment Variables (.env)

```env
# Application
APP_NAME="Inventaris K3 PLN"
APP_ENV=production
APP_KEY=base64:YOUR_PRODUCTION_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=k3_pln_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue (optional)
QUEUE_CONNECTION=sync

# Mail (jika ada)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🎯 Performance Targets

- Page Load Time: < 2 seconds
- Time to Interactive: < 3 seconds
- First Contentful Paint: < 1 second
- Server Response Time: < 200ms
- Database Query Time: < 100ms average

## 🐛 Common Issues & Solutions

### Issue: Modal profil muncul saat load
**Solution:** Hard refresh browser (Ctrl + Shift + R)

### Issue: Button perlu diklik 2x
**Solution:** Clear browser cache dan reload

### Issue: Page load lambat
**Solution:** 
1. Jalankan `optimize.bat`
2. Check database queries
3. Enable opcache

### Issue: Images tidak muncul
**Solution:** 
1. Jalankan `php artisan storage:link`
2. Check file permissions

### Issue: 500 Error
**Solution:**
1. Check `storage/logs/laravel.log`
2. Set proper permissions
3. Clear cache

## 📞 Emergency Contacts

- Developer: [Your Name]
- Server Admin: [Admin Name]
- Database Admin: [DBA Name]

## 🎉 Launch Day Checklist

- [ ] Backup everything
- [ ] Deploy to production
- [ ] Run all tests
- [ ] Monitor for 1 hour
- [ ] Announce to users
- [ ] Celebrate! 🎊
