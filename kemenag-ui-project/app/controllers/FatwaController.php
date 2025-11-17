<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Fatwa;
use App\Models\Category;

class FatwaController extends Controller
{
    private $fatwaModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->fatwaModel = $this->model('Fatwa');
        $this->categoryModel = $this->model('Category');
    }
    
    public function index()
    {
        $categories = $this->categoryModel->getByType('fatwa');
        $fatwas = $this->fatwaModel->getPublished();
        $popular = $this->fatwaModel->getPopular(10);
        
        $this->view('frontend/fatwa/index', [
            'page_title' => 'Informasi Fatwa',
            'categories' => $categories,
            'fatwas' => $fatwas,
            'popular' => $popular
        ]);
    }
    
    public function category($id)
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            $this->redirect('/fatwa');
        }
        
        $fatwas = $this->fatwaModel->getByCategory($id);
        
        $this->view('frontend/fatwa/category', [
            'page_title' => 'Fatwa: ' . $category['name'],
            'category' => $category,
            'fatwas' => $fatwas
        ]);
    }
    
    public function detail($slug)
    {
        $fatwa = $this->fatwaModel->getBySlug($slug);
        
        if (!$fatwa) {
            $this->redirect('/fatwa');
        }
        
        $this->fatwaModel->incrementViews($fatwa['id']);
        
        $related = $this->fatwaModel->query(
            "SELECT * FROM fatwas WHERE category_id = :cat_id AND id != :id AND is_published = 1 LIMIT 5",
            [':cat_id' => $fatwa['category_id'], ':id' => $fatwa['id']]
        )->fetchAll();
        
        $this->view('frontend/fatwa/detail', [
            'page_title' => $fatwa['title'],
            'fatwa' => $fatwa,
            'related' => $related
        ]);
    }
}
