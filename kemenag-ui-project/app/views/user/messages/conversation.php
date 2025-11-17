<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">
                        <a href="<?= url('/dashboard/messages') ?>" class="text-white"><i class="uil-arrow-left"></i></a>
                        💬 Conversation with <?= htmlspecialchars($participant['full_name'] ?? $participant['username']) ?>
                    </h6>
                </div>
                <span class="badge bg-light text-dark"><?= $participant['role_name'] ?></span>
            </div>
            
            <div class="card-body" style="height: 500px; overflow-y: auto;" id="messages-container">
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="mb-3 <?= $message['sender_id'] == $_SESSION['user_id'] ? 'text-end' : '' ?>">
                            <div class="d-inline-block p-3 rounded <?= $message['sender_id'] == $_SESSION['user_id'] ? 'bg-primary text-white' : 'bg-light' ?>" 
                                 style="max-width: 70%;">
                                <p class="mb-1"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                                <small class="<?= $message['sender_id'] == $_SESSION['user_id'] ? 'text-white-50' : 'text-muted' ?>">
                                    <?= date('H:i', strtotime($message['created_at'])) ?>
                                    <?php if ($message['is_read']): ?><i class="uil-check-double"></i><?php endif; ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="uil-comments-alt font-size-48"></i>
                        <p class="mt-3">No messages yet. Start the conversation!</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-footer">
                <form action="<?= url('/dashboard/messages/send') ?>" method="POST" class="d-flex gap-2">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="receiver_id" value="<?= $participant['id'] ?>">
                    <textarea class="form-control" name="message" rows="2" placeholder="Type your message..." required></textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="uil-message"></i> Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto scroll to bottom
document.getElementById('messages-container').scrollTop = document.getElementById('messages-container').scrollHeight;
</script>
