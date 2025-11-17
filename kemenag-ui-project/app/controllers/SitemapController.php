<?php

namespace App\Controllers;

use Core\Controller;

class SitemapController extends Controller
{
    public function index()
    {
        header('Content-Type: application/xml; charset=utf-8');
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        
        // Homepage
        echo $this->url(APP_URL, '1.0', 'daily');
        
        // Static pages
        echo $this->url(APP_URL . '/about', '0.8', 'monthly');
        echo $this->url(APP_URL . '/contact', '0.8', 'monthly');
        echo $this->url(APP_URL . '/qa', '0.9', 'daily');
        echo $this->url(APP_URL . '/fatwa', '0.9', 'daily');
        echo $this->url(APP_URL . '/material', '0.9', 'daily');
        echo $this->url(APP_URL . '/books', '0.8', 'weekly');
        echo $this->url(APP_URL . '/forum', '0.8', 'daily');
        
        // Q&A
        $qaModel = $this->model('QuestionAnswer');
        $qaList = $qaModel->getPublished();
        foreach ($qaList as $qa) {
            echo $this->url(APP_URL . '/qa/detail/' . $qa['id'], '0.7', 'weekly');
        }
        
        // Fatwas
        $fatwaModel = $this->model('Fatwa');
        $fatwas = $fatwaModel->getPublished();
        foreach ($fatwas as $fatwa) {
            echo $this->url(APP_URL . '/fatwa/detail/' . $fatwa['slug'], '0.7', 'weekly');
        }
        
        echo '</urlset>';
        exit();
    }
    
    private function url($loc, $priority = '0.5', $changefreq = 'monthly')
    {
        return '<url>' . PHP_EOL .
               '  <loc>' . htmlspecialchars($loc) . '</loc>' . PHP_EOL .
               '  <priority>' . $priority . '</priority>' . PHP_EOL .
               '  <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL .
               '</url>' . PHP_EOL;
    }
}
