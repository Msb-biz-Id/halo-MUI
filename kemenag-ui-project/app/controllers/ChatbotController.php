<?php

namespace App\Controllers;

use Core\Controller;
use App\Services\GeminiService;

class ChatbotController extends Controller
{
    private $geminiService;
    
    public function __construct()
    {
        parent::__construct();
        $this->geminiService = new GeminiService();
    }
    
    public function index()
    {
        $this->view('frontend/chatbot/index', [
            'page_title' => 'Chatbot AI - Asisten Keagamaan'
        ]);
    }
    
    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid method'], 405);
        }
        
        $message = $_POST['message'] ?? '';
        $history = json_decode($_POST['history'] ?? '[]', true);
        
        if (empty($message)) {
            $this->json(['success' => false, 'message' => 'Message required'], 400);
        }
        
        $response = $this->geminiService->getIslamicAnswer($message);
        
        $this->json($response);
    }
}
