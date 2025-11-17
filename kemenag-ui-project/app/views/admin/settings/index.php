<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">⚙️ Pengaturan Sistem</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="list-group list-group-flush">
                <a href="<?= url('/admin/settings') ?>" class="list-group-item list-group-item-action active">
                    <i class="uil-cog"></i> General
                </a>
                <a href="<?= url('/admin/settings/email') ?>" class="list-group-item list-group-item-action">
                    <i class="uil-envelope"></i> Email
                </a>
                <a href="<?= url('/admin/settings/seo') ?>" class="list-group-item list-group-item-action">
                    <i class="uil-chart-line"></i> SEO
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <form action="<?= url('/admin/settings/update') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <!-- Site Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📝 Informasi Website</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Website</label>
                        <input type="text" class="form-control" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? 'Kemenag UI') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tagline</label>
                        <input type="text" class="form-control" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" name="site_description" rows="3"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Kontak</label>
                            <input type="email" class="form-control" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Telepon</label>
                            <input type="text" class="form-control" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Logo & Favicon -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">🎨 Logo & Branding</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Logo Website</label>
                            <?php if (!empty($settings['site_logo'])): ?>
                                <img src="<?= asset($settings['site_logo']) ?>" alt="Logo" style="max-width: 200px; display: block; margin-bottom: 10px;">
                            <?php endif; ?>
                            <input type="file" class="form-control" name="site_logo" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Favicon</label>
                            <?php if (!empty($settings['site_favicon'])): ?>
                                <img src="<?= asset($settings['site_favicon']) ?>" alt="Favicon" style="max-width: 50px; display: block; margin-bottom: 10px;">
                            <?php endif; ?>
                            <input type="file" class="form-control" name="site_favicon" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security -->
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-dark">🔐 Keamanan</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_registration" <?= ($settings['enable_registration'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Enable User Registration
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="require_email_verification" <?= ($settings['require_email_verification'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Require Email Verification
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_mfa" <?= ($settings['enable_mfa'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Enable Multi-Factor Authentication (MFA)
                        </label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="uil-check"></i> Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
