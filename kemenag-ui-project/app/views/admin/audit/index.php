<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📋 Audit Logs</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Audit Logs</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="<?= url('/admin/audit-logs/export') ?>" class="btn btn-success">
                            <i class="uil-export"></i> Export CSV
                        </a>
                    </div>
                    <div>
                        <form action="<?= url('/admin/audit-logs') ?>" method="GET" class="d-flex gap-2">
                            <select name="action" class="form-select" onchange="this.form.submit()">
                                <option value="">All Actions</option>
                                <option value="create" <?= $action_filter == 'create' ? 'selected' : '' ?>>Create</option>
                                <option value="update" <?= $action_filter == 'update' ? 'selected' : '' ?>>Update</option>
                                <option value="delete" <?= $action_filter == 'delete' ? 'selected' : '' ?>>Delete</option>
                                <option value="login" <?= $action_filter == 'login' ? 'selected' : '' ?>>Login</option>
                                <option value="logout" <?= $action_filter == 'logout' ? 'selected' : '' ?>>Logout</option>
                            </select>
                            
                            <input type="date" name="date" class="form-control" value="<?= $date_filter ?>" onchange="this.form.submit()">
                            
                            <input type="search" name="search" class="form-control" placeholder="Search user..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="uil-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>IP Address</th>
                                <th class="text-end">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td>
                                            <small><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($log['username']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($log['email']) ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $actionColors = [
                                                'create' => 'success',
                                                'update' => 'info',
                                                'delete' => 'danger',
                                                'login' => 'primary',
                                                'logout' => 'secondary'
                                            ];
                                            $color = $actionColors[$log['action']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>">
                                                <?= strtoupper($log['action']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                <strong><?= htmlspecialchars($log['entity_type']) ?></strong>
                                                <?php if ($log['entity_id']): ?>
                                                    <br>#<?= $log['entity_id'] ?>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="showDetails(<?= htmlspecialchars(json_encode($log['details'] ?? '{}')) ?>)">
                                                <i class="uil-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="uil-file-times font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">No logs found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="logDetails" class="bg-light p-3" style="max-height: 400px; overflow-y: auto;"></pre>
            </div>
        </div>
    </div>
</div>

<script>
function showDetails(details) {
    document.getElementById('logDetails').textContent = JSON.stringify(details, null, 2);
    new bootstrap.Modal(document.getElementById('detailsModal')).show();
}
</script>
