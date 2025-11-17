<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\CertificateApplication;
use App\Models\User;
use App\Models\Notification;
use App\Services\EmailService;
use App\Services\ExcelService;

/**
 * Admin Certificate Controller
 * Manage halal certificate applications - FITUR UTAMA!
 */
class CertificateController extends Controller
{
    private $certModel;
    private $userModel;
    private $notificationModel;
    private $emailService;
    private $excelService;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('certificate_management');
        
        $this->certModel = $this->model('CertificateApplication');
        $this->userModel = $this->model('User');
        $this->notificationModel = $this->model('Notification');
        $this->emailService = new EmailService();
        $this->excelService = new ExcelService();
    }
    
    /**
     * List all certificate applications
     */
    public function index()
    {
        $status = $_GET['status'] ?? 'all';
        $priority = $_GET['priority'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        // Build query
        $sql = "SELECT ca.*, u.full_name as applicant_name, u.email as applicant_email,
                admin.full_name as assigned_admin_name
                FROM certificate_applications ca
                LEFT JOIN users u ON ca.user_id = u.id
                LEFT JOIN users admin ON ca.assigned_to = admin.id
                WHERE 1=1";
        
        $params = [];
        
        if ($status !== 'all') {
            $sql .= " AND ca.status = :status";
            $params[':status'] = $status;
        }
        
        if ($priority !== 'all') {
            $sql .= " AND ca.priority = :priority";
            $params[':priority'] = $priority;
        }
        
        if (!empty($search)) {
            $sql .= " AND (ca.ticket_number LIKE :search 
                      OR ca.company_name LIKE :search 
                      OR ca.product_name LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        
        $sql .= " ORDER BY ca.priority DESC, ca.submitted_at DESC";
        
        $this->certModel->query($sql, $params);
        $certificates = $this->certModel->db->fetchAll();
        
        // Get statistics
        $stats = $this->certModel->getStatistics();
        
        // Get admins for assignment
        $admins = $this->userModel->getUsersByRole(3); // role_id 3 = admin_sertifikat
        
        $data = [
            'page_title' => 'Manajemen Sertifikat Halal',
            'certificates' => $certificates,
            'stats' => $stats,
            'admins' => $admins,
            'current_status' => $status,
            'current_priority' => $priority,
            'search' => $search
        ];
        
        $this->view('admin/certificates/index', $data);
    }
    
    /**
     * View certificate detail
     */
    public function view($id)
    {
        $certificate = $this->certModel->query(
            "SELECT ca.*, u.full_name as applicant_name, u.email as applicant_email, 
                    u.phone as applicant_phone, u.company_name as user_company,
                    admin.full_name as assigned_admin_name,
                    reviewer.full_name as reviewer_name
             FROM certificate_applications ca
             LEFT JOIN users u ON ca.user_id = u.id
             LEFT JOIN users admin ON ca.assigned_to = admin.id
             LEFT JOIN users reviewer ON ca.reviewer_id = reviewer.id
             WHERE ca.id = :id",
            [':id' => $id]
        )->fetch();
        
        if (!$certificate) {
            $this->setFlash('error', 'Sertifikat tidak ditemukan');
            $this->redirect('/admin/certificates');
        }
        
        // Decode documents
        if (!empty($certificate['documents'])) {
            $certificate['documents_array'] = json_decode($certificate['documents'], true);
        }
        
        // Get status history
        $history = $this->certModel->query(
            "SELECT csh.*, u.full_name as changed_by_name
             FROM certificate_status_history csh
             LEFT JOIN users u ON csh.changed_by = u.id
             WHERE csh.certificate_id = :cert_id
             ORDER BY csh.created_at DESC",
            [':cert_id' => $id]
        )->fetchAll();
        
        // Get admins for assignment
        $admins = $this->userModel->getUsersByRole(3);
        
        $data = [
            'page_title' => 'Detail Sertifikat - ' . $certificate['ticket_number'],
            'certificate' => $certificate,
            'history' => $history,
            'admins' => $admins,
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('admin/certificates/view', $data);
    }
    
    /**
     * Assign certificate to admin
     */
    public function assign($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        $adminId = $_POST['admin_id'] ?? 0;
        
        if (!$adminId) {
            $this->json(['success' => false, 'message' => 'Admin ID required'], 400);
        }
        
        $certificate = $this->certModel->findById($id);
        
        if (!$certificate) {
            $this->json(['success' => false, 'message' => 'Certificate not found'], 404);
        }
        
        // Assign to admin
        if ($this->certModel->assignToAdmin($id, $adminId)) {
            // Update status if still pending
            if ($certificate['status'] === 'pending') {
                $this->certModel->updateStatus($id, 'in_review', $_SESSION['user_id']);
                
                // Add to history
                $this->addStatusHistory($id, 'in_review', 'Assigned to admin for review');
            }
            
            // Notify admin
            $admin = $this->userModel->findById($adminId);
            $this->notificationModel->createNotification([
                'user_id' => $adminId,
                'type' => 'certificate_assigned',
                'title' => 'Sertifikat Baru Ditugaskan',
                'message' => "Sertifikat {$certificate['ticket_number']} telah ditugaskan kepada Anda.",
                'link' => '/admin/certificates/view/' . $id
            ]);
            
            // Notify applicant
            $this->notificationModel->createNotification([
                'user_id' => $certificate['user_id'],
                'type' => 'certificate_status',
                'title' => 'Status Sertifikat Diperbarui',
                'message' => "Pengajuan Anda sedang ditinjau oleh tim kami.",
                'link' => '/certificate/track/' . $certificate['ticket_number']
            ]);
            
            // Send email
            $this->emailService->sendCertificateNotification(
                $certificate['email'],
                $certificate['ticket_number'],
                'in_review',
                'Pengajuan Anda sedang ditinjau oleh tim kami.'
            );
            
            log_audit($_SESSION['user_id'], 'assign', 'certificate_applications', $id);
            
            $this->json(['success' => true, 'message' => 'Sertifikat berhasil ditugaskan']);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal menugaskan sertifikat'], 500);
        }
    }
    
    /**
     * Approve certificate
     */
    public function approve($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/certificates/view/' . $id);
        }
        
        $notes = $_POST['notes'] ?? '';
        $certificate = $this->certModel->findById($id);
        
        if (!$certificate) {
            $this->setFlash('error', 'Sertifikat tidak ditemukan');
            $this->redirect('/admin/certificates');
        }
        
        // Update status
        if ($this->certModel->updateStatus($id, 'approved', $_SESSION['user_id'], $notes)) {
            // Add to history
            $this->addStatusHistory($id, 'approved', $notes);
            
            // Notify applicant
            $this->notificationModel->createNotification([
                'user_id' => $certificate['user_id'],
                'type' => 'certificate_approved',
                'title' => 'Sertifikat Disetujui',
                'message' => "Pengajuan sertifikat {$certificate['ticket_number']} telah disetujui!",
                'link' => '/certificate/track/' . $certificate['ticket_number']
            ]);
            
            // Send email
            $this->emailService->sendCertificateNotification(
                $certificate['email'],
                $certificate['ticket_number'],
                'approved',
                'Selamat! Pengajuan Anda telah disetujui. Sertifikat sedang diproses.'
            );
            
            log_audit($_SESSION['user_id'], 'approve', 'certificate_applications', $id);
            
            $this->setFlash('success', 'Sertifikat berhasil disetujui');
        } else {
            $this->setFlash('error', 'Gagal menyetujui sertifikat');
        }
        
        $this->redirect('/admin/certificates/view/' . $id);
    }
    
    /**
     * Reject certificate
     */
    public function reject($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/certificates/view/' . $id);
        }
        
        $reason = $_POST['reason'] ?? '';
        
        if (empty($reason)) {
            $this->setFlash('error', 'Alasan penolakan wajib diisi');
            $this->redirect('/admin/certificates/view/' . $id);
        }
        
        $certificate = $this->certModel->findById($id);
        
        if (!$certificate) {
            $this->setFlash('error', 'Sertifikat tidak ditemukan');
            $this->redirect('/admin/certificates');
        }
        
        // Update status
        $updateData = [
            'status' => 'rejected',
            'reviewer_id' => $_SESSION['user_id'],
            'reviewed_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason
        ];
        
        if ($this->certModel->update($id, $updateData)) {
            // Add to history
            $this->addStatusHistory($id, 'rejected', $reason);
            
            // Notify applicant
            $this->notificationModel->createNotification([
                'user_id' => $certificate['user_id'],
                'type' => 'certificate_rejected',
                'title' => 'Sertifikat Ditolak',
                'message' => "Pengajuan sertifikat {$certificate['ticket_number']} ditolak. Silakan lihat detail.",
                'link' => '/certificate/track/' . $certificate['ticket_number']
            ]);
            
            // Send email
            $this->emailService->sendCertificateNotification(
                $certificate['email'],
                $certificate['ticket_number'],
                'rejected',
                "Pengajuan ditolak. Alasan: {$reason}"
            );
            
            log_audit($_SESSION['user_id'], 'reject', 'certificate_applications', $id);
            
            $this->setFlash('success', 'Sertifikat berhasil ditolak');
        } else {
            $this->setFlash('error', 'Gagal menolak sertifikat');
        }
        
        $this->redirect('/admin/certificates/view/' . $id);
    }
    
    /**
     * Generate certificate PDF
     */
    public function generate($id)
    {
        $certificate = $this->certModel->findById($id);
        
        if (!$certificate) {
            $this->setFlash('error', 'Sertifikat tidak ditemukan');
            $this->redirect('/admin/certificates');
        }
        
        // Check if approved
        if ($certificate['status'] !== 'approved') {
            $this->setFlash('error', 'Hanya sertifikat yang disetujui yang dapat digenerate');
            $this->redirect('/admin/certificates/view/' . $id);
        }
        
        // TODO: Implement PDF generation with PDFService
        // For now, just update status to completed
        
        $updateData = [
            'status' => 'completed',
            'certificate_issued_at' => date('Y-m-d H:i:s'),
            'certificate_path' => 'uploads/certificates/CERT-' . $certificate['ticket_number'] . '.pdf' // Placeholder
        ];
        
        if ($this->certModel->update($id, $updateData)) {
            // Add to history
            $this->addStatusHistory($id, 'completed', 'Certificate generated and issued');
            
            // Notify applicant
            $this->notificationModel->createNotification([
                'user_id' => $certificate['user_id'],
                'type' => 'certificate_completed',
                'title' => 'Sertifikat Siap Diunduh',
                'message' => "Sertifikat {$certificate['ticket_number']} sudah siap! Silakan download.",
                'link' => '/certificate/track/' . $certificate['ticket_number']
            ]);
            
            // Send email
            $this->emailService->sendCertificateNotification(
                $certificate['email'],
                $certificate['ticket_number'],
                'completed',
                'Sertifikat Anda sudah siap! Silakan login untuk download.'
            );
            
            log_audit($_SESSION['user_id'], 'generate_certificate', 'certificate_applications', $id);
            
            $this->setFlash('success', 'Sertifikat berhasil digenerate');
        } else {
            $this->setFlash('error', 'Gagal generate sertifikat');
        }
        
        $this->redirect('/admin/certificates/view/' . $id);
    }
    
    /**
     * Export to Excel
     */
    public function export()
    {
        $status = $_GET['status'] ?? 'all';
        
        // Get data
        $sql = "SELECT ca.*, u.full_name as applicant_name
                FROM certificate_applications ca
                LEFT JOIN users u ON ca.user_id = u.id";
        
        if ($status !== 'all') {
            $sql .= " WHERE ca.status = :status";
            $certificates = $this->certModel->query($sql, [':status' => $status])->fetchAll();
        } else {
            $certificates = $this->certModel->query($sql)->fetchAll();
        }
        
        // Generate Excel
        $filename = 'certificates_export_' . date('Ymd_His') . '.xlsx';
        $filepath = $this->excelService->exportCertificateApplications($certificates, $filename);
        
        // Download file
        if (file_exists($filepath)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            
            // Delete file after download
            unlink($filepath);
            
            log_audit($_SESSION['user_id'], 'export', 'certificate_applications', 0);
            exit();
        } else {
            $this->setFlash('error', 'Gagal generate export file');
            $this->redirect('/admin/certificates');
        }
    }
    
    /**
     * Add status history
     */
    private function addStatusHistory($certId, $status, $notes = null)
    {
        $sql = "INSERT INTO certificate_status_history 
                (certificate_id, status, changed_by, notes, created_at) 
                VALUES (:cert_id, :status, :changed_by, :notes, NOW())";
        
        $this->certModel->query($sql, [
            ':cert_id' => $certId,
            ':status' => $status,
            ':changed_by' => $_SESSION['user_id'],
            ':notes' => $notes
        ])->execute();
    }
    
    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        $stats = $this->certModel->getStatistics();
        
        // Get recent certificates
        $recentCerts = $this->certModel->query(
            "SELECT ca.*, u.full_name as applicant_name
             FROM certificate_applications ca
             LEFT JOIN users u ON ca.user_id = u.id
             ORDER BY ca.submitted_at DESC
             LIMIT 10"
        )->fetchAll();
        
        $data = [
            'page_title' => 'Certificate Dashboard',
            'stats' => $stats,
            'recent_certs' => $recentCerts
        ];
        
        $this->view('admin/certificates/dashboard', $data);
    }
}
