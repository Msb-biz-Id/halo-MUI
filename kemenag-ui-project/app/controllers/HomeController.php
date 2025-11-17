<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Setting;
use App\Models\QuestionAnswer;
use App\Models\Fatwa;
use App\Models\Material;

/**
 * Home Controller
 * Handles public homepage and static pages
 */
class HomeController extends Controller
{
    private $settingModel;
    private $qaModel;
    private $fatwaModel;
    private $materialModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->settingModel = $this->model('Setting');
        $this->qaModel = $this->model('QuestionAnswer');
        $this->fatwaModel = $this->model('Fatwa');
        $this->materialModel = $this->model('Material');
    }
    
    /**
     * Homepage
     */
    public function index()
    {
        // Get latest content
        $latestQA = $this->qaModel->query(
            "SELECT * FROM questions_answers WHERE is_published = 1 ORDER BY created_at DESC LIMIT 5"
        )->fetchAll();
        
        $latestFatwa = $this->fatwaModel->query(
            "SELECT * FROM fatwas WHERE is_published = 1 ORDER BY published_at DESC LIMIT 5"
        )->fetchAll();
        
        $popularQA = $this->qaModel->query(
            "SELECT * FROM questions_answers WHERE is_published = 1 ORDER BY view_count DESC LIMIT 5"
        )->fetchAll();
        
        $data = [
            'page_title' => $this->settingModel->getValue('site_name', 'Kemenag UI'),
            'site_description' => $this->settingModel->getValue('site_description', ''),
            'latest_qa' => $latestQA,
            'latest_fatwa' => $latestFatwa,
            'popular_qa' => $popularQA,
            'enable_chatbot' => $this->settingModel->getValue('enable_chatbot', '1'),
            'enable_whatsapp' => $this->settingModel->getValue('enable_whatsapp_bot', '1')
        ];
        
        $this->view('frontend/home', $data);
    }
    
    /**
     * About page
     */
    public function about()
    {
        $data = [
            'page_title' => 'Tentang Kami',
            'site_name' => $this->settingModel->getValue('site_name', 'Kemenag UI')
        ];
        
        $this->view('frontend/about', $data);
    }
    
    /**
     * Contact page
     */
    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processContact();
        } else {
            $data = [
                'page_title' => 'Hubungi Kami',
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('frontend/contact', $data);
        }
    }
    
    /**
     * Process contact form
     */
    private function processContact()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/contact');
        }
        
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        
        // Validation
        if (empty($name) || empty($email) || empty($message)) {
            $this->setFlash('error', 'Semua field harus diisi');
            flashOldInput();
            $this->redirect('/contact');
        }
        
        if (!is_email($email)) {
            $this->setFlash('error', 'Email tidak valid');
            flashOldInput();
            $this->redirect('/contact');
        }
        
        // TODO: Send email to admin
        // For now, just show success message
        
        $this->setFlash('success', 'Pesan Anda telah terkirim. Kami akan menghubungi Anda segera.');
        $this->redirect('/contact');
    }
}
