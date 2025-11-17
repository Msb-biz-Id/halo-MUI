<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Panel' ?> - Kemenag UI</title>
    
    <!-- Morvin Admin Template CSS -->
    <link rel="stylesheet" href="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/css/icons.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/css/app.min.css') ?>">
    
    <style>
        :root {
            --primary: #006837;
            --secondary: #FFA500;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #004d28;
            border-color: #004d28;
        }
    </style>
</head>
<body data-layout="vertical" data-topbar="dark" data-sidebar="dark">
    
    <div id="layout-wrapper">
        
        <!-- Top Bar -->
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- Logo -->
                    <div class="navbar-brand-box">
                        <a href="<?= url('/admin') ?>" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="<?= asset('assets/images/logo-sm.png') ?>" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= asset('assets/images/logo-dark.png') ?>" alt="" height="20">
                            </span>
                        </a>
                        
                        <a href="<?= url('/admin') ?>" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="<?= asset('assets/images/logo-sm.png') ?>" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <h4 class="text-white mb-0">Kemenag UI</h4>
                            </span>
                        </a>
                    </div>
                    
                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>
                
                <div class="d-flex">
                    
                    <!-- Notifications -->
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown" data-bs-toggle="dropdown">
                            <i class="uil-bell"></i>
                            <span class="badge bg-danger rounded-pill">5</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0">Notifikasi</h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#!" class="small">Lihat Semua</a>
                                    </div>
                                </div>
                            </div>
                            <div style="max-height: 230px;" data-simplebar>
                                <a href="#" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="uil-certificate text-success font-size-24"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Pengajuan Sertifikat Baru</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-0">5 menit yang lalu</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Profile -->
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown">
                            <img class="rounded-circle header-profile-user" src="<?= asset('assets/images/avatar-default.png') ?>" alt="Avatar">
                            <span class="d-none d-xl-inline-block ms-1 fw-medium"><?= user('full_name') ?></span>
                            <i class="uil-angle-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="<?= url('/profile') ?>">
                                <i class="uil uil-user-circle font-size-18 align-middle text-muted me-1"></i>
                                <span class="align-middle">Profil</span>
                            </a>
                            <a class="dropdown-item" href="<?= url('/') ?>">
                                <i class="uil uil-estate font-size-18 align-middle me-1 text-muted"></i>
                                <span class="align-middle">Ke Beranda</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="uil uil-sign-out-alt font-size-18 align-middle me-1 text-danger"></i>
                                <span class="align-middle">Logout</span>
                            </a>
                            
                            <form id="logout-form" action="<?= url('/logout') ?>" method="POST" style="display: none;">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Sidebar -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <ul class="metismenu list-unstyled" id="side-menu">
                        
                        <li class="menu-title">Menu</li>
                        
                        <li>
                            <a href="<?= url('/admin') ?>">
                                <i class="uil-home-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        
                        <?php if (hasPermission('certificate_management')): ?>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="uil-certificate"></i>
                                <span>Sertifikat Halal</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="<?= url('/admin/certificates') ?>">Semua Pengajuan</a></li>
                                <li><a href="<?= url('/admin/certificates?status=pending') ?>">Menunggu Review</a></li>
                                <li><a href="<?= url('/admin/certificates?status=approved') ?>">Disetujui</a></li>
                                <li><a href="<?= url('/admin/certificates/dashboard') ?>">Statistik</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (hasPermission('content_management')): ?>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="uil-file-alt"></i>
                                <span>Konten</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="<?= url('/admin/qa') ?>">Tanya Jawab</a></li>
                                <li><a href="<?= url('/admin/fatwa') ?>">Fatwa</a></li>
                                <li><a href="<?= url('/admin/material') ?>">Materi</a></li>
                                <li><a href="<?= url('/admin/books') ?>">Buku</a></li>
                                <li><a href="<?= url('/admin/categories') ?>">Kategori</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (hasPermission('media_management')): ?>
                        <li>
                            <a href="<?= url('/admin/media') ?>">
                                <i class="uil-images"></i>
                                <span>Media</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (hasPermission('user_management')): ?>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="uil-users-alt"></i>
                                <span>Pengguna</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="<?= url('/admin/users') ?>">Semua Pengguna</a></li>
                                <li><a href="<?= url('/admin/roles') ?>">Role & Permission</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (hasPermission('system_settings')): ?>
                        <li class="menu-title">Sistem</li>
                        
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="uil-cog"></i>
                                <span>Pengaturan</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="<?= url('/admin/settings/general') ?>">Umum</a></li>
                                <li><a href="<?= url('/admin/settings/email') ?>">Email</a></li>
                                <li><a href="<?= url('/admin/settings/seo') ?>">SEO</a></li>
                                <li><a href="<?= url('/admin/settings/cache') ?>">Cache</a></li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="<?= url('/admin/audit') ?>">
                                <i class="uil-file-check-alt"></i>
                                <span>Audit Log</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    
                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0"><?= $page_title ?? 'Dashboard' ?></h4>
                                
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Admin</a></li>
                                        <?php if (isset($breadcrumbs)): ?>
                                            <?php foreach ($breadcrumbs as $crumb): ?>
                                                <li class="breadcrumb-item <?= $crumb['active'] ?? false ? 'active' : '' ?>">
                                                    <?php if (!empty($crumb['url']) && !($crumb['active'] ?? false)): ?>
                                                        <a href="<?= $crumb['url'] ?>"><?= $crumb['title'] ?></a>
                                                    <?php else: ?>
                                                        <?= $crumb['title'] ?>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="breadcrumb-item active"><?= $page_title ?? 'Dashboard' ?></li>
                                        <?php endif; ?>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Flash Messages -->
                    <?php if (isset($_SESSION['flash'])): ?>
                        <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                            <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                                <?= $message ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endforeach; ?>
                        <?php unset($_SESSION['flash']); ?>
                    <?php endif; ?>
                    
                    <!-- Content -->
                    <?= $content ?? '' ?>
                    
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= date('Y') ?> © Kemenag UI.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Admin Panel
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/libs/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/libs/metismenu/metisMenu.min.js') ?>"></script>
    <script src="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/libs/simplebar/simplebar.min.js') ?>"></script>
    <script src="<?= asset('assets/admin/Morvin_HTML_v1.2.0/HTML/dist/assets/js/app.js') ?>"></script>
    
    <script>
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
