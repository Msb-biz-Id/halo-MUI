<?php

namespace App\Controllers;

use Core\Controller;
use App\Services\WhatsAppService;

class WhatsappController extends Controller
{
    private $whatsappService;
    
    public function __construct()
    {
        parent::__construct();
        $this->whatsappService = new WhatsAppService();
    }
    
    public function webhook()
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        
        if (!$payload) {
            $this->json(['success' => false, 'message' => 'Invalid payload'], 400);
        }
        
        $result = $this->whatsappService->handleWebhook($payload);
        
        $this->json($result);
    }
}
