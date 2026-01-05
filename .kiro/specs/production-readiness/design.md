# Production Readiness Testing - Design Document

## Overview

This document outlines the comprehensive testing and validation strategy for ensuring the K3 PLN Inventaris system is production-ready. The design covers automated testing, manual testing procedures, configuration validation, security checks, performance verification, and deployment procedures.

The approach follows a multi-layered testing strategy:
1. **Automated Testing Layer** - PHPUnit tests for critical functionality
2. **Configuration Validation Layer** - Scripts to verify production settings
3. **Manual Testing Layer** - Structured test cases for end-to-end flows
4. **Performance Testing Layer** - Load testing and optimization verification
5. **Security Validation Layer** - Security checklist and vulnerability scanning
6. **Deployment Verification Layer** - Pre and post-deployment checks

## Architecture

### Testing Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Production Readiness                      │
│                      Testing System                          │
└─────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│  Automated   │    │    Manual    │    │Configuration │
│   Testing    │    │   Testing    │    │  Validation  │
└──────────────┘    └──────────────┘    └──────────────┘
        │                     │                     │
        ├─ Feature Tests      ├─ Auth Flows        ├─ Environment
        ├─ Unit Tests         ├─ CRUD Operations   ├─ Permissions
        ├─ Integration Tests  ├─ Approval Flow     ├─ Cache Status
        └─ Browser Tests      ├─ Guest Access      └─ Dependencies
                              ├─ PDF Export
                              └─ Floor Plan
                              
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│ Performance  │    │   Security   │    │  Deployment  │
│   Testing    │    │  Validation  │    │ Verification │
└──────────────┘    └──────────────┘    └──────────────┘
        │                     │                     │
        ├─ Load Testing       ├─ CSRF Protection   ├─ Pre-Deploy
        ├─ Query Analysis     ├─ XSS Prevention    ├─ Deploy Steps
        ├─ Cache Efficiency   ├─ SQL Injection     ├─ Post-Deploy
        └─ Response Times     ├─ Auth/Authz        └─ Rollback Plan
                              └─ HTTPS/SSL
```

### Test Data Management

```
┌─────────────────────────────────────────────────────────────┐
│                      Test Database                           │
│                    (SQLite in-memory)                        │
└─────────────────────────────────────────────────────────────┘
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
        ▼                     ▼                     ▼
┌──────────────┐    ┌──────────────┐    ┌──────────────┐
│   Factories  │    │   Seeders    │    │  Test Data   │
│              │    │              │    │   Fixtures   │
└──────────────┘    └──────────────┘    └──────────────┘
        │                     │                     │
        ├─ User Factory       ├─ Role Seeder       ├─ Sample APAR
        ├─ APAR Factory       ├─ Permission Seeder ├─ Sample Kartu
        ├─ Kartu Factory      └─ Unit Seeder       └─ Sample Users
        └─ Unit Factory
```

## Components and Interfaces

### 1. Automated Test Suite

#### 1.1 Feature Tests

**Purpose**: Test complete user flows and HTTP interactions

**Test Categories**:

1. **Authentication Tests** (Existing - Enhanced)
   - Login with valid credentials
   - Login with invalid credentials
   - Logout functionality
   - Session management
   - Remember me functionality
   - Password reset flow

2. **Equipment CRUD Tests** (New)
   - Create APAR/APAB/APAT/P3K/Box Hydrant/Fire Alarm/Rumah Pompa
   - Read equipment list with pagination
   - Update equipment details
   - Delete equipment (soft delete)
   - QR code generation on create
   - Validation rules enforcement

3. **Kartu Kendali Tests** (New)
   - Create kartu for each equipment type
   - View kartu history
   - Update kartu details
   - Validation for required fields
   - Date validation
   - Equipment association

4. **Approval Workflow Tests** (New)
   - Submit kartu for approval
   - View pending approvals (leader role)
   - Approve kartu
   - Reject kartu
   - Approval history tracking
   - Permission enforcement

5. **Guest Access Tests** (New)
   - Access equipment via QR code without login
   - View equipment details
   - View kartu history
   - Prevent access to protected routes
   - QR code validation

6. **Export Tests** (Existing - Enhanced)
   - Export equipment to Excel
   - Export kartu to Excel with approval history
   - Export kartu to PDF
   - PDF formatting validation
   - Excel data integrity

7. **Floor Plan Tests** (New)
   - Load floor plan page
   - Display equipment markers
   - Interactive zoom/pan functionality
   - Equipment detail popup

#### 1.2 Unit Tests

**Purpose**: Test individual components and business logic

**Test Categories**:

1. **Model Tests** (New)
   - Relationship definitions
   - Accessor/Mutator methods
   - Scope methods
   - Validation rules
   - Business logic methods

2. **Helper Function Tests** (Existing - Enhanced)
   - User helper functions
   - Date formatting helpers
   - Status helpers
   - Permission helpers

3. **Service Class Tests** (New)
   - QR code generation service
   - PDF generation service
   - Excel export service
   - Approval service

4. **Middleware Tests** (New)
   - PreventGuestAccess middleware
   - Role-based access middleware
   - CSRF protection

#### 1.3 Browser Tests (Optional - Dusk)

**Purpose**: Test JavaScript interactions and UI behavior

**Test Categories**:
- Modal interactions
- Form submissions with AJAX
- Real-time updates
- Floor plan interactions
- Mobile responsive behavior

### 2. Configuration Validation Script

**Purpose**: Automated script to verify production configuration

**File**: `tests/ProductionConfigValidator.php`

**Validations**:

```php
class ProductionConfigValidator
{
    public function validateEnvironment(): array
    {
        return [
            'app_debug' => $this->checkAppDebug(),
            'app_env' => $this->checkAppEnv(),
            'app_key' => $this->checkAppKey(),
            'database' => $this->checkDatabaseConfig(),
            'storage_permissions' => $this->checkStoragePermissions(),
            'cache_permissions' => $this->checkCachePermissions(),
            'storage_link' => $this->checkStorageLink(),
            'caches' => $this->checkCaches(),
            'dependencies' => $this->checkDependencies(),
            'php_version' => $this->checkPhpVersion(),
            'php_extensions' => $this->checkPhpExtensions(),
        ];
    }
    
    private function checkAppDebug(): bool
    {
        return config('app.debug') === false;
    }
    
    private function checkAppEnv(): bool
    {
        return config('app.env') === 'production';
    }
    
    // ... other validation methods
}
```

**Usage**:
```bash
php artisan app:validate-production-config
```

### 3. Manual Testing Checklist

**Purpose**: Structured manual testing for critical user flows

**Test Document**: `tests/manual/MANUAL_TEST_CHECKLIST.md`

**Structure**:

```markdown
# Manual Testing Checklist

## Authentication Flow
- [ ] Login as admin user
- [ ] Login as regular user
- [ ] Login with wrong password (should fail)
- [ ] Logout
- [ ] Session timeout after inactivity

## Equipment Management (APAR)
- [ ] Create new APAR
- [ ] View APAR list
- [ ] Edit APAR details
- [ ] Delete APAR
- [ ] Verify QR code generated
- [ ] Scan QR code as guest

## Kartu Kendali Flow
- [ ] Create kartu for APAR
- [ ] Fill all required fields
- [ ] Submit for approval
- [ ] View in pending approvals (as leader)
- [ ] Approve kartu
- [ ] View approved kartu in history
- [ ] Export kartu to PDF
- [ ] Verify PDF formatting

## Guest Access
- [ ] Scan QR code without login
- [ ] View equipment details
- [ ] View kartu history
- [ ] Try to access admin routes (should fail)

## Cross-Browser Testing
- [ ] Test on Chrome
- [ ] Test on Firefox
- [ ] Test on Safari
- [ ] Test on Edge
- [ ] Test on mobile Chrome
- [ ] Test on mobile Safari

## Performance Testing
- [ ] Page load time < 2 seconds
- [ ] Form submission responsive
- [ ] Large list pagination works
- [ ] PDF generation < 5 seconds
```

### 4. Performance Testing

**Purpose**: Verify application performance under load

**Tools**:
- Laravel Debugbar (development)
- Laravel Telescope (development)
- Apache Bench (ab) for load testing
- Chrome DevTools for frontend performance

**Test Scenarios**:

1. **Page Load Performance**
   ```bash
   # Test dashboard load time
   ab -n 100 -c 10 http://localhost/dashboard
   ```

2. **Database Query Analysis**
   - Enable query logging
   - Check for N+1 queries
   - Verify eager loading
   - Check query execution times

3. **Cache Effectiveness**
   - Verify config cache loaded
   - Verify route cache loaded
   - Verify view cache loaded
   - Check cache hit rates

4. **Asset Loading**
   - Verify gzip compression
   - Check browser caching headers
   - Verify asset minification
   - Check image optimization

### 5. Security Validation

**Purpose**: Ensure security best practices are implemented

**Security Checklist**:

1. **Configuration Security**
   - [ ] APP_DEBUG=false
   - [ ] APP_ENV=production
   - [ ] Strong APP_KEY
   - [ ] .env not in git
   - [ ] Secure session settings

2. **Authentication & Authorization**
   - [ ] Password hashing (bcrypt)
   - [ ] Session security (httpOnly, sameSite)
   - [ ] CSRF protection enabled
   - [ ] Role-based access control
   - [ ] Guest access restrictions

3. **Input Validation**
   - [ ] All forms validated
   - [ ] XSS prevention (escaped output)
   - [ ] SQL injection prevention (parameterized queries)
   - [ ] File upload validation
   - [ ] Mass assignment protection

4. **HTTPS/SSL** (Production Server)
   - [ ] SSL certificate installed
   - [ ] Force HTTPS redirect
   - [ ] Secure cookies
   - [ ] HSTS header

5. **Error Handling**
   - [ ] Custom error pages (404, 500)
   - [ ] No stack traces in production
   - [ ] Error logging configured
   - [ ] Sensitive data not logged

### 6. Deployment Procedures

**Purpose**: Standardized deployment process

#### 6.1 Pre-Deployment Checklist

```bash
# 1. Run all tests
php artisan test

# 2. Validate production config
php artisan app:validate-production-config

# 3. Check code quality
./vendor/bin/pint --test

# 4. Backup database
php artisan backup:run

# 5. Review changes
git diff production main

# 6. Build assets
npm run build
```

#### 6.2 Deployment Steps

```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies (production mode)
composer install --optimize-autoloader --no-dev

# 3. Install NPM dependencies and build
npm ci
npm run build

# 4. Run migrations
php artisan migrate --force

# 5. Clear and rebuild caches
php artisan optimize:clear
php artisan optimize

# 6. Restart services
php artisan queue:restart  # if using queues
sudo systemctl restart php-fpm  # or php service
```

#### 6.3 Post-Deployment Verification

```bash
# 1. Check application status
curl -I https://your-domain.com

# 2. Test critical endpoints
curl https://your-domain.com/login
curl https://your-domain.com/dashboard  # should redirect to login

# 3. Check logs for errors
tail -f storage/logs/laravel.log

# 4. Run smoke tests
php artisan app:smoke-test

# 5. Monitor server resources
htop
df -h
```

#### 6.4 Rollback Procedure

```bash
# 1. Revert to previous commit
git reset --hard HEAD~1

# 2. Reinstall dependencies
composer install --optimize-autoloader --no-dev

# 3. Rollback migrations (if needed)
php artisan migrate:rollback

# 4. Rebuild caches
php artisan optimize

# 5. Restore database backup (if needed)
mysql -u user -p database < backup.sql
```

## Data Models

### Test Data Structure

```php
// User Factory
User::factory()->create([
    'name' => 'Test Admin',
    'email' => 'admin@test.com',
    'password' => Hash::make('password'),
    'position' => 'Administrator',
    'unit_id' => 1,
]);

// APAR Factory
Apar::factory()->create([
    'name' => 'APAR-001',
    'serial_no' => 'SN-001',
    'barcode' => 'BC-001',
    'type' => 'Powder',
    'capacity' => '3 Kg',
    'location_code' => 'A-101',
    'status' => 'baik',
    'user_id' => 1,
    'unit_id' => 1,
]);

// Kartu APAR Factory
KartuApar::factory()->create([
    'apar_id' => 1,
    'user_id' => 1,
    'tgl_periksa' => now(),
    'pressure_gauge' => 'baik',
    'pin_segel' => 'baik',
    'selang' => 'baik',
    'tabung' => 'baik',
    'label' => 'baik',
    'kondisi_fisik' => 'baik',
    'kesimpulan' => 'Layak',
    'petugas' => 'Test Petugas',
]);
```

## Error Handling

### Test Error Scenarios

1. **Validation Errors**
   - Missing required fields
   - Invalid data types
   - Out of range values
   - Duplicate entries

2. **Authentication Errors**
   - Invalid credentials
   - Expired sessions
   - Unauthorized access
   - Missing permissions

3. **Database Errors**
   - Connection failures
   - Constraint violations
   - Deadlocks
   - Query timeouts

4. **File System Errors**
   - Permission denied
   - Disk full
   - Missing directories
   - File not found

5. **External Service Errors**
   - PDF generation failures
   - QR code generation failures
   - Email sending failures

### Error Handling Strategy

```php
// In tests, verify proper error handling
public function test_handles_database_connection_error()
{
    // Simulate database connection failure
    Config::set('database.connections.mysql.host', 'invalid-host');
    
    $response = $this->get('/dashboard');
    
    // Should show error page, not crash
    $response->assertStatus(500);
    $response->assertSee('Database connection error');
}
```

## Testing Strategy

### Test Execution Order

1. **Unit Tests** (Fast, isolated)
   - Run first to catch basic logic errors
   - Should complete in < 10 seconds

2. **Feature Tests** (Medium speed, database)
   - Run after unit tests
   - Should complete in < 60 seconds

3. **Browser Tests** (Slow, full stack)
   - Run last, optional for CI
   - Should complete in < 5 minutes

4. **Manual Tests** (Human verification)
   - Run before deployment
   - Critical path testing

5. **Performance Tests** (Load testing)
   - Run in staging environment
   - Verify under expected load

### Continuous Integration

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          
      - name: Install Dependencies
        run: composer install
        
      - name: Run Tests
        run: php artisan test
        
      - name: Check Code Style
        run: ./vendor/bin/pint --test
```

### Test Coverage Goals

- **Critical Features**: 90%+ coverage
  - Authentication
  - Equipment CRUD
  - Kartu Kendali
  - Approval workflow
  - Guest access

- **Supporting Features**: 70%+ coverage
  - Export functionality
  - Floor plan
  - Reporting

- **Overall**: 80%+ coverage

## Performance Targets

### Response Time Targets

- **Page Load**: < 2 seconds (95th percentile)
- **API Endpoints**: < 200ms (95th percentile)
- **Database Queries**: < 100ms average
- **PDF Generation**: < 5 seconds
- **Excel Export**: < 10 seconds (1000 records)

### Resource Limits

- **Memory**: < 128MB per request
- **CPU**: < 50% average utilization
- **Database Connections**: < 20 concurrent
- **Disk I/O**: < 10MB/s average

### Optimization Checklist

- [ ] Laravel caches enabled (config, route, view)
- [ ] PHP opcache enabled
- [ ] Database indexes on foreign keys
- [ ] Eager loading for relationships
- [ ] Query result caching where appropriate
- [ ] Asset minification and compression
- [ ] Image optimization
- [ ] CDN for static assets (optional)

## Monitoring and Logging

### Production Monitoring

1. **Application Monitoring**
   - Error rate tracking
   - Response time monitoring
   - User activity tracking
   - Failed job monitoring

2. **Server Monitoring**
   - CPU usage
   - Memory usage
   - Disk space
   - Network traffic

3. **Database Monitoring**
   - Query performance
   - Connection pool usage
   - Slow query log
   - Database size

4. **Log Management**
   - Centralized logging
   - Log rotation
   - Error alerting
   - Audit trail

### Logging Configuration

```php
// config/logging.php
'channels' => [
    'production' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'error',
        'days' => 14,
    ],
],
```

## Documentation

### Required Documentation

1. **Deployment Guide**
   - Server requirements
   - Installation steps
   - Configuration guide
   - Troubleshooting

2. **User Manual**
   - Feature overview
   - Step-by-step guides
   - Screenshots
   - FAQ

3. **API Documentation** (if applicable)
   - Endpoint list
   - Request/response examples
   - Authentication
   - Error codes

4. **Maintenance Guide**
   - Backup procedures
   - Update procedures
   - Common issues
   - Emergency contacts

## Success Criteria

The application is considered production-ready when:

1. ✅ All automated tests pass (100%)
2. ✅ Production configuration validated
3. ✅ Manual test checklist completed
4. ✅ Security checklist verified
5. ✅ Performance targets met
6. ✅ Cross-browser testing completed
7. ✅ Documentation completed
8. ✅ Deployment procedures tested
9. ✅ Rollback procedures tested
10. ✅ Monitoring and alerting configured
