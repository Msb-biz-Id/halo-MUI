<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">👤 Profil Saya</h4>
        </div>
    </div>
</div>

<!-- Profile Summary Card -->
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4">
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="<?= asset($user['profile_picture']) ?>" alt="Profile" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 48px;">
                        <?= strtoupper(substr($user['full_name'] ?? $user['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></h5>
                <p class="text-muted mb-2"><?= htmlspecialchars($user['email']) ?></p>
                
                <span class="badge bg-<?= $user['role_name'] === 'superadmin' ? 'danger' : ($user['role_name'] === 'admin' ? 'warning' : 'primary') ?> mb-3">
                    <?= ucfirst($user['role_name']) ?>
                </span>
                
                <div class="d-grid gap-2 mt-3">
                    <a href="<?= url('/profile/edit') ?>" class="btn btn-primary">
                        <i class="uil-edit"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-user"></i> Informasi Personal</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Username:</div>
                    <div class="col-md-8"><?= htmlspecialchars($user['username']) ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Email:</div>
                    <div class="col-md-8">
                        <?= htmlspecialchars($user['email']) ?>
                        <?php if ($user['email_verified']): ?>
                            <span class="badge bg-success ms-2">Verified</span>
                        <?php else: ?>
                            <span class="badge bg-warning ms-2">Not Verified</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($user['phone'])): ?>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">No. Telepon:</div>
                    <div class="col-md-8"><?= htmlspecialchars($user['phone']) ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($user['date_of_birth'])): ?>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tanggal Lahir:</div>
                    <div class="col-md-8"><?= date('d F Y', strtotime($user['date_of_birth'])) ?></div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($user['gender'])): ?>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Jenis Kelamin:</div>
                    <div class="col-md-8"><?= $user['gender'] === 'male' ? 'Laki-laki' : 'Perempuan' ?></div>
                </div>
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Bergabung:</div>
                    <div class="col-md-8"><?= date('d F Y', strtotime($user['created_at'])) ?></div>
                </div>
                
                <?php if (!empty($user['last_login'])): ?>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Login Terakhir:</div>
                    <div class="col-md-8"><?= date('d F Y H:i', strtotime($user['last_login'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body text-center">
                <i class="uil-lock-alt text-warning" style="font-size: 48px;"></i>
                <h6 class="mt-3 mb-2">Keamanan</h6>
                <p class="text-muted small mb-3">MFA & Password</p>
                <a href="<?= url('/profile/security') ?>" class="btn btn-sm btn-outline-primary">Kelola</a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body text-center">
                <i class="uil-bell text-info" style="font-size: 48px;"></i>
                <h6 class="mt-3 mb-2">Notifikasi</h6>
                <p class="text-muted small mb-3">Preferensi alert</p>
                <a href="<?= url('/profile/notification-settings') ?>" class="btn btn-sm btn-outline-primary">Atur</a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body text-center">
                <i class="uil-shield-check text-success" style="font-size: 48px;"></i>
                <h6 class="mt-3 mb-2">Privacy</h6>
                <p class="text-muted small mb-3">Data & visibility</p>
                <a href="<?= url('/profile/privacy') ?>" class="btn btn-sm btn-outline-primary">Lihat</a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm hover-card">
            <div class="card-body text-center">
                <i class="uil-key-skeleton text-danger" style="font-size: 48px;"></i>
                <h6 class="mt-3 mb-2">Password</h6>
                <p class="text-muted small mb-3">Ganti password</p>
                <a href="<?= url('/profile/change-password') ?>" class="btn btn-sm btn-outline-primary">Ubah</a>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>
