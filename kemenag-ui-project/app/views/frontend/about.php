<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold display-4 mb-3">Tentang Kami</h1>
                <p class="lead text-muted">Sistem Informasi Keagamaan - <?= $site_name ?? 'Kemenag UI' ?></p>
            </div>
            
            <!-- About Content -->
            <div class="card border-0 shadow-lg mb-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4"><i class="fas fa-mosque text-success"></i> Selamat Datang</h3>
                    <p class="lead">
                        Portal Kementerian Agama merupakan sistem informasi terpadu yang menyediakan berbagai layanan keagamaan untuk masyarakat Indonesia.
                    </p>
                    
                    <hr class="my-4">
                    
                    <h4 class="fw-bold mb-3"><i class="fas fa-bullseye text-warning"></i> Visi & Misi</h4>
                    
                    <h5 class="fw-semibold mt-4">Visi:</h5>
                    <p>
                        Menjadi pusat informasi keagamaan yang terpercaya dan mudah diakses oleh seluruh masyarakat Indonesia.
                    </p>
                    
                    <h5 class="fw-semibold mt-4">Misi:</h5>
                    <ul>
                        <li>Menyediakan layanan informasi keagamaan yang akurat dan mudah dipahami</li>
                        <li>Memfasilitasi proses sertifikasi halal untuk produk dan usaha</li>
                        <li>Menyelenggarakan forum diskusi keagamaan yang edukatif</li>
                        <li>Menyebarkan materi moderasi dan toleransi beragama</li>
                        <li>Menjaga kualitas layanan dengan teknologi modern</li>
                    </ul>
                    
                    <hr class="my-4">
                    
                    <h4 class="fw-bold mb-3"><i class="fas fa-rocket text-info"></i> Layanan Kami</h4>
                    
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="icon-box bg-success-subtle text-success rounded p-3">
                                        <i class="fas fa-certificate fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Sertifikat Halal</h5>
                                    <p class="text-muted">Layanan permohonan dan tracking sertifikat halal online</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="icon-box bg-info-subtle text-info rounded p-3">
                                        <i class="fas fa-question-circle fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Tanya Jawab</h5>
                                    <p class="text-muted">Konsultasi keagamaan dengan ahli berpengalaman</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="icon-box bg-warning-subtle text-warning rounded p-3">
                                        <i class="fas fa-comments fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Forum Diskusi</h5>
                                    <p class="text-muted">Platform diskusi keagamaan yang moderat</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="icon-box bg-danger-subtle text-danger rounded p-3">
                                        <i class="fas fa-balance-scale fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Informasi Fatwa</h5>
                                    <p class="text-muted">Kumpulan fatwa resmi yang mudah diakses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact CTA -->
            <div class="text-center">
                <h4 class="fw-bold mb-3">Butuh Bantuan?</h4>
                <p class="text-muted mb-4">Tim kami siap membantu Anda</p>
                <a href="<?= url('/contact') ?>" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-envelope"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
