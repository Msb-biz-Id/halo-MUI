<?php

namespace App\Services;

use OTPHP\TOTP;

/**
 * MFA Service
 * Handles Multi-Factor Authentication using TOTP
 */
class MFAService
{
    /**
     * Generate MFA secret
     * 
     * @return string
     */
    public function generateSecret()
    {
        $totp = TOTP::create();
        return $totp->getSecret();
    }
    
    /**
     * Get QR Code URL
     * 
     * @param string $secret
     * @param string $email
     * @param string $issuer
     * @return string
     */
    public function getQRCodeUrl($secret, $email, $issuer = null)
    {
        $issuer = $issuer ?? MFA_ISSUER;
        
        $totp = TOTP::create($secret);
        $totp->setLabel($email);
        $totp->setIssuer($issuer);
        
        return $totp->getProvisioningUri();
    }
    
    /**
     * Verify TOTP code
     * 
     * @param string $secret
     * @param string $code
     * @return bool
     */
    public function verifyCode($secret, $code)
    {
        try {
            $totp = TOTP::create($secret);
            return $totp->verify($code, null, 2); // Allow 2 time periods drift
        } catch (\Exception $e) {
            error_log('MFA verification error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get current TOTP code (for testing)
     * 
     * @param string $secret
     * @return string
     */
    public function getCurrentCode($secret)
    {
        $totp = TOTP::create($secret);
        return $totp->now();
    }
    
    /**
     * Generate backup codes
     * 
     * @param int $count
     * @return array
     */
    public function generateBackupCodes($count = 10)
    {
        $codes = [];
        
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4)));
        }
        
        return $codes;
    }
}
