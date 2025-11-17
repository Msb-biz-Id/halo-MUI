<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $subject ?? 'Email dari Kemenag UI' ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #006837 0%, #004d28 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .email-header img {
            max-width: 80px;
            margin-bottom: 15px;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .email-body {
            padding: 30px 20px;
        }
        
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #006837;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .btn:hover {
            background: #004d28;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
        
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        
        .success-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        
        table td:first-child {
            font-weight: 600;
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <img src="<?= APP_URL ?>/assets/images/logo-white.png" alt="Logo Kemenag">
            <h1>Kementerian Agama RI</h1>
        </div>
        
        <div class="email-body">
            <?= $content ?? '' ?>
        </div>
        
        <div class="email-footer">
            <p><strong>Kementerian Agama Republik Indonesia</strong></p>
            <p>
                Email: <?= CONTACT_EMAIL ?><br>
                Telepon: <?= CONTACT_PHONE ?><br>
                Website: <a href="<?= APP_URL ?>"><?= APP_URL ?></a>
            </p>
            <p style="color: #999; font-size: 11px;">
                Email ini dikirim secara otomatis. Mohon tidak membalas email ini.<br>
                © <?= date('Y') ?> Kementerian Agama RI. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
