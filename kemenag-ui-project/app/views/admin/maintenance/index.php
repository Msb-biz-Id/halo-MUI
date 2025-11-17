<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Maintenance Mode</h4>
            </div>
        </div>
    </div>

    <?php if ($settings['enabled'] ?? false): ?>
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-triangle"></i> Maintenance Mode is ACTIVE</h5>
        <p>Visitors will see the maintenance page. Only admin users and allowed IPs can access the site.</p>
        <form method="POST" action="<?= url('admin/maintenance/disable') ?>">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Disable Maintenance Mode
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Current Settings</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th width="200">Title:</th>
                    <td><?= htmlspecialchars($settings['title'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Message:</th>
                    <td><?= nl2br(htmlspecialchars($settings['message'] ?? '')) ?></td>
                </tr>
                <tr>
                    <th>Retry After:</th>
                    <td><?= gmdate('H:i:s', $settings['retry_after'] ?? 0) ?></td>
                </tr>
                <tr>
                    <th>Allowed IPs:</th>
                    <td>
                        <?php if (!empty($settings['allowed_ips'])): ?>
                            <?php foreach ($settings['allowed_ips'] as $ip): ?>
                                <code><?= htmlspecialchars($ip) ?></code><br>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <em>None</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Enabled At:</th>
                    <td><?= $settings['enabled_at'] ?? '-' ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php else: ?>
    <div class="alert alert-success">
        <h5><i class="fas fa-check-circle"></i> Site is Online</h5>
        <p>Maintenance mode is currently disabled. All visitors can access the site normally.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Enable Maintenance Mode</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= url('admin/maintenance/enable') ?>">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" 
                           value="Site Under Maintenance" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="4" required>We are currently performing scheduled maintenance.
We will be back soon!

Thank you for your patience.</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Retry After (seconds)</label>
                    <input type="number" name="retry_after" class="form-control" 
                           value="3600" min="0">
                    <small class="text-muted">Estimated downtime in seconds (3600 = 1 hour)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Allowed IPs (Optional)</label>
                    <textarea name="allowed_ips" class="form-control" rows="4" 
                              placeholder="192.168.1.1&#10;10.0.0.5"></textarea>
                    <small class="text-muted">One IP per line. These IPs can access site during maintenance.</small>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-exclamation-triangle"></i> Enable Maintenance Mode
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>
