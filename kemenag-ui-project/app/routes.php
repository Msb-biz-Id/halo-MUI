<?php

/**
 * Application Routes
 * Define all routes for the application
 */

$routes = [
    // Frontend Routes
    '' => ['controller' => 'HomeController', 'action' => 'index'],
    'home' => ['controller' => 'HomeController', 'action' => 'index'],
    
    // Authentication Routes
    'auth/login' => ['controller' => 'AuthController', 'action' => 'login'],
    'auth/register' => ['controller' => 'AuthController', 'action' => 'register'],
    'auth/logout' => ['controller' => 'AuthController', 'action' => 'logout'],
    'auth/forgot-password' => ['controller' => 'AuthController', 'action' => 'forgotPassword'],
    'auth/reset-password/{token}' => ['controller' => 'AuthController', 'action' => 'resetPassword'],
    'auth/verify-email/{token}' => ['controller' => 'AuthController', 'action' => 'verifyEmail'],
    'auth/mfa' => ['controller' => 'AuthController', 'action' => 'mfa'],
    
    // User Dashboard
    'dashboard' => ['controller' => 'DashboardController', 'action' => 'index'],
    'profile' => ['controller' => 'ProfileController', 'action' => 'index'],
    'profile/edit' => ['controller' => 'ProfileController', 'action' => 'edit'],
    'profile/change-password' => ['controller' => 'ProfileController', 'action' => 'changePassword'],
    
    // Tanya Jawab Routes
    'qa' => ['controller' => 'QaController', 'action' => 'index'],
    'qa/category/{id}' => ['controller' => 'QaController', 'action' => 'category'],
    'qa/detail/{id}' => ['controller' => 'QaController', 'action' => 'detail'],
    
    // Fatwa Routes
    'fatwa' => ['controller' => 'FatwaController', 'action' => 'index'],
    'fatwa/category/{id}' => ['controller' => 'FatwaController', 'action' => 'category'],
    'fatwa/detail/{id}' => ['controller' => 'FatwaController', 'action' => 'detail'],
    
    // Material Routes
    'material' => ['controller' => 'MaterialController', 'action' => 'index'],
    'material/category/{id}' => ['controller' => 'MaterialController', 'action' => 'category'],
    'material/detail/{id}' => ['controller' => 'MaterialController', 'action' => 'detail'],
    
    // Books/Library Routes
    'books' => ['controller' => 'BookController', 'action' => 'index'],
    'books/read/{id}' => ['controller' => 'BookController', 'action' => 'read'],
    'books/download/{id}' => ['controller' => 'BookController', 'action' => 'download'],
    
    // Certificate Application Routes
    'certificate' => ['controller' => 'CertificateController', 'action' => 'index'],
    'certificate/apply' => ['controller' => 'CertificateController', 'action' => 'apply'],
    'certificate/track/{ticket}' => ['controller' => 'CertificateController', 'action' => 'track'],
    'certificate/detail/{id}' => ['controller' => 'CertificateController', 'action' => 'detail'],
    'certificate/download/{id}' => ['controller' => 'CertificateController', 'action' => 'download'],
    
    // Forum Routes
    'forum' => ['controller' => 'ForumController', 'action' => 'index'],
    'forum/category/{id}' => ['controller' => 'ForumController', 'action' => 'category'],
    'forum/topic/{id}' => ['controller' => 'ForumController', 'action' => 'topic'],
    'forum/create-topic' => ['controller' => 'ForumController', 'action' => 'createTopic'],
    'forum/reply/{id}' => ['controller' => 'ForumController', 'action' => 'reply'],
    
    // Internal Chat Routes
    'chat' => ['controller' => 'InternalChatController', 'action' => 'index'],
    'chat/conversation/{id}' => ['controller' => 'InternalChatController', 'action' => 'conversation'],
    'chat/send' => ['controller' => 'InternalChatController', 'action' => 'send'],
    
    // Notification Routes
    'notifications' => ['controller' => 'NotificationController', 'action' => 'index'],
    'notifications/mark-read/{id}' => ['controller' => 'NotificationController', 'action' => 'markRead'],
    'notifications/mark-all-read' => ['controller' => 'NotificationController', 'action' => 'markAllRead'],
    
    // Search Routes
    'search' => ['controller' => 'SearchController', 'action' => 'index'],
    
    // Chatbot Routes
    'chatbot' => ['controller' => 'ChatbotController', 'action' => 'index'],
    'chatbot/send' => ['controller' => 'ChatbotController', 'action' => 'send'],
    
    // WhatsApp Webhook
    'whatsapp/webhook' => ['controller' => 'WhatsappController', 'action' => 'webhook'],
    
    // SEO Routes
    'sitemap.xml' => ['controller' => 'SitemapController', 'action' => 'index'],
    'robots.txt' => ['controller' => 'RobotsController', 'action' => 'index'],
    
    // Language Routes
    'language/switch/{lang}' => ['controller' => 'LanguageController', 'action' => 'switch'],
    
    // Admin Routes
    'admin' => ['controller' => 'DashboardController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/dashboard' => ['controller' => 'DashboardController', 'action' => 'index', 'namespace' => 'Admin'],
    
    // Admin - User Management
    'admin/users' => ['controller' => 'UserController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/users/create' => ['controller' => 'UserController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/users/edit/{id}' => ['controller' => 'UserController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/users/delete/{id}' => ['controller' => 'UserController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Role Management
    'admin/roles' => ['controller' => 'RoleController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/roles/create' => ['controller' => 'RoleController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/roles/edit/{id}' => ['controller' => 'RoleController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/roles/delete/{id}' => ['controller' => 'RoleController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Q&A Management (FIXED ROUTE!)
    'admin/qa' => ['controller' => 'QuestionAnswerController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/qa/create' => ['controller' => 'QuestionAnswerController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/qa/edit/{id}' => ['controller' => 'QuestionAnswerController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/qa/delete/{id}' => ['controller' => 'QuestionAnswerController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Fatwa Management (FIXED ROUTE!)
    'admin/fatwa' => ['controller' => 'FatwaController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/fatwa/create' => ['controller' => 'FatwaController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/fatwa/edit/{id}' => ['controller' => 'FatwaController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/fatwa/delete/{id}' => ['controller' => 'FatwaController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Material Management (FIXED ROUTE!)
    'admin/material' => ['controller' => 'MaterialController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/material/create' => ['controller' => 'MaterialController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/material/edit/{id}' => ['controller' => 'MaterialController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/material/delete/{id}' => ['controller' => 'MaterialController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Book Management
    'admin/books' => ['controller' => 'BookController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/books/create' => ['controller' => 'BookController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/books/edit/{id}' => ['controller' => 'BookController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/books/delete/{id}' => ['controller' => 'BookController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Certificate Management
    'admin/certificates' => ['controller' => 'CertificateController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/certificates/view/{id}' => ['controller' => 'CertificateController', 'action' => 'view', 'namespace' => 'Admin'],
    'admin/certificates/assign/{id}' => ['controller' => 'CertificateController', 'action' => 'assign', 'namespace' => 'Admin'],
    'admin/certificates/approve/{id}' => ['controller' => 'CertificateController', 'action' => 'approve', 'namespace' => 'Admin'],
    'admin/certificates/reject/{id}' => ['controller' => 'CertificateController', 'action' => 'reject', 'namespace' => 'Admin'],
    'admin/certificates/generate/{id}' => ['controller' => 'CertificateController', 'action' => 'generate', 'namespace' => 'Admin'],
    
    // Admin - Media Management
    'admin/media' => ['controller' => 'MediaController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/media/upload' => ['controller' => 'MediaController', 'action' => 'upload', 'namespace' => 'Admin'],
    'admin/media/delete/{id}' => ['controller' => 'MediaController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Settings
    'admin/settings' => ['controller' => 'SettingController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/settings/update' => ['controller' => 'SettingController', 'action' => 'update', 'namespace' => 'Admin'],
    
    // Admin - MFA
    'admin/mfa/setup' => ['controller' => 'MfaController', 'action' => 'setup', 'namespace' => 'Admin'],
    'admin/mfa/enable' => ['controller' => 'MfaController', 'action' => 'enable', 'namespace' => 'Admin'],
    'admin/mfa/disable' => ['controller' => 'MfaController', 'action' => 'disable', 'namespace' => 'Admin'],
    
    // Admin - Audit Logs
    'admin/audit-logs' => ['controller' => 'AuditLogController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/audit-logs/export' => ['controller' => 'AuditLogController', 'action' => 'export', 'namespace' => 'Admin'],
    
    // Admin - Translations
    'admin/translations' => ['controller' => 'TranslationController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/translations/create' => ['controller' => 'TranslationController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/translations/edit/{id}' => ['controller' => 'TranslationController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/translations/delete/{id}' => ['controller' => 'TranslationController', 'action' => 'delete', 'namespace' => 'Admin'],
    
    // Admin - Forum Moderation
    'admin/forum' => ['controller' => 'ForumController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/forum/view/{id}' => ['controller' => 'ForumController', 'action' => 'view', 'namespace' => 'Admin'],
    'admin/forum/approve/{id}' => ['controller' => 'ForumController', 'action' => 'approve', 'namespace' => 'Admin'],
    'admin/forum/reject/{id}' => ['controller' => 'ForumController', 'action' => 'reject', 'namespace' => 'Admin'],
    'admin/forum/delete/{id}' => ['controller' => 'ForumController', 'action' => 'delete', 'namespace' => 'Admin'],
    'admin/forum/toggle-lock/{id}' => ['controller' => 'ForumController', 'action' => 'toggleLock', 'namespace' => 'Admin'],
    'admin/forum/toggle-pin/{id}' => ['controller' => 'ForumController', 'action' => 'togglePin', 'namespace' => 'Admin'],
    'admin/forum/delete-post/{id}' => ['controller' => 'ForumController', 'action' => 'deletePost', 'namespace' => 'Admin'],
    
    // Admin - Word Blacklist
    'admin/blacklist' => ['controller' => 'WordBlacklistController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/blacklist/create' => ['controller' => 'WordBlacklistController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/blacklist/edit/{id}' => ['controller' => 'WordBlacklistController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/blacklist/delete/{id}' => ['controller' => 'WordBlacklistController', 'action' => 'delete', 'namespace' => 'Admin'],
    'admin/blacklist/toggle-active/{id}' => ['controller' => 'WordBlacklistController', 'action' => 'toggleActive', 'namespace' => 'Admin'],
    'admin/blacklist/bulk-add' => ['controller' => 'WordBlacklistController', 'action' => 'bulkAdd', 'namespace' => 'Admin'],
    'admin/blacklist/detection-logs' => ['controller' => 'WordBlacklistController', 'action' => 'detectionLogs', 'namespace' => 'Admin'],
    'admin/blacklist/test' => ['controller' => 'WordBlacklistController', 'action' => 'test', 'namespace' => 'Admin'],
    
    // Admin - Category Management
    'admin/categories' => ['controller' => 'CategoryController', 'action' => 'index', 'namespace' => 'Admin'],
    'admin/categories/create' => ['controller' => 'CategoryController', 'action' => 'create', 'namespace' => 'Admin'],
    'admin/categories/edit/{id}' => ['controller' => 'CategoryController', 'action' => 'edit', 'namespace' => 'Admin'],
    'admin/categories/delete/{id}' => ['controller' => 'CategoryController', 'action' => 'delete', 'namespace' => 'Admin'],
];

return $routes;
