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
                <a href="<?= url('/admin/settings/email') ?>" class="list-group-item list-group-item-action">
                    <i class="uil-envelope"></i> Email
                </a>
                <a href="<?= url('/admin/settings/seo') ?>" class="list-group-item list-group-item-action active">
                    <i class="uil-chart-line"></i> SEO
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <form action="<?= url('/admin/settings/update') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">🔍 Meta Tags</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Title</label>
                        <input type="text" class="form-control" name="seo_title" value="<?= htmlspecialchars($settings['seo_title'] ?? '') ?>">
                        <small class="text-muted">Maksimal 60 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description</label>
                        <textarea class="form-control" name="seo_description" rows="3"><?= htmlspecialchars($settings['seo_description'] ?? '') ?></textarea>
                        <small class="text-muted">Maksimal 160 karakter</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Keywords</label>
                        <input type="text" class="form-control" name="seo_keywords" value="<?= htmlspecialchars($settings['seo_keywords'] ?? '') ?>">
                        <small class="text-muted">Pisahkan dengan koma</small>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">🌐 Open Graph & Social Media</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">OG Title</label>
                        <input type="text" class="form-control" name="og_title" value="<?= htmlspecialchars($settings['og_title'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">OG Description</label>
                        <textarea class="form-control" name="og_description" rows="2"><?= htmlspecialchars($settings['og_description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">OG Image URL</label>
                        <input type="text" class="form-control" name="og_image" value="<?= htmlspecialchars($settings['og_image'] ?? '') ?>">
                        <small class="text-muted">URL gambar untuk social media sharing (1200x630px recommended)</small>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-dark">📊 Analytics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Google Analytics ID</label>
                        <input type="text" class="form-control" name="google_analytics_id" value="<?= htmlspecialchars($settings['google_analytics_id'] ?? '') ?>" placeholder="G-XXXXXXXXXX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Google Search Console Verification</label>
                        <input type="text" class="form-control" name="google_site_verification" value="<?= htmlspecialchars($settings['google_site_verification'] ?? '') ?>">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="enable_analytics" <?= ($settings['enable_analytics'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Enable Analytics Tracking
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🤖 Robots & Sitemap</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="allow_indexing" <?= ($settings['allow_indexing'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Allow Search Engine Indexing
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Robots.txt Custom Rules</label>
                        <textarea class="form-control font-monospace" name="robots_txt" rows="6"><?= htmlspecialchars($settings['robots_txt'] ?? '') ?></textarea>
                    </div>
                    <div class="alert alert-info">
                        <strong>Auto-generated URLs:</strong><br>
                        - Sitemap: <code><?= url('/sitemap.xml') ?></code><br>
                        - Robots.txt: <code><?= url('/robots.txt') ?></code>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="uil-check"></i> Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
