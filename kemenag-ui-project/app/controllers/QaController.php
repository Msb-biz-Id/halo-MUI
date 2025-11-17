<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\QuestionAnswer;
use App\Models\Category;

/**
 * Q&A Controller
 * Handles Tanya Jawab Keagamaan
 */
class QaController extends Controller
{
    private $qaModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->qaModel = $this->model('QuestionAnswer');
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * Q&A Index - List all Q&A with categories
     */
    public function index()
    {
        $categories = $this->categoryModel->getByType('tanya_jawab');
        
        // Get latest Q&A
        $latestQA = $this->qaModel->query(
            "SELECT qa.*, c.name as category_name, u.full_name as author_name
             FROM questions_answers qa
             LEFT JOIN categories c ON qa.category_id = c.id
             LEFT JOIN users u ON qa.author_id = u.id
             WHERE qa.is_published = 1
             ORDER BY qa.created_at DESC
             LIMIT 20"
        )->fetchAll();
        
        // Get popular Q&A
        $popularQA = $this->qaModel->query(
            "SELECT qa.*, c.name as category_name
             FROM questions_answers qa
             LEFT JOIN categories c ON qa.category_id = c.id
             WHERE qa.is_published = 1
             ORDER BY qa.view_count DESC
             LIMIT 10"
        )->fetchAll();
        
        $data = [
            'page_title' => 'Tanya Jawab Keagamaan',
            'categories' => $categories,
            'latest_qa' => $latestQA,
            'popular_qa' => $popularQA
        ];
        
        $this->view('frontend/qa/index', $data);
    }
    
    /**
     * View Q&A by Category
     */
    public function category($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category || $category['type'] !== 'tanya_jawab') {
            $this->setFlash('error', 'Kategori tidak ditemukan');
            $this->redirect('/qa');
        }
        
        $qaList = $this->qaModel->getByCategory($id);
        
        $data = [
            'page_title' => 'Tanya Jawab: ' . $category['name'],
            'category' => $category,
            'qa_list' => $qaList
        ];
        
        $this->view('frontend/qa/category', $data);
    }
    
    /**
     * View Q&A Detail
     */
    public function detail($id)
    {
        $qa = $this->qaModel->query(
            "SELECT qa.*, c.name as category_name, u.full_name as author_name
             FROM questions_answers qa
             LEFT JOIN categories c ON qa.category_id = c.id
             LEFT JOIN users u ON qa.author_id = u.id
             WHERE qa.id = :id AND qa.is_published = 1",
            [':id' => $id]
        )->fetch();
        
        if (!$qa) {
            $this->setFlash('error', 'Pertanyaan tidak ditemukan');
            $this->redirect('/qa');
        }
        
        // Increment view count
        $this->qaModel->incrementViews($id);
        
        // Get related Q&A
        $related = $this->qaModel->query(
            "SELECT * FROM questions_answers
             WHERE category_id = :cat_id AND id != :id AND is_published = 1
             ORDER BY created_at DESC
             LIMIT 5",
            [':cat_id' => $qa['category_id'], ':id' => $id]
        )->fetchAll();
        
        $data = [
            'page_title' => $qa['question'],
            'qa' => $qa,
            'related' => $related
        ];
        
        $this->view('frontend/qa/detail', $data);
    }
    
    /**
     * Search Q&A
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->redirect('/qa');
        }
        
        $results = $this->qaModel->searchQA($query);
        
        $data = [
            'page_title' => 'Hasil Pencarian: ' . $query,
            'query' => $query,
            'results' => $results
        ];
        
        $this->view('frontend/qa/search', $data);
    }
}
