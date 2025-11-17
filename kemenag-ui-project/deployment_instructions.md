# Deployment Instructions

Panduan lengkap deployment aplikasi Kemenag UI ke berbagai environment.

## 📋 Table of Contents

1. [Shared Hosting (cPanel)](#shared-hosting-cpanel)
2. [VPS/Cloud Server](#vpscloud-server)
3. [Docker Container](#docker-container)
4. [Post-Deployment](#post-deployment)

---

## 🌐 Shared Hosting (cPanel)

### Prerequisites
- cPanel hosting account
- PHP 7.4 or higher
- MySQL 5.7 or higher
- SSH access (optional but recommended)
- Composer access

### Step 1: Prepare Files

```bash
# On local machine, create deployment package
cd kemenag-ui-project
composer install --no-dev --optimize-autoloader
zip -r kemenag-ui-deploy.zip . -x "*.git*" "node_modules/*" "*.env"
```

### Step 2: Upload Files

**Option A: Using File Manager**
1. Login to cPanel
2. Open File Manager
3. Navigate to `public_html` or your domain directory
4. Upload `kemenag-ui-deploy.zip`
5. Extract the ZIP file
6. Delete the ZIP file

**Option B: Using FTP**
1. Connect via FTP client (FileZilla, WinSCP)
2. Upload all files to `public_html`

**Option C: Using SSH**
```bash
scp kemenag-ui-deploy.zip user@yourhost.com:~/public_html/
ssh user@yourhost.com
cd public_html
unzip kemenag-ui-deploy.zip
rm kemenag-ui-deploy.zip
```

### Step 3: Setup Database

1. Login to cPanel
2. Go to "MySQL Databases"
3. Create new database: `username_kemenag_db`
4. Create MySQL user with strong password
5. Add user to database with ALL PRIVILEGES
6. Note down database credentials

**Import Schema:**
1. Go to phpMyAdmin
2. Select your database
3. Click "Import" tab
4. Upload `db/schema.sql`
5. Click "Go"

### Step 4: Configure Environment

```bash
# Via File Manager or SSH
cd public_html
cp .env.example .env
nano .env  # or use File Manager editor
```

Update configuration:
```env
APP_URL=https://yourdomain.com
DB_HOST=localhost
DB_DATABASE=username_kemenag_db
DB_USERNAME=username_dbuser
DB_PASSWORD=your_strong_password

# Email (use cPanel email or Gmail)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls

# Generate new keys
APP_KEY=base64:...
ENCRYPTION_KEY=...
```

Generate keys:
```bash
php -r "echo 'APP_KEY=base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
php -r "echo 'ENCRYPTION_KEY=' . bin2hex(random_bytes(32)) . PHP_EOL;"
```

### Step 5: Set Permissions

```bash
# Via SSH
chmod -R 755 public/
chmod -R 755 storage/
chmod -R 755 public/uploads/
```

Or via File Manager:
- Right click on folders → Change Permissions
- Set to 755 for directories
- Set to 644 for files

### Step 6: Configure .htaccess

If your hosting requires, update the RewriteBase in `.htaccess`:

```apache
RewriteBase /
# Or if in subdirectory:
RewriteBase /subdirectory/
```

### Step 7: Point Domain to Public Folder

**Option A: Using cPanel Domain Manager**
1. Go to "Domains" or "Addon Domains"
2. Set Document Root to `/public_html/kemenag-ui-project/public`

**Option B: Using Symbolic Link**
```bash
# Remove default public_html content
cd ~/
mv public_html public_html_backup
ln -s kemenag-ui-project/public public_html
```

### Step 8: Install Composer Dependencies (if not done)

If SSH is available:
```bash
cd public_html
composer install --no-dev --optimize-autoloader
```

### Step 9: Test Installation

1. Visit: `https://yourdomain.com`
2. Check if homepage loads
3. Try login: `/admin`
4. Test database connection

### Troubleshooting cPanel

**Issue: 500 Internal Server Error**
```bash
# Check error logs
tail -f ~/logs/error_log

# Fix permissions
chmod 644 .htaccess
chmod 755 public/

# Disable .htaccess rules one by one to find issue
```

**Issue: Composer not available**
- Download composer.phar to your account
- Run: `php composer.phar install --no-dev`
- Or ask hosting support to install dependencies

---

## 🖥️ VPS/Cloud Server

### Prerequisites
- Ubuntu 20.04/22.04 or CentOS 7/8
- Root or sudo access
- At least 1GB RAM, 1 CPU core, 20GB disk

### Step 1: Setup Server (Ubuntu 22.04)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache
sudo apt install apache2 -y
sudo systemctl enable apache2

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install PHP 8.1
sudo apt install php8.1 php8.1-cli php8.1-common php8.1-mysql \
    php8.1-xml php8.1-curl php8.1-mbstring php8.1-zip php8.1-gd -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Git
sudo apt install git -y
```

### Step 2: Configure MySQL

```bash
# Create database and user
sudo mysql -u root -p

CREATE DATABASE kemenag_ui_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kemenag_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON kemenag_ui_db.* TO 'kemenag_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import schema
mysql -u kemenag_user -p kemenag_ui_db < db/schema.sql
```

### Step 3: Clone and Setup Application

```bash
# Clone repository
cd /var/www/
sudo git clone <repository-url> kemenag-ui
cd kemenag-ui

# Install dependencies
sudo composer install --no-dev --optimize-autoloader

# Setup environment
sudo cp .env.example .env
sudo nano .env
# Configure all settings

# Set permissions
sudo chown -R www-data:www-data /var/www/kemenag-ui
sudo chmod -R 755 /var/www/kemenag-ui
sudo chmod -R 755 storage/
sudo chmod -R 755 public/uploads/
```

### Step 4: Configure Apache

Create virtual host:
```bash
sudo nano /etc/apache2/sites-available/kemenag-ui.conf
```

Add configuration:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    ServerAdmin admin@yourdomain.com
    
    DocumentRoot /var/www/kemenag-ui/public
    
    <Directory /var/www/kemenag-ui/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/kemenag-error.log
    CustomLog ${APACHE_LOG_DIR}/kemenag-access.log combined
    
    # Security headers
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
</VirtualHost>
```

Enable site and modules:
```bash
sudo a2enmod rewrite headers
sudo a2ensite kemenag-ui.conf
sudo a2dissite 000-default.conf
sudo systemctl restart apache2
```

### Step 5: Setup SSL with Let's Encrypt

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal is automatic, test with:
sudo certbot renew --dry-run
```

### Step 6: Setup Firewall

```bash
# Configure UFW
sudo ufw allow OpenSSH
sudo ufw allow 'Apache Full'
sudo ufw enable
sudo ufw status
```

### Step 7: Setup Cron Jobs (Optional)

```bash
sudo crontab -e
```

Add cron jobs:
```cron
# Clear expired sessions daily
0 2 * * * find /var/www/kemenag-ui/storage/sessions -type f -mtime +1 -delete

# Backup database daily
0 3 * * * mysqldump -u kemenag_user -p'password' kemenag_ui_db > /var/backups/kemenag_$(date +\%Y\%m\%d).sql

# Clear old logs monthly
0 4 1 * * find /var/www/kemenag-ui/storage/logs -type f -mtime +30 -delete
```

### Step 8: Monitoring & Logs

```bash
# View Apache logs
sudo tail -f /var/log/apache2/kemenag-error.log

# View application logs
sudo tail -f /var/www/kemenag-ui/storage/logs/error.log

# Monitor server resources
htop
```

---

## 🐳 Docker Container

### Dockerfile

Create `Dockerfile`:
```dockerfile
FROM php:8.1-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache modules
RUN a2enmod rewrite headers

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage
RUN chmod -R 755 /var/www/html/public/uploads

# Expose port
EXPOSE 80
```

### docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: kemenag-app
    restart: unless-stopped
    ports:
      - "80:80"
    volumes:
      - ./:/var/www/html
      - ./storage:/var/www/html/storage
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    depends_on:
      - db
    networks:
      - kemenag-network

  db:
    image: mysql:8.0
    container_name: kemenag-db
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: kemenag_ui_db
      MYSQL_USER: kemenag_user
      MYSQL_PASSWORD: kemenag_password
    volumes:
      - db-data:/var/lib/mysql
      - ./db/schema.sql:/docker-entrypoint-initdb.d/schema.sql
    networks:
      - kemenag-network

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: kemenag-phpmyadmin
    restart: unless-stopped
    ports:
      - "8080:80"
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
    depends_on:
      - db
    networks:
      - kemenag-network

volumes:
  db-data:

networks:
  kemenag-network:
    driver: bridge
```

### Deploy with Docker

```bash
# Build and start containers
docker-compose up -d

# View logs
docker-compose logs -f app

# Access container
docker exec -it kemenag-app bash

# Stop containers
docker-compose down

# Rebuild after changes
docker-compose up -d --build
```

---

## ✅ Post-Deployment Checklist

### Security

- [ ] Change default admin password
- [ ] Enable MFA for all admin accounts
- [ ] Configure SSL/HTTPS
- [ ] Setup firewall rules
- [ ] Review file permissions
- [ ] Disable directory listing
- [ ] Hide PHP version
- [ ] Configure CORS if needed
- [ ] Setup rate limiting
- [ ] Review security headers

### Configuration

- [ ] Configure all .env variables
- [ ] Setup email (SMTP)
- [ ] Configure Google Gemini API
- [ ] Setup WhatsApp integration
- [ ] Configure file upload limits
- [ ] Setup cron jobs
- [ ] Configure session lifetime
- [ ] Setup error logging
- [ ] Configure backups

### Testing

- [ ] Test homepage
- [ ] Test user registration
- [ ] Test email sending
- [ ] Test file uploads
- [ ] Test admin login
- [ ] Test MFA
- [ ] Test certificate application
- [ ] Test forum functionality
- [ ] Test chatbot
- [ ] Test WhatsApp bot
- [ ] Test all CRUD operations
- [ ] Load testing
- [ ] Security audit

### Optimization

- [ ] Enable OPcache
- [ ] Configure MySQL query cache
- [ ] Setup Redis/Memcached (optional)
- [ ] Configure CDN for assets
- [ ] Optimize images
- [ ] Minify CSS/JS
- [ ] Enable gzip compression
- [ ] Setup monitoring

### Backups

- [ ] Database backup script
- [ ] File backup script
- [ ] Backup verification
- [ ] Offsite backup storage
- [ ] Backup retention policy
- [ ] Recovery procedure documentation

### Monitoring

- [ ] Setup uptime monitoring
- [ ] Configure error alerts
- [ ] Setup performance monitoring
- [ ] Configure log rotation
- [ ] Setup analytics
- [ ] Database performance monitoring

---

## 🔄 Update/Maintenance

### Updating Application

```bash
# Backup first!
mysqldump -u user -p database > backup_$(date +%Y%m%d).sql
tar -czf files_backup_$(date +%Y%m%d).tar.gz /var/www/kemenag-ui

# Pull updates
cd /var/www/kemenag-ui
git pull origin main

# Update dependencies
composer install --no-dev
composer dump-autoload -o

# Run migrations if any
# php migrate.php

# Clear cache
rm -rf storage/cache/*

# Restart services
sudo systemctl restart apache2
```

### Database Backup

```bash
# Manual backup
mysqldump -u kemenag_user -p kemenag_ui_db | gzip > backup_$(date +%Y%m%d).sql.gz

# Restore from backup
gunzip < backup_20240101.sql.gz | mysql -u kemenag_user -p kemenag_ui_db
```

---

## 📞 Support

For deployment issues:
- Check logs: `storage/logs/error.log`
- Apache logs: `/var/log/apache2/`
- MySQL logs: `/var/log/mysql/`
- Email: support@kemenag.go.id

---

**Last Updated**: 2024
**Version**: 1.0.0
