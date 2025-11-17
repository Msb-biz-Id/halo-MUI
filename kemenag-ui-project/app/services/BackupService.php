<?php

namespace App\Services;

use Exception;
use ZipArchive;

/**
 * Automated Backup Service
 * Handles database and file backups
 */
class BackupService
{
    private $backupPath;
    private $dbConfig;
    private $retentionDays;
    private $compressionEnabled;
    
    public function __construct()
    {
        $this->backupPath = __DIR__ . '/../../storage/backups/';
        $this->retentionDays = env('BACKUP_RETENTION_DAYS', 30);
        $this->compressionEnabled = env('BACKUP_COMPRESSION', true);
        
        // Ensure backup directory exists
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
        
        // Load database config
        $dbConfigFile = require __DIR__ . '/../../config/database.php';
        $this->dbConfig = $dbConfigFile['connections'][$dbConfigFile['default']];
    }
    
    /**
     * Perform full backup (database + files)
     */
    public function performFullBackup(): array
    {
        $timestamp = date('Y-m-d_His');
        $results = [
            'timestamp' => $timestamp,
            'database' => null,
            'files' => null,
            'success' => false
        ];
        
        try {
            // Backup database
            $dbBackup = $this->backupDatabase($timestamp);
            $results['database'] = $dbBackup;
            
            // Backup files
            $fileBackup = $this->backupFiles($timestamp);
            $results['files'] = $fileBackup;
            
            // Cleanup old backups
            $this->cleanupOldBackups();
            
            $results['success'] = true;
            
            // Log success
            $this->logBackup('SUCCESS', $results);
            
        } catch (Exception $e) {
            $results['error'] = $e->getMessage();
            $this->logBackup('FAILED', $results);
            throw $e;
        }
        
        return $results;
    }
    
    /**
     * Backup database
     */
    public function backupDatabase(string $timestamp): array
    {
        $filename = "database_{$timestamp}.sql";
        $filepath = $this->backupPath . $filename;
        
        // Build mysqldump command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s 2>&1',
            escapeshellarg($this->dbConfig['username']),
            escapeshellarg($this->dbConfig['password']),
            escapeshellarg($this->dbConfig['host']),
            escapeshellarg($this->dbConfig['port'] ?? 3306),
            escapeshellarg($this->dbConfig['database']),
            escapeshellarg($filepath)
        );
        
        // Execute backup
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new Exception("Database backup failed: " . implode("\n", $output));
        }
        
        // Compress if enabled
        if ($this->compressionEnabled) {
            $compressedFile = $filepath . '.gz';
            exec("gzip -f " . escapeshellarg($filepath), $output, $returnCode);
            
            if ($returnCode === 0) {
                $filepath = $compressedFile;
                $filename .= '.gz';
            }
        }
        
        $filesize = file_exists($filepath) ? filesize($filepath) : 0;
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'size' => $this->formatBytes($filesize),
            'size_bytes' => $filesize
        ];
    }
    
    /**
     * Backup important files
     */
    public function backupFiles(string $timestamp): array
    {
        $filename = "files_{$timestamp}.zip";
        $filepath = $this->backupPath . $filename;
        
        $zip = new ZipArchive();
        
        if ($zip->open($filepath, ZipArchive::CREATE) !== true) {
            throw new Exception("Failed to create ZIP archive");
        }
        
        // Directories to backup
        $dirsToBackup = [
            'public/uploads',
            'storage/app',
            'storage/logs',
            'config',
            '.env'
        ];
        
        $basePath = __DIR__ . '/../../';
        $fileCount = 0;
        
        foreach ($dirsToBackup as $dir) {
            $fullPath = $basePath . $dir;
            
            if (is_file($fullPath)) {
                $zip->addFile($fullPath, $dir);
                $fileCount++;
            } elseif (is_dir($fullPath)) {
                $this->addDirectoryToZip($zip, $fullPath, $dir, $fileCount);
            }
        }
        
        $zip->close();
        
        $filesize = file_exists($filepath) ? filesize($filepath) : 0;
        
        return [
            'filename' => $filename,
            'filepath' => $filepath,
            'size' => $this->formatBytes($filesize),
            'size_bytes' => $filesize,
            'file_count' => $fileCount
        ];
    }
    
    /**
     * Add directory to ZIP recursively
     */
    private function addDirectoryToZip(ZipArchive $zip, string $realPath, string $zipPath, int &$fileCount)
    {
        if (!is_dir($realPath)) {
            return;
        }
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($realPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipPath . '/' . substr($filePath, strlen($realPath) + 1);
                
                $zip->addFile($filePath, $relativePath);
                $fileCount++;
            }
        }
    }
    
    /**
     * Restore from backup
     */
    public function restoreDatabase(string $backupFile): bool
    {
        $filepath = $this->backupPath . $backupFile;
        
        if (!file_exists($filepath)) {
            throw new Exception("Backup file not found: {$backupFile}");
        }
        
        // Decompress if needed
        if (substr($backupFile, -3) === '.gz') {
            $decompressed = str_replace('.gz', '', $filepath);
            exec("gunzip -c " . escapeshellarg($filepath) . " > " . escapeshellarg($decompressed));
            $filepath = $decompressed;
        }
        
        // Restore database
        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s --port=%s %s < %s 2>&1',
            escapeshellarg($this->dbConfig['username']),
            escapeshellarg($this->dbConfig['password']),
            escapeshellarg($this->dbConfig['host']),
            escapeshellarg($this->dbConfig['port'] ?? 3306),
            escapeshellarg($this->dbConfig['database']),
            escapeshellarg($filepath)
        );
        
        exec($command, $output, $returnCode);
        
        // Cleanup decompressed file
        if (isset($decompressed) && file_exists($decompressed)) {
            unlink($decompressed);
        }
        
        if ($returnCode !== 0) {
            throw new Exception("Database restore failed: " . implode("\n", $output));
        }
        
        $this->logBackup('RESTORE', ['file' => $backupFile]);
        
        return true;
    }
    
    /**
     * List available backups
     */
    public function listBackups(): array
    {
        $backups = [];
        $files = glob($this->backupPath . '*');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $backups[] = [
                    'filename' => basename($file),
                    'filepath' => $file,
                    'size' => $this->formatBytes(filesize($file)),
                    'size_bytes' => filesize($file),
                    'created' => date('Y-m-d H:i:s', filemtime($file)),
                    'age_days' => floor((time() - filemtime($file)) / 86400)
                ];
            }
        }
        
        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return $b['created'] <=> $a['created'];
        });
        
        return $backups;
    }
    
    /**
     * Cleanup old backups
     */
    private function cleanupOldBackups(): int
    {
        $cutoffTime = time() - ($this->retentionDays * 86400);
        $deleted = 0;
        
        $files = glob($this->backupPath . '*');
        
        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoffTime) {
                unlink($file);
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->logBackup('CLEANUP', ['deleted_count' => $deleted]);
        }
        
        return $deleted;
    }
    
    /**
     * Upload backup to cloud storage (S3, etc.)
     */
    public function uploadToCloud(string $filename): bool
    {
        // TODO: Implement S3/Cloud storage upload
        // Example:
        // $s3Client = new Aws\S3\S3Client([...]);
        // $s3Client->putObject([
        //     'Bucket' => env('AWS_S3_BUCKET'),
        //     'Key' => $filename,
        //     'SourceFile' => $this->backupPath . $filename
        // ]);
        
        return true;
    }
    
    /**
     * Log backup activity
     */
    private function logBackup(string $action, array $data)
    {
        $logFile = $this->backupPath . 'backup.log';
        $logEntry = [
            'action' => $action,
            'timestamp' => date('Y-m-d H:i:s'),
            'data' => $data
        ];
        
        file_put_contents(
            $logFile,
            json_encode($logEntry) . PHP_EOL,
            FILE_APPEND
        );
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        
        return number_format($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
    
    /**
     * Get backup statistics
     */
    public function getBackupStats(): array
    {
        $backups = $this->listBackups();
        
        $stats = [
            'total_backups' => count($backups),
            'total_size' => 0,
            'oldest_backup' => null,
            'newest_backup' => null,
            'average_size' => 0
        ];
        
        if (!empty($backups)) {
            $stats['total_size'] = array_sum(array_column($backups, 'size_bytes'));
            $stats['oldest_backup'] = end($backups)['created'];
            $stats['newest_backup'] = $backups[0]['created'];
            $stats['average_size'] = $stats['total_size'] / count($backups);
            
            // Format
            $stats['total_size'] = $this->formatBytes($stats['total_size']);
            $stats['average_size'] = $this->formatBytes($stats['average_size']);
        }
        
        return $stats;
    }
}
