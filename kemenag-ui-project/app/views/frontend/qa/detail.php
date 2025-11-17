<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <!-- Q&A Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-question-circle"></i> <?= htmlspecialchars($qa['question']) ?></h3>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <span class="badge bg-primary-subtle text-primary me-2">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($qa['category_name']) ?>
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($qa['author_name'] ?? 'Admin') ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> <?= date('d M Y', strtotime($qa['created_at'])) ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-eye"></i> <?= $qa['view_count'] ?> views
                            </small>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-3"><i class="fas fa-comments text-success"></i> Jawaban:</h5>
                    <div class="answer-content">
                        <?= nl2br(htmlspecialchars($qa['answer'])) ?>
                    </div>
                    
                    <?php if (!empty($qa['references'])): ?>
                        <hr class="my-4">
                        <h6 class="fw-bold"><i class="fas fa-book"></i> Referensi:</h6>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($qa['references'])) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-thumbs-up"></i> Helpful
                            </button>
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-share-alt"></i> Share
                            </button>
                        </div>
                        <a href="<?= url('/qa') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Q&A -->
            <?php if (!empty($related)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-link"></i> Terkait</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($related as $rel): ?>
                            <a href="<?= url('/qa/detail/' . $rel['id']) ?>" class="list-group-item list-group-item-action">
                                <h6 class="mb-1 small"><?= htmlspecialchars($rel['question']) ?></h6>
                                <small class="text-muted">
                                    <i class="fas fa-eye"></i> <?= $rel['view_count'] ?> views
                                </small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Back to Category -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <a href="<?= url('/qa/category/' . $qa['category_id']) ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-folder"></i> Lihat semua di <?= htmlspecialchars($qa['category_name']) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.answer-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}
</style>
