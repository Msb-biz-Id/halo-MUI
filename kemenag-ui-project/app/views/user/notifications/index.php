<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex justify-content-between align-items-center">
            <h4 class="mb-0">🔔 Notifications</h4>
            <?php if (!empty($notifications)): ?>
                <a href="<?= url('/dashboard/notifications/mark-all-read') ?>" class="btn btn-sm btn-outline-primary">
                    Mark All Read
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="list-group list-group-flush">
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notif): ?>
                        <a href="<?= $notif['link'] ?? '#' ?>" 
                           class="list-group-item list-group-item-action <?= !$notif['is_read'] ? 'bg-light-blue' : '' ?>">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary text-white rounded-circle">
                                            <?php
                                            $icons = [
                                                'certificate' => 'certificate',
                                                'forum' => 'comments',
                                                'message' => 'envelope',
                                                'system' => 'bell'
                                            ];
                                            $icon = $icons[$notif['type']] ?? 'info-circle';
                                            ?>
                                            <i class="uil-<?= $icon ?>"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 <?= !$notif['is_read'] ? 'fw-bold' : '' ?>">
                                        <?= htmlspecialchars($notif['title']) ?>
                                    </h6>
                                    <p class="text-muted mb-1"><?= htmlspecialchars($notif['message']) ?></p>
                                    <small class="text-muted"><?= timeAgo($notif['created_at']) ?></small>
                                </div>
                                <?php if (!$notif['is_read']): ?>
                                    <span class="badge bg-primary">New</span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-5 text-center">
                        <i class="uil-bell-slash font-size-48 text-muted"></i>
                        <p class="text-muted mt-3">No notifications yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Notification Settings</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">Manage your notification preferences</p>
                <a href="<?= url('/dashboard/profile/notification-settings') ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="uil-cog"></i> Settings
                </a>
            </div>
        </div>
    </div>
</div>
