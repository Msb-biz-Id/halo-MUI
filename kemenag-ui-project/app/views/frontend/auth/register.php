<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="<?= asset('assets/images/logo-kemenag.png') ?>" alt="Logo" height="60" class="mb-3">
                        <h3 class="fw-bold">Daftar Akun Baru</h3>
                        <p class="text-muted">Silakan lengkapi form pendaftaran</p>
                    </div>
                    
                    <form action="<?= url('/register') ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= old('username') ?>" required>
                                <small class="text-muted">Tanpa spasi, minimal 4 karakter</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= old('email') ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?= old('full_name') ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="text-muted">Minimal 8 karakter</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="<?= old('phone') ?>" placeholder="08xx-xxxx-xxxx">
                        </div>
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nama Perusahaan <small class="text-muted">(Opsional)</small></label>
                            <input type="text" class="form-control" id="company_name" name="company_name" 
                                   value="<?= old('company_name') ?>">
                            <small class="text-muted">Isi jika Anda akan mengajukan sertifikat halal</small>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                Saya menyetujui <a href="<?= url('/terms') ?>" target="_blank">Syarat & Ketentuan</a> 
                                dan <a href="<?= url('/privacy') ?>" target="_blank">Kebijakan Privasi</a>
                            </label>
                        </div>
                        
                        <!-- Cloudflare Turnstile -->
                        <?php include __DIR__ . '/../../components/turnstile.php'; ?>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun? <a href="<?= url('/login') ?>" class="fw-bold">Login di sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    var password = this.value;
    var strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    // You can add visual feedback here
});

// Password confirmation validation
document.getElementById('password_confirm').addEventListener('input', function() {
    var password = document.getElementById('password').value;
    var confirm = this.value;
    
    if (password !== confirm) {
        this.setCustomValidity('Password tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});
</script>
