<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">📊 Activity History</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($activities)): ?>
                    <div class="activity-feed">
                        <?php foreach ($activities as $activity): ?>
                            <div class="feed-item border-start border-primary ps-3 pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?= htmlspecialchars($activity['description']) ?></strong>
                                        <p class="text-muted small mb-1"><?= htmlspecialchars($activity['details'] ?? '') ?></p>
                                        <small class="text-muted">
                                            <i class="uil-clock"></i> <?= timeAgo($activity['created_at']) ?>
                                        </small>
                                    </div>
                                    <div>
                                        <?php
                                        $colors = [
                                            'login' => 'info',
                                            'create' => 'success',
                                            'update' => 'warning',
                                            'delete' => 'danger'
                                        ];
                                        $color = $colors[$activity['action']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $color ?>"><?= strtoupper($activity['action']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="uil-history font-size-48 text-muted"></i>
                        <p class="text-muted mt-3">No activity recorded yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
