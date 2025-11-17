<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🔑 Ubah Password</h4>
            <a href="<?= url('/profile') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0 text-dark"><i class="uil-lock-alt"></i> Ganti Password Anda</h5>
            </div>
            
            <div class="card-body p-4">
                <div class="alert alert-info">
                    <i class="uil-info-circle"></i> 
                    <strong>Persyaratan Password:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Minimal 8 karakter</li>
                        <li>Kombinasi huruf besar dan kecil</li>
                        <li>Mengandung angka</li>
                        <li>Mengandung karakter khusus (!@#$%)</li>
                    </ul>
                </div>
                
                <form action="<?= url('/profile/change-password') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="current_password" class="form-label fw-bold">
                            Password Saat Ini <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="uil-lock"></i></span>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="uil-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password" class="form-label fw-bold">
                            Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="uil-lock-alt"></i></span>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                <i class="uil-eye" id="new_password_icon"></i>
                            </button>
                        </div>
                        <div id="password-strength" class="mt-2"></div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirm_password" class="form-label fw-bold">
                            Konfirmasi Password Baru <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="uil-check-circle"></i></span>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                <i class="uil-eye" id="confirm_password_icon"></i>
                            </button>
                        </div>
                        <div id="password-match" class="mt-2"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="uil-key-skeleton"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Security Tips -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="uil-shield-check"></i> Tips Keamanan</h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Gunakan password yang unik untuk setiap akun</li>
                    <li>Jangan gunakan informasi pribadi yang mudah ditebak</li>
                    <li>Aktifkan Multi-Factor Authentication untuk keamanan ekstra</li>
                    <li>Ubah password secara berkala (setiap 3-6 bulan)</li>
                    <li>Jangan bagikan password Anda kepada siapapun</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'uil-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'uil-eye';
    }
}

// Password strength checker
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('password-strength');
    
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[!@#$%^&*(),.?":{}|<>]/)) strength++;
    
    const levels = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
    const colors = ['danger', 'warning', 'info', 'success', 'success'];
    
    strengthDiv.innerHTML = `<small class="text-${colors[strength]}">Kekuatan: ${levels[strength]}</small>`;
});

// Password match checker
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirm = this.value;
    const matchDiv = document.getElementById('password-match');
    
    if (confirm.length > 0) {
        if (password === confirm) {
            matchDiv.innerHTML = '<small class="text-success"><i class="uil-check"></i> Password cocok</small>';
        } else {
            matchDiv.innerHTML = '<small class="text-danger"><i class="uil-times"></i> Password tidak cocok</small>';
        }
    }
});
</script>
