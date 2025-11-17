<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">📝 My Forum Topics</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($topics)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Replies</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topics as $topic): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url('/forum/topic/' . $topic['id']) ?>">
                                                <?= htmlspecialchars($topic['title']) ?>
                                            </a>
                                            <?php if ($topic['is_pinned']): ?>
                                                <span class="badge bg-warning ms-1">Pinned</span>
                                            <?php endif; ?>
                                            <?php if ($topic['is_locked']): ?>
                                                <span class="badge bg-danger ms-1">Locked</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($topic['category_name']) ?></td>
                                        <td>
                                            <?php
                                            $colors = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                            $color = $colors[$topic['status'] ?? 'approved'] ?? 'success';
                                            ?>
                                            <span class="badge bg-<?= $color ?>">
                                                <?= strtoupper($topic['status'] ?? 'approved') ?>
                                            </span>
                                        </td>
                                        <td><?= $topic['reply_count'] ?></td>
                                        <td><small><?= date('d M Y', strtotime($topic['created_at'])) ?></small></td>
                                        <td>
                                            <a href="<?= url('/forum/topic/' . $topic['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="uil-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="uil-file-times font-size-48 text-muted"></i>
                        <p class="text-muted mt-3">You haven't created any topics yet.</p>
                        <a href="<?= url('/forum/create') ?>" class="btn btn-primary">
                            <i class="uil-plus-circle"></i> Create First Topic
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
