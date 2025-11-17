<?php

namespace App\Services;

/**
 * Queue Service for Background Job Processing
 * Handles async tasks: emails, PDFs, exports, image processing
 */
class QueueService
{
    private $queuePath;
    private $maxRetries;
    
    public function __construct()
    {
        $this->queuePath = __DIR__ . '/../../storage/queue/';
        $this->maxRetries = env('QUEUE_MAX_RETRIES', 3);
        
        if (!is_dir($this->queuePath)) {
            mkdir($this->queuePath, 0777, true);
        }
    }
    
    /**
     * Add job to queue
     */
    public function dispatch(string $jobClass, array $data, string $queue = 'default'): string
    {
        $jobId = uniqid('job_', true);
        
        $job = [
            'id' => $jobId,
            'class' => $jobClass,
            'data' => $data,
            'queue' => $queue,
            'attempts' => 0,
            'status' => 'pending',
            'created_at' => time(),
            'scheduled_at' => $data['delay'] ?? time()
        ];
        
        $filename = $this->queuePath . "{$queue}_{$jobId}.json";
        file_put_contents($filename, json_encode($job));
        
        return $jobId;
    }
    
    /**
     * Process queue (worker)
     */
    public function work(string $queue = 'default', int $maxJobs = 0)
    {
        $processed = 0;
        
        echo "🚀 Queue Worker Started (Queue: {$queue})\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        while (true) {
            $job = $this->getNextJob($queue);
            
            if (!$job) {
                echo "⏳ Waiting for jobs...\n";
                sleep(3);
                continue;
            }
            
            echo "[" . date('H:i:s') . "] Processing job: {$job['id']}\n";
            
            try {
                $this->processJob($job);
                echo "✅ Job completed: {$job['id']}\n";
                $processed++;
            } catch (\Exception $e) {
                echo "❌ Job failed: {$job['id']} - {$e->getMessage()}\n";
                $this->handleFailedJob($job, $e);
            }
            
            if ($maxJobs > 0 && $processed >= $maxJobs) {
                echo "\n✅ Processed {$processed} jobs. Stopping.\n";
                break;
            }
        }
    }
    
    /**
     * Get next job from queue
     */
    private function getNextJob(string $queue): ?array
    {
        $files = glob($this->queuePath . "{$queue}_*.json");
        
        if (empty($files)) {
            return null;
        }
        
        // Sort by creation time
        usort($files, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        
        foreach ($files as $file) {
            $job = json_decode(file_get_contents($file), true);
            
            // Check if ready to process
            if ($job['scheduled_at'] <= time() && $job['status'] === 'pending') {
                $job['file'] = $file;
                return $job;
            }
        }
        
        return null;
    }
    
    /**
     * Process a job
     */
    private function processJob(array $job)
    {
        $jobClass = $job['class'];
        
        if (!class_exists($jobClass)) {
            throw new \Exception("Job class not found: {$jobClass}");
        }
        
        $instance = new $jobClass();
        $instance->handle($job['data']);
        
        // Mark as completed
        unlink($job['file']);
    }
    
    /**
     * Handle failed job
     */
    private function handleFailedJob(array $job, \Exception $e)
    {
        $job['attempts']++;
        $job['last_error'] = $e->getMessage();
        
        if ($job['attempts'] >= $this->maxRetries) {
            // Move to failed queue
            $failedFile = $this->queuePath . "failed_{$job['id']}.json";
            file_put_contents($failedFile, json_encode($job));
            unlink($job['file']);
            
            echo "💀 Job moved to failed queue after {$job['attempts']} attempts\n";
        } else {
            // Retry with exponential backoff
            $job['scheduled_at'] = time() + (pow(2, $job['attempts']) * 60);
            $job['status'] = 'pending';
            file_put_contents($job['file'], json_encode($job));
            
            echo "🔄 Job scheduled for retry #{$job['attempts']}\n";
        }
    }
    
    /**
     * Get queue statistics
     */
    public function getStats(): array
    {
        $stats = [
            'pending' => 0,
            'failed' => 0,
            'by_queue' => []
        ];
        
        $files = glob($this->queuePath . '*.json');
        
        foreach ($files as $file) {
            $job = json_decode(file_get_contents($file), true);
            
            if (strpos(basename($file), 'failed_') === 0) {
                $stats['failed']++;
            } else {
                $stats['pending']++;
                $queue = $job['queue'] ?? 'default';
                $stats['by_queue'][$queue] = ($stats['by_queue'][$queue] ?? 0) + 1;
            }
        }
        
        return $stats;
    }
    
    /**
     * Clear queue
     */
    public function clear(string $queue = 'all'): int
    {
        $pattern = $queue === 'all' ? '*.json' : "{$queue}_*.json";
        $files = glob($this->queuePath . $pattern);
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return count($files);
    }
}

/**
 * Example Job Classes
 */
class SendEmailJob
{
    public function handle(array $data)
    {
        $emailService = new EmailService();
        $emailService->sendEmail($data['to'], $data['subject'], $data['body']);
    }
}

class GeneratePDFJob
{
    public function handle(array $data)
    {
        $pdfService = new PDFService();
        $pdfService->generateCertificate($data['certificate_id']);
    }
}
