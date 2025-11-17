<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-4">
                <h3 class="fw-bold">Edit Profile</h3>
                <p class="text-muted">Update informasi pribadi Anda</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Pribadi</h5>
                </div>
                
                <div class="card-body">
                    <form action="<?= url('/user/profile/update') ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?= htmlspecialchars(user('full_name')) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars(user('username')) ?>" disabled>
                                <small class="text-muted">Username tidak dapat diubah</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars(user('email')) ?>" disabled>
                            <small class="text-muted">
                                <?php if (user('email_verified_at')): ?>
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Verified</span>
                                <?php else: ?>
                                    <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Not verified</span>
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars(user('phone') ?? '') ?>" placeholder="+62">
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars(user('address') ?? '') ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Ubah Password</h5>
                </div>
                
                <div class="card-body">
                    <form action="<?= url('/user/profile/change-password') ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Profile Summary -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3" 
                         style="width: 100px; height: 100px; background: linear-gradient(135deg, #006837, #004d28); 
                                border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span class="text-white" style="font-size: 40px; font-weight: bold;">
                            <?= strtoupper(substr(user('full_name'), 0, 1)) ?>
                        </span>
                    </div>
                    <h5 class="mb-1"><?= htmlspecialchars(user('full_name')) ?></h5>
                    <p class="text-muted mb-2">@<?= htmlspecialchars(user('username')) ?></p>
                    <span class="badge bg-success"><?= ucfirst(user('role_name')) ?></span>
                </div>
            </div>
            
            <!-- Account Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Member sejak:</span>
                        <strong><?= date('M Y', strtotime(user('created_at'))) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status Email:</span>
                        <?php if (user('email_verified_at')): ?>
                            <span class="badge bg-success">Verified</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Not Verified</span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">MFA:</span>
                        <?php if (user('mfa_enabled')): ?>
                            <span class="badge bg-success">Enabled</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Disabled</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!user('mfa_enabled')): ?>
                        <hr>
                        <a href="<?= url('/user/security/enable-mfa') ?>" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-shield-alt"></i> Aktifkan MFA
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
