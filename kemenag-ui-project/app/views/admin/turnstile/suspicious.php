<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title">Suspicious IP Addresses</h4>
                    <p class="text-muted mb-0">IPs with multiple failed Turnstile verifications</p>
                </div>
                <div>
                    <a href="<?= url('admin/turnstile') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Minimum Failed Attempts</label>
                            <input type="number" class="form-control" name="threshold" 
                                   value="<?= $_GET['threshold'] ?? 5 ?>" min="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Time Window (Hours)</label>
                            <input type="number" class="form-control" name="hours" 
                                   value="<?= $_GET['hours'] ?? 24 ?>" min="1" max="168">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-filter"></i> Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert if no suspicious IPs -->
    <?php if (empty($suspicious)): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <strong>All Clear!</strong> No suspicious IP addresses detected in the specified time window.
            </div>
        </div>
    </div>
    <?php else: ?>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Total Suspicious IPs</h5>
                    <h2 class="text-warning"><?= count($suspicious) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Highest Failed Count</h5>
                    <h2 class="text-danger">
                        <?= !empty($suspicious) ? max(array_column($suspicious, 'failed_count')) : 0 ?>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Time Window</h5>
                    <h2><?= $hours ?> hours</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Threshold</h5>
                    <h2><?= $threshold ?> attempts</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Suspicious IPs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Suspicious IP List</h5>
                    <div>
                        <button class="btn btn-sm btn-danger" onclick="blockSelected()">
                            <i class="fas fa-ban"></i> Block Selected
                        </button>
                        <button class="btn btn-sm btn-success" onclick="exportToCSV()">
                            <i class="fas fa-download"></i> Export CSV
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="suspiciousTable">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" id="selectAll" onchange="toggleAll(this)">
                                    </th>
                                    <th>IP Address</th>
                                    <th>Failed Attempts</th>
                                    <th>First Seen</th>
                                    <th>Last Seen</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suspicious as $index => $ip): ?>
                                <?php
                                $first = strtotime($ip['first_seen']);
                                $last = strtotime($ip['last_seen']);
                                $duration = $last - $first;
                                $durationStr = $duration > 3600 
                                    ? round($duration / 3600, 1) . ' hours' 
                                    : round($duration / 60) . ' minutes';
                                
                                $severity = $ip['failed_count'] >= 20 ? 'danger' : 
                                           ($ip['failed_count'] >= 10 ? 'warning' : 'secondary');
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="ip-checkbox" value="<?= htmlspecialchars($ip['ip']) ?>">
                                    </td>
                                    <td>
                                        <code><?= htmlspecialchars($ip['ip']) ?></code>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $severity ?> fs-6">
                                            <?= $ip['failed_count'] ?>
                                        </span>
                                    </td>
                                    <td><?= $ip['first_seen'] ?></td>
                                    <td><?= $ip['last_seen'] ?></td>
                                    <td><?= $durationStr ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info" onclick="viewDetails('<?= htmlspecialchars($ip['ip']) ?>')" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="blockIP('<?= htmlspecialchars($ip['ip']) ?>')"
                                                    title="Block IP">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            <button class="btn btn-success" onclick="whitelistIP('<?= htmlspecialchars($ip['ip']) ?>')"
                                                    title="Whitelist">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<script>
// Toggle all checkboxes
function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.ip-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

// View IP details
function viewDetails(ip) {
    alert(`Viewing details for IP: ${ip}\n\nThis feature will show:\n- All verification attempts\n- Timestamps\n- Error codes\n- User agent strings`);
    // TODO: Implement details modal
}

// Block single IP
function blockIP(ip) {
    if (confirm(`Block IP: ${ip}?\n\nThis will:\n- Add to firewall blocklist\n- Reject all future requests\n- Log the action`)) {
        // TODO: Implement IP blocking
        fetch('<?= url('admin/turnstile/block-ip') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ip: ip })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('IP blocked successfully!');
                location.reload();
            } else {
                alert('Failed to block IP: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
}

// Whitelist IP
function whitelistIP(ip) {
    if (confirm(`Whitelist IP: ${ip}?\n\nThis will:\n- Remove from suspicious list\n- Allow all future requests\n- Bypass Turnstile verification`)) {
        // TODO: Implement whitelisting
        alert('Whitelist feature coming soon!');
    }
}

// Block selected IPs
function blockSelected() {
    const checkboxes = document.querySelectorAll('.ip-checkbox:checked');
    const ips = Array.from(checkboxes).map(cb => cb.value);
    
    if (ips.length === 0) {
        alert('Please select at least one IP address');
        return;
    }
    
    if (confirm(`Block ${ips.length} IP address(es)?`)) {
        alert('Bulk block feature coming soon!');
        // TODO: Implement bulk blocking
    }
}

// Export to CSV
function exportToCSV() {
    const table = document.getElementById('suspiciousTable');
    let csv = [];
    
    // Headers
    csv.push(['IP Address', 'Failed Attempts', 'First Seen', 'Last Seen'].join(','));
    
    // Data rows
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        const ip = cols[1].textContent.trim();
        const count = cols[2].textContent.trim();
        const first = cols[3].textContent.trim();
        const last = cols[4].textContent.trim();
        csv.push([ip, count, first, last].join(','));
    });
    
    // Download
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'suspicious_ips_' + new Date().toISOString().split('T')[0] + '.csv';
    a.click();
}
</script>

<style>
.table td, .table th {
    vertical-align: middle;
}
.badge {
    padding: 0.5em 0.75em;
}
</style>
