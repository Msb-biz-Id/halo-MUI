<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Services\QueueService;

class QueueController extends Controller
{
    private $queueService;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->queueService = new QueueService();
    }
    
    public function index()
    {
        $stats = $this->queueService->getStats();
        
        $this->view('admin/queue/index', [
            'title' => 'Queue Management',
            'stats' => $stats
        ]);
    }
    
    public function clear($queue = 'all')
    {
        $cleared = $this->queueService->clear($queue);
        $_SESSION['success'] = "Cleared {$cleared} jobs from queue";
        
        redirect('/admin/queue');
    }
}
