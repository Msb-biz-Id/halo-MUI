<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="fw-bold mb-2"><i class="fas fa-balance-scale text-danger"></i> Informasi Fatwa</h2>
            <p class="text-muted">Kumpulan fatwa dan keputusan keagamaan resmi</p>
        </div>
    </div>
    
    <!-- Categories -->
    <?php if (!empty($categories)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-folder"></i> Kategori Fatwa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($categories as $category): ?>
                                <div class="col-md-4">
                                    <a href="<?= url('/fatwa/category/' . $category['id']) ?>" 
                                       class="btn btn-outline-danger w-100 text-start">
                                        <?= htmlspecialchars($category['name']) ?>
                                        <span class="badge bg-danger float-end"><?= $category['fatwa_count'] ?? 0 ?></span>
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
        <!-- Latest Fatwa -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Fatwa Terbaru</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (!empty($fatwas)): ?>
                        <?php foreach ($fatwas as $fatwa): ?>
                            <a href="<?= url('/fatwa/detail/' . $fatwa['slug']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0"><?= htmlspecialchars($fatwa['title']) ?></h6>
                                            <?php if (!empty($fatwa['fatwa_number'])): ?>
                                                <span class="badge bg-danger ms-2"><?= $fatwa['fatwa_number'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="mb-1 small text-muted">
                                            <?= truncate(strip_tags($fatwa['summary'] ?? $fatwa['content']), 150) ?>
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> <?= htmlspecialchars($fatwa['category_name'] ?? 'Umum') ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($fatwa['published_at'] ?? $fatwa['created_at'])) ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-eye"></i> <?= $fatwa['view_count'] ?? 0 ?> views
                                        </small>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada fatwa</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Popular Fatwa Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-fire"></i> Fatwa Populer</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (!empty($popular)): ?>
                        <?php foreach ($popular as $fat): ?>
                            <a href="<?= url('/fatwa/detail/' . $fat['slug']) ?>" class="list-group-item list-group-item-action">
                                <h6 class="mb-1 small"><?= htmlspecialchars($fat['title']) ?></h6>
                                <?php if (!empty($fat['fatwa_number'])): ?>
                                    <span class="badge bg-secondary small"><?= $fat['fatwa_number'] ?></span>
                                <?php endif; ?>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-eye"></i> <?= $fat['view_count'] ?? 0 ?> views
                                </small>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted">
                            No popular fatwa yet
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-info"></i> Tentang Fatwa</h6>
                    <p class="small mb-0">
                        Fatwa adalah pendapat atau keputusan hukum Islam yang dikeluarkan oleh lembaga yang berwenang dalam hal keagamaan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
