<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🔐 Keamanan Akun</h4>
            <a href="<?= url('/profile') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- MFA Settings -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="uil-mobile-android"></i> Multi-Factor Authentication (MFA)</h5>
            </div>
            <div class="card-body">
                <?php if ($user['mfa_enabled']): ?>
                    <div class="alert alert-success">
                        <i class="uil-check-circle"></i> <strong>MFA Aktif!</strong> Akun Anda dilindungi dengan autentikasi 2 faktor.
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-1">Status MFA</h6>
                            <small class="text-muted">Diaktifkan sejak: <?= date('d M Y', strtotime($user['mfa_enabled_at'] ?? $user['updated_at'])) ?></small>
                        </div>
                        <span class="badge bg-success">Active</span>
                    </div>
                    
                    <form action="<?= url('/profile/disable-mfa') ?>" method="POST" onsubmit="return confirm('Yakin ingin nonaktifkan MFA? Ini akan mengurangi keamanan akun Anda.')">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="uil-times-circle"></i> Nonaktifkan MFA
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="uil-exclamation-triangle"></i> <strong>MFA Tidak Aktif!</strong> Aktifkan untuk keamanan ekstra.
                    </div>
                    
                    <h6 class="mb-3">Apa itu MFA?</h6>
                    <p class="text-muted">
                        Multi-Factor Authentication menambahkan lapisan keamanan ekstra dengan meminta kode verifikasi 
                        dari aplikasi authenticator setiap kali Anda login.
                    </p>
                    
                    <h6 class="mb-3 mt-4">Cara Setup:</h6>
                    <ol class="text-muted">
                        <li>Download aplikasi authenticator (Google Authenticator, Authy, dll)</li>
                        <li>Scan QR code yang akan muncul</li>
                        <li>Masukkan kode 6 digit untuk verifikasi</li>
                        <li>Simpan backup codes untuk recovery</li>
                    </ol>
                    
                    <a href="<?= url('/profile/setup-mfa') ?>" class="btn btn-success mt-3">
                        <i class="uil-shield-check"></i> Aktifkan MFA Sekarang
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Active Sessions -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="uil-monitor"></i> Sesi Aktif</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                    <div>
                        <h6 class="mb-1">
                            <i class="uil-laptop"></i> Sesi Saat Ini
                        </h6>
                        <small class="text-muted">
                            <?= $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Device' ?>
                        </small>
                        <br>
                        <small class="text-muted">
                            IP: <?= $_SERVER['REMOTE_ADDR'] ?? 'Unknown' ?>
                        </small>
                    </div>
                    <span class="badge bg-success">Active Now</span>
                </div>
                
                <p class="text-muted small mb-0">
                    <i class="uil-info-circle"></i> Hanya sesi saat ini yang ditampilkan. 
                    Jika Anda melihat aktivitas mencurigakan, segera ubah password Anda.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Security Info -->
    <div class="col-lg-4 mb-4">
        <!-- Account Status -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Status Akun</h6>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small>Email Verification</small>
                        <?php if ($user['email_verified']): ?>
                            <i class="uil-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="uil-times-circle text-danger"></i>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small>MFA Enabled</small>
                        <?php if ($user['mfa_enabled']): ?>
                            <i class="uil-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="uil-times-circle text-danger"></i>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small>Account Active</small>
                        <?php if ($user['is_active']): ?>
                            <i class="uil-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="uil-times-circle text-danger"></i>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($user['last_login'])): ?>
                <hr>
                <small class="text-muted">
                    <strong>Login Terakhir:</strong><br>
                    <?= date('d M Y H:i', strtotime($user['last_login'])) ?>
                </small>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Security Score -->
        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-body text-center">
                <h6 class="fw-bold mb-3">Security Score</h6>
                <?php
                $score = 0;
                if ($user['email_verified']) $score += 25;
                if ($user['mfa_enabled']) $score += 50;
                if ($user['is_active']) $score += 25;
                
                $color = $score < 50 ? 'danger' : ($score < 75 ? 'warning' : 'success');
                ?>
                <div class="mb-3">
                    <h2 class="text-<?= $color ?> mb-0"><?= $score ?>%</h2>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar bg-<?= $color ?>" style="width: <?= $score ?>%"></div>
                    </div>
                </div>
                <p class="small text-muted mb-0">
                    <?php if ($score >= 75): ?>
                        Keamanan Anda sangat baik!
                    <?php elseif ($score >= 50): ?>
                        Tingkatkan keamanan dengan MFA
                    <?php else: ?>
                        Perbaiki keamanan akun Anda!
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Aksi Cepat</h6>
                <div class="d-grid gap-2">
                    <a href="<?= url('/profile/change-password') ?>" class="btn btn-sm btn-outline-warning">
                        <i class="uil-key-skeleton"></i> Ubah Password
                    </a>
                    <a href="<?= url('/admin/audit-logs') ?>" class="btn btn-sm btn-outline-info">
                        <i class="uil-history"></i> Lihat Activity Log
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
