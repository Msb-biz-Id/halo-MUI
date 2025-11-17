<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🛡️ Word Blacklist Management</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Blacklist</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0 me-3">
                        <span class="avatar-title bg-primary-subtle text-primary font-size-18 rounded">
                            <i class="uil-list-ul"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Words</p>
                        <h4 class="mb-0"><?= $stats['total'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0 me-3">
                        <span class="avatar-title bg-success-subtle text-success font-size-18 rounded">
                            <i class="uil-check-circle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Active</p>
                        <h4 class="mb-0"><?= $stats['active'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0 me-3">
                        <span class="avatar-title bg-danger-subtle text-danger font-size-18 rounded">
                            <i class="uil-ban"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Critical</p>
                        <h4 class="mb-0"><?= $stats['critical'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0 me-3">
                        <span class="avatar-title bg-warning-subtle text-warning font-size-18 rounded">
                            <i class="uil-exclamation-triangle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Detections</p>
                        <h4 class="mb-0"><?= $stats['detections_today'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= url('/admin/blacklist/create') ?>" class="btn btn-primary">
                        <i class="uil-plus"></i> Add New Word
                    </a>
                    <a href="<?= url('/admin/blacklist/bulk-add') ?>" class="btn btn-success">
                        <i class="uil-files-landscapes-alt"></i> Bulk Add
                    </a>
                    <a href="<?= url('/admin/blacklist/detection-logs') ?>" class="btn btn-info">
                        <i class="uil-chart-line"></i> View Detection Logs
                    </a>
                    <a href="<?= url('/admin/blacklist/test') ?>" class="btn btn-warning">
                        <i class="uil-flask"></i> Test Checker
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= url('/admin/blacklist') ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Severity</label>
                        <select name="severity" class="form-select">
                            <option value="">All</option>
                            <option value="low" <?= $filters['severity'] == 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= $filters['severity'] == 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= $filters['severity'] == 'high' ? 'selected' : '' ?>>High</option>
                            <option value="critical" <?= $filters['severity'] == 'critical' ? 'selected' : '' ?>>Critical</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All</option>
                            <option value="flag" <?= $filters['action'] == 'flag' ? 'selected' : '' ?>>Flag</option>
                            <option value="block" <?= $filters['action'] == 'block' ? 'selected' : '' ?>>Block</option>
                            <option value="auto_reject" <?= $filters['action'] == 'auto_reject' ? 'selected' : '' ?>>Auto Reject</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Search Word</label>
                        <input type="text" name="q" class="form-control" placeholder="Search word..." value="<?= $filters['q'] ?? '' ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="uil-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Blacklist Words Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Blacklisted Words (<?= count($words) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Word</th>
                                <th>Type</th>
                                <th>Severity</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th class="text-center">Status</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($words)): ?>
                                <?php foreach ($words as $word): ?>
                                    <tr>
                                        <td><code class="text-danger"><?= htmlspecialchars($word['word']) ?></code></td>
                                        <td><span class="badge bg-secondary"><?= $word['type'] ?></span></td>
                                        <td>
                                            <?php
                                            $colors = ['low' => 'info', 'medium' => 'warning', 'high' => 'orange', 'critical' => 'danger'];
                                            ?>
                                            <span class="badge bg-<?= $colors[$word['severity']] ?>">
                                                <?= strtoupper($word['severity']) ?>
                                            </span>
                                        </td>
                                        <td><span class="badge bg-dark"><?= $word['action'] ?></span></td>
                                        <td><small class="text-muted"><?= htmlspecialchars($word['description'] ?? '-') ?></small></td>
                                        <td class="text-center">
                                            <?php if ($word['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><small><?= date('d M Y', strtotime($word['created_at'])) ?></small></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/blacklist/edit/' . $word['id']) ?>">
                                                            <i class="uil-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/blacklist/toggle-active/' . $word['id']) ?>">
                                                            <i class="uil-<?= $word['is_active'] ? 'ban' : 'check' ?> me-2"></i>
                                                            <?= $word['is_active'] ? 'Deactivate' : 'Activate' ?>
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= url('/admin/blacklist/delete/' . $word['id']) ?>"
                                                           onclick="return confirm('Delete this word?')">
                                                            <i class="uil-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="uil-inbox font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">No blacklisted words yet</p>
                                        <a href="<?= url('/admin/blacklist/create') ?>" class="btn btn-primary">
                                            <i class="uil-plus"></i> Add First Word
                                        </a>
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

<style>
code {
    font-size: 0.9rem;
    padding: 2px 6px;
    background: rgba(220,53,69,0.1);
    border-radius: 3px;
}
</style>
