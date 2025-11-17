<?php

namespace App\Controllers;

use Core\Controller;

class SearchController extends Controller
{
    public function index()
    {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->redirect('/');
        }
        
        // Search across multiple models
        $qaModel = $this->model('QuestionAnswer');
        $fatwaModel = $this->model('Fatwa');
        $materialModel = $this->model('Material');
        
        $qaResults = $qaModel->searchQA($query);
        $fatwaResults = $fatwaModel->query(
            "SELECT * FROM fatwas WHERE MATCH(title, content) AGAINST(:q IN NATURAL LANGUAGE MODE) AND is_published = 1 LIMIT 10",
            [':q' => $query]
        )->fetchAll();
        
        $materialResults = $materialModel->query(
            "SELECT * FROM materials WHERE MATCH(title, content) AGAINST(:q IN NATURAL LANGUAGE MODE) AND is_published = 1 LIMIT 10",
            [':q' => $query]
        )->fetchAll();
        
        $this->view('frontend/search/index', [
            'page_title' => 'Hasil Pencarian: ' . $query,
            'query' => $query,
            'qa_results' => $qaResults,
            'fatwa_results' => $fatwaResults,
            'material_results' => $materialResults
        ]);
    }
}
