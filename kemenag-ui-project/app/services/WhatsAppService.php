<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * WhatsApp Service
 * Handles WhatsApp integration (WAHA & Fonnte)
 */
class WhatsAppService
{
    private $client;
    private $provider;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->provider = WHATSAPP_PROVIDER;
    }
    
    /**
     * Send message
     * 
     * @param string $to
     * @param string $message
     * @return array
     */
    public function sendMessage($to, $message)
    {
        if ($this->provider === 'waha') {
            return $this->sendViaWAHA($to, $message);
        } elseif ($this->provider === 'fonnte') {
            return $this->sendViaFonnte($to, $message);
        }
        
        return ['success' => false, 'message' => 'Invalid WhatsApp provider'];
    }
    
    /**
     * Send message via WAHA
     * 
     * @param string $to
     * @param string $message
     * @return array
     */
    private function sendViaWAHA($to, $message)
    {
        try {
            $url = WAHA_API_URL . '/api/sendText';
            
            $response = $this->client->post($url, [
                'json' => [
                    'session' => WAHA_SESSION_NAME,
                    'chatId' => $to,
                    'text' => $message
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Api-Key' => WAHA_API_KEY
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'success' => true,
                'data' => $data
            ];
            
        } catch (\Exception $e) {
            error_log('WAHA API error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Send message via Fonnte
     * 
     * @param string $to
     * @param string $message
     * @return array
     */
    private function sendViaFonnte($to, $message)
    {
        try {
            $url = FONNTE_API_URL . '/send';
            
            $response = $this->client->post($url, [
                'form_params' => [
                    'target' => $to,
                    'message' => $message,
                    'countryCode' => '62'
                ],
                'headers' => [
                    'Authorization' => FONNTE_TOKEN
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            return [
                'success' => $data['status'] ?? false,
                'data' => $data
            ];
            
        } catch (\Exception $e) {
            error_log('Fonnte API error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Handle incoming webhook
     * 
     * @param array $payload
     * @return array
     */
    public function handleWebhook($payload)
    {
        $whatsappId = $payload['from'] ?? null;
        $message = $payload['body'] ?? $payload['text'] ?? '';
        
        if (!$whatsappId || !$message) {
            return ['success' => false, 'message' => 'Invalid payload'];
        }
        
        // Get or create WhatsApp user
        $whatsappUserModel = new \App\Models\WhatsappUser();
        $whatsappUser = $whatsappUserModel->getByWhatsappId($whatsappId);
        
        if (!$whatsappUser) {
            $whatsappUserModel->createOrUpdate($whatsappId, [
                'phone_number' => $whatsappId,
                'current_mode' => 'gemini_ai'
            ]);
            $whatsappUser = $whatsappUserModel->getByWhatsappId($whatsappId);
        }
        
        // Get AI response
        $geminiService = new GeminiService();
        $history = $whatsappUser['gemini_history'] ?? [];
        
        $aiResponse = $geminiService->getIslamicAnswer($message);
        
        if ($aiResponse['success']) {
            $reply = $aiResponse['message'];
            
            // Update history
            $history[] = ['role' => 'user', 'message' => $message];
            $history[] = ['role' => 'model', 'message' => $reply];
            
            // Keep only last 10 exchanges
            if (count($history) > 20) {
                $history = array_slice($history, -20);
            }
            
            $whatsappUserModel->updateHistory($whatsappId, $history);
            
            // Send reply
            $this->sendMessage($whatsappId, $reply);
            
            return ['success' => true, 'reply' => $reply];
        }
        
        return ['success' => false, 'message' => 'AI response failed'];
    }
}
