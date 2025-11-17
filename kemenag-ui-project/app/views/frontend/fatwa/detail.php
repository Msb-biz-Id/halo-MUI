<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <!-- Fatwa Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2"><?= htmlspecialchars($fatwa['title']) ?></h3>
                            <?php if (!empty($fatwa['fatwa_number'])): ?>
                                <h5 class="mb-0"><span class="badge bg-light text-dark">No. <?= $fatwa['fatwa_number'] ?></span></h5>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <span class="badge bg-danger-subtle text-danger me-2">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($fatwa['category_name'] ?? 'Umum') ?>
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($fatwa['published_at'] ?? $fatwa['created_at'])) ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-eye"></i> <?= $fatwa['view_count'] ?? 0 ?> views
                            </small>
                        </div>
                    </div>
                    
                    <?php if (!empty($fatwa['summary'])): ?>
                        <div class="alert alert-info">
                            <h6 class="fw-bold"><i class="fas fa-info-circle"></i> Ringkasan:</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($fatwa['summary'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <h5 class="fw-bold mb-3"><i class="fas fa-balance-scale text-danger"></i> Isi Fatwa:</h5>
                    <div class="fatwa-content">
                        <?= nl2br(htmlspecialchars($fatwa['content'])) ?>
                    </div>
                    
                    <?php if (!empty($fatwa['legal_basis'])): ?>
                        <hr class="my-4">
                        <h6 class="fw-bold"><i class="fas fa-gavel"></i> Dasar Hukum:</h6>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($fatwa['legal_basis'])) ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($fatwa['references'])): ?>
                        <hr class="my-4">
                        <h6 class="fw-bold"><i class="fas fa-book"></i> Referensi:</h6>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($fatwa['references'])) ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-share-alt"></i> Share
                            </button>
                        </div>
                        <a href="<?= url('/fatwa') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Fatwa Details -->
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Detail Fatwa</h6>
                    <?php if (!empty($fatwa['fatwa_number'])): ?>
                        <p class="mb-2"><strong>Nomor:</strong> <?= $fatwa['fatwa_number'] ?></p>
                    <?php endif; ?>
                    <p class="mb-2"><strong>Tanggal:</strong> <?= date('d F Y', strtotime($fatwa['published_at'] ?? $fatwa['created_at'])) ?></p>
                    <p class="mb-2"><strong>Kategori:</strong> <?= htmlspecialchars($fatwa['category_name'] ?? 'Umum') ?></p>
                    <p class="mb-0"><strong>Views:</strong> <?= $fatwa['view_count'] ?? 0 ?></p>
                </div>
            </div>
            
            <!-- Related Fatwa -->
            <?php if (!empty($related)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-link"></i> Fatwa Terkait</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($related as $rel): ?>
                            <a href="<?= url('/fatwa/detail/' . $rel['slug']) ?>" class="list-group-item list-group-item-action">
                                <h6 class="mb-1 small"><?= htmlspecialchars($rel['title']) ?></h6>
                                <?php if (!empty($rel['fatwa_number'])): ?>
                                    <span class="badge bg-secondary small"><?= $rel['fatwa_number'] ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Back to Category -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <a href="<?= url('/fatwa/category/' . $fatwa['category_id']) ?>" class="btn btn-outline-danger w-100">
                        <i class="fas fa-folder"></i> Lihat semua di <?= htmlspecialchars($fatwa['category_name'] ?? 'Umum') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.fatwa-content {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #333;
    text-align: justify;
}
</style>
