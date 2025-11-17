<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Queue Management</h4>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Pending Jobs</h5>
                    <h2 class="text-warning"><?= $stats['pending'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Failed Jobs</h5>
                    <h2 class="text-danger"><?= $stats['failed'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Worker Status</h5>
                    <h2 class="text-success">Running</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- By Queue -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Jobs by Queue</h4>
            <a href="<?= url('admin/queue/clear/all') ?>" 
               class="btn btn-danger btn-sm"
               onclick="return confirm('Clear ALL queues?')">
                Clear All
            </a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Queue Name</th>
                        <th>Pending Jobs</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($stats['by_queue'] ?? []) as $queue => $count): ?>
                    <tr>
                        <td><?= htmlspecialchars($queue) ?></td>
                        <td><?= $count ?></td>
                        <td>
                            <a href="<?= url("admin/queue/clear/{$queue}") ?>" 
                               class="btn btn-sm btn-warning"
                               onclick="return confirm('Clear this queue?')">
                                Clear
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
