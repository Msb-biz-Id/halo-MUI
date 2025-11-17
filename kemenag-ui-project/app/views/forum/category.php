<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h2 class="page-title"><?= htmlspecialchars($category['name']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($category['description']) ?></p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5><?= count($topics) ?> Topics</h5>
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= url('/forum/create') ?>?category=<?= $category['id'] ?>" class="btn btn-primary">
                    <i class="uil-plus"></i> New Topic
                </a>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($topics)): ?>
            <?php foreach ($topics as $topic): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h5 class="mb-2">
                                    <a href="<?= url('/forum/topic/' . $topic['id']) ?>" class="text-dark">
                                        <?= htmlspecialchars($topic['title']) ?>
                                    </a>
                                    <?php if ($topic['is_locked']): ?>
                                        <span class="badge bg-danger ms-2">Locked</span>
                                    <?php endif; ?>
                                    <?php if ($topic['is_pinned']): ?>
                                        <span class="badge bg-warning ms-2">Pinned</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="text-muted mb-2"><?= nl2br(htmlspecialchars(substr($topic['content'], 0, 200))) ?>...</p>
                                <small class="text-muted">
                                    By <strong><?= htmlspecialchars($topic['author_name']) ?></strong> •
                                    <?= timeAgo($topic['created_at']) ?> •
                                    <?= $topic['reply_count'] ?> replies •
                                    <?= $topic['view_count'] ?> views
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="uil-info-circle"></i> No topics in this category yet. Be the first to start a discussion!
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-lg-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Categories</h6>
            </div>
            <div class="list-group list-group-flush">
                <?php foreach ($all_categories as $cat): ?>
                    <a href="<?= url('/forum/category/' . $cat['id']) ?>" 
                       class="list-group-item list-group-item-action <?= $cat['id'] == $category['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                        <span class="badge bg-secondary float-end"><?= $cat['topic_count'] ?? 0 ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
