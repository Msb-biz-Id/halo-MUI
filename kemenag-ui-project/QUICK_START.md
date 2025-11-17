# Quick Start Guide - Kemenag UI

Panduan cepat untuk memulai project ini.

## 🚀 Setup Development (5 Menit)

### 1. Setup Database

```bash
# Login MySQL
mysql -u root -p

# Create database
CREATE DATABASE kemenag_ui_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Import schema
mysql -u root -p kemenag_ui_db < db/schema.sql
```

### 2. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit configuration
nano .env
```

Minimal configuration:
```env
DB_HOST=localhost
DB_DATABASE=kemenag_ui_db
DB_USERNAME=root
DB_PASSWORD=your_password

APP_URL=http://localhost/kemenag-ui-project/public
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Set Permissions

```bash
chmod -R 755 storage/
chmod -R 755 public/uploads/
```

### 5. Access Application

Browser: `http://localhost/kemenag-ui-project/public`

**Default Login:**
- Username: `superadmin`
- Password: `Admin123!`

---

## 📝 Next Steps

### Copy Template Assets

```bash
# Copy News5 (Frontend)
cp -r ../news5/assets public/assets

# Copy Morvin (Admin)
cp -r ../Morvin_HTML_v1.2.0/HTML/dist/assets public/admin-assets
```

### Create Your First Controller

```bash
# Create HomeController
cat > app/controllers/HomeController.php << 'EOF'
<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $settingModel = $this->model('Setting');
        
        $data = [
            'site_name' => $settingModel->getValue('site_name', 'Kemenag UI'),
            'site_description' => $settingModel->getValue('site_description', '')
        ];
        
        $this->view('frontend/home', $data);
    }
}
EOF
```

### Create Your First View

```bash
mkdir -p app/views/frontend
cat > app/views/frontend/home.php << 'EOF'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $site_name ?? 'Kemenag UI' ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <h1><?= e($site_name) ?></h1>
    <p><?= e($site_description) ?></p>
</body>
</html>
EOF
```

### Test It

Browser: `http://localhost/kemenag-ui-project/public`

---

## 🛠️ Development Workflow

### 1. Create Model (if needed)

Models sudah ada di `app/models/`. Contoh penggunaan:

```php
$userModel = $this->model('User');
$users = $userModel->findAll();
```

### 2. Create Controller

```php
<?php

namespace App\Controllers;

use Core\Controller;

class MyController extends Controller
{
    public function index()
    {
        // Your code
        $this->view('frontend/my_view', $data);
    }
}
```

### 3. Add Route

Edit `app/routes.php`:

```php
'my-route' => ['controller' => 'MyController', 'action' => 'index'],
```

### 4. Create View

Create file di `app/views/frontend/my_view.php`

### 5. Test

Browser: `http://localhost/kemenag-ui-project/public/my-route`

---

## 🔧 Common Tasks

### Send Email

```php
use App\Services\EmailService;

$emailService = new EmailService();
$emailService->send('user@example.com', 'Subject', 'Body HTML');
```

### Log Activity

```php
log_audit($userId, 'action', 'table_name', $recordId);
```

### Flash Message

```php
// Set message
$this->setFlash('success', 'Operation successful!');

// Get message (in view)
<?php if ($msg = flash('success')): ?>
    <div class="alert alert-success"><?= $msg ?></div>
<?php endif; ?>
```

### Check Permission

```php
// In controller
$this->requirePermission('certificate_management');

// Or check manually
if ($this->hasPermission('certificate_management')) {
    // Allow
}
```

### CSRF Protection

```php
// In form
<form method="POST">
    <?= csrf_field() ?>
    <!-- form fields -->
</form>

// In controller
if (!$this->verifyCsrf()) {
    // Invalid request
}
```

---

## 📚 Helpful Commands

### Generate Encryption Key

```bash
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
```

### Clear Cache

```bash
rm -rf storage/cache/*
```

### View Logs

```bash
tail -f storage/logs/error.log
```

### Database Backup

```bash
mysqldump -u root -p kemenag_ui_db > backup_$(date +%Y%m%d).sql
```

### Database Restore

```bash
mysql -u root -p kemenag_ui_db < backup_20240101.sql
```

---

## 🐛 Troubleshooting

### 404 Error

Check:
1. `.htaccess` exists in `public/`
2. `mod_rewrite` enabled
3. Route defined in `app/routes.php`

### Database Error

Check:
1. Database credentials in `.env`
2. Database exists
3. Schema imported

### Permission Denied

```bash
chmod -R 755 storage/
chmod -R 755 public/uploads/
```

### Composer Error

```bash
composer clear-cache
composer install
```

---

## 📖 Documentation

- **README.md**: Comprehensive guide
- **PROJECT_SUMMARY.md**: What's done and what's needed
- **deployment_instructions.md**: Deploy to production

---

## 🎯 Priority Tasks

1. [ ] Copy template assets
2. [ ] Create layout views
3. [ ] Create CertificateController (important!)
4. [ ] Create certificate views
5. [ ] Test certificate workflow
6. [ ] Create other controllers
7. [ ] Create other views
8. [ ] Configure API keys (Gemini, WhatsApp)
9. [ ] Test all features
10. [ ] Deploy

---

**Need Help?** Check `PROJECT_SUMMARY.md` for detailed guidance.
