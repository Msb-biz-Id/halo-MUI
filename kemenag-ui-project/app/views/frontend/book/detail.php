<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <?php if (!empty($book['cover_image'])): ?>
                                <img src="<?= asset($book['cover_image']) ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($book['title']) ?>">
                            <?php else: ?>
                                <div class="bg-gradient rounded shadow d-flex align-items-center justify-content-center" 
                                     style="height: 400px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                    <i class="fas fa-book-open fa-5x text-white opacity-50"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-8">
                            <span class="badge bg-success-subtle text-success mb-2">
                                <?= htmlspecialchars($book['category_name']) ?>
                            </span>
                            
                            <h2 class="fw-bold mb-3"><?= htmlspecialchars($book['title']) ?></h2>
                            
                            <div class="mb-3">
                                <p class="mb-1"><strong><i class="fas fa-user text-primary"></i> Penulis:</strong> <?= htmlspecialchars($book['author']) ?></p>
                                <?php if (!empty($book['publisher'])): ?>
                                    <p class="mb-1"><strong><i class="fas fa-building text-info"></i> Penerbit:</strong> <?= htmlspecialchars($book['publisher']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($book['year'])): ?>
                                    <p class="mb-1"><strong><i class="fas fa-calendar text-warning"></i> Tahun:</strong> <?= $book['year'] ?></p>
                                <?php endif; ?>
                                <?php if (!empty($book['pages'])): ?>
                                    <p class="mb-1"><strong><i class="fas fa-file-alt text-secondary"></i> Halaman:</strong> <?= $book['pages'] ?> halaman</p>
                                <?php endif; ?>
                                <?php if (!empty($book['isbn'])): ?>
                                    <p class="mb-1"><strong><i class="fas fa-barcode text-danger"></i> ISBN:</strong> <?= $book['isbn'] ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex gap-2 mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-download"></i> <?= $book['download_count'] ?? 0 ?> downloads
                                </small>
                            </div>
                            
                            <?php if ($book['is_available']): ?>
                                <div class="d-grid gap-2">
                                    <?php if (!empty($book['pdf_file'])): ?>
                                        <a href="<?= asset($book['pdf_file']) ?>" class="btn btn-success btn-lg" download>
                                            <i class="fas fa-download"></i> Download PDF
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($book['external_url'])): ?>
                                        <a href="<?= $book['external_url'] ?>" class="btn btn-outline-primary" target="_blank">
                                            <i class="fas fa-external-link-alt"></i> Lihat Online
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Buku ini sementara tidak tersedia untuk diunduh
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle text-info"></i> Deskripsi Buku</h5>
                    <div class="book-description">
                        <?= nl2br(htmlspecialchars($book['description'])) ?>
                    </div>
                    
                    <?php if (!empty($book['tags'])): ?>
                        <hr class="my-4">
                        <div>
                            <strong><i class="fas fa-tags"></i> Tags:</strong>
                            <?php
                            $tags = explode(',', $book['tags']);
                            foreach ($tags as $tag):
                            ?>
                                <span class="badge bg-secondary-subtle text-secondary me-1"><?= trim($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-share-alt"></i> Share
                            </button>
                            <button class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-bookmark"></i> Simpan
                            </button>
                        </div>
                        <a href="<?= url('/book') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Book Info Card -->
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Detail Buku</h6>
                    <p class="mb-2"><strong>Kategori:</strong> <?= htmlspecialchars($book['category_name']) ?></p>
                    <?php if (!empty($book['year'])): ?>
                        <p class="mb-2"><strong>Tahun Terbit:</strong> <?= $book['year'] ?></p>
                    <?php endif; ?>
                    <?php if (!empty($book['pages'])): ?>
                        <p class="mb-2"><strong>Jumlah Halaman:</strong> <?= $book['pages'] ?></p>
                    <?php endif; ?>
                    <p class="mb-2"><strong>Status:</strong> 
                        <?php if ($book['is_available']): ?>
                            <span class="badge bg-success">Available</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Unavailable</span>
                        <?php endif; ?>
                    </p>
                    <p class="mb-0"><strong>Downloads:</strong> <?= $book['download_count'] ?? 0 ?></p>
                </div>
            </div>
            
            <!-- Related Books -->
            <?php if (!empty($related)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-link"></i> Buku Terkait</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($related as $rel): ?>
                            <a href="<?= url('/book/detail/' . $rel['slug']) ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex">
                                    <?php if (!empty($rel['cover_image'])): ?>
                                        <img src="<?= asset($rel['cover_image']) ?>" alt="Cover" style="width: 40px; height: 60px; object-fit: cover; margin-right: 10px;">
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-1 small"><?= htmlspecialchars($rel['title']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($rel['author']) ?></small>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Category Link -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <a href="<?= url('/book/category/' . $book['category_id']) ?>" class="btn btn-outline-success w-100">
                        <i class="fas fa-folder"></i> Lihat semua di <?= htmlspecialchars($book['category_name']) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.book-description {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #333;
    text-align: justify;
}
</style>
