# 🚀 Advanced Features Implementation

## Ringkasan

Dokumen ini menjelaskan implementasi fitur-fitur advanced yang telah ditambahkan ke sistem Kemenag UI.

---

## 📊 1. Error Monitoring

### Fitur:
- ✅ Real-time error tracking
- ✅ Sentry integration
- ✅ Local error logging
- ✅ Performance tracking
- ✅ Slow query detection
- ✅ User action tracking
- ✅ Email alerts untuk critical errors
- ✅ Error statistics & analytics

### Files:
- `app/services/ErrorMonitoringService.php`
- `app/controllers/Admin/MonitoringController.php`
- `app/views/admin/monitoring/index.php`
- `app/views/admin/monitoring/performance.php`

### Usage:

```php
use App\Services\ErrorMonitoringService;

$monitoring = new ErrorMonitoringService();

// Capture exception
try {
    // code
} catch (Exception $e) {
    $monitoring->captureException($e, ['context' => 'additional data']);
}

// Capture message
$monitoring->captureMessage('User logged in', 'info', ['user_id' => 123]);

// Track performance
$start = microtime(true);
// ... operation ...
$duration = microtime(true) - $start;
$monitoring->trackPerformance('generate_pdf', $duration);
```

### Configuration (.env):
```env
SENTRY_DSN=your_sentry_dsn_here
ERROR_MONITORING_ENABLED=true
ADMIN_EMAIL=admin@kemenag.go.id
```

---

## 💾 2. Automated Backups

### Fitur:
- ✅ Full database backup (mysqldump)
- ✅ File system backup (uploads, config, logs)
- ✅ Compression (gzip)
- ✅ Automatic retention (30 days)
- ✅ Restore functionality
- ✅ Cloud storage upload (S3-ready)
- ✅ Backup statistics

### Files:
- `app/services/BackupService.php`
- `app/controllers/Admin/BackupController.php`
- `app/views/admin/backup/index.php`
- `cron/daily.php`

### Usage:

```php
use App\Services\BackupService;

$backup = new BackupService();

// Create full backup
$result = $backup->performFullBackup();
// Returns: ['database' => [...], 'files' => [...]]

// List backups
$backups = $backup->listBackups();

// Restore from backup
$backup->restoreDatabase('database_2024-11-17_143022.sql.gz');

// Get statistics
$stats = $backup->getBackupStats();
```

### Cron Setup:

```bash
# Daily backup at 2 AM
0 2 * * * php /path/to/cron/daily.php >> /path/to/logs/cron.log 2>&1
```

### Configuration (.env):
```env
BACKUP_RETENTION_DAYS=30
BACKUP_COMPRESSION=true
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_BUCKET=your-bucket
```

---

## 🔴 3. Real-time Features (WebSocket)

### Fitur:
- ✅ Real-time notifications
- ✅ Live chat
- ✅ Typing indicators
- ✅ User presence (online/offline)
- ✅ Broadcasting
- ✅ Authentication

### Files:
- `app/services/WebSocketService.php`
- `websocket_server.php`

### Usage:

**Start WebSocket Server:**
```bash
php websocket_server.php
```

**Client-side (JavaScript):**
```javascript
const ws = new WebSocket('ws://localhost:8080');

// Authenticate
ws.onopen = () => {
    ws.send(JSON.stringify({
        type: 'auth',
        user_id: 123,
        token: 'your_jwt_token'
    }));
};

// Receive messages
ws.onmessage = (event) => {
    const data = JSON.parse(event.data);
    console.log('Received:', data);
};

// Send chat message
ws.send(JSON.stringify({
    type: 'chat',
    to_user_id: 456,
    message: 'Hello!'
}));

// Send typing indicator
ws.send(JSON.stringify({
    type: 'typing',
    is_typing: true,
    channel: 'general'
}));
```

### Configuration (.env):
```env
WEBSOCKET_HOST=0.0.0.0
WEBSOCKET_PORT=8080
```

### Systemd Service (Linux):
```ini
[Unit]
Description=WebSocket Server
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/kemenag-ui-project
ExecStart=/usr/bin/php websocket_server.php
Restart=always

[Install]
WantedBy=multi-user.target
```

---

## 📱 4. Mobile PWA (Progressive Web App)

### Fitur:
- ✅ Offline support
- ✅ Add to home screen
- ✅ Push notifications
- ✅ Service worker caching
- ✅ Background sync
- ✅ App shortcuts
- ✅ Responsive icons

### Files:
- `public/manifest.json`
- `public/service-worker.js`
- `public/offline.html`

### Installation:

**1. Add to HTML `<head>`:**
```html
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#2563eb">
<link rel="apple-touch-icon" href="/assets/icons/icon-192x192.png">
```

**2. Register Service Worker:**
```javascript
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
        .then(reg => console.log('SW registered', reg))
        .catch(err => console.error('SW error', err));
}
```

**3. Request Push Notification Permission:**
```javascript
Notification.requestPermission().then(permission => {
    if (permission === 'granted') {
        console.log('Notifications enabled');
    }
});
```

### Generate Icons:
```bash
# Install imagemagick
sudo apt install imagemagick

# Generate all sizes from 512x512 source
convert icon-512x512.png -resize 72x72 icon-72x72.png
convert icon-512x512.png -resize 96x96 icon-96x96.png
convert icon-512x512.png -resize 128x128 icon-128x128.png
convert icon-512x512.png -resize 144x144 icon-144x144.png
convert icon-512x512.png -resize 152x152 icon-152x152.png
convert icon-512x512.png -resize 192x192 icon-192x192.png
convert icon-512x512.png -resize 384x384 icon-384x384.png
```

---

## 🤖 5. Advanced AI Features

### Fitur:
- ✅ Smart recommendations (collaborative filtering)
- ✅ Semantic search dengan intent recognition
- ✅ Auto-categorization
- ✅ Sentiment analysis
- ✅ Content quality scoring
- ✅ Duplicate detection
- ✅ Predictive analytics
- ✅ Churn prediction

### Files:
- `app/services/AdvancedAIService.php`

### Usage:

```php
use App\Services\AdvancedAIService;

$ai = new AdvancedAIService();

// Get recommendations
$recommendations = $ai->getRecommendations(userId: 123, type: 'qa');

// Smart search
$results = $ai->smartSearch('cara membuat sertifikat halal');
// Returns: ['intent' => '...', 'results' => [...], 'suggestions' => [...]]

// Auto-categorize
$categories = $ai->categorizeContent($content);

// Sentiment analysis
$sentiment = $ai->analyzeSentiment($userComment);
// Returns: ['sentiment' => 'positive', 'score' => 0.85]

// Content quality
$quality = $ai->scoreContentQuality($articleContent);

// Detect duplicates
$duplicates = $ai->detectDuplicates($newContent, 'qa');

// Predict certificate approval
$prediction = $ai->predictCertificateApproval($applicationData);

// Predict user churn
$churn = $ai->predictUserChurn(userId: 123);
```

---

## ⚡ 6. Queue System

### Fitur:
- ✅ Background job processing
- ✅ Job retry dengan exponential backoff
- ✅ Multiple queues
- ✅ Failed job handling
- ✅ Job scheduling
- ✅ Queue statistics

### Files:
- `app/services/QueueService.php`
- `queue_worker.php`
- `app/controllers/Admin/QueueController.php`
- `app/views/admin/queue/index.php`

### Usage:

**Dispatch Jobs:**
```php
use App\Services\QueueService;
use App\Services\SendEmailJob;

$queue = new QueueService();

// Simple dispatch
$jobId = $queue->dispatch(SendEmailJob::class, [
    'to' => 'user@example.com',
    'subject' => 'Welcome',
    'body' => 'Hello!'
]);

// Delayed dispatch
$jobId = $queue->dispatch(SendEmailJob::class, [
    'to' => 'user@example.com',
    'subject' => 'Reminder',
    'body' => 'Don\'t forget!',
    'delay' => time() + 3600 // 1 hour delay
], 'notifications');
```

**Create Custom Job:**
```php
namespace App\Jobs;

class ProcessImageJob
{
    public function handle(array $data)
    {
        $imagePath = $data['image_path'];
        
        // Resize image
        // Generate thumbnail
        // Upload to CDN
        
        echo "Image processed: {$imagePath}\n";
    }
}
```

**Start Worker:**
```bash
# Process default queue
php queue_worker.php

# Process specific queue
php queue_worker.php notifications

# With supervisor (recommended)
[program:queue-worker]
command=php /var/www/kemenag-ui-project/queue_worker.php
autostart=true
autorestart=true
user=www-data
numprocs=3
redirect_stderr=true
stdout_logfile=/var/log/queue-worker.log
```

---

## 🔧 7. Microservices Architecture

### Fitur:
- ✅ API Gateway
- ✅ Service routing
- ✅ Rate limiting
- ✅ Authentication forwarding
- ✅ CORS handling
- ✅ Request/response transformation

### Files:
- `microservices/api-gateway.php`

### Architecture:

```
Client → API Gateway → Service (Auth, Certificate, Forum, etc.)
```

### Services:
1. **Auth Service** (port 8001): User authentication, sessions
2. **Certificate Service** (port 8002): Halal certificate applications
3. **Forum Service** (port 8003): Discussion forums
4. **Content Service** (port 8004): Q&A, Fatwa, Materials
5. **Notification Service** (port 8005): Emails, push notifications

### Usage:

**Start API Gateway:**
```bash
php -S localhost:8000 microservices/api-gateway.php
```

**API Calls:**
```bash
# Auth
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"secret"}'

# Certificate
curl http://localhost:8000/api/certificate/applications \
  -H "Authorization: Bearer YOUR_TOKEN"

# Forum
curl http://localhost:8000/api/forum/topics?category=1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🎯 Integration Examples

### Full Stack Example:

```php
// Controller
public function createCertificateApplication()
{
    $monitoring = new ErrorMonitoringService();
    $queue = new QueueService();
    $ai = new AdvancedAIService();
    
    try {
        // Validate with AI
        $quality = $ai->scoreContentQuality($_POST['description']);
        if ($quality['score'] < 0.5) {
            throw new Exception('Application quality too low');
        }
        
        // Save to DB
        $applicationId = $this->model->create($_POST);
        
        // Dispatch async jobs
        $queue->dispatch(GeneratePDFJob::class, [
            'application_id' => $applicationId
        ]);
        
        $queue->dispatch(SendEmailJob::class, [
            'to' => $_POST['email'],
            'subject' => 'Application Received',
            'body' => 'Your application is being processed'
        ], 'notifications');
        
        // Track performance
        $monitoring->trackUserAction('create_application', [
            'application_id' => $applicationId
        ]);
        
        // Broadcast real-time notification
        // (via WebSocket to admin panel)
        
        redirect('/certificates/success');
        
    } catch (Exception $e) {
        $monitoring->captureException($e);
        redirect('/certificates/apply?error=1');
    }
}
```

---

## 📊 Admin Dashboard Routes

Tambahkan ke `app/routes.php`:

```php
// Monitoring
$routes['admin/monitoring'] = ['controller' => 'Admin/MonitoringController', 'action' => 'index'];
$routes['admin/monitoring/performance'] = ['controller' => 'Admin/MonitoringController', 'action' => 'performance'];

// Backup
$routes['admin/backup'] = ['controller' => 'Admin/BackupController', 'action' => 'index'];
$routes['admin/backup/create'] = ['controller' => 'Admin/BackupController', 'action' => 'create'];
$routes['admin/backup/restore/{id}'] = ['controller' => 'Admin/BackupController', 'action' => 'restore'];

// Queue
$routes['admin/queue'] = ['controller' => 'Admin/QueueController', 'action' => 'index'];
$routes['admin/queue/clear/{queue}'] = ['controller' => 'Admin/QueueController', 'action' => 'clear'];
```

---

## 🚀 Deployment Checklist

### 1. Install Dependencies:
```bash
composer install
composer require cboden/ratchet sentry/sentry predis/predis
```

### 2. Configure Environment:
```bash
cp .env.example .env
# Edit .env dengan configuration yang sesuai
```

### 3. Setup Cron Jobs:
```bash
crontab -e

# Add:
0 * * * * php /path/to/cron/hourly.php >> /var/log/cron.log 2>&1
0 2 * * * php /path/to/cron/daily.php >> /var/log/cron.log 2>&1
```

### 4. Start Services:
```bash
# WebSocket Server
php websocket_server.php &

# Queue Worker
php queue_worker.php &

# API Gateway
php -S localhost:8000 microservices/api-gateway.php &
```

### 5. Setup Systemd Services (Production):
```bash
sudo systemctl enable websocket
sudo systemctl enable queue-worker
sudo systemctl start websocket
sudo systemctl start queue-worker
```

---

## 📈 Monitoring & Maintenance

### Daily Tasks:
- ✅ Check error logs (`storage/logs/`)
- ✅ Verify backups (`storage/backups/`)
- ✅ Monitor queue (`admin/queue`)
- ✅ Review performance metrics (`admin/monitoring`)

### Weekly Tasks:
- ✅ Analyze error trends
- ✅ Optimize slow queries
- ✅ Review backup retention
- ✅ Update dependencies

### Monthly Tasks:
- ✅ Security audit
- ✅ Performance tuning
- ✅ Database optimization
- ✅ Cost analysis (cloud storage, etc.)

---

## 🔒 Security Considerations

1. **WebSocket**: Implement proper JWT validation
2. **API Gateway**: Enable HTTPS in production
3. **Backups**: Encrypt sensitive backups
4. **Queue**: Sanitize job data
5. **Error Monitoring**: Don't expose sensitive data in logs

---

## 📚 Additional Resources

- [Ratchet WebSocket Documentation](http://socketo.me/)
- [Sentry PHP Documentation](https://docs.sentry.io/platforms/php/)
- [PWA Documentation](https://web.dev/progressive-web-apps/)
- [Service Workers Guide](https://developers.google.com/web/fundamentals/primers/service-workers)

---

**🎉 Semua fitur advanced telah diimplementasikan dan siap digunakan!**
