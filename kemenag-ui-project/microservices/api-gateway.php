<?php
/**
 * API Gateway for Microservices Architecture
 * Routes requests to appropriate microservices
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

class APIGateway
{
    private $services = [
        'auth' => 'http://localhost:8001',
        'certificate' => 'http://localhost:8002',
        'forum' => 'http://localhost:8003',
        'content' => 'http://localhost:8004',
        'notification' => 'http://localhost:8005'
    ];
    
    private $rateLimits = [];
    
    public function handle()
    {
        // CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        
        // Parse request
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Extract service from path
        // Format: /api/service/endpoint
        preg_match('#^/api/([^/]+)/(.*)$#', $path, $matches);
        
        if (!$matches) {
            $this->sendError('Invalid API path', 404);
            return;
        }
        
        $service = $matches[1];
        $endpoint = $matches[2];
        
        // Check service exists
        if (!isset($this->services[$service])) {
            $this->sendError('Service not found', 404);
            return;
        }
        
        // Rate limiting
        if (!$this->checkRateLimit()) {
            $this->sendError('Rate limit exceeded', 429);
            return;
        }
        
        // Authentication
        $token = $this->extractToken();
        if (!$this->validateToken($token)) {
            $this->sendError('Unauthorized', 401);
            return;
        }
        
        // Forward request
        $response = $this->forwardRequest($service, $endpoint, $method);
        
        // Send response
        $this->sendResponse($response);
    }
    
    private function forwardRequest(string $service, string $endpoint, string $method): array
    {
        $url = $this->services[$service] . '/' . $endpoint;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        // Forward body
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $body = file_get_contents('php://input');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        
        // Forward headers
        $headers = $this->getForwardHeaders();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status' => $statusCode,
            'body' => $response
        ];
    }
    
    private function checkRateLimit(): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $now = time();
        
        if (!isset($this->rateLimits[$ip])) {
            $this->rateLimits[$ip] = ['count' => 0, 'reset' => $now + 60];
        }
        
        if ($now > $this->rateLimits[$ip]['reset']) {
            $this->rateLimits[$ip] = ['count' => 0, 'reset' => $now + 60];
        }
        
        $this->rateLimits[$ip]['count']++;
        
        return $this->rateLimits[$ip]['count'] <= 100; // 100 requests per minute
    }
    
    private function extractToken(): ?string
    {
        $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
            return $matches[1];
        }
        return null;
    }
    
    private function validateToken(?string $token): bool
    {
        // TODO: Implement JWT validation
        return !empty($token);
    }
    
    private function getForwardHeaders(): array
    {
        return [
            'Content-Type: application/json',
            'Authorization: ' . ($_SERVER['HTTP_AUTHORIZATION'] ?? '')
        ];
    }
    
    private function sendResponse(array $response)
    {
        http_response_code($response['status']);
        echo $response['body'];
    }
    
    private function sendError(string $message, int $code)
    {
        http_response_code($code);
        echo json_encode(['error' => $message]);
    }
}

// Run gateway
$gateway = new APIGateway();
$gateway->handle();
