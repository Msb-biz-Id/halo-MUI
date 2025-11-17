<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between">
                <h4 class="page-title">Backup Schedules</h4>
                <a href="<?= url('admin/backup/schedule/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Schedule
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Frequency</th>
                        <th>Time</th>
                        <th>What to Backup</th>
                        <th>Retention</th>
                        <th>Status</th>
                        <th>Last Run</th>
                        <th>Next Run</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><?= htmlspecialchars($schedule['name']) ?></td>
                        <td>
                            <span class="badge bg-info"><?= ucfirst($schedule['frequency']) ?></span>
                            <?php if ($schedule['frequency'] === 'weekly'): ?>
                                <br><small class="text-muted">Day: <?= $schedule['day_of_week'] ?></small>
                            <?php elseif ($schedule['frequency'] === 'monthly'): ?>
                                <br><small class="text-muted">Day: <?= $schedule['day_of_month'] ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= date('H:i', strtotime($schedule['time'])) ?></td>
                        <td>
                            <?php if ($schedule['backup_database']): ?>
                                <span class="badge bg-primary">DB</span>
                            <?php endif; ?>
                            <?php if ($schedule['backup_files']): ?>
                                <span class="badge bg-primary">Files</span>
                            <?php endif; ?>
                            <?php if ($schedule['backup_uploads']): ?>
                                <span class="badge bg-primary">Uploads</span>
                            <?php endif; ?>
                            <?php if ($schedule['backup_logs']): ?>
                                <span class="badge bg-primary">Logs</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $schedule['retention_days'] ?> days</td>
                        <td>
                            <span class="badge bg-<?= $schedule['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($schedule['status']) ?>
                            </span>
                        </td>
                        <td><?= $schedule['last_run'] ? date('Y-m-d H:i', strtotime($schedule['last_run'])) : '-' ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($schedule['next_run'])) ?></td>
                        <td>
                            <button onclick="runNow(<?= $schedule['id'] ?>)" 
                                    class="btn btn-sm btn-success" title="Run Now">
                                <i class="fas fa-play"></i>
                            </button>
                            <button onclick="toggleSchedule(<?= $schedule['id'] ?>)" 
                                    class="btn btn-sm btn-<?= $schedule['status'] === 'active' ? 'warning' : 'info' ?>" 
                                    title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <a href="<?= url('admin/backup/schedule/edit/' . $schedule['id']) ?>" 
                               class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteSchedule(<?= $schedule['id'] ?>)" 
                                    class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function runNow(id) {
    if (confirm('Run backup now?')) {
        location.href = `<?= url('admin/backup/schedule/run/') ?>${id}`;
    }
}

function toggleSchedule(id) {
    fetch(`<?= url('admin/backup/schedule/toggle/') ?>${id}`, {method: 'POST'})
        .then(() => location.reload());
}

function deleteSchedule(id) {
    if (confirm('Delete this schedule?')) {
        location.href = `<?= url('admin/backup/schedule/delete/') ?>${id}`;
    }
}
</script>
