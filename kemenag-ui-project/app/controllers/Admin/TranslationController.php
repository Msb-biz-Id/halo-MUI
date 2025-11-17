<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Translation;

/**
 * Admin Translation Controller
 * Manage multi-language translations
 */
class TranslationController extends Controller
{
    private $translationModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('content_management');
        
        $this->translationModel = $this->model('Translation');
    }
    
    /**
     * List all translations
     */
    public function index()
    {
        $language = $_GET['language'] ?? 'id';
        $search = $_GET['search'] ?? '';
        
        $translations = $this->translationModel->getByLanguage($language, $search);
        
        $data = [
            'page_title' => 'Manage Translations',
            'translations' => $translations,
            'current_language' => $language,
            'search' => $search
        ];
        
        $this->view('admin/translations/index', $data);
    }
    
    /**
     * Edit translation
     */
    public function edit($id)
    {
        $translation = $this->translationModel->findById($id);
        
        if (!$translation) {
            $_SESSION['error'] = 'Translation not found.';
            $this->redirect('/admin/translations');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'translation_value' => $_POST['translation_value'] ?? ''
            ];
            
            if ($this->translationModel->update($id, $data)) {
                // Log activity
                $auditModel = $this->model('AuditLog');
                $auditModel->log([
                    'user_id' => $_SESSION['user_id'],
                    'action' => 'update',
                    'entity_type' => 'translation',
                    'entity_id' => $id,
                    'description' => "Updated translation: {$translation['translation_key']}"
                ]);
                
                $_SESSION['success'] = 'Translation updated successfully!';
                $this->redirect('/admin/translations?language=' . $translation['language']);
                return;
            }
        }
        
        $data = [
            'page_title' => 'Edit Translation',
            'translation' => $translation
        ];
        
        $this->view('admin/translations/edit', $data);
    }
    
    /**
     * Create translation
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'language' => $_POST['language'] ?? 'id',
                'translation_key' => $_POST['translation_key'] ?? '',
                'translation_value' => $_POST['translation_value'] ?? ''
            ];
            
            if ($this->translationModel->create($data)) {
                $_SESSION['success'] = 'Translation created successfully!';
                $this->redirect('/admin/translations?language=' . $data['language']);
                return;
            }
        }
        
        $data = [
            'page_title' => 'Add Translation'
        ];
        
        $this->view('admin/translations/create', $data);
    }
    
    /**
     * Delete translation
     */
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/translations');
            return;
        }
        
        if ($this->translationModel->delete($id)) {
            $_SESSION['success'] = 'Translation deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete translation.';
        }
        
        $this->redirect('/admin/translations');
    }
}
