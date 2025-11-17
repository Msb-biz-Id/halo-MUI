<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Services\TurnstileService;

/**
 * Turnstile Admin Controller
 * Manage and monitor Turnstile security
 */
class TurnstileController extends Controller
{
    private $turnstile;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->turnstile = new TurnstileService();
    }
    
    /**
     * Dashboard - Statistics and monitoring
     */
    public function index()
    {
        $stats = $this->turnstile->getStats(7);
        $suspicious = $this->turnstile->getSuspiciousIPs(5, 24);
        
        $this->view('admin/turnstile/index', [
            'title' => 'Turnstile Security',
            'stats' => $stats,
            'suspicious' => $suspicious,
            'enabled' => $this->turnstile->isEnabled(),
            'site_key' => $this->turnstile->getSiteKey()
        ]);
    }
    
    /**
     * View suspicious IPs
     */
    public function suspicious()
    {
        $threshold = $_GET['threshold'] ?? 5;
        $hours = $_GET['hours'] ?? 24;
        
        $suspicious = $this->turnstile->getSuspiciousIPs($threshold, $hours);
        
        $this->view('admin/turnstile/suspicious', [
            'title' => 'Suspicious IPs',
            'suspicious' => $suspicious,
            'threshold' => $threshold,
            'hours' => $hours
        ]);
    }
    
    /**
     * Test Turnstile verification
     */
    public function test()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['cf-turnstile-response'] ?? '';
            $result = $this->turnstile->verify($token, $_SERVER['REMOTE_ADDR']);
            
            $this->view('admin/turnstile/test', [
                'title' => 'Test Turnstile',
                'result' => $result,
                'tested' => true
            ]);
            return;
        }
        
        $this->view('admin/turnstile/test', [
            'title' => 'Test Turnstile',
            'tested' => false
        ]);
    }
}
