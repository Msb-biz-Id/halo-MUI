<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * Gemini AI Service
 * Handles integration with Google Gemini AI
 */
class GeminiService
{
    private $client;
    private $apiKey;
    private $model;
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta';
    
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = GEMINI_API_KEY;
        $this->model = GEMINI_MODEL;
    }
    
    /**
     * Send message to Gemini AI
     * 
     * @param string $message
     * @param array $history
     * @return array
     */
    public function chat($message, $history = [])
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'Gemini API key not configured'
            ];
        }
        
        try {
            $contents = [];
            
            // Add history
            foreach ($history as $item) {
                $contents[] = [
                    'role' => $item['role'] ?? 'user',
                    'parts' => [['text' => $item['message']]]
                ];
            }
            
            // Add current message
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $message]]
            ];
            
            $url = "{$this->apiUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";
            
            $response = $this->client->post($url, [
                'json' => [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                
                return [
                    'success' => true,
                    'message' => $reply,
                    'raw_response' => $data
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Invalid response from Gemini AI'
            ];
            
        } catch (\Exception $e) {
            error_log('Gemini API error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Error communicating with AI: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get Islamic Q&A response
     * 
     * @param string $question
     * @return array
     */
    public function getIslamicAnswer($question)
    {
        $systemPrompt = "Anda adalah asisten AI untuk Kemenag (Kementerian Agama) Indonesia yang bertugas menjawab pertanyaan seputar Islam, ibadah, fatwa, dan moderasi beragama. Jawab dengan bahasa Indonesia yang sopan dan merujuk pada sumber-sumber Islam yang terpercaya. Jika pertanyaan di luar topik keagamaan, arahkan pengguna untuk bertanya hal yang relevan.";
        
        $fullMessage = $systemPrompt . "\n\nPertanyaan: " . $question;
        
        return $this->chat($fullMessage);
    }
}
