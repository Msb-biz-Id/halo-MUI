<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="<?= asset('assets/frontend/news5/assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #006837;
            --secondary: #FFA500;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary) 0%, #004d28 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .error-container {
            text-align: center;
            color: white;
            padding: 2rem;
        }
        
        .error-code {
            font-size: 120px;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 0 5px 20px rgba(0,0,0,0.3);
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .error-icon {
            font-size: 80px;
            margin-bottom: 2rem;
            opacity: 0.8;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .error-buttons .btn {
            margin: 0.5rem;
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .btn-home {
            background: var(--secondary);
            border: none;
            color: white;
        }
        
        .btn-home:hover {
            background: #ff8c00;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 165, 0, 0.4);
        }
        
        .btn-back {
            background: transparent;
            border: 2px solid white;
            color: white;
        }
        
        .btn-back:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <div class="error-code">404</div>
        
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        
        <p class="error-message">
            Maaf, halaman yang Anda cari tidak dapat ditemukan.<br>
            Halaman mungkin telah dipindahkan atau dihapus.
        </p>
        
        <div class="error-buttons">
            <a href="<?= url('/') ?>" class="btn btn-home">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <button onclick="history.back()" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
    </div>
    
    <script>
        // Optional: Add some interactive effects
        document.addEventListener('mousemove', function(e) {
            const icon = document.querySelector('.error-icon');
            const moveX = (e.clientX / window.innerWidth - 0.5) * 20;
            const moveY = (e.clientY / window.innerHeight - 0.5) * 20;
            icon.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    </script>
</body>
</html>
