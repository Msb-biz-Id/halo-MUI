<!-- Hero Section -->
<section class="hero bg-gradient text-white py-5" style="background: linear-gradient(135deg, #006837 0%, #004d28 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Selamat Datang di Portal Kemenag UI</h1>
                <p class="lead mb-4">Sistem Informasi Keagamaan dengan layanan Help Desk Sertifikat Halal yang terintegrasi</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= url('/certificate/apply') ?>" class="btn btn-warning btn-lg">
                        <i class="fas fa-certificate"></i> Ajukan Sertifikat Halal
                    </a>
                    <a href="<?= url('/certificate/track') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search"></i> Lacak Sertifikat
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <i class="fas fa-mosque" style="font-size: 150px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features py-5">
    <div class="container">
        <div class="row text-center mb-4">
            <div class="col-12">
                <h2 class="fw-bold">Layanan Kami</h2>
                <p class="text-muted">Berbagai layanan informasi keagamaan untuk Anda</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: rgba(0,104,55,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-certificate text-success" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold">Sertifikat Halal</h5>
                        <p class="text-muted">Ajukan dan lacak permohonan sertifikat halal Anda secara online</p>
                        <a href="<?= url('/certificate') ?>" class="btn btn-outline-success">Selengkapnya →</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: rgba(23,162,184,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-question-circle text-info" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold">Tanya Jawab Keagamaan</h5>
                        <p class="text-muted">Temukan jawaban atas pertanyaan keagamaan Anda</p>
                        <a href="<?= url('/qa') ?>" class="btn btn-outline-info">Selengkapnya →</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: rgba(255,193,7,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-comments text-warning" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold">Forum Diskusi</h5>
                        <p class="text-muted">Bergabung dalam diskusi dengan komunitas</p>
                        <a href="<?= url('/forum') ?>" class="btn btn-outline-warning">Selengkapnya →</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: rgba(220,53,69,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-balance-scale text-danger" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold">Informasi Fatwa</h5>
                        <p class="text-muted">Akses koleksi fatwa terlengkap</p>
                        <a href="<?= url('/fatwa') ?>" class="btn btn-outline-danger">Selengkapnya →</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: rgba(111,66,193,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-book-reader text-primary" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold">Materi Moderasi</h5>
                        <p class="text-muted">Pelajari materi moderasi dan toleransi beragama</p>
                        <a href="<?= url('/material') ?>" class="btn btn-outline-primary">Selengkapnya →</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: rgba(40,167,69,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-book text-success" style="font-size: 36px;"></i>
                        </div>
                        <h5 class="fw-bold">Perpustakaan Digital</h5>
                        <p class="text-muted">Akses ribuan buku keagamaan digital</p>
                        <a href="<?= url('/book') ?>" class="btn btn-outline-success">Selengkapnya →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Content Section -->
<section class="latest-content py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <h3 class="fw-bold mb-3"><i class="fas fa-question-circle text-info"></i> Tanya Jawab Terbaru</h3>
                
                <?php if (!empty($latest_qa)): ?>
                    <div class="list-group">
                        <?php foreach (array_slice($latest_qa, 0, 5) as $qa): ?>
                            <a href="<?= url('/qa/' . $qa['slug']) ?>" class="list-group-item list-group-item-action">
                                <h6 class="mb-1"><?= htmlspecialchars($qa['question']) ?></h6>
                                <p class="mb-1 small text-muted"><?= truncate($qa['answer'], 100) ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-eye"></i> <?= $qa['view_count'] ?> views
                                </small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Belum ada tanya jawab</p>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-6 mb-4">
                <h3 class="fw-bold mb-3"><i class="fas fa-balance-scale text-danger"></i> Fatwa Populer</h3>
                
                <?php if (!empty($popular_fatwa)): ?>
                    <div class="list-group">
                        <?php foreach (array_slice($popular_fatwa, 0, 5) as $fatwa): ?>
                            <a href="<?= url('/fatwa/' . $fatwa['slug']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($fatwa['title']) ?></h6>
                                    <?php if (!empty($fatwa['fatwa_number'])): ?>
                                        <span class="badge bg-secondary"><?= $fatwa['fatwa_number'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="mb-1 small text-muted"><?= truncate($fatwa['summary'], 100) ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Belum ada fatwa</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Butuh Bantuan?</h2>
        <p class="lead mb-4">Tim kami siap membantu Anda dengan pertanyaan atau permohonan sertifikat halal</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="<?= url('/contact') ?>" class="btn btn-light btn-lg">
                <i class="fas fa-envelope"></i> Hubungi Kami
            </a>
            <a href="<?= url('/register') ?>" class="btn btn-warning btn-lg">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </a>
        </div>
    </div>
</section>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>
