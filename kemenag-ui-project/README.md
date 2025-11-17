# Kemenag UI - Website Magang Clone Kemenag

Platform informasi keagamaan lengkap dengan sistem Help Desk Sertifikat Halal, Fatwa, Materi Moderasi, Perpustakaan Digital, Forum Diskusi, dan integrasi AI Chatbot.

## 🚀 Fitur Utama

### Frontend (Pengguna)
- ✅ **Beranda**: Informasi dan navigasi utama
- ✅ **Tanya Jawab Keagamaan**: Kategori pertanyaan keagamaan dengan pencarian
- ✅ **Help Desk Sertifikat Halal**: Sistem ticketing untuk pengajuan sertifikat halal
- ✅ **Informasi Fatwa**: Database fatwa dengan sistem komentar
- ✅ **Materi Moderasi-Toleransi**: Artikel dan video edukatif
- ✅ **Perpustakaan Digital**: Koleksi buku keagamaan dengan filter
- ✅ **Forum Diskusi**: Forum komunitas dengan kategori topik
- ✅ **Chat Internal**: Sistem pesan antar pengguna
- ✅ **Notifikasi Real-time**: Update status dan aktivitas
- ✅ **Chatbot AI**: Asisten AI menggunakan Google Gemini
- ✅ **WhatsApp Bot**: Integrasi WhatsApp untuk layanan 24/7
- ✅ **Dashboard Pengguna**: Panel lengkap untuk mengelola aktivitas

### Backend (Admin Panel)
- ✅ **Superadmin**: Kontrol penuh sistem
- ✅ **Admin Konten**: Kelola Q&A, fatwa, materi, buku
- ✅ **Admin Sertifikat**: Kelola pengajuan sertifikat halal
- ✅ **CRUD Management**: Semua entitas dapat dikelola
- ✅ **Role-Based Access Control (RBAC)**: Kontrol akses berbasis role
- ✅ **Audit Logging**: Pencatatan semua aktivitas penting
- ✅ **Multi-Factor Authentication (MFA)**: Keamanan tambahan
- ✅ **Export Excel**: Laporan dalam format Excel
- ✅ **Email Notifications**: Notifikasi otomatis via email

### Keamanan
- ✅ Password hashing menggunakan bcrypt
- ✅ Multi-Factor Authentication (TOTP)
- ✅ CSRF Protection
- ✅ Rate Limiting
- ✅ Input Validation & Sanitization
- ✅ SQL Injection Protection (Prepared Statements)
- ✅ XSS Protection
- ✅ Account Lockout Protection
- ✅ Audit Trail Logging

### SEO & Performance
- ✅ Meta Tags Dynamic
- ✅ Schema.org Markup
- ✅ XML Sitemap
- ✅ Robots.txt
- ✅ Open Graph Tags
- ✅ Twitter Cards
- ✅ Clean URLs
- ✅ Browser Caching
- ✅ Gzip Compression

## 🛠️ Teknologi

### Backend
- **PHP**: 7.4+ (Native OOP/MVC)
- **MySQL**: 5.7+ (InnoDB)
- **Apache**: Web server dengan mod_rewrite

### Frontend
- **News5**: Template untuk tampilan publik
- **Morvin HTML**: Template untuk admin panel
- **jQuery**: JavaScript library
- **Bootstrap**: CSS framework

### Libraries
- **PHPMailer**: Email notifications
- **PHPSpreadsheet**: Excel export
- **OTPHP**: MFA/TOTP authentication
- **Google Gemini PHP**: AI integration
- **Guzzle**: HTTP client untuk API calls

### Integrasi
- **Google Gemini AI**: Chatbot intelligent
- **WAHA**: WhatsApp HTTP API
- **Fonnte**: Alternative WhatsApp gateway

## 📁 Struktur Project

```
kemenag-ui-project/
├── app/
│   ├── controllers/          # Controllers (Frontend & Admin)
│   │   ├── Admin/           # Admin controllers
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── ...
│   ├── models/              # Models (20+ models)
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── CertificateApplication.php
│   │   └── ...
│   ├── views/               # Views
│   │   ├── frontend/       # Frontend views
│   │   ├── admin/          # Admin views
│   │   ├── layouts/        # Layout templates
│   │   ├── emails/         # Email templates
│   │   └── errors/         # Error pages
│   ├── services/            # Service classes
│   │   ├── EmailService.php
│   │   ├── MFAService.php
│   │   ├── GeminiService.php
│   │   ├── WhatsAppService.php
│   │   └── ExcelService.php
│   ├── core/                # Core system
│   │   ├── Database.php
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Model.php
│   │   └── View.php
│   ├── helpers.php          # Helper functions
│   └── routes.php           # Application routes
├── config/
│   └── config.php           # Configuration
├── db/
│   ├── schema.sql          # Database schema
│   └── migrations/         # Database migrations
├── public/                  # Public web root
│   ├── index.php           # Entry point
│   ├── assets/             # CSS, JS, images
│   ├── uploads/            # User uploads
│   └── .htaccess           # Apache configuration
├── storage/                 # Storage directory
│   ├── logs/               # Application logs
│   ├── cache/              # Cache files
│   └── exports/            # Excel exports
├── vendor/                  # Composer dependencies
├── .env.example            # Environment configuration example
├── .htaccess               # Root htaccess
├── composer.json           # Composer configuration
└── README.md              # This file
```

## 🔧 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- Composer
- Git

### Step 1: Clone Repository

```bash
cd /your/web/directory
git clone <repository-url> kemenag-ui-project
cd kemenag-ui-project
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Configure Environment

```bash
# Copy .env.example to .env
cp .env.example .env

# Edit .env file with your configurations
nano .env
```

**Important configurations:**
- Database credentials (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- SMTP/Email settings (MAIL_HOST, MAIL_USERNAME, MAIL_PASSWORD)
- Google Gemini API Key (GEMINI_API_KEY)
- WhatsApp API settings (WAHA atau Fonnte)
- Encryption keys (generate with: `php -r "echo bin2hex(random_bytes(32));"`)

### Step 4: Setup Database

```bash
# Create database
mysql -u root -p
CREATE DATABASE kemenag_ui_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# Import schema
mysql -u root -p kemenag_ui_db < db/schema.sql
```

### Step 5: Set Permissions

```bash
# Set correct permissions
chmod -R 755 public/
chmod -R 755 storage/
chmod -R 755 public/uploads/

# If needed
chown -R www-data:www-data storage/
chown -R www-data:www-data public/uploads/
```

### Step 6: Configure Apache

For cPanel/shared hosting, no additional configuration needed. The `.htaccess` files are already configured.

For VPS/dedicated server, create virtual host:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/kemenag-ui-project/public
    
    <Directory /path/to/kemenag-ui-project/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/kemenag-error.log
    CustomLog ${APACHE_LOG_DIR}/kemenag-access.log combined
</VirtualHost>
```

### Step 7: Test Installation

1. Open browser: `http://your-domain.com`
2. Login with default superadmin:
   - Username: `superadmin`
   - Password: `Admin123!`

**⚠️ IMPORTANT: Change default password immediately!**

## 🔐 Default Credentials

| Role | Username | Password | Access |
|------|----------|----------|--------|
| Superadmin | superadmin | Admin123! | Full system access |

**Roles Available:**
- `superadmin`: Full system control
- `admin_konten`: Content management
- `admin_sertifikat`: Certificate management
- `user`: Regular user with limited access

## 📖 Usage Guide

### For Administrators

#### Managing Content
1. Login to admin panel: `/admin`
2. Navigate to relevant section (Q&A, Fatwa, Materials, Books)
3. Use CRUD operations to manage content

#### Managing Certificates
1. Go to `/admin/certificates`
2. View all certificate applications
3. Assign to admin, review, approve/reject
4. Generate certificate PDF

#### Managing Users
1. Go to `/admin/users`
2. Create, edit, delete users
3. Assign roles and permissions

#### Viewing Audit Logs
1. Go to `/admin/audit-logs`
2. Filter by user, action, or date
3. Export to Excel for reporting

### For Users

#### Applying for Halal Certificate
1. Login to account
2. Go to `/certificate/apply`
3. Fill out application form
4. Upload required documents
5. Submit and receive ticket number
6. Track status at `/certificate/track/{ticket}`

#### Participating in Forum
1. Go to `/forum`
2. Choose category
3. Create topic or reply to existing ones

#### Using Chatbot
1. Click chatbot icon (bottom right)
2. Ask questions about Islam, halal, etc.
3. AI will respond intelligently

## 🔌 API Integration

### WhatsApp Bot Setup

#### Using WAHA (WhatsApp HTTP API)

1. Install WAHA:
```bash
docker run -d -p 3000:3000 devlikeapro/whatsapp-http-api
```

2. Configure in `.env`:
```env
WHATSAPP_PROVIDER=waha
WAHA_API_URL=http://localhost:3000
WAHA_API_KEY=your_api_key
```

3. Setup webhook: `/whatsapp/webhook`

#### Using Fonnte

1. Register at fonnte.com
2. Get API token
3. Configure in `.env`:
```env
WHATSAPP_PROVIDER=fonnte
FONNTE_TOKEN=your_fonnte_token
```

### Google Gemini AI Setup

1. Get API key from [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Configure in `.env`:
```env
GEMINI_API_KEY=your_api_key_here
GEMINI_MODEL=gemini-pro
```

## 📊 Database Schema

Database includes 20+ tables:
- `roles`: User roles and permissions
- `users`: User accounts
- `categories`: Content categories
- `questions_answers`: Q&A content
- `fatwas`: Fatwa content
- `materials`: Moderation materials
- `books`: Digital library
- `certificate_applications`: Certificate applications
- `forum_categories`, `forum_topics`, `forum_posts`: Forum system
- `internal_messages`: Internal messaging
- `notifications`: User notifications
- `audit_logs`: Activity logging
- And more...

See `db/schema.sql` for complete schema.

## 🔒 Security Best Practices

1. **Change default credentials** immediately after installation
2. **Enable MFA** for all admin accounts
3. **Use strong passwords** (minimum 12 characters)
4. **Keep software updated** (PHP, MySQL, dependencies)
5. **Regular backups** of database and files
6. **Monitor audit logs** for suspicious activity
7. **Use HTTPS** in production
8. **Configure firewall** properly
9. **Set proper file permissions**
10. **Review RBAC permissions** regularly

## 🚀 Deployment

See `deployment_instructions.md` for detailed deployment guide for:
- Shared hosting (cPanel)
- VPS/Cloud (Ubuntu, CentOS)
- Docker containers

## 📝 Development

### Adding New Features

1. Create model in `app/models/`
2. Create controller in `app/controllers/`
3. Add routes in `app/routes.php`
4. Create views in `app/views/`
5. Update database schema if needed

### Code Style

Follow PSR-1 and PSR-2 coding standards:
```php
<?php

namespace App\Controllers;

class ExampleController extends Controller
{
    public function index()
    {
        // Your code here
    }
}
```

### Helper Functions

Use helper functions from `app/helpers.php`:
```php
url('/path');              // Generate URL
asset('/css/style.css');   // Generate asset URL
csrf_field();              // Generate CSRF field
auth();                    // Check if authenticated
e($string);                // Escape HTML
flash('key');              // Get flash message
```

## 🐛 Troubleshooting

### Common Issues

**500 Internal Server Error**
- Check file permissions (755 for folders, 644 for files)
- Check `.htaccess` configuration
- Enable mod_rewrite in Apache
- Check error logs: `storage/logs/error.log`

**Database Connection Error**
- Verify database credentials in `.env`
- Ensure MySQL server is running
- Check if database exists

**Composer Dependencies Error**
- Run `composer install` or `composer update`
- Check PHP version (minimum 7.4)

**Email Not Sending**
- Verify SMTP credentials in `.env`
- Check if firewall blocks SMTP ports
- Try different mail driver (smtp, sendmail, mail)

## 📄 License

Proprietary - © 2024 Kementerian Agama Republik Indonesia

## 👥 Support

For support and questions:
- Email: support@kemenag.go.id
- Documentation: See `/docs` folder
- Issue Tracker: GitHub Issues

## 🎯 Roadmap

- [ ] Progressive Web App (PWA) support
- [ ] Mobile app (React Native)
- [ ] Advanced analytics dashboard
- [ ] Multi-language support (English, Arabic)
- [ ] Video streaming for materials
- [ ] Live chat support
- [ ] Integration with more payment gateways
- [ ] API documentation (Swagger/OpenAPI)

## 📚 Additional Resources

- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Composer](https://getcomposer.org/)
- [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- [Google Gemini AI](https://ai.google.dev/)

---

**Built with ❤️ for Kementerian Agama Republik Indonesia**
