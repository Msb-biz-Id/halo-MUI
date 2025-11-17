<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="fw-bold mb-2"><i class="fas fa-question-circle text-info"></i> Tanya Jawab Keagamaan</h2>
            <p class="text-muted">Temukan jawaban atas pertanyaan keagamaan Anda</p>
        </div>
    </div>
    
    <!-- Categories -->
    <?php if (!empty($categories)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-folder"></i> Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($categories as $category): ?>
                                <div class="col-md-4">
                                    <a href="<?= url('/qa/category/' . $category['id']) ?>" 
                                       class="btn btn-outline-primary w-100 text-start">
                                        <?= htmlspecialchars($category['name']) ?>
                                        <span class="badge bg-primary float-end"><?= $category['qa_count'] ?? 0 ?></span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Latest Q&A -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Tanya Jawab Terbaru</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (!empty($latest_qa)): ?>
                        <?php foreach ($latest_qa as $qa): ?>
                            <a href="<?= url('/qa/detail/' . $qa['id']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= htmlspecialchars($qa['question']) ?></h6>
                                        <p class="mb-1 small text-muted">
                                            <?= truncate(strip_tags($qa['answer']), 150) ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> <?= htmlspecialchars($qa['category_name']) ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-user"></i> <?= htmlspecialchars($qa['author_name'] ?? 'Admin') ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-eye"></i> <?= $qa['view_count'] ?> views
                                        </small>
                                    </div>
                                    <small class="text-muted ms-3"><?= timeAgo($qa['created_at']) ?></small>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada tanya jawab</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Popular Q&A Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-fire"></i> Populer</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (!empty($popular_qa)): ?>
                        <?php foreach ($popular_qa as $qa): ?>
                            <a href="<?= url('/qa/detail/' . $qa['id']) ?>" class="list-group-item list-group-item-action">
                                <h6 class="mb-1 small"><?= htmlspecialchars($qa['question']) ?></h6>
                                <small class="text-muted">
                                    <i class="fas fa-eye"></i> <?= $qa['view_count'] ?> views
                                    <span class="mx-1">•</span>
                                    <?= htmlspecialchars($qa['category_name']) ?>
                                </small>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted">
                            No popular Q&A yet
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Search Box -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-search"></i> Cari</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/qa/search') ?>" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Cari pertanyaan..." required>
                            <button type="submit" class="btn btn-info text-white">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
