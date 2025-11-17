<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">💬 Messages</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Conversations</h6>
            </div>
            <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv): ?>
                        <a href="<?= url('/dashboard/messages/' . $conv['id']) ?>" 
                           class="list-group-item list-group-item-action <?= $conv['unread_count'] > 0 ? 'fw-bold' : '' ?>">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div><?= htmlspecialchars($conv['participant_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars(substr($conv['last_message'], 0, 50)) ?>...</small>
                                </div>
                                <?php if ($conv['unread_count'] > 0): ?>
                                    <span class="badge bg-primary"><?= $conv['unread_count'] ?></span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><?= timeAgo($conv['updated_at']) ?></small>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-3 text-center text-muted">
                        No conversations yet
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="uil-comments font-size-48 text-muted"></i>
                <p class="text-muted mt-3">Select a conversation to view messages</p>
            </div>
        </div>
    </div>
</div>
