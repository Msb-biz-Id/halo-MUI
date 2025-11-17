<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Setting;

/**
 * Admin Setting Controller
 * Manage system settings
 */
class SettingController extends Controller
{
    private $settingModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('system_settings');
        
        $this->settingModel = $this->model('Setting');
    }
    
    /**
     * Show all settings
     */
    public function index()
    {
        $group = $_GET['group'] ?? 'general';
        
        $settings = $this->settingModel->findBy(['group_name' => $group]);
        
        // Get all groups for tabs
        $groups = $this->settingModel->query(
            "SELECT DISTINCT group_name FROM settings ORDER BY group_name"
        )->fetchAll();
        
        $data = [
            'page_title' => 'Pengaturan Sistem',
            'settings' => $settings,
            'groups' => $groups,
            'current_group' => $group,
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('admin/settings/index', $data);
    }
    
    /**
     * Update settings
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/settings');
        }
        
        $settings = $_POST['settings'] ?? [];
        
        foreach ($settings as $key => $value) {
            $this->settingModel->updateValue($key, $value);
        }
        
        log_audit($_SESSION['user_id'], 'update', 'settings', 0);
        
        $this->setFlash('success', 'Pengaturan berhasil diperbarui');
        $this->redirect('/admin/settings');
    }
    
    /**
     * General settings
     */
    public function general()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processGeneralSettings();
        } else {
            $settings = [
                'site_name' => $this->settingModel->getValue('site_name'),
                'site_description' => $this->settingModel->getValue('site_description'),
                'site_keywords' => $this->settingModel->getValue('site_keywords'),
                'contact_email' => $this->settingModel->getValue('contact_email'),
                'contact_phone' => $this->settingModel->getValue('contact_phone'),
                'contact_address' => $this->settingModel->getValue('contact_address'),
                'maintenance_mode' => $this->settingModel->getValue('maintenance_mode'),
            ];
            
            $data = [
                'page_title' => 'Pengaturan Umum',
                'settings' => $settings,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/settings/general', $data);
        }
    }
    
    /**
     * Process general settings
     */
    private function processGeneralSettings()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/settings/general');
        }
        
        $updates = [
            'site_name' => $_POST['site_name'] ?? '',
            'site_description' => $_POST['site_description'] ?? '',
            'site_keywords' => $_POST['site_keywords'] ?? '',
            'contact_email' => $_POST['contact_email'] ?? '',
            'contact_phone' => $_POST['contact_phone'] ?? '',
            'contact_address' => $_POST['contact_address'] ?? '',
            'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0',
        ];
        
        foreach ($updates as $key => $value) {
            $this->settingModel->updateValue($key, $value);
        }
        
        log_audit($_SESSION['user_id'], 'update_general_settings', 'settings', 0);
        
        $this->setFlash('success', 'Pengaturan umum berhasil diperbarui');
        $this->redirect('/admin/settings/general');
    }
    
    /**
     * Email settings
     */
    public function email()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEmailSettings();
        } else {
            $settings = [
                'smtp_host' => $this->settingModel->getValue('smtp_host'),
                'smtp_port' => $this->settingModel->getValue('smtp_port'),
                'smtp_username' => $this->settingModel->getValue('smtp_username'),
                'smtp_encryption' => $this->settingModel->getValue('smtp_encryption'),
                'mail_from_address' => $this->settingModel->getValue('mail_from_address'),
                'mail_from_name' => $this->settingModel->getValue('mail_from_name'),
            ];
            
            $data = [
                'page_title' => 'Pengaturan Email',
                'settings' => $settings,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/settings/email', $data);
        }
    }
    
    /**
     * Process email settings
     */
    private function processEmailSettings()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/settings/email');
        }
        
        $updates = [
            'smtp_host' => $_POST['smtp_host'] ?? '',
            'smtp_port' => $_POST['smtp_port'] ?? '587',
            'smtp_username' => $_POST['smtp_username'] ?? '',
            'smtp_encryption' => $_POST['smtp_encryption'] ?? 'tls',
            'mail_from_address' => $_POST['mail_from_address'] ?? '',
            'mail_from_name' => $_POST['mail_from_name'] ?? '',
        ];
        
        // Update password only if provided
        if (!empty($_POST['smtp_password'])) {
            $updates['smtp_password'] = encrypt($_POST['smtp_password']);
        }
        
        foreach ($updates as $key => $value) {
            $this->settingModel->updateValue($key, $value);
        }
        
        log_audit($_SESSION['user_id'], 'update_email_settings', 'settings', 0);
        
        $this->setFlash('success', 'Pengaturan email berhasil diperbarui');
        $this->redirect('/admin/settings/email');
    }
    
    /**
     * SEO settings
     */
    public function seo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processSeoSettings();
        } else {
            $settings = [
                'seo_meta_title' => $this->settingModel->getValue('seo_meta_title'),
                'seo_meta_description' => $this->settingModel->getValue('seo_meta_description'),
                'seo_meta_keywords' => $this->settingModel->getValue('seo_meta_keywords'),
                'seo_og_image' => $this->settingModel->getValue('seo_og_image'),
                'google_analytics_id' => $this->settingModel->getValue('google_analytics_id'),
            ];
            
            $data = [
                'page_title' => 'Pengaturan SEO',
                'settings' => $settings,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/settings/seo', $data);
        }
    }
    
    /**
     * Process SEO settings
     */
    private function processSeoSettings()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/settings/seo');
        }
        
        $updates = [
            'seo_meta_title' => $_POST['seo_meta_title'] ?? '',
            'seo_meta_description' => $_POST['seo_meta_description'] ?? '',
            'seo_meta_keywords' => $_POST['seo_meta_keywords'] ?? '',
            'google_analytics_id' => $_POST['google_analytics_id'] ?? '',
        ];
        
        // Handle OG image upload
        if (isset($_FILES['seo_og_image']) && $_FILES['seo_og_image']['error'] === UPLOAD_ERR_OK) {
            $ogImagePath = upload_file($_FILES['seo_og_image'], 'settings/');
            $updates['seo_og_image'] = $ogImagePath;
        }
        
        foreach ($updates as $key => $value) {
            $this->settingModel->updateValue($key, $value);
        }
        
        log_audit($_SESSION['user_id'], 'update_seo_settings', 'settings', 0);
        
        $this->setFlash('success', 'Pengaturan SEO berhasil diperbarui');
        $this->redirect('/admin/settings/seo');
    }
    
    /**
     * Cache management
     */
    public function cache()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'clear_all') {
                $this->clearAllCache();
            }
            
            $this->redirect('/admin/settings/cache');
        } else {
            $data = [
                'page_title' => 'Manajemen Cache',
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/settings/cache', $data);
        }
    }
    
    /**
     * Clear all cache
     */
    private function clearAllCache()
    {
        $cacheDir = STORAGE_PATH . 'cache/';
        
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        log_audit($_SESSION['user_id'], 'clear_cache', 'settings', 0);
        
        $this->setFlash('success', 'Cache berhasil dibersihkan');
    }
}
