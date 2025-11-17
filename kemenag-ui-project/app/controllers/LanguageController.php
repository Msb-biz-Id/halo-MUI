<?php

namespace App\Controllers;

use Core\Controller;

/**
 * Language Controller
 * Handle language switching
 */
class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch($lang)
    {
        // Validate language
        $allowedLanguages = ['en', 'id', 'ar'];
        
        if (!in_array($lang, $allowedLanguages)) {
            $lang = 'id'; // Default to Indonesian
        }
        
        // Set language in session
        $_SESSION['language'] = $lang;
        
        // Set cookie for persistence
        setcookie('language', $lang, time() + (86400 * 365), '/');
        
        // Redirect back to previous page or home
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        header('Location: ' . $referer);
        exit;
    }
}
