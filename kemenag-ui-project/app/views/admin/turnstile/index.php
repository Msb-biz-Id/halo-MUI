<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between">
                <h4 class="page-title">Cloudflare Turnstile Security</h4>
                <div>
                    <a href="<?= url('admin/turnstile/test') ?>" class="btn btn-primary">
                        <i class="fas fa-flask"></i> Test Turnstile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-<?= $enabled ? 'success' : 'warning' ?>">
                <div class="d-flex align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-<?= $enabled ? 'shield-alt' : 'exclamation-triangle' ?>"></i>
                            Turnstile Status: <?= $enabled ? 'ACTIVE' : 'DISABLED' ?>
                        </h5>
                        <p class="mb-0">
                            <?php if ($enabled): ?>
                                Turnstile is protecting your site from bots and brute-force attacks.
                            <?php else: ?>
                                Turnstile is disabled. Enable it in .env to activate protection.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if ($enabled && !empty($site_key)): ?>
                    <div class="ms-auto">
                        <code>Site Key: <?= substr($site_key, 0, 20) ?>...</code>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Total Verifications</h5>
                    <h2><?= number_format($stats['total']) ?></h2>
                    <small class="text-muted">Last 7 days</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Success</h5>
                    <h2 class="text-success"><?= number_format($stats['success']) ?></h2>
                    <small class="text-muted">
                        <?= $stats['total'] > 0 ? round($stats['success'] / $stats['total'] * 100, 1) : 0 ?>% rate
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Failed</h5>
                    <h2 class="text-danger"><?= number_format($stats['failed']) ?></h2>
                    <small class="text-muted">
                        <?= $stats['total'] > 0 ? round($stats['failed'] / $stats['total'] * 100, 1) : 0 ?>% rate
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Suspicious IPs</h5>
                    <h2 class="text-warning"><?= count($suspicious) ?></h2>
                    <a href="<?= url('admin/turnstile/suspicious') ?>" class="text-decoration-none">
                        <small>View details →</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- By Day Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Verifications by Day</h5>
                </div>
                <div class="card-body">
                    <canvas id="verificationChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Types -->
    <?php if (!empty($stats['by_error'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Failed Verifications by Error Type</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Error Code</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['by_error'] as $code => $count): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($code) ?></code></td>
                                <td><?= $count ?></td>
                                <td><?= round($count / $stats['failed'] * 100, 1) ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Suspicious IPs Preview -->
    <?php if (!empty($suspicious)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Suspicious IPs (Top 10)</h5>
                    <a href="<?= url('admin/turnstile/suspicious') ?>" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>IP Address</th>
                                <th>Failed Attempts</th>
                                <th>First Seen</th>
                                <th>Last Seen</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($suspicious, 0, 10) as $ip): ?>
                            <tr>
                                <td><code><?= htmlspecialchars($ip['ip']) ?></code></td>
                                <td><span class="badge bg-danger"><?= $ip['failed_count'] ?></span></td>
                                <td><?= $ip['first_seen'] ?></td>
                                <td><?= $ip['last_seen'] ?></td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="blockIP('<?= $ip['ip'] ?>')">
                                        <i class="fas fa-ban"></i> Block
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Verification Chart
const ctx = document.getElementById('verificationChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_keys($stats['by_day'])) ?>,
        datasets: [
            {
                label: 'Success',
                data: <?= json_encode(array_column($stats['by_day'], 'success')) ?>,
                backgroundColor: 'rgba(40, 167, 69, 0.5)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            },
            {
                label: 'Failed',
                data: <?= json_encode(array_column($stats['by_day'], 'failed')) ?>,
                backgroundColor: 'rgba(220, 53, 69, 0.5)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function blockIP(ip) {
    if (confirm(`Block IP ${ip}? This will prevent all access from this IP.`)) {
        // TODO: Implement IP blocking
        alert('IP blocking feature coming soon!');
    }
}
</script>
