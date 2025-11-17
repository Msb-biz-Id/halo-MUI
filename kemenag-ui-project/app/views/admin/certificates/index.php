<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📜 Manajemen Sertifikat Halal</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Certificates</li>
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
                        <span class="avatar-title bg-warning-subtle text-warning font-size-18 rounded">
                            <i class="uil-clock"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Pending</p>
                        <h4 class="mb-0"><?= $stats['pending'] ?? 0 ?></h4>
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
                        <span class="avatar-title bg-info-subtle text-info font-size-18 rounded">
                            <i class="uil-file-search-alt"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">In Review</p>
                        <h4 class="mb-0"><?= $stats['in_review'] ?? 0 ?></h4>
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
                        <p class="text-muted mb-1">Approved</p>
                        <h4 class="mb-0"><?= $stats['approved'] ?? 0 ?></h4>
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
                        <span class="avatar-title bg-primary-subtle text-primary font-size-18 rounded">
                            <i class="uil-file-check"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Completed</p>
                        <h4 class="mb-0"><?= $stats['completed'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">Filter & Search</h5>
                    </div>
                    <div>
                        <a href="<?= url('/admin/certificates/export?status=' . $current_status) ?>" class="btn btn-success">
                            <i class="uil-file-download"></i> Export Excel
                        </a>
                    </div>
                </div>
                
                <form action="<?= url('/admin/certificates') ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all" <?= $current_status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                            <option value="pending" <?= $current_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in_review" <?= $current_status == 'in_review' ? 'selected' : '' ?>>In Review</option>
                            <option value="approved" <?= $current_status == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $current_status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            <option value="completed" <?= $current_status == 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="all" <?= $current_priority == 'all' ? 'selected' : '' ?>>All</option>
                            <option value="high" <?= $current_priority == 'high' ? 'selected' : '' ?>>High</option>
                            <option value="normal" <?= $current_priority == 'normal' ? 'selected' : '' ?>>Normal</option>
                            <option value="low" <?= $current_priority == 'low' ? 'selected' : '' ?>>Low</option>
                        </select>
                    </div>
                    
                    <div class="col-md-5">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Ticket, Company, Product..." value="<?= htmlspecialchars($search ?? '') ?>">
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

<!-- Certificates Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Certificate Applications (<?= count($certificates) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Company / Product</th>
                                <th>Applicant</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                                <th>Assigned To</th>
                                <th>Submitted</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($certificates)): ?>
                                <?php foreach ($certificates as $cert): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url('/admin/certificates/view/' . $cert['id']) ?>" class="fw-semibold">
                                                <?= $cert['ticket_number'] ?>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($cert['company_name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($cert['product_name']) ?></small>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($cert['applicant_name']) ?></div>
                                            <small class="text-muted"><?= $cert['applicant_email'] ?></small>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $priorityColors = ['low' => 'secondary', 'normal' => 'info', 'high' => 'warning'];
                                            $priorityColor = $priorityColors[$cert['priority']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $priorityColor ?>-subtle text-<?= $priorityColor ?>">
                                                <?= ucfirst($cert['priority']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'in_review' => 'info',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'completed' => 'primary'
                                            ];
                                            $statusColor = $statusColors[$cert['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $statusColor ?>">
                                                <?= ucfirst(str_replace('_', ' ', $cert['status'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($cert['assigned_admin_name'])): ?>
                                                <?= htmlspecialchars($cert['assigned_admin_name']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Unassigned</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($cert['submitted_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/certificates/view/' . $cert['id']) ?>">
                                                            <i class="uil-eye me-2"></i> View Detail
                                                        </a>
                                                    </li>
                                                    <?php if ($cert['status'] == 'pending'): ?>
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assignModal<?= $cert['id'] ?>">
                                                                <i class="uil-user-plus me-2"></i> Assign Admin
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="uil-inbox font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">No certificates found</p>
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

<!-- Assign Modals -->
<?php if (!empty($certificates)): ?>
    <?php foreach ($certificates as $cert): ?>
        <?php if ($cert['status'] == 'pending'): ?>
            <div class="modal fade" id="assignModal<?= $cert['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= url('/admin/certificates/assign/' . $cert['id']) ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Certificate</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            
                            <div class="modal-body">
                                <p><strong>Ticket:</strong> <?= $cert['ticket_number'] ?></p>
                                <p><strong>Company:</strong> <?= htmlspecialchars($cert['company_name']) ?></p>
                                
                                <div class="mb-3">
                                    <label class="form-label">Assign to Admin</label>
                                    <select name="admin_id" class="form-select" required>
                                        <option value="">-- Select Admin --</option>
                                        <?php foreach ($admins as $admin): ?>
                                            <option value="<?= $admin['id'] ?>">
                                                <?= htmlspecialchars($admin['full_name']) ?> (<?= $admin['email'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
