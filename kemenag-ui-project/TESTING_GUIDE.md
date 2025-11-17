# 🧪 TESTING GUIDE - Step by Step

**Status**: Ready for testing  
**Last Updated**: 17 November 2025

---

## 📋 PRE-TESTING CHECKLIST:

Before testing, ensure:
- [ ] PHP 7.4+ installed
- [ ] MySQL 5.7+ installed
- [ ] Apache/Nginx web server running
- [ ] Composer installed
- [ ] mod_rewrite enabled (Apache)

---

## 🚀 INSTALLATION STEPS:

### **Step 1: Install Dependencies**
```bash
cd /path/to/kemenag-ui-project
composer install
```

**Expected Output:**
```
Loading composer repositories with package information
Installing dependencies from lock file
Package operations: X installs, 0 updates, 0 removals
  - Installing phpmailer/phpmailer
  - Installing phpoffice/phpspreadsheet
  ...
Generating autoload files
```

### **Step 2: Configure Environment**
```bash
cp .env.example .env
nano .env
```

**Minimum Configuration:**
```env
APP_URL=http://localhost/kemenag-ui-project/public
DB_HOST=localhost
DB_DATABASE=kemenag_ui_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### **Step 3: Create Database**
```sql
mysql -u root -p

CREATE DATABASE kemenag_ui_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kemenag_ui_db;
SOURCE db/schema.sql;
SOURCE db/migration_forum_moderation.sql;
SHOW TABLES;
```

**Expected Output:** 25 tables created

### **Step 4: Set Permissions**
```bash
chmod 777 public/uploads
chmod 777 storage
chmod 644 .env
```

### **Step 5: Configure Web Server**

**For Apache (public/.htaccess already exists):**
```apache
<VirtualHost *:80>
    DocumentRoot "/path/to/kemenag-ui-project/public"
    ServerName kemenag.local
    
    <Directory "/path/to/kemenag-ui-project/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**For Nginx:**
```nginx
server {
    listen 80;
    server_name kemenag.local;
    root /path/to/kemenag-ui-project/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 🧪 RUNNING TESTS:

### **Test 1: Application Structure**
```bash
php test_application.php
```

**Expected Output:**
```
✅ Passed:   45+ tests
❌ Failed:   0 tests
⚠️  Warnings: 2 warnings (DB connection expected)
🎉 ALL TESTS PASSED! Application structure is correct!
```

### **Test 2: Access Homepage**
```bash
curl http://localhost/kemenag-ui-project/public
```

**Expected:** HTML response with homepage content

### **Test 3: Test Routing**
```bash
# Test various routes
curl http://localhost/kemenag-ui-project/public/
curl http://localhost/kemenag-ui-project/public/auth/login
curl http://localhost/kemenag-ui-project/public/qa
curl http://localhost/kemenag-ui-project/public/fatwa
```

**Expected:** Each returns proper HTML

### **Test 4: Test Database Connection**
Create `test_db.php`:
```php
<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';

$dbConfig = require 'config/database.php';
$dbSettings = $dbConfig['connections'][$dbConfig['default']];

try {
    $database = new \Core\Database($dbSettings);
    $db = $database->connect();
    echo "✅ Database connected successfully!\n";
    
    // Test query
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    echo "✅ Found {$result['count']} users\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
```

Run:
```bash
php test_db.php
```

### **Test 5: Test Model**
Create `test_model.php`:
```php
<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';

$dbConfig = require 'config/database.php';
$dbSettings = $dbConfig['connections'][$dbConfig['default']];
$database = new \Core\Database($dbSettings);
$db = $database->connect();

// Test User Model
$userModel = new \App\Models\User($db);
$users = $userModel->findAll();
echo "✅ User model works! Found " . count($users) . " users\n";

// Test Role Model
$roleModel = new \App\Models\Role($db);
$roles = $roleModel->findAll();
echo "✅ Role model works! Found " . count($roles) . " roles\n";
```

---

## 🔍 FUNCTIONAL TESTING:

### **Test Frontend:**

1. **Homepage** - `http://localhost/public`
   - [ ] Homepage loads
   - [ ] Navigation menu works
   - [ ] Footer displays
   - [ ] No JavaScript errors

2. **Authentication** - `http://localhost/public/auth/login`
   - [ ] Login form displays
   - [ ] Can submit login
   - [ ] Error messages show
   - [ ] Redirect after login

3. **Q&A** - `http://localhost/public/qa`
   - [ ] Q&A list displays
   - [ ] Pagination works
   - [ ] Search functions
   - [ ] Detail page loads

4. **Fatwa** - `http://localhost/public/fatwa`
   - [ ] Fatwa list displays
   - [ ] Categories work
   - [ ] Detail page loads

5. **Certificate** - `http://localhost/public/certificate/apply`
   - [ ] Application form displays
   - [ ] File upload works
   - [ ] Form validation
   - [ ] Submission successful

6. **Forum** - `http://localhost/public/forum`
   - [ ] Forum list displays
   - [ ] Topics load
   - [ ] Comments visible
   - [ ] Login required for posting

### **Test Admin Panel:**

1. **Login** - `http://localhost/public/admin`
   - [ ] Admin login form
   - [ ] Authentication works
   - [ ] Dashboard loads
   - [ ] Sidebar menu displays

2. **User Management** - `/admin/users`
   - [ ] User list displays
   - [ ] Create user works
   - [ ] Edit user works
   - [ ] Delete user works
   - [ ] Search/filter works

3. **Content Management:**
   - [ ] Q&A CRUD works
   - [ ] Fatwa CRUD works
   - [ ] Material CRUD works
   - [ ] Book CRUD works

4. **Certificate Management:**
   - [ ] Application list displays
   - [ ] Can view details
   - [ ] Can approve/reject
   - [ ] PDF generation works
   - [ ] Email notifications sent

5. **Forum Moderation:**
   - [ ] Pending topics visible
   - [ ] Can approve topics
   - [ ] Can reject topics
   - [ ] Blacklist words work

6. **Settings:**
   - [ ] General settings save
   - [ ] Email settings save
   - [ ] SEO settings save
   - [ ] Cache clear works

---

## 🔐 SECURITY TESTING:

### **Test 1: SQL Injection Prevention**
Try:
```
Login: admin' OR '1'='1
Password: ' OR '1'='1
```
**Expected:** Login fails, no SQL error

### **Test 2: XSS Prevention**
Try posting:
```html
<script>alert('XSS')</script>
```
**Expected:** Script is escaped, not executed

### **Test 3: CSRF Protection**
Try submitting form without CSRF token.
**Expected:** Request rejected

### **Test 4: Authentication**
Try accessing `/admin` without login.
**Expected:** Redirected to login

### **Test 5: Authorization**
Login as regular user, try accessing `/admin/users`.
**Expected:** Access denied (403)

---

## 📊 PERFORMANCE TESTING:

### **Test 1: Page Load Time**
```bash
curl -w "@curl-format.txt" -o /dev/null -s http://localhost/public
```

**Expected:** < 500ms

### **Test 2: Database Query Count**
Enable query logging, load a page.
**Expected:** < 20 queries per page

### **Test 3: Memory Usage**
Check PHP memory usage in logs.
**Expected:** < 128MB per request

---

## 🐛 DEBUGGING CHECKLIST:

### **If Application Doesn't Load:**

1. **Check Apache Error Log:**
```bash
tail -f /var/log/apache2/error.log
```

2. **Check PHP Errors:**
Enable in `.env`:
```env
APP_DEBUG=true
```

3. **Check File Permissions:**
```bash
ls -la public/
ls -la storage/
```

4. **Check .htaccess:**
```bash
cat public/.htaccess
```

5. **Test Direct PHP:**
```bash
php -S localhost:8000 -t public/
```

### **If Database Errors:**

1. **Verify Connection:**
```bash
mysql -u root -p -e "SHOW DATABASES;"
```

2. **Check Tables:**
```bash
mysql -u root -p kemenag_ui_db -e "SHOW TABLES;"
```

3. **Check User Permissions:**
```sql
SHOW GRANTS FOR 'your_user'@'localhost';
```

### **If Routes Don't Work:**

1. **Check mod_rewrite:**
```bash
apache2ctl -M | grep rewrite
```

2. **Test Route Manually:**
```bash
php public/index.php
```

3. **Check routes.php:**
```bash
php -r "var_dump(require 'app/routes.php');"
```

---

## ✅ FINAL VERIFICATION:

Run all these commands in sequence:

```bash
# 1. Structure test
php test_application.php

# 2. Database test
php test_db.php

# 3. Model test
php test_model.php

# 4. Access test
curl http://localhost/public

# 5. Admin access
curl http://localhost/public/admin

# 6. API test (if applicable)
curl http://localhost/public/api/health
```

**All should pass!**

---

## 📞 TROUBLESHOOTING:

### **Common Issues:**

1. **"Class not found" Error**
   - Run: `composer dump-autoload`
   - Check namespace declarations

2. **"Database connection failed"**
   - Verify `.env` settings
   - Check MySQL is running
   - Test connection manually

3. **"404 Not Found"**
   - Check `.htaccess` exists
   - Verify DocumentRoot
   - Test mod_rewrite

4. **"Permission denied"**
   - Run: `chmod 777 public/uploads storage`
   - Check file ownership

5. **"CSRF token mismatch"**
   - Clear browser cookies
   - Check session configuration

---

## 🎯 SUCCESS CRITERIA:

Application is considered "working" when:

- [x] All structure tests pass
- [x] Database connects successfully
- [x] Homepage loads without errors
- [x] Login works (frontend & admin)
- [x] CRUD operations work
- [x] File uploads work
- [x] Email sends (if configured)
- [x] No PHP errors in logs
- [x] No JavaScript errors
- [x] All security measures active

---

**🎉 If all tests pass, application is PRODUCTION READY! 🎉**

**Created**: 17 November 2025  
**Status**: Ready for deployment
