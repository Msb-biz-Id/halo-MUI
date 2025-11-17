<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between">
                <h4 class="page-title">Backup Management</h4>
                <a href="<?= url('admin/backup/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Backup
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Backups</h5>
                    <h2><?= $stats['total_backups'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Size</h5>
                    <h2><?= $stats['total_size'] ?? '0 B' ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Newest Backup</h5>
                    <p><?= $stats['newest_backup'] ?? 'N/A' ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Oldest Backup</h5>
                    <p><?= $stats['oldest_backup'] ?? 'N/A' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup List -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Available Backups</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>Created</th>
                        <th>Age</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($backups as $backup): ?>
                    <tr>
                        <td><?= htmlspecialchars($backup['filename']) ?></td>
                        <td><?= $backup['size'] ?></td>
                        <td><?= $backup['created'] ?></td>
                        <td><?= $backup['age_days'] ?> days</td>
                        <td>
                            <a href="<?= url('admin/backup/download/' . urlencode($backup['filename'])) ?>" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-download"></i>
                            </a>
                            <a href="<?= url('admin/backup/restore/' . urlencode($backup['filename'])) ?>" 
                               class="btn btn-sm btn-warning"
                               onclick="return confirm('Are you sure? This will overwrite current data!')">
                                <i class="fas fa-undo"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
