<?php

namespace App\Controllers\Admin;

use Core\Controller;

/**
 * Bulk Action Controller
 * Handle bulk edit/delete for all content types like WordPress
 */
class BulkActionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * Process bulk actions
     */
    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }
        
        $action = $_POST['bulk_action'] ?? '';
        $type = $_POST['content_type'] ?? '';
        $ids = $_POST['ids'] ?? [];
        
        if (empty($action) || empty($type) || empty($ids)) {
            $this->json(['error' => 'Missing parameters'], 400);
            return;
        }
        
        // Validate IDs
        $ids = array_filter(array_map('intval', $ids));
        if (empty($ids)) {
            $this->json(['error' => 'No valid IDs provided'], 400);
            return;
        }
        
        try {
            switch ($action) {
                case 'delete':
                    $result = $this->bulkDelete($type, $ids);
                    break;
                    
                case 'publish':
                    $result = $this->bulkUpdateStatus($type, $ids, 'published');
                    break;
                    
                case 'draft':
                    $result = $this->bulkUpdateStatus($type, $ids, 'draft');
                    break;
                    
                case 'trash':
                    $result = $this->bulkTrash($type, $ids);
                    break;
                    
                case 'restore':
                    $result = $this->bulkRestore($type, $ids);
                    break;
                    
                case 'category':
                    $categoryId = $_POST['category_id'] ?? null;
                    $result = $this->bulkUpdateCategory($type, $ids, $categoryId);
                    break;
                    
                case 'export':
                    $result = $this->bulkExport($type, $ids);
                    break;
                    
                default:
                    $this->json(['error' => 'Invalid action'], 400);
                    return;
            }
            
            $this->json(['success' => true, 'result' => $result]);
            
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Bulk delete items
     */
    private function bulkDelete(string $type, array $ids): array
    {
        $table = $this->getTableName($type);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "DELETE FROM {$table} WHERE id IN ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        
        $deleted = $stmt->rowCount();
        
        // Log audit
        foreach ($ids as $id) {
            $this->logAudit('bulk_delete', $type, $id);
        }
        
        return [
            'deleted' => $deleted,
            'message' => "{$deleted} item(s) deleted successfully"
        ];
    }
    
    /**
     * Bulk update status
     */
    private function bulkUpdateStatus(string $type, array $ids, string $status): array
    {
        $table = $this->getTableName($type);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "UPDATE {$table} SET status = ? WHERE id IN ({$placeholders})";
        $params = array_merge([$status], $ids);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $updated = $stmt->rowCount();
        
        return [
            'updated' => $updated,
            'message' => "{$updated} item(s) updated to {$status}"
        ];
    }
    
    /**
     * Bulk move to trash
     */
    private function bulkTrash(string $type, array $ids): array
    {
        $table = $this->getTableName($type);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "UPDATE {$table} SET status = 'trash', deleted_at = NOW() WHERE id IN ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        
        return [
            'trashed' => $stmt->rowCount(),
            'message' => "{$stmt->rowCount()} item(s) moved to trash"
        ];
    }
    
    /**
     * Bulk restore from trash
     */
    private function bulkRestore(string $type, array $ids): array
    {
        $table = $this->getTableName($type);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "UPDATE {$table} SET status = 'draft', deleted_at = NULL WHERE id IN ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        
        return [
            'restored' => $stmt->rowCount(),
            'message' => "{$stmt->rowCount()} item(s) restored"
        ];
    }
    
    /**
     * Bulk update category
     */
    private function bulkUpdateCategory(string $type, array $ids, ?int $categoryId): array
    {
        if (!$categoryId) {
            throw new \Exception('Category ID is required');
        }
        
        $table = $this->getTableName($type);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "UPDATE {$table} SET category_id = ? WHERE id IN ({$placeholders})";
        $params = array_merge([$categoryId], $ids);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return [
            'updated' => $stmt->rowCount(),
            'message' => "{$stmt->rowCount()} item(s) category updated"
        ];
    }
    
    /**
     * Bulk export to Excel
     */
    private function bulkExport(string $type, array $ids): array
    {
        $table = $this->getTableName($type);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "SELECT * FROM {$table} WHERE id IN ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Generate Excel file
        $filename = $type . '_export_' . date('Y-m-d_His') . '.xlsx';
        $filepath = __DIR__ . '/../../../storage/exports/' . $filename;
        
        // Create exports directory if not exists
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0777, true);
        }
        
        // Use PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        if (!empty($data)) {
            $headers = array_keys($data[0]);
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', ucfirst($header));
                $col++;
            }
            
            // Data
            $row = 2;
            foreach ($data as $item) {
                $col = 'A';
                foreach ($item as $value) {
                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }
                $row++;
            }
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);
        
        return [
            'exported' => count($data),
            'filename' => $filename,
            'download_url' => url('admin/export/download/' . $filename),
            'message' => count($data) . " item(s) exported"
        ];
    }
    
    /**
     * Get table name for content type
     */
    private function getTableName(string $type): string
    {
        $tables = [
            'qa' => 'questions_answers',
            'fatwa' => 'fatwas',
            'material' => 'materials',
            'book' => 'books',
            'certificate' => 'certificate_applications',
            'forum_topic' => 'forum_topics',
            'forum_post' => 'forum_posts',
            'user' => 'users',
            'category' => 'categories'
        ];
        
        if (!isset($tables[$type])) {
            throw new \Exception('Invalid content type');
        }
        
        return $tables[$type];
    }
    
    /**
     * Log audit trail
     */
    private function logAudit(string $action, string $type, int $itemId)
    {
        $sql = "INSERT INTO audit_logs (user_id, action, table_name, record_id, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $_SESSION['user_id'] ?? 0,
            $action,
            $type,
            $itemId
        ]);
    }
}
