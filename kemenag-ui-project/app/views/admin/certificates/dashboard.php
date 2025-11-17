<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">📊 Certificate Dashboard</h4>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-warning text-white rounded-3">
                                <i class="uil-clock font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Pending</p>
                        <h4 class="mb-0"><?= $stats['pending'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-info text-white rounded-3">
                                <i class="uil-search font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">In Review</p>
                        <h4 class="mb-0"><?= $stats['in_review'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-success text-white rounded-3">
                                <i class="uil-check-circle font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Approved</p>
                        <h4 class="mb-0"><?= $stats['approved'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-danger text-white rounded-3">
                                <i class="uil-times-circle font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Rejected</p>
                        <h4 class="mb-0"><?= $stats['rejected'] ?? 0 ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Applications</h5>
                    <a href="<?= url('/admin/certificates') ?>" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_applications)): ?>
                                <?php foreach (array_slice($recent_applications, 0, 10) as $app): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($app['company_name']) ?></td>
                                        <td><small><?= htmlspecialchars($app['product_name']) ?></small></td>
                                        <td>
                                            <?php
                                            $colors = [
                                                'pending' => 'warning',
                                                'in_review' => 'info',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $color = $colors[$app['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= strtoupper($app['status']) ?></span>
                                        </td>
                                        <td><small><?= date('d M Y', strtotime($app['created_at'])) ?></small></td>
                                        <td>
                                            <a href="<?= url('/admin/certificates/view/' . $app['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="uil-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Monthly Trend</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($monthly_stats)): ?>
                    <?php foreach ($monthly_stats as $month): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><?= $month['month_name'] ?></span>
                            <span class="badge bg-primary"><?= $month['count'] ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= url('/admin/certificates?status=pending') ?>" class="btn btn-outline-warning">
                        <i class="uil-clock"></i> View Pending (<?= $stats['pending'] ?? 0 ?>)
                    </a>
                    <a href="<?= url('/admin/certificates?status=in_review') ?>" class="btn btn-outline-info">
                        <i class="uil-search"></i> In Review (<?= $stats['in_review'] ?? 0 ?>)
                    </a>
                    <a href="<?= url('/admin/certificates/export') ?>" class="btn btn-outline-success">
                        <i class="uil-download-alt"></i> Export Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
