<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">📊 Audit Dashboard</h4>
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
                            <div class="avatar-title bg-primary text-white rounded-3">
                                <i class="uil-file-alt font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Logs</p>
                        <h4 class="mb-0"><?= number_format($stats['total_logs'] ?? 0) ?></h4>
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
                                <i class="uil-plus-circle font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Creates Today</p>
                        <h4 class="mb-0"><?= number_format($stats['creates_today'] ?? 0) ?></h4>
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
                                <i class="uil-trash font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Deletes Today</p>
                        <h4 class="mb-0"><?= number_format($stats['deletes_today'] ?? 0) ?></h4>
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
                                <i class="uil-users-alt font-size-24"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Active Users</p>
                        <h4 class="mb-0"><?= number_format($stats['active_users'] ?? 0) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Entity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_logs)): ?>
                                <?php foreach (array_slice($recent_logs, 0, 10) as $log): ?>
                                    <tr>
                                        <td><small><?= date('H:i:s', strtotime($log['created_at'])) ?></small></td>
                                        <td><?= htmlspecialchars($log['username']) ?></td>
                                        <td>
                                            <?php
                                            $colors = ['create' => 'success', 'update' => 'info', 'delete' => 'danger'];
                                            $color = $colors[$log['action']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>"><?= strtoupper($log['action']) ?></span>
                                        </td>
                                        <td><small><?= htmlspecialchars($log['entity_type']) ?></small></td>
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
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Top Users</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php if (!empty($top_users)): ?>
                        <?php foreach (array_slice($top_users, 0, 5) as $user): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($user['username']) ?></strong>
                                    <br><small class="text-muted"><?= htmlspecialchars($user['role_name']) ?></small>
                                </div>
                                <span class="badge bg-primary"><?= $user['action_count'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
