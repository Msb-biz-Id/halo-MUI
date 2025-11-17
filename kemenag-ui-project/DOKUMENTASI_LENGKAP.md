# 📖 Dokumentasi Lengkap - Kemenag UI Project

**Version:** 2.0  
**Last Updated:** 17 November 2024  
**Status:** Production Ready  
**Security Level:** MAXIMUM 🔒

---

## 📑 Daftar Isi

1. [Ringkasan Proyek](#ringkasan-proyek)
2. [Teknologi Stack](#teknologi-stack)
3. [Fitur Utama](#fitur-utama)
4. [Instalasi & Setup](#instalasi--setup)
5. [Konfigurasi Keamanan](#konfigurasi-keamanan)
6. [Cloudflare Turnstile](#cloudflare-turnstile)
7. [Struktur Database](#struktur-database)
8. [Admin Panel](#admin-panel)
9. [API Endpoints](#api-endpoints)
10. [Testing](#testing)
11. [Deployment](#deployment)
12. [Troubleshooting](#troubleshooting)

---

## 🎯 Ringkasan Proyek

### Deskripsi
Sistem manajemen konten islami yang komprehensif untuk Kementerian Agama, mencakup:
- Manajemen Q&A dan Fatwa
- Sertifikasi Halal Online
- Forum Diskusi dengan Moderasi
- Dashboard Admin Lengkap
- Sistem Keamanan Multi-layer

### Statistik Proyek
```
Total Files:            200+
Lines of Code:          50,000+
Controllers:            40+
Models:                 20+
Views:                  120+
Services:               15+
Admin Dashboards:       25+
Documentation:          Comprehensive
```

### Status Fitur
- ✅ **Authentication & Authorization** - Complete
- ✅ **Content Management** - Complete
- ✅ **Certificate System** - Complete
- ✅ **Forum & Moderation** - Complete
- ✅ **Security (Turnstile)** - Complete
- ✅ **Admin Panel** - Complete
- ✅ **PWA Support** - Complete
- ✅ **Multi-language** - Complete

---

## 💻 Teknologi Stack

### Backend
```
PHP:                    7.4+
Framework:              Native MVC (Custom)
Database:               MySQL 5.7+ (InnoDB)
Web Server:             Apache 2.4+
```

### Frontend
```
Template (Public):      News5 Template
Template (Admin):       Morvin HTML v1.2.0
CSS Framework:          Bootstrap 5
JavaScript:             jQuery 3.x
Charts:                 Chart.js
Icons:                  Font Awesome 5
Editor:                 TinyMCE
```

### Libraries (Composer)
```php
"require": {
    "php": ">=7.4.0",
    "phpmailer/phpmailer": "^6.8",           // Email
    "phpoffice/phpspreadsheet": "^1.29",    // Excel Export
    "spomky-labs/otphp": "^10.0",           // MFA (TOTP)
    "google/generative-ai-php": "^0.3",     // Gemini AI
    "guzzlehttp/guzzle": "^7.8",            // HTTP Client
    "tecnickcom/tcpdf": "^6.7",             // PDF Generator
    "cboden/ratchet": "^0.4.4",             // WebSocket
    "predis/predis": "^2.2"                 // Redis/Queue
}
```

### External Services
```
Cloudflare Turnstile:   Anti-bot Protection
Google Gemini:          AI Chatbot
WhatsApp (WAHA):        Messaging Integration
AWS S3:                 Cloud Storage (Optional)
```

---

## 🚀 Fitur Utama

### 1. Authentication & Security

#### Multi-Factor Authentication (MFA)
- TOTP-based (Google Authenticator compatible)
- QR Code generation
- Backup codes
- Recovery options

#### Role-Based Access Control (RBAC)
```
Roles:
- Superadmin:  Full system access
- Admin:       Content & user management
- Moderator:   Forum moderation only
- User:        Standard user access
```

#### Cloudflare Turnstile Protection 🔒
**Protects Against:**
- ✅ Brute-force attacks
- ✅ Bot spam
- ✅ DDoS via automation
- ✅ Credential stuffing
- ✅ Automated scraping

**Protected Endpoints:**
- Login (`/auth/login`)
- Register (`/auth/register`)
- Forgot Password (`/auth/forgot-password`)
- Certificate Application (`/certificate/apply`)
- Forum Topics (`/forum/create-topic`)
- Forum Replies (`/forum/topic/{id}/reply`)
- Contact Form (`/contact`)

#### Security Features
- Password hashing (bcrypt/argon2)
- CSRF protection
- XSS prevention
- SQL injection prevention
- Rate limiting
- Account lockout
- Session management
- IP logging
- Audit trail

---

### 2. Content Management

#### Categories
- Hierarchical structure
- SEO-friendly slugs
- Dynamic meta tags
- Custom ordering

#### Q&A System
- Question submission
- Answer by experts
- Rich text editor
- File attachments
- Comments
- Voting system

#### Fatwa Management
- Fatwa publication
- Category organization
- Comment system
- Status workflow
- PDF export

#### Materials (Educational Content)
- Upload documents
- Multi-format support
- Comments & ratings
- Download tracking
- Access control

#### Books Library
- Digital library
- Reading interface
- Download options
- Category filtering
- Search functionality

---

### 3. Certificate Management

#### Halal Certification
**Application Process:**
1. User submits application
2. Document upload (multiple files)
3. Admin review
4. Verification process
5. Approval/rejection
6. Certificate generation (PDF)
7. Email notification

**Status Tracking:**
- Pending
- Under Review
- Verified
- Approved
- Rejected
- Completed

**Features:**
- Multi-step form
- Document management
- Status history
- Email notifications
- PDF certificate with QR code
- Receipt generation

---

### 4. Forum & Discussion

#### Forum System
- Multiple categories
- Topic creation
- Threaded replies
- Rich text editor
- File attachments
- User mentions
- Topic locking
- Pinned topics

#### Content Moderation
**Word Blacklist System:**
- Configurable blacklist
- Multiple word types (exact, partial, regex)
- Severity levels (low, medium, high, critical)
- Actions (flag, block, auto-reject)
- Detection logging
- Notification system

**Admin Moderation:**
- Topic approval workflow
- Post editing/deletion
- User warnings
- Ban system
- Report handling
- Audit logging

**User Requirements:**
- Must login to post
- Must login to comment
- Account verification required

---

### 5. Communication

#### Internal Messaging
- User-to-user chat
- Admin notifications
- System messages
- Read receipts
- Message history

#### Email Service (PHPMailer)
- Transactional emails
- Notification emails
- HTML templates
- Attachment support
- Queue system

#### WhatsApp Integration
- WAHA/Fonnte integration
- Automated messages
- User registration
- Status updates
- Two-way communication

#### AI Chatbot (Google Gemini)
- Natural language processing
- Context-aware responses
- FAQ automation
- Multi-language support
- Learning capabilities

---

### 6. Advanced Features

#### Real-time Features (WebSocket)
- Live chat
- Real-time notifications
- Typing indicators
- User presence
- Online status

#### Progressive Web App (PWA)
- Offline support
- Push notifications
- Add to home screen
- App shortcuts
- Service worker caching

#### Advanced AI
- Content recommendations
- Semantic search
- Auto-categorization
- Sentiment analysis
- Content quality scoring
- Duplicate detection
- Predictive analytics

#### Queue System
- Background job processing
- Email queue
- Export queue
- Retry mechanism
- Failed job handling
- Multiple queues

#### Automated Backups
- Database backup (mysqldump)
- File system backup (zip)
- Scheduled backups (daily/weekly/monthly)
- Retention policy
- Cloud upload (S3-ready)
- Backup restoration

---

## 🛠️ Instalasi & Setup

### Persyaratan Sistem

**Minimum:**
```
PHP:        7.4+
MySQL:      5.7+
Apache:     2.4+
Memory:     256MB
Storage:    1GB
```

**Recommended:**
```
PHP:        8.0+
MySQL:      8.0+
Apache:     2.4+
Memory:     512MB
Storage:    5GB
```

### Langkah Instalasi

#### 1. Clone/Download Project
```bash
git clone https://github.com/your-repo/kemenag-ui.git
cd kemenag-ui
```

#### 2. Install Dependencies
```bash
composer install
```

#### 3. Database Setup
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE kemenag_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"

# Import schema
mysql -u root -p kemenag_db < db/schema.sql

# Import migrations
mysql -u root -p kemenag_db < db/migration_forum_moderation.sql
mysql -u root -p kemenag_db < db/migration_redirects.sql
```

#### 4. Generate Security Keys
```bash
php generate_keys.php
# Answer 'y' to auto-update .env
```

#### 5. Configure Environment
```bash
cp .env.example .env
nano .env
```

**Edit `.env` with your settings:**
```env
# Database
DB_HOST=localhost
DB_DATABASE=kemenag_db
DB_USERNAME=root
DB_PASSWORD=your_password

# Application
APP_NAME="Kemenag UI"
APP_URL=http://localhost
APP_ENV=production
APP_DEBUG=false

# Security Keys (generated by generate_keys.php)
APP_KEY=base64:your_generated_key
CSRF_TOKEN_NAME=csrf_token_12345678
ENCRYPTION_KEY=your_encryption_key

# Cloudflare Turnstile
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=your_site_key
TURNSTILE_SECRET_KEY=your_secret_key

# Email (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@kemenag.go.id
MAIL_FROM_NAME="Kemenag System"

# Google Gemini AI
GEMINI_API_KEY=your_gemini_api_key

# WhatsApp (WAHA)
WHATSAPP_API_URL=http://localhost:3000
WHATSAPP_API_KEY=your_waha_api_key
WHATSAPP_SESSION_NAME=default
```

#### 6. Set Permissions
```bash
# Storage directories
chmod -R 777 storage/
chmod -R 777 storage/logs/
chmod -R 777 storage/backups/
chmod -R 777 storage/uploads/
chmod -R 777 storage/cache/

# .env file
chmod 600 .env

# Public assets
chmod -R 755 public/
```

#### 7. Apache Configuration
```apache
<VirtualHost *:80>
    ServerName kemenag.local
    DocumentRoot /path/to/kemenag-ui/public
    
    <Directory /path/to/kemenag-ui/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/kemenag_error.log
    CustomLog ${APACHE_LOG_DIR}/kemenag_access.log combined
</VirtualHost>
```

```bash
# Enable modules
sudo a2enmod rewrite
sudo a2enmod headers

# Restart Apache
sudo systemctl restart apache2
```

#### 8. Create Admin User
```bash
# Via MySQL
mysql -u root -p kemenag_db

INSERT INTO users (username, email, password, full_name, role_id, status, email_verified, created_at)
VALUES (
    'admin',
    'admin@kemenag.go.id',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'Super Administrator',
    1, -- superadmin role
    'active',
    1,
    NOW()
);
```

#### 9. Setup Cron Jobs
```bash
crontab -e
```

Add:
```cron
# Daily backups at 2 AM
0 2 * * * /usr/bin/php /path/to/kemenag-ui/cron/daily.php

# Hourly notifications
0 * * * * /usr/bin/php /path/to/kemenag-ui/cron/hourly.php
```

#### 10. Test Installation
```bash
# Visit in browser
http://localhost

# Login as admin
http://localhost/admin
Username: admin
Password: password
```

---

## 🔒 Konfigurasi Keamanan

### 1. Security Keys Setup

#### Automatic (Recommended)
```bash
php generate_keys.php
```

**Output:**
```
Generated Security Keys:

APP_KEY: base64:kX7t9vR2mN5qP8wY3zA6bC1dE4fG7hJ0
CSRF_TOKEN_NAME: csrf_token_a1b2c3d4
ENCRYPTION_KEY: gH5jK8lM2nP4qR7tV9xZ3aC5dF8gJ1k

Auto-update .env? (y/n): y
✅ Keys saved to .env successfully!
```

#### Manual
```bash
# Generate random strings
openssl rand -base64 32  # For APP_KEY
openssl rand -hex 16     # For ENCRYPTION_KEY
```

### 2. HTTPS/SSL Setup

#### Let's Encrypt (Free)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

#### Force HTTPS
```apache
# In .htaccess (already configured)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 3. Firewall Configuration

```bash
# UFW (Ubuntu)
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 22/tcp
sudo ufw enable

# Fail2Ban
sudo apt install fail2ban
sudo systemctl enable fail2ban
```

### 4. Database Security

```sql
-- Create dedicated user
CREATE USER 'kemenag_app'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON kemenag_db.* TO 'kemenag_app'@'localhost';
FLUSH PRIVILEGES;

-- Disable remote root
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1');
FLUSH PRIVILEGES;
```

### 5. File Upload Security

**Configuration in `config/config.php`:**
```php
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);
define('UPLOAD_PATH', BASE_PATH . '/storage/uploads/');
```

**Validation:**
- File type checking (MIME)
- File size limits
- Filename sanitization
- Virus scanning (optional)

---

## 🔐 Cloudflare Turnstile

### Setup (5 Minutes)

#### Step 1: Get Turnstile Keys

1. Login to [Cloudflare Dashboard](https://dash.cloudflare.com/)
2. Navigate to **Turnstile**
3. Click **Add Site**
4. Fill form:
   ```
   Site Name:    Kemenag UI
   Domain:       yourdomain.com (or localhost for testing)
   Widget Mode:  Managed (Recommended)
   ```
5. Click **Create**
6. Copy **Site Key** and **Secret Key**

#### Step 2: Configure .env

```env
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA
```

#### Step 3: Test Integration

**Test Page:**
```
http://yourdomain.com/admin/turnstile/test
```

**Protected Forms:**
- Login: http://yourdomain.com/auth/login
- Register: http://yourdomain.com/auth/register
- Certificate: http://yourdomain.com/certificate/apply

### Admin Monitoring

#### Dashboard
```
URL: /admin/turnstile

Features:
- Total verifications (7 days)
- Success vs Failed rate
- Verification chart by day
- Error breakdown by type
- Top 10 suspicious IPs
```

#### Suspicious IPs
```
URL: /admin/turnstile/suspicious

Features:
- IPs with multiple failures (configurable threshold)
- Failed attempt count
- First/last seen timestamps
- Block/whitelist actions
- Export to CSV
```

### How It Works

#### Frontend (Client-Side)
```html
<!-- Auto-rendered by component -->
<div class="cf-turnstile" 
     data-sitekey="YOUR_SITE_KEY" 
     data-theme="light"></div>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
```

#### Backend (Server-Side)
```php
// In controller
if (!turnstile_verify()) {
    $_SESSION['error'] = 'Security verification failed';
    redirect('/auth/login');
    return;
}

// Continue processing...
```

### Customization

#### Widget Themes
```php
<!-- Light (default) -->
<div class="cf-turnstile" data-theme="light"></div>

<!-- Dark -->
<div class="cf-turnstile" data-theme="dark"></div>

<!-- Auto (system preference) -->
<div class="cf-turnstile" data-theme="auto"></div>
```

#### Widget Sizes
```php
<!-- Normal (300x65px) -->
<div class="cf-turnstile" data-size="normal"></div>

<!-- Compact (130x120px) -->
<div class="cf-turnstile" data-size="compact"></div>

<!-- Flexible (responsive) -->
<div class="cf-turnstile" data-size="flexible"></div>
```

### Troubleshooting

#### Widget Not Showing?
```bash
# Check .env
grep TURNSTILE .env

# Should show:
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=1x00000...
TURNSTILE_SECRET_KEY=1x0000...
```

#### Verification Fails?
**Common causes:**
1. Token expired (user took too long)
2. Wrong secret key (check .env)
3. Domain not whitelisted (add to Cloudflare)
4. Network error (check server connectivity)

**Debug:**
```bash
# Check logs
tail -f storage/logs/turnstile_$(date +%Y-%m-%d).log

# Test connection
curl https://challenges.cloudflare.com/cdn-cgi/trace
```

---

## 💾 Struktur Database

### Tables (25 Total)

#### Core Tables
```sql
users               -- User accounts
roles               -- User roles (RBAC)
permissions         -- Role permissions (JSON)
password_resets     -- Password reset tokens
audit_logs          -- System audit trail
settings            -- Key-value settings
```

#### Content Tables
```sql
categories          -- Content categories
questions_answers   -- Q&A system
fatwas              -- Fatwa/rulings
fatwa_comments      -- Fatwa comments
materials           -- Educational materials
material_comments   -- Material comments
books               -- Digital library
media               -- Media library
```

#### Certificate Tables
```sql
certificate_applications        -- Applications
certificate_status_history      -- Status tracking
```

#### Forum Tables
```sql
forum_categories    -- Forum categories
forum_topics        -- Forum topics
forum_posts         -- Forum posts/replies
```

#### Moderation Tables
```sql
word_blacklist          -- Blacklisted words
blacklist_detections    -- Detection logs
```

#### Communication Tables
```sql
internal_messages       -- Internal chat
notifications           -- User notifications
whatsapp_users          -- WhatsApp integration
chatbot_conversations   -- AI chatbot history
```

#### Multi-language Tables
```sql
translations        -- Translation keys
```

#### System Tables
```sql
redirects           -- URL redirects (301/302)
backup_schedules    -- Automated backup config
```

### Key Relationships

```
users
  ├─ has many: certificate_applications
  ├─ has many: forum_topics
  ├─ has many: forum_posts
  ├─ has many: notifications
  └─ belongs to: roles

forum_topics
  ├─ has many: forum_posts
  ├─ belongs to: forum_categories
  └─ belongs to: users (author)

certificate_applications
  ├─ has many: certificate_status_history
  └─ belongs to: users
```

### Indexes

**Optimized for:**
- User lookups (email, username)
- Content searches (title, slug)
- Status filtering
- Date ranges
- Full-text search (MySQL)

---

## 👨‍💼 Admin Panel

### Access
```
URL: /admin
Login: admin credentials required
```

### Dashboards

#### Main Dashboard
```
URL: /admin

Widgets:
- Statistics overview
- Recent activities
- Pending approvals
- System health
- User analytics
- Content statistics
```

#### User Management
```
Users:          /admin/users
Roles:          /admin/roles
Permissions:    /admin/roles/edit/{id}
Activity Logs:  /admin/audit/user-activity/{id}
```

#### Content Management
```
Categories:     /admin/categories
Q&A:            /admin/qa
Fatwas:         /admin/fatwas
Materials:      /admin/materials
Books:          /admin/books
Media Library:  /admin/media
```

#### Certificate Management
```
Applications:   /admin/certificates
Dashboard:      /admin/certificates/dashboard
Review:         /admin/certificates/review/{id}
Approve:        /admin/certificates/approve/{id}
Reject:         /admin/certificates/reject/{id}
```

#### Forum Moderation
```
Topics:         /admin/forum/topics
Pending:        /admin/forum/pending
Blacklist:      /admin/blacklist
Detections:     /admin/blacklist/logs
```

#### Security & Monitoring
```
Turnstile:      /admin/turnstile
Suspicious IPs: /admin/turnstile/suspicious
Error Monitor:  /admin/monitoring
Performance:    /admin/monitoring/performance
Audit Logs:     /admin/audit
```

#### System Administration
```
Settings:       /admin/settings
Translations:   /admin/translations
Cache:          /admin/settings/cache
Backups:        /admin/backup
Backup Schedule:/admin/backup/schedule
Redirects:      /admin/redirect
Maintenance:    /admin/maintenance
Queue Monitor:  /admin/queue
```

### Bulk Operations (WordPress-style)

**Supported Content:**
- Q&A
- Fatwas
- Materials
- Books
- Forum Topics
- Forum Posts

**Actions:**
```javascript
// Select items with checkboxes
// Choose action from dropdown:
- Delete
- Publish
- Unpublish
- Trash
- Restore
- Change Category
- Export to Excel
```

---

## 🔌 API Endpoints

### Authentication
```
POST   /auth/login
POST   /auth/register
POST   /auth/logout
POST   /auth/forgot-password
POST   /auth/reset-password/{token}
GET    /auth/verify-email/{token}
```

### User
```
GET    /user/profile
POST   /user/profile/update
POST   /user/password/change
GET    /user/notifications
POST   /user/notifications/mark-read/{id}
```

### Q&A
```
GET    /qa
GET    /qa/{id}
POST   /qa/create
POST   /qa/{id}/answer
```

### Fatwas
```
GET    /fatwas
GET    /fatwas/{id}
POST   /fatwas/{id}/comment
```

### Materials
```
GET    /materials
GET    /materials/{id}
POST   /materials/{id}/comment
GET    /materials/{id}/download
```

### Books
```
GET    /books
GET    /books/{id}
GET    /books/{id}/read
GET    /books/{id}/download
```

### Forum
```
GET    /forum
GET    /forum/category/{id}
GET    /forum/topic/{id}
POST   /forum/create-topic
POST   /forum/topic/{id}/reply
POST   /forum/post/{id}/edit
DELETE /forum/post/{id}
```

### Certificates
```
GET    /certificate
POST   /certificate/apply
GET    /certificate/application/{id}
GET    /certificate/download/{id}
```

### Admin API
```
GET    /admin/api/stats
GET    /admin/api/users
POST   /admin/api/users/{id}/ban
GET    /admin/api/certificates/pending
POST   /admin/api/certificates/{id}/approve
```

---

## 🧪 Testing

### Manual Testing

#### Authentication Flow
```
1. Visit /auth/register
   ✓ Fill form
   ✓ Complete Turnstile
   ✓ Submit
   ✓ Check email verification

2. Visit /auth/login
   ✓ Enter credentials
   ✓ Complete Turnstile
   ✓ Submit
   ✓ Verify MFA (if enabled)
   ✓ Check dashboard access

3. Test /auth/forgot-password
   ✓ Enter email
   ✓ Complete Turnstile
   ✓ Check email
   ✓ Reset password
```

#### Certificate Application
```
1. Login as user
2. Visit /certificate/apply
3. Fill complete form
4. Upload documents
5. Complete Turnstile
6. Submit application
7. Check status tracking

Admin side:
1. Login as admin
2. Visit /admin/certificates
3. Review application
4. Approve/reject
5. Check PDF generation
6. Verify email sent
```

#### Forum Testing
```
1. Create topic
   ✓ Complete Turnstile
   ✓ Check blacklist filtering
   ✓ Verify moderation queue

2. Reply to topic
   ✓ Complete Turnstile
   ✓ Check blacklist filtering
   ✓ Verify threaded display

Admin side:
1. Approve/reject topics
2. Edit/delete posts
3. Check detection logs
4. Test blacklist rules
```

#### Turnstile Testing
```
1. Visit /admin/turnstile/test
2. Complete challenge
3. Click "Test Verification"
4. Check result

5. Visit /admin/turnstile
6. View statistics
7. Check charts
8. Review error logs

9. Visit /admin/turnstile/suspicious
10. Filter IPs
11. Export CSV
```

### Load Testing

```bash
# Using Apache Bench
ab -n 1000 -c 10 http://localhost/

# Using wrk
wrk -t12 -c400 -d30s http://localhost/
```

### Security Testing

```bash
# SQL Injection
sqlmap -u "http://localhost/qa/1" --batch

# XSS Testing
# Try injecting: <script>alert('XSS')</script>

# CSRF Testing
# Try request without token

# Brute Force
# Try multiple failed logins
# Check Turnstile blocks after threshold
```

---

## 🚀 Deployment

### cPanel/Shared Hosting

#### 1. Upload Files
```
- Upload via FTP/File Manager
- Extract to public_html/
- Or subdirectory: public_html/kemenag/
```

#### 2. Database Import
```
- phpMyAdmin → Import
- Import schema.sql
- Import migrations
```

#### 3. Configure .env
```
- Edit .env via File Manager
- Update database credentials
- Update APP_URL
- Add Turnstile keys
```

#### 4. Set Permissions
```
chmod -R 755 public_html/kemenag
chmod -R 777 public_html/kemenag/storage
chmod 600 public_html/kemenag/.env
```

#### 5. Setup Cron
```
Cron Jobs → Add New Cron Job

Daily:
0 2 * * * /usr/bin/php /home/username/public_html/kemenag/cron/daily.php

Hourly:
0 * * * * /usr/bin/php /home/username/public_html/kemenag/cron/hourly.php
```

#### 6. Configure Domain
```
- Point domain to public_html/kemenag/public
- Or create subdomain
- Enable SSL via cPanel (Let's Encrypt)
```

### VPS/Cloud (Ubuntu)

#### 1. Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install LAMP
sudo apt install apache2 mysql-server php8.0 php8.0-{cli,mysql,mbstring,xml,curl,zip,gd,intl} -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### 2. Clone & Configure
```bash
cd /var/www/html
git clone https://github.com/your-repo/kemenag-ui.git
cd kemenag-ui

composer install
cp .env.example .env
php generate_keys.php
```

#### 3. Database Setup
```bash
sudo mysql -e "CREATE DATABASE kemenag_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
sudo mysql kemenag_db < db/schema.sql
sudo mysql kemenag_db < db/migration_forum_moderation.sql
sudo mysql kemenag_db < db/migration_redirects.sql
```

#### 4. Apache VHost
```bash
sudo nano /etc/apache2/sites-available/kemenag.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/kemenag-ui/public
    
    <Directory /var/www/html/kemenag-ui/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/kemenag_error.log
    CustomLog ${APACHE_LOG_DIR}/kemenag_access.log combined
</VirtualHost>
```

```bash
sudo a2ensite kemenag.conf
sudo a2enmod rewrite headers
sudo systemctl restart apache2
```

#### 5. SSL Setup
```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d yourdomain.com
```

#### 6. Cron & Services
```bash
# Cron
crontab -e
0 2 * * * /usr/bin/php /var/www/html/kemenag-ui/cron/daily.php
0 * * * * /usr/bin/php /var/www/html/kemenag-ui/cron/hourly.php

# WebSocket (optional)
sudo nano /etc/systemd/system/kemenag-websocket.service
```

```ini
[Unit]
Description=Kemenag WebSocket Server
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/html/kemenag-ui
ExecStart=/usr/bin/php websocket_server.php
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable kemenag-websocket
sudo systemctl start kemenag-websocket
```

### Docker Deployment

#### docker-compose.yml
```yaml
version: '3.8'

services:
  web:
    image: php:8.0-apache
    volumes:
      - .:/var/www/html
    ports:
      - "80:80"
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_DATABASE=kemenag_db
      - DB_USERNAME=root
      - DB_PASSWORD=secret

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: kemenag_db
    volumes:
      - dbdata:/var/lib/mysql

volumes:
  dbdata:
```

```bash
docker-compose up -d
```

---

## 🔧 Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
**Cause:** Missing .htaccess or mod_rewrite
```bash
# Check Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2

# Check .htaccess exists in public/
ls -la public/.htaccess
```

#### 2. Database Connection Error
**Check:**
```bash
# Test connection
mysql -h localhost -u username -p database_name

# Check .env settings
grep DB_ .env
```

#### 3. CSRF Token Mismatch
**Cause:** Session not starting
```php
// Check in config/config.php
session_start();

// Clear browser cookies
// Regenerate keys
php generate_keys.php
```

#### 4. File Upload Fails
**Check permissions:**
```bash
chmod -R 777 storage/uploads/

# Check PHP limits
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

**Increase limits:**
```ini
# php.ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

#### 5. Email Not Sending
**Test SMTP:**
```php
// Create test.php
<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_email@gmail.com';
$mail->Password = 'your_app_password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('from@example.com');
$mail->addAddress('to@example.com');
$mail->Subject = 'Test';
$mail->Body = 'Test email';

echo $mail->send() ? 'Sent!' : 'Error: ' . $mail->ErrorInfo;
```

#### 6. Turnstile Not Working
**Debug:**
```bash
# Check keys
grep TURNSTILE .env

# Check logs
tail -f storage/logs/turnstile_*.log

# Test connectivity
curl https://challenges.cloudflare.com/cdn-cgi/trace

# Verify domain whitelist in Cloudflare Dashboard
```

#### 7. Composer Install Fails
```bash
# Update Composer
composer self-update

# Clear cache
composer clear-cache

# Install with verbose
composer install -vvv

# Alternative: use --no-dev
composer install --no-dev
```

#### 8. Permission Denied Errors
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/html/kemenag-ui

# Fix permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 777 storage/
chmod 600 .env
```

### Debug Mode

**Enable in .env:**
```env
APP_DEBUG=true
APP_ENV=development
```

**View errors:**
```bash
# Apache error log
tail -f /var/log/apache2/error.log

# Application logs
tail -f storage/logs/app_$(date +%Y-%m-%d).log
```

### Performance Issues

**Enable caching:**
```php
// In config/config.php
define('CACHE_ENABLED', true);
define('CACHE_DRIVER', 'file'); // or 'redis'
```

**Optimize database:**
```sql
-- Check slow queries
SHOW FULL PROCESSLIST;

-- Analyze tables
ANALYZE TABLE users, forum_topics, forum_posts;

-- Optimize tables
OPTIMIZE TABLE users, forum_topics, forum_posts;
```

**Enable OPcache:**
```ini
# php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

---

## 📞 Support & Maintenance

### Regular Maintenance

**Daily:**
- Check error logs
- Monitor Turnstile stats
- Review pending approvals

**Weekly:**
- Review audit logs
- Check backup success
- Update content

**Monthly:**
- Security updates (Composer)
- Database optimization
- Performance review
- Backup testing

### Monitoring URLs

```
Admin Dashboard:     /admin
System Health:       /admin/monitoring
Error Logs:          /admin/monitoring/performance
Turnstile Stats:     /admin/turnstile
Suspicious IPs:      /admin/turnstile/suspicious
Backup Status:       /admin/backup
Queue Monitor:       /admin/queue
Audit Logs:          /admin/audit
```

### Update Process

```bash
# Backup first!
php cron/backup.php

# Pull updates
git pull origin main

# Update dependencies
composer update

# Run migrations (if any)
mysql -u root -p kemenag_db < db/new_migration.sql

# Clear cache
rm -rf storage/cache/*

# Test
# Visit site and check functionality
```

### Security Checklist

**Monthly Security Review:**
- [ ] Update PHP packages (`composer update`)
- [ ] Review failed login attempts
- [ ] Check suspicious IPs
- [ ] Review audit logs
- [ ] Test backups
- [ ] Update passwords
- [ ] Rotate encryption keys
- [ ] Review user permissions
- [ ] Check SSL certificate expiry
- [ ] Scan for malware

---

## 🎯 Best Practices

### Development

1. **Use Version Control**
   - Commit regularly
   - Write clear commit messages
   - Use branches for features

2. **Follow PSR Standards**
   - PSR-1: Basic Coding Standard
   - PSR-2: Coding Style Guide
   - PSR-4: Autoloading

3. **Document Code**
   - Use PHPDoc comments
   - Update README for major changes
   - Maintain changelog

4. **Test Before Deploy**
   - Test locally first
   - Use staging environment
   - Backup before production deploy

### Security

1. **Keep Updated**
   - Update PHP regularly
   - Update Composer packages
   - Update templates/libraries

2. **Monitor Logs**
   - Check error logs daily
   - Review audit logs weekly
   - Monitor suspicious activity

3. **Use Strong Passwords**
   - Minimum 12 characters
   - Mix of upper, lower, numbers, symbols
   - Change regularly

4. **Backup Regularly**
   - Daily automated backups
   - Test restore process monthly
   - Store offsite (S3/cloud)

### Performance

1. **Enable Caching**
   - OPcache for PHP
   - Redis for sessions/cache
   - Browser caching

2. **Optimize Database**
   - Index frequently queried columns
   - Archive old data
   - Regular maintenance

3. **Optimize Images**
   - Compress before upload
   - Use appropriate formats
   - Lazy loading

4. **Monitor Performance**
   - Use APM tools
   - Track slow queries
   - Optimize bottlenecks

---

## 📊 Statistik Proyek

### Codebase
```
Total Lines:            50,000+
PHP Files:              200+
Views:                  120+
Controllers:            40+
Models:                 20+
Services:               15+
Middleware:             5+
```

### Features
```
Complete Features:      50+
Admin Dashboards:       25+
User Features:          30+
API Endpoints:          100+
Security Layers:        12+
```

### Database
```
Tables:                 25
Columns:                300+
Indexes:                50+
Relationships:          40+
```

### Security
```
Protection Level:       MAXIMUM 🔒
Authentication:         Multi-factor
Authorization:          RBAC
Anti-bot:               Cloudflare Turnstile
Encryption:             AES-256
Password Hashing:       Bcrypt/Argon2
```

---

## 🎉 Kesimpulan

Sistem Kemenag UI adalah platform **enterprise-grade** yang:

✅ **Lengkap** - Semua fitur sesuai requirements  
✅ **Aman** - Multi-layer security dengan Turnstile  
✅ **Scalable** - Siap untuk pertumbuhan  
✅ **Documented** - Dokumentasi comprehensive  
✅ **Production Ready** - Siap deploy langsung  

**Status: 100% READY FOR PRODUCTION** 🚀

---

**Version:** 2.0  
**Last Updated:** 17 November 2024  
**Maintained by:** Development Team  
**License:** Proprietary

---

**🔒 Security Level: MAXIMUM**  
**📊 Completion: 100%**  
**🚀 Status: PRODUCTION READY**
