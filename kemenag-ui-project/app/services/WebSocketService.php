<?php

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * WebSocket Service for Real-time Features
 * Handles real-time notifications, chat, and updates
 */
class WebSocketService implements MessageComponentInterface
{
    protected $clients;
    protected $users;
    
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
        $this->users = [];
    }
    
    /**
     * New connection opened
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        
        echo "New connection! ({$conn->resourceId})\n";
    }
    
    /**
     * Message received
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        
        if (!$data) {
            return;
        }
        
        // Handle different message types
        switch ($data['type'] ?? '') {
            case 'auth':
                $this->handleAuth($from, $data);
                break;
                
            case 'chat':
                $this->handleChat($from, $data);
                break;
                
            case 'notification':
                $this->handleNotification($from, $data);
                break;
                
            case 'typing':
                $this->handleTyping($from, $data);
                break;
                
            case 'presence':
                $this->handlePresence($from, $data);
                break;
                
            default:
                $this->broadcast($msg, $from);
        }
    }
    
    /**
     * Connection closed
     */
    public function onClose(ConnectionInterface $conn)
    {
        // Remove user from users list
        if (isset($this->users[$conn->resourceId])) {
            $userId = $this->users[$conn->resourceId];
            unset($this->users[$conn->resourceId]);
            
            // Broadcast user offline
            $this->broadcastPresence($userId, 'offline');
        }
        
        $this->clients->detach($conn);
        
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    
    /**
     * Error occurred
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
    
    /**
     * Handle authentication
     */
    private function handleAuth(ConnectionInterface $conn, array $data)
    {
        $userId = $data['user_id'] ?? null;
        $token = $data['token'] ?? null;
        
        // Verify token (implement your auth logic)
        if ($this->verifyToken($token, $userId)) {
            $this->users[$conn->resourceId] = $userId;
            
            // Send auth success
            $conn->send(json_encode([
                'type' => 'auth_success',
                'user_id' => $userId
            ]));
            
            // Broadcast user online
            $this->broadcastPresence($userId, 'online');
            
            echo "User {$userId} authenticated\n";
        } else {
            $conn->send(json_encode([
                'type' => 'auth_failed',
                'message' => 'Invalid credentials'
            ]));
        }
    }
    
    /**
     * Handle chat message
     */
    private function handleChat(ConnectionInterface $from, array $data)
    {
        $message = [
            'type' => 'chat',
            'from_user_id' => $this->users[$from->resourceId] ?? null,
            'to_user_id' => $data['to_user_id'] ?? null,
            'message' => $data['message'] ?? '',
            'timestamp' => time()
        ];
        
        // Send to specific user or broadcast
        if (isset($message['to_user_id'])) {
            $this->sendToUser($message['to_user_id'], json_encode($message));
        } else {
            $this->broadcast(json_encode($message), $from);
        }
        
        // Save to database
        $this->saveChatMessage($message);
    }
    
    /**
     * Handle notification
     */
    private function handleNotification(ConnectionInterface $from, array $data)
    {
        $notification = [
            'type' => 'notification',
            'title' => $data['title'] ?? '',
            'message' => $data['message'] ?? '',
            'icon' => $data['icon'] ?? 'bell',
            'timestamp' => time()
        ];
        
        // Send to specific user(s)
        if (isset($data['user_ids'])) {
            foreach ($data['user_ids'] as $userId) {
                $this->sendToUser($userId, json_encode($notification));
            }
        } else {
            $this->broadcast(json_encode($notification));
        }
    }
    
    /**
     * Handle typing indicator
     */
    private function handleTyping(ConnectionInterface $from, array $data)
    {
        $typing = [
            'type' => 'typing',
            'user_id' => $this->users[$from->resourceId] ?? null,
            'is_typing' => $data['is_typing'] ?? false,
            'channel' => $data['channel'] ?? 'general'
        ];
        
        $this->broadcast(json_encode($typing), $from);
    }
    
    /**
     * Handle presence (online/offline)
     */
    private function handlePresence(ConnectionInterface $from, array $data)
    {
        $userId = $this->users[$from->resourceId] ?? null;
        $status = $data['status'] ?? 'online';
        
        $this->broadcastPresence($userId, $status);
    }
    
    /**
     * Broadcast presence change
     */
    private function broadcastPresence($userId, string $status)
    {
        $message = json_encode([
            'type' => 'presence',
            'user_id' => $userId,
            'status' => $status,
            'timestamp' => time()
        ]);
        
        $this->broadcast($message);
    }
    
    /**
     * Broadcast message to all clients
     */
    private function broadcast(string $message, ConnectionInterface $except = null)
    {
        foreach ($this->clients as $client) {
            if ($except && $from !== $client) {
                $client->send($message);
            } elseif (!$except) {
                $client->send($message);
            }
        }
    }
    
    /**
     * Send message to specific user
     */
    private function sendToUser($userId, string $message)
    {
        foreach ($this->users as $resourceId => $uid) {
            if ($uid == $userId) {
                foreach ($this->clients as $client) {
                    if ($client->resourceId == $resourceId) {
                        $client->send($message);
                        break;
                    }
                }
            }
        }
    }
    
    /**
     * Verify authentication token
     */
    private function verifyToken($token, $userId): bool
    {
        // TODO: Implement your token verification logic
        // Example: check JWT token, session, etc.
        return !empty($token) && !empty($userId);
    }
    
    /**
     * Save chat message to database
     */
    private function saveChatMessage(array $message)
    {
        // TODO: Save to database
        // Example: INSERT INTO messages ...
    }
    
    /**
     * Get online users
     */
    public function getOnlineUsers(): array
    {
        return array_unique(array_values($this->users));
    }
    
    /**
     * Get connection count
     */
    public function getConnectionCount(): int
    {
        return count($this->clients);
    }
}
