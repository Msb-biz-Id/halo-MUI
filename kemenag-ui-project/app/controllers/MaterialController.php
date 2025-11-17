<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Material;
use App\Models\Category;

class MaterialController extends Controller
{
    private $materialModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->materialModel = $this->model('Material');
        $this->categoryModel = $this->model('Category');
    }
    
    public function index()
    {
        $categories = $this->categoryModel->getByType('materi');
        $materials = $this->materialModel->getPublished();
        
        $this->view('frontend/material/index', [
            'page_title' => 'Materi Moderasi & Toleransi',
            'categories' => $categories,
            'materials' => $materials
        ]);
    }
    
    public function category($id)
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            $this->redirect('/material');
        }
        
        $materials = $this->materialModel->getByCategory($id);
        
        $this->view('frontend/material/category', [
            'page_title' => 'Materi: ' . $category['name'],
            'category' => $category,
            'materials' => $materials
        ]);
    }
    
    public function detail($slug)
    {
        $material = $this->materialModel->getBySlug($slug);
        
        if (!$material) {
            $this->redirect('/material');
        }
        
        $this->materialModel->incrementViews($material['id']);
        
        $this->view('frontend/material/detail', [
            'page_title' => $material['title'],
            'material' => $material
        ]);
    }
}
