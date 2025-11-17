<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    private $bookModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->bookModel = $this->model('Book');
        $this->categoryModel = $this->model('Category');
    }
    
    public function index()
    {
        $categories = $this->categoryModel->getByType('buku');
        $categoryId = $_GET['category'] ?? null;
        $year = $_GET['year'] ?? null;
        
        $books = $this->bookModel->filterBooks($categoryId, $year);
        
        $this->view('frontend/book/index', [
            'page_title' => 'Perpustakaan Digital',
            'categories' => $categories,
            'books' => $books,
            'selected_category' => $categoryId,
            'selected_year' => $year
        ]);
    }
    
    public function read($id)
    {
        $book = $this->bookModel->findById($id);
        
        if (!$book) {
            $this->setFlash('error', 'Buku tidak ditemukan');
            $this->redirect('/books');
        }
        
        $this->bookModel->incrementViews($id);
        
        $this->view('frontend/book/read', [
            'page_title' => $book['title'],
            'book' => $book
        ]);
    }
    
    public function download($id)
    {
        $book = $this->bookModel->findById($id);
        
        if (!$book) {
            $this->setFlash('error', 'Buku tidak ditemukan');
            $this->redirect('/books');
        }
        
        $this->bookModel->incrementDownload($id);
        
        $filepath = PUBLIC_PATH . '/' . $book['file_path'];
        
        if (file_exists($filepath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $book['title'] . '.pdf"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit();
        } else {
            $this->setFlash('error', 'File tidak ditemukan');
            $this->redirect('/books');
        }
    }
}
