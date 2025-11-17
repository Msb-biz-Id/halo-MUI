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
                <a href="<?= url('/admin/settings') ?>" class="list-group-item list-group-item-action">
                    <i class="uil-cog"></i> General
                </a>
                <a href="<?= url('/admin/settings/email') ?>" class="list-group-item list-group-item-action active">
                    <i class="uil-envelope"></i> Email
                </a>
                <a href="<?= url('/admin/settings/seo') ?>" class="list-group-item list-group-item-action">
                    <i class="uil-chart-line"></i> SEO
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <form action="<?= url('/admin/settings/update') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">📧 SMTP Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">SMTP Host</label>
                        <input type="text" class="form-control" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>" placeholder="smtp.gmail.com">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">SMTP Port</label>
                            <input type="number" class="form-control" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '587') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Encryption</label>
                            <select class="form-select" name="smtp_encryption">
                                <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                <option value="ssl" <?= ($settings['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">SMTP Username</label>
                        <input type="text" class="form-control" name="smtp_username" value="<?= htmlspecialchars($settings['smtp_username'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">SMTP Password</label>
                        <input type="password" class="form-control" name="smtp_password" placeholder="••••••••">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">From Email</label>
                            <input type="email" class="form-control" name="mail_from_address" value="<?= htmlspecialchars($settings['mail_from_address'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">From Name</label>
                            <input type="text" class="form-control" name="mail_from_name" value="<?= htmlspecialchars($settings['mail_from_name'] ?? 'Kemenag UI') ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-dark">🔔 Email Notifications</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_email_notifications" <?= ($settings['enable_email_notifications'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Enable Email Notifications
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Admin Notification Email</label>
                        <input type="email" class="form-control" name="admin_email" value="<?= htmlspecialchars($settings['admin_email'] ?? '') ?>">
                        <small class="text-muted">Email untuk notifikasi admin</small>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="uil-check"></i> Simpan Pengaturan
            </button>
            <button type="button" class="btn btn-outline-info" onclick="testEmail()">
                <i class="uil-envelope-send"></i> Test Email
            </button>
        </form>
    </div>
</div>

<script>
function testEmail() {
    alert('Test email akan dikirim ke email Anda. Cek inbox dalam beberapa menit.');
}
</script>
