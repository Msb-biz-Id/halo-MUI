<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📊 Blacklist Detection Logs</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/blacklist') ?>">Blacklist</a></li>
                    <li class="breadcrumb-item active">Logs</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="card-title mb-0">Detection History (<?= count($logs) ?>)</h5>
                    </div>
                    <div>
                        <form action="<?= url('/admin/blacklist/detection-logs') ?>" method="GET" class="d-flex gap-2">
                            <select name="action" class="form-select" onchange="this.form.submit()">
                                <option value="">All Actions</option>
                                <option value="flagged" <?= $action_filter == 'flagged' ? 'selected' : '' ?>>Flagged</option>
                                <option value="blocked" <?= $action_filter == 'blocked' ? 'selected' : '' ?>>Blocked</option>
                                <option value="auto_rejected" <?= $action_filter == 'auto_rejected' ? 'selected' : '' ?>>Auto Rejected</option>
                            </select>
                            
                            <select name="content_type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                <option value="forum_topic" <?= $content_filter == 'forum_topic' ? 'selected' : '' ?>>Forum Topic</option>
                                <option value="forum_post" <?= $content_filter == 'forum_post' ? 'selected' : '' ?>>Forum Post</option>
                                <option value="comment" <?= $content_filter == 'comment' ? 'selected' : '' ?>>Comment</option>
                            </select>
                            
                            <input type="date" name="date" class="form-control" value="<?= $date_filter ?>" onchange="this.form.submit()">
                        </form>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Content Type</th>
                                <th>Detected Words</th>
                                <th>Action Taken</th>
                                <th class="text-end">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td>
                                            <small><?= date('d M Y H:i', strtotime($log['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($log['username']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($log['email']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">
                                                <?= ucwords(str_replace('_', ' ', $log['content_type'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $words = json_decode($log['detected_words'], true);
                                            if (is_array($words)):
                                                foreach (array_slice($words, 0, 3) as $word):
                                            ?>
                                                <span class="badge bg-danger me-1"><?= htmlspecialchars($word['word'] ?? $word) ?></span>
                                            <?php
                                                endforeach;
                                                if (count($words) > 3):
                                            ?>
                                                <span class="badge bg-secondary">+<?= count($words) - 3 ?> more</span>
                                            <?php endif; endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $actionColors = [
                                                'flagged' => 'warning',
                                                'blocked' => 'danger',
                                                'auto_rejected' => 'danger'
                                            ];
                                            $color = $actionColors[$log['action_taken']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>">
                                                <?= strtoupper(str_replace('_', ' ', $log['action_taken'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="showContent(<?= htmlspecialchars(json_encode($log), ENT_QUOTES) ?>)">
                                                <i class="uil-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="uil-file-times font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">No detections logged</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Modal -->
<div class="modal fade" id="contentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detected Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>User:</strong> <span id="modal-user"></span>
                </div>
                <div class="mb-3">
                    <strong>Detected Words:</strong>
                    <div id="modal-words"></div>
                </div>
                <div class="mb-3">
                    <strong>Original Content:</strong>
                    <div class="bg-light p-3 border" id="modal-content" style="max-height: 300px; overflow-y: auto;"></div>
                </div>
                <div class="mb-3">
                    <strong>IP Address:</strong> <code id="modal-ip"></code>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showContent(log) {
    document.getElementById('modal-user').textContent = log.username + ' (' + log.email + ')';
    document.getElementById('modal-content').textContent = log.original_content;
    document.getElementById('modal-ip').textContent = log.ip_address || 'N/A';
    
    const words = JSON.parse(log.detected_words);
    const wordsHtml = words.map(w => {
        const word = typeof w === 'object' ? w.word : w;
        return `<span class="badge bg-danger me-1">${word}</span>`;
    }).join('');
    document.getElementById('modal-words').innerHTML = wordsHtml;
    
    new bootstrap.Modal(document.getElementById('contentModal')).show();
}
</script>
