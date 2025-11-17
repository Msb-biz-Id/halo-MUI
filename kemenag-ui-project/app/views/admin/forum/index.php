<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Moderasi Forum - Approval Topik</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Forum Moderation</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
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
                        <span class="avatar-title bg-success-subtle text-success font-size-18 rounded">
                            <i class="uil-check"></i>
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
                        <span class="avatar-title bg-danger-subtle text-danger font-size-18 rounded">
                            <i class="uil-exclamation-triangle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Contains Blacklist</p>
                        <h4 class="mb-0"><?= $stats['with_blacklist'] ?? 0 ?></h4>
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
                            <i class="uil-list-ul"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Topics</p>
                        <h4 class="mb-0"><?= $stats['total'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= url('/admin/forum') ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="pending" <?= $filters['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $filters['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $filters['category'] == $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" name="q" class="form-control" placeholder="Cari judul topik..." value="<?= $filters['q'] ?? '' ?>">
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

<!-- Topics List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Forum Topics</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Blacklist</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topics)): ?>
                                <?php foreach ($topics as $topic): ?>
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="<?= url('/admin/forum/view/' . $topic['id']) ?>" class="text-dark fw-semibold">
                                                    <?= htmlspecialchars($topic['title']) ?>
                                                </a>
                                                <?php if ($topic['is_locked']): ?>
                                                    <span class="badge bg-danger-subtle text-danger ms-1">Locked</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted"><?= truncate($topic['content'], 80) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= htmlspecialchars($topic['category_name']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold"><?= htmlspecialchars($topic['full_name'] ?? $topic['username']) ?></div>
                                                <small class="text-muted"><?= $topic['email'] ?></small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($topic['is_approved']): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($topic['has_blacklist'])): ?>
                                                <span class="badge bg-danger" data-bs-toggle="tooltip" title="Contains blacklisted words">
                                                    <i class="uil-exclamation-triangle"></i> Yes
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success-subtle text-success">Clean</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y H:i', strtotime($topic['created_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/forum/view/' . $topic['id']) ?>">
                                                            <i class="uil-eye me-2"></i> View Detail
                                                        </a>
                                                    </li>
                                                    <?php if (!$topic['is_approved']): ?>
                                                        <li>
                                                            <a class="dropdown-item text-success" href="<?= url('/admin/forum/approve/' . $topic['id']) ?>">
                                                                <i class="uil-check me-2"></i> Approve
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="<?= url('/admin/forum/reject/' . $topic['id']) ?>">
                                                                <i class="uil-times me-2"></i> Reject
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= url('/admin/forum/delete/' . $topic['id']) ?>" 
                                                           onclick="return confirm('Delete this topic permanently?')">
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
                                    <td colspan="7" class="text-center py-4">
                                        <i class="uil-inbox font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">Tidak ada topik forum</p>
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
