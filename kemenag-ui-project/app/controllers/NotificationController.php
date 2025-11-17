<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    private $notificationModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->notificationModel = $this->model('Notification');
    }
    
    public function index()
    {
        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getByUser($userId);
        
        $this->view('user/notifications/index', [
            'page_title' => 'Notifikasi',
            'notifications' => $notifications
        ]);
    }
    
    public function markRead($id)
    {
        $notification = $this->notificationModel->findById($id);
        
        if (!$notification || $notification['user_id'] != $_SESSION['user_id']) {
            $this->json(['success' => false], 403);
        }
        
        $this->notificationModel->markAsRead($id);
        $this->json(['success' => true]);
    }
    
    public function markAllRead()
    {
        $userId = $_SESSION['user_id'];
        $this->notificationModel->markAllAsRead($userId);
        $this->json(['success' => true]);
    }
}
