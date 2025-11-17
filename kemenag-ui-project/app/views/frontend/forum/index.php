<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-2"><i class="fas fa-comments text-success"></i> Forum Diskusi</h2>
                    <p class="text-muted">Bergabung dalam diskusi dengan komunitas</p>
                </div>
                <?php if (auth()): ?>
                    <a href="<?= url('/forum/create-topic') ?>" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Buat Topik Baru
                    </a>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="btn btn-success">
                        <i class="fas fa-sign-in-alt"></i> Login untuk Membuat Topik
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Categories -->
    <div class="row">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?= url('/forum/category/' . $category['id']) ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($category['name']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small"><?= htmlspecialchars($category['description']) ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-primary"><?= $category['topic_count'] ?? 0 ?> Topik</span>
                                <a href="<?= url('/forum/category/' . $category['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    Lihat <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada kategori forum</div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Recent Topics -->
    <?php if (!empty($recent_topics)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="fw-bold mb-3"><i class="fas fa-clock text-warning"></i> Topik Terbaru</h4>
                
                <div class="card border-0 shadow-sm">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recent_topics as $topic): ?>
                            <a href="<?= url('/forum/topic/' . $topic['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <?php if ($topic['is_pinned']): ?>
                                                <i class="fas fa-thumbtack text-warning"></i>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($topic['title']) ?>
                                            <?php if ($topic['is_locked']): ?>
                                                <i class="fas fa-lock text-danger"></i>
                                            <?php endif; ?>
                                        </h6>
                                        <p class="mb-1 small text-muted">
                                            oleh <strong><?= htmlspecialchars($topic['full_name'] ?? $topic['username']) ?></strong> 
                                            di <span class="text-primary"><?= htmlspecialchars($topic['category_name']) ?></span>
                                        </p>
                                    </div>
                                    <div class="text-end ms-3">
                                        <small class="text-muted d-block"><?= timeAgo($topic['last_activity_at'] ?? $topic['created_at']) ?></small>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-comments"></i> <?= $topic['reply_count'] ?? 0 ?> 
                                        </span>
                                        <span class="badge bg-info">
                                            <i class="fas fa-eye"></i> <?= $topic['view_count'] ?? 0 ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.hover-card {
    transition: all 0.3s;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
</style>
