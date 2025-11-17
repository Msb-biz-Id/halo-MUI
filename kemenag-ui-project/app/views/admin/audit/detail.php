<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Audit Log Detail</h4>
            <a href="<?= url('/admin/audit-logs') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="uil-file-info-alt"></i> Log Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Action:</div>
                    <div class="col-md-9">
                        <span class="badge bg-<?= $actionColors[$log['action']] ?? 'secondary' ?> fs-6">
                            <?= strtoupper($log['action']) ?>
                        </span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Entity:</div>
                    <div class="col-md-9">
                        <?= htmlspecialchars($log['entity_type']) ?> 
                        <?php if ($log['entity_id']): ?>
                            <code>#<?= $log['entity_id'] ?></code>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Timestamp:</div>
                    <div class="col-md-9"><?= date('d F Y H:i:s', strtotime($log['created_at'])) ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">IP Address:</div>
                    <div class="col-md-9"><code><?= htmlspecialchars($log['ip_address']) ?></code></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">User Agent:</div>
                    <div class="col-md-9"><small><?= htmlspecialchars($log['user_agent']) ?></small></div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="fw-bold mb-3">Full Details:</h6>
                <pre class="bg-light p-3 border" style="max-height: 400px; overflow-y: auto;"><?= json_encode(json_decode($log['details'] ?? '{}'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">User Info</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; font-size: 32px;">
                        <?= strtoupper(substr($log['username'], 0, 1)) ?>
                    </div>
                </div>
                
                <h6 class="text-center mb-1"><?= htmlspecialchars($log['full_name'] ?? $log['username']) ?></h6>
                <p class="text-center text-muted small mb-3"><?= htmlspecialchars($log['email']) ?></p>
                
                <hr>
                
                <div class="mb-2">
                    <strong>Username:</strong> <?= htmlspecialchars($log['username']) ?>
                </div>
                <div class="mb-2">
                    <strong>Role:</strong> 
                    <span class="badge bg-<?= $log['role_name'] === 'superadmin' ? 'danger' : 'info' ?>">
                        <?= htmlspecialchars($log['role_name']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
