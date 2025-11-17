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
                <a href="<?= url('/admin/settings/seo') ?>" class="list-group-item list-group-item-action">
                    <i class="uil-chart-line"></i> SEO
                </a>
                <a href="<?= url('/admin/settings/cache') ?>" class="list-group-item list-group-item-action active">
                    <i class="uil-database"></i> Cache & Performance
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0 text-dark">🗄️ Cache Management</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="uil-file-times-alt font-size-48 text-warning mb-3"></i>
                                <h6>View Cache</h6>
                                <p class="text-muted small">Clear compiled view templates</p>
                                <button type="button" class="btn btn-warning" onclick="clearCache('views')">
                                    <i class="uil-trash"></i> Clear Views
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="uil-server font-size-48 text-info mb-3"></i>
                                <h6>Application Cache</h6>
                                <p class="text-muted small">Clear application data cache</p>
                                <button type="button" class="btn btn-info" onclick="clearCache('app')">
                                    <i class="uil-trash"></i> Clear Cache
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="uil-cog font-size-48 text-success mb-3"></i>
                                <h6>Config Cache</h6>
                                <p class="text-muted small">Clear configuration cache</p>
                                <button type="button" class="btn btn-success" onclick="clearCache('config')">
                                    <i class="uil-trash"></i> Clear Config
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card border">
                            <div class="card-body text-center">
                                <i class="uil-refresh font-size-48 text-danger mb-3"></i>
                                <h6>Clear All Cache</h6>
                                <p class="text-muted small">Clear all cached data</p>
                                <button type="button" class="btn btn-danger" onclick="clearCache('all')">
                                    <i class="uil-trash-alt"></i> Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <form action="<?= url('/admin/settings/update') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">⚡ Performance Settings</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_cache" 
                               <?= ($settings['enable_cache'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Enable Application Cache
                        </label>
                        <br><small class="text-muted">Improves performance by caching frequently accessed data</small>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_view_cache" 
                               <?= ($settings['enable_view_cache'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Enable View Cache
                        </label>
                        <br><small class="text-muted">Cache compiled view templates</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cache Duration (seconds)</label>
                        <input type="number" class="form-control" name="cache_duration" 
                               value="<?= $settings['cache_duration'] ?? 3600 ?>" min="60">
                        <small class="text-muted">Default: 3600 (1 hour)</small>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_compression" 
                               <?= ($settings['enable_compression'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold">
                            Enable GZIP Compression
                        </label>
                        <br><small class="text-muted">Compress output to reduce bandwidth</small>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg px-5">
                <i class="uil-check"></i> Simpan Pengaturan
            </button>
        </form>
    </div>
</div>

<script>
function clearCache(type) {
    if (confirm(`Clear ${type} cache? This may temporarily slow down the application.`)) {
        fetch('<?= url('/admin/settings/clear-cache') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $csrf_token ?>'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Cache cleared successfully!');
        })
        .catch(error => {
            alert('Failed to clear cache: ' + error);
        });
    }
}
</script>
