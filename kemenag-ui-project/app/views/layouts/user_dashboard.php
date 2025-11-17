<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'User Dashboard' ?> - Kemenag UI</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= asset('assets/frontend/news5/assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/frontend/news5/assets/css/all.min.css') ?>">
    
    <style>
        :root {
            --primary-color: #006837;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #006837 0%, #004d28 100%);
            color: white;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid #FFA500;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .content-wrapper {
            padding: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .icon-primary { background: rgba(0,104,55,0.1); color: var(--primary-color); }
        .icon-success { background: rgba(40,167,69,0.1); color: #28a745; }
        .icon-warning { background: rgba(255,193,7,0.1); color: #ffc107; }
        .icon-info { background: rgba(23,162,184,0.1); color: #17a2b8; }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4>User Dashboard</h4>
            <p class="mb-0 small"><?= user('full_name') ?></p>
            <small class="text-white-50"><?= user('role_name') ?></small>
        </div>
        
        <div class="sidebar-menu">
            <a href="<?= url('/dashboard') ?>" class="<?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            
            <a href="<?= url('/dashboard/certificates') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/dashboard/certificates') !== false ? 'active' : '' ?>">
                <i class="fas fa-certificate me-2"></i> Sertifikat Saya
            </a>
            
            <a href="<?= url('/dashboard/forum-topics') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/dashboard/forum-topics') !== false ? 'active' : '' ?>">
                <i class="fas fa-comments me-2"></i> Topik Forum
            </a>
            
            <a href="<?= url('/dashboard/messages') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/dashboard/messages') !== false ? 'active' : '' ?>">
                <i class="fas fa-envelope me-2"></i> Pesan
            </a>
            
            <a href="<?= url('/dashboard/notifications') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/dashboard/notifications') !== false ? 'active' : '' ?>">
                <i class="fas fa-bell me-2"></i> Notifikasi
            </a>
            
            <a href="<?= url('/profile') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/profile') !== false ? 'active' : '' ?>">
                <i class="fas fa-user me-2"></i> Profil
            </a>
            
            <hr class="my-3" style="border-color: rgba(255,255,255,0.1)">
            
            <a href="<?= url('/') ?>">
                <i class="fas fa-globe me-2"></i> Ke Beranda
            </a>
            
            <a href="<?= url('/logout') ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
            
            <form id="logout-form" action="<?= url('/logout') ?>" method="POST" style="display: none;">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0 d-inline"><?= $page_title ?? 'Dashboard' ?></h5>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?= user('username') ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= url('/profile') ?>">Profil</a></li>
                        <li><a class="dropdown-item" href="<?= url('/profile/security') ?>">Keamanan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="px-4 pt-3">
                <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                    <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="content-wrapper">
            <?= $content ?? '' ?>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="<?= asset('assets/frontend/news5/assets/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= asset('assets/frontend/news5/assets/js/bootstrap.bundle.min.js') ?>"></script>
    
    <script>
        // Sidebar toggle for mobile
        $('#sidebarToggle').click(function() {
            $('#sidebar').toggleClass('show');
        });
        
        // Auto-hide flash messages
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
    
    <?php if (isset($additional_js)): ?>
        <?= $additional_js ?>
    <?php endif; ?>
</body>
</html>
