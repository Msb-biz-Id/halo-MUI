<?php

namespace App\Controllers;

use Core\Controller;

class RobotsController extends Controller
{
    public function index()
    {
        header('Content-Type: text/plain');
        
        echo "User-agent: *" . PHP_EOL;
        echo "Allow: /" . PHP_EOL;
        echo "Disallow: /admin/" . PHP_EOL;
        echo "Disallow: /api/" . PHP_EOL;
        echo "Disallow: /dashboard/" . PHP_EOL;
        echo "Disallow: /profile/" . PHP_EOL;
        echo "Disallow: /uploads/private/" . PHP_EOL;
        echo PHP_EOL;
        echo "Sitemap: " . APP_URL . "/sitemap.xml" . PHP_EOL;
        
        exit();
    }
}
