<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\CertificateApplication;
use App\Models\Notification;
use App\Services\EmailService;

/**
 * Certificate Controller
 * Handles halal certificate applications (Help Desk System)
 */
class CertificateController extends Controller
{
    private $certModel;
    private $notificationModel;
    private $emailService;
    
    public function __construct()
    {
        parent::__construct();
        $this->certModel = $this->model('CertificateApplication');
        $this->notificationModel = $this->model('Notification');
        $this->emailService = new EmailService();
    }
    
    /**
     * Certificate Info Page (Public)
     */
    public function index()
    {
        $data = [
            'page_title' => 'Sertifikat Halal - Help Desk',
            'show_apply_button' => !$this->isLoggedIn()
        ];
        
        $this->view('frontend/certificate/index', $data);
    }
    
    /**
     * Apply for Certificate
     */
    public function apply()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processApplication();
        } else {
            $userId = $_SESSION['user_id'];
            $userModel = $this->model('User');
            $user = $userModel->findById($userId);
            
            $data = [
                'page_title' => 'Ajukan Sertifikat Halal',
                'user' => $user,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('frontend/certificate/apply', $data);
        }
    }
    
    /**
     * Process Certificate Application
     */
    private function processApplication()
    {
        // Verify Turnstile (anti-bot & spam prevention)
        if (!turnstile_verify()) {
            $this->setFlash('error', turnstile_error() ?? 'Security verification failed. Please try again.');
            $this->redirect('/certificate/apply');
        }
        
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/certificate/apply');
        }
        
        $userId = $_SESSION['user_id'];
        
        // Collect form data
        $applicationData = [
            'user_id' => $userId,
            'company_name' => $_POST['company_name'] ?? '',
            'company_address' => $_POST['company_address'] ?? '',
            'contact_person' => $_POST['contact_person'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'product_category' => $_POST['product_category'] ?? '',
            'product_name' => $_POST['product_name'] ?? '',
            'product_description' => $_POST['product_description'] ?? '',
            'certificate_type' => $_POST['certificate_type'] ?? 'standard',
            'priority' => 'medium',
            'status' => 'pending'
        ];
        
        // Validation
        $required = ['company_name', 'company_address', 'contact_person', 'email', 'phone', 
                     'product_category', 'product_name', 'product_description'];
        
        foreach ($required as $field) {
            if (empty($applicationData[$field])) {
                $this->setFlash('error', 'Semua field wajib diisi');
                flashOldInput();
                $this->redirect('/certificate/apply');
            }
        }
        
        // Handle document uploads
        $documents = [];
        
        if (isset($_FILES['documents']) && $_FILES['documents']['error'][0] !== 4) {
            foreach ($_FILES['documents']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['documents']['error'][$key] === 0) {
                    $file = [
                        'name' => $_FILES['documents']['name'][$key],
                        'type' => $_FILES['documents']['type'][$key],
                        'tmp_name' => $tmp_name,
                        'error' => $_FILES['documents']['error'][$key],
                        'size' => $_FILES['documents']['size'][$key]
                    ];
                    
                    $filename = upload_file($file, 'uploads/certificates/', ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']);
                    
                    if ($filename) {
                        $documents[] = [
                            'filename' => $filename,
                            'original_name' => $_FILES['documents']['name'][$key],
                            'uploaded_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }
        }
        
        $applicationData['documents'] = $documents;
        
        // Create application
        $certId = $this->certModel->createApplication($applicationData);
        
        if ($certId) {
            $cert = $this->certModel->findById($certId);
            $ticketNumber = $cert['ticket_number'];
            
            // Create notification for user
            $this->notificationModel->createNotification([
                'user_id' => $userId,
                'type' => 'certificate_submitted',
                'title' => 'Pengajuan Sertifikat Berhasil',
                'message' => "Pengajuan sertifikat Anda dengan nomor tiket {$ticketNumber} telah diterima dan sedang diproses.",
                'link' => '/certificate/track/' . $ticketNumber
            ]);
            
            // Send email notification
            $this->emailService->sendCertificateNotification(
                $applicationData['email'],
                $ticketNumber,
                'pending',
                'Pengajuan Anda telah diterima dan akan segera diproses.'
            );
            
            // Log activity
            log_audit($userId, 'create', 'certificate_applications', $certId);
            
            $this->setFlash('success', "Pengajuan berhasil! Nomor tiket Anda: <strong>{$ticketNumber}</strong>");
            $this->redirect('/certificate/track/' . $ticketNumber);
        } else {
            $this->setFlash('error', 'Gagal membuat pengajuan. Silakan coba lagi.');
            $this->redirect('/certificate/apply');
        }
    }
    
    /**
     * Track Certificate by Ticket Number
     */
    public function track($ticketNumber = null)
    {
        if (!$ticketNumber) {
            // Show track form
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ticket = $_POST['ticket_number'] ?? '';
                if ($ticket) {
                    $this->redirect('/certificate/track/' . $ticket);
                }
            }
            
            $data = [
                'page_title' => 'Lacak Sertifikat',
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('frontend/certificate/track_form', $data);
            return;
        }
        
        // Get certificate by ticket
        $certificate = $this->certModel->getByTicket($ticketNumber);
        
        if (!$certificate) {
            $this->setFlash('error', 'Nomor tiket tidak ditemukan');
            $this->redirect('/certificate/track');
        }
        
        // Check if user owns this certificate or is admin
        if ($this->isLoggedIn()) {
            $userId = $_SESSION['user_id'];
            if ($certificate['user_id'] != $userId && !$this->hasRole('superadmin') && !$this->hasRole('admin_sertifikat')) {
                $this->setFlash('error', 'Anda tidak memiliki akses ke sertifikat ini');
                $this->redirect('/certificate/track');
            }
        }
        
        // Get status history
        $historyModel = $this->model('CertificateApplication');
        $history = $historyModel->query(
            "SELECT csh.*, u.full_name as changed_by_name
             FROM certificate_status_history csh
             LEFT JOIN users u ON csh.changed_by = u.id
             WHERE csh.certificate_id = :cert_id
             ORDER BY csh.created_at DESC",
            [':cert_id' => $certificate['id']]
        )->fetchAll();
        
        $data = [
            'page_title' => 'Detail Pengajuan - ' . $ticketNumber,
            'certificate' => $certificate,
            'history' => $history
        ];
        
        $this->view('frontend/certificate/detail', $data);
    }
    
    /**
     * Download Certificate PDF
     */
    public function download($id)
    {
        $this->requireAuth();
        
        $certificate = $this->certModel->findById($id);
        
        if (!$certificate) {
            $this->setFlash('error', 'Sertifikat tidak ditemukan');
            $this->redirect('/dashboard/my-certificates');
        }
        
        // Check ownership
        $userId = $_SESSION['user_id'];
        if ($certificate['user_id'] != $userId && !$this->hasRole('superadmin') && !$this->hasRole('admin_sertifikat')) {
            $this->unauthorized();
        }
        
        // Check if certificate is completed
        if ($certificate['status'] !== 'completed' || empty($certificate['certificate_path'])) {
            $this->setFlash('error', 'Sertifikat belum tersedia untuk diunduh');
            $this->redirect('/certificate/track/' . $certificate['ticket_number']);
        }
        
        // Download file
        $filepath = PUBLIC_PATH . '/' . $certificate['certificate_path'];
        
        if (file_exists($filepath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Sertifikat_' . $certificate['ticket_number'] . '.pdf"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit();
        } else {
            $this->setFlash('error', 'File sertifikat tidak ditemukan');
            $this->redirect('/certificate/track/' . $certificate['ticket_number']);
        }
    }
    
    /**
     * My Certificates (User Dashboard)
     */
    public function myCertificates()
    {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $certificates = $this->certModel->getByUser($userId);
        
        $data = [
            'page_title' => 'Pengajuan Sertifikat Saya',
            'certificates' => $certificates
        ];
        
        $this->view('user/certificates/index', $data);
    }
    
    /**
     * Certificate Detail for User
     */
    public function detail($id)
    {
        $this->requireAuth();
        
        $certificate = $this->certModel->findById($id);
        
        if (!$certificate) {
            $this->setFlash('error', 'Sertifikat tidak ditemukan');
            $this->redirect('/dashboard/my-certificates');
        }
        
        // Check ownership
        $userId = $_SESSION['user_id'];
        if ($certificate['user_id'] != $userId) {
            $this->unauthorized();
        }
        
        $data = [
            'page_title' => 'Detail Pengajuan - ' . $certificate['ticket_number'],
            'certificate' => $certificate
        ];
        
        $this->view('user/certificates/detail', $data);
    }
    
    /**
     * FAQ Page
     */
    public function faq()
    {
        $data = [
            'page_title' => 'FAQ - Sertifikat Halal'
        ];
        
        $this->view('frontend/certificate/faq', $data);
    }
    
    /**
     * Requirements Page
     */
    public function requirements()
    {
        $data = [
            'page_title' => 'Persyaratan Sertifikat Halal'
        ];
        
        $this->view('frontend/certificate/requirements', $data);
    }
}
