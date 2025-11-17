<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">👤 User Activity Report</h4>
            <a href="<?= url('/admin/audit-logs') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Back to Logs
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="uil-user-circle"></i> Activity for: <?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></h5>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($activities)): ?>
                                <?php foreach ($activities as $activity): ?>
                                    <tr>
                                        <td><small><?= date('d M Y H:i:s', strtotime($activity['created_at'])) ?></small></td>
                                        <td>
                                            <?php
                                            $colors = ['create' => 'success', 'update' => 'info', 'delete' => 'danger', 'login' => 'primary'];
                                            $color = $colors[$activity['action']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= strtoupper($activity['action']) ?></span>
                                        </td>
                                        <td>
                                            <small>
                                                <strong><?= htmlspecialchars($activity['entity_type']) ?></strong>
                                                <?php if ($activity['entity_id']): ?>#<?= $activity['entity_id'] ?><?php endif; ?>
                                            </small>
                                        </td>
                                        <td><code><?= htmlspecialchars($activity['ip_address']) ?></code></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="text-muted">No activity found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <div class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 100px; height: 100px; font-size: 40px;">
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                </div>
                <h5><?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                <span class="badge bg-info"><?= htmlspecialchars($user['role_name']) ?></span>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Activity Summary</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Total Actions:</strong> <?= $stats['total'] ?? 0 ?>
                </div>
                <div class="mb-2">
                    <strong>Last Login:</strong><br>
                    <small><?= date('d M Y H:i', strtotime($user['last_login'])) ?></small>
                </div>
                <div class="mb-2">
                    <strong>Account Created:</strong><br>
                    <small><?= date('d M Y', strtotime($user['created_at'])) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
