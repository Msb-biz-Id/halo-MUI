<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Email Service
 * Handles email sending using PHPMailer
 */
class EmailService
{
    private $mailer;
    
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }
    
    /**
     * Configure PHPMailer
     */
    private function configure()
    {
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = MAIL_HOST;
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = MAIL_USERNAME;
            $this->mailer->Password = MAIL_PASSWORD;
            $this->mailer->SMTPSecure = MAIL_ENCRYPTION;
            $this->mailer->Port = MAIL_PORT;
            
            // Default sender
            $this->mailer->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            
            // Encoding
            $this->mailer->CharSet = 'UTF-8';
        } catch (Exception $e) {
            error_log('Email configuration error: ' . $e->getMessage());
        }
    }
    
    /**
     * Send email
     * 
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param bool $isHTML
     * @return bool
     */
    public function send($to, $subject, $body, $isHTML = true)
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->isHTML($isHTML);
            $this->mailer->Body = $body;
            
            if (!$isHTML) {
                $this->mailer->AltBody = strip_tags($body);
            }
            
            $result = $this->mailer->send();
            $this->mailer->clearAddresses();
            
            return $result;
        } catch (Exception $e) {
            error_log('Email sending error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send verification email
     * 
     * @param string $to
     * @param string $token
     * @return bool
     */
    public function sendVerificationEmail($to, $token)
    {
        $verifyUrl = APP_URL . '/auth/verify-email/' . $token;
        
        $subject = 'Verifikasi Email - ' . APP_NAME;
        $body = $this->getEmailTemplate('verification', [
            'verify_url' => $verifyUrl
        ]);
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Send password reset email
     * 
     * @param string $to
     * @param string $token
     * @return bool
     */
    public function sendPasswordResetEmail($to, $token)
    {
        $resetUrl = APP_URL . '/auth/reset-password/' . $token;
        
        $subject = 'Reset Password - ' . APP_NAME;
        $body = $this->getEmailTemplate('password_reset', [
            'reset_url' => $resetUrl
        ]);
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Send certificate status notification
     * 
     * @param string $to
     * @param string $ticketNumber
     * @param string $status
     * @param string $notes
     * @return bool
     */
    public function sendCertificateNotification($to, $ticketNumber, $status, $notes = '')
    {
        $subject = "Status Sertifikat #{$ticketNumber} - " . APP_NAME;
        $body = $this->getEmailTemplate('certificate_status', [
            'ticket_number' => $ticketNumber,
            'status' => $status,
            'notes' => $notes,
            'track_url' => APP_URL . '/certificate/track/' . $ticketNumber
        ]);
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Get email template
     * 
     * @param string $template
     * @param array $data
     * @return string
     */
    private function getEmailTemplate($template, $data = [])
    {
        $templateFile = VIEW_PATH . '/emails/' . $template . '.php';
        
        if (file_exists($templateFile)) {
            extract($data);
            ob_start();
            include $templateFile;
            return ob_get_clean();
        }
        
        return $this->getDefaultTemplate($template, $data);
    }
    
    /**
     * Get default email template
     * 
     * @param string $type
     * @param array $data
     * @return string
     */
    private function getDefaultTemplate($type, $data)
    {
        $html = '<html><body style="font-family: Arial, sans-serif; padding: 20px;">';
        $html .= '<div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border: 1px solid #ddd;">';
        $html .= '<h2 style="color: #333;">' . APP_NAME . '</h2>';
        
        switch ($type) {
            case 'verification':
                $html .= '<p>Terima kasih telah mendaftar. Silakan klik tombol berikut untuk verifikasi email Anda:</p>';
                $html .= '<p><a href="' . $data['verify_url'] . '" style="background: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Verifikasi Email</a></p>';
                break;
                
            case 'password_reset':
                $html .= '<p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>';
                $html .= '<p><a href="' . $data['reset_url'] . '" style="background: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Reset Password</a></p>';
                $html .= '<p>Jika Anda tidak melakukan permintaan ini, abaikan email ini.</p>';
                break;
                
            case 'certificate_status':
                $html .= '<p>Status pengajuan sertifikat Anda telah diperbarui:</p>';
                $html .= '<p><strong>Nomor Tiket:</strong> ' . $data['ticket_number'] . '</p>';
                $html .= '<p><strong>Status:</strong> ' . $data['status'] . '</p>';
                if (!empty($data['notes'])) {
                    $html .= '<p><strong>Catatan:</strong> ' . $data['notes'] . '</p>';
                }
                $html .= '<p><a href="' . $data['track_url'] . '" style="background: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Lihat Detail</a></p>';
                break;
        }
        
        $html .= '<hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">';
        $html .= '<p style="color: #999; font-size: 12px;">Email ini dikirim otomatis, mohon tidak membalas email ini.</p>';
        $html .= '</div></body></html>';
        
        return $html;
    }
}
