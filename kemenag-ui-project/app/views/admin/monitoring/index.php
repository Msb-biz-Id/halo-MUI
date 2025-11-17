<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Error Monitoring</h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Total Errors (7 days)</h5>
                    <h2><?= $stats['total_errors'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Critical Count</h5>
                    <h2 class="text-danger"><?= $stats['critical_count'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Errors by Day</h5>
                    <canvas id="errorsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Types -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Errors by Type</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Error Type</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($stats['by_type'] ?? []) as $type => $count): ?>
                            <tr>
                                <td><?= htmlspecialchars($type) ?></td>
                                <td><?= $count ?></td>
                                <td><?= round($count / $stats['total_errors'] * 100, 2) ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart
const ctx = document.getElementById('errorsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($stats['by_day'] ?? [])) ?>,
        datasets: [{
            label: 'Errors',
            data: <?= json_encode(array_values($stats['by_day'] ?? [])) ?>,
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1
        }]
    }
});
</script>
