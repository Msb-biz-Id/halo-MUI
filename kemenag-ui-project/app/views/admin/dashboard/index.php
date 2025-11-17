<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Pengguna</span>
                        <h4 class="mb-3">
                            <?= number_format($stats['total_users'] ?? 0) ?>
                        </h4>
                        <div class="text-muted">
                            <span class="badge badge-soft-success font-size-12">
                                <i class="mdi mdi-menu-up"></i> Active: <?= $stats['active_users'] ?? 0 ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <div id="mini-chart1" data-colors='["--primary"]' class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Sertifikat Pending</span>
                        <h4 class="mb-3">
                            <?= number_format($stats['pending_certificates'] ?? 0) ?>
                        </h4>
                        <div class="text-muted">
                            <span class="badge badge-soft-warning font-size-12">
                                <i class="mdi mdi-clock-outline"></i> Butuh Review
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <i class="uil-certificate text-warning display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Q&A</span>
                        <h4 class="mb-3">
                            <?= number_format($stats['total_qa'] ?? 0) ?>
                        </h4>
                        <div class="text-muted">
                            <span class="badge badge-soft-info font-size-12">
                                <i class="mdi mdi-arrow-up"></i> Bulan ini: <?= $stats['qa_this_month'] ?? 0 ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <i class="uil-question-circle text-info display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Forum Topics</span>
                        <h4 class="mb-3">
                            <?= number_format($stats['total_forum_topics'] ?? 0) ?>
                        </h4>
                        <div class="text-muted">
                            <span class="badge badge-soft-success font-size-12">
                                <i class="mdi mdi-comment-multiple"></i> Active
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-end dash-widget">
                        <i class="uil-comments-alt text-success display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Pengajuan Sertifikat (30 Hari Terakhir)</h4>
            </div>
            <div class="card-body">
                <canvas id="certificateChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Status Sertifikat</h4>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & Certificates -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="uil-history me-1"></i> Aktivitas Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0">
                        <tbody>
                            <?php if (!empty($recent_activity)): ?>
                                <?php foreach ($recent_activity as $activity): ?>
                                    <tr>
                                        <td style="width: 50px;">
                                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                    <i class="uil uil-user"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="font-size-14 mb-1"><?= htmlspecialchars($activity['user_name']) ?></h6>
                                            <p class="text-muted mb-0 font-size-13">
                                                <?= htmlspecialchars($activity['action']) ?> - 
                                                <strong><?= htmlspecialchars($activity['table_name']) ?></strong>
                                            </p>
                                        </td>
                                        <td>
                                            <div class="text-end">
                                                <small class="text-muted"><?= timeAgo($activity['created_at']) ?></small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada aktivitas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= url('/admin/audit') ?>" class="btn btn-sm btn-soft-primary">
                        Lihat Semua <i class="uil-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0"><i class="uil-certificate-alt me-1"></i> Sertifikat Terbaru</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap mb-0">
                        <tbody>
                            <?php if (!empty($recent_certificates)): ?>
                                <?php foreach ($recent_certificates as $cert): ?>
                                    <tr>
                                        <td>
                                            <h6 class="font-size-14 mb-1"><?= htmlspecialchars($cert['product_name']) ?></h6>
                                            <p class="text-muted mb-0 font-size-13">
                                                <?= htmlspecialchars($cert['company_name']) ?>
                                            </p>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-<?= 
                                                $cert['status'] === 'completed' ? 'success' : 
                                                ($cert['status'] === 'rejected' ? 'danger' : 'warning')
                                            ?>">
                                                <?= ucfirst($cert['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?= url('/admin/certificates/view/' . $cert['id']) ?>" class="btn btn-sm btn-soft-primary">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada pengajuan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= url('/admin/certificates') ?>" class="btn btn-sm btn-soft-primary">
                        Lihat Semua <i class="uil-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Certificate Chart
<?php if (!empty($certificate_stats)): ?>
const ctx1 = document.getElementById('certificateChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($certificate_stats, 'date')) ?>,
        datasets: [{
            label: 'Pengajuan',
            data: <?= json_encode(array_column($certificate_stats, 'count')) ?>,
            borderColor: 'rgb(0, 104, 55)',
            backgroundColor: 'rgba(0, 104, 55, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
<?php endif; ?>

// Status Chart
<?php if (!empty($status_counts)): ?>
const ctx2 = document.getElementById('statusChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'In Review', 'Approved', 'Rejected', 'Completed'],
        datasets: [{
            data: [
                <?= $status_counts['pending'] ?? 0 ?>,
                <?= $status_counts['in_review'] ?? 0 ?>,
                <?= $status_counts['approved'] ?? 0 ?>,
                <?= $status_counts['rejected'] ?? 0 ?>,
                <?= $status_counts['completed'] ?? 0 ?>
            ],
            backgroundColor: [
                '#ffc107',
                '#17a2b8',
                '#28a745',
                '#dc3545',
                '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
<?php endif; ?>
</script>
