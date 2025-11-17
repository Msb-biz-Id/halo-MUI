<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Services\ErrorMonitoringService;

class MonitoringController extends Controller
{
    private $monitoring;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->monitoring = new ErrorMonitoringService();
    }
    
    public function index()
    {
        $stats = $this->monitoring->getErrorStats(7);
        
        $this->view('admin/monitoring/index', [
            'title' => 'Error Monitoring',
            'stats' => $stats
        ]);
    }
    
    public function performance()
    {
        // Get performance logs
        $this->view('admin/monitoring/performance', [
            'title' => 'Performance Monitoring'
        ]);
    }
}
