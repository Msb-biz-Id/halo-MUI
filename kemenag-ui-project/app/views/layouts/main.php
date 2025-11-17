<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= $page_title ?? 'Website Magang Kemenag UI' ?> - Kementerian Agama RI</title>
    <meta name="description" content="<?= $meta_description ?? 'Sistem Informasi Keagamaan dengan Help Desk Sertifikat Halal' ?>">
    <meta name="keywords" content="<?= $meta_keywords ?? 'kemenag, sertifikat halal, tanya jawab keagamaan, fatwa, moderasi' ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= $page_title ?? 'Website Magang Kemenag UI' ?>">
    <meta property="og:description" content="<?= $meta_description ?? 'Sistem Informasi Keagamaan' ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= url($_SERVER['REQUEST_URI']) ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="<?= asset('favicon.ico') ?>" type="image/x-icon">
    
    <!-- CSS from News5 Template -->
    <link rel="stylesheet" href="<?= asset('assets/frontend/news5/assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/frontend/news5/assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/frontend/news5/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/frontend/news5/assets/css/responsive.css') ?>">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #006837;
            --secondary-color: #FFA500;
            --text-dark: #333;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #004d28;
            border-color: #004d28;
        }
        
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
    </style>
    
    <?php if (isset($additional_css)): ?>
        <?= $additional_css ?>
    <?php endif; ?>
</head>
<body>
    
    <!-- Header / Navigation -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="<?= url('/') ?>">
                    <img src="<?= asset('assets/images/logo-kemenag.png') ?>" alt="Logo" height="40" class="me-2">
                    <span>Kemenag UI</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/') ?>">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/certificate') ?>">Sertifikat Halal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/qa') ?>">Tanya Jawab</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/fatwa') ?>">Fatwa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/material') ?>">Materi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/book') ?>">Perpustakaan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/forum') ?>">Forum</a>
                        </li>
                        
                        <?php if (auth()): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> <?= user('username') ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= url('/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?= url('/profile') ?>"><i class="fas fa-user"></i> Profil</a></li>
                                    <li><a class="dropdown-item" href="<?= url('/notifications') ?>"><i class="fas fa-bell"></i> Notifikasi</a></li>
                                    <li><a class="dropdown-item" href="<?= url('/messages') ?>"><i class="fas fa-envelope"></i> Pesan</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <?php if (user('role_name') === 'superadmin' || user('role_name') === 'admin_konten' || user('role_name') === 'admin_sertifikat'): ?>
                                        <li><a class="dropdown-item" href="<?= url('/admin') ?>"><i class="fas fa-cog"></i> Admin Panel</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    <?php endif; ?>
                                    <li>
                                        <form action="<?= url('/logout') ?>" method="POST" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('/login') ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-primary btn-sm ms-2" href="<?= url('/register') ?>">Daftar</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="flash-message">
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>
    
    <!-- Footer -->
    <footer class="footer bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Tentang Kami</h5>
                    <p>Sistem Informasi Keagamaan Kementerian Agama Republik Indonesia dengan layanan Help Desk Sertifikat Halal.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= url('/certificate') ?>" class="text-white-50">Sertifikat Halal</a></li>
                        <li><a href="<?= url('/qa') ?>" class="text-white-50">Tanya Jawab</a></li>
                        <li><a href="<?= url('/fatwa') ?>" class="text-white-50">Fatwa</a></li>
                        <li><a href="<?= url('/forum') ?>" class="text-white-50">Forum Diskusi</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope"></i> info@kemenag.go.id</li>
                        <li><i class="fas fa-phone"></i> (021) 123-4567</li>
                        <li><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> Kementerian Agama RI. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="<?= asset('assets/frontend/news5/assets/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= asset('assets/frontend/news5/assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('assets/frontend/news5/assets/js/main.js') ?>"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            $('.flash-message .alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    </script>
    
    <?php if (isset($additional_js)): ?>
        <?= $additional_js ?>
    <?php endif; ?>
</body>
</html>
