# 📦 Installation Guide - Website Kemenag UI

## Quick Start (5 Minutes)

### Step 1: Install Dependencies
```bash
composer install
```

### Step 2: Configure Environment
```bash
cp .env.example .env
nano .env
```

Edit the following:
```env
# Application
APP_URL=http://your-domain.com
APP_ENV=production

# Database
DB_HOST=localhost
DB_NAME=kemenag_db
DB_USER=your_db_user
DB_PASS=your_db_password

# Email (SMTP)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-email-password
MAIL_FROM_ADDRESS=noreply@kemenag.go.id
MAIL_FROM_NAME="Kemenag UI"

# Google Gemini AI (Optional)
GEMINI_API_KEY=your-gemini-api-key

# Security
APP_KEY=your-32-character-random-key
ENCRYPTION_KEY=your-32-character-encryption-key
```

### Step 3: Create Database & Import Schema
```bash
mysql -u root -p
```

```sql
CREATE DATABASE kemenag_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kemenag_db;
SOURCE db/schema.sql;
EXIT;
```

### Step 4: Set Permissions
```bash
chmod 755 -R .
chmod 775 -R storage/
chmod 775 -R storage/uploads/
chmod 775 -R storage/cache/
chmod 775 -R storage/logs/
chmod 775 -R storage/sessions/
```

### Step 5: Test
Visit: `http://your-domain.com`

**Default Superadmin:**
- Username: `superadmin`
- Password: `Admin123!@#`

---

## Deployment Options

### Option 1: cPanel (Shared Hosting)
1. Upload files via FTP/File Manager
2. Create database via MySQL Databases
3. Import `db/schema.sql` via phpMyAdmin
4. Edit `.env` file
5. Done!

### Option 2: VPS/Cloud (Ubuntu)
```bash
# Install LAMP
sudo apt update
sudo apt install apache2 mysql-server php7.4 php7.4-{cli,mbstring,gd,curl,xml,zip,mysql,pdo}

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Configure Apache
sudo nano /etc/apache2/sites-available/kemenag.conf
```

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/kemenag/public
    
    <Directory /var/www/kemenag/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

```bash
sudo a2ensite kemenag.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Option 3: Docker
```bash
docker-compose up -d
```

---

## Post-Installation

### Security:
1. Change default superadmin password
2. Generate new APP_KEY
3. Enable HTTPS/SSL
4. Review `.htaccess` rules

### Optional Features:
1. Configure Google Gemini AI
2. Setup WhatsApp integration
3. Configure cron jobs
4. Enable rate limiting

### Testing:
1. Test registration & login
2. Apply for certificate
3. Create forum topic
4. Test admin functions
5. Send test email

---

## Troubleshooting

**Problem**: Page not found (404)
**Solution**: Enable mod_rewrite in Apache

**Problem**: Database connection error
**Solution**: Check DB credentials in .env

**Problem**: Permission denied
**Solution**: Run `chmod 775 -R storage/`

**Problem**: Email not sending
**Solution**: Check SMTP settings in .env

---

## Support

For questions or issues:
- Email: dev@kemenag.go.id
- Documentation: See FINAL_PROJECT_COMPLETE.md
- GitHub: [Your Repo URL]

---

**Installation Time**: ~5 minutes
**Difficulty**: Easy
**Requirements**: PHP 7.4+, MySQL 5.7+, Apache

🎉 **Enjoy your new Website Kemenag UI!**
