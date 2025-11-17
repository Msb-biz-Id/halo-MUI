# 🔐 Security Keys Setup Guide

## Overview

Aplikasi ini menggunakan 3 security keys untuk enkripsi dan keamanan:

1. **APP_KEY** - Untuk enkripsi session dan general security
2. **CSRF_TOKEN_NAME** - Nama token untuk CSRF protection
3. **ENCRYPTION_KEY** - Untuk enkripsi data sensitif

---

## 🚀 Quick Setup (Automatic)

### Step 1: Generate Keys

```bash
php generate_keys.php
```

### Step 2: Follow Instructions

Script akan:
1. Generate 3 security keys secara otomatis
2. Menampilkan keys yang di-generate
3. Menanyakan apakah ingin auto-update `.env` file

**Output Example:**
```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔐 SECURITY KEY GENERATOR
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✅ Keys generated successfully!

APP_KEY:
  base64:Tk4vN2R3ZGxhZGhsa2Zkc2pmZGtsamZrbGRzamZrbA==

CSRF_TOKEN_NAME:
  csrf_token_a3f7b2e1

ENCRYPTION_KEY:
  7e4a9f2b8c1d3e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📝 ADD TO YOUR .env FILE:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

# Security Keys (Generated: 2024-11-17 12:34:56)
APP_KEY=base64:Tk4vN2R3ZGxhZGhsa2Zkc2pmZGtsamZrbGRzamZrbA==
CSRF_TOKEN_NAME=csrf_token_a3f7b2e1
ENCRYPTION_KEY=7e4a9f2b8c1d3e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Auto-update .env file? (y/n): 
```

---

## 📝 Manual Setup

Jika tidak ingin menggunakan automatic generator:

### 1. APP_KEY

Generate random 32-byte string dan encode ke base64:

**PHP:**
```php
php -r "echo 'base64:' . base64_encode(random_bytes(32));"
```

**OpenSSL:**
```bash
echo "base64:$(openssl rand -base64 32)"
```

**Example Output:**
```
base64:Tk4vN2R3ZGxhZGhsa2Zkc2pmZGtsamZrbGRzamZrbA==
```

### 2. CSRF_TOKEN_NAME

Generate unique token name:

```php
php -r "echo 'csrf_token_' . substr(md5(time()), 0, 8);"
```

**Example Output:**
```
csrf_token_a3f7b2e1
```

### 3. ENCRYPTION_KEY

Generate 64-character hex string:

```php
php -r "echo bin2hex(random_bytes(32));"
```

**Example Output:**
```
7e4a9f2b8c1d3e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f
```

---

## 💻 Add to .env File

Buka `.env` file dan tambahkan:

```env
# Security Keys
APP_KEY=base64:Tk4vN2R3ZGxhZGhsa2Zkc2pmZGtsamZrbGRzamZrbA==
CSRF_TOKEN_NAME=csrf_token_a3f7b2e1
ENCRYPTION_KEY=7e4a9f2b8c1d3e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f
```

---

## 🔒 Security Best Practices

### 1. Keep Keys Secret

- ❌ **NEVER** commit `.env` to Git
- ❌ **NEVER** share keys publicly
- ❌ **NEVER** hardcode keys in source code
- ✅ **ALWAYS** use `.env` file
- ✅ **ALWAYS** add `.env` to `.gitignore`

### 2. Rotate Keys Regularly

Regenerate keys jika:
- Keys potentially compromised
- Developer leaves team
- Security audit recommends
- Every 6-12 months (best practice)

### 3. Backup Keys Securely

```bash
# Backup .env to secure location
cp .env .env.backup
chmod 600 .env.backup

# Store in password manager or encrypted storage
```

### 4. Environment-Specific Keys

Use different keys untuk setiap environment:

```
Production:   APP_KEY=base64:xxxxx...
Staging:      APP_KEY=base64:yyyyy...
Development:  APP_KEY=base64:zzzzz...
```

---

## 📖 How Keys Are Used

### APP_KEY

```php
// Session encryption
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);

// Data encryption
$encrypted = encrypt($data, APP_KEY);
```

### CSRF_TOKEN_NAME

```php
// Generate CSRF token
$token = bin2hex(random_bytes(32));
$_SESSION[CSRF_TOKEN_NAME] = $token;

// Verify CSRF token
if ($_POST[CSRF_TOKEN_NAME] !== $_SESSION[CSRF_TOKEN_NAME]) {
    die('CSRF token mismatch');
}
```

### ENCRYPTION_KEY

```php
// Encrypt sensitive data
function encryptData($data) {
    $key = hex2bin(ENCRYPTION_KEY);
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

// Decrypt sensitive data
function decryptData($encrypted) {
    $key = hex2bin(ENCRYPTION_KEY);
    $data = base64_decode($encrypted);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}
```

---

## 🔄 Key Rotation Guide

### When to Rotate

1. **Immediately** if compromised
2. **Regularly** every 6-12 months
3. **After** security incident
4. **When** team member leaves

### How to Rotate

#### Step 1: Generate New Keys

```bash
php generate_keys.php
```

#### Step 2: Backup Current Keys

```bash
cp .env .env.old
```

#### Step 3: Update .env

Replace old keys dengan new keys di `.env`

#### Step 4: Clear Sessions

```bash
# Clear all sessions
rm -rf storage/sessions/*

# Or via PHP
session_start();
session_destroy();
```

#### Step 5: Test Application

```bash
# Test login
# Test CSRF forms
# Test encrypted data
```

#### Step 6: Notify Users

- Force re-login for all users
- Send notification if needed

---

## ⚠️ Troubleshooting

### Error: "Invalid APP_KEY"

**Cause:** APP_KEY tidak valid atau missing

**Fix:**
```bash
php generate_keys.php
# Update APP_KEY in .env
```

### Error: "CSRF token mismatch"

**Cause:** CSRF_TOKEN_NAME berubah atau session expired

**Fix:**
```bash
# Clear sessions
rm -rf storage/sessions/*

# Atau refresh browser
```

### Error: "Decryption failed"

**Cause:** ENCRYPTION_KEY berubah, data encrypted dengan old key

**Fix:**
```php
// Re-encrypt data dengan new key
// Or keep old key untuk decrypt, then re-encrypt with new key
```

---

## 📚 Additional Resources

### Helpers Functions

Create `app/helpers/security.php`:

```php
<?php

/**
 * Get APP_KEY
 */
function getAppKey(): string {
    $key = env('APP_KEY');
    if (strpos($key, 'base64:') === 0) {
        return base64_decode(substr($key, 7));
    }
    return $key;
}

/**
 * Encrypt data
 */
function encrypt(string $data): string {
    $key = hex2bin(env('ENCRYPTION_KEY'));
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

/**
 * Decrypt data
 */
function decrypt(string $encrypted): string {
    $key = hex2bin(env('ENCRYPTION_KEY'));
    $data = base64_decode($encrypted);
    $iv = substr($data, 0, 16);
    $ciphertext = substr($data, 16);
    return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, 0, $iv);
}

/**
 * Generate CSRF token
 */
function csrfToken(): string {
    $tokenName = env('CSRF_TOKEN_NAME', 'csrf_token');
    if (!isset($_SESSION[$tokenName])) {
        $_SESSION[$tokenName] = bin2hex(random_bytes(32));
    }
    return $_SESSION[$tokenName];
}

/**
 * Verify CSRF token
 */
function verifyCsrf(string $token): bool {
    $tokenName = env('CSRF_TOKEN_NAME', 'csrf_token');
    return hash_equals($_SESSION[$tokenName] ?? '', $token);
}
```

### HTML CSRF Token

```html
<form method="POST">
    <input type="hidden" name="<?= env('CSRF_TOKEN_NAME') ?>" value="<?= csrfToken() ?>">
    <!-- form fields -->
</form>
```

---

## ✅ Checklist

Before going to production:

- [ ] Generate all 3 security keys
- [ ] Add keys to `.env` file
- [ ] Test login functionality
- [ ] Test CSRF protection
- [ ] Test data encryption
- [ ] Add `.env` to `.gitignore`
- [ ] Backup `.env` file securely
- [ ] Document key rotation schedule
- [ ] Setup monitoring for security events

---

**🔐 Security is critical! Always follow best practices!**

Generated: November 17, 2024
