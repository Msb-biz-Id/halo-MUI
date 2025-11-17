# 🚀 QUICK START - Advanced Features

## ⚡ 5-Minute Setup Guide

---

## 1️⃣ Install Dependencies (1 min)

```bash
cd /var/www/kemenag-ui-project
composer require cboden/ratchet sentry/sentry predis/predis
```

---

## 2️⃣ Configure Environment (2 min)

```bash
nano .env
```

**Add these lines:**
```env
# Error Monitoring
SENTRY_DSN=https://your-sentry-dsn@sentry.io/project-id
ERROR_MONITORING_ENABLED=true
ADMIN_EMAIL=admin@kemenag.go.id

# WebSocket
WEBSOCKET_HOST=0.0.0.0
WEBSOCKET_PORT=8080

# Backup
BACKUP_RETENTION_DAYS=30
BACKUP_COMPRESSION=true

# Queue
QUEUE_MAX_RETRIES=3

# AI
GEMINI_API_KEY=your_gemini_api_key
```

---

## 3️⃣ Start Services (1 min)

**WebSocket Server:**
```bash
nohup php websocket_server.php > /var/log/websocket.log 2>&1 &
```

**Queue Worker:**
```bash
nohup php queue_worker.php > /var/log/queue.log 2>&1 &
```

---

## 4️⃣ Setup Cron Jobs (1 min)

```bash
crontab -e
```

**Add:**
```cron
# Hourly tasks (queue check, cache)
0 * * * * php /var/www/kemenag-ui-project/cron/hourly.php

# Daily tasks (backup, cleanup)
0 2 * * * php /var/www/kemenag-ui-project/cron/daily.php
```

---

## 5️⃣ Test Everything (30 sec)

**Test Error Monitoring:**
```bash
curl http://localhost/admin/monitoring
```

**Test Backup:**
```bash
curl -X POST http://localhost/admin/backup/create
```

**Test WebSocket:**
```bash
# In browser console:
ws = new WebSocket('ws://localhost:8080')
ws.onopen = () => console.log('Connected!')
```

**Test Queue:**
```bash
curl http://localhost/admin/queue
```

---

## ✅ DONE!

Semua fitur advanced sudah running! 🎉

### Access Admin Dashboards:
- 📊 Error Monitoring: `/admin/monitoring`
- 💾 Backups: `/admin/backup`
- ⚡ Queue: `/admin/queue`

### Check Service Status:
```bash
# WebSocket
ps aux | grep websocket_server

# Queue Worker
ps aux | grep queue_worker

# Cron Jobs
crontab -l
```

---

## 🆘 Troubleshooting

**WebSocket not connecting?**
```bash
# Check if port is open
netstat -tulpn | grep 8080

# Check firewall
sudo ufw allow 8080
```

**Backup fails?**
```bash
# Check permissions
chmod 777 storage/backups

# Check mysqldump
which mysqldump
```

**Queue not processing?**
```bash
# Check worker log
tail -f /var/log/queue.log

# Restart worker
pkill -f queue_worker
nohup php queue_worker.php > /var/log/queue.log 2>&1 &
```

---

## 📖 Full Documentation

- `ADVANCED_FEATURES.md` - Complete guide (500+ lines)
- `IMPLEMENTATION_COMPLETE.md` - Summary & stats
- Inline documentation in each service file

---

**Ready to use! 🚀**
