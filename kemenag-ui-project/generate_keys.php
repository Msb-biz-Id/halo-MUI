<?php
/**
 * Security Key Generator
 * Generate APP_KEY, CSRF_TOKEN_NAME, and ENCRYPTION_KEY
 * 
 * Usage: php generate_keys.php
 */

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔐 SECURITY KEY GENERATOR\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

/**
 * Generate random key
 */
function generateKey($length = 32)
{
    return bin2hex(random_bytes($length));
}

/**
 * Generate base64 key
 */
function generateBase64Key($length = 32)
{
    return 'base64:' . base64_encode(random_bytes($length));
}

/**
 * Update .env file
 */
function updateEnvFile($updates)
{
    $envFile = __DIR__ . '/.env';
    $envExampleFile = __DIR__ . '/.env.example';
    
    // Determine which file to use
    $targetFile = file_exists($envFile) ? $envFile : $envExampleFile;
    
    if (!file_exists($targetFile)) {
        echo "❌ Error: .env file not found!\n";
        echo "   Create .env file first by copying .env.example\n\n";
        return false;
    }
    
    $content = file_get_contents($targetFile);
    
    foreach ($updates as $key => $value) {
        // Check if key exists
        if (preg_match("/^{$key}=.*/m", $content)) {
            // Update existing key
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            // Add new key
            $content .= "\n{$key}={$value}";
        }
    }
    
    file_put_contents($targetFile, $content);
    return true;
}

// Generate keys
$appKey = generateBase64Key(32);
$csrfTokenName = 'csrf_token_' . substr(md5(time()), 0, 8);
$encryptionKey = generateKey(32);

echo "✅ Keys generated successfully!\n\n";

echo "APP_KEY:\n";
echo "  {$appKey}\n\n";

echo "CSRF_TOKEN_NAME:\n";
echo "  {$csrfTokenName}\n\n";

echo "ENCRYPTION_KEY:\n";
echo "  {$encryptionKey}\n\n";

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📝 ADD TO YOUR .env FILE:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$envContent = <<<ENV
# Security Keys (Generated: {DATE})
APP_KEY={$appKey}
CSRF_TOKEN_NAME={$csrfTokenName}
ENCRYPTION_KEY={$encryptionKey}
ENV;

$envContent = str_replace('{DATE}', date('Y-m-d H:i:s'), $envContent);
echo $envContent . "\n\n";

// Ask to auto-update
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Auto-update .env file? (y/n): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtolower($line) === 'y') {
    $updates = [
        'APP_KEY' => $appKey,
        'CSRF_TOKEN_NAME' => $csrfTokenName,
        'ENCRYPTION_KEY' => $encryptionKey
    ];
    
    if (updateEnvFile($updates)) {
        echo "\n✅ .env file updated successfully!\n";
    } else {
        echo "\n❌ Failed to update .env file\n";
    }
} else {
    echo "\n📋 Copy the keys above and manually add to your .env file\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔒 SECURITY NOTES:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "1. APP_KEY: Used for session encryption and general security\n";
echo "2. CSRF_TOKEN_NAME: Token name for CSRF protection\n";
echo "3. ENCRYPTION_KEY: Used for encrypting sensitive data\n\n";

echo "⚠️  IMPORTANT:\n";
echo "   - Keep these keys SECRET\n";
echo "   - Never commit .env to git\n";
echo "   - Regenerate keys if compromised\n";
echo "   - Backup these keys securely\n\n";

echo "✅ Done!\n\n";
