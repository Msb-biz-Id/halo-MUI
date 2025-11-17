<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Detail Topik Forum</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/forum') ?>">Forum Moderation</a></li>
                    <li class="breadcrumb-item active">View Topic</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Topic Content -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3 class="mb-2"><?= htmlspecialchars($topic['title']) ?></h3>
                        <span class="badge bg-primary"><?= htmlspecialchars($topic['category_name']) ?></span>
                    </div>
                    <div>
                        <?php if ($topic['is_approved']): ?>
                            <span class="badge bg-success font-size-14">Approved</span>
                        <?php else: ?>
                            <span class="badge bg-warning font-size-14">Pending Approval</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Author:</strong> <?= htmlspecialchars($topic['full_name'] ?? $topic['username']) ?> (<?= $topic['email'] ?>)<br>
                    <strong>Posted:</strong> <?= date('d M Y H:i', strtotime($topic['created_at'])) ?><br>
                    <strong>Views:</strong> <?= $topic['view_count'] ?> | <strong>Replies:</strong> <?= $topic['reply_count'] ?>
                </div>
                
                <hr>
                
                <div class="topic-content">
                    <?= nl2br(htmlspecialchars($topic['content'])) ?>
                </div>
            </div>
        </div>
        
        <!-- Blacklist Check Results -->
        <?php if (!empty($blacklist_check)): ?>
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="uil-exclamation-triangle"></i> Blacklist Detection</h5>
                </div>
                <div class="card-body">
                    <?php if ($blacklist_check['has_blacklist']): ?>
                        <div class="alert alert-danger mb-3">
                            <strong>⚠️ WARNING:</strong> This topic contains <strong><?= count($blacklist_check['detected_words']) ?></strong> blacklisted word(s)!
                        </div>
                        
                        <h6>Detected Words:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Word</th>
                                        <th>Type</th>
                                        <th>Severity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($blacklist_check['detected_words'] as $detected): ?>
                                        <tr>
                                            <td><code><?= htmlspecialchars($detected['word']) ?></code></td>
                                            <td><span class="badge bg-secondary"><?= $detected['type'] ?></span></td>
                                            <td>
                                                <?php
                                                $severityColor = [
                                                    'low' => 'info',
                                                    'medium' => 'warning',
                                                    'high' => 'orange',
                                                    'critical' => 'danger'
                                                ];
                                                ?>
                                                <span class="badge bg-<?= $severityColor[$detected['severity']] ?>">
                                                    <?= strtoupper($detected['severity']) ?>
                                                </span>
                                            </td>
                                            <td><span class="badge bg-dark"><?= $detected['action'] ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <p class="mb-0"><strong>Recommended Action:</strong> 
                            <span class="text-<?= $blacklist_check['action'] == 'auto_reject' ? 'danger' : 'warning' ?>">
                                <?= strtoupper($blacklist_check['action']) ?>
                            </span>
                        </p>
                    <?php else: ?>
                        <div class="alert alert-success mb-0">
                            <i class="uil-check-circle"></i> <strong>Clean!</strong> No blacklisted words detected in this topic.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Replies -->
        <?php if (!empty($posts)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Replies (<?= count($posts) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($posts as $post): ?>
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= htmlspecialchars($post['full_name'] ?? $post['username']) ?></strong>
                                    <br><small class="text-muted"><?= timeAgo($post['created_at']) ?></small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDeletePost(<?= $post['id'] ?>)">
                                        <i class="uil-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Moderation Actions -->
        <?php if (!$topic['is_approved']): ?>
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-white"><i class="uil-clipboard-alt"></i> Moderation Actions</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/admin/forum/approve/' . $topic['id']) ?>" method="POST" class="mb-2">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="uil-check"></i> Approve Topic
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="uil-times"></i> Reject Topic
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                <i class="uil-check-circle"></i> <strong>Approved</strong><br>
                by <?= htmlspecialchars($topic['approver_name'] ?? 'Admin') ?><br>
                on <?= date('d M Y H:i', strtotime($topic['approved_at'])) ?>
            </div>
        <?php endif; ?>
        
        <!-- Other Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="uil-setting"></i> Other Actions</h5>
            </div>
            <div class="card-body">
                <a href="<?= url('/admin/forum/toggle-lock/' . $topic['id']) ?>" class="btn btn-warning w-100 mb-2">
                    <i class="uil-<?= $topic['is_locked'] ? 'lock-open-alt' : 'lock' ?>"></i> 
                    <?= $topic['is_locked'] ? 'Unlock' : 'Lock' ?> Topic
                </a>
                
                <a href="<?= url('/admin/forum/toggle-pin/' . $topic['id']) ?>" class="btn btn-info w-100 mb-2">
                    <i class="uil-thumbtack"></i> 
                    <?= $topic['is_pinned'] ? 'Unpin' : 'Pin' ?> Topic
                </a>
                
                <a href="<?= url('/forum/topic/' . $topic['id']) ?>" class="btn btn-primary w-100 mb-2" target="_blank">
                    <i class="uil-external-link-alt"></i> View Public
                </a>
                
                <hr>
                
                <button type="button" class="btn btn-danger w-100" onclick="confirmDelete()">
                    <i class="uil-trash"></i> Delete Topic
                </button>
            </div>
        </div>
        
        <!-- Topic Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="uil-chart-line"></i> Statistics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Views:</span>
                    <strong><?= $topic['view_count'] ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Replies:</span>
                    <strong><?= $topic['reply_count'] ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Last Activity:</span>
                    <strong><?= timeAgo($topic['last_activity_at'] ?? $topic['created_at']) ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= url('/admin/forum/reject/' . $topic['id']) ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Topic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="4" required 
                                  placeholder="Explain why this topic is rejected..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Topic</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Delete this topic permanently? This action cannot be undone!')) {
        window.location.href = '<?= url('/admin/forum/delete/' . $topic['id']) ?>';
    }
}

function confirmDeletePost(postId) {
    if (confirm('Delete this reply?')) {
        window.location.href = '<?= url('/admin/forum/delete-post/') ?>/' + postId;
    }
}
</script>
