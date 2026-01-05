# 🚀 Deployment Scripts Documentation

## Overview

This directory contains production deployment automation scripts for the K3 PLN Inventory System.

## Scripts

### 1. `pre-deploy.sh` - Pre-Deployment Validation
**Purpose:** Validate application before deployment

**What it does:**
- ✅ Runs all automated tests
- ✅ Validates production configuration
- ✅ Checks code quality (PHP syntax)
- ✅ Creates database backup
- ✅ Builds production assets

**Usage:**
```bash
./scripts/deployment/pre-deploy.sh
```

**Exit codes:**
- `0`: All validations passed, ready to deploy
- `1`: Validation failed, DO NOT deploy

---

### 2. `deploy.sh` - Deployment Script
**Purpose:** Deploy application to production

**What it does:**
- 📥 Pulls latest code from Git
- 📦 Installs production dependencies
- 🗄️ Runs database migrations
- 🧹 Clears and rebuilds caches
- 🔄 Restarts services (PHP-FPM, nginx/apache, queue workers)
- 🔒 Sets proper file permissions

**Usage:**
```bash
# Deploy from main branch (default)
./scripts/deployment/deploy.sh

# Deploy from specific branch
GIT_BRANCH=release ./scripts/deployment/deploy.sh
```

**Important:**
- Run `pre-deploy.sh` FIRST
- Requires sudo access for service restarts
- Backup is created automatically by pre-deploy script

---

### 3. `post-deploy.sh` - Post-Deployment Verification
**Purpose:** Verify deployment success

**What it does:**
- 🏥 Checks application status
- 🌐 Tests critical endpoints
- 📋 Checks logs for errors
- 🧪 Runs smoke tests
- 📊 Displays application health info

**Usage:**
```bash
./scripts/deployment/post-deploy.sh
```

**Exit codes:**
- `0`: Deployment verified successfully
- `1`: Verification failed, consider rollback

---

### 4. `rollback.sh` - Rollback Script
**Purpose:** Rollback to previous version

**What it does:**
- 🔙 Reverts code to previous commit/tag
- 📦 Reinstalls dependencies
- 🗄️ Rolls back database migrations
- 💾 Optionally restores database backup
- 🧹 Rebuilds caches
- 🔄 Restarts services

**Usage:**
```bash
# Rollback to previous commit
./scripts/deployment/rollback.sh

# Rollback to specific commit/tag
./scripts/deployment/rollback.sh abc123f
./scripts/deployment/rollback.sh v1.2.3
```

**Interactive prompts:**
- Confirmation before rollback
- Optional database restore from backup

---

## Complete Deployment Workflow

### Fresh Deployment

```bash
# 1. Pre-deployment validation
./scripts/deployment/pre-deploy.sh

# 2. If validation passes, deploy
./scripts/deployment/deploy.sh

# 3. Verify deployment
./scripts/deployment/post-deploy.sh
```

### Rollback Procedure

```bash
# 1. Execute rollback
./scripts/deployment/rollback.sh

# 2. Verify rollback
./scripts/deployment/post-deploy.sh

# 3. Fix issues before redeploying
```

---

## Configuration

### Environment Variables

- `APP_ENV`: Application environment (production/staging)
- `APP_URL`: Application URL for endpoint testing
- `GIT_BRANCH`: Git branch to deploy (default: main)
- `BACKUP_DIR`: Directory for database backups (default: storage/backups)

### Customization

Edit the scripts to match your production environment:

1. **App Directory** (`deploy.sh`, `rollback.sh`):
   ```bash
   APP_DIR="/var/www/plnweb"  # Update this
   ```

2. **PHP Version** (all scripts):
   ```bash
   php8.2-fpm  # Update to your PHP version
   ```

3. **Web Server** (all scripts):
   ```bash
   nginx  # or apache2
   ```

4. **Critical Endpoints** (`post-deploy.sh`):
   ```bash
   CRITICAL_ENDPOINTS=(
       "/"
       "/login"
       "/user"
       # Add more endpoints
   )
   ```

---

## Permissions

Make scripts executable:

```bash
chmod +x scripts/deployment/*.sh
```

---

## Platform Support

- ✅ Linux (Ubuntu, Debian, CentOS)
- ✅ macOS
- ⚠️ Windows (use Git Bash or WSL)

---

## Safety Features

### Pre-deployment
- Aborts if tests fail
- Validates configuration
- Creates automatic backups

### Deployment
- Exits on any error (`set -e`)
- Optimized for production (`--no-dev`, `--optimize-autoloader`)

### Rollback
- Requires confirmation
- Optional database restore
- Complete service restart

---

## Troubleshooting

### Tests fail in pre-deploy
**Solution:** Fix failing tests before deploying

### Migration fails during deploy
**Solution:** Use rollback script, fix migration, redeploy

### Services don't restart
**Solution:** Verify service names match your system, check sudo permissions

### Database backup fails
**Solution:** Manually backup before deployment:
```bash
mysqldump -u root plnweb > backup_manual.sql
```

---

## Best Practices

1. **Always run pre-deploy** before deploying
2. **Monitor post-deploy** verification output
3. **Keep backups** for at least 7 days
4. **Test rollback** procedure in staging first
5. **Document** any deployment issues
6. **Schedule deployments** during low-traffic hours
7. **Have rollback plan** ready before deployment

---

## Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review deployment output
- Contact development team

---

**Last Updated:** 2026-01-05  
**Version:** 1.0
