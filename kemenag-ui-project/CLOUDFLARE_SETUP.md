# 🔐 Cloudflare Turnstile Setup Guide

**Complete Step-by-Step Guide untuk Setup Cloudflare Turnstile**

---

## 📋 Daftar Isi

1. [Apa itu Cloudflare Turnstile?](#apa-itu-cloudflare-turnstile)
2. [Cara Mendapatkan API Keys](#cara-mendapatkan-api-keys)
3. [Konfigurasi di Project](#konfigurasi-di-project)
4. [Testing & Verification](#testing--verification)
5. [Troubleshooting](#troubleshooting)

---

## 🎯 Apa itu Cloudflare Turnstile?

Cloudflare Turnstile adalah alternatif CAPTCHA yang:
- ✅ **Privacy-first** - Tidak tracking pengguna
- ✅ **User-friendly** - Tidak perlu solve puzzle
- ✅ **Gratis** - 1 juta request/bulan free
- ✅ **Cepat** - Verifikasi dalam milidetik
- ✅ **Powerful** - Deteksi bot dengan AI

**Melindungi dari:**
- Brute-force attacks
- Bot spam
- DDoS via automation
- Credential stuffing
- Scraping otomatis

---

## 🔑 Cara Mendapatkan API Keys

### Step 1: Buat Akun Cloudflare

1. Kunjungi: https://dash.cloudflare.com/sign-up
2. Daftar dengan email (GRATIS)
3. Verifikasi email Anda

### Step 2: Akses Turnstile Dashboard

1. Login ke: https://dash.cloudflare.com/
2. Di sidebar kiri, klik **"Turnstile"**
3. Atau langsung ke: https://dash.cloudflare.com/?to=/:account/turnstile

### Step 3: Buat Site Baru

1. Klik tombol **"Add Site"** atau **"Add a site"**

2. Isi form dengan detail:

```
Site Name:          Kemenag UI
Domain:             yourdomain.com
                    (atau gunakan "localhost" untuk testing)
```

3. Pilih **Widget Mode**:
   - **Managed** (Recommended) - Cloudflare decides when to challenge
   - **Non-Interactive** - Always invisible
   - **Invisible** - Hidden widget

4. Klik **"Create"**

### Step 4: Copy Keys

Setelah site dibuat, Anda akan melihat:

```
┌─────────────────────────────────────────────────────┐
│ Site Key (Public)                                   │
│ 1x00000000000000000000AA                            │
│ [Copy] button                                        │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ Secret Key (Private)                                │
│ 1x0000000000000000000000000000000AA                 │
│ [Copy] button                                        │
└─────────────────────────────────────────────────────┘
```

**PENTING:**
- **Site Key** = Public (aman di frontend)
- **Secret Key** = Private (JANGAN share/commit!)

---

## ⚙️ Konfigurasi di Project

### Step 1: Update .env

Edit file `.env` di root project:

```bash
nano .env
```

Tambahkan/update:

```env
# Cloudflare Turnstile Configuration
TURNSTILE_ENABLED=true
TURNSTILE_SITE_KEY=1x00000000000000000000AA
TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA

# Optional: Widget Configuration
TURNSTILE_THEME=light        # light, dark, auto
TURNSTILE_SIZE=normal        # normal, compact, flexible
TURNSTILE_LANGUAGE=auto      # auto, en, id, ar

# Optional: Logging
TURNSTILE_LOGGING=true
TURNSTILE_LOG_SUCCESS=true
TURNSTILE_LOG_FAILURE=true

# Optional: Suspicious IP Detection
TURNSTILE_SUSPICIOUS_THRESHOLD=5    # Failed attempts
TURNSTILE_SUSPICIOUS_WINDOW=24      # Hours
```

### Step 2: Verify Config File

File konfigurasi ada di: `config/turnstile.php`

Check apakah file ini exist:

```bash
ls -la config/turnstile.php
```

Jika tidak ada, file ini sudah otomatis dibuat dengan default config.

### Step 3: Set Permissions

```bash
# Pastikan .env tidak bisa dibaca publik
chmod 600 .env

# Pastikan log directory bisa ditulis
chmod -R 777 storage/logs/
```

---

## 🧪 Testing & Verification

### Test 1: Check Environment Variables

```bash
# Via command line
grep TURNSTILE .env

# Should output:
# TURNSTILE_ENABLED=true
# TURNSTILE_SITE_KEY=your_site_key
# TURNSTILE_SECRET_KEY=your_secret_key
```

### Test 2: Visit Login Page

```bash
# Buka browser
http://localhost/auth/login

# atau
http://yourdomain.com/auth/login
```

**Yang harus Anda lihat:**
- Widget Turnstile muncul di bawah form password
- Widget menampilkan checkbox atau loading indicator
- Widget bertuliskan "Verifying you are human..."

**Jika widget TIDAK muncul:**
- Check browser console (F12) untuk errors
- Pastikan `TURNSTILE_ENABLED=true`
- Pastikan `TURNSTILE_SITE_KEY` benar

### Test 3: Test Login

1. Isi form login
2. Complete Turnstile (klik checkbox jika muncul)
3. Submit form
4. Jika berhasil → login success
5. Jika gagal → lihat error message

### Test 4: Admin Test Page

Visit admin test page:

```
http://localhost/admin/turnstile/test
```

**Steps:**
1. Login sebagai admin
2. Go to `/admin/turnstile/test`
3. Complete Turnstile widget
4. Click "Test Verification"
5. Check result:
   - ✅ Success: "Verification passed!"
   - ❌ Failed: Error message with details

### Test 5: Check Logs

```bash
# View today's Turnstile logs
tail -f storage/logs/turnstile_$(date +%Y-%m-%d).log

# Should show:
# {"success":true,"challenge_ts":"...","hostname":"...","ip":"..."}
```

### Test 6: Admin Dashboard

Visit monitoring dashboard:

```
http://localhost/admin/turnstile
```

**Should display:**
- Total verifications
- Success/failure rate
- Chart by day
- Top suspicious IPs

---

## 🔍 Troubleshooting

### Issue 1: Widget Tidak Muncul

**Symptoms:**
- No Turnstile widget on form
- Form works but no security check

**Solutions:**

1. **Check .env:**
```bash
grep TURNSTILE_ENABLED .env
# Should be: TURNSTILE_ENABLED=true
```

2. **Check Site Key:**
```bash
grep TURNSTILE_SITE_KEY .env
# Should have valid key, not empty
```

3. **Check Browser Console:**
```
Open browser DevTools (F12)
Go to Console tab
Look for errors like:
- "Invalid site key"
- "Failed to load Turnstile"
```

4. **Clear Cache:**
```bash
# Clear browser cache (Ctrl+Shift+Delete)
# Or hard refresh (Ctrl+F5)
```

### Issue 2: "Invalid Site Key" Error

**Cause:** Site key salah atau tidak valid

**Fix:**

1. Login ke Cloudflare Dashboard
2. Go to Turnstile
3. Click your site
4. **Copy site key** (pastikan tidak ada spasi!)
5. Update `.env`:
```env
TURNSTILE_SITE_KEY=correct_key_here
```

### Issue 3: "Verification Failed" saat Submit

**Cause:** Secret key salah atau token expired

**Fix:**

1. **Check Secret Key:**
```bash
grep TURNSTILE_SECRET_KEY .env
# Pastikan benar dan sesuai dengan Cloudflare
```

2. **Check Logs:**
```bash
tail -f storage/logs/turnstile_*.log
# Look for error codes
```

3. **Common Error Codes:**
```
missing-input-secret    → Secret key tidak ada di .env
invalid-input-secret    → Secret key salah
missing-input-response  → User tidak complete Turnstile
invalid-input-response  → Token tidak valid
timeout-or-duplicate    → Token expired atau sudah digunakan
```

### Issue 4: Domain Not Allowed

**Symptoms:**
- Widget shows: "Invalid domain"
- Verification fails with domain error

**Fix:**

1. Go to Cloudflare Dashboard → Turnstile → Your Site
2. Click **"Settings"**
3. Under **"Domains"**, add:
   ```
   localhost              (for local development)
   yourdomain.com         (for production)
   www.yourdomain.com     (if using www)
   ```
4. Save changes
5. Wait 1-2 minutes for propagation

### Issue 5: Widget Muncul tapi Loading Terus

**Cause:** Network issue atau Cloudflare down

**Fix:**

1. **Check Internet Connection:**
```bash
ping cloudflare.com
```

2. **Check Cloudflare Status:**
```
Visit: https://www.cloudflarestatus.com/
```

3. **Check if Server Can Reach Cloudflare:**
```bash
curl https://challenges.cloudflare.com/cdn-cgi/trace
# Should return: fl=... h=... ip=... ts=... visit_scheme=...
```

4. **Temporary Bypass (Development Only):**
```env
# In .env
TURNSTILE_ENABLED=false
```

### Issue 6: Logs Tidak Tertulis

**Cause:** Permission issue

**Fix:**
```bash
# Create logs directory if not exists
mkdir -p storage/logs

# Set permissions
chmod -R 777 storage/logs/

# Check if writing works
touch storage/logs/test.log
# If error → permission problem

# Fix ownership
sudo chown -R www-data:www-data storage/
```

---

## 🔐 Security Best Practices

### 1. Protect Secret Key

```bash
# NEVER commit .env to git
echo ".env" >> .gitignore

# Proper permissions
chmod 600 .env

# Use environment variables in production
# Don't hardcode keys in code!
```

### 2. Whitelist Domains

Di Cloudflare Dashboard, tambahkan HANYA domain yang valid:

```
✅ Good:
- yourdomain.com
- www.yourdomain.com
- localhost (for dev)

❌ Bad:
- * (wildcard - NOT SECURE!)
- Leave empty (allows any domain)
```

### 3. Monitor Suspicious Activity

```bash
# Check suspicious IPs regularly
Visit: http://localhost/admin/turnstile/suspicious

# Export suspicious IPs
# Block in firewall if needed

# Example: Block IP with iptables
sudo iptables -A INPUT -s 192.168.1.100 -j DROP
```

### 4. Rotate Keys

Ganti keys secara berkala (recommended: setiap 6 bulan):

1. Generate new keys di Cloudflare
2. Update `.env` dengan keys baru
3. Test verification
4. Delete old keys dari Cloudflare

---

## 📊 Monitoring & Maintenance

### Daily Checks

```bash
# Check today's logs
tail -f storage/logs/turnstile_$(date +%Y-%m-%d).log

# Count verifications today
grep -c '"success":true' storage/logs/turnstile_$(date +%Y-%m-%d).log

# Count failures today
grep -c '"success":false' storage/logs/turnstile_$(date +%Y-%m-%d).log
```

### Weekly Tasks

1. Visit `/admin/turnstile`
2. Review statistics
3. Check suspicious IPs
4. Export CSV if needed
5. Block abusive IPs

### Monthly Tasks

1. Review logs
2. Optimize thresholds
3. Update whitelist if needed
4. Check Cloudflare quota usage
5. Plan key rotation

---

## 🆘 Getting Help

### Cloudflare Resources

- **Documentation:** https://developers.cloudflare.com/turnstile/
- **Dashboard:** https://dash.cloudflare.com/
- **Status Page:** https://www.cloudflarestatus.com/
- **Community:** https://community.cloudflare.com/

### Project Resources

- **Admin Dashboard:** `/admin/turnstile`
- **Test Page:** `/admin/turnstile/test`
- **Logs:** `storage/logs/turnstile_*.log`
- **Config:** `config/turnstile.php`

### Quick Test Command

```bash
# Test if everything configured correctly
php -r "
require 'vendor/autoload.php';
require 'config/config.php';

\$enabled = env('TURNSTILE_ENABLED');
\$siteKey = env('TURNSTILE_SITE_KEY');
\$secretKey = env('TURNSTILE_SECRET_KEY');

echo 'Turnstile Configuration Test' . PHP_EOL;
echo '----------------------------' . PHP_EOL;
echo 'Enabled: ' . (\$enabled ? 'Yes' : 'No') . PHP_EOL;
echo 'Site Key: ' . (empty(\$siteKey) ? 'MISSING!' : 'Set ✓') . PHP_EOL;
echo 'Secret Key: ' . (empty(\$secretKey) ? 'MISSING!' : 'Set ✓') . PHP_EOL;

if (\$enabled && !empty(\$siteKey) && !empty(\$secretKey)) {
    echo PHP_EOL . '✅ Configuration looks good!' . PHP_EOL;
} else {
    echo PHP_EOL . '❌ Configuration incomplete!' . PHP_EOL;
}
"
```

---

## ✅ Checklist Setup

Gunakan checklist ini untuk memastikan setup lengkap:

### Cloudflare Side
- [ ] Akun Cloudflare dibuat
- [ ] Site Turnstile dibuat
- [ ] Site Key di-copy
- [ ] Secret Key di-copy
- [ ] Domain ditambahkan (localhost + production)
- [ ] Widget mode dipilih (Managed recommended)

### Project Side
- [ ] File `.env` di-update
- [ ] `TURNSTILE_ENABLED=true`
- [ ] `TURNSTILE_SITE_KEY` diisi
- [ ] `TURNSTILE_SECRET_KEY` diisi
- [ ] File `.env` permission 600
- [ ] Directory `storage/logs/` writable (777)
- [ ] File `config/turnstile.php` exists

### Testing
- [ ] Widget muncul di halaman login
- [ ] Login berhasil setelah complete Turnstile
- [ ] Admin test page works (`/admin/turnstile/test`)
- [ ] Dashboard shows statistics (`/admin/turnstile`)
- [ ] Logs tertulis di `storage/logs/turnstile_*.log`

### Security
- [ ] `.env` tidak di-commit ke Git
- [ ] Secret key aman
- [ ] Domain whitelist dikonfigurasi
- [ ] IP whitelist (jika perlu)
- [ ] Monitoring aktif

---

## 🎉 Setup Complete!

Jika semua checklist ✅, Turnstile sudah aktif dan melindungi sistem Anda!

**Protected Forms:**
- ✅ Login
- ✅ Register
- ✅ Forgot Password
- ✅ Certificate Application
- ✅ Forum Posts
- ✅ Contact Form

**Security Status:** 🔒 MAXIMUM

---

**Last Updated:** November 17, 2024  
**Version:** 1.0  
**Status:** Production Ready
