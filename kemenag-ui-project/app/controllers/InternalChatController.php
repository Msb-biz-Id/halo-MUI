<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\InternalMessage;
use App\Models\User;

class InternalChatController extends Controller
{
    private $messageModel;
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->messageModel = $this->model('InternalMessage');
        $this->userModel = $this->model('User');
    }
    
    public function index()
    {
        $userId = $_SESSION['user_id'];
        $inbox = $this->messageModel->getInbox($userId);
        
        $this->view('user/messages/index', [
            'page_title' => 'Pesan Internal',
            'messages' => $inbox,
            'unread_count' => $this->messageModel->countUnread($userId)
        ]);
    }
    
    public function conversation($userId2)
    {
        $userId = $_SESSION['user_id'];
        $messages = $this->messageModel->getConversation($userId, $userId2);
        $otherUser = $this->userModel->findById($userId2);
        
        $this->view('user/messages/conversation', [
            'page_title' => 'Percakapan dengan ' . $otherUser['full_name'],
            'messages' => $messages,
            'other_user' => $otherUser,
            'csrf_token' => $this->generateCsrf()
        ]);
    }
    
    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false], 405);
        }
        
        if (!$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF'], 403);
        }
        
        $senderId = $_SESSION['user_id'];
        $receiverId = $_POST['receiver_id'] ?? 0;
        $message = $_POST['message'] ?? '';
        
        if (empty($message) || !$receiverId) {
            $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }
        
        $messageId = $this->messageModel->insert([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->json(['success' => true, 'message_id' => $messageId]);
    }
}
