<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="<?= asset('assets/images/logo-kemenag.png') ?>" alt="Logo" height="60" class="mb-3">
                        <h3 class="fw-bold">Login</h3>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>
                    
                    <form action="<?= url('/login') ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username atau Email</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username') ?>" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                        
                        <!-- Cloudflare Turnstile -->
                        <?php include __DIR__ . '/../../components/turnstile.php'; ?>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <a href="<?= url('/forgot-password') ?>" class="text-decoration-none">Lupa Password?</a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun? <a href="<?= url('/register') ?>" class="fw-bold">Daftar Sekarang</a></p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-3 bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-2"><i class="fas fa-info-circle text-primary"></i> Informasi</h6>
                    <ul class="small mb-0">
                        <li>Gunakan username atau email untuk login</li>
                        <li>Password minimal 8 karakter</li>
                        <li>Jika lupa password, klik "Lupa Password"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
