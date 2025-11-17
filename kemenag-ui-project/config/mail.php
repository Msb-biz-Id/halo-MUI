<?php
/**
 * Mail Configuration
 * Email sending settings using PHPMailer
 */

return [
    'default' => env('MAIL_MAILER', 'smtp'),
    
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'), // tls or ssl
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => 30,
        ],
        
        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs'),
        ],
        
        'log' => [
            'transport' => 'log',
            'channel' => 'mail',
        ],
    ],
    
    // Global "From" Address
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@kemenag.go.id'),
        'name' => env('MAIL_FROM_NAME', 'Kemenag Halal Certification'),
    ],
    
    // Global "Reply-To" Address
    'reply_to' => [
        'address' => env('MAIL_REPLY_TO_ADDRESS', 'support@kemenag.go.id'),
        'name' => env('MAIL_REPLY_TO_NAME', 'Kemenag Support'),
    ],
    
    // Markdown Mail Settings
    'markdown' => [
        'theme' => 'default',
        'paths' => [
            __DIR__ . '/../app/views/emails',
        ],
    ],
];
