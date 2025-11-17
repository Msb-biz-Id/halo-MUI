<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Site Under Maintenance' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        
        .icon {
            font-size: 100px;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }
        
        p {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .timer {
            background: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }
        
        .time {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
        }
        
        .label {
            font-size: 14px;
            color: #999;
            margin-top: 10px;
        }
        
        .progress-bar {
            width: 100%;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 30px;
            overflow: hidden;
        }
        
        .progress {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            animation: progress 3s infinite;
        }
        
        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔧</div>
        <h1><?= htmlspecialchars($title ?? 'Site Under Maintenance') ?></h1>
        <p><?= nl2br(htmlspecialchars($message ?? 'We are currently performing scheduled maintenance. We will be back soon!')) ?></p>
        
        <?php if (isset($retry_after) && $retry_after > 0): ?>
        <div class="timer">
            <div class="time" id="countdown"><?= gmdate('H:i:s', $retry_after) ?></div>
            <div class="label">Estimated time remaining</div>
        </div>
        <?php endif; ?>
        
        <div class="progress-bar">
            <div class="progress"></div>
        </div>
    </div>

    <?php if (isset($retry_after) && $retry_after > 0): ?>
    <script>
        let seconds = <?= $retry_after ?>;
        const countdown = document.getElementById('countdown');
        
        setInterval(() => {
            if (seconds > 0) {
                seconds--;
                const hours = Math.floor(seconds / 3600);
                const minutes = Math.floor((seconds % 3600) / 60);
                const secs = seconds % 60;
                countdown.textContent = 
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(secs).padStart(2, '0');
            } else {
                location.reload();
            }
        }, 1000);
    </script>
    <?php endif; ?>
</body>
</html>
