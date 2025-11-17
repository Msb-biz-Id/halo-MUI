<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold display-4 mb-3">Hubungi Kami</h1>
                <p class="lead text-muted">Kami siap membantu Anda. Silakan isi form di bawah ini</p>
            </div>
            
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-envelope"></i> Form Kontak</h5>
                        </div>
                        
                        <div class="card-body p-4">
                            <form action="<?= url('/contact') ?>" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                               value="<?= old('name') ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                               value="<?= old('email') ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <input type="text" class="form-control form-control-lg" id="subject" name="subject" 
                                           value="<?= old('subject') ?>" placeholder="Contoh: Pertanyaan tentang sertifikat halal">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="6" 
                                              placeholder="Tulis pesan Anda di sini..." required><?= old('message') ?></textarea>
                                </div>
                                
                                <!-- Cloudflare Turnstile -->
                                <?php if (function_exists('turnstile_enabled') && turnstile_enabled()): ?>
                                    <?php include __DIR__ . '/../components/turnstile.php'; ?>
                                <?php endif; ?>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Info Sidebar -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Kontak</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-bold"><i class="fas fa-map-marker-alt text-danger"></i> Alamat</h6>
                                <p class="text-muted mb-0">
                                    Kementerian Agama RI<br>
                                    Jl. M.H. Thamrin No. 6<br>
                                    Jakarta Pusat, DKI Jakarta<br>
                                    Indonesia 10340
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="fw-bold"><i class="fas fa-phone text-success"></i> Telepon</h6>
                                <p class="text-muted mb-0">
                                    +62 21 3811663<br>
                                    +62 21 3800244
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="fw-bold"><i class="fas fa-envelope text-primary"></i> Email</h6>
                                <p class="text-muted mb-0">
                                    info@kemenag.go.id<br>
                                    support@kemenag.go.id
                                </p>
                            </div>
                            
                            <div>
                                <h6 class="fw-bold"><i class="fas fa-clock text-warning"></i> Jam Kerja</h6>
                                <p class="text-muted mb-0">
                                    Senin - Jumat: 08:00 - 16:00 WIB<br>
                                    Sabtu - Minggu: Tutup
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-question-circle text-info"></i> FAQ</h6>
                            <p class="small mb-2">
                                <strong>Q: Berapa lama waktu respon?</strong><br>
                                A: Kami akan merespon dalam 1-2 hari kerja.
                            </p>
                            <p class="small mb-0">
                                <strong>Q: Bisa konsultasi via telepon?</strong><br>
                                A: Ya, hubungi nomor di atas pada jam kerja.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
